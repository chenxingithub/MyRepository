<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/8 下午4:24
 */

namespace ibnthink\task\connector;

use ibnthink\task\client\RedisTaskClient;
use ibnthink\task\ConfigReload;
use ibnthink\task\RedisClient;
use ibnthink\task\Worker;
use think\Log;

class RedisConnector extends Connector {

    private $redisClient;
    private $redisQueues;
    private $pollingTimeout;
    private $taskClients = [];

    private $stopping = false;

    public function __construct(Worker $worker) {
        parent::__construct($worker);
        foreach ($worker->getTaskQueues() as $k => $taskQueue) {
            $key = "ibntaskq_{$k}";
            $this->redisQueues[] = $key;
            $this->taskClients[$key] = new RedisTaskClient($k, $taskQueue);
        }
        $this->pollingTimeout = $worker->getTaskWorker()['polling_timeout'] ?? 5;
        $this->redisClient = new RedisClient($worker->getTaskWorker()['ibn_redis_key']);
    }

    public function __pop(\Redis $redis) {
        return $redis->blPop($this->redisQueues, $this->pollingTimeout);
    }

    private $currQueueKey;

    public function __handleSignal($signo) {
        Log::info('redis connector开始停止 = ' . $signo);
        $this->stopping = true;
    }

    public function start() {
        pcntl_signal(SIGTERM, [$this, '__handleSignal']);

        $lastConfigReloadTime = time();
        while (!$this->stopping) {
            $item = $this->redisClient->exec([$this, '__pop']);
            if (!empty($item)) {
                $this->currQueueKey = $item[0];
                $this->handle($item[1]);
            }
            pcntl_signal_dispatch();
            $now = time();
            if ($now - $lastConfigReloadTime >= 30) {
                $lastConfigReloadTime = $now;
                ConfigReload::instance()->reload();
            }
        }
        Log::info('redis connector完成停止');
    }

    protected function submitRetryTask($task, $delay) {
        $this->redisClient->exec(function(\Redis $redis) use($task, $delay) {
            /**
             * @var $taskClient RedisTaskClient
             */
            $taskClient = $this->taskClients[$this->currQueueKey];
            $taskClient->__internalSubmitDelay($task, $delay, $redis);
        });
    }
}