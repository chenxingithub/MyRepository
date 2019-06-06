<?php
namespace app\api\model;

class JpTurntableCommodity extends \think\Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'oa_jp_turntable_commodity';
	protected $autoWriteTimestamp = 'timestamp';

    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    //概率获取
    public function getRotate($prize_arr,$probabilityKey){
		$proSum=0;
		foreach($prize_arr as $v){
			if ($v->surplus_number>0) {
				$proSum+=$v->probability;
			}
			
		}
		foreach($prize_arr as $k=>$v){
			if ($v->surplus_number>0) {
				$randNum=mt_rand(1,$proSum);//随机数
				if($randNum<=$v->probability) {
					return $k;
				}else{
					$proSum-=$v->probability;
				}
			}
		}
    }
}