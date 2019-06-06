<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/8/7 下午6:24
 */

namespace ibnthink\task;

use ibnthink\task\exception\ExitException;
use think\App;
use think\Log;
use think\log\driver\Ibnlog;

class Server {

    private $workerKey;
    private $workerNum;
    private $taskWorker;
    private $taskQueues;

    private $debug = false;
    /**
     * @var RedisDelayChecker|null
     */
    private $redisDelayChecker;

    public function __construct($workerKey, $workerNum = 0) {
        list($this->taskWorker, $this->taskQueues) = TaskUtils::getTaskWorkerAndQueuesByKey($workerKey);
        $this->workerKey = $workerKey;
        $this->workerNum = $workerNum > 0 ? $workerNum : ($this->taskWorker['num'] ?? 2);
        $this->debug = get_cfg_var('ibntask.debug') === '1';
        if ($this->debug) {
            $this->workerNum = 1;
        }
        if ($this->taskWorker['type'] === 'redis') {
            $this->redisDelayChecker = new RedisDelayChecker($this);
        }
    }

    /**
     * @return mixed
     */
    public function getWorkerKey() {
        return $this->workerKey;
    }

    /**
     * @return int
     */
    public function getWorkerNum(): int {
        return $this->workerNum;
    }

    /**
     * @return mixed
     */
    public function getTaskWorker() {
        return $this->taskWorker;
    }

