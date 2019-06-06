<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\WechatUser AS MWechatUser;
use app\api\model\SignInRecord AS MSignInRecord;
use app\api\model\WeekSignInRecord AS MWeekSignInRecord;
use app\api\model\SignInCdk AS MSignInCdk;
use app\api\model\WeekSignGift AS MWeekSignGift;
use app\api\model\DaySignGift AS MDaySignGift;
use think\Request;
use think\Db;
use think\Loader;

class SignIn extends Common
{
    /**
     * 日签到
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function DaySign(Request $request)
    {   
        $token = $request->post('token');

        $GameId = $request->post('game_id');

        $MWechatUser = new MWechatUser;
        
        //解析token 获取用户信息
        $UserInfo = $MWechatUser->Decrypt($token);


        //当签到时间
        $time = time();
        $week = date("w");
        $TimeDate = date('Y-m-d H:i:s',$time);/*
        //通过openid 获取用户id
        $Users = $wpdb->get_row("SELECT id FROM bnfxcms_jlzdy_users WHERE open_id = '".$OpenId."' LIMIT 1");*/


        //对比上一次签到时间和当前时间
        $DaySign = MSignInRecord::where('open_id',$UserInfo->openid)->where('game_id',$GameId)->order('id desc')->find();
        //$DaySign = $wpdb->get_row("SELECT * FROM `bnfxcms_day_sign` WHERE users_id = $Users->id ORDER BY sign_time DESC LIMIT 1" );
        
        $DaySignSter = strtotime($DaySign->created_at);

        $beginWeek = date('Y-m-d H:i:s', (strtotime(date('Y-m-d',$time)) - ((date('w',$time) == 0 ? 7 : date('w',$time)) - 1) * 24 * 3600));
        $endWeek = date('Y-m-d H:i:s', (strtotime(date('Y-m-d',$time))+ 86399 + (7 - (date('w',$time) == 0 ? 7 : date('w',$time))) * 24 * 3600));

        $DaySigncount = MSignInRecord::where('created_at','between time',[$beginWeek,$endWeek])->where('open_id',$UserInfo->openid)->where('game_id',$GameId)->count();

        $count = $DaySigncount + 1;

