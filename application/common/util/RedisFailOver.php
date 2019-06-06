<?php
/**
 * 基于redis的failover辅助类
 * @author: dryangkun
 * @date: 2018/1/8 下午7:33
 */

namespace app\common\util;

use ibnthink\RedisPool;
use think\Log;

class RedisFailOver {

    private $prefix;
    private $minFailCount;
    private $maxFailRate;
    private $period;

    private $redisKey;
    /**
     * @var RedisPool
     */
    private $redisPool;

    public function __construct($prefix, $minFailCount, $maxFailRate, $period = 5, $redisKey = null) {
        $this->prefix = $prefix;
        $this->minFailCount = $minFailCount;
        $this->maxFailRate = $maxFailRate;
        $this->period = $period;
        $this->redisKey = $redisKey;
    }

    private function getRedisPool() {
        if ($this->redisPool === null) {
            $this->redisPool = $this->redisKey === null ? RedisPool::get() : RedisPool::get($this->redisKey);
        }
        return $this->redisPool;
    }

    private function makeKey($str, $time) {
        $minute = date('YmdHi', $time);
        $minute = $minute - $minute % $this->period;
        return  "{$this->prefix}-{$minute}-{$str}";
    }

    public function shouldSkip($str, $time) {
        $key = $this->makeKey($str, $time);
        $tmp = $this->getRedisPool()->hGetAll($key);
        if (!empty($tmp)) {
            $failCount = (int)$tmp['0'];
            $succCount = (int)$tmp['1'];
            if ($failCount >= $this->minFailCount && $failCount / ($failCount + $succCount) >= $this->maxFailRate) {
                Log::record("[wx_shouldSkip] str={$str} time={$time} failcount={$failCount} successCount={$succCount}");
                return true;
            }
        }
        return false;
    }

    public function increment($str, $time, $succOrFail) {
        $key = $this->makeKey($str, $time);
        $subKey = !empty($succOrFail) ? '1' : '0';
        $ret = $this->getRedisPool()->hIncrBy($key, $subKey, 1);
        if ($ret === 1) {
            $this->getRedisPool()->expire($key, $this->period * 60 + 30);
        }
    }
}