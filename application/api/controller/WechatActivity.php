<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\CdkRecord AS MCdkRecord;
use app\api\model\CdkType AS MCdkType;
use app\api\model\WechatUser AS MWechatUser;
use EasyWeChat\Factory;
use think\Request;
use think\Loader;


class WechatActivity extends Common
{



    /**
     * CMS微信分享配置
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ShareConfig(Request $request)
    { 
                //写入配置
        $config = [
            'app_id' => 'wx88c84ff004115e9e',
            'secret' => 'f07a4d799a451826fa1a1a5305eddf8c',
            'token'     => '0f5b8397ca127ad3f6a4462e2d6989b7',
            'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file' => '/data/web/bnweb-cms2/runtime/log/wechat.log',
            ],
        ];

        //实例化类
        $app = Factory::officialAccount($config);
        $app->jssdk->setUrl($request->get('url'));
        $WechatShareConfigure = $app->jssdk->buildConfig(array('onMenuShareAppMessage','onMenuShareTimeline'), false,false,false);
        return json($WechatShareConfigure);
    }
    /**
     * 微信活动Cdk 获取页面
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function CdkView(Request $request)
    { 
        $data = [
            'cdk_type_id'=>$request->get('cdk_type_id'),
            'open_id'=>$request->get('open_id'),
        ];
        //参数过滤
        $validate = Loader::validate('WechatCdkView');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        $CdkRecord = MCdkRecord::where("open_id",$data['open_id'])->where("cdk_type_id",$data['cdk_type_id'])->find();
        if (!$CdkRecord&&!$CdkRecord->code) {
           return json(['message'=>'非法访问'],203);
        }
        $CdkType = MCdkType::where("id",$data['cdk_type_id'])->find();
        return json(['data'=>["cdk_type"=>$CdkType,"CdkRecord"=>$CdkRecord]]);
    }

    /**
     * 微信授权登录
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function Login(Request $request)
    {   
        //写入配置
        $config = [
            'app_id' => 'wx88c84ff004115e9e',
            'secret' => 'f07a4d799a451826fa1a1a5305eddf8c',
            'token'     => '0f5b8397ca127ad3f6a4462e2d6989b7',
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => '/data/web/bnweb-cms2/runtime/log/wechat.log',
            ],
            'oauth' => [
                          'scopes'   => ['snsapi_userinfo'],
                          'callback' => 'yh-cms-api/wechat-login',
                      ],
        ];

        $app = Factory::officialAccount($config);
        $oauth = $app->oauth;

        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();


        $data = $user->original;

        $WechatUserData = [
            'openid' => $data['openid'],
            'nickname' => $data['nickname'],
            'sex' => $data['sex'],
            'language' => $data['language'],
            'city' => $data['city'],
            'province' => $data['province'],
            'country' => $data['country'],
            'image'=>$data['headimgurl'],
        ];
/*        $app = Factory::officialAccount($config);
        $oauth = $app->oauth;

        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();


        $data = $user->original;*/
        //伪造数据
/*        $data = [
            'openid' => 'oJUXo0j5qiklTTvesSp6_N1a3vgo2',
            'nickname' => '马健涛涛涛涛2',
            'sex' => 1,
            'language' => 'zh_c',
            'city' => '肇庆',
            'province' => '广东省',
            'country' => '中国',
            ,
            'image'=>'static/upload/image/\201806105651528278209.png',
        ];

        $WechatUserData = [
            'openid' => $data['openid'],
            'nickname' => $data['nickname'],
            'sex' => $data['sex'],
            'language' => $data['language'],
            'city' => $data['city'],
            'province' => $data['province'],
            'country' => $data['country']
        ];*/
        $data['expiry_time'] = time()+900;

        $MWechatUser = new MWechatUser;

        if (!MWechatUser::where('openid',$WechatUserData['openid'])->find()) {
            //写入数据库
            $SpiritType = MWechatUser::create($WechatUserData);
        }

        //获取用户token
        $token = $MWechatUser->Encryption($data);
        return json(['message'=>'OK','data'=>['token'=>$token]],200);
    }

}
 