<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\SpiritPlate as MSpiritPlate;
use app\admin\model\SpirtConfigure as MSpirtConfigure;
use app\admin\model\SpirtChannelConfigure as MSpirtChannelConfigure;
use think\Db;
use think\Request;

class SpiritConfigure extends ApiCommon
{

    /**
     * 精灵基础配置单个查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function SingleGet(Request $request)
    {   

        $SpirtConfigure = MSpirtConfigure::where('game_id',$request->get('game_id'))->find();
        if (!$SpirtConfigure) {
                $data = [
                    'game_id'=>$request->get('game_id')
                ];
                $SpirtConfigure = MSpirtConfigure::create($data);
        }


        //查询对应渠道的配置
        $SpirtChannelConfigure = MSpirtChannelConfigure::where('game_id',$request->get('game_id'))->select();

        $SpirtConfigure->channel_configure = $SpirtChannelConfigure;

        return json($SpirtConfigure);

        
    }

    /**
     * 精灵基础配置修改
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
            case 'changeFeedback': //另外的修改类型
                return $this->changeFeedback($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->changeContent($request,$id);
            case 'changeProhibitChannel': //另外的修改类型
                return $this->changeProhibitChannel($request,$id);
            case 'changeChannelConfigure': //另外的修改类型
                return $this->changeChannelConfigure($request,$id);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
    }

    /**
     * 微信渠道配置
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeChannelConfigure($request,$id)
    {   
        if ($id) {
            $SpirtChannelConfigure = MSpirtChannelConfigure::get($id);
            $SpirtChannelConfigure->channel = $request->post('channel');
            $SpirtChannelConfigure->qq_group_img = $request->post('qq_group_img');
            $SpirtChannelConfigure->qq_group_introduce = $request->post('qq_group_introduce');
            $SpirtChannelConfigure->qq_group_num = $request->post('qq_group_num');
            $SpirtChannelConfigure->wechat_img = $request->post('wechat_img');
            $SpirtChannelConfigure->wechat_introduce = $request->post('wechat_introduce');
            $SpirtChannelConfigure->wechat_num = $request->post('wechat_num');
            $res = $SpirtChannelConfigure->save();
            if ($res) {
              return json(['message'=>'修改成功！']);
            }
            return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
        }else{
            $data = [
                'channel'=>$request->post('channel'),
                'game_id'=>$request->post('game_id'),
                'qq_group_img'=>$request->post('qq_group_img'),
                'qq_group_introduce'=>$request->post('qq_group_introduce'),
                'qq_group_num'=>$request->get('qq_group_num'),
                'wechat_img'=>$request->post('wechat_img'),
                'wechat_introduce'=>$request->post('wechat_introduce'),
                'wechat_num'=>$request->post('wechat_num'),
                'type'=>$request->post('type')
            ];
            $SpirtChannelConfigure = MSpirtChannelConfigure::create($data);
            if ($SpirtChannelConfigure) {
              return json(['message'=>'添加成功！']);
            }
            return json(['message'=>'添加失败，请联系IT部的小学生！'],203);


        
        }

    }

    /**
     * 渠道配置删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ChannelConfigureDelete($id)
    {

        $res = MSpirtChannelConfigure::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }

    /**
     * 精灵基础配置渠道ID修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeProhibitChannel($request,$id)
    {   
        $SpirtConfigure = MSpirtConfigure::get($id);
        $SpirtConfigure->prohibit_channel = $request->post('prohibit_channel');
        $res = $SpirtConfigure->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }

    /**
     * 精灵基础配置内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeContent($request,$id)
    {   
        $SpirtConfigure = MSpirtConfigure::get($id);
        $SpirtConfigure->wechat_num = $request->post('wechat_num');
        $SpirtConfigure->wechat_introduce = $request->post('wechat_introduce');
        $SpirtConfigure->qq_group_num = $request->post('qq_group_num');
        $SpirtConfigure->qq_group_introduce = $request->post('qq_group_introduce');
        $SpirtConfigure->wechat_img = $request->post('wechat_img');
        $SpirtConfigure->qq_group_img = $request->post('qq_group_img');
        $res = $SpirtConfigure->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }

    /**
     * 精灵基础配置修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeFeedback($request,$id)
    {   
        // 启动事务
        Db::startTrans();
        try{
            $SpiritPlate = MSpiritPlate::get($request->post('plate_id'));
            $SpiritPlate->name = $request->post('name');
            $res = $SpiritPlate->save();

            $SpirtConfigure = MSpirtConfigure::get($id);
            $SpirtConfigure->feedback_text = $request->post('feedback_text');
            $res2 = $SpirtConfigure->save();
            if ($res !== false&&$res2 !== false) {
                // 提交事务
              Db::commit();
              return json(['message'=>'修改成功！']);
            }
              Db::rollback();
              return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
        } catch (\Exception $e) {
            dump($e);
            // 回滚事务
            Db::rollback();
        }
    }

}