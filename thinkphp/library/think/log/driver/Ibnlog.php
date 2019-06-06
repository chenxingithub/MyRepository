<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/6/17 下午12:30
 */

namespace think\log\driver;

use think\App;
use think\Request;

class Ibnlog {

    const BUFFER_MAX_SIZE = 8192;
    private static $requestId = null;

    private static $name;
    private static $suffix;
    private static $console = null;

    public static function getName() {
        return self::$name;
    }

    public static function setName($name) {
        self::$name = $name;
        if ($name === null || $name === '') {
            self::$suffix = '';
        } else {
            self::$suffix = "{$name}_";
        }
    }

    public static function console($is) {
        self::$console = $is;
    }

    public static function setRequestId($requestId) {
        self::$requestId = $requestId;
    }

    public static function getRequestId() {
        return self::$requestId;
    }

    protected $time_format = 'c';
    protected $path = LOG_PATH;

    protected $writed = [];

    // 实例化并传入参数
    public function __construct($config = []) {
        if (isset($config['time_format'])) {
            $this->time_format = $config['time_format'];
        }
        if (isset($config['path'])) {
            $this->path = rtrim($config['path'], '/') . DS;
        }
        if (self::$suffix === null) {
            self::setName(null);
        }
        if (self::$requestId === null) {
            // self::$requestId = sprintf('%05d.%x', posix_getpid(), THINK_START_TIME * 10000);//posix_getpid()不兼容windows
            self::$requestId = sprintf('%05d.%x', get_current_user(), THINK_START_TIME * 10000);
            if (IS_CLI) {
                self::$requestId .= '.cli';
            }
        }
        if (IS_CLI && self::$console === null) {
            $console = get_cfg_var('ibnlog.console');
            if ($console === false) {
                self::console(posix_isatty(2));
            } else {
                self::console($console === '1');
            }
        }
    }

    protected $currDestination = null;
    protected $currFp = null;

    /**
     * 日志写入接口
     * @access public
     * @param array $log 日志信息
     * @return bool
     */
    public function save(array $log = []) {
        if (!self::$console) {
            $module = Request::instance()->module();
            if ($module !== '') {
                $destination = $this->path . $module . DS . self::$suffix . date('Ymd') . '.log';
            } else {
                $destination = $this->path . self::$suffix . date('Ymd') . '.log';
            }
            if ($this->currDestination === null || $this->currDestination !== $destination) {
                if ($this->currFp !== null) {
                    fclose($this->currFp);
                }
                $this->currDestination = $destination;
                $path = dirname($destination);
                !is_dir($path) && mkdir($path, 0755, true);

                $this->currFp = fopen($destination, 'a');
                stream_set_chunk_size($this->currFp, 2147483647);
            }
        } else {
            $destination = '';
        }

        $buffer = '';
        foreach ($log as $type => $val) {
            foreach ($val as $msg) {
                if (is_array($msg)) {
                    $msg = json_encode($msg, JSON_UNESCAPED_UNICODE);
                }
                $this->append($type, $msg, $destination, $buffer);
            }
        }
        if ($buffer !== '') {
            $this->write($buffer);
        }
        return true;
    }

    protected function write(&$buffer) {
        if (!self::$console && $this->currFp !== null) {
            fwrite($this->currFp, $buffer);
        } else {
            echo $buffer;
        }
        $buffer = '';
    }

    protected function append($type, $msg, $destination, &$buffer) {
        $time = date($this->time_format);

        if (!isset($this->writed[$destination])) {
            if (App::$debug) {
                // 获取基本信息
                if (isset($_SERVER['HTTP_HOST'])) {
                    $current_uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                } else {
                    $current_uri = "cmd:{$_SERVER['argv'][1]}";
                }
                $runtime = round(microtime(true) - THINK_START_TIME, 10);
                $reqs = $runtime > 0 ? number_format(1 / $runtime, 2) : '∞';
                $time_str = ' [运行时间：' . number_format($runtime, 6) . 's][吞吐率：' . $reqs . 'req/s]';
                $memory_use = number_format((memory_get_usage() - THINK_START_MEM) / 1024, 2);
                $memory_str = ' [内存消耗：' . $memory_use . 'kb]';
                $file_load = ' [文件加载：' . count(get_included_files()) . ']';

                $buffer .= "[{$time}][" . self::$requestId . "][info] {$current_uri} {$time_str} {$memory_str} {$file_load}\r\n";
                if (strlen($buffer) >= self::BUFFER_MAX_SIZE) {
                    $this->write($buffer);
                }
            }

            $remote = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
            if (!IS_CLI) {
                $method = $_SERVER['REQUEST_METHOD'];
                $uri = $_SERVER['REQUEST_URI'];
            } else {
                $method = 'CLI';
                $uri = $_SERVER['argv'][1];
            }
            $buffer .= "[{$time}][" . self::$requestId . "][info] start {$remote} {$method} {$uri}\r\n";
            if (strlen($buffer) >= self::BUFFER_MAX_SIZE) {
                $this->write($buffer);
            }
            $this->writed[$destination] = true;
        }

        $buffer .= "[{$time}][" . self::$requestId . "][{$type}] {$msg}\r\n";
        if (strlen($buffer) >= self::BUFFER_MAX_SIZE) {
            $this->write($buffer);
        }
    }

    public function __destruct() {
        if ($this->currFp !== null) {
            fclose($this->currFp);
            $this->currFp = null;
        }
    }
}