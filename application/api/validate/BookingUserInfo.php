<?php
/*
 * @Company: 广州冰鸟网络科技有限公司
 * @Description: 参数验证规则
 * @Author: ChenXin
 * @Email: chenxin@ibingniao.com
 * @Date: 2019-04-10 14:50:41
 * @LastEditTime: 2019-04-12 16:06:22
 */
namespace app\api\validate;

use think\Validate;

/**
* 设置模型
*/
class BookingUserInfo extends Validate{

	protected $rule = array(
		'mobile'   => 'require',
		'gameId'   => 'require|number',
		'email'   => 'require',
		'smsCode'   => 'require',
		'andOrIos' => 'require|number',
	);
	protected $message = array(
		'mobile.require'    => '缺少手机号',
		'gameId.require'    => '缺少游戏Id',
		'gameId.number'    => '游戏Id参数格式错误',
		'email.require'    => '缺少email参数',
		'smsCode.require'    => '缺少smsCode参数',
		'andOrIos.require'    => '缺少手机系统标识参数',
		'andOrIos.number'    => '手机系统标识参数格式错误',
	);
}