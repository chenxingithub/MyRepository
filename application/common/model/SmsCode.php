<?php
/**
 *
 * @author: dryangkun
 * @date: 2018/1/12 下午7:11
 */

namespace app\common\model;

use app\common\util\RedisFailOver;
use app\common\util\Util;
use ibnthink\RedisPool;
use think\Log;

class SmsCode {

    const TYPE_RESET = 'reset';
    const TYPE_BIND = 'bind';
    const TYPE_UNBIND = 'unbind';
    const TYPE_SMSLOGIN = 'smslogin';
    const TYPE_SMSREG = 'smsreg';

    //不限制每天短信发送次数的手机号
    private static $noLimitPhones = [
        '15113330523' => 1, //zikang
        '18933997099' => 1, //zikang
        '15975859527' => 1, //wudan
        '17762405923' => 1, //chunjun
        '13189474063' => 1, //shaohao
        '13554446258' => 1, //yetao
        '18620130547' => 1, //zhifeng
        '18170450197' => 1, //shiming
        '15216104965' => 1, //shiming
        '15992097567' => 1, //zhibin
        '17336043090' => 1, //zhibin
    ];

    public static $configs = [
        'default' => [
            'bibi' => [
                'apikey' => 'dngf3r',
                'secretkey' => '66cfb2904fb3ad8ceb1c1b7f712c25d5a447bb0c',
                'sign_name' => '冰鸟游戏',
                'tpls' => [
                    self::TYPE_RESET => 'TM001167',
                    self::TYPE_BIND => 'TM001172',
                    self::TYPE_UNBIND => 'TM001171',
                    self::TYPE_SMSLOGIN => 'TM001170',
                    self::TYPE_SMSREG => 'TM001168',
                ]
            ],
            'yunpian' => [
                'apikey' => '09964970f0da1db31a247869ed2db1bf',
                'tpls' => [
                    self::TYPE_RESET => '1871254',
                    self::TYPE_BIND => '1871256',
                    self::TYPE_UNBIND => '1871286',
                    self::TYPE_SMSLOGIN => '1885142',
                    self::TYPE_SMSREG => '1885136',
                ]
            ],
        ],
        'noibn' => [
            'yunpian' => [
                'apikey' => '09964970f0da1db31a247869ed2db1bf',
                'tpls' => [
                    self::TYPE_RESET => '2136816',
                    self::TYPE_BIND => '2136922',
                    self::TYPE_UNBIND => '2136926',
                    self::TYPE_SMSLOGIN => '2136928',
                    self::TYPE_SMSREG => '2136930',
                ]
            ]
        ]
    ];
    private static $instance = null;

    public static function get() {
        if (self::$instance === null) {
            self::$instance = new SmsCode();
        }
        return self::$instance;
    }

