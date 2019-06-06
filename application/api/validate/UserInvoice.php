<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class UserInvoice extends Validate{

	protected $rule = array(
		'user_name'   => 'require',
		'user_id'   => 'require',
		'name'   => 'require',
		'email'   => 'require|email',
		'phone'   => 'require|number',
		'orders'   => 'require',
		'money'   => 'require'
	);
	protected $message = array(
		'user_name.require'    => '缺少用户名参数',
		'user_id.require'    => '缺少用户id参数',
		'name.require'    => '缺少真实姓名参数',
		'email.require'    => '缺少邮箱参数',
		'email.email'    => '邮箱格式错误',
		'phone.require'    => '缺少手机号码参数',
		'phone.number'    => '手机号码格式错误',
		'orders.require'    => '缺少订单号参数',
		'money.require'    => '缺少金额参数'
	);
}