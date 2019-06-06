<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\SystemConfig;
use app\api\model\CdkType AS MCdkType;
use app\api\model\CdkList;
use think\Request;
use think\Loader;
use think\Cache;

class Config extends Common
{
    /**
     * 礼包配置查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Gift(Request $request)
    {   
        $SystemConfig = SystemConfig::where('name','GIFT_ID')->find();
        $CdkType = MCdkType::where('id','in',$SystemConfig->value)->select();

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
 