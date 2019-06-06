<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/11 下午2:50
 */

namespace ibnthink\task\client;

abstract class TaskClient {

    public static function get($key, array $taskQueue) {
        switch ($taskQueue['type']) {
            case 'redis':
                return new RedisTaskClient($key, $taskQueue);
            default:
                throw new \InvalidArgumentException("not support taskclient type = {$taskQueue['type']}");
        }
    }

    protected $key;

    public function __construct($key, array $taskQueue) {
        $this->key = $key;
    }

    /**
     * @see TaskClient::submitDelay()
     */
    public function submit($path, array $paramValues = [], $retryNum = null, $retryPeriod = null) {
        $this->submitDelay(0, $path, $paramValues, $retryNum, $retryPeriod);
    }

    /**
     * @param int $delay
     * @param $path
     * @param array $paramValues 参数
     * @param null|int $retryNum 重试次数
     * @param null|int|array $retryPeriod 重试周期，默认10秒；int则表示固定的时间；array eg:
     *      $retryNum = 5, $retryPeriod = [10, 20, 50, 100]，则
     *      第一次重试会延迟10秒
     *      第二次重试会延迟20秒
     *      第三次重试会延迟50秒
     *      第四次重试会延迟100秒
     *      第五次重试会延迟100秒
     */
    public function submitDelay(int $delay, $path, array $paramValues = [], $retryNum = null, $retryPeriod = null) {
        if (empty($path)) {
            throw new \InvalidArgumentException('param path invalid');
        }
        if ($retryNum !== null) {
            $retryNum = (int)$retryNum;
        }
        if ($retryPeriod != null && !is_array($retryPeriod)) {
            $retryPeriod = (int)$retryPeriod;
        }
        $this->_submitDelay($delay, $path, $paramValues, $retryNum, $retryPeriod);
    }

    protected abstract function _submitDelay(int $delay, $path, array $paramValues = [], $retryNum = null, $retryPeriod = null);
}