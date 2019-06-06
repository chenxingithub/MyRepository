<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\TurntableUser AS MTurntableUser;
use think\Request;

class TurntableUser extends ApiCommon
{

    /**
     * 转盘抽奖用户列表查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function Get(Request $request)
    {   
        $query = new MTurntableUser();

        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('real_name', 'LIKE', '%' . $keyword . '%')
                  ->WhereOr('work_number', 'LIKE', '%' . $keyword . '%') ;

        }
        $TurntableUser = $query->order('id desc')->paginate($request->get('limit'));
        return json($TurntableUser);
    }

    /**
     * 转盘用户修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function Put($id,Request $request)
    {
        switch ($request->post('action')) {
            case 'changeType': //修改用户类型
                return $this->UserChangeType($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->ArtisanChangeSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->TurntableUserContent($request,$id);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
    }


    /**
     * 转盘用户状态修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function UserChangeType($request,$id)
    {   
        $TurntableUser = MTurntableUser::get($id);
        $TurntableUser->type = $request->post('type');
        $res = $TurntableUser->save();
        if ($res) {
          return json(['message'=>'修改，成功！']);
        }
        return json(['message'=>'修改, 失败！'],203);
    }


    /**
     * 转盘用户内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function TurntableUserContent($request,$id)
    {   
        $TurntableUser = MTurntableUser::get($id);
        $TurntableUser->real_name = $request->post('real_name');
        $TurntableUser->work_number = $request->post('work_number');
        $TurntableUser->gender = $request->post('gender');
        $res = $TurntableUser->save();
        if ($res) {
          return json(['message'=>'修改，成功！']);
        }
        return json(['message'=>'修改, 失败！'],203);
    }

    /**
     * 转盘商品列表删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Delete($id)
    {
        $res = MTurntableUser::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }

    /**
     * 转盘商品修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function TurntableCommodityContent($request,$id)
    {   
        $TurntableCommodity = MTurntableCommodity::get($id);
        $TurntableCommodity->name = $request->post('name');
        $TurntableCommodity->img = $request->post('img');
        $TurntableCommodity->probability = $request->post('probability');
        $TurntableCommodity->total_number = $request->post('total_number');
        $TurntableCommodity->surplus_number = $request->post('surplus_number');
        $res = $TurntableCommodity->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }

    /**
     * 转盘商品列表添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Post(Request $request)
    {
        $data = [
            'real_name'=>$request->post('real_name'),
            'work_number'=>$request->post('work_number'),
            'gender'=>$request->post('gender'),
            'type'=>2
        ];
        $TurntableUser = MTurntableUser::create($data);

        if ($TurntableUser) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }
}
 