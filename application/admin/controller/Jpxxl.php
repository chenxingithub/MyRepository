<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\ArtisanType;
use app\admin\model\FocusImg;
use think\Request;

class Jpxxl extends ApiCommon
{
    /*    const PAGE_SIZE = 15;

    public $pageTitle = '我在大清当皇帝';*/


    /**
     * 基本配置
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */

    public function ConfigGet(Request $request){
        $Games = Games::where('id','=',Games::WZDQDWD)->find();
        return json($Games);
    }


    /**
     * 基本配置修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ConfigPut(Request $request)
    {   
        $game = Games::where('id','=',Games::WZDQDWD)->find();
        $game->game_name = $request->post('game_name');
        $game->dow_code_img = $request->post('dow_code_img');
        $game->official_url = $request->post('official_url');
        $res = $game->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败！请联系IT部的小学生！'],203);
    }

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
        $query = new FocusImg();
        $query->where('game_id','=',Games::WZDQDWD);
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('title', 'LIKE', '%' . $keyword . '%');
        }
        $position = $request->get('position');
        if (is_numeric($position)) {
            $query->where('position','=',$position);
        }
        $Focus = $query->order('sort desc,updated_at desc')->paginate($request->get('limit'));
        return json($Focus);
    }

    /**
     * 焦点图修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function FocusPut($id,Request $request)
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
     * 焦点图状态修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function ChangeStatus($request,$id)
    {   
        $FocusImg = FocusImg::get($id);
        $FocusImg->status = $request->post('status');
        $res = $FocusImg->save();
        if ($res) {
          return json(['message'=>'使用/禁用，成功！']);
        }
        return json(['message'=>'使用/禁用, 失败 请联系IT部的小学生！'],203);
    }

    /**
     * 焦点图置顶修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeSort($request,$id)
    {   
        $FocusImg = FocusImg::get($id);
        $FocusImg->sort = $request->post('sort');
        $res = $FocusImg->save();
        if ($res) {
          return json(['message'=>'置顶/取消置顶，成功！']);
        }
        return json(['message'=>'置顶/取消置顶, 失败 请联系IT部的小学生！'],203);
    }

    /**
     * 焦点图内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeContent($request,$id)
    {   
        $FocusImg = FocusImg::get($id);
        $FocusImg->picture = $request->post('picture');
        $FocusImg->title = $request->post('title');
        $FocusImg->position = $request->post('position');
        $res = $FocusImg->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }
    /**
     * 焦点图删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function FocusDelete($id)
    {
        $res = FocusImg::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }

    /**
     * 焦点图添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function FocusPost(Request $request)
    {
        $data = [
            'picture'=>$request->post('picture'),
            'title'=>$request->post('title'),
            'position'=>$request->post('position'),
            'game_id'=>Games::WZDQDWD
        ];
        $FocusImg = FocusImg::create($data);

        if ($FocusImg) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

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
        $query = new ArtisanType();
        $query->where('game_id','=',Games::WZDQDWD);
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        }
        $status = $request->get('status');
        if (is_numeric($status)) {
            $query->where('status','=',$status);
        }
        $ArtisanType = $query->order('id desc')->paginate($request->get('limit'));
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
        $ArtisanType = ArtisanType::get($id);
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
        $ArtisanType = ArtisanType::get($id);
        $ArtisanType->name = $request->post('name');
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
            'name'=>$request->post('name'),
            'game_id'=>Games::WZDQDWD
        ];
        $ArtisanType = ArtisanType::create($data);

        if ($FocusImg) {
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
        $res = ArtisanType::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }
}
 