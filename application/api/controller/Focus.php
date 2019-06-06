<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\FocusImg;
use think\Request;
use think\Loader;
use think\Cache;

class Focus extends Common
{
    /*    const PAGE_SIZE = 15;*/
    /**
     * 焦点图查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function FocusGet(Request $request)
    {   
        $data = [
            'game_id'=>$request->get('game_id'),
            'position'=>$request->get('position'),
            'terminal'=>$request->get('terminal'),
        ];
        //参数过滤
        $validate = Loader::validate('FocusImg');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        $query = new FocusImg();
        $terminal = $request->get('terminal');
        if ($terminal) {
            $query->where('terminal','in',$terminal.',0');
        }
        $FocusImg = $query->where("game_id","=",$request->get('game_id'))->where("position","in",$request->get('position'))->where("status","=",0)->order('sort desc')->select();
        //重组
        $Regroup = $this->Regroup('position',$FocusImg);
        return json(['data'=>$Regroup]);
    }

    /*    const PAGE_SIZE = 15;*/
    /**
     * 焦点图查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Kki(Request $request)
    {   
        $Redis = \ibnthink\RedisPool::get();
        $Redis->set('ceshi_1','1231233');
        $a = $Redis->get('ceshi_1');
        var_dump($a);exit();
    }

}
 