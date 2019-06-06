<?php
// +----------------------------------------------------------------------
// | Description: 微信公众号服务
// +----------------------------------------------------------------------
// | Author: 涛涛 <772369024@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\api\model\WechatConfigure as MWechatConfigure;
use app\api\model\WechatKeyword as MWechatKeyword;
use app\api\model\WechatCoverBuilding as MWechatCoverBuilding;
use app\api\model\CdkType AS MCdkType;
use app\api\model\CdkList AS MCdkList;
use app\api\model\CdkRecord AS MCdkRecord;
use EasyWeChat\Factory; //wechar 类
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use think\Controller;
use think\Request;
use think\Db;


class Wechat extends Controller 
{
    /**
     * 微信服务接入以及自动回复
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Serve($id)
    {   
        //获取游戏对应微信配置
        $WechatConfigure = MWechatConfigure::where('game_id',$id)->where('type',4)->where('access_state',1)->find();
        if (!$WechatConfigure) {
            return json(['message'=>'十分抱歉，该游戏没有微信认证基础配置喔！'],203);
            exit();
        }
        //写入配置
        $config = [
            'app_id' => $WechatConfigure->app_id,
            'secret' => $WechatConfigure->app_secret,
            'token'     => $WechatConfigure->token,
            'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file' => '/data/web/bnweb-cms2/runtime/log/wechat.log',
            ],
        ];

        //实例化类
        $app = Factory::officialAccount($config);

        //获取微信用户名
        $MWechatKeyword = new MWechatKeyword();
        $MCdkList = new MCdkList();
        $MCdkType = new MCdkType();
        $MCdkRecord = new MCdkRecord();
        $MWechatCoverBuilding = new MWechatCoverBuilding();
        //redis 缓存实例
        $Redis = \ibnthink\RedisPool::get();
        //这里是回复内容（闭包）不会就研究。
        $app->server->push(function ($message) use ($id,$MWechatKeyword,$MCdkList,$MCdkType,$MCdkRecord,$Redis,$MWechatCoverBuilding){
            //$message里面的参数自己看文档https://www.easywechat.com/docs/master/zh-CN/official-account/server
            /*$WechatFrontQA = [
                'front_problem'=>'',
                'front_reply'=>''
            ];*/

        //对冰鸟网络公众号单独做处理
        if ($id == 12) {
            return '亲爱的玩家您好，由于业务升级，目前公众号正式更换为“冰鸟游戏”，请您搜索公众号“冰鸟游戏”后进行关注领取礼包哦。感谢您对我们的支持，祝您游戏愉快 。';
        }

