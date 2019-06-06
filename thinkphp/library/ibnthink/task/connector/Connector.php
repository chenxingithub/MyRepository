<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/8 下午4:24
 */

namespace ibnthink\task\connector;

use ibnthink\task\exception\TaskNoRetryException;
use ibnthink\task\exception\TaskRetryException;
use ibnthink\task\TaskCommand;
use ibnthink\task\TaskDispatcher;
use ibnthink\task\TaskUtils;
use ibnthink\task\Worker;
use think\Log;
use think\log\driver\Ibnlog;
use think\Request;

abstract class Connector {

    public static function get($workerType, Worker $worker):Connector {
        switch ($workerType) {
            case 'redis':
                return new RedisConnector($worker);
            default:
                throw new \Exception("not support connector type = {$workerType}");
        }
    }

    protected $worker;
    protected $taskDispatcher;
    protected $keepFile;

    public function __construct(Worker $worker) {
        $this->worker = $worker;
        $this->taskDispatcher = new TaskDispatcher();
        $this->keepFile = RUNTIME_PATH . "ibntask-{$this->worker->getWorkerKey()}-{$this->worker->getIndex()}.lasttask";
    }

    public function recoverLastTask() {
        if (!is_file($this->keepFile)) {
            return;
        }
        $task = file_get_contents($this->keepFile);
        if (!empty($task)) {
            Log::info('connector recover task');
            $this->handle($task, true);
        }
    }

    public abstract function start();

    protected function saveCurrentTask($task) {
        file_put_contents($this->keepFile, $task);
    }

    protected function removeCurrentTask() {
        file_put_contents($this->keepFile, '');
    }

    protected abstract function submitRetryTask($task, $delay);

    protected function handle($task, $noSave = false) {
        $data = \swoole_serialize::unpack($task);
        if (!is_array($data) || count($data) < 2) {
            Log::alert('element invalid = ' . json_encode($data));
            return;
        }
        list($path, $paramValues, $retryNum, $retryPeriod, $retryTimes) = $data;
        if (empty($path) || !is_array($paramValues)) {
            Log::alert('no path or paramValues = ' . json_encode($data));
            return;
        }
        if ($noSave) {
            $this->saveCurrentTask($task);
        }

        $oldRequestId = Ibnlog::getRequestId();
        Ibnlog::setRequestId(sprintf('%s:%x', $oldRequestId, microtime(true) * 10000));
        try {
            TaskCommand::$currTaskPath = $path;
            if ($retryTimes !== null) {
                TaskCommand::$isLastRetry = $retryTimes == $retryNum;
            }
            $this->taskDispatcher->dispatchForConnector($path, $paramValues);
        } catch (TaskNoRetryException $e) {
            TaskUtils::ibnerror(("task({$path})失败, {$e->getMessage()}"), false);
            Log::error("task({$path})失败, {$e->getMessage()}\n{$e->getTraceAsString()}");
        } catch (\Throwable $t) {
            $retryTimes = (int)$retryTimes;
            if ($retryNum === null && $t instanceof TaskRetryException) {
                $retryNum = $t->retries;
                $retryPeriod = $t->retryPeriod;
                $paramValues = $t->paramValues ?? $paramValues;
            }
            if ($retryTimes < $retryNum) {
                if (is_array($retryPeriod)) {
                    $cnt = count($retryPeriod);
                    $delay = (int)$retryPeriod[$retryTimes < $cnt ? $retryTimes : $cnt - 1];
                } else {
                    $delay = $retryPeriod;
                }
                Log::alert("task({$path})重试, {$t->getMessage()}");
                $data = [$path, $paramValues, $retryNum, $retryPeriod, ++$retryTimes];
                try {
                    $this->submitRetryTask(\swoole_serialize::pack($data), $delay);
                } catch (\Exception $e) {
                    TaskUtils::ibnerror("task({$path})提交重试任务失败, {$e->getMessage()}", false);
                    Log::error("task({$path})提交重试任务失败, {$e->getMessage()}\n{$e->getTraceAsString()}");
                }
            } else {
                TaskUtils::ibnerror("task({$path})失败, {$t->getMessage()}", false);
                Log::error("task({$path})失败, {$t->getMessage()}\n{$t->getTraceAsString()}");
            }
        } finally {
            Request::instance()->module('');
            Ibnlog::setRequestId($oldRequestId);

            TaskCommand::$currTaskPath = TaskCommand::$isLastRetry = null;
            $this->removeCurrentTask();
        }
    }
}