    /**
     * @return mixed
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

    private $workerInfos = [];
    private $lockFp;
    private $stopping = false;

    public function __handleSignal($signo) {
        switch ($signo) {
            case SIGINT:
            case SIGTERM:
                Log::info('server开始关闭');
                $this->stopping = true;
                if ($this->redisDelayChecker) {
                    $this->redisDelayChecker->close();
                }
                $this->killWorkers();
                break;
            case SIGUSR1:
                Log::info('server开始reload');
                $pids = array_keys($this->workerInfos);
                foreach ($pids as $pid) {
                    \swoole_process::kill($pid, SIGTERM);
                }
                break;
            case SIGCHLD:
                $now = time();
                while ($ret = \swoole_process::wait(false)) {
                    $pid = $ret['pid'];
                    Log::info("worker退出 = {$pid}");
                    /**
                     * @var $workerInfo WorkerInfo
                     */
                    if (!$this->stopping && ($workerInfo = $this->workerInfos[$pid])) {
                        unset($this->workerInfos[$pid]);
                        if ($workerInfo->lastExitTime === -1) {
                            $workerInfo->lastExitTime = $now;
                        } elseif($now - $workerInfo->lastExitTime <= 1800) {
                            if ((++$workerInfo->exitCounter) >= 10) {
                                TaskUtils::ibnerror("worker({$workerInfo->index})30分钟内异常退出超过10次");
                            }
                        } else {
                            $workerInfo->exitCounter = 1;
                        }
                        $process = new \swoole_process([$workerInfo, 'startWorker']);
                        $newPid = $process->start();
                        if ($newPid === false) {
                            TaskUtils::ibnerror("worker({$workerInfo->index})无法重启, " . swoole_strerror(swoole_errno()));
                        } else {
                            $this->workerInfos[$newPid] = $workerInfo;
                        }
                    } else {
                        unset($this->workerInfos[$pid]);
                    }
                }
                break;
        }
    }

    private function killWorkers($exit = 0) {
        if (empty($this->workerInfos)) {
            exit($exit);
        }
        foreach ($this->workerInfos as $pid => $_) {
            $ret = \swoole_process::kill($pid, SIGTERM);
            if ($ret !== true) {
                unset($this->workerInfos[$pid]);
            }
        }
        if (empty($this->workerInfos)) {
            Log::info('workers全部退出');
            if ($this->lockFp) {
                flock($this->lockFp, LOCK_UN);
                fclose($this->lockFp);
            }
            swoole_event_exit();
            exit($exit);
        }

        $counter = 0;
        \swoole_timer_tick(2000, function($timerId) use(&$counter, $exit) {
            if (empty($this->workerInfos)) {
                \swoole_timer_clear($timerId);
                Log::info('workers全部退出');
                if ($this->lockFp) {
                    flock($this->lockFp, LOCK_UN);
                    fclose($this->lockFp);
                }
                swoole_event_exit();
                exit($exit);
            }
            $counter++;
            if ($counter == 20) {
                Log::alert('超过60秒workers没有完全退出, 开始强制退出 = ' . json_encode(array_keys($this->workerInfos)));
                foreach ($this->workerInfos as $pid => $_) {
                    $ret = \swoole_process::kill($pid, SIGKILL);
                    if ($ret !== 0) {
                        unset($this->workerInfos[$pid]);
                    }
                }
            } elseif ($counter % 10 == 0) {
                TaskUtils::ibnerror('已经超过' . ($counter * 3) . '秒workers没有完全退出 = ' . json_encode(array_keys($this->workerInfos)));
            }
        });
    }

    public function start() {
        TaskUtils::clearConsole();
        TaskUtils::$serverId = getmypid() . '@' . gethostname();
        App::$debug = $this->debug;
        Ibnlog::setName("ibntask_{$this->workerKey}");
        Ibnlog::setRequestId(TaskUtils::$serverId . ':server');
        TaskUtils::setExceptionHandler();
        TaskUtils::setErrorHandler();
        Log::info('启动server');

        if (!$this->debug) {
            \swoole_process::daemon(true, false);
            $this->lockFp = fopen(RUNTIME_PATH . "ibntask_{$this->workerKey}.lock", 'w');
            if ($this->lockFp === false) {
                throw new ExitException('打开锁文件失败', 1);
            }
            if (!flock($this->lockFp, LOCK_EX | LOCK_NB)) {
                fclose($this->lockFp);
                throw new ExitException('上锁失败', 1);
            }
            swoole_set_process_name("ibntask-server: {$this->workerKey} " . APP_PATH);
        }
        Ibnlog::console($this->debug);

        \swoole_process::signal(SIGCHLD, [$this, '__handleSignal']);
        for ($i = 0; $i < $this->workerNum; $i++) {
            $workerInfo = new WorkerInfo($i, $this->workerKey, $this->debug);
            $process = new \swoole_process([$workerInfo, 'startWorker']);
            $pid = $process->start();
            if ($pid === false) {
                Log::error("启动worker({$i})失败 = " . swoole_strerror(swoole_errno()));
                $this->stopping = true;
                break;
            }
            $this->workerInfos[$pid] = $workerInfo;
        }
        if ($this->stopping) {
            $this->killWorkers(1);
        } else {
            if ($this->redisDelayChecker) {
                $this->redisDelayChecker->run();
            }
            \swoole_timer_tick(30000, function($timerId) {
                if (!$this->stopping) {
                    ConfigReload::instance()->reload();
                }
            });
            \swoole_process::signal(SIGINT, [$this, '__handleSignal']);
            \swoole_process::signal(SIGTERM, [$this, '__handleSignal']);
            \swoole_process::signal(SIGUSR1, [$this, '__handleSignal']);
        }
    }

    public function stop() {
        TaskUtils::$serverId = getmypid() . '@' . gethostname();
        App::$debug = $this->debug;
//        Ibnlog::console($this->debug);
        Ibnlog::setName("ibntask_{$this->workerKey}");
        Ibnlog::setRequestId(TaskUtils::$serverId . ':server');

        $procName = escapeshellarg("ibntask-server: {$this->workerKey} " . APP_PATH);
        exec("ps -ef | grep {$procName} | grep -v grep | awk '{print \$2}'", $output);
        $pid = (int)$output[0];
        if ($pid > 0) {
            Log::info("server({$pid})开始关闭");
            posix_kill($pid, SIGINT);
            sleep(2);
            while (1) {
                $output = [];
                exec("ps -ef | grep {$procName} | grep {$pid} | grep -v -c grep", $output);
                $str = trim($output[0]);
                if (!empty($str)) {
                    Log::info("server({$pid})关闭中...");
                    sleep(2);
                } else {
                    break;
                }
            }
            Log::info("server({$pid})完成关闭");
        } else {
            Log::info("server未启动");
        }
    }

    public function reload() {
        TaskUtils::$serverId = getmypid() . '@' . gethostname();
        App::$debug = $this->debug;
//        Ibnlog::console($this->debug);
        Ibnlog::setName("ibntask_{$this->workerKey}");
        Ibnlog::setRequestId(TaskUtils::$serverId . ':server');

        $procName = escapeshellarg("ibntask-server: {$this->workerKey} " . APP_PATH);
        exec("ps -ef | grep {$procName} | grep -v grep | awk '{print \$2}'", $output);
        $pid = (int)$output[0];
        if ($pid > 0) {
            Log::info("server({$pid})开始reload");
            posix_kill($pid, SIGUSR1);
        } else {
            Log::info("server未启动");
        }
    }
}