<?php

namespace app\admin\controller\business;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="关联商家联系")
 */
class Share extends AdminController
{

    use \app\admin\traits\Curd;

    protected $relationSerach = true;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\BusinessShare();

    }

}