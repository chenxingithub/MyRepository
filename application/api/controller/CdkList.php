<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\CdkList AS MCdkList;
use app\api\model\CdkType AS MCdkType;
use app\common\lib\Curl;
use app\api\model\BnUser;
use app\api\model\Games;
use think\Request;
use think\Loader;

class CdkList extends Common
{
    /**
     * 礼包cdk获取
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function CdkListReceive($id,Request $request)
    {   
        $data = [
            'id'=>$id,
        ];
        //参数过滤
        $validate = Loader::validate('CdkListReceive');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        $CdkType = MCdkType::where('id',$id)->where('type',1)->find();
        if (!$CdkType) {
            return json(['message'=>"该礼包类型为空"],203);
        }
        $GamesInfo = Games::get($CdkType->game_id);

        if ($GamesInfo->nature == 1) {
            $token = Request::instance()->header('cms-token');
            //用token换取冰鸟用户信息
            $userParams = [
                'access_token'=>$token,
                'app_id'=>'110000000',
                'from_h5'=>1,
            ];
            //实例化用户表
            $MBnUser = new BnUser;
            //生成签名sign
            $sign = $MBnUser->BnUserSign($userParams);
            $userParams['sign'] = $sign;
            
            //请求冰鸟返回用户信息
            $userInfoJson = Curl::post('http://m.1aiyouxi.com/oauth/userInfo',$userParams);
            $userInfo = json_decode($userInfoJson);

            if ($userInfo->ret != 1) {
                return json(['message'=>'冰鸟授权登录失败','state'=>3]);
            }
            if (!$userInfo->content->tel) {
                return json(['message'=>"请您先绑定手机号",'state'=>2]);
            }
            
            $BnUser = $MBnUser->where('user_id',$userInfo->content->user_id)->find();
            $count = MCdkList::where('user_id',$BnUser->id)->where('cdk_type_id',$id)->count();

            if ($count) {
                return json(['message'=>'您已经领取过该礼包，请不要重复领取。','state'=>4]);
            }
        }

        $Cdk = MCdkList::where('cdk_type_id',$id)->where('status',0)->order('id desc')->find();
        if (!$Cdk) {
            return json(['message'=>"该礼包类型为空"],203);
        }
        $Cdk->status = 1;

        if ($GamesInfo->nature == 1) {
        $Cdk->user_id = $BnUser->id;
        }

        $res = $Cdk->save();
        if ($res) {
            return json(['data'=>$Cdk]);
        }else{
            return json(['message'=>"cdk领取失败"],203);
        }
    }
}
 