<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class CdkListReceive extends Validate{

	protected $rule = array(
		'id'   => 'require|number',
	);
	protected $message = array(
		'id.require'    => '缺少文章id参数',
		'id.number'    => '文章id参数格式错误',
	);
}