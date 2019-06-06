<?php
// +----------------------------------------------------------------------
// | Description: 精灵板块
// +----------------------------------------------------------------------
// | Author: 冰鸟 <772369024@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\SpiritConfigure as MSpiritConfigure;
use app\api\model\SpirtChannelConfigure as MSpirtChannelConfigure;
use think\Request;
use think\Loader;

class SpiritConfigure extends Common
{
    /**
     * 查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Get(Request $request)
    {   
        $data = [
            'game_id'=>$request->get('game_id'),
            'channel'=>$request->get('channel')
        ];
        //参数过滤
        $validate = Loader::validate('SpiritConfigure');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        $SpiritConfigure = MSpiritConfigure::where("game_id",$data['game_id'])->find();

        $QqSpirtChannelConfigure = MSpirtChannelConfigure::where('find_in_set(:channel,channel)',['channel'=>$data['channel']])->where('type',1)->where('game_id',$data['game_id'])->find();
        $WechatSpirtChannelConfigure = MSpirtChannelConfigure::where('find_in_set(:channel,channel)',['channel'=>$data['channel']])->where('type',2)->where('game_id',$data['game_id'])->find();
         $SpiritConfigure->channel_configure = (object)array();
        $SpiritConfigure->channel_configure->wechat_num = $WechatSpirtChannelConfigure?$WechatSpirtChannelConfigure->wechat_num:'';
        $SpiritConfigure->channel_configure->wechat_introduce = $WechatSpirtChannelConfigure?$WechatSpirtChannelConfigure->wechat_introduce:'';
        $SpiritConfigure->channel_configure->wechat_img = $WechatSpirtChannelConfigure?$WechatSpirtChannelConfigure->wechat_img:'';
        $SpiritConfigure->channel_configure->qq_group_num = $QqSpirtChannelConfigure?$QqSpirtChannelConfigure->qq_group_num:'';
        $SpiritConfigure->channel_configure->qq_group_introduce = $QqSpirtChannelConfigure?$QqSpirtChannelConfigure->qq_group_introduce:'';
        $SpiritConfigure->channel_configure->qq_group_img = $QqSpirtChannelConfigure?$QqSpirtChannelConfigure->qq_group_img:'';
        

        return json(['data'=>$SpiritConfigure]);
    }


}
 