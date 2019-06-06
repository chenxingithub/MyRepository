<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/9 下午12:54
 */

namespace ibnthink\task;

abstract class TaskCommand {

    /**
     * @var string 当前执行的task path
     */
    public static $currTaskPath;
    /**
     * @var bool|null 是否是最后一次重试:
     *      null表示非重试执行;
     *      false表示重试中非最后一次重试;
     *      true表示充值中最后一次重试;
     */
    public static $isLastRetry;

    final public function __construct() {
        $this->__init();
    }

    protected function __init() {
    }
}