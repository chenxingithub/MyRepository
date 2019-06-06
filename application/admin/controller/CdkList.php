<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\CdkList AS MCdkList;
use app\admin\model\CdkType;
use think\Request;
use PHPExcel_IOFactory;
use PHPExcel;

class CdkList extends ApiCommon
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
    public function CdkListGet(Request $request)
    {   
        $query = new MCdkList();
        $query->join('oa_admin_cdk_type_copy', 'oa_admin_cdk_list.cdk_type_id = oa_admin_cdk_type_copy.id ');
        $query->where('oa_admin_cdk_type_copy.game_id','=',$request->get('game_id'));
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('code', 'LIKE', '%' . $keyword . '%');
        }
        $status = $request->get('status');
        if (is_numeric($status)) {
            $query->where('oa_admin_cdk_list.status','=',$status);
        }
        $receiveTime = $request->get('receiveTime/a');
        if ($receiveTime[0]) {
            $query->where('oa_admin_cdk_list.updated_at', 'between time', $receiveTime);
        }

        $CdkTypeId = $request->get('cdk_type_id');
        if (is_numeric($CdkTypeId)) {
            $query->where('oa_admin_cdk_type_copy.id','=',$CdkTypeId);
        }
        $CdkList = $query->order('id desc')->field(['oa_admin_cdk_list.*', 'oa_admin_cdk_type_copy.cdk_type_title'])->paginate($request->get('limit'));
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
    public function CdkListPost(Request $request)
    {
       ini_set('max_execution_time', '0');//设置永不超时，无限执行下去直到结束
       $data = [];
       $model = new MCdkList();  
       $t=time();
       $cdk_list = $request->post('cdk_list/a');
       foreach ($cdk_list as $key => $value) {
            $data[] = [
                        'cdk_type_id'=>$request->post('cdk_type_id'),
                        'code'=>$value,
                        'created_at'=>date("Y-m-d H:i:s",$t),
                        'updated_at'=>date("Y-m-d H:i:s",$t)
                      ];
        }

        if ($request->post('cdk_type')==2) {
            $res = $model->where('cdk_type_id',$request->post('cdk_type_id'))->delete();
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
    public function CdkListDelete($id)
    {
        $res = MCdkList::get($id)->delete();
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
    public function CdkListBatchDelete(Request $request)
    {
        $selectArr = $request->post('selectArr/a');
        if (!$selectArr) {
             return json(['message'=>'亲，请选择要删除的数据！'],203);
        }
        $res = MCdkList::where('id','in',$selectArr)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }
    /**
     * cdk列表Excel下载
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ExcelGet(Request $request)
    {   
        set_time_limit(0);

        $query = new MCdkList();
        $query->join('oa_admin_cdk_type_copy', 'oa_admin_cdk_list.cdk_type_id = oa_admin_cdk_type_copy.id ');
        $query->where('oa_admin_cdk_type_copy.game_id','=',$request->get('game_id'));
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('code', 'LIKE', '%' . $keyword . '%');
        }
        $status = $request->get('status');
        if (is_numeric($status)) {
            $query->where('oa_admin_cdk_list.status','=',$status);
        }
        $receiveTime = $request->get('receiveTime');
        if ($receiveTime) {
            $receiveTime = explode(',', $receiveTime);
            $query->where('oa_admin_cdk_list.updated_at', 'between time', $receiveTime);
        }

        $CdkTypeId = $request->get('cdk_type_id');
        if (is_numeric($CdkTypeId)) {
            $query->where('oa_admin_cdk_type_copy.id','=',$CdkTypeId);
        }
        $data = $query->field(['oa_admin_cdk_list.*', 'oa_admin_cdk_type_copy.cdk_type_title'])->select();
        $objExcel = new PHPExcel();
        //set document Property
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');

        $objActSheet = $objExcel->getActiveSheet();

        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G,H");
        $arrHeader =  array('ID','礼包类型','cdk码','领取状态','创建时间','更新时间');;
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        };
        //填充表格信息
        foreach($data as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['id']);
            $objActSheet->setCellValue('B'.$k,$v['cdk_type_title']);
            // // 图片生成
            // $objDrawing[$k] = new \PHPExcel_Worksheet_Drawing();
            // $objDrawing[$k]->setPath('public/static/admin/images/profile_small.jpg');
            // // 设置宽度高度
            // $objDrawing[$k]->setHeight(40);//照片高度
            // $objDrawing[$k]->setWidth(40); //照片宽度
            // /*设置图片要插入的单元格*/
            // $objDrawing[$k]->setCoordinates('C'.$k);
            // // 图片偏移距离
            // $objDrawing[$k]->setOffsetX(30);
            // $objDrawing[$k]->setOffsetY(12);
            // $objDrawing[$k]->setWorksheet($objPHPExcel->getActiveSheet());
            // 表格内容
            $objActSheet->setCellValue('C'.$k, $v['code']);
            $objActSheet->setCellValue('D'.$k, $v['status']?'已领取':'未领取');
            $objActSheet->setCellValue('E'.$k, $v['created_at']);
            $objActSheet->setCellValue('F'.$k, $v['updated_at']);
            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }
        $width = array(20,20,15,10,10,30,10,15,30);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth($width[0]);
        $objActSheet->getColumnDimension('B')->setWidth($width[3]);
        $objActSheet->getColumnDimension('C')->setWidth($width[5]);
        $objActSheet->getColumnDimension('D')->setWidth($width[3]);
        $objActSheet->getColumnDimension('E')->setWidth($width[3]);
        $objActSheet->getColumnDimension('F')->setWidth($width[5]);
        $objActSheet->getColumnDimension('G')->setWidth($width[8]);


        $outfile = "ckd领取记录表".date("Y-m-d").".xls";
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outfile.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
}
 