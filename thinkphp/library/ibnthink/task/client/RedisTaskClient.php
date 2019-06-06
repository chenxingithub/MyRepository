<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/11 下午2:51
 */

namespace ibnthink\task\client;

use ibnthink\RedisPool;

class RedisTaskClient extends TaskClient {

    private $ibnRedisKey;
    private $delayTaskConcurrency;

    public function __construct($key, array $taskQueue) {
        parent::__construct($key, $taskQueue);
        $this->ibnRedisKey = $taskQueue['ibn_redis_key'];
        $this->delayTaskConcurrency = $taskQueue['delay_task_concurrency'] ?? 4;
    }

    protected function _submitDelay(int $delay, $path, array $paramValues = [], $retryNum = null, $retryPeriod = null) {
        $task = \swoole_serialize::pack([$path, $paramValues, $retryNum, $retryPeriod]);
        $this->__internalSubmitDelay($task, $delay, RedisPool::get($this->ibnRedisKey));
    }

    public function __internalSubmitDelay($task, int $delay, \Redis $redis) {
        if ($delay <= 0) {
            $key = "ibntaskq_{$this->key}";
            if ($redis->rPush($key, $task) === false) {
                throw new \Exception("rpush {$key} fail = {$redis->getLastError()}");
            }
        } else {
            $i = sprintf('%u', crc32($task)) % $this->delayTaskConcurrency;
            $key = "ibntaskz_{$this->key}_{$i}";
            if ($redis->zAdd($key, time() + $delay, $task) === false) {
                throw new \Exception("zadd {$key} fail = {$redis->getLastError()}");
            }
        }
    }
}