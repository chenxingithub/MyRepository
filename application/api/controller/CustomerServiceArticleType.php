<?php
// +----------------------------------------------------------------------
// | Description: 精灵反馈
// +----------------------------------------------------------------------
// | Author: 冰鸟 <772369024@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;
use app\common\controller\Common;
use app\api\model\CustomerServiceArticleType as MCustomerServiceArticleType;
use think\Request;
use think\Loader;

class CustomerServiceArticleType extends Common
{
    /**
     * 客服文章分类列表
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function Get(Request $request)
    {   
       $CustomerServiceArticleType = MCustomerServiceArticleType::select();
        if ($CustomerServiceArticleType) {
          return json(['message'=>'查询成功','data'=>$CustomerServiceArticleType]);
        }
        return json(['message'=>'系统错误，请重新尝试。'],203);
    }


    /**
     * 客服文章分类下的一级文章
     *
     * @author Tao
     *
     * @param Request $request
     * @return json
     */
    public function SubgradeGet($id,Request $request)
    {   
        $CustomerServiceArticleType = MCustomerServiceArticleType::get($id);
        $CustomerServiceArticle = $CustomerServiceArticleType->Articles()->where('f_id',0)->select();
        if ($CustomerServiceArticleType) {
          return json(['message'=>'查询成功','data'=>$CustomerServiceArticle]);
        }
        return json(['message'=>'系统错误，请重新尝试。'],203);
    }
}
 