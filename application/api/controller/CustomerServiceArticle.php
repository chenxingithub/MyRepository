<?php
// +----------------------------------------------------------------------
// | Description: 精灵反馈
// +----------------------------------------------------------------------
// | Author: 冰鸟 <772369024@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\CustomerServiceArticle as MCustomerServiceArticle;
use think\Request;
use think\Loader;

class CustomerServiceArticle extends Common
{

    /**
     * 客服文章查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function Get($id,Request $request)
    {   
        $CustomerServiceArticle = MCustomerServiceArticle::get($id);
        if ($CustomerServiceArticle->type==1) {
            $data =  MCustomerServiceArticle::where('f_id',$CustomerServiceArticle->id)->order('sort_id desc')->select();
        }else{
            $data =  $CustomerServiceArticle;
        }
        if ($data) {
          return json(['message'=>'查询成功','type'=>$CustomerServiceArticle->type,'data'=>$data]);
        }
        return json(['message'=>'系统错误，请重新尝试。'],203);
    }
}
 