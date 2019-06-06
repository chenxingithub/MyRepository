<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class CdkTypeGet extends Validate{

	protected $rule = array(
		'page'   => 'require|number',
		'limit'   => 'require|number',
		'game_id'   => 'require|number'
	);
	protected $message = array(
		'game_id.require'    => '缺少游戏参数',
		'game_id.number'    => '游戏参数格式错误',
		'page.require'    => '缺少页码参数',
		'page.number'    => '页码参数格式错误',
		'limit.require'    => '缺少条数参数',
		'limit.number'    => '条数参数格式错误',
	);
}