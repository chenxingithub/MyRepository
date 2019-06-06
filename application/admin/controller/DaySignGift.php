<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\DaySignGift AS MDaySignGift;
use think\Request;

class DaySignGift extends ApiCommon
{

    /**
     * 日签到礼品列表模块-查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function DaySignGiftGet(Request $request)
    {   
        $DaySignGift = MDaySignGift::where('game_id',$request->get('game_id'))->paginate($request->get('limit'));
        return json($DaySignGift);
    }

    /**
     * 日签到礼品列表修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function DaySignGiftPut($id,Request $request)
    {

        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->CdkTypeChangeStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->changeSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->DaySignGiftChangeContent($request,$id);
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
    private function DaySignGiftChangeContent($request,$id)
    {   
        $DaySignGift = MDaySignGift::get($id);
        $DaySignGift->img_url = $request->post('img_url');
        $DaySignGift->title = $request->post('title');
        $DaySignGift->day_num = $request->post('day_num');
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
            'day_num'=>$request->post('day_num'),
            'game_id'=>$request->post('game_id')
        ];
        $DaySignGift = MDaySignGift::create($data);

        if ($DaySignGift) {
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
        $res = MDaySignGift::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }
}
 