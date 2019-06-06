<?php

namespace app\api\validate;
use think\Validate;
/**
* 设置模型
*/
class SpiritFeedbackPost extends Validate{

	protected $rule = array(
		'game_id'   => 'require|number',
		'from'   => 'require',
		'feedback_message' => 'require',
	);
	protected $message = array(
		'game_id.require'    => '缺少游戏参数',
		'feedback_message.require'    => '缺少反馈内容参数',
		'game_id.number'    => '游戏参数格式错误',
		'from.require'    => '缺少发送者唯一标识参数',
	);
}