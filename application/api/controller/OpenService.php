<?php
// +----------------------------------------------------------------------
// | Description: 开服组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\OpenService AS MOpenService;
use think\Request;
use think\Loader;

class OpenService extends Common
{

    /**
     * 开服列表
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function List(Request $request)
    {   
        $data = [
            'id'=>$request->get('game_id'),
        ];
        //参数过滤
        $validate = Loader::validate('OpenServiceList');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }

        if ($request->get('type')) {
            $OpenService = MOpenService::where('game_id','=',$request->get('game_id'))->where('type',$request->get('type'))->where('status',0)->order('open_service_time desc')->select();
        }else{
            $OpenService = MOpenService::where('game_id','=',$request->get('game_id'))->where('status',0)->order('open_service_time desc')->select();
        }
        
        return json(['data'=>$OpenService]);
    }
}
 