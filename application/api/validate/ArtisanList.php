<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class ArtisanList extends Validate{

	protected $rule = array(
		'keyword'   => 'require',
		'game_id'   => 'require|number',
		'terminal'   => 'require|number',
		'page'   => 'require|number',
		'limit'   => 'require|number',
	);
	protected $message = array(
		'keyword.require'    => '缺少文章搜索参数',
		'page.require'    => '缺少页码参数',
		'page.number'    => '页码参数格式错误',
		'limit.require'    => '缺少条数参数',
		'limit.number'    => '条数参数格式错误',
		'terminal.require'    => '缺少终端参数',
		'terminal.number'    => '终端参数格式错误',
	);
}