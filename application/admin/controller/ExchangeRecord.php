<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\ExchangeRecord AS MExchangeRecord;
use think\Request;

class ExchangeRecord extends ApiCommon
{

    /**
     * 转盘抽奖记录列表查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function Get(Request $request)
    {   
        $query = new MExchangeRecord();

        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('exchange_name', 'LIKE', '%' . $keyword . '%')
                  ->WhereOr('bn_uid', 'LIKE', '%' . $keyword . '%');

        }
        $ExchangeRecord = $query->order('id desc')->paginate($request->get('limit'));
        return json($ExchangeRecord);
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
            case 'changeGrant': //发货
                return $this->RecordChangeGrant($request,$id);
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
     * 转盘商品状态修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function RecordChangeGrant($request,$id)
    {   
        $ExchangeRecord = MExchangeRecord::get($id);
        $ExchangeRecord->is_grant = $request->post('is_grant');
        $res = $ExchangeRecord->save();
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
        $res = MExchangeRecord::get($id)->delete();
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
}
 