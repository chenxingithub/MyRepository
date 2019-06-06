<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\OpenService AS MOpenService;
use think\Request;

class OpenService extends ApiCommon
{

    /**
     * 开服表查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Get(Request $request)
    {   
        $query = new MOpenService();
        $query->where('game_id','=',$request->get('game_id'));
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('open_service_name', 'LIKE', '%' . $keyword . '%');
        }
        $status = $request->get('status');
        if (is_numeric($status)) {
            $query->where('status','=',$status);
        }
        $QueryType = $request->get('query_type');
        if ($QueryType) {
           $OpenService = $query->order('open_service_time desc')->select();
        }else{
           $OpenService = $query->order('open_service_time desc')->paginate($request->get('limit'));
        }
        return json($OpenService);
    }

    /**
     * 开服表修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Put($id,Request $request)
    {

        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->OpenServiceChangeStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->changeSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->OpenServiceContent($request,$id);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
    }

    /**
     * 开服表状态修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function OpenServiceChangeStatus($request,$id)
    {   
        $OpenService = MOpenService::get($id);
        $OpenService->status = $request->post('status');
        $res = $OpenService->save();
        if ($res) {
          return json(['message'=>'使用/禁用，成功！']);
        }
          return json(['message'=>'使用/禁用, 失败 请联系IT部的小学生！'],203);
    }

    /**
     * 开服表内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function OpenServiceContent($request,$id)
    {   
        $OpenService = MOpenService::get($id);
        $OpenService->open_service_name = $request->post('open_service_name');
        $OpenService->open_service_time = $request->post('open_service_time');
        $OpenService->type = $request->post('type');
        $res = $OpenService->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }

    /**
     * 开服表添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Post(Request $request)
    {
        $data = [
            'open_service_time'=>$request->post('open_service_time'),
            'open_service_name'=>$request->post('open_service_name'),
            'type'=>$request->post('type'),
            'game_id'=>$request->post('game_id'),
        ];
        $OpenService = MOpenService::create($data);    

        if ($OpenService) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

    /**
     * 开服表删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Delete($id)
    {
        $res = MOpenService::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }
}
 