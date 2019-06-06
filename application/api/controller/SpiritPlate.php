<?php
// +----------------------------------------------------------------------
// | Description: 精灵板块
// +----------------------------------------------------------------------
// | Author: 冰鸟 <772369024@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\SpiritPlate as MSpiritPlate;
use think\Request;
use think\Loader;

class SpiritPlate extends Common
{
    /**
     * 查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Get(Request $request)
    {   
        $data = [
            'game_id'=>$request->get('game_id')
        ];
        //参数过滤
        $validate = Loader::validate('SpiritPlate');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        $SpiritPlate = MSpiritPlate::where("game_id",$data['game_id'])->where("status",0)->order('sort desc')->select();
        return json(['data'=>$SpiritPlate]);
    }


}
 