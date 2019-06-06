<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\ResumeList AS MResumeList;
use think\Request;
use think\Loader;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ResumeList extends Common
{
    
    /**
     * 简历投递
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Post(Request $request)
    {   

        // 保存上传文件
        $file = $request->file('resume_file');        // 获取表单提交过来的文件  
        $data = [
            'position_name'=>$request->post('position_name'),
            'college'=>$request->post('college'),
            'phone'=>$request->post('phone'),
            'name'=>$request->post('name'),
            'major'=>$request->post('major'),
            'position_type_id'=>$request->post('position_type_id')
        ];
        //参数过滤
        $validate = Loader::validate('ResumeListPost');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        
        $name = $request->post('name');
        //判断用户是否已经对该岗位有投递简历记录
        $ResumeList = MResumeList::where("name",$name)->where("artisan_type_id",$request->post('position_type_id'))->find();
        if ($ResumeList) {
            return json(['message'=>'请您不要对同一职位，多次投递！'],205);
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->validate(['size'=>5242880])->move(ROOT_PATH . 'public'  . DS . 'upload'. DS . 'resume');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $resume_url = 'upload'. DS . 'resume'. DS . $info->getSaveName();;
            }else{
                // 上传失败获取错误信息
                return json(['message'=>$file->getError()],203);
            }
        }else{
            return json(['message'=>'请上传简历文件'],422);
        }        
        $data = [
            'resume_url'=>$resume_url,
            'college'=>$request->post('college'),
            'phone'=>$request->post('phone'),
            'name'=>$request->post('name'),
            'major'=>$request->post('major'),
            'artisan_type_id'=>$request->post('position_type_id')
        ];
        //数据插入数据库
        $ResumeList = MResumeList::create($data);
        //推送简历给小龙虾的邮箱
        $mail = new PHPMailer(true);
        try {
            // 服务器设置
            $mail->isSMTP(); // 使用SMTP
            $mail->Host = 'smtp.exmail.qq.com'; // 服务器地址
            $mail->SMTPAuth = true; // 开启SMTP验证
            $mail->Username = 'admin@ibingniao.com'; // SMTP 用户名（你要使用的邮件发送账号）
            $mail->Password = 'Ibingniao520'; // SMTP 密码
            $mail->SMTPSecure = 'ssl'; // 开启TLS 可选
            $mail->Port = 465; // 端口

            // 收件人
            $mail->setFrom('admin@ibingniao.com'); // 来自
            //$mail->addAddress('1722897633@qq.com'); // 添加一个收件人
            $mail->addAddress('xbnhr@ibingniao.com'); // 可以只传邮箱地址


            // 附件
            $mail->addAttachment($resume_url); // 添加附件
            //$mail->addAttachment('2.zip'); // 可以设定名字

            // 内容
            $mail->isHTML(true); // 设置邮件格式为HTML
            $mail->Subject = $request->post('name').'-'.$request->post('phone').'-'.$request->post('college').'-'.$request->post('position_name').'-'.$request->post('major').'-'.$request->post('msg_source'); //邮件主题
            $mail->Body = '应聘岗位：' . $request->post('position_name'); //邮件内容
            $mail->send();
            return json(['message'=>'简历上传成功，请耐心等候HR的回复']);
        } catch (Exception $e) {
            return json(['message'=>'$mail->ErrorInfo'],203);
        }
    }

}
 