<?php
// +----------------------------------------------------------------------
// | Description: 精灵反馈
// +----------------------------------------------------------------------
// | Author: 冰鸟 <772369024@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\CustomerServiceAppeal as MCustomerServiceAppeal;
use app\api\model\CustomerServiceInvoice as MCustomerServiceInvoice;
use think\Request;
use think\Loader;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CustomerService extends Common
{
    
    /**
     * 客服发票提交
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function InvoicePost(Request $request)
    {   
       $data = [
            'game_name'=>$request->post('gameName'),
            'user_name'=>$request->post('userName'),
            'real_name'=>$request->post('realName'),
            'payment_method'=>$request->post('paymentMethod'),
            'phone'=>$request->post('phone'),
            'order_number'=>$request->post('orderNumber'),
            'address'=>$request->post('address'),
            'total_amount'=>$request->post('totalAmount'),
            'email'=>$request->post('email')
        ];

        $CustomerServiceInvoice = MCustomerServiceInvoice::create($data);

        if ($CustomerServiceInvoice) {
          return json(['message'=>'提交成功！']);
        }
        return json(['message'=>'提交失败，请联系客服！'],203);

    }

    /**
     * 客服申诉提交
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function AppealPost(Request $request)
    {   
        $appeal_number = date('YmdHis', time()).str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $data = [
            'gameName'=>$request->post('gameName'),
            'channel'=>$request->post('channel'),
            'districtClothes'=>$request->post('districtClothes'),
            'roleName'=>$request->post('roleName'),
            'userName'=>$request->post('userName'),
            'roleId'=>$request->post('roleId'),
            'phoneIMEI'=>$request->post('phoneIMEI'),
            'registerTime'=>$request->post('registerTime'),
            'bindingPhone'=>$request->post('bindingPhone'),
            'email'=>$request->post('email'),
            'type'=>$request->post('type'),
            'identity'=>$request->post('identity'),
            'recharge'=>$request->post('recharge'),
            'urlArr'=>$request->post('urlArr'),
            'channel'=>$request->post('channel'),
            'resetPassword'=>$request->post('resetPassword')
        ];
        //参数过滤
/*        $validate = Loader::validate('CustomerServiceAppealPost');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }*/

            //获取ip 和 ip城市  淘宝 弃用
            // $ip = $_SERVER["REMOTE_ADDR"];
            // $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;//淘宝借口需要填写ip
            // $ipData=json_decode(file_get_contents($url));  
            // if((string)$ip->code=='1'){
            //    return false;
            // }
        

        //获取ip 和 ip城市  百度地图api
        $ip = $_SERVER["REMOTE_ADDR"];
        $ak = 'DrugfKgNU2O5b31LOMlXsbPnW5RerxWs';
        $url = file_get_contents("http://api.map.baidu.com/location/ip?ip=$ip&ak=$ak");
        $ipData = json_decode($url,true);
        if (!$ipData) {
            return false;
        }

        $appeal_city = $ipData['content']['address'];

        $count = MCustomerServiceAppeal::where('appeal_ip',$_SERVER["REMOTE_ADDR"])->whereTime('created_at', 'today')->count();
        if ($count>=5) {
            return json(['message'=>'十分抱歉，您今天的申诉次数已超！'],203);
        }
        switch ($data['type']) {
            case 1: // 账号找回
                        $appealType = '账号找回';  
                        $createData = [
                                'type'=>$data['type'],
                                'appeal_number'=>$appeal_number,
                                'game_name'=>$data['gameName'],
                                'appeal_ip'=>$_SERVER["REMOTE_ADDR"],
                                'appeal_city'=>$appeal_city,
                                'phone'=>$data['bindingPhone'],
                                'mailbox'=>$data['email'],
                                'channel'=>$data['channel'],
                                'district_clothes'=>$data['districtClothes'],
                                'role_name'=>$data['roleName'],
                                'role_id'=>$data['roleId'],
                                'register_time'=>$data['registerTime'],
                                'phone_imei'=>$data['phoneIMEI'],
                                'recharge_img'=>$data['urlArr']
                            ];
                            break;
            case 2: // 密码找回
                        $appealType = '密码找回'; 
                        $createData = [
                                'type'=>$data['type'],
                                'appeal_number'=>$appeal_number,
                                'game_name'=>$data['gameName'],
                                'appeal_ip'=>$_SERVER["REMOTE_ADDR"],
                                'appeal_city'=>$appeal_city,
                                'phone'=>$data['bindingPhone'],
                                'mailbox'=>$data['email'],
                                'channel'=>$data['channel'],
                                'district_clothes'=>$data['districtClothes'],
                                'role_name'=>$data['roleName'],
                                'role_id'=>$data['roleId'],
                                'register_time'=>$data['registerTime'],
                                'phone_imei'=>$data['phoneIMEI'],
                                'identity'=>$data['identity'],
                                'recharge_total'=>$data['recharge'],
                                'user_name'=>$data['userName'],
                                'recharge_img'=>$data['urlArr']
                            ];
                            break;
            case 3: // 解封申请
                        $appealType = '解封申请';
                        $createData = [
                                'type'=>$data['type'],
                                'appeal_number'=>$appeal_number,
                                'game_name'=>$data['gameName'],
                                'appeal_ip'=>$_SERVER["REMOTE_ADDR"],
                                'appeal_city'=>$appeal_city,
                                'phone'=>$data['bindingPhone'],
                                'mailbox'=>$data['email'],
                                'channel'=>$data['channel'],
                                'district_clothes'=>$data['districtClothes'],
                                'role_name'=>$data['roleName'],
                                'role_id'=>$data['roleId'],
                                'register_time'=>$data['registerTime'],
                                'phone_imei'=>$data['phoneIMEI'],
                                'identity'=>$data['identity'],
                                'recharge_total'=>$data['recharge'],
                                'user_name'=>$data['userName'],
                                'recharge_img'=>$data['urlArr'],
                            ];
                            break;
            case 4: // 解绑手机
                        $appealType = '解绑手机';
                        $createData = [
                                'type'=>$data['type'],
                                'appeal_number'=>$appeal_number,
                                'game_name'=>$data['gameName'],
                                'appeal_ip'=>$_SERVER["REMOTE_ADDR"],
                                'appeal_city'=>$appeal_city,
                                'phone'=>$data['bindingPhone'],
                                'mailbox'=>$data['email'],
                                'channel'=>$data['channel'],
                                'district_clothes'=>$data['districtClothes'],
                                'role_name'=>$data['roleName'],
                                'role_id'=>$data['roleId'],
                                'register_time'=>$data['registerTime'],
                                'phone_imei'=>$data['phoneIMEI'],
                                'identity'=>$data['identity'],
                                'recharge_total'=>$data['recharge'],
                                'user_name'=>$data['userName'],
                                'recharge_img'=>$data['urlArr'],
                                'reset_password'=>$data['resetPassword']
                            ];
            default://
                break;
        }
        $CustomerServiceAppeal = MCustomerServiceAppeal::create($createData);

        //推送邮件给申诉玩家的邮箱
        $mail = new PHPMailer(true);
        try {
            // 服务器设置
            $mail->isSMTP(); // 使用SMTP
            $mail->Host = 'smtp.exmail.qq.com'; // 服务器地址
            $mail->SMTPAuth = true; // 开启SMTP验证
            $mail->Username = 'bnkf@ibingniao.com'; // SMTP 用户名（你要使用的邮件发送账号）
            $mail->Password = 'Bnkf123456'; // SMTP 密码
            $mail->SMTPSecure = 'ssl'; // 开启TLS 可选
            $mail->Port = 465; // 端口

            // 收件人
            $mail->setFrom('bnkf@ibingniao.com'); // 来自
            //$mail->addAddress('1722897633@qq.com'); // 添加一个收件人
            $mail->addAddress($data['email']); // 可以只传邮箱地址


            // 附件
            //$mail->addAttachment($resume_url); // 添加附件
            //$mail->addAttachment('2.zip'); // 可以设定名字

            // 内容
            $mail->isHTML(true); // 设置邮件格式为HTML
            $mail->CharSet = 'utf-8';
            $mail->Subject = '【冰鸟游戏 】玩家申诉反馈'; //邮件主题
            $mail->Body = '<p><font size="3">&nbsp; &nbsp; &nbsp; 您好，您已经成功提交了&ldquo;'.$appealType.'&rdquo;申诉， 申诉编号：<font color="#000000"><font color="#FF0000"><u>'.$appeal_number.'</u></font>。</font>请您牢记申诉编号，成功提交申诉后，我们将会在24小时内将处理结果至该邮箱    。同时，您可以凭借该申诉编号通过账号申诉中心&ldquo;自助查询&ldquo;查询处理进度 。<br /></font></p>
<p>&nbsp;</p>
<p><font size="3"><br />*此邮件为系统发送 ，请勿直接回复<br />*如有其它疑问 ，请您联系游戏客服 QQ 公众号：800180310 咨询<br /><br /></font></p>'; //邮件内容
            $mail->send();

            if ($CustomerServiceAppeal) {
              return json(['message'=>'申诉成功！','data'=>['appeal_number'=>$appeal_number]]);
            }
            return json(['message'=>'申诉失败，请联系客服！'],203);
        } catch (Exception $e) {
            return json(['message'=>'该邮箱发送失败，请更换邮箱。'],203);
        }
    }

    /**
     * 玩家根据申诉号查询结果
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function AppealGet(Request $request)
    {   
       $CustomerServiceAppeal = MCustomerServiceAppeal::where("appeal_number",$request->get('appeal_number'))->find();
        if ($CustomerServiceAppeal) {
            if ($CustomerServiceAppeal->state == 0) {
                return json(['message'=>'申诉成功！','data'=>['appeal_result'=>'您好，经查询您提交的问题正在处理中，请您不要着急，我们会尽快核实处理。']]);
            }
          return json(['message'=>'申诉成功！','data'=>['appeal_result'=>$CustomerServiceAppeal->appeal_result]]);
        }
        return json(['message'=>'十分抱歉，没有查询到该订单号的任何信息'],203);
    }

    /**
     * 玩家根据user_name查询申请列表结果
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function UserNameGet(Request $request)
    {   
        $CustomerServiceAppeal = MCustomerServiceAppeal::where("user_name",$request->get('user_name'))->select();
        if ($CustomerServiceAppeal) {
            return json(['message'=>'查询成功！','data'=>$CustomerServiceAppeal]);
        }
         return json(['message'=>'十分抱歉，没有查询到您的申请信息'],203);
    }
}
 