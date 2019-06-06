<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\ArtisanType;
use app\admin\model\SpiritType as MSpiritType;
use think\Request;

class SpiritType extends ApiCommon
{

    /**
     * 精灵分类查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Get(Request $request)
    {   
        $query = new MSpiritType();
        $query->where('game_id','=',$request->get('game_id'));
        $QueryType = $request->get('query_type');
        if ($QueryType) {
           $SpiritType = $query->order('id desc')->select();
        }else{
           $SpiritType = $query->order('id desc')->paginate($request->get('limit'));
        }
        return json($SpiritType);
    }

    /**
     * 精灵分类查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Post(Request $request)
    {   
        $data = [
            'name'=>$request->post('name'),
            'game_id'=>$request->post('game_id')
        ];
        $SpiritType = MSpiritType::create($data);

        if ($SpiritType) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

    /**
     * 精灵分类修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Put($id,Request $request)
    {
        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->ChangeStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->changeSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->changeContent($request,$id);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
    }

    /**
     * 精灵分类内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeContent($request,$id)
    {   
        $SpiritType = MSpiritType::get($id);
        $SpiritType->name = $request->post('name');
        $res = $SpiritType->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }

    /**
     * 精灵分类内容删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Delete($id)
    {
        $res = MSpiritType::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }

}
 