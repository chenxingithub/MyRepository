<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/8 下午3:22
 */

namespace ibnthink\task;

use ibnthink\RedisPool;
use think\Log;
use think\log\driver\Ibnlog;

class WorkerInfo {

    public $exitCounter = 0;
    public $lastExitTime = -1;
    public $index;

    private $workerKey;
    private $debug;

    public function __construct($index, $workerKey, $debug) {
        $this->index = $index;
        $this->workerKey = $workerKey;
        $this->debug = $debug;
    }

    public function startWorker(\swoole_process $process) {
        RedisPool::closeAll();
        $r = new \ReflectionClass(Log::class);
        $p = $r->getProperty('driver');
        $p->setAccessible(true);
        $driver = $p->getValue();
        if ($driver instanceof Ibnlog) {
            $driver->__destruct();
        }
        $bin = getenv('_');
        if (!preg_match('/(?:^|\/)php$/', $bin)) {
            $bin = '/usr/local/bin/php';
        }

        $args = [
            '-d',
            'ibntask.debug=' . ($this->debug ? '1' : '0'),
            $_SERVER['argv'][0],
            'task:worker',
            '--worker=' . $this->workerKey,
            '--index=' . $this->index
        ];
        if ($process->exec($bin, $args) === false) {
            echo "{$bin} " . implode(' ', $args) . " fail\n";
            $process->exit(1);
        }
    }
}