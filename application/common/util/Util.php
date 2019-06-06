<?php
/**
 *
 * @author: dryangkun
 * @date: 2017/6/6 下午3:42
 */

namespace app\common\util;


use think\Config;
use think\Log;

class Util {

    const LOG_REG = 'userReg'; //用户注册
    const LOG_PAY_REQUEST = 'payRequest'; //下单
    const LOG_CALLBACK = 'payCallback'; //下单
    const LOG_NOTIFY_CP = 'notifyCp'; //发货

    public static function bnlog($errCode = '00', $logType = '', $gameId = '', $errorReason = '', $detailLogInfo = '') {

        $infoKey = implode('-', [
            $logType,
            $gameId ? $gameId : ''
        ]);

        $msg = '[out-api] '
            . $infoKey . " "
            . ($errCode ? "{$errCode} " : "--")
            . ($errorReason ? "{$errorReason} " : "--")
            . ($detailLogInfo ? "{$detailLogInfo} " : "--");
        Log::record($msg);
    }


    public static function getClientIp() {
        static $ip = null;
        if ($ip !== null) {
            return $ip;
        }

        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($arr as $_ip) {
                $_ip = trim($_ip);
                if ($_ip !== 'unknown') {
                    $ip = $_ip;
                    break;
                }
            }
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        if ($ip === null) {
            $ip = '';
        }
        return $ip;
    }

    /**
     * @param $url
     * @param int $timeout
     * @param null $postfields
     * @param array $extraOptions
     * @param int $retries 重试次数
     * @return string
     * @throws \Exception
     */
    public static function curlRequest($url,
                                       $timeout = 5,
                                       $postfields = null,
                                       array $extraOptions = [],
                                       $retries = 1) {
        static $defaultHeaders = [
            'Connection: close',
            'Cache-Control: no-cache'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = $defaultHeaders;
        if (isset($extraOptions[CURLOPT_HTTPHEADER])) {
            foreach ($extraOptions[CURLOPT_HTTPHEADER] as $v) {
                $headers[] = $v;
            }
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if (strpos($url, 'https://') !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        if ($timeout > 0) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        }
        if ($postfields !== null) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        }
        if (!empty($extraOptions)) {
            curl_setopt_array($ch, $extraOptions);
        }

        $result = false;
        while (($result === false) && (--$retries >= 0)) {
            $result = curl_exec($ch);
        }

        if ($result === false) {
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);
            throw new \Exception("curlerror, {$error}", $errno);
        }
        curl_close($ch);
        return $result;
    }

    public static function isAppleIp($ip) {
        $ip = ip2long($ip);
        if ($ip !== false) {
            $ip = sprintf('%u', $ip);
            if (
                ($ip >= 285212672 && $ip <= 301989887) ||   //17.0.0.0 ~ 17.255.255.255
                ($ip >= 1063051264 && $ip <= 1063059455) || //63.92.224.0 ~ 63.92.255.255
                ($ip >= 1103566336 && $ip <= 1103566847) || //65.199.22.0 ~ 65.199.23.255
                ($ip >= 3222030848 && $ip <= 3222031103) || //192.12.74.0 ~ 192.12.74.255
                ($ip >= 3224041728 && $ip <= 3224041983) || //192.42.249.0 ~ 192.42.249.255
                ($ip >= 3427778048 && $ip <= 3427778303) || //204.79.190.0 ~ 204.79.190.255
                ($ip >= 2427584512 && $ip <= 2427600895)    //144.178.0.0 ~ 144.178.63.255
            ) {
                return true;
            }
        }
        return false;
    }

    // 整合获取系统相关信息
    public static function getOsInfo($device_info) {
        $os = $device_info['os'] ? $device_info['os'] : '';
        if ($device_info['os'] == '') {
            $os = $device_info['android_version'] ? 'Android' : 'iOS';
        }

        $os_info = array(
            // 操作系统
            'os' => $os,
            // 操作系统版本
            'os_version' => $device_info['android_version'] ?
                $device_info['android_version'] :
                $device_info['system_version'],
            // sdk版本
            'sdk_version' => $device_info['sdk_version'] ?
                $device_info['sdk_version'] :
                '',
            // 设备名称
            'device_name' => $device_info['device_name'] ?
                $device_info['device_name'] :
                $device_info['system_name']
        );

        if ($os == 'Android') {
            $os_info['ad_id'] = $device_info['ad_id'] ? $device_info['ad_id'] : '';
            $os_info['and_id'] = $device_info['and_id'] ? $device_info['and_id'] : '';
        }

        return json_encode($os_info);
    }

    public static function isPhone($uname) {
        static $pattern = '/^1\d{10}$/';
        return preg_match($pattern, $uname) === 1;
    }

    public static function urlDecode($data) {
        if (is_array($data)) {
            $ret = [];
            foreach ($data as $k => $v) {
                $ret[$k] = urldecode($v);
            }
            return $ret;
        } else {
            return urldecode($data);
        }
    }

    /*
     *  判断是否是需要实名认证的包
     */
    public static function isNeedRealJhc($jhChannel) {
        $conf = Config::get('NEED_REAL_JH_CHANNELS');
        return in_array($jhChannel, $conf);
    }

    /*
     *  sdk3.0
     *  整合获取系统相关信息
     */
    public static function sdk3GetOsInfo($data) {
        $os = $data['os'] ?? '';

        $os_info = array(
            // 操作系统
            'os' => $os,
            // 操作系统版本
            'os_version' => $data['os_version'] ?? '',
            // sdk版本
            'sdk_version' => $data['sdk_version'] ?? '',
            // 设备名称
            'device_name' => $data['device_name'] ?? ''
        );

        if (strtolower($os) == 'android') {
            $os_info['ad_id'] = $data['ad_id'] ? $data['ad_id'] : '';
            $os_info['and_id'] = $data['and_id'] ? $data['and_id'] : '';
        }

        return json_encode($os_info);
    }

    public static function userDataModifyLog($userId = '', $userName = '', $appKey, $category, $extra='', $dir = 'userdata_modify')
    {
        if(empty($userId) && empty($userName)){
            Log::error("[userDataModifyLog] empty_uid_uname");
        }
        $enter = [
            't' => time(),
            'u' => $userId ?? '',
            'n' => $userName ?? '',
            'a' => $appKey ?? '',
            'c' => $category,
            'e' => $extra,
            'ip' => Util::getClientIp()
        ];

        $logfile = "/work/data/ibndata/{$dir}/" . date('Ymd_Hi') . '.log';
        try {
            file_put_contents($logfile, json_encode($enter) . "\n", FILE_APPEND);
        } catch (\Throwable $t) {
            Log::error("[userDataModifyLog] error  msg={$t->getMessage()}" . $category . '修改写入日志失败');
        }
    }

    public static function getIpLocation(){
        static $area = null;
        if ($area !== null) {
            return $area;
        }
        $location = $_SERVER['IP_LOCATION'];
        if (empty($location)){
            return false;
        }else{
            $area = explode('-', $location);
            return $area;
        }
    }

    public static function isInCityies($cities, $provinces = []) {
        $area = self::getIpLocation();
        if ($area &&
            (in_array($area[1], $provinces) || in_array($area[2], $cities))
        ) {
            return true;
        }
        return false;
    }

}