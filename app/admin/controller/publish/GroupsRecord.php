<?php

namespace app\admin\controller\publish;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="社群记录")
 */
class GroupsRecord extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\PublishGroupsRecord();
        
        $this->assign('getPublishWeixinGroupsList', $this->model->getPublishWeixinGroupsList());

        $this->assign('getCurrentDayGoodsList', $this->model->getCurrentDayGoodsList(0));
//var_dump($this->model->getCurrentDayGoodsList(0));die;
    }

    
    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFieds')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            if(session('admin')['auth_ids'] == 7 || session('admin')['auth_ids'] == 1){
                $count = $this->model
                    ->withJoin('publishWeixinGroups', 'LEFT')
                    ->where($where)
                    ->count();
                $list = $this->model
                    ->withJoin(['publishWeixinGroups','mallIntegralGoods'], 'LEFT')
                    ->where($where)
                    ->page($page, $limit)
                    ->order($this->sort)
                    ->select();
            }else{
                $count = $this->model
                    ->withJoin('publishWeixinGroups', 'LEFT')
                    ->where($where)
                    ->where('')
                    ->count();
                $list = $this->model
                    ->withJoin(['publishWeixinGroups','mallIntegralGoods'], 'LEFT')
                    ->where($where)
                    ->page($page, $limit)
                    ->order($this->sort)
                    ->select();
            }

            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="添加")
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $this->model->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        $row = $this->model->find($id);
        $row->isEmpty() && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }
}