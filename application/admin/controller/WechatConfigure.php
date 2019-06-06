<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\WechatConfigure as MWechatConfigure;
use think\Request;

class WechatConfigure extends ApiCommon
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
    public function GetConfigure(Request $request)
    {   

        $WechatConfigure = MWechatConfigure::where('game_id',$request->get('game_id'))->find();;
        return json($WechatConfigure);
    } 

    /**
     * 游戏列表修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function PutConfigure($id,Request $request)
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
     * 微信基础配置内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeContent($request,$id)
    {   
        $WechatConfigure = MWechatConfigure::where('game_id',$id)->find();

        //判断公众号配置是否已经存在
        if (!$WechatConfigure) {
            $data = [
                'game_id'=>$id,
                'name'=>$request->post('name'),
                'token'=>$request->post('token'),
                'server_url'=>$request->post('server_url'),
                'original_id'=>$request->post('original_id'),
                'wechat_num'=>$request->post('wechat_num'),
                'head_portrait'=>$request->post('head_portrait'),
                'app_id'=>$request->post('app_id'),
                'app_secret'=>$request->post('app_secret'),
                'qr_code'=>$request->post('qr_code'),
                'type'=>$request->post('type'),
                'access_state'=>$request->post('access_state')
            ];
            $res = MWechatConfigure::create($data); 
        }else{
            $WechatConfigure->token = $request->post('token');
            $WechatConfigure->name = $request->post('name');
            $WechatConfigure->server_url = $request->post('server_url');
            $WechatConfigure->original_id = $request->post('original_id');
            $WechatConfigure->wechat_num = $request->post('wechat_num');
            $WechatConfigure->head_portrait = $request->post('head_portrait');
            $WechatConfigure->app_id = $request->post('app_id');
            $WechatConfigure->app_secret = $request->post('app_secret');
            $WechatConfigure->qr_code = $request->post('qr_code');
            $WechatConfigure->type = $request->post('type');
            $WechatConfigure->access_state = $request->post('access_state');
            $res = $WechatConfigure->save();
        }
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
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
            'sketch'=>$request->post('sketch'),
        ];
        $Games = MGames::create($data);
        if ($Games) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

}
 