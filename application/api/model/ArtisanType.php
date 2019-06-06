<?php
namespace app\api\model;

class ArtisanType extends \think\Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'oa_admin_artisan_type';
	protected $autoWriteTimestamp = 'timestamp';

    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    public function getChildrenIds($sort_id)
       {

       $ArtisanType = $this->where('p_id','=',$sort_id)->select();
       if ($ArtisanType)
       {
           foreach ($ArtisanType as $key=>$val)
           {
               $ids .= $val->id.',';
               $ids .= $this->getChildrenIds ($val->id);
           }
       }
       return $ids;
    }
}