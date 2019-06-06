<?php
// +----------------------------------------------------------------------
// | Description: 官网礼包配置组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\SystemConfig AS MSystemConfig;
use think\Request;

class Config extends ApiCommon
{

    /**
     * 官网礼包配置查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function OfficialGiftGet(Request $request)
    {   
        $SystemConfig = MSystemConfig::where('name','GIFT_ID')->find();
        return json($SystemConfig);
    }

    /**
     * 官网礼包配置修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function OfficialGiftPut($id,Request $request)
    {   
        $SystemConfig = MSystemConfig::get($id);
        $SystemConfig->value = $request->post('value');
        $res = $SystemConfig->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
          return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }

    /**
     * 官网礼包配置添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function OfficialGiftPost(Request $request)
    {   

        $data = [
            'name'=>'GIFT_ID',
            'value'=>$request->post('value'),
            'group'=>11,
            'need_auth'=>0
        ];
        $SystemConfig = MSystemConfig::create($data);

        if ($SystemConfig) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }
}
 