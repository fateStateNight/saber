<?php

namespace app\admin\controller\business;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="招商活动")
 */
class Scene extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\BusinessScene();
        
        $this->assign('getStatusList', $this->model->getStatusList());


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
                ->withJoin('taobaoAccount', 'LEFT')
                ->where(['taobaoAccount.id' => session('admin')['taobao_accountId']])
                ->where($where)
                ->count();
            $list = $this->model
                ->withJoin('taobaoAccount', 'LEFT')
                ->where(['taobaoAccount.id' => session('admin')['taobao_accountId']])
                ->where($where)
                ->page($page, $limit)
                ->order('startTime','desc')
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

    
}