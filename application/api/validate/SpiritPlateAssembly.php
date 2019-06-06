<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class SpiritPlateAssembly extends Validate{

	protected $rule = array(
		'spirit_plate_id'   => 'require|number'
	);
	protected $message = array(
		'spirit_plate_id.require'    => '缺少精灵板块id参数',
		'spirit_plate_id.number'    => '游戏精灵板块id格式错误'
	);
}