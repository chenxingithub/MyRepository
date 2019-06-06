<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class SpiritKeyword extends Validate{

	protected $rule = array(
		'keyword'   => 'require',
		'game_id'   => 'require',
		'from'   => 'require',
		'type' => 'require|number'
	);
	protected $message = array(
		'keyword.require'    => '缺少关键字参数',
		'game_id.require'    => '缺少游戏参数',
		'from.require'    => '缺少唯一标识参数',
		'type.require'    => '缺少查询类型参数',
		'type.number'    => '查询类型格式错误'
	);
}