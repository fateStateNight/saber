<?php

namespace app\admin\controller\mall;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="积分商品")
 */
class IntegralGoods extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\MallIntegralGoods();
        
        $this->assign('getGoodsTypeList', $this->model->getGoodsTypeList());

    }

    
}