<?php
// +----------------------------------------------------------------------
// | Description: Api基础类，验证权限
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------
namespace app\api\controller;
use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;
use \Firebase\JWT\JWT;
class ApiCommon extends Common
{
    const JWTKEY = "jfdksajfkl;dsajfkdjsaklfdajffdsafdsfdsfdsfdsklfdsafdsafdsafdsdsajlkfdsa";  //基础菜单
    
    public function _initialize()
    {
        parent::_initialize();
    }

    public function Encryption($data)
    {
        $key = self::JWTKEY;
        $jwt = JWT::encode($data, $key);
        return $jwt;
    } 

    public function Decrypt($jwt)
    {
        $key = self::JWTKEY;
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        return $decoded;
    } 
}