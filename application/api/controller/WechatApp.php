<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\WechatAppFocus as MWechatAppFocus;
use app\api\model\WechatAppGames as MWechatAppGames;
use think\Request;
use think\Loader;
use think\Cache;

class WechatApp extends Common
{
    /**
     * 焦点图查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function InitialGet(Request $request)
    {   
        $WechatAppFocus  = MWechatAppFocus::order('sort desc')->where('status',0)->select();
        $WechatAppRecommendGames  = MWechatAppGames::order('sort desc')->where('status',0)->where('is_recommend',1)->select();
        foreach ($WechatAppRecommendGames as $key => $value) {
            $WechatAppRecommendGames[$key]['label_arr'] = explode(',', $value->label);
        }
        return json(['WechatAppFocus'=>$WechatAppFocus,'WechatAppRecommendGames'=>$WechatAppRecommendGames]);
    }

    /**
     * 游戏列表查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function GamesGet(Request $request)
    {   
       $data = [
            'page'=>$request->get('page'),
            'limit'=>$request->get('limit'),
        ];
        $validate = Loader::validate('WechatAppGamesGet');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        $WechatAppGames  = MWechatAppGames::order('sort desc')->where('status',0)->paginate($request->get('limit'))->each(function($item, $key){
                    $item->label_arr = explode(',', $item->label);
                });
        return json($WechatAppGames);
    }

    /**
     * 游戏列表查询推荐
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function RecommendGamesGet(Request $request)
    {   
       $data = [
            'page'=>$request->get('page'),
            'limit'=>$request->get('limit'),
        ];
        $validate = Loader::validate('WechatAppGamesGet');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        $WechatAppGames  = MWechatAppGames::order('sort desc')->where('status',0)->where('is_recommend',1)->paginate($request->get('limit'))->each(function($item, $key){
                    $item->label_arr = explode(',', $item->label);
                });
        return json($WechatAppGames);
    }

}
 