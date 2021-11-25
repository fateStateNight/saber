<?php


namespace app\admin\controller\mall;


use app\admin\model\MallGoods;
use app\admin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\admin\controller\mall
 * @ControllerAnnotation(title="商城商品管理")
 */
class Goods extends AdminController
{

    use Curd;

    protected $relationSerach = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new MallGoods();

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
            if(session('admin')['auth_ids'] == 9){
                //判断为招商角色
                $count = $this->model
                    ->withJoin(['cate','creater','admin'], 'LEFT')
                    ->where(['creater.id'=>session('admin')['id']])
                    ->where($where)
                    ->count();
                $list = $this->model
                    ->withJoin(['cate','creater','admin'], 'LEFT')
                    ->where(['creater.id'=>session('admin')['id']])
                    ->where($where)
                    ->page($page, $limit)
                    ->order($this->sort)
                    ->select();
            }else{
                //elseif(session('admin')['auth_ids'] == 7 || session('admin')['auth_ids'] == 10)
                //判断为超级管理员或者推广管理员
                $count = $this->model
                    ->withJoin(['cate','creater','admin'], 'LEFT')
                    ->where($where)
                    ->count();
                $list = $this->model
                    ->withJoin(['cate','creater','admin'], 'LEFT')
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
    public function publish($id)
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
                $post['admin_id'] = session('admin')['id'];
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('推广失败');
            }
            $save ? $this->success('推广成功') : $this->error('推广失败');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

}