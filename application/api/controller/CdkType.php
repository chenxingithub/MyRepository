<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\CdkType AS MCdkType;
use app\api\model\CdkList;
use think\Request;
use think\Loader;

class CdkType extends Common
{
    /**
     * 礼包分类列表查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function CdkTypeGet(Request $request)
    {   
        $data = [
            'game_id'=>$request->get('game_id'),
            'page'=>$request->get('page'),
            'limit'=>$request->get('limit'),
        ];
        //参数过滤
        $validate = Loader::validate('CdkTypeGet');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        $TimeDate = date("Y-m-d H:i:s",time());
        $CdkType = MCdkType::where("game_id","=",$request->get('game_id'))->where("type","=",1)->where('oa_admin_cdk_type_copy.type',1)->where('oa_admin_cdk_type_copy.status',0)->where('start_at','<',$TimeDate)->where('expired_at','>',$TimeDate)->paginate($request->get('limit'));
         foreach ($CdkType as $key => $value) {
            //获取每个礼包的礼包码总数
            $CdkTypeTotal = CdkList::where('cdk_type_id',$value->id)->count();
            $CdkTypeSurplus = CdkList::where('cdk_type_id',$value->id)->where('status',0)->count();
            $CdkType[$key]['cdk_total_num'] = $CdkTypeTotal;
            $CdkType[$key]['surplus'] = $CdkTypeSurplus;
         }
        return json($CdkType);
    }
}
 