<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/8 下午1:04
 */

namespace ibnthink\task;

use think\Log;

class RedisDelayChecker {

    private $ibnRedisKey;
    private $zsetKeys;
    private $zset2List;

    private $hostname;
    private $rebalanceKey;
    private $redisClient;

    private $closed = false;

    public function __construct(Server $server) {
        $this->zsetKeys = [];
        foreach ($server->getTaskQueues() as $k => $taskQueue) {
            $this->ibnRedisKey = $taskQueue['ibn_redis_key'];
            $delayTaskConcurrency = $taskQueue['delay_task_concurrency'] ?? 4;
            for ($i = 0; $i < $delayTaskConcurrency; $i++) {
                $zsetKey = "ibntaskz_{$k}_{$i}";
                $this->zsetKeys[] = $zsetKey;
                $this->zset2List[$zsetKey] = "ibntaskq_{$k}";
            }
        }
        sort($this->zsetKeys);
        $this->hostname = get_cfg_var('ibntask.hostname');
        if (empty($this->hostname)) {
            $this->hostname = gethostname();
        }
        $this->rebalanceKey = "ibntask_rb_{$server->getWorkerKey()}";
        $this->redisClient = new RedisClient($this->ibnRedisKey);
    }

    private function rebalance(\Redis $redis) {
        $old = error_reporting(0);
        $ret = $redis->multi(\Redis::PIPELINE)
            ->hSet($this->rebalanceKey, $this->hostname, time())
            ->hGetAll($this->rebalanceKey)
            ->exec();
        error_reporting($old);

        if (empty($ret) || $ret[0] === false || !is_array($ret[1]) || empty($ret[1])) {
            throw new \Exception("rebalance hset/hgetall {$this->rebalanceKey} fail={$redis->getLastError()}");
        }

        $servers = [];
        $brokenServers = [];
        $now = time();
        foreach ($ret[1] as $server => $time) {
            $servers[] = $server;
            if ($now - $time > 15) {
                $brokenServers[$server] = 1;
            }
        }
        sort($servers);
        $cnt = count($servers);
        if (!empty($brokenServers)) {
            Log::info("rebalance servers = " . json_encode($servers) . ', brokens = ' . json_encode($brokenServers));
        }

        $zsetKeys = [];
        $prefix = $redis->getOption(\Redis::OPT_PREFIX);
        foreach ($this->zsetKeys as $i => $key) {
            $server = $servers[$i % $cnt];
            if ($server === $this->hostname || $brokenServers[$server]) {
                $lockKey = "{$prefix}{$key}_lock";
                if ($redis->rawCommand('set', $lockKey, $this->hostname, 'ex', 15, 'nx')) {
                    $zsetKeys[] = $key;
                } elseif ($redis->get($lockKey) == $this->hostname) {
                    $redis->expire($lockKey, 15);
                    $zsetKeys[] = $key;
                }
            }
        }
        return $zsetKeys;
    }

    public function __push(\Redis $redis) {
        static $options = ['limit' => [0, 20]];
        $zsetKeys = $this->rebalance($redis);

        $now = time();
        $total = 0;
        while (!empty($zsetKeys)) {
            foreach ($zsetKeys as $i => $key) {
                $vals = $redis->zRangeByScore($key, '-inf', $now, $options);
                if ($vals === false) {
                    throw new \Exception("zrangebyscore {$key} fail={$redis->getLastError()}");
                }
                $cnt = count($vals);
                if ($cnt > 0) {
                    if ($redis->rPush($this->zset2List[$key], ...$vals) === false) {
                        throw new \Exception("rpush {$this->zset2List[$key]} fail={$redis->getLastError()}");
                    }
                    if ($redis->zDelete($key, ...$vals) === false) {
                        throw new \Exception("zdelete {$key} fail={$redis->getLastError()}");
                    }
                }
                $total += $cnt;
                if ($cnt < 20) {
                    unset($zsetKeys[$i]);
                }
                if (time() - $now > 5) {
                    break 2;
                }
            }
        }
        if ($total > 0) {
            Log::info("push延迟任务总数 = {$total}");
        }
    }

    public function run() {
        swoole_timer_tick(5000, function ($timerId) {
            if ($this->closed) {
                swoole_timer_clear($timerId);
                return;
            }
            try {
                $this->redisClient->exec([$this, '__push']);
            } catch (\Exception $e) {
                TaskUtils::ibnerror("延迟任务检查失败, {$e->getMessage()}", false);
                Log::error("延迟任务检查失败, {$e->getMessage()}\n{$e->getTraceAsString()}");
            }
        });
    }

    public function close() {
        $this->closed = true;
    }
}