<?php

namespace app\admin\controller\mall;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="首单商品")
 */
class ShoudanGoods extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\MallShoudanGoods();
        
        $this->assign('getItemStatusList', $this->model->getItemStatusList());

        $this->assign('getModeList', $this->model->getModeList());

        $this->assign('getStatusList', $this->model->getStatusList());

    }

    
}