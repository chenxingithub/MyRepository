<?php
// +----------------------------------------------------------------------
// | Description: 基础配置文件
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honghaiweb.com>
// +----------------------------------------------------------------------
error_reporting(E_ERROR | E_WARNING | E_PARSE);

return [
    'app_namespace' => 'app',
    // 'app_debug' => false,
    'app_debug' => true,
    'app_status' => defined('APP_STATUS') ? APP_STATUS : '',
    'session' => [
        'id' => '',
        'prefix' => 'think',
        'type' => '',
        'auto_start' => false,
        'httponly' => true,
        'secure' => false
    ],
    'url_route_on' => true,
    'url_param_type' => 1,
    'url_domain_deploy' => true,
    'log' => [
        'type' => 'Ibnlog',
        'level' => [],
    ],

    'ibn_redis' => [
        'base' => [
            'host' => 'hn1.web.redis.m',
            'port' => 6379,
            'timeout' => 10,
            'prefix' => 'wechat',
            'persistent' => true
        ]
    ],




    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'admin',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => '',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => false,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'php',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}}',
        // 标签库标签开始标记
        'taglib_begin' => '{{',
        // 标签库标签结束标记
        'taglib_end'   => '}}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => [
    '__CSS__'=>'static/css',
    '__JS__'=>'static/js',
    '__310VUE__'=>'newcms2/310game-pc/static',
    '__VUE__'=>'static/dist/static'
    ],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',



    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => '',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    'qiniu' => [
        'ak' => 'EJiUJugzluiAwb5qE1n-KD5OPzEGSat9Nb1cpJmw',
        'sk' => 'UR84zG7V9TtGMl8ljukmZrVyc7_Njmh_FAAojNCl',
        'bucket' => 'youqu',
        'image_url' => 'http://onpkt1yb4.bkt.clouddn.com/',
    ],

    'jwt_key'             => '1gHuiop975cdashyex9Ud23ldsvm2Xx',
];