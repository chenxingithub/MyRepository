<?php
// +----------------------------------------------------------------------
// | Description: 精灵反馈
// +----------------------------------------------------------------------
// | Author: 冰鸟 <772369024@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\SpiritFeedback as MSpiritFeedback;
use think\Request;
use think\Loader;

class SpiritFeedback extends Common
{
    /**
     * 用户反馈提交
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Post(Request $request)
    {   
        $data = [
            'game_id'=>$request->post('game_id'),
            'feedback_message'=>$request->post('feedback_message'),
            'from'=>$request->post('from')
        ];
        //参数过滤
        $validate = Loader::validate('SpiritFeedbackPost');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }

        $data = [
            'service_area'=>$request->post('service_area'),
            'from'=>$request->post('from'),
            'to'=>'admin',
            'user_id'=>$request->post('user_id'),
            'role_name'=>$request->post('role_name'),
            'vip'=>$request->post('vip'),
            'role_rank'=>$request->post('role_rank'),
            'feedback_message'=>$request->post('feedback_message'),
            'game_id'=>$request->post('game_id')
        ];
        $SpiritFeedback = MSpiritFeedback::create($data);

        if ($SpiritFeedback) {
          return json(['message'=>'反馈成功！']);
        }
        return json(['message'=>'反馈失败，请联系客服！'],203);
    }


    /**
     * 用户反馈回复查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ReplyGet(Request $request)
    {   
        $data = [
            'game_id'=>$request->get('game_id'),
            'limit'=>$request->get('limit'),
            'page'=>$request->get('page'),
            'to'=>$request->get('to')
        ];
        //参数过滤
        $validate = Loader::validate('SpiritFeedbackReply');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        $SpiritConfigure = MSpiritFeedback::where("game_id",$data['game_id'])->where("to",$data['to'])->whereOr('from',$data['to'])->order('id desc')->paginate($request->get('limit'));
        return json(['data'=>$SpiritConfigure]);

    }
}
 