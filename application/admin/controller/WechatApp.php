<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\WechatAppFocus as MWechatAppFocus;
use app\admin\model\WechatAppGames as MWechatAppGames;
use think\Request;

class WechatApp extends ApiCommon
{
    /**
     * 游戏列表查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function GamesGet(Request $request)
    {   
        $query = new MWechatAppGames();
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('title', 'LIKE', '%' . $keyword . '%');
        }
        $WechatAppFocus = $query->order('sort desc')->paginate($request->get('limit'));
        return json($WechatAppFocus);
    }

    /**
     * 游戏列表添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function GamesPost(Request $request)
    {   

        $data = [
            'img_url'=>$request->post('img_url'),
            'title'=>$request->post('title'),
            'jump_url'=>$request->post('jump_url'),
            'nature'=>$request->post('nature'),
            'label'=>$request->post('label'),
            'describe'=>$request->post('describe'),
            'is_recommend'=>$request->post('is_recommend'),
        ];

        $WechatAppGames = MWechatAppGames::create($data);

        if ($WechatAppGames) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }


    /**
     * 游戏列表修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function GamesPut($id,Request $request)
    {   

        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->ChangeGameStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->changeGameSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->changeGameContent($request,$id);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
    }

    /**
     * 游戏列表修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeGameContent($request,$id)
    {   
        $WechatAppGames = MWechatAppGames::get($id);

        $WechatAppGames->img_url = $request->post('img_url');
        $WechatAppGames->jump_url = $request->post('jump_url');
        $WechatAppGames->title = $request->post('title');
        $WechatAppGames->nature = $request->post('nature');
        $WechatAppGames->label = $request->post('label');
        $WechatAppGames->describe = $request->post('describe');
        $WechatAppGames->degree_heat = $request->post('degree_heat');
        $WechatAppGames->is_recommend = $request->post('is_recommend');
        $res = $WechatAppGames->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }    

    /**
     * 游戏列表状态修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function ChangeGameStatus($request,$id)
    {   
        $WechatAppGames = MWechatAppGames::get($id);
        $WechatAppGames->status = $request->post('status');
        $res = $WechatAppGames->save();
        if ($res) {
          return json(['message'=>'使用/禁用，成功！']);
        }
        return json(['message'=>'使用/禁用, 失败 请联系IT部的小学生！'],203);
    }

    /**
     * 游戏列表置顶修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeGameSort($request,$id)
    {   
        $WechatAppGames = MWechatAppGames::get($id);
        $WechatAppGames->sort = $request->post('sort');
        $res = $WechatAppGames->save();
        if ($res) {
          return json(['message'=>'修改排序，成功！']);
        }
        return json(['message'=>'修改排序, 失败！'],203);
    }
    /**
     * 游戏列表删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function GamesDelete($id)
    {   
        $res = MWechatAppGames::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
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
        $query = new MWechatAppFocus();
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('url', 'LIKE', '%' . $keyword . '%');
        }
        $WechatAppFocus = $query->order('sort desc')->paginate($request->get('limit'));
        return json($WechatAppFocus);
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
            'img_url'=>$request->post('img_url'),
            'jump_url'=>$request->post('jump_url'),
        ];

        $WechatAppFocus = MWechatAppFocus::create($data);

        if ($WechatAppFocus) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
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
        $res = MWechatAppFocus::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
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
     * 焦点图内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeContent($request,$id)
    {   
        $WechatAppFocus = MWechatAppFocus::get($id);
        $WechatAppFocus->img_url = $request->post('img_url');
        $WechatAppFocus->jump_url = $request->post('jump_url');
        $res = $WechatAppFocus->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
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
        $WechatAppFocus = MWechatAppFocus::get($id);
        $WechatAppFocus->status = $request->post('status');
        $res = $WechatAppFocus->save();
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
        $WechatAppFocus = MWechatAppFocus::get($id);
        $WechatAppFocus->sort = $request->post('sort');
        $res = $WechatAppFocus->save();
        if ($res) {
          return json(['message'=>'修改排序，成功！']);
        }
        return json(['message'=>'修改排序, 失败！'],203);
    }
}
 