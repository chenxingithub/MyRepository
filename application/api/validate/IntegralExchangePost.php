<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class IntegralExchangePost extends Validate{

	protected $rule = array(
		'token'   => 'require',
		'user_id'   => 'require',
		'exchange_id' => 'require',
		
	);
	protected $message = array(
		'token.require'    => '缺少token参数',
		'user_id.require'    => '缺少user_id参数',
		'exchange_id.require'    => '缺少兑换ID',
	);
}