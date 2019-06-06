<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/11 下午2:50
 */

namespace ibnthink\task;

use ibnthink\task\client\TaskClient;
use think\Config;

class TaskClientPool {

    private static $_objs = [];

    /**
     * @param $queue
     * @return client\TaskClient
     */
    public static function get($queue) {
        if (isset(self::$_objs[$queue])) {
            return self::$_objs[$queue];
        }
        $taskQueue = Config::get("ibn_task_queues.{$queue}");
        if (empty($taskQueue)) {
            throw new \InvalidArgumentException("config[ibn_task_queues][{$queue}]不存在");
        }
        return self::$_objs[$queue] = TaskClient::get($queue, $taskQueue);
    }
}