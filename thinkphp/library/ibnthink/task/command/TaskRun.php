<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/8 上午11:06
 */

namespace ibnthink\task\command;

use ibnthink\task\TaskCommand;
use ibnthink\task\TaskDispatcher;
use ibnthink\task\TaskUtils;
use think\App;
use think\Config;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\exception\ClassNotFoundException;
use think\Log;
use think\log\driver\Ibnlog;

class TaskRun extends Command {

    protected function configure() {
        $this->setName('task:run')
            ->setDefinition([
                new Option('module', 'm', Option::VALUE_OPTIONAL, '显示单个module的任务列表'),
            ])
            ->addArgument('path', Argument::OPTIONAL, '运行的任务path')
            ->addArgument('params', Argument::IS_ARRAY | Argument::OPTIONAL, '运行任务参数')
            ->setDescription('运行任务 or 查看任务');
    }

    protected function execute(Input $input, Output $output) {
        $path = $input->hasArgument('path') ? $input->getArgument('path') : null;
        $params = $input->hasArgument('params') ? $input->getArgument('params') : [];
        if (empty($path)) {
            $module = $input->hasOption('module') ? $input->getOption('module') : null;
            $this->showTaskList($module, $output);
        } else {
            $taskDispatcher = new TaskDispatcher();
            if ($params[0] === 'help') {
                $this->showTask($path, $taskDispatcher, $output);
            } else {
                Ibnlog::setRequestId('ibntask_run');
                Log::info('start....');
                $taskDispatcher->dispatchForCli($path, $params);
            }
        }
    }

    private function showTask($path, TaskDispatcher $taskDispatcher, Output $output) {
        /**
         * @var $rMethod \ReflectionMethod
         */
        list(, $rMethod,) = $taskDispatcher->findMethod($path);
        list($desc, $params) = self::parseMethodDocument($rMethod->getDocComment());

        $output->comment("task={$path} 描述: " . ($desc ?? '空'));
        $output->comment("参数说明:");

        $newParams = [];
        $maxLen = -1;
        foreach ($rMethod->getParameters() as $rParameter) {
            $name = $rParameter->getName();
            $type = $rParameter->getType() ?? 'mixed';
            if ($rParameter->isVariadic()) {
                $type = '可变参数';
            }

            $str = $params[$name] ?: "\${$name} {$type}";
            if ($rParameter->isDefaultValueAvailable()) {
                if ($rParameter->isDefaultValueConstant()) {
                    $tmp = explode('\\', $rParameter->getDefaultValueConstantName());
                    $prefix = 'optional(' . end($tmp) . ')';
                } else {
                    $default = $rParameter->getDefaultValue();
                    $prefix = 'optional(' . ($default === null ? 'null' : $default) . ')';
                }
            } else {
                $prefix = $rParameter->isVariadic() ? 'optional' : 'required';
            }
            $maxLen = max(strlen($prefix), $maxLen);
            $newParams[] = [$prefix, $str];
        }

        foreach ($newParams as $newParam) {
            $output->info('  ' . str_pad($newParam[0], $maxLen + 2) . $newParam[1]);
        }
        $output->writeln('');
        $output->comment('* 如果参数类型为array, 则传: 英文逗号分隔字符串 or json字符串');
    }

    private function showTaskList($module, Output $output) {
        $taskDirs = [];
        if ($module === null) {
            $iter = new \DirectoryIterator(APP_PATH);
            foreach ($iter as $v) {
                if (!$v->isDot() && $v->isDir()) {
                    if ($v->getFilename() === 'task') {
                        $taskDirs[''] = $v->getPathname();
                    } else {
                        $taskDir = $v->getPathname() . DS . 'task';
                        if (is_dir($taskDir)) {
                            $taskDirs[$v->getFilename()] = $taskDir;
                        }
                    }
                }
            }
        } else {
            $taskDir = APP_PATH . ($module ? $module . DS : '') . 'task';
            if (is_dir($taskDir)) {
                $taskDirs[$module] = $taskDir;
            }
        }

        $output->comment("{$this->getDescription()}, Usage:");
        $output->writeln("  {$this->getName()} path [help|param1 param2 param3...]");
        $output->writeln('');
        $output->comment('Available task paths:');

        $appNamespace = Config::get('app_namespace');
        $paths = [];
        $maxPathLength = -1;
        foreach ($taskDirs as $module => $taskDir) {
            $iter = new \DirectoryIterator($taskDir);
            foreach ($iter as $v) {
                if ($v->isDot() || $v->isDir() || $v->getExtension() !== 'php') {
                    continue;
                }
                if ($module) {
                    $class = "\\{$appNamespace}\\{$module}\\task\\" . $v->getBasename('.php');
                    $pathPrefix = "{$module}/";
                } else {
                    $class = "\\{$appNamespace}\\task\\" . $v->getBasename('.php');
                    $pathPrefix = '';
                }
                $rClass = null;
                try {
                    $rClass = new \ReflectionClass($class);
                } catch (ClassNotFoundException $e) {
                    continue;
                }
                if (!($rClass->isSubclassOf(TaskCommand::class)) || $rClass->isAbstract()) {
                    continue;
                }
                foreach ($rClass->getMethods() as $rMethod) {
                    if ($rMethod->getModifiers() & (\ReflectionMethod::IS_PRIVATE | \ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_STATIC)) {
                        continue;
                    }
                    if (strncmp($rMethod->getName(), '__', 2) == 0) {
                        continue;
                    }
                    list($desc) = self::parseMethodDocument($rMethod->getDocComment());
                    $path = $pathPrefix . lcfirst($rClass->getShortName()) . '/' . $rMethod->getName();
                    $paths[] = [$path, $desc];
                    $maxPathLength = max(strlen($path), $maxPathLength);
                }
            }
        }

        foreach ($paths as list($path, $desc)) {
            $output->info('  ' . str_pad($path, $maxPathLength + 2) . ($desc ?? '空'));
        }
    }

    private static function parseMethodDocument($str) {
        $desc = null;
        $params = [];
        if (!empty($str)) {
            foreach (explode("\n", $str) as $line) {
                $line = trim($line);
                if ($line !== '/**' && $line !== '*') {
                    if (empty($desc) && strncmp($line, '* @', 3) != 0) {
                        $desc = substr($line, 2);
                    }
                    if (strncmp($line, '* @param ', 9) === 0) {
                        $line = substr($line, 9);
                        $tmp = preg_split('/\s+/', $line);
                        if ($tmp[0][0] === '$') {
                            $params[substr($tmp[0], 1)] = $line;
                        } elseif ($tmp[1][0] === '$') {
                            $params[substr($tmp[1], 1)] = $line;
                        }
                    }
                }
            }
        }
        return [$desc, $params];
    }
}