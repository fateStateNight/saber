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


use app\admin\model\IntegralDraw;
use app\common\controller\AdminController;
//use EasyAdmin\annotation\ControllerAnnotation;
//use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Log;

/**
 * Class Draw
 * @package app\admin\controller\integral
 */
class Draw extends AdminController
{

    //use \app\admin\traits\Curd;

    protected $sort = [
        'sort' => 'desc',
        'id'   => 'desc',
    ];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new IntegralDraw();
    }

    public function index()
    {
        $shortLink = input('shortLink')?input('shortLink'):'/abcdefghijklmn/';
        $this->assign('shortLink',$shortLink);
        return $this->fetch();
    }

    public function searchContact()
    {
        $data = file_get_contents('php://input');
        var_dump($data);die;
    }

}