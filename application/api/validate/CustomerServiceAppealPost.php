<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class CustomerServiceAppealPost extends Validate{

	protected $rule = array(
		'gameName'   => 'require',
		'channel'   => 'require',
		'districtClothes' => 'require',
		'roleName' => 'require',
		'phoneIMEI' => 'require',
		'registerTime' => 'require',
		'bindingPhone' => 'require',
		'email' => 'require|email',
		'type' => 'require|number',
		'channel' => 'require|number'

	);
	protected $message = array(
	);
}