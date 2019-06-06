<?php

/**
 * 行为绑定
 */
\think\Hook::add('app_init','app\\common\\behavior\\InitConfigBehavior');

/**
 * 返回对象
 * @param $array 响应数据
 */
function resultArray($array)
{
    if(isset($array['data'])) {
        $array['error'] = '';
        $code = 200;
    } elseif (isset($array['error'])) {
        $code = 400;
        $array['data'] = '';
    }
    return [
        'code'  => $code,
        'data'  => $array['data'],
        'error' => $array['error']
    ];
}

/**
 * 调试方法
 * @param  array   $data  [description]
 */
function p($data,$die=1)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if ($die) die;
}

/**
 * 用户密码加密方法
 * @param  string $str      加密的字符串
 * @param  [type] $auth_key 加密符
 * @return string           加密后长度为32的字符串
 */
function user_md5($str, $auth_key = '')
{
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}


/*
 * 获得随机数
 */
function randomCode($length = 6, $onlyNum = false) {
    mt_srand((double)microtime() * 1000000);
    $hash = '';
    $chars = 'ALLENABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    if ($onlyNum) {
        $chars = '01234567890123456789';
    }
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}


/**
 * 统一输出json
 * @param $ret int 返回码
 * @param string $msg 返回信息
 * @param array $content 返回数据
 */
function outputJson($ret, $msg = '', $content = []) {
    return json_encode(
        [
            'ret' => (string)$ret,
            'msg' => $msg ? $msg : ($ret == 1 ? 'success' : 'fail'),
            'content' => $content
        ]
    );
}