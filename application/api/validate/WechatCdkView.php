<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class WechatCdkView extends Validate{

	protected $rule = array(
		'cdk_type_id'   => 'require|number',
		'open_id'  => 'require',
	);
	protected $message = array(
		'cdk_type_id.require'    => '缺少礼包类型参数',
		'cdk_type_id.number'    => '礼包类型参数错误',
		'open_id.require'    => '缺少微信open_id参数',
	);
}