    /**
     * return array [true|false, msg] msg可以直接返回给客户端
     */
    public function send($tel, $type, $noibn = false) {
      
        $redisPool = RedisPool::get();

        //添加ip限制
        if($_SERVER['REMOTE_ADDR']){

            $ip = $_SERVER['REMOTE_ADDR'];

        }elseif($_SERVER['HTTP_CLIENT_IP']){

            $ip = $_SERVER['HTTP_CLIENT_IP'];

        }elseif($_SERVER['HTTP_X_FORWARDER_FOR']){

            $ip = $_SERVER['HTTP_X_FORWARDER_FOR'];
        }

        //检查短信发送上限
        $keyCntPerDay =  'sendcode|' . date('Ymd') . "|{$tel}" . "|{$ip}";
        $keyForIp =  'sendcode|' . date('Ymd') . "|{$ip}";
        if (!isset(self::$noLimitPhones[$tel]) && ($redisPool->get($keyCntPerDay) >= 5 
        || $redisPool->get($keyForIp)>=10)) {
            return [false, '对不起您已经达到今天的短信发送上限'];
        }
        $code = randomCode(6, true);
        // $redisPool->setex( "smscode-{$tel}", 600 , $code);

        $flag = empty($noibn) ? 'default' : 'noibn';
        $configs = self::$configs[$flag];
        if (count($configs) > 1) {
            $time = time();
            $failOver = new RedisFailOver('smscode', 5, 10);

            $error = null;
            foreach ($configs as $name => $conf) {
                $str = $name . ($flag == 'default' ? '' : "-{$flag}");
                if (!$failOver->shouldSkip($str, $time)) {
                    try {        
                        $this->doSend($name, $conf, $tel, $code, $type);
                        $failOver->increment($str, time(), true);
                        if ($redisPool->incr($keyCntPerDay) === 1) {
                            $redisPool->expire($keyCntPerDay, 86400);
                        }

                        if ($redisPool->incr($keyForIp) === 1) {
                            $redisPool->expire($keyForIp, 86400);
                        }
                        $redisPool->setex( "smscode-{$tel}", 600 , $code);
                        return [true];
                    } catch (\Throwable $t) {
                        $error = $t->getMessage();
                        Log::error("sms send fail, name={$name}({$flag}), {$error}");
                        $failOver->increment($str, time(), false);
                    }
                }
            }
            return [false, '系统错误，请稍后重试'];
        } else {
            $name = key($configs);
            $conf = $configs[$name];
            try {
                $this->doSend($name, $conf, $tel, $code, $type);
                return [true];
            } catch (\Throwable $t) {
                Log::error("sms send fail, name={$name}{$flag}, {$t->getMessage()}");
                return [false, '系统错误，请稍后重试'];
            }
        }
    }

    /**
     * @return bool|string
     */
    public function getCode($tel, $type) {
        $redis = RedisPool::get();
        try {
            return $redis->get( "smscode-{$tel}");
        } catch (\Exception $e) {
            Log::error("sms getCode fail, {$e->getMessage()}");
            return false;
        }
    }

    public function delCode($tel, $type) {
        $redis = RedisPool::get();
        try {
            $redis->del("smscode-{$tel}");
        } catch (\Exception $e) {
            Log::error("sms delCode fail, {$e->getMessage()}");
        }
    }

    public function doSend($name, array $conf, $tel, $code, $type) {
        switch ($name) {
            case 'bibi':
                $this->sendBibi($conf, $tel, $code, $type);
                break;
            default:
                $this->sendYunpian($conf, $tel, $code, $type);
                break;
        }
    }

    private function sendYunpian(array $conf, $tel, $code, $type) {
        $url = "http://yunpian.com/v1/sms/tpl_send.json";
        $data = [
            'apikey' => $conf['apikey'],
            'mobile' => $tel,
            'tpl_id' => $conf['tpls'][$type],
            'tpl_value' => "#code#=" . urlencode($code) . "&#time#=10",
        ];
        $result = Util::curlRequest($url, 5, http_build_query($data), [
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded'
            ]
        ]);
        Log::info("[sendYunpian] mobile={$tel},code={$code},type={$type},curl_ret={$result}");
        $ret = json_decode($result, true);
        if (!is_array($ret) || $ret['code'] != 0) {
            throw new \Exception($ret['msg']);
        }
    }

    private function sendBibi(array $conf, $tel, $code, $type) {
        $timestamp = date('mdHis');
        $apikey = $conf['apikey'];
        $secretkey = $conf['secretkey'];
        $body = [
            'template_code' => $conf['tpls'][$type],
            'mobile' => $tel,
            'sign_name' => $conf['sign_name'],
            'params' => json_encode(['code' => $code]),
        ];
        $body['request_id'] = sha1("{$timestamp}{$apikey}{$body['sign_name']}{$body['template_code']}{$body['params']}{$tel}");
        $result = Util::curlRequest('http://sms.bibi.com.cn/api/v1/message', 5, http_build_query($body), [
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api-key: {$apikey}",
                "timestamp: {$timestamp}",
                'signature: ' . md5($apikey . "\x00\x00\x00\x00\x00\x00\x00\x00\x00" . $secretkey . $timestamp),
            ]
        ]);
        Log::info("[sendBibi] mobile={$tel},code={$code},type={$type},curl_ret={$result}");
        $ret = json_decode($result, true);
        if (!is_array($ret) || $ret['statusCode'] != 200) {
            throw new \Exception($ret['message']);
        }
    }
}