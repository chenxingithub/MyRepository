<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/8 下午5:07
 */

namespace ibnthink\task;

use ibnthink\RedisPool;
use think\Cache;
use think\cache\Driver;
use think\cache\driver\Memcache;
use think\cache\driver\Memcached;
use think\cache\driver\Redis;
use think\cache\driver\Sqlite;
use think\Config;
use think\Db;
use think\db\Connection;
use think\Hook;
use think\Lang;
use think\Log;
use think\Request;

class ConfigReload {

    public static function instance() {
        static $obj = null;
        if ($obj !== null) {
            return $obj;
        }
        return $obj = new ConfigReload();
    }

    /**
     * @var array[ "key" => [string func, string unique, array config] ]
     */
    private $watchedConfigKeys = [
        'database' => ['closeDatabase'],
        'cache' => ['closeCache'],
        'ibn_redis' => ['closeIbnRedis'],
    ];

    private $moduleConfigs = [];
    private $refConfig;
    private $oldConfigValue;

    private $lastDbTime;
    private $lastDbExecutes = 0;

    private function __construct() {
        foreach ($this->watchedConfigKeys as $k => &$v) {
            $config = Config::get($k);
            $v[1] = md5(serialize($config));
            $v[2] = $config;
        }
        unset($v);
        $this->refConfig = (new \ReflectionClass(Config::class))->getProperty('config');
        $this->refConfig->setAccessible(true);
        $this->lastDbTime = time();
    }

    public function reload() {
//        Log::info('开始重载配置');
        clearstatcache();
        $this->loadConfig();

        $closedDb = false;
        foreach ($this->watchedConfigKeys as $k => &$v) {
            $config = Config::get($k);
            $unique = md5(serialize($config));
            if ($v[1] !== $unique) {
                $v[1] = $unique;
                $v[2] = $config;
                $func = $v[0];
                $this->$func();
                if ($k === 'database') {
                    $closedDb = true;
                }
            }
        }
        unset($v);
        Hook::listen('ibntask_config_reload');
        foreach ($this->moduleConfigs as $k => &$v) {
            $oldConfigValue = $this->refConfig->getValue();
            $this->loadConfig($k);
            foreach ($this->watchedConfigKeys as $k1 => $v1) {
                Config::set($k1, $v1[2]);
            }
            $v = $this->refConfig->getValue();
            Hook::listen("ibntask_config_reload:{$k}");
            $this->refConfig->setValue(null, $oldConfigValue);
        }
        unset($v);

        //如果十分钟内没有database查询，则关闭database
        $now = time();
        $dbExecutes = Db::$queryTimes + Db::$executeTimes;
        if ($closedDb) {
            $this->lastDbTime = $now;
            $this->lastDbExecutes = $dbExecutes;
        } elseif ($now - $this->lastDbTime >= 600) {
            $this->lastDbTime = $now;
            if ($this->lastDbExecutes == $dbExecutes) {
                $this->closeDatabase();
            } else {
                $this->lastDbExecutes = $dbExecutes;
            }
        }
    }

    public function closeDatabase() {
        Log::info('开始close database');
        $r = new \ReflectionClass(Db::class);
        $p = $r->getProperty('instance');
        $p->setAccessible(true);

        $instances = $p->getValue();
        $k = md5(serialize([]));
        /**
         * @var $conn Connection
         */
        if ($conn = $instances[$k]) {
            $conn->__destruct();
            unset($instances[$k]);
        }
        $p->setValue(null, $instances);
    }

    public function closeCache() {
        Log::info('开始close cache');
        $r = new \ReflectionClass(Cache::class);
        $p1 = $r->getProperty('instance');
        $p2 = $r->getProperty('handler');
        $p1->setAccessible(true);
        $p2->setAccessible(true);

        $instances = $p1->getValue();
        /**
         * @var $instance Driver
         */
        foreach ($instances as $instance) {
            $handler = $instance->handler();
            if ($handler === null) {
                continue;
            }
            if ($instance instanceof Memcache) {
                $handler->close();
            } elseif ($instance instanceof Memcached) {
                $handler->quit();
            } elseif ($instance instanceof Redis) {
                $handler->close();
            } elseif ($instance instanceof Sqlite) {
                sqlite_close($handler);
            }
        }
        $p1->setValue(null, []);
        $p2->setValue(null, null);
    }

    public function closeIbnRedis() {
        Log::info('开始close ibn_redis');
        RedisPool::closeAll();
    }

    public function setModuleConfig($module = '') {
        $this->oldConfigValue = $this->refConfig->getValue();
        if ($module === '') {
            return;
        } elseif ($moduleConfig = $this->moduleConfigs[$module]) {
            $this->refConfig->setValue(null, $moduleConfig);
        } else {
            self::loadConfig($module, false);
            foreach ($this->watchedConfigKeys as $k => $v) {
                Config::set($k, $v[2]);
            }
            $this->moduleConfigs[$module] = $this->refConfig->getValue();
        }
    }

    public function resetModuleConfig() {
        if ($this->oldConfigValue !== null) {
            $this->refConfig->setValue(null, $this->oldConfigValue);
        }
    }

    private $lastInitFileLoadedTimes = [];

    private function loadConfig($module = '', $onlyConf = true) {
        $module = $module ? $module . DS : '';
        $initFiles = [
            APP_PATH . $module . 'init' . EXT,
            RUNTIME_PATH . $module . 'init' . EXT
        ];
        foreach ($initFiles as $initFile) {
            if (is_file($initFile) && ($newMtime = filemtime($initFile))) {
                if ($oldMtime = $this->lastInitFileLoadedTimes[$initFile]) {
                    if ($oldMtime === $newMtime) {
                        return;
                    }
                }

                if ($onlyConf) {
                    $buffer = null;
                    $success = false;
                    $fp = fopen($initFile, 'r');
                    while (!feof($fp)) {
                        $line = fgets($fp);
                        if (strpos($line, '\think\Config::set(') === 0) {
                            $buffer = [$line];
                        } elseif ($buffer !== null) {
                            $buffer[] = $line;
                            if (strpos($line, '));') === 0) {
                                $success = true;
                                break;
                            }
                        }
                    }
                    fclose($fp);
                    if ($success) {
                        eval(implode("\n", $buffer));
                        $this->lastInitFileLoadedTimes[$initFile] = $newMtime;
                        return;
                    }
                } else {
                    include $initFile;
                    $this->lastInitFileLoadedTimes[$initFile] = $newMtime;
                    return;
                }
                break;
            }
        }

        $path = APP_PATH . $module;
        $config = Config::load(CONF_PATH . $module . 'config' . CONF_EXT);
        $filename = CONF_PATH . $module . 'database' . CONF_EXT;
        Config::load($filename, 'database');
        if (is_dir(CONF_PATH . $module . 'extra')) {
            $dir = CONF_PATH . $module . 'extra';
            $files = scandir($dir);
            foreach ($files as $file) {
                if (strpos($file, CONF_EXT)) {
                    $filename = $dir . DS . $file;
                    Config::load($filename, pathinfo($file, PATHINFO_FILENAME));
                }
            }
        }
        if ($config['app_status']) {
            Config::load(CONF_PATH . $module . $config['app_status'] . CONF_EXT);
        }
        if (!$onlyConf) {
            if (is_file(CONF_PATH . $module . 'tags' . EXT)) {
                Hook::import(include CONF_PATH . $module . 'tags' . EXT);
            }
            if (is_file($path . 'common' . EXT)) {
                include $path . 'common' . EXT;
            }
            if ($module) {
                Lang::load($path . 'lang' . DS . Request::instance()->langset() . EXT);
            }
        }
    }
}