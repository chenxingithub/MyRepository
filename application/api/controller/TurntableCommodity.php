<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\TurntableCommodity AS MTurntableCommodity;
use app\api\model\TurntableUser AS MTurntableUser;
use app\api\model\TurntableRecord AS MTurntableRecord;
use think\Request;
use think\Loader;
use think\Db;

class TurntableCommodity extends Common
{
    /**
     * 转盘商品列表查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function Get(Request $request)
    {   
        $MTurntableCommodity = MTurntableCommodity::select();
        return json($MTurntableCommodity);
    }

    /**
     * 转盘商品获取
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function SelectPost(Request $request)
    {

        // return json(['message'=>'抽奖系统正在维护中，请留意群里的修复通知！'],203);
        // $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
        //         //设置起始时间
        // $start= strtotime($BeginDate." 14:30:00");
        // //设置结束时间
        // $end= strtotime(date('Y-m-d', strtotime("$BeginDate +1 month -1 day"))." 23:30:00");
        // if(time() > $end|| time() < $start){
        //     return json(['message'=>'不在访问时间内'],203);
        // }


        $MTurntableCommodity = new MTurntableCommodity();
        //获取转盘商品
        $TurntableCommodity = $MTurntableCommodity->select();


       $TurntableUser = new MTurntableUser;
        //解析token 获取用户信息
       $UserInfo = $TurntableUser->Decrypt($request->post('bn_token'));

       if ($UserInfo->gender == 0) {
            foreach ($TurntableCommodity as $key => $value) {
                $TurntableCommodity[$key]->probability = $value->probability + 250;
            }
        }
        //抽出奖品
        $CommodityDataKey = $MTurntableCommodity->getRotate($TurntableCommodity);

        //抽奖用户


                // 启动事务
        Db::startTrans();

        try{
/*                $TurntableUser = MTurntableUser::where('real_name',$request->post('real_name'))->where('work_number',$request->post('work_number'))->lock(true)->find();*/

                $count = MTurntableRecord::whereTime('created_at', 'm')->where('uid',$UserInfo->id)->lock(true)->count();

                //判断限制次数
                if ($UserInfo->type==1) {
                    $limitCount = 2;
                }else{
                    $limitCount = 1;
                }

                if ($count>=$limitCount) {
                    return json(['message'=>'您本周的抽奖次数，已用完...'],203);
                }

                if (!$UserInfo) {
                    return json(['message'=>'非法访问'],203);
                }

/*                if (!$CommodityDataKey) {
                    return json(['message'=>'系统错误'],203);
                }*/
                
                $TurntableRecordData = [
                        'uid'=>$UserInfo->id,
                        'claim'=>$UserInfo->real_name,
                        'commodity_id'=>$TurntableCommodity[$CommodityDataKey]->id,
                        'commodity_name'=>$TurntableCommodity[$CommodityDataKey]->name,
                        'consumption_num'=>1,
                        'is_grant'=>0,
                        'commodity_type'=>0
                ];

                $TurntableRecord = MTurntableRecord::create($TurntableRecordData);

                $Commodity = MTurntableCommodity::get($TurntableCommodity[$CommodityDataKey]->id);
                $Commodity->surplus_number  =  $Commodity->surplus_number - 1; 

                $res = $Commodity->save();

                if ($TurntableRecord&&$res) {
                    Db::commit();
                  return json(['message'=>'获取奖品成功！','data'=>$CommodityDataKey]);
                }
                    Db::rollback();
                return json(['message'=>'系统错误'],203);



        } catch (\Exception $e) {
           return json(['message'=>'系统错误'],203);
            // 回滚事务
            Db::rollback();
        }

    }

    /**
     * 登录
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Login(Request $request)
    {

        $TurntableUser = MTurntableUser::where('real_name',$request->post('real_name'))->where('work_number',$request->post('work_number'))->find();

        if ($TurntableUser) {

         //获取用户token
         $token = $TurntableUser->Encryption($TurntableUser);

          return json(['message'=>'验证成功','data'=>$token]);
        }
        return json(['message'=>'姓名/钉钉工号错误，请重新输入！'],203); 
    }

    /**
     * 用户信息
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function UserInfo(Request $request)
    {
        $TurntableUser = new MTurntableUser;
      
        //解析token 获取用户信息
        $UserInfo = $TurntableUser->Decrypt($request->get('bn_token'));

        //判断限制次数
        if ($UserInfo->type==1) {
            $limitCount = 2;
        }else{
            $limitCount = 1;
        }

       $count = MTurntableRecord::whereTime('created_at', 'm')->where('uid',$UserInfo->id)->count();
        if ($TurntableUser) {
          return json(['message'=>'验证成功','data'=>['TurntableUser'=>$UserInfo,'lottery_ticket'=>$limitCount-$count]]);
        }
    }
 
    /**
     * 抽奖记录
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Record(Request $request)
    {
       $TurntableUser = new MTurntableUser;
        //解析token 获取用户信息
       $UserInfo = $TurntableUser->Decrypt($request->get('bn_token'));
       $TurntableRecord = MTurntableRecord::where('uid',$UserInfo->id)->order('id desc')->paginate($request->get('limit'));
       return json($TurntableRecord); 
    }

}
 