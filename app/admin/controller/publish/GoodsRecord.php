<?php

namespace app\admin\controller\publish;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="推广商品管理")
 */
class GoodsRecord extends AdminController
{

    use \app\admin\traits\Curd;

    protected $relationSerach = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\admin\model\MallGoods();

        $this->assign('adminInfo', session('admin'));

        $this->assign('getStatusList', $this->model->getPublishStatus());
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

            $count = $this->model
                ->withJoin(['cate','creater','admin'], 'LEFT')
                ->where(['admin.id'=>session('admin')['id']])
                ->where($where)
                ->count();
            $list = $this->model
                ->withJoin(['cate','creater','admin'], 'LEFT')
                ->where(['admin.id'=>session('admin')['id']])
                ->where($where)
                ->page($page, $limit)
                ->order($this->sort)
                ->select();

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
                $post['creater_id'] = session('admin')['id'];
                $post['status'] = 0;
                $save = $this->model->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="推广操作")
     */
    public function publish($id,$status)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                //$post['total_stock'] = $row->total_stock + $post['stock'];
                //$post['stock'] = $row->stock + $post['stock'];
                $post['status'] = $status;
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('推广失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

}