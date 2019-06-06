<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/9 下午2:17
 */

namespace ibnthink\task;

use ibnthink\task\exception\TaskNoRetryException;
use think\Config;
use think\log\driver\Ibnlog;
use think\Request;

class TaskDispatcher {

    private $refCache = [];
    private $appNamespace;

    public function __construct() {
        $this->appNamespace = Config::get('app_namespace');
    }

    public function findMethod($path) {
        $tmp = explode('/', $path);
        $count = count($tmp);
        switch ($count) {
            case 2:
                $module = '';
                $class = "\\{$this->appNamespace}\\task\\" . ucfirst($tmp[0]);
                $method = $tmp[1];
                break;
            case 3:
                $module = $tmp[0];
                $class = "\\{$this->appNamespace}\\{$tmp[0]}\\task\\" . ucfirst($tmp[1]);
                $method = $tmp[2];
                break;
            default:
                throw new \Exception("invalid path = {$path}");
        }
        unset($tmp, $count);
        if (strncmp($method, '__', 2) == 0) {
            throw new \Exception("method can't startWith '__', path = {$path}");
        }

        $rClass = new \ReflectionClass($class);
        if ($rClass->isAbstract()) {
            throw new \InvalidArgumentException("class is abstract, path = {$path}");
        }
        if (!$rClass->isSubclassOf(TaskCommand::class)) {
            throw new \InvalidArgumentException("class is not subclass of TaskCommand, path = {$path}");
        }
        $rMethod = $rClass->getMethod($method);
        if ($rMethod->getModifiers() & \ReflectionMethod::IS_STATIC) {
            throw new \InvalidArgumentException("method is static, path = {$path}");
        }
        if (!($rMethod->getModifiers() & \ReflectionMethod::IS_PUBLIC)) {
            throw new \InvalidArgumentException("method is not public, path = {$path}");
        }
        /**
         * @var $rParameter \ReflectionParameter
         */
        foreach ($rMethod->getParameters() as $rParameter) {
            if ($rParameter->getClass() !== null) {
                throw new \InvalidArgumentException("参数({$rParameter->getName()})不能指定class类型, path = {$path}");
            }
            if ($rParameter->isPassedByReference()) {
                throw new \InvalidArgumentException("参数({$rParameter->getName()})不能是引用传参, path = {$path}");
            }
        }
        return [$rClass, $rMethod, $module];
    }

    public function dispatchForConnector($path, array $paramValues) {
        if (!isset($this->refCache[$path])) {
            try {
                $ref = $this->findMethod($path);
                $this->refCache[$path] = $ref;
            } catch (\InvalidArgumentException $e) {
                $this->refCache[$path] = $e->getMessage();
                throw new TaskNoRetryException($e->getMessage());
            } catch (\ReflectionException $e) {
                $this->refCache[$path] = $e->getMessage();
                throw new TaskNoRetryException($e->getMessage());
            }
            if (count($this->refCache) > 20) {
                array_shift($this->refCache);
            }
        } else {
            $ref = $this->refCache[$path];
            unset($this->refCache[$path]);
            $this->refCache[$path] = $ref;
        }

        if (!is_array($ref)) {
            throw new TaskNoRetryException($ref);
        } else {
            /**
             * @var $rClass \ReflectionClass
             * @var $rMethod \ReflectionMethod
             * @var $obj TaskCommand
             */
            list($rClass, $rMethod, $module) = $ref;
            //会在Connector中重置
            Request::instance()->module($module);

            $obj = $rClass->newInstance();
            try {
                ConfigReload::instance()->setModuleConfig($module);
                $rMethod->invokeArgs($obj, $paramValues);
            } catch (\TypeError $e) {
                throw new TaskNoRetryException($e->getMessage(), $e->getCode(), $e);
            } finally {
                ConfigReload::instance()->resetModuleConfig();
            }
        }
    }

    public function dispatchForCli($path, array $paramValues) {
        /**
         * @var $rMethod \ReflectionMethod
         */
        list($rClass, $rMethod, $module) = $this->findMethod($path);

        $rParameters = $rMethod->getParameters();
        foreach ($paramValues as $i => &$paramValue) {
            if (($rParameter = $rParameters[$i]) === null) {
                continue;
            }
            if ($rParameter->isVariadic() || !$rParameter->isArray() ||
                is_array($paramValue) || $paramValue === '') {
                continue;
            }
            $tmp = json_decode($paramValue, true);
            if (is_array($tmp)) {
                $paramValue = $tmp;
            } else {
                $paramValue = explode(',', $paramValue);
            }
        }
        unset($paramValue);

        $oldRequestId = Ibnlog::getRequestId();
        Ibnlog::setRequestId(sprintf('%s:%x', $oldRequestId, microtime(true) * 10000));
        Request::instance()->module($module);

        $obj = $rClass->newInstance();
        try {
            ConfigReload::instance()->setModuleConfig($module);
            $rMethod->invokeArgs($obj, $paramValues);
        } finally {
            ConfigReload::instance()->resetModuleConfig();
            Request::instance()->module('');
            Ibnlog::setRequestId($oldRequestId);
        }
    }
}