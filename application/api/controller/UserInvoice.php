<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\UserInvoice AS MUserInvoice;
use think\Request;
use think\Loader;

class UserInvoice extends Common
{
    /**
     * 用户发票申请
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Post(Request $request)
    {   
        $data = [
            'user_name'=>$request->post('user_name'),
            'user_id'=>$request->post('user_id'),
            'name'=>$request->post('name'),
            'email'=>$request->post('email'),
            'phone'=>$request->post('phone'),
            'orders'=>$request->post('orders'),
            'money'=>$request->post('money')
        ];

        //参数过滤
        $validate = Loader::validate('UserInvoice');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }


        $count = MUserInvoice::where('user_id',$data['user_id'])->whereTime('created_time', 'month')->count();

        if ($count >= 2) {
            return json(['message'=>'您本月开据发票次数已达到上限！'],302);
        }

        $UserInvoice = MUserInvoice::where('orders',$data['orders'])->find();

        if ($UserInvoice) {
            return json(['message'=>'您已开据此发票，请勿重复提交！'],302);
        }

        $data = [
            'user_name'=>$request->post('user_name'),
            'user_id'=>$request->post('user_id'),
            'name'=>$request->post('name'),
            'email'=>$request->post('email'),
            'phone'=>$request->post('phone'),
            'orders'=>$request->post('orders'),
            'money'=>$request->post('money')
        ];
        $UserInvoice = MUserInvoice::create($data);

        if ($UserInvoice) {
          return json(['message'=>'申请成功！']);
        }
        return json(['message'=>'申请失败，请联系客服！'],203);
    }
}
 