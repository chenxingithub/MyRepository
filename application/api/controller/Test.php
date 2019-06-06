<?php
/*
 * @Company: 广州冰鸟网络科技有限公司
 * @Description: 
 * @Author: ChenXin
 * @Email: chenxin@ibingniao.com
 * @Date: 2019-04-10 11:02:18
 * @LastEditTime: 2019-04-12 17:15:42
 */
namespace app\api\controller;

use app\common\model\SmsCode;
use app\common\util\RedisFailOver;
use app\common\util\Util;
use ibnthink\RedisPool;
use app\common\controller\Common;
use app\api\model\BookingUserInfo as MBookingUserInfo;
use think\captcha\Captcha;;
use app\common\lib\Curl;  
use think\Session;
use think\Request;
use think\Loader;

class Test extends ApiCommon
{
   
      //游戏预约

    public function test()
    {
    var_dump(123);die;   
    }
     

    //校验验证码

    private function check($code)
    {
        $captcha = new Captcha();
        return $captcha->check($code); 
    }

    
     //发送短信验证码
    public function send(Request $request) 
    {
        //跨域允许
        // header("Access-Control-Allow-Origin:*");
        $phone= $request->post('mobile');
        $imgCode= $request->post('imgCode');

         //图片验证码验证
         if(!$this->check($request->post('imgCode'))){

            return outputJson(0,'图片验证码不正确哦~');
        }
        //接收参数
        // $postData = checkParams([
        //     'type', 'tel_num', 'time', 'jh_app_id', 'jh_channel', 'sign'
        // ], 'post', ['tel_num', 'jh_app_id', 'type']);
        // $phone = $postData['tel_num'];
        // $smsType = $postData['type'];

        //校验签名
        // $jh_sign = config('SIGN')['sdk'];
        // $checkSign = md5("jh_app_id={$postData['jh_app_id']}&tel_num={$phone}&type={$smsType}&jh_sign={$jh_sign}&time={$postData['time']}");
        // if ($postData['sign'] != $checkSign) {
        //     return outputJson(0, '签名校验失败');
        // }
        //校验手机号合法性
        if (!MBookingUserInfo::isTelphone($phone)) {
            return outputJson(0, '不是合法的手机号哦~');
        }

        $smsType = 'smsreg';
        $smsCode = SmsCode::get();
        list($ret, $msg) = $smsCode->send($phone, $smsType, $noibn);
        if ($ret) {
            return outputJson(1, '短信发送成功了呢~');
        } else {
            return outputJson(0, $msg);
        }
    }

  
}