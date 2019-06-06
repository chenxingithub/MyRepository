<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\CdkType AS MCdkType;
use think\Request;

class Statistics extends ApiCommon
{

    /**
     * 游戏礼包领取状况
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function StatisticsGet(Request $request)
    {   
        //获取对应游戏的礼包分类id
        $CdkTypeList = MCdkType::where('game_id',$request->get('GameId'))->select();
        foreach ($CdkTypeList as $key => $value) {
            $CdkTypeArr[] = $value->id;
        }
        
        
        $query = new MCdkType();
        $query->where('game_id','=',$request->get('game_id'));
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('title', 'LIKE', '%' . $keyword . '%');
        }
        $position = $request->get('position');
        if (is_numeric($position)) {
            $query->where('position','=',$position);
        }
        $terminal = $request->get('terminal');
        if ($terminal) {
            $query->where('terminal','=',$terminal);
        }
        $Focus = $query->order('sort desc')->paginate($request->get('limit'));
        return json($Focus);
    }

}
 