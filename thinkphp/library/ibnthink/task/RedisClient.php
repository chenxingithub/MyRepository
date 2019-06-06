<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/8 下午9:14
 */

namespace ibnthink\task;

use ibnthink\RedisPool;
use think\Config;
use think\Log;

class RedisClient {

    private $ibnRedisKey;
    private $unreachableCounter = 0;
    private $lastErrorTime = -1;

    public function __construct($ibnRedisKey) {
        $this->ibnRedisKey = $ibnRedisKey;
    }

    public function exec(callable $callable) {
        $tries = 3;
        while ($tries-- > 0) {
            try {
                $redis = RedisPool::get($this->ibnRedisKey);
                if ($redis->getOption(\Redis::OPT_READ_TIMEOUT) < 60) {
                    $redis->setOption(\Redis::OPT_READ_TIMEOUT, 60);
                }
                return call_user_func($callable, $redis);
            } catch (\Exception $e) {
                if (!($e instanceof \RedisException) &&
                    strpos($e->getMessage(), 'connect失败') === false) {
                    throw $e;
                }
                RedisPool::close($this->ibnRedisKey);
                if ($tries == 0) {
                    $now = time();
                    $conf = Config::get("ibn_redis.{$this->ibnRedisKey}");
                    if ((++$this->unreachableCounter) >= 20) {
                        if ($this->lastErrorTime === -1 || $now - $this->lastErrorTime > 1800) {
                            TaskUtils::ibnerror("redis已经30分钟内超过20次无法连接 = {$this->ibnRedisKey}({$conf['host']}:{$conf['port']})");
                            $this->lastErrorTime = $now;
                            $this->unreachableCounter = 0;
                            return false;
                        }
                    }
                    Log::error("redis-client连无法接 = {$this->ibnRedisKey}({$conf['host']}:{$conf['port']})");
                    return false;
                } else {
                    sleep(1);
                }
            }
        }
        return false;
    }
}