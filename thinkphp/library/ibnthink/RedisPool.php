<?php
namespace ibnthink;

use think\Config;

/**
 * redis对象池，配置的参数 [
 *      'host'=>'',
 *      'port'=>6379,
 *      'timeout'=>0, //单位 = 秒
 *      'persistent'=>false,
 *      'password'=>'',
 *      'select'=>0,
 *      'persistent_id'=>'',
 *      'prefix' => '',
 * ]
 * @author: dryangkun
 * @date: 2017/6/6 上午11:11
 */
class RedisPool {

    private static $_objs = [];

    /**
     * @param string $name
     * @return \Redis
     * @throws \Exception
     */
    public static function get($name = 'base') {
        $obj = self::$_objs[$name];
        if ($obj !== null) {
            return $obj;
        }

        $conf = Config::get("ibn_redis.{$name}");
        if ($conf === null) {
            throw new \InvalidArgumentException("config[ibn_redis][{$name}]不存在");
        }
        $obj = new \Redis;
        $old = error_reporting(0);
        if (IS_CLI || !$conf['persistent']) {
            $ret = $obj->connect($conf['host'], $conf['port'], $conf['timeout']);
        } else {
            // persistent_id = host + port + timeout or host + persistent_id
            $ret = $obj->pconnect($conf['host'], $conf['port'], $conf['timeout'], $conf['persistent_id']);
        }
        error_reporting($old);
        if ($ret === false) {
            throw new \Exception("config[ibn_redis][{$name}]connect失败, redis = {$conf['host']}:{$conf['port']}");
        }

        if (!empty($conf['password'])) {
            if ($obj->auth($conf['password']) === false) {
                throw new \Exception("config[ibn_redis][{$name}]auth失败, redis = {$conf['host']}:{$conf['port']}");
            }
        }
        if ($conf['select'] > 0) {
            if ($obj->select($conf['select']) === false) {
                throw new \Exception("config[ibn_redis][{$name}]select失败, redis = {$conf['host']}:{$conf['port']}");
            }
        }
        if (!empty($conf['prefix'])) {
            $obj->setOption(\Redis::OPT_PREFIX, $conf['prefix']);
        }

        return self::$_objs[$name] = $obj;
    }

    public static function close($name = 'base') {
        /**
         * @var $obj \Redis
         */
        if ($obj = self::$_objs[$name]) {
            $obj->close();
            unset(self::$_objs[$name]);
        }
    }

    public static function closeAll() {
        /**
         * @var $obj \Redis
         */
        foreach (self::$_objs as $obj) {
            $obj->close();
        }
        self::$_objs = [];
    }
}