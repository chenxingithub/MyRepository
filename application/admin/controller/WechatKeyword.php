<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\WechatKeyword as MWechatKeyword;
use app\admin\model\WechatCoverBuilding as MWechatCoverBuilding;
use think\Db;
use think\Request;

class WechatKeyword extends ApiCommon
{

    /**
     * 关键字查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return JSON
     */
    public function GetKeyword(Request $request)
    {   
        $query = new MWechatKeyword();
        $query->where('game_id','=',$request->get('game_id'));
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('keyword', 'LIKE', '%' . $keyword . '%');
        }
        $Keywords = $query->order('id desc')->paginate($request->get('limit'));
        return json($Keywords);
    }

    /**
     * 回复查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return JSON
     */
    public function GetReply($id,Request $request)
    {   
        $WechatReplys = MWechatKeyword::get($id)->replys;
        return json($WechatReplys);
    }

    /**
     * 微信关键字回复修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function KeywordReplyPut($id,Request $request)
    {
        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->ChangeStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->changeSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->changeContent($request,$id);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
    }

    /**
     * 微信关键字回复修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function changeContent($request,$id)
    {   
        $WechatKeyword = MWechatKeyword::get($id);
        // 启动事务
        Db::startTrans();
        /*try{*/
            //修改关键字
            /*$WechatKeyword->is_vague = $request->post('is_vague');*/
            $WechatKeyword->type = $request->post('type');
            $WechatKeyword->keyword = $request->post('keyword');
            $WechatKeyword->is_context = $request->post('is_context');
            $WechatKeyword->front_problem = $request->post('front_problem');
            $WechatKeyword->front_reply = $request->post('front_reply');
            $WechatKeyword->is_vague = $request->post('is_vague');
            $WechatKeyword->is_cover_building = $request->post('is_cover_building');
            $WechatKeyword->floor = $request->post('floor/a')?implode("|", $request->post('floor/a')):'';
            $res1 = $WechatKeyword->save();
            //修改回复
            
            $res2 = $WechatKeyword->replys()->delete();
            if ($WechatKeyword->type == 1) {
                $res3 = $WechatKeyword->replys()->saveAll([[
                                                            'keyword_id' => $id,
                                                            'text_content' =>$request->post('text_content')
                                                          ]]);
            }elseif ($WechatKeyword->type == 3) {
                $res3 = $WechatKeyword->replys()->saveAll([[
                                                            'keyword_id' => $id,
                                                            'cdk_type_id' =>$request->post('cdk_type_id')
                                                          ]]);
            }elseif ($request->post('type') == 4 ) {

                $replys = array();
                foreach ($request->post('sub_keyword/a') as $sub_keywordkey => $sub_keywordvalue) {
                   $replys[] = [
                      'keyword_id' => $WechatKeyword->id,
                      'sub_keyword' => $sub_keywordvalue,
                   ];
                }
                $res3 = $WechatKeyword->replys()->saveAll($replys);
            }else{
                $res3 = $WechatKeyword->replys()->saveAll($request->post('reply/a'));
            }


            //判断是否需要追加盖楼词库
            if ($request->post('is_cover_building') == 1) {

                $MWechatCoverBuilding = MWechatCoverBuilding::get($WechatKeyword->cover_building_id);
                $MWechatCoverBuilding->keyword = $request->post('keyword');
                $res4 =   $MWechatCoverBuilding->save();
            }else{
                $res4 = true;
            }


            //判断是否操作成功
            if ($res1!==false&&$res2&&$res3) {
                // 提交事务
                Db::commit();
                return json(['message'=>'更新成功！']);  
            }else{
                Db::rollback();
                return json(['message'=>'更新失败，请联系IT部的小学生！'],203);
            }
/*        } catch (\Exception $e) {
            dump($e);
            // 回滚事务
            Db::rollback();
        }*/
    }
    /**
     * 微信关键字回复删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function KeywordReplyDelete($id)
    {
        // 启动事务
        Db::startTrans();
        try{

            $WechatKeyword = MWechatKeyword::get($id);

            $res = $WechatKeyword->replys()->delete();
            $res2 = $WechatKeyword->delete();

            if ($WechatKeyword->cover_building_id) {
               $res3 = MWechatCoverBuilding::get($WechatKeyword->cover_building_id)->delete();
            }else{
               $res3 = true;
            }
            if ($res&&$res&&$res3) {
            // 提交事务
              Db::commit();
              return json(['message'=>'删除成功！']);
            }
            Db::rollback();
            return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
    }

    /**
     * 微信关键字回复添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function KeywordReplyPost(Request $request)
    {
        // 启动事务
        Db::startTrans();
        // try{
            $data = [
                /*'is_vague'=>$request->post('is_vague'),*/
                'type'=>$request->post('type'),
                'keyword'=>$request->post('keyword'),
                'game_id'=>$request->post('game_id'),
                'is_context'=>$request->post('is_context'),
                'is_cover_building'=>$request->post('is_cover_building'),
                'floor'=>$request->post('floor/a')?implode("|", $request->post('floor/a')):'',
                'is_vague'=>$request->post('is_vague'),
                'front_problem'=>$request->post('front_problem'),
                'front_reply'=>$request->post('front_reply'),
            ];
            //判断是否需要追加盖楼词库
            if ($request->post('is_cover_building') == 1) {
                $CoverBuildingdata = [
                    'keyword'=>$request->post('keyword'),
                    'game_id'=>$request->post('game_id')
                ];

                $res2 =  MWechatCoverBuilding::create($CoverBuildingdata);
                $data['cover_building_id'] = $res2['id'];
            }else{
                $res2 = true;
            }

            //插入关键字
            $WechatKeyword = MWechatKeyword::create($data);

            //插入回复
            if ($request->post('type') == 1 ) {
                $res = $WechatKeyword->replys()->saveAll([[
                                                            'keyword_id' => $WechatKeyword->id,
                                                            'text_content' =>$request->post('text_content')
                                                          ]]);
            }elseif ($request->post('type') == 3 ) {
                $res = $WechatKeyword->replys()->saveAll([[
                                                            'keyword_id' => $WechatKeyword->id,
                                                            'cdk_type_id' =>$request->post('cdk_type_id')
                                                          ]]);
            }elseif ($request->post('type') == 4 ) {

                $replys = array();
                foreach ($request->post('sub_keyword/a') as $sub_keywordkey => $sub_keywordvalue) {
                   $replys[] = [
                      'keyword_id' => $WechatKeyword->id,
                      'sub_keyword' => $sub_keywordvalue,
                   ];
                }
                $res = $WechatKeyword->replys()->saveAll($replys);
            }else{
                $res = $WechatKeyword->replys()->saveAll($request->post('reply/a'));
            }
            //判断操作是否成功
            if ($res&&$res2) {
              // 提交事务
              Db::commit();
              return json(['message'=>'添加成功！']);
            }
            Db::rollback();
            return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
        // } catch (\Exception $e) {
        //     dump($e);
        //     // 回滚事务
        //     Db::rollback();
        // }
    }

}
 