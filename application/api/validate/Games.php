<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class Games extends Validate{

	protected $rule = array(
		'idfa'   => 'require',
	);
	protected $message = array(
		'idfa.require'    => '缺少游戏标识',
	);
}