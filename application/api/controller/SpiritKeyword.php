<?php
// +----------------------------------------------------------------------
// | Description: 精灵板块
// +----------------------------------------------------------------------
// | Author: 冰鸟 <772369024@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\SpiritKeyword as MSpiritKeyword;
use app\api\model\SpiritRetrieval as MSpiritRetrieval;
use think\Request;
use think\Loader;
use think\Db;

class SpiritKeyword extends Common
{


    /**
     * 查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function Get(Request $request)
    {   
        $data = [
            'keyword'=>$request->get('keyword'),
            'type'=>$request->get('type'),
            'game_id'=>$request->get('game_id'),
            'from'=>$request->get('from')
        ];
        //参数过滤
        $validate = Loader::validate('SpiritKeyword');
        if(!$validate->check($data)){
           return json(['message'=>$validate->getError()],422);
        }
        switch ($data['type']) {
                    case 1://匹配规则名
                          $SpiritKeyword = MSpiritKeyword::where("rule",$data['keyword'])->where('game_id',$data['game_id'])->order('id desc')->find();
                          break;
                    case 2://匹配关键字
                          //先精确匹配
                          $SpiritKeyword = MSpiritKeyword::where("keyword",$data['keyword'])->where('game_id',$data['game_id'])->where('status',0)->where('is_vague',0)->order('id desc')->find();
                          if (!$SpiritKeyword) {  // 不存在 是用模糊匹配
                              $SpiritKeyword = MSpiritKeyword::where('locate(keyword,:keyword)',['keyword'=>$data['keyword']])->where('game_id',$data['game_id'])->where('status',0)->where('is_vague',1)->order('id desc')->find();
                          }
                          break;
                    default:
                        break;
        }
        // 启动事务
        Db::startTrans();
        try{
            $RetrievalData = [
                'service_area'=>$request->get('service_id'),
                'from'=>$request->get('from'),
                'to'=>'admin',
                'role_name'=>$request->get('role_name'),
                'vip'=>$request->get('vip'),
                'role_rank'=>$request->get('role_rank'),
                'retrieval_message'=>$request->get('keyword'),
                'game_id'=>$request->get('game_id')
            ];
            $res = MSpiritRetrieval::create($RetrievalData);

            $RetrievalData2 = [
              'from'=>'admin',
              'to'=>$request->get('from'),
              'retrieval_message'=>$SpiritKeyword->content,
              'game_id'=>$request->get('game_id'),
              'role_name'=>'管理员'
            ];

            $res2 = MSpiritRetrieval::create($RetrievalData2);
            if ($res&&$res2) {
                 // 提交事务
              Db::commit();
              return json(['data'=>$SpiritKeyword]);
            }else{
                Db::rollback();
                return json(['message'=>'网络错误！'],203);
            }
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            dump($e);
            // 回滚事务
            Db::rollback();
        }
    }


}
 