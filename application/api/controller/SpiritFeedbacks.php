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

class SpiritFeedbacks extends Common
{
    /**
     * 查询回复读取
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ReadGet(Request $request)
    {   
       $SpiritFeedbackCount = MSpiritFeedback::where('to',$request->get('from_id'))->where('is_read',0)->count();
       return json(['message'=>'查询成功','state'=>0,'number'=>$SpiritFeedbackCount]);
    }


    /**
     * 查询回复处理
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ReadPost(Request $request)
    {   
        $res = MSpiritFeedback::where('to',$request->post('from_id'))->update(['is_read' => 1]);

        if ($res!==false) {
          return json(['message'=>'消息处理成功','state'=>0]);
        }
        return json(['message'=>'系统错误，消息处理失败','state'=>1]);

       
    }

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
            'service_area'=>$request->post('service_id'),
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


        $SpiritConfigure = MSpiritFeedback::where(
                                                    function ($query) use ($data) {
                                                                        $query->where("game_id",$data['game_id'])->where("to",$data['to']);
                                                                      })
                                          ->whereOr(
                                                    function ($query) use ($data) {
                                                                            $query->where('from',$data['to'])->where("game_id",$data['game_id']);
                                                                      })->order('id desc')->paginate($request->get('limit'));
        return json(['data'=>$SpiritConfigure]);

    }
}
 