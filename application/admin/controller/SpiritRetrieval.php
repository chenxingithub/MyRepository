<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\SpiritRetrieval as MSpiritRetrieval;
use think\Request;
use PHPExcel_IOFactory;
use PHPExcel;

class SpiritRetrieval extends ApiCommon
{

    /**
     * 精灵检索查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Get(Request $request)
    {   
        $query = new MSpiritRetrieval();
        $query->where('game_id','=',$request->get('game_id'))->where('f_id',0)->where('from','<>','admin');
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('from', 'LIKE', '%' . $keyword . '%')
                  ->WhereOr('role_name', 'LIKE', '%' . $keyword . '%') ;
        }
        $SpiritRetrieval = $query->order('id desc')->paginate($request->get('limit'));
        return json($SpiritRetrieval);
    }

    /**
     * 精灵检索Excel下载
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ExcelGet(Request $request)
    {   
        set_time_limit(0);

        $query = new MSpiritRetrieval();
        $query->where('game_id','=',$request->get('game_id'))->where('f_id',0);
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('from', 'LIKE', '%' . $keyword . '%')
                  ->WhereOr('role_name', 'LIKE', '%' . $keyword . '%');
        }
        $data = $query->select();
        $objExcel = new PHPExcel();
        //set document Property
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');

        $objActSheet = $objExcel->getActiveSheet();

        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G,H");
        $arrHeader =  array('区服','玩家id','用户名','VIP','等级','时间','检索信息');;
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        };
        //填充表格信息
        foreach($data as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['service_area']?$v['service_area']:null);
            $objActSheet->setCellValue('B'.$k, $v['from']);
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
            $objActSheet->setCellValue('C'.$k, $v['role_name']?$v['role_name']:null);
            $objActSheet->setCellValue('D'.$k, $v['vip']?$v['vip']:null);
            $objActSheet->setCellValue('E'.$k, $v['role_rank']?$v['role_rank']:null);
            $objActSheet->setCellValue('F'.$k, $v['created_at']);
            $objActSheet->setCellValue('G'.$k, $v['retrieval_message']?$v['retrieval_message']:null);



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


        $outfile = "玩家检索列表".date("Y-m-d").".xls";
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

    /**
     * 精灵检索对话查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function DialogueGet(Request $request)
    {   
       $dialogue =  MSpiritRetrieval::where(function ($query) use ($request) {
                $query->where('game_id', $request->get('game_id'))->where('from', $request->get('Interlocutor'));
            })->whereOr(function ($query) use ($request) {
                $query->where('game_id', $request->get('game_id'))->where('to', $request->get('Interlocutor'));;
            })->order('id desc')->limit(20)->select();
       return json($dialogue);
    }

    /**
     * 精灵检索添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Post(Request $request)
    {   
        $data = [
            'from'=>'admin',
            'to'=>$request->post('to'),
            'retrieval_message'=>$request->post('retrieval_message'),
            'game_id'=>$request->post('game_id'),
            'f_id'=>$request->post('f_id'),
            'role_name'=>'管理员'
        ];
        $SpiritRetrieval = MSpiritRetrieval::create($data);

        if ($SpiritRetrieval) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

    /**
     * 精灵检索修改
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
                return $this->ChangeStatus($request,$id);
            case 'changeSign': //另外的修改类型
                return $this->changeSign($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->changeContent($request,$id);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
    }

    /**
     * 精灵检索标记修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeSign($request,$id)
    {   
        $SpiritRetrieval = MSpiritRetrieval::get($id);
        $SpiritRetrieval->is_sign = $request->post('is_sign');
        $res = $SpiritRetrieval->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }

    /**
     * 精灵检索删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Delete($id)
    {
        $res = MSpiritRetrieval::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }

}
 