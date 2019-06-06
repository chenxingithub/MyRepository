<?php
/*
 * @Company: 广州冰鸟网络科技有限公司
 * @Description: svip配置
 * @Author: ChenXin
 * @Email: chenxin@ibingniao.com
 * @Date: 2019-04-13 09:51:23
 * @LastEditTime: 2019-04-13 14:37:59
 */

namespace app\admin\controller;

use app\admin\model\Games;
use app\admin\model\ArtisanType;
use app\admin\model\SvipConfig;
use think\Request;

class Svip extends ApiCommon
{

    public function SvipConfigGet(Request $request)
    {   
        $query = new SvipConfig();
        $query->where('game_id','=',$request->get('game_id'));
        $channelId = $request->get('channelId');
        if ($channelId) {
            $query->where('channel_id', '=', $channelId);
        }
        $status = $request->get('status');
        if (is_numeric($status)) {
            $query->where('status','=',$status);
        }
        $svipConfig = $query->paginate($request->get('limit'));
        return json($svipConfig);
    }

}