        try{
            switch ($message['MsgType']) {
                case 'event':
                        //首次关注
                        if ($message['Event']=='subscribe') {
                            $WechatKeyword = $MWechatKeyword->where('keyword','首次关注提示')->where('game_id',$id)->find();
                            if ($WechatKeyword) {
                                //查询回复
                                $WechatReplys = $WechatKeyword->replys;
                                return $WechatReplys[0]->text_content;;
                            }else{
                                return '欢迎关注本公众号！';
                            }
                            break;
                        }else if ($message['Event']=='CLICK') {

                                $keyword = $message['EventKey'];
                                //查询关键字
                                $WechatKeyword = $MWechatKeyword->where('find_in_set(:keyword,keyword)',['keyword'=>$keyword])->where('game_id',$id)->find();
                                //查询关键字 - 模糊
                                if (!$WechatKeyword) {
                                    $WechatKeyword = $MWechatKeyword->where('locate(keyword,:keyword)',['keyword'=>$keyword])->where('is_vague',1)->where('game_id',$id)->find();
                                }
                                //查询回复
                                $WechatReplys = $WechatKeyword->replys;
                                if ($WechatKeyword->type == 1) {//文本回复
                                    //写入redis缓存
                                    $Redis->setex('front_reply_'.$message['FromUserName'],3600,$WechatReplys[0]->text_content);
                                    return $WechatReplys[0]->text_content;
                                    break;
                                }else if ($WechatKeyword->type == 2) {
                                    $items = [];
                                    foreach ($WechatReplys as $key => $value) {//图文回复
                                        $items[] = new NewsItem([
                                                                    'title'       => $value->title,
                                                                    'description' => $value->describe,
                                                                    'url'         => $value->url,
                                                                    'image'       => 'https://cdn3.ibingniao.com/'.$value->img_url,
                                                                ]);
                                    }
                                    return new News($items);
                                    break;
                                }else if ($WechatKeyword->type == 3) { //cdk礼包码回复
                                    $cdk_type_id = $WechatReplys[0]->cdk_type_id;
                                    //获取礼包信息
                                    $TimeDate = date("Y-m-d H:i:s",time());
                                    $CdkType = $MCdkType->where('id',$cdk_type_id)->where('start_at','<',$TimeDate)->where('expired_at','>',$TimeDate)->find();
                                        if (!$CdkType) {
                                            return '十分抱歉，您不在本活动日期范围！';
                                            break;
                                        }
                                    //查询用户是否已经领取
                                    $MCdkRecord->startTrans();//开启事物
                                    try{
                                        $CdkRecord = $MCdkRecord->lock(true)->where('cdk_type_id',$cdk_type_id)->where('open_id',$message['FromUserName'])->find();
                                        //已领取 执行
                                        if ($CdkRecord) {
                                            //构造图文回复
                                                if ($CdkRecord->code) { //再判断用户是否成功领取礼包码
                                                            $items[] = new NewsItem([
                                                                'title'       => $CdkType->cdk_type_title,
                                                                'description' => $CdkType->cdk_type_content,
                                                                'url'         => 'https://www.310game.com/newcms2/wechat/index.html?open_id='.$message['FromUserName'].'&cdk_type_id='.$cdk_type_id.'&time='.time(),
                                                                'image'       => 'https://cdn3.ibingniao.com/'.$CdkType->head_img,
                                                            ]);
                                                            $MCdkRecord->commit();
                                                            return new News($items);
                                                            break;
                                                }else{
                                                            $MCdkRecord->commit();
                                                            return '十分抱歉，您没有获取到该礼包的礼包码！';
                                                            break;            
                                                }
                                        }//用户没有领取过礼包  执行
                                                //获取礼包码
                                                $Cdk = $MCdkList->where('cdk_type_id',$cdk_type_id)->lock(true)->where('status',0)->order('id desc')->find(); 
                                                if (!$Cdk) {
                                                    $MCdkRecord->commit();
                                                    $WechatKeyword = $MWechatKeyword->where('keyword','礼包码为空提示')->where('game_id',$id)->find();
                                                    if ($WechatKeyword) {
                                                        //查询回复
                                                        $WechatReplys = $WechatKeyword->replys;
                                                        return $WechatReplys[0]->text_content;;
                                                    }else{
                                                        return '目前礼包码发放完毕，工作人员正在补充，请稍后尝试领取。';
                                                    }
                                                    break;
                                                }

                                                //概率获取礼包码
                                                $rand =  rand(1,100);
                                                $CdkType->probability;
                                                if ($rand<=$CdkType->probability) { //成功获取cdk
                                                            $Cdk->status = 1;
                                                            $Cdk->open_id = $message['FromUserName'];
                                                            $data = [
                                                                'open_id'=>$message['FromUserName'],
                                                                'code'=>$Cdk->code,
                                                                'cdk_type_id'=>$cdk_type_id
                                                            ];
                                                            //写入领取记录
                                                            $res2 = $MCdkRecord->create($data);
                                                            //更新cdk码领取状态
                                                            $res = $Cdk->save();
                                                            if ($res&&$res2) {
                                                                //构造图文回复
                                                                 $items[] = new NewsItem([
                                                                                    'title'       => $CdkType->cdk_type_title,
                                                                                    'description' => $CdkType->cdk_type_content,
                                                                                    'url'         => 'https://www.310game.com/newcms2/wechat/index.html?open_id='.$message['FromUserName'].'&cdk_type_id='.$cdk_type_id.'&time='.time(),
                                                                                    'image'       => 'https://cdn3.ibingniao.com/'.$CdkType->head_img,
                                                                                ]);
                                                                $MCdkRecord->commit();
                                                                return new News($items);
                                                                break;
                                                            }  
                                                }else{ //没有获取到cdk
                                                        $data = [
                                                            'open_id'=>$message['FromUserName'],
                                                            'code'=>$Cdk->code,
                                                            'cdk_type_id'=>$cdk_type_id
                                                        ];
                                                        //写入领取记录
                                                        $CdkRecord = $MCdkRecord->create($data);
                                                        if ($CdkRecord) {
                                                            $MCdkRecord->commit();
                                                            return '十分抱歉，您没有获取到该礼包的礼包码！';
                                                            break;   
                                                        } 
                                                }
                                        } catch (\Exception $e) {
                                            $MCdkRecord->rollback();
                                        }
                                }
                                $MCdkRecord->rollback();
                                return '';
                                break;
                        }
                    return "";
                    break;
                case 'text':

                    $keyword = $message['Content'];

                    if ($keyword == "11") {
                                return '111';
                                break;
                    }
                    //查询关键字
                    $WechatKeyword = $MWechatKeyword->where('find_in_set(:keyword,keyword)',['keyword'=>$keyword])->where('is_context',0)->where('game_id',$id)->find();
                    //查询关键字 - 模糊
                    if (!$WechatKeyword) {
                        $WechatKeyword = $MWechatKeyword->where('locate(keyword,:keyword)',['keyword'=>$keyword])->where('is_context',0)->where('is_vague',1)->where('game_id',$id)->find();
                    }

                    //上下文查询
                    if (!$WechatKeyword) {
                        $WechatKeywordArr = $MWechatKeyword->where('keyword',$keyword)->where('is_context',1)->where('game_id',$id)->select();
                        $is_front_problem = false;
                        $is_front_reply = false;
                        foreach ($WechatKeywordArr as $key => $WechatKeywordItem) {
                                            //判断上一句问题是否符合要求
                                if (!$WechatKeywordItem->front_problem||str_replace(array(" ","　","\t","\n","\r"), array("","","","",""), $WechatKeywordItem->front_problem) == str_replace(array(" ","　","\t","\n","\r"), array("","","","",""), $Redis->get('front_problem_'.$message['FromUserName']))) {
                                    $is_front_problem = true;
                                    $WechatKeywordKey = $key;
                                }
                                            //判断上一句回复是否符合要求
                                if (!$WechatKeywordItem->front_reply||str_replace(array(" ","　","\t","\n","\r"), array("","","","",""), $WechatKeywordItem->front_reply) == str_replace(array(" ","　","\t","\n","\r"), array("","","","",""), $Redis->get('front_reply_'.$message['FromUserName']))) {
                                    $is_front_reply = true;
                                    $WechatKeywordKey = $key;
                                    break;
                                }
                        }
                        if (!$is_front_problem||!$is_front_reply) {
                            $Redis->setex('front_problem_'.$message['FromUserName'],3600,$keyword);
                            return '';
                            break;
                        }
                        $WechatKeyword = $MWechatKeyword->get($WechatKeywordArr[$WechatKeywordKey]->id);
                    }

                    //查询回复
                    $WechatReplys = $WechatKeyword->replys;


                    //先判断关键字是否拥有子关键字回复

                    if ($WechatKeyword->type == 4) {
                            $MCdkRecord->startTrans();//开启事物
                            $isFloor = false;
                            $WechatCoverBuilding = $MWechatCoverBuilding->where('keyword',$keyword)->where('game_id',$id)->lock(true)->find();
                            $WechatCoverBuilding->count_num = $WechatCoverBuilding->count_num+1;
                            $WechatCoverBuildingRes = $WechatCoverBuilding->save();


                            $floor_arr = explode("|", $WechatKeyword->floor);
                            foreach ($floor_arr as $floorkey => $floorvalue) {
                                $floor = explode(",", $floorvalue);
                                if (in_array($WechatCoverBuilding->count_num,$floor)) {
                                    $foorRewardKey = $floorkey;
                                     $isFloor = true;
                                }
                            }

                            if (!$isFloor) {
                                $MCdkRecord->commit();

                                $WechatKeyword = $MWechatKeyword->where('keyword','盖楼引导提示')->where('game_id',$id)->find();
                                //查询回复
                                $WechatReplys = $WechatKeyword->replys;
                                //楼层文本过滤
                                $content = preg_replace('#{{楼层}}#',$WechatCoverBuilding->count_num,$WechatReplys[0]->text_content);
                                //关键字文本过滤
                                $content = preg_replace('#{{关键字}}#','"'.$keyword.'"',$content);

                                return $content;
                                break; 
                            }
                            $foorRewardKey = 0;
                           // return $WechatReplys[0]->text_content;
                            $keyword = $WechatReplys[$foorRewardKey]->sub_keyword;
                            //查询关键字
                            $WechatKeyword = $MWechatKeyword->where('find_in_set(:keyword,keyword)',['keyword'=>$keyword])->where('game_id',$id)->find();
                            //查询关键字 - 模糊
                            if (!$WechatKeyword) {
                                $WechatKeyword = $MWechatKeyword->where('locate(keyword,:keyword)',['keyword'=>$keyword])->where('is_vague',1)->where('game_id',$id)->find();
                            }

                            //查询回复
                            $WechatReplys = $WechatKeyword->replys;
                    }

                    if ($WechatKeyword->type == 1) {//文本回复

                        if ($WechatKeyword->is_context == 1) { //判断是否开启上下文模式
                                $is_front_problem = false;
                                $is_front_reply = false;
                                        //判断上一句问题是否符合要求
                            if (!$WechatKeyword->front_problem||str_replace(array(" ","　","\t","\n","\r"), array("","","","",""), $WechatKeyword->front_problem) == str_replace(array(" ","　","\t","\n","\r"), array("","","","",""), $Redis->get('front_problem_'.$message['FromUserName']))) {
                                $is_front_problem = true;
                            }
                                        //判断上一句回复是否符合要求
                            if (!$WechatKeyword->front_reply||str_replace(array(" ","　","\t","\n","\r"), array("","","","",""), $WechatKeyword->front_reply) == str_replace(array(" ","　","\t","\n","\r"), array("","","","",""), $Redis->get('front_reply_'.$message['FromUserName']))) {
                                $is_front_reply = true;
                            }
                            if (!$is_front_problem||!$is_front_reply) {
                                $Redis->setex('front_problem_'.$message['FromUserName'],3600,$keyword);
                                $MCdkRecord->commit();
                                return '';
                                break;
                            }
                        }
                        //写入redis缓存
                        $Redis->setex('front_problem_'.$message['FromUserName'],3600,$keyword);
                        $Redis->setex('front_reply_'.$message['FromUserName'],3600,$WechatReplys[0]->text_content);
                        return $WechatReplys[0]->text_content;
                        break;
                    }else if ($WechatKeyword->type == 2) {
                        $Redis->setex('front_problem_'.$message['FromUserName'],3600,$keyword);
                        $items = [];
                        foreach ($WechatReplys as $key => $value) {//图文回复
                            $items[] = new NewsItem([
                                                        'title'       => $value->title,
                                                        'description' => $value->describe,
                                                        'url'         => $value->url,
                                                        'image'       => 'https://cdn3.ibingniao.com/'.$value->img_url,
                                                    ]);
                        }
                        return new News($items);
                        break;
                    }else if ($WechatKeyword->type == 3) { //cdk礼包码回复
                        $Redis->setex('front_problem_'.$message['FromUserName'],3600,$keyword);
                                    $cdk_type_id = $WechatReplys[0]->cdk_type_id;
                                    //获取礼包信息
                                    $TimeDate = date("Y-m-d H:i:s",time());
                                    $CdkType = $MCdkType->where('id',$cdk_type_id)->where('start_at','<',$TimeDate)->where('expired_at','>',$TimeDate)->find();
                                        if (!$CdkType) {
                                            return '十分抱歉，您不在本活动日期范围！';
                                            break;
                                        }
                                    //查询用户是否已经领取

                                    try{
                                    if ($isFloor) {
                                        $MCdkRecord->startTrans();//开启事物
                                    }
                                        $CdkRecord = $MCdkRecord->lock(true)->where('cdk_type_id',$cdk_type_id)->where('open_id',$message['FromUserName'])->find();
                                        //已领取 执行
                                        if ($CdkRecord) {
                                            //构造图文回复
                                                if ($CdkRecord->code) { //再判断用户是否成功领取礼包码
                                                    if ($WechatKeyword->is_cover_building == 1) {
                                                        $WechatKeyword = $MWechatKeyword->where('keyword','盖楼领奖后提示')->where('game_id',$id)->find();
                                                        //查询回复
                                                        $WechatReplys = $WechatKeyword->replys;
                                                        return $WechatReplys[0]->text_content;
                                                    }else{
                                                            $items[] = new NewsItem([
                                                                'title'       => $CdkType->cdk_type_title,
                                                                'description' => $CdkType->cdk_type_content,
                                                                'url'         => 'https://www.310game.com/newcms2/wechat/index.html?open_id='.$message['FromUserName'].'&cdk_type_id='.$cdk_type_id.'&time='.time(),
                                                                'image'       => 'https://cdn3.ibingniao.com/'.$CdkType->head_img,
                                                            ]);
                                                            $MCdkRecord->commit();
                                                            return new News($items);
                                                            break;
                                                    }
                                                }else{
                                                            $MCdkRecord->commit();
                                                            return '十分抱歉，您没有获取到该礼包的礼包码！';
                                                            break;            
                                                }
                                        }//用户没有领取过礼包  执行

                                                // if ($WechatKeyword->is_cover_building == 1) {//判断是否开启盖楼模式
                                                //     $WechatCoverBuilding = MWechatCoverBuilding::where('keyword',$keyword)->find();
                                                //     $WechatCoverBuilding->count_num = $WechatCoverBuilding->count_num+1;
                                                //     $res3 = $WechatCoverBuilding->save();
                                                //     $floor_arr = explode(",", $WechatKeyword->floor);
                                                //     //判断用户楼层是否为中奖楼层
                                                //     if (!in_array($WechatCoverBuilding->count_num,$floor_arr)) {
                                                //         if ($res3) {
                                                //             $MCdkRecord->commit();

                                                //             $WechatKeyword = $MWechatKeyword->where('keyword','盖楼引导提示')->where('game_id',$id)->find();
                                                //             //查询回复
                                                //             $WechatReplys = $WechatKeyword->replys;
                                                //             //楼层文本过滤
                                                //             $content = preg_replace('#{{楼层}}#',$WechatCoverBuilding->count_num,$WechatReplys[0]->text_content);
                                                //             //关键字文本过滤
                                                //             $content = preg_replace('#{{关键字}}#','"'.$keyword.'"',$content);

                                                //             return $content;

                                                //             /*return '您现在在第'.$WechatCoverBuilding->count_num.'楼';*/
                                                //             break; 
                                                //         } 
                                                //     }

                                                // }
                                                //获取礼包码
                                                $Cdk = $MCdkList->where('cdk_type_id',$cdk_type_id)->where('status',0)->order('id desc')->find(); 
                                                if (!$Cdk) {
                                                    $MCdkRecord->commit();
                                                    $WechatKeyword = $MWechatKeyword->where('keyword','礼包码为空提示')->where('game_id',$id)->find();
                                                    if ($WechatKeyword) {
                                                        //查询回复
                                                        $WechatReplys = $WechatKeyword->replys;
                                                        return $WechatReplys[0]->text_content;
                                                    }else{
                                                        return '目前礼包码发放完毕，工作人员正在补充，请稍后尝试领取。';
                                                    }
                                                }

                                                //概率获取礼包码
                                                $rand =  rand(1,100);
                                                $CdkType->probability;
                                                if ($rand<=$CdkType->probability) { //成功获取cdk
                                                            $Cdk->status = 1;
                                                            $Cdk->open_id = $message['FromUserName'];
                                                            $data = [
                                                                'open_id'=>$message['FromUserName'],
                                                                'code'=>$Cdk->code,
                                                                'cdk_type_id'=>$cdk_type_id
                                                            ];
                                                            //写入领取记录
                                                            $res2 = $MCdkRecord->create($data);
                                                            //更新cdk码领取状态
                                                            $res = $Cdk->save();
                                                            if ($res&&$res2) {
                                                                //构造图文回复
                                                                 $items[] = new NewsItem([
                                                                                    'title'       => $CdkType->cdk_type_title,
                                                                                    'description' => $CdkType->cdk_type_content,
                                                                                    'url'         => 'https://www.310game.com/newcms2/wechat/index.html?open_id='.$message['FromUserName'].'&cdk_type_id='.$cdk_type_id.'&time='.time(),
                                                                                    'image'       => 'https://cdn3.ibingniao.com/'.$CdkType->head_img,
                                                                                ]);
                                                                $MCdkRecord->commit();
                                                                return new News($items);
                                                                break;
                                                            }  
                                                }else{ //没有获取到cdk
                                                        $data = [
                                                            'open_id'=>$message['FromUserName'],
                                                            'code'=>$Cdk->code,
                                                            'cdk_type_id'=>$cdk_type_id
                                                        ];
                                                        //写入领取记录
                                                        $CdkRecord = $MCdkRecord->create($data);
                                                        if ($CdkRecord) {
                                                            $MCdkRecord->commit();
                                                            return '十分抱歉，您没有获取到该礼包的礼包码！';
                                                            break;   
                                                        } 
                                                }
                                        } catch (\Exception $e) {
                                            $MCdkRecord->rollback();
                                        }
                    }else{  
                        $Redis->setex('front_problem_'.$message['FromUserName'],3600,$keyword);
                        return "";
                        break;
                    }
                    break;
                case 'image':
                    $keyword = '图片消息提示回复@？';
                    $WechatKeyword = $MWechatKeyword->where('keyword',$keyword)->where('game_id',$id)->find();
                    $WechatReplys = $WechatKeyword->replys;
                    if ($WechatKeyword->type == 1) {//文本回复

                        if ($WechatKeyword->is_context == 1) { //判断是否开启上下文模式
                                $is_front_problem = false;
                                $is_front_reply = false;
                                        //判断上一句问题是否符合要求
                            if (!$WechatKeyword->front_problem||str_replace(array(" ","　","\t","\n","\r"), array("","","","",""), $WechatKeyword->front_problem) == str_replace(array(" ","　","\t","\n","\r"), array("","","","",""), $Redis->get('front_problem_'.$message['FromUserName']))) {
                                $is_front_problem = true;
                            }
                                        //判断上一句回复是否符合要求
                            if (!$WechatKeyword->front_reply||str_replace(array(" ","　","\t","\n","\r"), array("","","","",""), $WechatKeyword->front_reply) == str_replace(array(" ","　","\t","\n","\r"), array("","","","",""), $Redis->get('front_reply_'.$message['FromUserName']))) {
                                $is_front_reply = true;
                            }
                            if (!$is_front_problem||!$is_front_reply) {
                                $Redis->setex('front_problem_'.$message['FromUserName'],3600,$keyword);
                                return '';
                                break;
                            }
                        }
                        //写入redis缓存
                        $Redis->setex('front_problem_'.$message['FromUserName'],3600,$keyword);
                        $Redis->setex('front_reply_'.$message['FromUserName'],3600,$WechatReplys[0]->text_content);
                        return $WechatReplys[0]->text_content;
                        break;
                    }else if ($WechatKeyword->type == 2) {
                        $Redis->setex('front_problem_'.$message['FromUserName'],3600,$keyword);
                        $items = [];
                        foreach ($WechatReplys as $key => $value) {//图文回复
                            $items[] = new NewsItem([
                                                        'title'       => $value->title,
                                                        'description' => $value->describe,
                                                        'url'         => $value->url,
                                                        'image'       => 'https://cdn3.ibingniao.com/'.$value->img_url,
                                                    ]);
                        }
                        return new News($items);
                        break;
                    }else if ($WechatKeyword->type == 3) { //cdk礼包码回复
                        $Redis->setex('front_problem_'.$message['FromUserName'],3600,$keyword);
                                    $cdk_type_id = $WechatReplys[0]->cdk_type_id;
                                    //获取礼包信息
                                    $TimeDate = date("Y-m-d H:i:s",time());
                                    $CdkType = $MCdkType->where('id',$cdk_type_id)->where('start_at','<',$TimeDate)->where('expired_at','>',$TimeDate)->find();
                                        if (!$CdkType) {
                                            return '十分抱歉，您不在本活动日期范围！';
                                            break;
                                        }
                                    //查询用户是否已经领取
                                    $MCdkRecord->startTrans();//开启事物
                                    try{
                                        $CdkRecord = $MCdkRecord->lock(true)->where('cdk_type_id',$cdk_type_id)->where('open_id',$message['FromUserName'])->find();
                                        //已领取 执行
                                        if ($CdkRecord) {
                                            //构造图文回复
                                                if ($CdkRecord->code) { //再判断用户是否成功领取礼包码
                                                            $items[] = new NewsItem([
                                                                'title'       => $CdkType->cdk_type_title,
                                                                'description' => $CdkType->cdk_type_content,
                                                                'url'         => 'https://www.310game.com/newcms2/wechat/index.html?open_id='.$message['FromUserName'].'&cdk_type_id='.$cdk_type_id.'&time='.time(),
                                                                'image'       => 'https://cdn3.ibingniao.com/'.$CdkType->head_img,
                                                            ]);
                                                            $MCdkRecord->commit();
                                                            return new News($items);
                                                            break;
                                                }else{
                                                            $MCdkRecord->commit();
                                                            return '十分抱歉，您没有获取到该礼包的礼包码！';
                                                            break;            
                                                }
                                        }//用户没有领取过礼包  执行
                                                //获取礼包码
                                                $Cdk = $MCdkList->where('cdk_type_id',$cdk_type_id)->where('status',0)->order('id desc')->find(); 
                                                if (!$Cdk) {
                                                    $MCdkRecord->commit();
                                                    $WechatKeyword = $MWechatKeyword->where('keyword','礼包码为空提示')->where('game_id',$id)->find();
                                                    if ($WechatKeyword) {
                                                        //查询回复
                                                        $WechatReplys = $WechatKeyword->replys;
                                                        return $WechatReplys[0]->text_content;;
                                                    }else{
                                                        return '目前礼包码发放完毕，工作人员正在补充，请稍后尝试领取。';
                                                    }
                                                }

                                                //概率获取礼包码
                                                $rand =  rand(1,100);
                                                $CdkType->probability;
                                                if ($rand<=$CdkType->probability) { //成功获取cdk
                                                            $Cdk->status = 1;
                                                            $Cdk->open_id = $message['FromUserName'];
                                                            $data = [
                                                                'open_id'=>$message['FromUserName'],
                                                                'code'=>$Cdk->code,
                                                                'cdk_type_id'=>$cdk_type_id
                                                            ];
                                                            //写入领取记录
                                                            $res2 = $MCdkRecord->create($data);
                                                            //更新cdk码领取状态
                                                            $res = $Cdk->save();
                                                            if ($res&&$res2) {
                                                                //构造图文回复
                                                                 $items[] = new NewsItem([
                                                                                    'title'       => $CdkType->cdk_type_title,
                                                                                    'description' => $CdkType->cdk_type_content,
                                                                                    'url'         => 'https://www.310game.com/newcms2/wechat/index.html?open_id='.$message['FromUserName'].'&cdk_type_id='.$cdk_type_id.'&time='.time(),
                                                                                    'image'       => 'https://cdn3.ibingniao.com/'.$CdkType->head_img,
                                                                                ]);
                                                                $MCdkRecord->commit();
                                                                return new News($items);
                                                                break;
                                                            }  
                                                }else{ //没有获取到cdk
                                                        $data = [
                                                            'open_id'=>$message['FromUserName'],
                                                            'code'=>$Cdk->code,
                                                            'cdk_type_id'=>$cdk_type_id
                                                        ];
                                                        //写入领取记录
                                                        $CdkRecord = $MCdkRecord->create($data);
                                                        if ($CdkRecord) {
                                                            $MCdkRecord->commit();
                                                            return '十分抱歉，您没有获取到该礼包的礼包码！';
                                                            break;   
                                                        } 
                                                }
                                        } catch (\Exception $e) {
                                            $MCdkRecord->rollback();
                                        }
                    }else{  
                        return '收到图片消息';
                    }
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        } catch (\Exception $e) {
            ibnerror($e);
        }
        });

        $response = $app->server->serve();

        //这里是输出
        $response->send();

    }

    /**
     * 微信服务接入以及自动回复
     *`
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Serve2($id)
    {   

        $keyword = '盖楼测试1';

        //查询关键字
        $WechatKeyword = MWechatKeyword::where('find_in_set(:keyword,keyword)',['keyword'=>$keyword])->where('game_id',7)->find();
        //查询关键字 - 模糊
        if (!$WechatKeyword) {
            $WechatKeyword = MWechatKeyword::where('locate(keyword,:keyword)',['keyword'=>$keyword])->where('is_vague',1)->where('game_id',7)->find();
        }
        //查询回复
        $WechatReplys = $WechatKeyword->replys;

        var_dump($WechatReplys[0]->sub_keyword);
    }
}
 