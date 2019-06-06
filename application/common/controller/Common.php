<?php
// +----------------------------------------------------------------------
// | Description: 解决跨域问题
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\common\controller;

use think\Controller;
use think\Request;

class Common extends Controller
{
    public $param;
    public function _initialize()
    {
        parent::_initialize();
/*      
        $origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';  
        $allow_origin = array(  
        'http://client1.runoob.com',  
        'http://client2.runoob.com'  
        );  

        if(in_array($origin, $allow_origin)){  
        header('Access-Control-Allow-Origin:'.$origin);       
        } */
        /*防止跨域*/      
        /*header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);*/
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId, Access-Token, X-Token");
        $param =  Request::instance()->param();    
        $this->param = $param;
    }

    public function object_array($array) 
    {  
        if (is_object($array)) {  
            $array = (array)$array;  
        } 
        if (is_array($array)) {  
            foreach ($array as $key=>$value) {  
                $array[$key] = $this->object_array($value);  
            }  
        }  
        return $array;  
    }

    public function Regroup($key = 'id',$data,$array = []) 
    {  
        foreach ($data as $value) {
            $array[$value->$key][] = $value;
        }
        return $array;  
    }
}
 