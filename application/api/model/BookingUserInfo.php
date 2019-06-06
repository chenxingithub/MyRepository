<?php
/*
 * @Company: 广州冰鸟网络科技有限公司
 * @Description: 游戏预约用户信息model
 * @Author: ChenXin
 * @Email: chenxin@ibingniao.com
 * @Date: 2019-04-10 15:10:40
 * @LastEditTime: 2019-04-11 16:54:28
 */

namespace app\api\model;

class BookingUserInfo extends \think\Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'oa_booking_user_info';
	protected $autoWriteTimestamp = 'timestamp';

    // 定义时间戳字段名
    protected $createTime = 'created_at';

    public static function isTelphone($tel) {
        return preg_match('/^\d+$/', $tel);
    }

}