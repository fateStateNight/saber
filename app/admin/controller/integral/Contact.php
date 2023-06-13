<?php

// +----------------------------------------------------------------------
// | App Integral Exchange
// +----------------------------------------------------------------------
// | 独立页面
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | 不限制登录状态
// +----------------------------------------------------------------------

namespace app\admin\controller\integral;


use app\admin\model\IntegralContact;
use app\common\controller\AdminController;
//use EasyAdmin\annotation\ControllerAnnotation;
//use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Log;

/**
 * Class Draw
 * @package app\admin\controller\integral
 */
class Contact extends AdminController
{

    //use \app\admin\traits\Curd;

    protected $sort = [
        'sort' => 'desc',
        'id'   => 'desc',
    ];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new IntegralContact();
    }

    public function index()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $data = file_get_contents('php://input');
            try {
                var_dump($post);
                var_dump($data);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $this->success('保存成功');
        }
        return $this->fetch();
    }

    public function searchContact()
    {
        $data = file_get_contents('php://input');
        var_dump($data);die;
    }

}