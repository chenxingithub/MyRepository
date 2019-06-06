<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\ArtisanType AS MArtisanType;
use app\admin\model\FocusImg;
use think\Request;

class ArtisanType extends ApiCommon
{
    /*    const PAGE_SIZE = 15;

    public $pageTitle = '我在大清当皇帝';*/

    /**
     * 文章分类查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ArtisanTypeGet(Request $request)
    {   
        $query = new MArtisanType();
        $game_id = $request->get('game_id');
        if ($game_id) {
            $query->where('game_id','=',$request->get('game_id'));
        }
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%')
                  ->WhereOr('id', 'LIKE', '%' . $keyword . '%') ;

        }
        $status = $request->get('status');
        if (is_numeric($status)) {
            $query->where('status','=',$status);
        }
        $p_id = $request->get('p_id');
        if (is_numeric($p_id)) {
            $query->where('p_id','=',$p_id);
        }
        $QueryType = $request->get('query_type');
        if ($QueryType) {
           $ArtisanType = $query->order('id desc')->select();
        }else{
        $ArtisanType = $query->order('id desc')->paginate($request->get('limit'));
        }
        return json($ArtisanType);
    }

    /**
     * 文章分类修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ArtisanTypePut($id,Request $request)
    {

        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->ArtisanTypeChangeStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->changeSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->ArtisanTypeChangeContent($request,$id);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
    }

    /**
     * 文章分类状态修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function ArtisanTypeChangeStatus($request,$id)
    {   
        $ArtisanType = MArtisanType::get($id);
        $ArtisanType->status = $request->post('status');
        $res = $ArtisanType->save();
        if ($res) {
          return json(['message'=>'使用/禁用，成功！']);
        }
          return json(['message'=>'使用/禁用, 失败 请联系IT部的小学生！'],203);
    }

    /**
     * 文章分类内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function ArtisanTypeChangeContent($request,$id)
    {   
        $ArtisanType = MArtisanType::get($id);
        $ArtisanType->name = $request->post('name');
        $ArtisanType->p_id = $request->post('p_id');
        $res = $ArtisanType->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }

    /**
     * 文章分类添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ArtisanTypePost(Request $request)
    {
        $data = [
            'p_id'=>$request->post('p_id'),
            'name'=>$request->post('name'),
            'game_id'=>$request->post('game_id')
        ];
        $ArtisanType = MArtisanType::create($data);

        if ($ArtisanType) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

    /**
     * 文章分类删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ArtisanTypeDelete($id)
    {
        $res = MArtisanType::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }
}
 