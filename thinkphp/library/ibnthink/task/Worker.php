<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/8 下午12:12
 */

namespace ibnthink\task;

use ibnthink\task\connector\Connector;
use think\App;
use think\Log;
use think\log\driver\Ibnlog;

class Worker {

    private $workerKey;
    private $index;

    private $taskWorker;
    private $taskQueues;
    private $debug = false;

    public function __construct($workerKey, $index) {
        $this->workerKey = $workerKey;
        $this->index = $index;
        list($this->taskWorker, $this->taskQueues) = TaskUtils::getTaskWorkerAndQueuesByKey($workerKey);
        $this->debug = get_cfg_var('ibntask.debug') === '1';
    }

    /**
     * @return string
     */
    public function getWorkerKey() {
        return $this->workerKey;
    }

    /**
     * @return int
     */
    public function getIndex() {
        return $this->index;
    }

    /**
     * @return array
     */
    public function getTaskWorker() {
        return $this->taskWorker;
    }

    /**
     * @return array
     */
    public function getTaskQueues() {
        return $this->taskQueues;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool {
        return $this->debug;
    }

    public function start() {
        TaskUtils::clearConsole();
        if (!$this->debug) {
            swoole_set_process_name("ibntask-worker: {$this->workerKey} " . APP_PATH);
        }

        TaskUtils::$serverId = getmypid() . '@' . gethostname();
        App::$debug = $this->debug;
        Ibnlog::console($this->debug);
        Ibnlog::setName("ibntask_{$this->workerKey}");
        Ibnlog::setRequestId(TaskUtils::$serverId . ":worker{$this->index}");

        TaskUtils::setExceptionHandler();
        TaskUtils::setErrorHandler();
        Log::info('启动worker');

        $workerType = $this->taskWorker['type'];
        $connector = Connector::get($workerType, $this);
        $connector->recoverLastTask();
        $connector->start();
    }
}