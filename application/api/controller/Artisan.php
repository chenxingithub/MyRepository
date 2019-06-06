<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\Artisan as MArtisan;
use app\api\model\ArtisanType;
use think\Request;
use think\Loader;

class Artisan extends Common
{

    /**
     * 文章列表查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return 
     */
    public function ArtisanList(Request $request)
    {   
        $data = [
            'game_id'=>$request->get('game_id'),
            'keyword'=>$request->get('keyword'),
            'terminal'=>$request->get('terminal'),
            'page'=>$request->get('page'),
            'limit'=>$request->get('limit'),
        ];
        $validate = Loader::validate('ArtisanList');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        //获取该游戏id的文章分类
        $ArtisanType = ArtisanType::where('game_id',$request->get('game_id'))->select();
        foreach ($ArtisanType as $key => $value) {
            $ArtisanTypeId[] = $value->id;
        }
        $query = new MArtisan();
        $query->where('artisan_type_id','in',$ArtisanTypeId);
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('title', 'LIKE', '%' . $keyword . '%');
        }
        $terminal = $request->get('terminal');
        if ($terminal) {
            $query->where('terminal','in',$terminal.',0');
        }

        $Artisan = $query->order('updated_at desc')->paginate($request->get('limit'));
        return json($Artisan);
    }


    public function ArtisanListPut($id,Request $request)
    {
        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->ArtisanChangeStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->ArtisanChangeSort($request,$id);
            case 'ChangeFabulousNum': //另外的修改类型
                return $this->ArtisanChangeFabulousNum($request,$id);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
    }

    /**
     * 文章列表点赞修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function ArtisanChangeFabulousNum($request,$id)
    {   
        $Artisan = MArtisan::get($id);
        $Artisan->fabulous_num += 1;
        $res = $Artisan->save();
        if ($res) {
          return json(['message'=>'点赞，成功！']);
        }
        return json(['message'=>'点赞, 失败！'],203);
    }
    /**
     * 根据文章分类获取文章信息查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ArtisanInfo(Request $request)
    {   
        $data = [
            'artisan_type_id'=>$request->get('artisan_type_id'),
            'terminal'=>$request->get('terminal'),
            'page'=>$request->get('page'),
            'limit'=>$request->get('limit'),
        ];
        $validate = Loader::validate('ArtisanInfo');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        $artisan_type_id = explode(',',$request->get('artisan_type_id'));
        //参数过滤
        $ArtisanReform = [];
        foreach ($artisan_type_id as $key => $value) {
            $ArtisanType = new ArtisanType();
            $ArtisanTypeInfo = $ArtisanType->get($value);
            $ChildrenIds = $ArtisanType->getChildrenIds($value);
            $query = new MArtisan();
            $terminal = $request->get('terminal');
            if ($terminal) {
                $query->where('terminal','in',$terminal.',0');
            }
            $Artisan  = $query->where("status","=",0)->where('artisan_type_id','in',$ChildrenIds.$value)->order('updated_at desc')->field('id,title,subtitled,traffic_volume,label,sketch,jump_url,bg_img,icon,created_at,updated_at,artisan_type_id,enclosure_url')->paginate($request->get('limit'));
            $ArtisanReform[$value]['name'][] = $ArtisanTypeInfo->name;
            $ArtisanReform[$value]['data'][] = $Artisan;
        }
        return json($ArtisanReform);
    }

    /**
     * 根据文章id获取文章信息
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ArtisanSingle($id,Request $request)
    {   
        if ($request->get('type') == 1) {
            
            $Artisan  = MArtisan::where('id','in',$id)->select();
            return json(['data'=>$Artisan]);
        }else{
            $data = [
                'id'=>$id,
            ];
            //参数过滤
            $validate = Loader::validate('ArtisanSingle');
            if(!$validate->check($data)){
               return json(['message'=>$validate->getError()],422);
            }
            $Artisan  = MArtisan::get($id);
            return json(['data'=>$Artisan]);
        }
    }

}
 