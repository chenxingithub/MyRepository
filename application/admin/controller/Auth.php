<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\controller\ApiCommon;
use app\admin\model\Jurisdiction as MJurisdiction;
use app\admin\model\Games as MGames;


class Auth extends ApiCommon
{

    public function Index()
    {   
    	if (APP_STATUS == 'dev') {
    		$user = $GLOBALS['userInfo'];
    		$menus = $GLOBALS['UserMenus'];
    		return view('Auth/index',['user' => $user,'menus' => json_encode($menus)]);
    	}else{
	    	$user = $GLOBALS['userInfo'];
	    	$userArr = json_decode($user,true);
	    	/*var_dump($user);exit();*/
	    	//获取用户菜单权限
	    	$Jurisdiction = MJurisdiction::where('user_name',$userArr['user_name'])->find();
	    	//获取菜单信息
	    	$Games = MGames::where('id','in',$Jurisdiction->game_id)->select();
	    	$menus = $GLOBALS['UserMenus'];

	        //先遍历基本功能菜单
	    	foreach ($menus as $key => $value) {
	    		if (	in_array($key,MJurisdiction::BASICSMENUS)) {
	    			if (in_array($userArr['user_id'],MJurisdiction::BASEUSERS)) {
	    				$NewMenus[] = $value;
	    			}
	    		}else{
	    			foreach ($value['subs'] as $subsValue) {
	    				//官网&公众号菜单
	    				$GameMenus[] = $subsValue;
    					//官网菜单
    					if (in_array($key,MJurisdiction::BASEMENUS)) {
    						$BaseMenus[] = $subsValue;
    					}
    					//公众号菜单
    					if (in_array($key,MJurisdiction::WECHATMENUS)) {
    						$WechatMenus[] = $subsValue;
    					}
					    //游戏精灵菜单
    					if (in_array($key,MJurisdiction::SPIRITMENUS)) {
    						$SpiritMenus[] = $subsValue;
    					}
	    			}
	    		}
	    	}
	    	//后遍历游戏菜单
	    	foreach ($Games as $key => $value) {
	    			//判断游戏是什么类型选择菜单列表
	    			if ($value->type == 1) {
	    					$NewMenus[] = [
    							'name' => $value->game_name,
			    				'visible' => true,
			    				'subs' => [
					    				[
						    				'name' => '官网',
						    				'visible' => true,
						    				'game_id' => $value->id,
						    				'subs' => $BaseMenus
					    				],
					    				[
						    				'name' => '公众号',
						    				'visible' => true,
						    				'game_id' => $value->id,
						    				'subs' => $WechatMenus
					    				],
					    				[
						    				'name' => '游戏精灵',
						    				'visible' => true,
						    				'game_id' => $value->id,
						    				'subs' => $SpiritMenus
					    				]
			    				]
	    					];
	    			}elseif ($value->type == 2) {
	    					if ($value->id == 11) {
	    						$BaseMenus[] = array('name'=>'首页','visible'=>true,'url'=>['/config/official-gift']);
	    					}
	    					//官网菜单
	    					$NewMenus[] = [
    							'name' => $value->game_name,
			    				'visible' => true,
			    				'subs' => [
					    				[
						    				'name' => '官网',
						    				'visible' => true,
			    							'game_id' => $value->id,
						    				'subs' => $BaseMenus
					    				],
					    				[
						    				'name' => '游戏精灵',
						    				'visible' => true,
						    				'game_id' => $value->id,
						    				'subs' => $SpiritMenus
					    				]
			    				]
	    					];
	    			}else{
	    				//公众号菜单
	    					$NewMenus[] = [
    							'name' => $value->game_name,
			    				'visible' => true,
			    				'subs' => [
					    				[
						    				'name' => '公众号',
						    				'visible' => true,
						    				'game_id' => $value->id,
						    				'subs' => $WechatMenus
					    				],
			    				]
	    					];
	    			}
	    	}
	        return view('Auth/index',['user' => $user,'menus' => json_encode($NewMenus)]);
    	}
    }

}
 