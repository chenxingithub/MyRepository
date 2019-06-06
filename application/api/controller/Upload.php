<?php
// +----------------------------------------------------------------------
// | Description: 图片上传
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------
namespace app\api\controller;

use think\Request;
use think\Controller;
use app\common\lib\QiniuUpload;
use PHPExcel_IOFactory;
use PHPExcel;
use OSS\OssClient;
use OSS\Core\OssException;

class Upload extends Controller
{   
    const accessKeyId = 'LTAI6Jtx1B0gZAZO';
    const accessKeySecret = 'yrKORwkMJa2KlChgRrg1rq0TsA3s56';
    const endpoint = 'oss-cn-shenzhen-internal.aliyuncs.com';
    const bucket = 'bingniao-cdn3';
    const cdn_dir = 'cms/';



    public function index()
    {	

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $file = request()->file('file');
        if (!$file) {
        	return resultArray(['error' => '请上传文件']);
        }
        
        $info = $file->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH . DS . 'uploads');
        if ($info) {
            return resultArray(['data' =>  'uploads'. DS .$info->getSaveName()]);
        }
        
        return resultArray(['error' =>  $file->getError()]);
    }

    //oos 图片上传
    public function UploadImage(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

        ini_set('memory_limit','256M'); //升级为申请256M内存

        $file = request()->file('file');
        $info = $file->validate(['ext'=>'jpg,png,gif,jpeg'])->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'static/upload/image/');
        $error = $file->getError();
        // return ['code'=>2,'error'=>$error];
        //验证文件后缀后大小
        if($error||!$info){
          return json(['message'=>'上传失败'],203);
        }
        $newName = date("Ym").rand().$_SERVER['REQUEST_TIME'].'.'.$info->getExtension();//当前系统时间，比time()多5秒
        try {
            $ossClient = new OssClient(self::accessKeyId, self::accessKeySecret, self::endpoint);
        } catch (OssException $e) {
          return json(['message'=>'上传失败'],203);
        }

        try{
        $upload_file = $ossClient->uploadFile(self::bucket, self::cdn_dir . $newName, 'static/upload/image/'.$info->getSaveName());
        } catch(OssException $e) {
            unlink('static/upload/image/'.$info->getSaveName());
            return json(['message'=>'上传失败'],203);
        }
        if ($upload_file['info']['http_code'] == 200) {
            unlink('static/upload/image/'.$info->getSaveName());
            return json(['url'=>$newName,'message' => 'OK'],200);
        }
            return json(['message'=>'上传失败'],203);
    }
    

    /**
     * Xlsx上传
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function UploadXlsx(Request $request)
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
            $cdk_list =[];
            foreach ($data as $key => $value) {
                    foreach (array_filter($value) as $key => $value) {
                        $cdk_list[]=$value;
                    }
             }
             return json(['cdk_list'=>$cdk_list,'message' => 'OK'],200); 
             exit();

             //下面是没用的！！！！
            $highestRow = $sheet->getHighestRow(); // 取得总行数  
            $highestColumm = $sheet->getHighestColumn(); // 取得总列数  
            /** 循环读取每个单元格的数据 */  
            $User = new User;  
            for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始，这里示例中excel有3列字段  
              $userName = $sheet->getCell('A'.$row)->getValue();;  
              $website = $sheet->getCell('B'.$row)->getValue();;  
              $phone = $sheet->getCell('C'.$row)->getValue();;  
              $where = array();  
              $where['website'] = $website ? $website : 'http://fity.cn';  
              $where['phone'] = $phone;  
              $userInfo = $User->where($where)->find();  
              if($userInfo){  
                $userInfo = $userInfo->toArray();  
              }  
              $data = array();  
              if (!$userInfo) {  
                $data = array(  
                  'userName'  =>  $userName,  
                  'website'  =>  $website,  
                  'phone'    =>  $phone  
                );  
                $User->data($data,true)->isUpdate(false)->save();  
              }  
            }  
        }else{  
          return json(['message'=>'上传失败'],203);  
        }  
    }
}
 