        if ($DaySign&&strtotime(date('Y-m-d',$time))-strtotime(date('Y-m-d',$DaySignSter))<86400) {
            return json(['message'=>'OK','state'=>1,'data'=>['count'=>$DaySigncount,'sign_data'=>$DaySign]],200);
        }
        // 启动事务
        Db::startTrans();
        try {
            //拿出当前时间的签到码
            $SignCode = MSignInCdk::where("cdk_day_id",$count)->where('game_id',$GameId)->where('status',0)->find();
            if ($SignCode) {
                //改变签到码状态
                $SignCode->status = 1;
                $SignCode->open_id = $UserInfo->openid;
                $SignCode->user_name = $UserInfo->nickname;
                $res = $SignCode->save();
                //写入记录
                $data=array(
                    'open_id'=>$UserInfo->openid,
                    'game_id'=>$GameId,
                    'sign_code'=>$SignCode->code
                );
                $res2 = MSignInRecord::create($data);
                if ($res&&$res2) {
                    Db::commit();
                    return json(['message'=>'OK','state'=>1,'data'=>['count'=>$count,'sign_data'=>$res2]],200);
                }
            }else{
                Db::rollback();
                return json(['message'=>'十分抱歉,今天的签到码已经被领光了!','state'=>0,'data'=>null],200);
            }
        }catch(Exception $e) {
            dump($e);
            // 回滚事务
            Db::rollback();
        }
    }

    /**
     * 周签到
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function WeekSign(Request $request)
    {   
            $token = $request->post('token');

            $GameId = $request->post('game_id');

            $MWechatUser = new MWechatUser;
            
            //解析token 获取用户信息
            $UserInfo = $MWechatUser->Decrypt($token);


            $time = time();
            $beginWeek = date('Y-m-d H:i:s', (strtotime(date('Y-m-d',$time)) - ((date('w',$time) == 0 ? 7 : date('w',$time)) - 1) * 24 * 3600));
            $endWeek = date('Y-m-d H:i:s', (strtotime(date('Y-m-d',$time))+ 86399 + (7 - (date('w',$time) == 0 ? 7 : date('w',$time))) * 24 * 3600));
            //当签到时间
            $week = date("w");
            $TimeDate = date('Y-m-d H:i:s',$time);
            //本周总日签到次数
            $DaySigncount = MSignInRecord::where('created_at','between time',[$beginWeek,$endWeek])->where('open_id',$UserInfo->openid)->where('game_id',$GameId)->count();
            if ($DaySigncount<7) {
                return json(['message'=>'十分抱歉,您不符合领取要求!','state'=>0,'data'=>null],200);
            }
            //本周总周签到次数
            //对比上一次签到时间和当前时间
            $WeekSign = MWeekSignInRecord::where('created_at','between time',[$beginWeek,$endWeek])->where('open_id',$UserInfo->openid)->where('game_id',$GameId)->order('id desc')->find();

            
            if ($WeekSign) {
                return json(['message'=>'success','state'=>1,'data'=>['sign_data'=>$WeekSign]],200);
            }
            // 启动事务
            Db::startTrans();
            try {
                //拿出当前时间的签到码
                $SignCode = MSignInCdk::where("cdk_day_id",8)->where('game_id',$GameId)->where('status',0)->find();
                if ($SignCode) {
                        //改变签到码状态
                    $SignCode->status = 1;
                    $SignCode->open_id = $UserInfo->openid;
                    $SignCode->user_name = $UserInfo->nickname;
                    $res = $SignCode->save();
                    //写入记录
                    $data=array(
                        'open_id'=>$UserInfo->openid,
                        'game_id'=>$GameId,
                        'sign_code'=>$SignCode->code
                    );
                    $res2 = MWeekSignInRecord::create($data);
                    if ($res&&$res2) {
                        Db::commit();
                        return json(['message'=>'success','state'=>1,'data'=>['sign_data'=>$res2]],200);
                    }
                }else{
                    Db::rollback();
                    return json(['message'=>'十分抱歉,今天的签到码已经被领光了!','state'=>0,'data'=>null],200);
                }
            }catch(Exception $e) {
                dump($e);
                // 回滚事务
                Db::rollback();
            }
    }

    /**
     * 日签到记录
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function DayRecord(Request $request)
    {   

        $token = $request->get('token');

        $GameId = $request->get('game_id');

        $MWechatUser = new MWechatUser;
        
        //解析token 获取用户信息
        $UserInfo = $MWechatUser->Decrypt($token);
        $time = time();
        $beginWeek = date('Y-m-d H:i:s', (strtotime(date('Y-m-d',$time)) - ((date('w',$time) == 0 ? 7 : date('w',$time)) - 1) * 24 * 3600));
        $endWeek = date('Y-m-d H:i:s', (strtotime(date('Y-m-d',$time))+ 86399 + (7 - (date('w',$time) == 0 ? 7 : date('w',$time))) * 24 * 3600));

        $DaySign = MSignInRecord::where('created_at','between time',[$beginWeek,$endWeek])->where('open_id',$UserInfo->openid)->where('game_id',$GameId)->count();

        return json(['message'=>'success','state'=>1,'data'=>$DaySign],200);
    }
 
    /**
     * 日签到奖品
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function DaySignGift(Request $request)
    {   
        $DaySignGift = MDaySignGift::where('game_id',$request->get('game_id'))->select();
        return json(['message'=>'success','data'=>$DaySignGift],200);
    }
    
    /**
     * 周签到奖品
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function WeekSignGift(Request $request)
    {   
        $WeekSignGift = MWeekSignGift::where('game_id',$request->get('game_id'))->select();
        return json(['message'=>'success','data'=>$WeekSignGift],200);
    }
}
 