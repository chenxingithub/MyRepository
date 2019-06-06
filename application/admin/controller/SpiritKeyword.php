<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\SpirtKeyword as MSpirtKeyword;
use think\Db;
use think\Request;
use PHPExcel_IOFactory;
use PHPExcel;

class SpiritKeyword extends ApiCommon
{

    /**
     * 关键字查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return JSON
     */
    public function Get(Request $request)
    {   
        $query = new MSpirtKeyword();
        $query->join('oa_spirit_type', 'oa_spirit_type.id = oa_spirt_keyword.type_id','LEFT');
        $query->where('oa_spirt_keyword.game_id','=',$request->get('game_id'));
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('rule', 'LIKE', '%' . $keyword . '%');
        }
        $QueryType = $request->get('query_type');
        if ($QueryType) {
            $Keywords = $query->order('id desc')->field(['any_value(oa_spirt_keyword.id) as id', 'any_value(oa_spirit_type.name) as name', 'any_value(oa_spirt_keyword.keyword) as keyword', 'any_value(oa_spirt_keyword.type_id) as type_id', 'any_value(oa_spirt_keyword.rule) as rule', 'any_value(oa_spirt_keyword.is_vague) as is_vague', 'any_value(oa_spirt_keyword.content) as content', 'any_value(oa_spirt_keyword.status) as status', 'any_value(oa_spirt_keyword.created_at) as created_at', 'any_value(oa_spirt_keyword.updated_at) as updated_at','group_concat(keyword) as keyword_arr','group_concat(is_vague) as vague_arr','group_concat(oa_spirt_keyword.id) as keyword_id_arr'])->group('rule')->select();
        }else{
            $Keywords = $query->order('id desc')->field(['any_value(oa_spirt_keyword.id) as id', 'any_value(oa_spirit_type.name) as name', 'any_value(oa_spirt_keyword.keyword) as keyword', 'any_value(oa_spirt_keyword.type_id) as type_id', 'any_value(oa_spirt_keyword.rule) as rule', 'any_value(oa_spirt_keyword.is_vague) as is_vague', 'any_value(oa_spirt_keyword.content) as content', 'any_value(oa_spirt_keyword.status) as status', 'any_value(oa_spirt_keyword.created_at) as created_at', 'any_value(oa_spirt_keyword.updated_at) as updated_at','group_concat(keyword) as keyword_arr','group_concat(is_vague) as vague_arr','group_concat(oa_spirt_keyword.id) as keyword_id_arr'])->group('rule')->paginate($request->get('limit'));
        }
        return json($Keywords);
    }

    /**
     * 关键字回复修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Put(Request $request)
    {
        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->ChangeStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->changeSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->changeContent($request);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
    }
    /**
     * 关键字回复状态修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function ChangeStatus($request)
    {   
        $SpirtKeyword = new MSpirtKeyword;
        $keyword_id_arr = explode(",",$request->post('keyword_id_arr'));
        foreach ($keyword_id_arr as $key => $value) {
            $list[] = [
                'id'=>$value,
                'status'=>$request->post('status')
            ];
        }
        $res = $SpirtKeyword->saveAll($list);
        if ($res) {
          return json(['message'=>'使用/禁用，成功！']);
        }
        return json(['message'=>'使用/禁用, 失败！'],203);
    }
    /**
     * 关键字回复修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeContent($request)
    {   
        $keyword_arr = $request->post('keyword_arr/a');
        $vague_arr = $request->post('vague_arr/a');
        $keyword_id_arr = $request->post('keyword_id_arr');
        // 先删之前的数据 再添加数据
        // 启动事务
        Db::startTrans();
        try{
            // 删除
            $res = MSpirtKeyword::destroy($keyword_id_arr);
            $model = new MSpirtKeyword();
            $data = [];
            $t=time();
            foreach ($keyword_arr as $key => $value) {
                $data[] = [
                    'rule'=>$request->post('rule'),
                    'keyword'=>$value,
                    'is_vague'=>$vague_arr[$key],
                    'content'=>$request->post('content'),
                    'game_id'=>$request->post('game_id'),
                    'type_id'=>$request->post('spirit_type_id'),
                    'created_at'=>date("Y-m-d H:i:s",$t),
                    'updated_at'=>date("Y-m-d H:i:s",$t)
                ];
            }
            $res2 = $model->saveAll($data);
            //判断是否操作成功
            if ($res&&$res2) {
                // 提交事务
                Db::commit();
                return json(['message'=>'更新成功！']);  
            }else{
                Db::rollback();
                return json(['message'=>'更新失败，请联系IT部的小学生！'],203);
            }
        } catch (\Exception $e) {
            dump($e);
            // 回滚事务
            Db::rollback();
        }
    }
    /**
     * 关键字回复删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Delete(Request $request)
    {   
        $res = MSpirtKeyword::destroy($request->get('keyword_id_arr'));
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }

    /**
     * 关键字回复添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Post(Request $request)
    {
        $keyword_arr = $request->post('keyword_arr/a');
        $vague_arr = $request->post('vague_arr/a');
        $model = new MSpirtKeyword();
        $data = [];
        $t=time();
        foreach ($keyword_arr as $key => $value) {
            $data[] = [
                'rule'=>$request->post('rule'),
                'keyword'=>$value,
                'is_vague'=>$vague_arr[$key],
                'content'=>$request->post('content'),
                'game_id'=>$request->post('game_id'),
                'type_id'=>$request->post('spirit_type_id'),
                'created_at'=>date("Y-m-d H:i:s",$t),
                'updated_at'=>date("Y-m-d H:i:s",$t)
            ];
        }
        $SpirtKeyword = $model->saveAll($data);

        if ($SpirtKeyword) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

    /**
     * 关键字回复批量添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Import(Request $request)
    {
            $file = request()->file('file');        // 获取表单提交过来的文件  
            $ext = explode('.', $file->getInfo()['name']);
    /*        $data = [];
            $cdk_list =[];*/
    /*        $PHPExcel = $ext[1] == 'xls' ? PHPExcel_IOFactory::createReader("Excel5") : PHPExcel_IOFactory::createReader("Excel2007");*/
            $error = $_FILES['file']['error'];  // 如果$_FILES['file']['error']>0,表示文件上传失败 
            if(!$error){  
              $dir = ROOT_PATH . 'public' . DS . 'upload';  
              // 验证文件并移动到框架应用根目录/public/uploads/ 目录下  
              $info = $file->validate(['ext'=>'xls,xlsx'])->rule('uniqid')->move($dir);  
              /*判断是否符合验证*/  
                //$file_type = $info->getExtension();  
                $filename = $dir. DS .$info->getSaveName();  
                //echo $filename;   
                if ($ext[1]=='xls') {
                $reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式
                }else{
                $reader = PHPExcel_IOFactory::createReader('Excel2007'); //设置以Excel2007格式  
                }
                $PHPExcel = $reader->load($filename); // 载入excel文件  
                $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表  
                $data  = $sheet->toArray();
                array_shift($data);  //删除第一个数组(标题);
                $city =[];
                $t=time();
                foreach($data as $k=>$v) {
                        $city[$k]['rule'] = $v[0];
                        $city[$k]['keyword'] = $v[1];  
                        $city[$k]['type_id'] = $v[2];
                        $city[$k]['is_vague'] = $v[3];
                        $city[$k]['content'] = $v[4];
                        $city[$k]['game_id'] = $v[5];
                        $city[$k]['status'] = 0;
                }
                $model = new MSpirtKeyword();
                $SpirtKeyword = $model->saveAll($city);
                if ($SpirtKeyword) {
                  return json(['message'=>'添加成功！']);
                }
                return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
            }else{  
              return json(['message'=>'上传失败'],203);  
            }  
    }

}
 