<?php
// +----------------------------------------------------------------------
// | Description: 精灵板块
// +----------------------------------------------------------------------
// | Author: 冰鸟 <772369024@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\SpiritPlateAssembly as MSpiritPlateAssembly;
use think\Request;
use think\Loader;

class SpiritPlateAssembly extends Common
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
            'spirit_plate_id'=>$request->get('spirit_plate_id')
        ];
        //参数过滤
        $validate = Loader::validate('SpiritPlateAssembly');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        $SpiritPlateAssembly = MSpiritPlateAssembly::where("spirit_plate_id",$data['spirit_plate_id'])->select();
        return json(['data'=>$SpiritPlateAssembly]);
    }


}
 