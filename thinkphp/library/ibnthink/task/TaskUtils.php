<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/8 上午11:13
 */

namespace ibnthink\task;

use ibnthink\task\exception\ExitException;
use think\App;
use think\Config;
use think\Console;
use think\Log;

class TaskUtils {

    public static $serverId;

    public static function clearConsole() {
        $console = Console::init(false);
        $r = new \ReflectionClass(Console::class);
        foreach (['commands', 'definition'] as $v) {
            $p = $r->getProperty($v);
            $p->setAccessible(true);
            $p->setValue($console, null);
        }
    }

    public static function setExceptionHandler() {
        set_exception_handler(function (\Throwable $t) {
            Log::error('未catch异常' . get_class($t) . ", {$t->getMessage()}\n{$t->getTraceAsString()}");
            if ($t instanceof ExitException) {
                exit($t->getCode());
            }
        });
    }

    public static function setErrorHandler() {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            if (error_reporting() & $errno) {
                Log::error("PHPERROR [$errno]{$errstr}, file:{$errfile}({$errline}");
            }
            return true;
        });
    }

    public static function ibnerror($msg, $log = true) {
        if ($log) {
            Log::error($msg);
        }
        if (!App::$debug) {
            ibnerror($msg);
        }
    }

    public static function getTaskWorkerAndQueuesByKey($workerKey) {
        $taskWorkers = Config::get('ibn_task_workers');
        if (empty($taskWorkers) || !is_array($taskWorkers)) {
            throw new \Exception('config[ibn_task_workers]不能为空');
        }
        $taskQueues = Config::get('ibn_task_queues');
        if (empty($taskQueues) || !is_array($taskQueues)) {
            throw new \Exception('config[ibn_task_queues]不能为空');
        }
        if (($taskWorker = $taskWorkers[$workerKey]) === null) {
            throw new \Exception("在config[ibn_task_workers]中不存在worker = {$workerKey}");
        }

        $taskWorkerQueues = [];
        $workerType = $taskWorker['type'];
        $redisKey = $taskWorker['ibn_redis_key'];
        foreach ($taskWorker['queues'] as $queueKey) {
            if (($taskQueue = $taskQueues[$queueKey]) === null) {
                throw new \Exception("在config[ibn_task_queues]中不存在queue = {$queueKey}");
            }
            if ($taskQueue['type'] !== $workerType) {
                throw new \Exception("queue({$queueKey})的type({$taskQueue['type']})与worker的type({$workerType})不一致");
            }
            if ($workerType === 'redis') {
                if ($taskQueue['ibn_redis_key'] !== $redisKey) {
                    throw new \Exception("queue({$queueKey})的ibn_redis_key({$taskQueue['ibn_redis_key']})与worker的的ibn_redis_key({$redisKey})不一致");
                }
            }
            $taskWorkerQueues[$queueKey] = $taskQueue;
        }

        return [$taskWorker, $taskWorkerQueues];
    }
}