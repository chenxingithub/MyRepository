<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\BnUser;
use app\common\lib\Curl;  
use think\Request;
use think\Loader;

class User extends ApiCommon
{
    /**
     * 用户登录
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Login(Request $request)
    {   

        switch ($request->post('type')) {
            case '1': //冰鸟授权登录
                return $this->BnLogin($request,$id);
                break;
            case '2': //另外的修改类型
                exit();
                break;
            default://用户资料修改
                exit();
                break;
        }
    }


    /**
     * 用户登录
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function BindingPhone(Request $request)
    {   
        var_dump($Redis->get('cms_user_bntoken'.$userInfo->content->user_id));exit();
    }

    /**
     * 冰鸟授权登录
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function BnLogin($request,$id)
    {   
        //实例化用户表
        $MBnUser = new BnUser;

        //用code换取token
        $codeParams = [
            'code'=>$request->post('code'),
            'redirect_url'=>'http://apply.luosm.cn/bn_test/index.html',
            'app_id'=>'110000000',
            'from_h5'=>1,
        ];
        //生成签名sign
        $sign = $MBnUser->BnUserSign($codeParams);

        $codeParams['sign'] = $sign;
        
        //请求冰鸟返回用户信息
        $tokenJson = Curl::post('http://m.1aiyouxi.com/oauth/token',$codeParams);
        $tokenInfo = json_decode($tokenJson);


        if($tokenInfo->ret != 1){
            return json(['message'=>'冰鸟授权toker获取失败','state'=>4]);
        }
        
        //用token换取冰鸟用户信息
        $userParams = [
            'access_token'=>$tokenInfo->content->access_token,
            'app_id'=>'110000000',
            'from_h5'=>1,
        ];

        //生成签名sign
        $sign = $MBnUser->BnUserSign($userParams);
        $userParams['sign'] = $sign;
        
        //请求冰鸟返回用户信息
        $userInfoJson = Curl::post('http://m.1aiyouxi.com/oauth/userInfo',$userParams);
        $userInfo = json_decode($userInfoJson);
        if ($userInfo->ret != 1) {
            return json(['message'=>'冰鸟授权登录失败','state'=>3]);
        }

        $userData=[
            'bn_user_name'=>$userInfo->content->user_name,
            'bn_user_id'=>$userInfo->content->user_id,
            'real_num'=>$userInfo->content->real->id,
            'real_name'=>$userInfo->content->real->name,
            'phone'=>$userInfo->content->tel,
        ];

        //查看是否存在该冰鸟用户
        $BnUser = BnUser::where('bn_user_id',$userInfo->content->user_id)->find();

        if (!$BnUser) {//用户不存在 则添加到数据库
            $BnUserInfo = BnUser::create($userData);
            if (!$BnUserInfo) {
                return json(['message'=>'系统错误'],203);
            }
        }else{//用户存在则更新用户资料
            $BnUser->bn_user_name = $userData['bn_user_name'];
            $BnUser->bn_user_id = $userData['bn_user_id'];
            $BnUser->real_num = $userData['real_num'];
            $BnUser->real_name = $userData['real_name'];
            $BnUser->phone = $userData['phone'];
            $BnUser->save();
            $BnUser = BnUser::where('bn_user_id',$userInfo->content->user_id)->find();
        }
        return json(['message'=>'OK','data'=>['cms-token'=>$tokenInfo->content->access_token],'state'=>1],200);
    }
}
 