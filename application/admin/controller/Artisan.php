<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Games;
use app\admin\model\Artisan AS MArtisan;
use app\admin\model\FocusImg;
use think\Request;

class Artisan extends ApiCommon
{
    /*    const PAGE_SIZE = 15;

    public $pageTitle = '我在大清当皇帝';*/

    /**
     * 文章列表查询
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ArtisanGet(Request $request)
    {   
        $query = new MArtisan();

        $query->join('oa_admin_artisan_type', 'oa_admin_artisan_type.id = oa_admin_artisan_copy.artisan_type_id');
        $query->where('oa_admin_artisan_type.game_id','=',$request->get('game_id'));
        $keyword = $request->get('keyword');
        if ($keyword) {
            $query->where('title', 'LIKE', '%' . $keyword . '%');
        }
        $status = $request->get('status');
        if (is_numeric($status)) {
            $query->where('oa_admin_artisan_copy.status','=',$status);
        }
        $ArtisanTypeId = $request->get('artisan_type_id');
        if (is_numeric($ArtisanTypeId)) {
            $query->where('oa_admin_artisan_type.id','=',$ArtisanTypeId);
        }
        $terminal = $request->get('terminal');
        if ($terminal) {
            $query->where('terminal','=',$terminal);
        }
        $Artisan = $query->order('id desc')->field(['oa_admin_artisan_copy.*', 'oa_admin_artisan_type.name'])->paginate($request->get('limit'));
        return json($Artisan);
    }

    /**
     * 文章列表修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ArtisanPut($id,Request $request)
    {

        switch ($request->post('action')) {
            case 'changeStatus': //用户锁定
                return $this->ArtisanChangeStatus($request,$id);
            case 'changeSort': //另外的修改类型
                return $this->ArtisanChangeSort($request,$id);
            case 'changeContent': //另外的修改类型
                return $this->ArtisanChangeContent($request,$id);
            default://用户资料修改
                return $this->Change($request,$id);
                break;
        }
    }


    /**
     * 文章列表状态修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function ArtisanChangeStatus($request,$id)
    {   
        $Artisan = MArtisan::get($id);
        $Artisan->status = $request->post('status');
        $res = $Artisan->save();
        if ($res) {
          return json(['message'=>'使用/禁用，成功！']);
        }
        return json(['message'=>'使用/禁用, 失败！'],203);
    }


    /**
     * 文章列表添加
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ArtisanPost(Request $request)
    {
        //过滤数据
        $SensitiveFilter = $this->SensitiveFilter([$request->post('content'),$request->post('title'),$request->post('subtitled'),$request->post('sketch')]);
        if (!$SensitiveFilter) {
          return json(['message'=>'内容存在敏感词！'],203);
        }
        $data = [
            'title'=>$request->post('title'),
            'subtitled'=>$request->post('subtitled'),
            'sketch'=>$request->post('sketch'),
            'jump_url'=>$request->post('jump_url'),
            'traffic_volume'=>$request->post('traffic_volume'),
            'bg_img'=>$request->post('bg_img'),
            'icon'=>$request->post('icon'),
            'content'=>$request->post('content'),
            'artisan_type_id'=>$request->post('artisan_type_id'),
            'updated_at'=>$request->post('created_at'),
            'terminal'=>$request->post('terminal'),
            'label'=>$request->post('label'),
            'enclosure_url'=>$request->post('enclosure_url')
        ];
        $Artisan = MArtisan::create($data);

        if ($Artisan) {
          return json(['message'=>'添加成功！']);
        }
        return json(['message'=>'添加失败，请联系IT部的小学生！'],203);
    }

    /**
     * 文章列表置顶修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function ArtisanChangeSort($request,$id)
    {   
        $Artisan = MArtisan::get($id);
        $Artisan->sort = $request->post('sort');
        $res = $Artisan->save();
        if ($res) {
          return json(['message'=>'置顶/取消置顶，成功！']);
        }
        return json(['message'=>'置顶/取消置顶, 失败！'],203);
    }

    /**
     * 文章列表删除
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function ArtisanDelete($id)
    {
        $res = MArtisan::get($id)->delete();
        if ($res) {
          return json(['message'=>'删除成功！']);
        }
        return json(['message'=>'删除失败，请联系IT部的小学生！'],203);
    }

    /**
     * 文章列表内容修改
     *
     * @author Tao
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    private function ArtisanChangeContent($request,$id)
    {   
        $Artisan = MArtisan::get($id);
        $Artisan->title = $request->post('title');
        $Artisan->subtitled = $request->post('subtitled');
        $Artisan->sketch = $request->post('sketch');
        $Artisan->jump_url = $request->post('jump_url');
        $Artisan->traffic_volume = $request->post('traffic_volume');
        $Artisan->bg_img = $request->post('bg_img');
        $Artisan->icon = $request->post('icon');
        $Artisan->content = $request->post('content');
        $Artisan->artisan_type_id = $request->post('artisan_type_id');
        $Artisan->updated_at = $request->post('created_at');
        $Artisan->terminal = $request->post('terminal');
        $Artisan->label = $request->post('label');
        $Artisan->enclosure_url = $request->post('enclosure_url');
        $res = $Artisan->save();
        if ($res) {
          return json(['message'=>'修改成功！']);
        }
        return json(['message'=>'修改失败，请联系IT部的小学生！'],203);
    }
}
 