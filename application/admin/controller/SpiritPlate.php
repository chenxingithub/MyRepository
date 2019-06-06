<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\SpiritPlate as MSpiritPlate;
use app\admin\model\SpiritKeywordAssembly as MSpiritKeywordAssembly;
use think\Request;

class SpiritPlate extends ApiCommon
{

    /**
     * 精灵板块查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Get(Request $request)
    {   
        $query = new MSpiritPlate();
        $query->where('game_id','=',$request->get('game_id'));
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        }
        $SpiritType = $query->order('id desc')->paginate($request->get('limit'));
        return json($SpiritType);
    }

    /**
     * 精灵关键字组件查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function KeywordAssemblyGet(Request $request)
    {   
        $SpiritKeywordAssembly = MSpiritKeywordAssembly::where('spirit_plate_id','=',$request->get('spirit_plate_id'))->order('sort desc')->select();
        return json($SpiritKeywordAssembly);
    }


    /**
     * 精灵关键字组件添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function KeywordAssemblyPost(Request $request)
    {   
        $data = [
            'rule'=>$request->post('rule'),
            'title'=>$request->post('title'),
            'sort'=>$request->post('sort')?$request->post('sort'):0,
            'state'=>$request->post('state')?$request->post('state'):0,
            'label'=>$request->post('label')?$request->post('label'):0,
            'content_color'=>$request->post('content_color'),
            'content'=>$request->post('content'),
            'img'=>$request->post('img'),
            'spirit_plate_id'=>$request->post('spirit_plate_id')
        ];
        $SpiritKeywordAssembly = MSpiritKeywordAssembly::create($data);

        if ($SpiritKeywordAssembly) {
          return json(['message'=>'添加成功！','id'=>$SpiritKeywordAssembly->id]);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

    /**
     * 精灵关键字组件删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function KeywordAssemblyDelete($id)
    {
        $res = MSpiritKeywordAssembly::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }
    /**
     * 精灵板块添加
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
            'type'=>$request->post('type'),
            'game_id'=>$request->post('game_id')
        ];
        $SpiritPlate = MSpiritPlate::create($data);

        if ($SpiritPlate) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }


    /**
     * 精灵板块状态修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function ChangeStatus($request,$id)
    {   
        $SpiritPlate = MSpiritPlate::get($id);
        $SpiritPlate->status = $request->post('status');
        $res = $SpiritPlate->save();
        if ($res) {
          return json(['message'=>'使用/禁用，成功！']);
        }
        return json(['message'=>'使用/禁用, 失败 请联系IT部的小学生！'],203);
    }

    /**
     * 精灵关键字组件修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function KeywordAssemblyPut($id,Request $request)
    {
        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->KeywordAssemblyChangeStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->KeywordAssemblyChangeSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->KeywordAssemblyChangeContent($request,$id);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
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
        $SpiritPlate = MSpiritPlate::get($id);
        $SpiritPlate->name = $request->post('name');
        $res = $SpiritPlate->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }


    /**
     * 精灵关键字组件内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function KeywordAssemblyChangeContent($request,$id)
    {   
        $SpiritKeywordAssembly = MSpiritKeywordAssembly::get($id);
        $SpiritKeywordAssembly->rule = $request->post('rule');
        $SpiritKeywordAssembly->title = $request->post('title');
        $SpiritKeywordAssembly->label = $request->post('label')?$request->post('label'):0;
        $SpiritKeywordAssembly->content_color = $request->post('content_color');
        $SpiritKeywordAssembly->content = $request->post('content');
        $SpiritKeywordAssembly->img = $request->post('img');
        $res = $SpiritKeywordAssembly->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }

    /**
     * 精灵关键字组件排序修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function KeywordAssemblyChangeSort($request,$id)
    {   
        $SpiritKeywordAssembly = MSpiritKeywordAssembly::get($id);
        $SpiritKeywordAssembly->sort = $request->post('sort');
        $res = $SpiritKeywordAssembly->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }
    

    /**
     * 精灵关键字组件状态修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function KeywordAssemblyChangeStatus($request,$id)
    {   
        $SpiritKeywordAssembly = MSpiritKeywordAssembly::get($id);
        $SpiritKeywordAssembly->state = $request->post('state');
        $res = $SpiritKeywordAssembly->save();
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
        $res = MSpiritPlate::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }

}
 