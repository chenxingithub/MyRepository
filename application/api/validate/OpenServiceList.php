<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class OpenServiceList extends Validate{

	protected $rule = array(
		'id'   => 'require|number',
	);
	protected $message = array(
		'id.require'    => '缺少游戏id参数',
		'id.number'    => '游戏id参数格式错误',
	);
}