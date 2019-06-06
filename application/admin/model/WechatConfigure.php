<?php
namespace app\admin\model;

class WechatConfigure extends \think\Model
{
	const WZDQDWD = "1";
    // 设置当前模型对应的完整数据表名称
    protected $table = 'oa_wechat_configure';
	protected $autoWriteTimestamp = 'timestamp';

    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
}