<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class SpiritConfigure extends Validate{

	protected $rule = array(
		'game_id'   => 'require|number',
		// 'channel'   => 'require|number'
	);
	protected $message = array(
		'game_id.require'    => '缺少游戏参数',
		'game_id.number'    => '游戏参数格式错误',
		'channel.require'    => '缺少渠道参数',
		'channel.number'    => '游戏渠道参数格式错误'
	);
}