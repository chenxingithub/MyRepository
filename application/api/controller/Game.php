<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\Games;
use think\Request;
use think\Loader;

class Game extends Common
{
    /**
     * 游戏信息查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function GameInfo(Request $request)
    {   
        $data = [
            'idfa'=>$request->get('idfa'),
        ];
        //参数过滤
        $validate = Loader::validate('Games');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }  
        $Games  = Games::where('idfa','=',$request->get('idfa'))->select();
        if (!$Games) {
           return json(['message'=>"不存在该游戏标识"],203);
        }
        return json(['data'=>$Games]);
    }
    /**
     * 游戏列表查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function GameList(Request $request)
    {   
        $data = [
            'page'=>$request->get('page'),
            'limit'=>$request->get('limit'),
        ];
        $validate = Loader::validate('GameList');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        $Games  = Games::order('sort desc')->paginate($request->get('limit'));
        return json(['data'=>$Games]);
    }

}
 