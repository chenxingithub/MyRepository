<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class WechatAppGamesGet extends Validate{

	protected $rule = array(
		'page'   => 'require|number',
		'limit'   => 'require|number'
	);
	protected $message = array(
		'page.require'    => '缺少页码参数',
		'page.number'    => '页码参数格式错误',
		'limit.require'    => '缺少条数参数',
		'limit.number'    => '条数参数格式错误'
	);
}