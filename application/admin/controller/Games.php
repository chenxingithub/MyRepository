<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games as MGames;
use think\Request;

class Games extends ApiCommon
{
    /*    const PAGE_SIZE = 15;

    public $pageTitle = '游戏列表';*/

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
        $query = new MGames();
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('game_name', 'LIKE', '%' . $keyword . '%')
                ->WhereOr('idfa', 'LIKE', '%' . $keyword . '%');
        }
        $sort = $request->get('sort');
        if (is_numeric($sort)) {
            $query->where('sort','=',$sort);
        }
        $QueryType = $request->get('query_type');
        if ($QueryType) {
            $Games = $query->order('id desc')->select();
        }else{
            $Games = $query->order('id desc')->paginate($request->get('limit'));
        }
        return json($Games);
    } 
   /**
     * 游戏列表查询单个
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function GamesSingleGet($id,Request $request)
    {   
        $Game = MGames::get($id);
         return json($Game);
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
     * 置顶修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeSort($request,$id)
    {   
        $Games = MGames::get($id);
        $Games->sort = $request->post('sort');
        $res = $Games->save();
        if ($res) {
          return json(['message'=>'置顶/取消置顶，成功！']);
        }
        return json(['message'=>'置顶/取消置顶, 失败 请联系IT部的小学生！'],203);
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
        $res = MGames::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
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
            'idfa'=>$request->post('idfa'),
            'game_name'=>$request->post('game_name'),
            'ios_dow_code_img'=>$request->post('ios_dow_code_img'),
            'android_dow_code_img'=>$request->post('android_dow_code_img'),
            'ios_download_url'=>$request->get('ios_download_url'),
            'android_download_url'=>$request->post('android_download_url'),
            'official_url'=>$request->post('official_url'),
            'video_url'=>$request->post('video_url'),
            'service_qq'=>$request->post('service_qq'),
            'service_phone'=>$request->post('service_phone'),
            'logo_url'=>$request->post('logo_url'),
            'label'=>$request->post('label'),
            'type'=>$request->post('type'),
            'sketch'=>$request->post('sketch'),
            'nature'=>$request->post('nature'),

        ];
        $Games = MGames::create($data);
        if ($Games) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

    /**
     * 游戏列表内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeContent($request,$id)
    {   
        $Games = MGames::get($id);
        $Games->idfa = $request->post('idfa');
        $Games->game_name = $request->post('game_name');
        $Games->ios_dow_code_img = $request->post('ios_dow_code_img');
        $Games->android_dow_code_img = $request->post('android_dow_code_img');
        $Games->official_url = $request->post('official_url');
        $Games->video_url = $request->post('video_url');
        $Games->ios_download_url = $request->post('ios_download_url');
        $Games->android_download_url = $request->post('android_download_url');
        $Games->service_qq = $request->post('service_qq');
        $Games->service_phone = $request->post('service_phone');
        $Games->logo_url = $request->post('logo_url');
        $Games->label = $request->post('label');
        $Games->type = $request->post('type');
        $Games->sketch = $request->post('sketch');
        $Games->nature = $request->post('nature');
        $res = $Games->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }

}
 