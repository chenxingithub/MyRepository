<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/9 下午7:40
 */

namespace ibnthink\task\exception;

class TaskRetryException extends \Exception {

    public $paramValues;
    public $retries;
    public $retryPeriod = 10;

    public function setParamValues(array $paramValues) {
        $this->paramValues = $paramValues;
        return $this;
    }

    public function setRetries(int $retries) {
        $this->retries = $retries;
        return $this;
    }

    /**
     * @param $retryPeriod int|array
     * @return $this
     */
    public function setRetryPeriod($retryPeriod) {
        if (is_array($retryPeriod)) {
            if (!empty($retryPeriod)) {
                $this->retryPeriod = $retryPeriod;
            }
        } elseif ($retryPeriod > 0) {
            $this->retryPeriod = (int)$retryPeriod;
        }
        return $this;
    }
}