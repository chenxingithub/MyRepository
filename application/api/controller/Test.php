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

    public function test(Request $request)
    {
        $data = [
            'gameId'=>intval($request->post('gameId')),
            'mobile'=>trim($request->post('mobile')),
            'email'=>trim($request->post('email')),
            'smsCode'=>trim($request->post('smsCode')),
            'andOrIos'=>intval($request->post('andOrIos')),
        ];
        
        //参数检查
        $validate = Loader::validate('BookingUserInfo');
        if(!$validate->check($data)){
      
           return outputJson(0,$validate->getError());
        }
        
        //短信验证码校验
        $SmsObj = new SmsCode();
        if($SmsObj->getCode($request->get('mobile'), '') != $request->get('smsCode')){
    
            return outputJson(0,'短信验证码不正确哦~');
        }

        $res = MBookingUserInfo::where('mobile',$request->post('mobile'))
        ->where('gameId',$request->post('gameId'))->find();

        if($res){
            
            return outputJson(0,'请勿重复预约哦~');
        }
    
        unset( $data['smsCode']);

        //插入数据
        try{

         MBookingUserInfo::insert($data);

        } catch (\Exception $e) {
            
            return outputJson(0,'预约失败哦~');
        }
       
        return outputJson(1,'恭喜您，预约成功~');
    }
    

    
    //生成验证码

    public function verify()
    {
        $config=[
        // 验证码字符集合
        'codeSet' => '2345678',
        // 验证码字体大小(px)
        'fontSize'=>15,
        // 是否画混淆曲线
        'useCurve' => false,
        // 验证码图片高度
        'imageH' => 30,
        // 验证码图片宽度
        'imageW' => 120,
        // 验证码位数
        'length' => 4,
        // 验证成功后是否重置        
        'reset' => true
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();

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