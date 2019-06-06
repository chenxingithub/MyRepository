<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class SpiritPlate extends Validate{

	protected $rule = array(
		'game_id'   => 'require|number'
	);
	protected $message = array(
		'game_id.require'    => '缺少游戏参数',
		'game_id.number'    => '游戏参数格式错误'
	);
}