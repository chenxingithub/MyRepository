<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class FocusImg extends Validate{

	protected $rule = array(
		'game_id'   => 'require|number',
		'position'  => 'require',
		'terminal'  => 'require|number',
	);
	protected $message = array(
		'game_id.require'    => '缺少游戏参数',
		'game_id.number'    => '游戏参数格式错误',
		'position.require'    => '缺少位置参数',
		'terminal.require'    => '缺少终端参数',
		'terminal.number'    => '终端参数格式错误',
	);
}