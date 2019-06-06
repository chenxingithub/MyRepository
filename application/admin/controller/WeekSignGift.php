<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\WeekSignGift AS MWeekSignGift;
use think\Request;

class WeekSignGift extends ApiCommon
{

    /**
     * 周签到礼品列表模块-查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function WeekSignGiftGet(Request $request)
    {   
        $WeekSignGift = MWeekSignGift::where('game_id',$request->get('game_id'))->paginate($request->get('limit'));
        return json($WeekSignGift);
    }

    /**
     * 日签到礼品列表修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function WeekSignGiftPut($id,Request $request)
    {

        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->CdkTypeChangeStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->changeSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->WeekSignGiftChangeContent($request,$id);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
    }

    /**
     * 文章分类状态修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function CdkTypeChangeStatus($request,$id)
    {   
        $CdkType = MCdkType::get($id);
        $CdkType->status = $request->post('status');
        $res = $CdkType->save();
        if ($res) {
          return json(['message'=>'使用/禁用，成功！']);
        }
          return json(['message'=>'使用/禁用, 失败 请联系IT部的小学生！'],203);
    }

    /**
     * 日签到礼品列表内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function WeekSignGiftChangeContent($request,$id)
    {   
        $DaySignGift = MWeekSignGift::get($id);
        $DaySignGift->img_url = $request->post('img_url');
        $DaySignGift->title = $request->post('title');
        $res = $DaySignGift->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }

    /**
     * 日签到礼品列表添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Post(Request $request)
    {
        $data = [
            'img_url'=>$request->post('img_url'),
            'title'=>$request->post('title'),
            'game_id'=>$request->post('game_id')
        ];
        $WeekSignGift = MWeekSignGift::create($data);

        if ($WeekSignGift) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

    /**
     * 日签到礼品列表删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Delete($id)
    {
        $res = MWeekSignGift::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }
}
 