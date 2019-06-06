<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Jurisdiction as MJurisdiction;
use think\Request;

class Jurisdiction extends ApiCommon
{
    /*    const PAGE_SIZE = 15;

    public $pageTitle = '游戏列表';*/

   /**
     * 权限列表查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function JurisdictionGet(Request $request)
    {   
        $query = new MJurisdiction();
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('user_name', 'LIKE', '%' . $keyword . '%');
        }
        $Jurisdictions = $query->order('id desc')->paginate($request->get('limit'));
        return json($Jurisdictions);
    } 

    /**
     * 权限分配修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function JurisdictionPut($id,Request $request)
    {

        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->ChangeStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->changeSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->changeContent($request,$id);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
    }

        /**
     * 游戏列表删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function JurisdictionDelete($id)
    {
        $res = MJurisdiction::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }

    /**
     * 权限分配添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return JSON
     */
    public function JurisdictionPost(Request $request)
    {
        $game_id = implode(',', $request->post('GameSelectedData/a'));
        $data = [
            'user_name'=>$request->post('user_name'),
            'game_id'=>$game_id
        ];
        $Jurisdiction = MJurisdiction::create($data);
        if ($Jurisdiction) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

    /**
     * 游戏列表内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeContent($request,$id)
    {   
        $Jurisdiction = MJurisdiction::get($id);
        $Jurisdiction->user_name = $request->post('user_name');
        $Jurisdiction->game_id = implode(',',$request->post('GameSelectedData/a'));
        $res = $Jurisdiction->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }

}
 