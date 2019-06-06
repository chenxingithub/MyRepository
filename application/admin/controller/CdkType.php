<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\CdkType AS MCdkType;
use app\admin\model\CdkList AS MCdkList;
use think\Request;

class CdkType extends ApiCommon
{

    /**
     * cdk分类查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function CdkTypeGet(Request $request)
    {   
        $query = new MCdkType();
        $query->where('game_id','=',$request->get('game_id'));
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('cdk_type_title', 'LIKE', '%' . $keyword . '%')
            ->WhereOr('cdk_type_content', 'LIKE', '%' . $keyword . '%');
        }
        $status = $request->get('status');
        if (is_numeric($status)) {
            $query->where('status','=',$status);
        }
        $QueryType = $request->get('query_type');
        if ($QueryType) {
           $type = $request->get('type');
          if ($type) {
               $CdkType = $query->where('type',$type);
           }
           $CdkType = $query->order('oa_admin_cdk_type_copy.id desc')->select();
        }else{
             //连表
            $query->join('oa_admin_cdk_list', 'oa_admin_cdk_type_copy.id = oa_admin_cdk_list.cdk_type_id','LEFT');
            //分组
            $query->group('oa_admin_cdk_type_copy.id');
        $CdkType = $query->order('oa_admin_cdk_type_copy.id desc')->field('COUNT(case when oa_admin_cdk_list.status=0 then oa_admin_cdk_list.id end) AS surplus_num, COUNT(case when oa_admin_cdk_list.status=1 then oa_admin_cdk_list.id end) AS use_num,COUNT(oa_admin_cdk_list.id) AS total_num,oa_admin_cdk_type_copy.*')->paginate($request->get('limit'));
        }
        return json($CdkType);
    }

    /**
     * cdk分类修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function CdkTypePut($id,Request $request)
    {

        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->CdkTypeChangeStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->changeSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->CdkTypeChangeContent($request,$id);
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
     * 文章分类内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function CdkTypeChangeContent($request,$id)
    {   
        $CdkType = MCdkType::get($id);
        $CdkType->cdk_type_title = $request->post('cdk_type_title');
        $CdkType->cdk_type_content = $request->post('cdk_type_content');
        $CdkType->expired_at = $request->post('expired_at');
        $CdkType->start_at = $request->post('start_at');
        $CdkType->head_img = $request->post('head_img');
        $CdkType->bg_color = $request->post('bg_color');
        $CdkType->instructions = $request->post('instructions');
        $CdkType->usage_method = $request->post('usage_method');
        $CdkType->type = $request->post('type');
        $CdkType->probability = $request->post('probability')?$request->post('probability'):100;
        $res = $CdkType->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }

    /**
     * cdk分类添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function CdkTypePost(Request $request)
    {
        $data = [
            'cdk_type_title'=>$request->post('cdk_type_title'),
            'cdk_type_content'=>$request->post('cdk_type_content'),
            'expired_at'=>$request->post('expired_at'),
            'start_at'=>$request->post('start_at'),
            'game_id'=>$request->post('game_id'),
            'head_img'=>$request->post('head_img'),
            'bg_color'=>$request->post('bg_color'),
            'instructions'=>$request->post('instructions'),
            'usage_method'=>$request->post('usage_method'),
            'probability'=>$request->post('probability')?$request->post('probability'):100,
            'type'=>$request->post('type')
        ];
        $CdkType = MCdkType::create($data);    






              

        if ($CdkType) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

    /**
     * cdk分类删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function CdkTypeDelete($id)
    {
        $res = MCdkType::get($id)->delete();
        $res2 = MCdkList::where('cdk_type_id',$id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }
}
 