<?php
namespace app\admin\model;

class WechatMenu extends \think\Model
{
	const WZDQDWD = "1";
    const MENUTYPE = [
            '1' => 'view',
            '2' => 'click',
    ];
    // 设置当前模型对应的完整数据表名称
    protected $table = 'oa_wechat_menu';
	protected $autoWriteTimestamp = 'timestamp';

    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    //给jstree提供json数据
    public function getTreeData($data, $pId)
    {
        /*$permissions = $this->query()->get()->toArray();*/
        $tree = [];
        if ($data) {
				foreach($data as $k => $v)
				{
				  if($v['parent_id'] == $pId)
				  {        //父亲找到儿子
				   $v['son'] = $this->getTreeData($data, $v['id']);
				   $tree[] = $v;
				   //unset($WechatMenus[$k]);
				  }
				}
        }
		return $tree;
    }

}