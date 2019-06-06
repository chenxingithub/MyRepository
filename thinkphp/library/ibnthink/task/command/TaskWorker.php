<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/8 下午3:35
 */

namespace ibnthink\task\command;

use ibnthink\task\Worker;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class TaskWorker extends Command {

    protected function configure() {
        $this->setName('task:worker')
            ->setDefinition([
                new Option('worker', null, Option::VALUE_REQUIRED, '指定运行worker'),
                new Option('index', null, Option::VALUE_REQUIRED, '指定运行worker的子进程编号')
            ])
            ->setDescription('运行worker，系统内部使用，不要手动运行');
    }

    protected function execute(Input $input, Output $output) {
        $workerKey = $input->getOption('worker');
        $index = $input->getOption('index');
        $worker = new Worker($workerKey, $index);
        $worker->start();
    }
}