<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/8 上午11:06
 */

namespace ibnthink\task\command;

use ibnthink\task\Server;
use ibnthink\task\TaskUtils;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class TaskServer extends Command {

    protected function configure() {
        $this->setName('task:server')
            ->setDefinition([
                new Option('worker', null, Option::VALUE_OPTIONAL, '指定运行的worker，默认default'),
                new Option('num', null, Option::VALUE_OPTIONAL, '指定worker的进程数')
            ])
            ->addArgument('action', Argument::REQUIRED, 'action = start/stop/restart/reload')
            ->setDescription('启动/停止/重启异步任务服务');
    }

    protected function execute(Input $input, Output $output) {
        $action = $input->getArgument('action');
        if (!in_array($action, ['start', 'stop', 'restart', 'reload'])) {
            throw new \Exception('action必须是[start/stop/restart/reload]其中一个');
        }
        $worker = $input->hasOption('worker') ? $input->getOption('worker') : 'default';
        $num = $input->hasOption('num') ? $input->getOption('num') : 0;

        $server = new Server($worker, $num);
        switch ($action) {
            case 'start':
                $server->start();
                break;
            case 'stop':
                $server->stop();
                break;
            case 'restart':
                $server->stop();
                $server->start();
                break;
            case 'reload':
                $server->reload();
                break;
        }
    }
}