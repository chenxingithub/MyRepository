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

class Focus extends ApiCommon
{

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
          return json(['message'=>'修改排序，成功！']);
        }
        return json(['message'=>'修改排序, 失败！'],203);
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
        $FocusImg->jump_url = $request->post('jump_url');
        $FocusImg->position = $request->post('position');
        $FocusImg->terminal = $request->post('terminal');
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
            'jump_url'=>$request->post('jump_url'),
            'position'=>$request->post('position'),
            'terminal'=>$request->post('terminal'),
            'game_id'=>$request->post('game_id')
        ];
        $FocusImg = FocusImg::create($data);

        if ($FocusImg) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

}
 