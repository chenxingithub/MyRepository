<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\SignInCdk AS MSignInCdk;
use app\admin\model\CdkType;
use think\Request;

class SignInCdk extends ApiCommon
{
    /*    const PAGE_SIZE = 15;/

    /**
     * cdk列表查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Get(Request $request)
    {   
        $query = new MSignInCdk();

        $query->join('oa_day_sign_gift_copy', 'oa_sign_in_cdk.cdk_day_id = oa_day_sign_gift_copy.day_num ');
        $query->where('oa_day_sign_gift_copy.game_id','=',$request->get('game_id'));
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('code', 'LIKE', '%' . $keyword . '%');
        }
        $status = $request->get('status');
        if (is_numeric($status)) {
            $query->where('oa_sign_in_cdk.status','=',$status);
        }
        $CdkTypeId = $request->get('cdk_day_id');
        if (is_numeric($CdkTypeId)) {
            $query->where('oa_day_sign_gift_copy.day_num','=',$CdkTypeId);
        }
        $CdkList = $query->order('id desc')->field(['oa_sign_in_cdk.*', 'oa_day_sign_gift_copy.title'])->paginate($request->get('limit'));
        return json($CdkList);
    }

    /**
     * 文章列表修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ArtisanPut($id,Request $request)
    {

        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->ArtisanChangeStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->ArtisanChangeSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->ArtisanChangeContent($request,$id);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
    }


    /**
     * 文章列表状态修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function ArtisanChangeStatus($request,$id)
    {   
        $Artisan = MArtisan::get($id);
        $Artisan->status = $request->post('status');
        $res = $Artisan->save();
        if ($res) {
          return json(['message'=>'使用/禁用，成功！']);
        }
        return json(['message'=>'使用/禁用, 失败！'],203);
    }


    /**
     * cdk列表添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Post(Request $request)
    {
       ini_set('max_execution_time', '0');//设置永不超时，无限执行下去直到结束
       $data = [];
       $model = new MSignInCdk();  
       $t=time();
       $cdk_list = $request->post('cdk_list/a');
       foreach ($cdk_list as $key => $value) {
            $data[] = [
                        'cdk_day_id'=>$request->post('cdk_day_id'),
                        'game_id'=>$request->post('game_id'),
                        'code'=>$value,
                        'created_at'=>date("Y-m-d H:i:s",$t),
                        'updated_at'=>date("Y-m-d H:i:s",$t)
                      ];
        }
       $CkdList = $model->saveAll($data);

        if ($CkdList) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

    /**
     * 文章列表置顶修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function ArtisanChangeSort($request,$id)
    {   
        $Artisan = MArtisan::get($id);
        $Artisan->sort = $request->post('sort');
        $res = $Artisan->save();
        if ($res) {
          return json(['message'=>'置顶/取消置顶，成功！']);
        }
        return json(['message'=>'置顶/取消置顶, 失败！'],203);
    }

    /**
     * 文章列表删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Delete($id)
    {
        $res = MSignInCdk::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }

    /**
     * cdk列表批量删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function BatchDelete(Request $request)
    {
        $selectArr = $request->post('selectArr/a');
        if (!$selectArr) {
             return json(['message'=>'亲，请选择要删除的数据！'],203);
        }
        $res = MSignInCdk::where('id','in',$selectArr)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }
}
 