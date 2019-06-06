<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class ResumeListPost extends Validate{

	protected $rule = array(
		'position_name'  => 'require',
		'position_type_id'  => 'require|number',
	);
	protected $message = array(
		'position_name.require'    => '缺少应聘职位名称参数',
		'position_type_id.require'    => '缺少应聘职位id参数',
		'position_type_id.number'    => '应聘职位id参数格式错误',
	);
}