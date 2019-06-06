<?php
namespace app\api\model;
use \Firebase\JWT\JWT;


class TurntableUser extends \think\Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'oa_turntable_user';
	protected $autoWriteTimestamp = 'timestamp';

    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';


    const JWTKEY = "jfdksajfkl;dsajfkdjsaklfdajffdsafdsfdsfdsfdsklfdsafdsafdsafdsdsajlkfdsa";  //基础菜单

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