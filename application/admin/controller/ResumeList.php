<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;;
use app\admin\model\ResumeList AS MResumeList;
use think\Request;
class ResumeList extends ApiCommon
{

    /**
     * 简历查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Get(Request $request)
    {   
        $query = new MResumeList();
        $query->join('oa_admin_artisan_type', 'oa_admin_artisan_type.id = oa_resume_list.artisan_type_id');
        $artisan_type_id = $request->get('artisan_type_id');
        if (is_numeric($artisan_type_id)) {
            $query->where('artisan_type_id','=',$artisan_type_id);
        }
        $startTime = $request->get('startTime');
        $endTime = $request->get('endTime');
        if ($startTime&&$endTime) {
            $query->where('created_at','between time',[$startTime,$endTime]);
        }
        $ResumeList = $query->order('id desc')->field(['oa_resume_list.*', 'oa_admin_artisan_type.name'])->paginate($request->get('limit'));
        return json($ResumeList);
    }

    /**
     * 简历下载
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Download($id)
    {   
        $Resume = MResumeList::find($id);
        $new_name='';
        $file_url=$Resume->resume_url;
        if(!isset($file_url)||trim($file_url)==''){  
            echo '500';  
        }  
        if(!file_exists($file_url)){ //检查文件是否存在  
            echo '404';  
        } 
        $file_name=basename($file_url);  
        $file_type=explode('.',$file_url);  
        $file_type=$file_type[count($file_type)-1];  
        $file_name=trim($new_name=='')?$file_name:urlencode($new_name);  
        $file_type=fopen($file_url,'r'); //打开文件  
        //输入文件标签 
        header("Content-type: application/octet-stream");  
        header("Accept-Ranges: bytes");  
        header("Accept-Length: ".filesize($file_url));  
        header("Content-Disposition: attachment; filename=".$file_name);  
        //输出文件内容  
        echo fread($file_type,filesize($file_url));  
        fclose($file_type);
    }

}
 