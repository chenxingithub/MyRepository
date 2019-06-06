<?php
// +----------------------------------------------------------------------
// | Description: Api基础类，验证权限
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;
use think\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;
use app\common\controller\AucInterceptor;//冰鸟登录系统类
use think\Session;
class ApiCommon extends Common
{
    const sys_id = 16;
    const sys_key = '14c210e108759b5a77b89df4caf36351';
    static $instance = null;
    public function _initialize()
    {
        parent::_initialize();
        if (!IS_CLI && APP_STATUS != 'dev') {
            if (self::$instance == null) {
                $conf = [
                    'sys_id' => self::sys_id,
                    'sys_key' => self::sys_key,
                ];
                $auc = AucInterceptor::init($conf, function ($currPath, $currentQuery) {
                    return [
                        $currPath, $currentQuery
                    ];
                }, 'prod', false);
                self::$instance = $auc;
            }
            $ct = strtolower(Request::instance()->controller());
            $ac = strtolower(Request::instance()->action());
            $excepts = ['index/login'];
            if (!in_array("/{$ct}/{$ac}", $excepts)) {
              $auc->start();
            }
            //获取用户信息
            $GLOBALS['userInfo'] = json_encode($auc->getCurrentUserInfo());
            $GLOBALS['UserMenus'] = $auc->getCurrentMenus();
            unset($GLOBALS['UserMenus']['__common_menu']);
            return true;
        }else{
            /*获取头部信息*/ 
    /*        $header = Request::instance()->header();
            
            $authKey = $header['authkey'];
            $sessionId = $header['sessionid'];
            $cache = cache('Auth_'.$authKey);*/
            
            // 校验sessionid和authKey
    /*        if (empty($sessionId)||empty($authKey)||empty($cache)) {
                header('Content-Type:application/json; charset=utf-8');
                $this->redirect('/login');
            }*/
            $userInfo = Session::get('UserInfo');
            if (empty($userInfo)) {
                header('Content-Type:application/json; charset=utf-8');
                $this->redirect('/login');
            }
            $authKey = user_md5($userInfo['username'].$userInfo['password'].$info['sessionId']);
            $cache = cache('Auth_'.$authKey);
            // 检查账号有效性
            $map['id'] = $userInfo['id'];
            $map['status'] = 1;
            if (!Db::name('admin_user')->where($map)->value('id')) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(['code'=>103, 'error'=>'账号已被删除或禁用']));   
            }
            //获取权限
            $authAdapter = new AuthAdapter($authKey);
            $request = Request::instance();
            $ruleName = $request->module().'-'.$request->controller() .'-'.$request->action();
            if (!$authAdapter->checkLogin($ruleName, $userInfo['id'])) {
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode(['code'=>102,'error'=>'没有权限']));
            }
            $GLOBALS['userInfo'] = $userInfo;
        }
    }
    public function SensitiveFilter($strArr) {
/*        $badword = file(IBN_ROOT_PATH.'/public/filterChat.txt');
        var_dump($strArr);
        var_dump($badword);
        exit();
        foreach ($strArr as $value) {
            foreach ($badword as $badwordValue) {
                if (strpos($value,trim($badwordValue))!==false) {
                    return  false;
                }
            }
        }*/
        return true;
    }
    private function isHaveAuth($p) {
        $pid = Db::table('permissions')->where('name', $p)->find()['id'];
        if (!$pid) {
            return true;
        }
        $userid = User::getUserId();
        $tmp = Db::table('role_user')->where('user_id', $userid)->select();
        $roles = [];
        foreach ($tmp as $v) {
            $roles[] = $v['role_id'];
        }
        $inrole = "(" . implode(',', $roles) . ")";
        $tmp = Db::query("select * from permission_role where role_id in {$inrole}");
        $ps = [];
        foreach ($tmp as $item) {
            $ps[] = $item['permission_id'];
        }
        return in_array($pid, $ps);
    }
}