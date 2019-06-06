<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\common\lib\Curl;
use app\api\model\JpTurntableUser AS MJpTurntableUser;
use app\api\model\JpTaskRecord AS MJpTaskRecord;
use app\api\model\AppWebEnter AS MAppWebEnter;
use app\api\model\AppWebPay AS MAppWebPay;
use app\api\model\ExchangeRecord AS MExchangeRecord;
use app\api\model\LuckDrawRecord AS MLuckDrawRecord;
use app\api\model\JpTurntableCommodity AS MJpTurntableCommodity;
use app\api\model\CdkList AS MCdkList;
use EasyWeChat\Factory; //wechar 类
use think\Request;
use think\Loader;
use think\Db;

class JpTurntable extends Common
{
    /**
     * 九品-每日分享回调
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */   
    public function WechatShare(Request $request)
    {   
        $data = [
            'token'=>$request->post('token'),
            'user_id'=>$request->post('user_id')
        ];
        $validate = Loader::validate('ReceiptInformation');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }

        //认证token

        $key = 'f7af36c2c42f86955c3b2b39779827a1';
        $token = md5($key . $data['user_id']);
        if ($token != $data['token']) {
            return json(['message'=>'token 参数错误！'],422);
        }

        //每日分享
        $JpTaskRecord = MJpTaskRecord::where('bn_uid',$data['user_id'])->where('type',2)->whereTime('created_at', 'd')->count();
        if ($JpTaskRecord) {
            return json(['message'=>'您今天的分享任务，已经完成了，请明天再来！'],203);
            exit();
        }  
        $res = MJpTaskRecord::create([
                'bn_uid'=>$request->post('user_id'),
                'type'=>2,
            ]);
        return json(['message'=>'OK']);
    }

    /**
     * 九品-转盘商品列表查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function Get(Request $request)
    {   
        $JpTurntableCommodity = MJpTurntableCommodity::select();
        return json($JpTurntableCommodity);
    }

    /**
     * 九品-获取用户抽奖次数与任务完成度
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function User(Request $request)
    {   
        $data = [
            'token'=>$request->get('token'),
            'user_id'=>$request->get('user_id'),
        ];
        $validate = Loader::validate('JpTurntableUser');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }

        //认证token

        $key = 'f7af36c2c42f86955c3b2b39779827a1';
        $token = md5($key . $data['user_id']);
        if ($token != $data['token']) {
            return json(['message'=>'token 参数错误！'],422);
        }

        // 判断用户是否已经存在
        $JpTurntableUser  = MJpTurntableUser::where('bn_uid',$request->get('user_id'))->find();

        if (!$JpTurntableUser) {
           $res = MJpTurntableUser::create([
                    'bn_uid'=>$request->get('user_id'),
                ]);
           if (!$res) {
               return json(['message'=>'系统错误！'],203);
           }
        }

        $Task = [
            'Is_AppWebEnter_6' => false,
            'Is_AppWebEnter_98' => false,
            'Is_AppWebEnter_648' => false,
            'Is_AppWebEnter' => false,
            'Is_JpTaskRecord' => false
        ];

        $SurplusNum = 0;
        //每日登陆任务
        $AppWebEnter = MAppWebEnter::where('user_id',$data['user_id'])->whereTime('enter_date', 'd')->count();

        if ($AppWebEnter) {
             /*$SurplusNum  = $SurplusNum + 1;*/
             $Task['Is_AppWebEnter'] = true;
        }

        //每日登陆
        $AppWebEnterList = MAppWebEnter::where('user_id',$data['user_id'])->whereTime('enter_date', 'between', ['2018-07-20', '2018-07-23'])->field(['count(user_id)'=>'count_id','DATE_FORMAT(enter_date,"%Y%m%d")'=>'days'])->group('days')->select();
        foreach ($AppWebEnterList as $key => $value) {
            if ($value->count_id) {
                 $SurplusNum  = $SurplusNum + 1;
            }   
        }

        //每日充值任务
        $AppWebPay = MAppWebPay::where('game_id','in','100000206,100000262,100000267,100000269,100000295,100000218,100000261,null1,null3,null5,100000494,100000336,100000587,100000207,100000231,100000216,100000266,100000268,100000287,100000296,100000257,100000310,100000321,100000327,100000356,null2,null4,100000412,100000493,100000329,100000678,100000606')->where('user_id',$data['user_id'])->whereTime('pay_date', 'd')->sum('money');
        if ($AppWebPay >= 6) {
             $Task['Is_AppWebEnter_6'] = true;
        }
        if ($AppWebPay >= 98) {
             $Task['Is_AppWebEnter_98'] = true;
        }
        if ($AppWebPay >= 648) {
             $Task['Is_AppWebEnter_648'] = true;
        }
        //每日充值
        $AppWebPayList = MAppWebPay::where('game_id','in','100000206,100000262,100000267,100000269,100000295,100000218,100000261,null1,null3,null5,100000494,100000336,100000587,100000207,100000231,100000216,100000266,100000268,100000287,100000296,100000257,100000310,100000321,100000327,100000356,null2,null4,100000412,100000493,100000329,100000678,100000606')->where('user_id',$data['user_id'])->whereTime('pay_date', 'between', ['2018-07-20', '2018-07-23'])->field(['sum(money)'=>'sum_money','DATE_FORMAT(pay_date,"%Y%m%d")'=>'days'])->group('days')->select();
        foreach ($AppWebPayList as $key => $value) {
            if ($value->sum_money >= 6) {
                 $SurplusNum  = $SurplusNum + 1;
            }
            if ($value->sum_money >= 98) {
                 $SurplusNum  = $SurplusNum + 1;
            }
            if ($value->sum_money >= 648) {
                 $SurplusNum  = $SurplusNum + 1;
            }
        }

        //每日分享任务
        $JpTaskRecord = MJpTaskRecord::where('bn_uid',$data['user_id'])->where('type',2)->whereTime('created_at', 'd')->count();
        if ($JpTaskRecord) {
             $Task['Is_JpTaskRecord'] = true;
        }   
        //每日分享
        $JpTaskRecordList = MJpTaskRecord::where('bn_uid',$data['user_id'])->where('type',2)->field(['count(id)'=>'count_id','DATE_FORMAT(created_at,"%Y%m%d")'=>'days'])->group('days')->select();


        foreach ($JpTaskRecordList as $key => $value) {
            if ($value->count_id) {
                 $SurplusNum  = $SurplusNum + 1;
            }   
        }


        //消耗次数
        $LuckDrawRecord = MLuckDrawRecord::where('bn_uid',$data['user_id'])->count();

        //当天兑换记录
        $ExchangeList = MExchangeRecord::where('bn_uid',$data['user_id'])->whereTime('created_at', 'd')->select();
        foreach ($ExchangeList as $key => $value) {
            $ExchangeListArr[] = $value->exchange_id;
        }

        return json(['Task'=>$Task,'SurplusNum'=>$SurplusNum-$LuckDrawRecord,'integral'=>$JpTurntableUser->integral,'exchange_list'=>$ExchangeListArr]);
    }
 

     /**
     * 九品-用户抽奖
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function SelectPost(Request $request)
    {   
        exit();
        $timeBegin = strtotime(date('Ymd')) + 32400;
        $timeEnd = strtotime(date('Ymd')) + 86399;

        if(time() > $timeBegin && time() <= $timeEnd)
        {
            $data = [
                'token'=>$request->post('token'),
                'user_id'=>$request->post('user_id'),
            ];
            $validate = Loader::validate('JpTurntableUser');
            if(!$validate->check($data)){
               return json(['message'=>$validate->getError()],422);
            }

            //认证token

            $key = 'f7af36c2c42f86955c3b2b39779827a1';
            $token = md5($key . $data['user_id']);
            if ($token != $data['token']) {
                return json(['message'=>'token 参数错误！'],422);
            }

            $Task = [
                'Is_AppWebEnter_6' => false,
                'Is_AppWebEnter_98' => false,
                'Is_AppWebEnter_648' => false,
                'Is_AppWebEnter' => false,
                'Is_JpTaskRecord' => false
            ];

            $SurplusNum = 0;


            // 启动事务
            Db::startTrans();
            try{
                    //每日登陆
                    $AppWebEnterList = MAppWebEnter::where('user_id',$data['user_id'])->whereTime('enter_date', 'between', ['2018-07-20', '2018-07-23'])->lock(true)->field(['count(user_id)'=>'count_id','DATE_FORMAT(enter_date,"%Y%m%d")'=>'days'])->group('days')->select();
                    foreach ($AppWebEnterList as $key => $value) {
                        if ($value->count_id) {
                             $SurplusNum  = $SurplusNum + 1;
                        }   
                    }

                    //每日充值
                    $AppWebPayList = MAppWebPay::where('game_id','in','100000206,100000262,100000267,100000269,100000295,100000218,100000261,null1,null3,null5,100000494,100000336,100000587,100000207,100000231,100000216,100000266,100000268,100000287,100000296,100000257,100000310,100000321,100000327,100000356,null2,null4,100000412,100000493,100000329,100000678,100000606')->where('user_id',$data['user_id'])->whereTime('pay_date', 'between', ['2018-07-20', '2018-07-23'])->field(['sum(money)'=>'sum_money','DATE_FORMAT(pay_date,"%Y%m%d")'=>'days'])->group('days')->select();
                    foreach ($AppWebPayList as $key => $value) {
                        if ($value->sum_money >= 6) {
                             $SurplusNum  = $SurplusNum + 1;
                        }
                        if ($value->sum_money >= 98) {
                             $SurplusNum  = $SurplusNum + 1;
                        }
                        if ($value->sum_money >= 648) {
                             $SurplusNum  = $SurplusNum + 1;
                        }
                    }

                    //每日分享
                    $JpTaskRecordList = MJpTaskRecord::where('bn_uid',$data['user_id'])->where('type',2)->field(['count(id)'=>'count_id','DATE_FORMAT(created_at,"%Y%m%d")'=>'days'])->group('days')->select();
                    foreach ($JpTaskRecordList as $key => $value) {
                        if ($value->count_id) {
                             $SurplusNum  = $SurplusNum + 1;
                        }   
                    }

                    //消耗次数
                    $LuckDrawRecord = MLuckDrawRecord::where('bn_uid',$data['user_id'])->count();

                    //判断是否具有抽奖资格
                    if ($SurplusNum - $LuckDrawRecord <= 0 ) {
                        return json(['message'=>'您的剩余抽奖次数不足'],203);
                    }

                    $MJpTurntableCommodity = new MJpTurntableCommodity();
                    //获取转盘商品
                    $JpTurntableCommodity = $MJpTurntableCommodity->select();
                    //抽出奖品
                    $CommodityDataKey = $MJpTurntableCommodity->getRotate($JpTurntableCommodity,0);

                    $LuckDrawRecordData = [
                            'bn_uid'=>$request->post('user_id'),
                            'prize_id'=>$JpTurntableCommodity[$CommodityDataKey]->id,
                            'prize_name'=>$JpTurntableCommodity[$CommodityDataKey]->name,
                            'is_grant'=>0,
                            'prize_type'=>$JpTurntableCommodity[$CommodityDataKey]->type
                    ];

                    //写入抽奖记录
                    $res = MLuckDrawRecord::create($LuckDrawRecordData);
                    //减少奖品剩余数量
                    $res2 = $MJpTurntableCommodity->save([
                                                            'surplus_number'  => $JpTurntableCommodity[$CommodityDataKey]->surplus_number - 1,
                                                            ],['id' => $JpTurntableCommodity[$CommodityDataKey]->id]);
                    $res3 = false;


                    $res4 = false;
                    // 用户积分处理
                    if ($JpTurntableCommodity[$CommodityDataKey]->type == 2) {
                        $JpTurntableUser = MJpTurntableUser::where('bn_uid',$request->post('user_id'))->find();
                        $JpTurntableUser->integral = $JpTurntableUser->integral + $JpTurntableCommodity[$CommodityDataKey]->currency_num;
                        $res3 = $JpTurntableUser->save();
                        $res4 = MExchangeRecord::create([
                                'bn_uid'=>$request->post('user_id'),
                                'exchange_name'=>$JpTurntableCommodity[$CommodityDataKey]->name,
                                'prize_type'=>5,
                                'integral'=>$JpTurntableCommodity[$CommodityDataKey]->currency_num,
                            ]);
                    }else{
                        $res3 = true;
                    }
                    //写入奖励列表
                    if ($JpTurntableCommodity[$CommodityDataKey]->type == 3) {
                        $res4 = MExchangeRecord::create([
                                'bn_uid'=>$request->post('user_id'),
                                'exchange_name'=>$JpTurntableCommodity[$CommodityDataKey]->name,
                                'prize_type'=>$JpTurntableCommodity[$CommodityDataKey]->type,
                            ]);
                    }else{
                        $res4 = true;
                    }


                //判断是否操作成功
                if ($res&&$res2&&$res3) {
                    // 提交事务
                    Db::commit();
                    return json(['message'=>'获取奖品成功！','data'=>$CommodityDataKey,'exchange_record'=>$res4]);
                }else{
                    Db::rollback();
                    return json(['message'=>'获取奖品失败，请联系IT部的小学生！'],203);
                }
            } catch (\Exception $e) {
                dump($e);
                // 回滚事务
                Db::rollback();
            }
            return json(['Task'=>$Task,'SurplusNum'=>$SurplusNum]);
        }
    }   


    /**
     * 九品-我的奖励查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function ExchangeRecordGet(Request $request)
    {   
        $data = [
            'token'=>$request->get('token'),
            'user_id'=>$request->get('user_id'),
        ];
        $validate = Loader::validate('JpTurntableUser');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }

        //认证token

        $key = 'f7af36c2c42f86955c3b2b39779827a1';
        $token = md5($key . $data['user_id']);
        if ($token != $data['token']) {
            return json(['message'=>'token 参数错误！'],422);
        }

        $ExchangeRecord = MExchangeRecord::where('bn_uid',$request->get('user_id'))->select();
        return json($ExchangeRecord);
    }

    /**
     * 九品-收货信息
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function ReceiptInformation(Request $request)
    {   
        $data = [
            'token'=>$request->post('token'),
            'user_id'=>$request->post('user_id')
        ];
        $validate = Loader::validate('ReceiptInformation');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }

        //认证token

        $key = 'f7af36c2c42f86955c3b2b39779827a1';
        $token = md5($key . $data['user_id']);
        if ($token != $data['token']) {
            return json(['message'=>'token 参数错误！'],422);
        }


        $ExchangeRecord = MExchangeRecord::where('bn_uid',$request->post('user_id'))->where('id',$request->post('exchange_record_id'))->find();
        $ExchangeRecord->phone = $request->post('phone');
        $ExchangeRecord->receiving_address = $request->post('receiving_address');
        $ExchangeRecord->exchange_name = $request->post('exchange_name');
        $ExchangeRecord->mailbox = $request->post('mailbox');
        $ExchangeRecord->real_name = $request->post('real_name');
         $res = $ExchangeRecord->save();
         if ($res) {
             return json(['message'=>'填写成功']);
         }
        return json(['message'=>'系统错误，填写失败。'],203);
    }

    /**
     * 九品-微信分享配置
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function WechatShareConfigure(Request $request)
    {   

                //写入配置
        $config = [
            'app_id' => 'wx98b87930f340bc0f',
            'secret' => '490e3879a73d4b3125861dff73e6067a',
            'token'     => '234adfsfd2342434ffj462e2d6989b7',
            'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file' => '/data/web/bnweb-cms2/runtime/log/wechat.log',
            ],
        ];

        //实例化类
        $app = Factory::officialAccount($config);
        $app->jssdk->setUrl($request->get('url'));
        $WechatShareConfigure = $app->jssdk->buildConfig(array('onMenuShareAppMessage','onMenuShareTimeline'), false,false,false);
        return json($WechatShareConfigure);
    }

    /**
     * 九品-积分兑换
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function IntegralExchangePost(Request $request)
    {   
        exit();
/*        $checkDayStr = date('Y-m-d ',time());
        $timeBegin1 = strtotime($checkDayStr."09:00".":00");
        $timeEnd1 = strtotime($checkDayStr."12:00".":00");
       
        $curr_time = time();
       
        if($curr_time >= $timeBegin1 && $curr_time <= $timeEnd1)
        {
            exit();
        }*/
        
        $data = [
            'token'=>$request->post('token'),
            'user_id'=>$request->post('user_id'),
            'exchange_id' => $request->post('exchange_id'),
        ];
        $validate = Loader::validate('IntegralExchangePost');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        //认证token

        $key = 'f7af36c2c42f86955c3b2b39779827a1';
        $token = md5($key . $data['user_id']);
        if ($token != $data['token']) {
            return json(['message'=>'token 参数错误！'],422);
        }

        $ExchangeRecordCount = MExchangeRecord::where('exchange_id',$request->post('exchange_id'))->where('bn_uid',$request->post('user_id'))->whereTime('created_at', 'd')->count();

        if ($ExchangeRecordCount) {
            return json(['message'=>"该礼包码，您今天已领取过！"],203);
            exit();
        }
        //兑换列表
        $ExchangeList = [
            [
                'GiftBagId'=>98,
                'integral'=>30,
                'ExchangeName'=>'出战令*1',
            ],
            [
                'GiftBagId'=>99,
                'integral'=>60,
                'ExchangeName'=>'政绩礼包*1',
            ],
            [
                'GiftBagId'=>100,
                'integral'=>80,
                'ExchangeName'=>'卷轴残卷*2',
            ],
            [
                'GiftBagId'=>101,
                'integral'=>200,
                'ExchangeName'=>'夏日礼包*1',
            ],
            [
                'GiftBagId'=>102,
                'integral'=>300,
                'ExchangeName'=>'粉丝礼包*1',
            ],
            [
                'GiftBagId'=>103,
                'integral'=>450,
                'ExchangeName'=>'狂欢礼包*1',
            ]
        ];

        // 启动事务
        Db::startTrans();
        try{
            $JpTurntableUser = MJpTurntableUser::where('bn_uid',$request->post('user_id'))->lock(true)->find();
            $integral = $JpTurntableUser->integral - $ExchangeList[$request->post('exchange_id')]['integral'];
            if ($integral<0) {
               Db::rollback();
               return json(['message'=>"您的积分不足。"],205);
            }

            $JpTurntableUser->integral = $integral;

            $res = $JpTurntableUser->save();

            $Cdk = MCdkList::where('cdk_type_id',$ExchangeList[$request->post('exchange_id')]['GiftBagId'])->where('status',0)->field('id,code,status')->order('id desc')->find();
            if (!$Cdk) {
                return json(['message'=>"该礼包已被领光！"],203);
            }
            $Cdk->status = 1;
            $res2 = $Cdk->save();

            $res3 = MExchangeRecord::create([
                    'bn_uid'=>$request->post('user_id'),
                    'exchange_name'=>$ExchangeList[$request->post('exchange_id')]['ExchangeName'],
                    'exchange_id'=>$request->post('exchange_id'),
                    'integral'=>$ExchangeList[$request->post('exchange_id')]['integral'],
                    'cdk_code'=>$Cdk->code,
                    'prize_type'=>2
                ]);

            if ($res&&$res2&&$res3) {
                // 提交事务
                Db::commit();
                return json(['message'=>'兑换成功！','data'=>$Cdk]);
            }
        } catch (\Exception $e) {
            dump($e);
            // 回滚事务
            Db::rollback();
        }
    }

}
 