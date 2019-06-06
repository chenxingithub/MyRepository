<?php
namespace app\api\model;
use \Firebase\JWT\JWT;

class BnUser extends \think\Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'oa_bn_user';
	protected $autoWriteTimestamp = 'timestamp';

    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    const JWTKEY = "dfsdyuh;iuwkdhkdjsaklfdajffdsafdsfdsfdsfdsklfdsafdsafdsafdsdsajlkfdsa";
    const BnLoginKeyMD5 = 'cd110d3743bdadd28157e63f221e524e';

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

    public function BnUserSign($params)
    {
        $signStr = '';
        krsort($params);
        $key = self::BnLoginKeyMD5;
        foreach ($params as $k => $v) {
            $signStr .= "{$k}^{$v}";
        }
        $shouldBeSign = md5($signStr . $key);
        return $shouldBeSign;
    } 

    public function BnTowerSign($params)
    {
        $signStr = '';
        krsort($params);
        $key = self::BnLoginKeyMD5;
        foreach ($params as $k => $v) {
            $signStr .= "{$k}^{$v}";
        }
        $shouldBeSign = md5($signStr . $key);
        return $shouldBeSign;
    } 

}