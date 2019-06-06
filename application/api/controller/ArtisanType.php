<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\ArtisanType AS MArtisanType;
use think\Request;
use think\Loader;

class ArtisanType extends Common
{
    /**
     * 文章分类信息查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ArtisanTypeInfo(Request $request)
    {   
        var_dump("12313");exit();
    }

    /**
     * 获取当前文章分类下的子分类查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ArtisanTypeChildren($id,Request $request)
    {   
        $data = [
            'id'=>$id,
        ];
        //参数过滤
        $validate = Loader::validate('ArtisanTypeChildren');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        $ArtisanType = MArtisanType::get($id);
        $ArtisanTypeChildren = MArtisanType::where('p_id','=',$id)->where('status',0)->select();
        return json(['p_title'=>$ArtisanType->name,'data'=>$ArtisanTypeChildren]);
    }
}
 