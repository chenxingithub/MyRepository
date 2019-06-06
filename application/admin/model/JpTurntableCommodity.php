<?php
namespace app\admin\model;

class JpTurntableCommodity extends \think\Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'oa_jp_turntable_commodity';
	protected $autoWriteTimestamp = 'timestamp';

    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
}