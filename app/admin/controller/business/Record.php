<?php

namespace app\admin\controller\business;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="商家记录")
 */
class Record extends AdminController
{

    use \app\admin\traits\Curd;

    protected $relationSerach = true;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\BusinessRecord();

    }


    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        $store_id = input('store_id');
        $list = $this->model
            ->where('store_id', '=', $store_id)
            ->order(['create_time'=>'desc'])
            ->select();
        $recordData = [];
        if(!$list->isEmpty()){
            $recordData = $list->toArray();
        }
        $this->assign('recordData', $recordData);
        return $this->fetch();
    }

}