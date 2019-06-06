<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\WechatMenu as MWechatMenu;
use app\admin\model\WechatConfigure as MWechatConfigure;
use think\Request;
use think\Db;
use EasyWeChat\Factory; //wechar 类

class WechatMenu extends ApiCommon
{

   /**
     * 微信菜单查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function GetMenu(Request $request)
    {   
        $MWechatMenu = new MWechatMenu();
        $WechatMenus = MWechatMenu::where("game_id",$request->get('game_id'))->select();

        if ($WechatMenus) {
            $WechatMenus = collection($WechatMenus)->toArray();
            $data = $MWechatMenu->getTreeData($WechatMenus,0);
        }else{
             $data = [];
        }
        return json($data);
    } 

    /**
     * 微信菜单修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function PutMenu(Request $request)
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
     * 微信基础配置内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeContent($request)
    {  
        //获取游戏对应微信配置
        $WechatConfigure = MWechatConfigure::where('game_id',$request->post('game_id'))->where('type',4)->where('access_state',1)->find();
        if (!$WechatConfigure) {
            return json(['message'=>'十分抱歉，该游戏没有微信认证基础配置喔！'],203);
        }
        //写入配置
        $config = [
            'app_id' => $WechatConfigure->app_id,
            'secret' => $WechatConfigure->app_secret,

            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file' => $_SERVER['HTTP_HOST'].'/log/wechat.log',
            ],
        ];
        $app = Factory::officialAccount($config);
/*$current = $app->menu->current();

var_dump($current);exit();*/
        //创建微信菜单
        $buttons = [
            [
                "type" => "click",
                "name" => "今日歌曲",
                "key"  => "V1001_TODAY_MUSIC"
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "搜索",
                        "url"  => "http://www.soso.com/"
                    ],
                    [
                        "type" => "view",
                        "name" => "视频",
                        "url"  => "http://v.qq.com/"
                    ],
                    [
                        "type" => "click",
                        "name" => "赞一下我们",
                        "key" => "V1001_GOOD"
                    ],
                ],
            ],
        ];
        $menu_data = $request->post('menu_data/a');
        $list = [];
        $buttons = [];
        $time = time();
        // 启动事务
        Db::startTrans();
        try{
            $res = MWechatMenu::where('id','>',0)->where('game_id',$request->post('game_id'))->delete();
            $res1 = true;
            $res2 = true;
            foreach ($menu_data as $key => $value) {
                $list['name'] = $value['name'];
                $list['type'] = $value['type'];
                $list['url'] = $value['url'];
                $list['parent_id'] = 0;
                $list['game_id'] = $request->post('game_id');


                //生成微信一级菜单 开始
                    //菜单名称
                    $buttons[$key]['name'] = $value['name'];
                    //判断是否有二级菜单
                    if (!$value['son']) {
                            //菜单类型
                            $buttons[$key]['type'] = MWechatMenu::MENUTYPE[$value['type']];

                            //不同的菜单类型
                            if ($value['type']==1) {
                                $buttons[$key]['url'] = $value['url'];
                            }else{
                                $buttons[$key]['key'] = $value['url'];
                            }
                    }
                //生成微信一级菜单 结束


                $ParentId = MWechatMenu::create($list);
                    //子级
                    foreach ($value['son'] as $sonkey => $sonvalue) {
                        $i += 1;
                        $Sonlist['name'] = $sonvalue['name'];
                        $Sonlist['type'] = $sonvalue['type'];
                        $Sonlist['url'] = $sonvalue['url'];
                        $Sonlist['parent_id'] = $ParentId->id;
                        $Sonlist['game_id'] = $request->post('game_id');
                        $Sonlist['name'] = $sonvalue['name'];
                        //生成微信二级菜单 开始
                        $buttons[$key]['sub_button'][$sonkey]['name'] = $sonvalue['name'];
                        $buttons[$key]['sub_button'][$sonkey]['type'] = MWechatMenu::MENUTYPE[$sonvalue['type']];
                            //不同的菜单类型
                            if ($sonvalue['type']==1) {
                                $buttons[$key]['sub_button'][$sonkey]['url'] = $sonvalue['url'];
                            }else{
                                $buttons[$key]['sub_button'][$sonkey]['key'] = $sonvalue['url'];
                            }
                        //生成微信一级菜单 结束
                        $SonId = MWechatMenu::create($Sonlist);
                        if (!$SonId) {
                            $res2 = false;
                        }
                    }
            }
            $res3 = $app->menu->create($buttons);
            //判断是否成功
            if ($res!==false&&$res1&&$res2&&$res3['errcode']==0) {
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
     * 置顶修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeSort($request,$id)
    {   
        $Games = MGames::get($id);
        $Games->sort = $request->post('sort');
        $res = $Games->save();
        if ($res) {
          return json(['message'=>'置顶/取消置顶，成功！']);
        }
        return json(['message'=>'置顶/取消置顶, 失败 请联系IT部的小学生！'],203);
    }

        /**
     * 游戏列表删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function GamesDelete($id)
    {
        $res = MGames::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }

    /**
     * 游戏列表添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function GamesPost(Request $request)
    {
        $data = [
            'idfa'=>$request->post('idfa'),
            'game_name'=>$request->post('game_name'),
            'ios_dow_code_img'=>$request->post('ios_dow_code_img'),
            'android_dow_code_img'=>$request->post('android_dow_code_img'),
            'ios_download_url'=>$request->get('ios_download_url'),
            'android_download_url'=>$request->post('android_download_url'),
            'official_url'=>$request->post('official_url'),
            'video_url'=>$request->post('video_url'),
            'service_qq'=>$request->post('service_qq'),
            'service_phone'=>$request->post('service_phone'),
            'sketch'=>$request->post('sketch'),
        ];
        $Games = MGames::create($data);
        if ($Games) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

}
 