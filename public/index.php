<?php
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){  
	header("Access-Control-Allow-Origin: *");  
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId, Access-Token, X-Token");
	header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');  
	exit;  
}  


error_reporting(E_ALL^E_NOTICE);
define('IBN_ROOT_PATH', dirname(__DIR__));
define('APP_PATH', IBN_ROOT_PATH . '/application/');
define('APP_NAME', 'demo');

$env = $_SERVER['IBINGNIAO_TECH_ENV'] ?? get_cfg_var('IBINGNIAO_TECH_ENV');
if (!empty($env)) {
    if (strncmp($env, 'prod', 4) != 0) {
        define('APP_DEBUG', true);
    }
    define('APP_STATUS', $env);
}
unset($env);
require IBN_ROOT_PATH . '/vendor/autoload.php';
require IBN_ROOT_PATH . '/thinkphp/start.php';
