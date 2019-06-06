<?php
namespace app\admin\model;

class WechatKeyword extends \think\Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'oa_wechat_keyword';
	protected $autoWriteTimestamp = 'timestamp';

    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    public function replys()
    {
        return $this->hasMany('WechatReply','keyword_id')->field('keyword_id,title,describe,img_url,text_content,url,cdk_type_id,sub_keyword');;
    }
}