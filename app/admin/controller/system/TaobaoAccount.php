<?php

namespace app\admin\controller\system;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="淘宝联盟账号")
 */
class TaobaoAccount extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\SystemTaobaoAccount();
        
        $this->assign('getStatusList', $this->model->getStatusList());

    }

    
}