<?php
namespace app\admin\model;

class Jurisdiction extends \think\Model
{
	const BASICSMENUS = [34,35,52,53];  //基础菜单
	const BASEMENUS = [42,43,44,45,46,96];	//官网菜单
	const WECHATMENUS = [45,46,47,48,49,50,51,93]; // 公众号菜单
	const SPIRITMENUS = [57,60,62,63,64];	//游戏精灵菜单
	const BASEUSERS = ['115','12','17','234']; //管理员身份的用户

    // 设置当前模型对应的完整数据表名称
    protected $table = 'oa_auc_jurisdiction';
	protected $autoWriteTimestamp = 'timestamp';

    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
}