<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\TurntableCommodity AS MTurntableCommodity;
use think\Request;

class TurntableCommodity extends ApiCommon
{

    /**
     * 转盘商品列表查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function Get(Request $request)
    {   
        $query = new MTurntableCommodity();

        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        }
        $is_delete = $request->get('is_delete');
        if (is_numeric($is_delete)) {
            $query->where('is_delete','=',$is_delete);
        }
        $Artisan = $query->order('sort asc')->paginate($request->get('limit'));
        return json($Artisan);
    }

    /**
     * 转盘商品修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function Put($id,Request $request)
    {

        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->ArtisanChangeStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->ArtisanChangeSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->TurntableCommodityContent($request,$id);
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
     * 转盘商品列表删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Delete($id)
    {
        $res = MTurntableCommodity::get($id)->delete();
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
            'name'=>$request->post('name'),
            'img'=>$request->post('img'),
            'probability'=>$request->post('img'),
            'probability'=>$request->post('probability'),
            'total_number'=>$request->post('total_number'),
            'surplus_number'=>$request->post('surplus_number')
        ];
        $TurntableCommodity = MTurntableCommodity::create($data);

        if ($TurntableCommodity) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }
}
 