<?php

// +----------------------------------------------------------------------
// | App Integral Exchange
// +----------------------------------------------------------------------
// | 与微信公众平台对接
// +----------------------------------------------------------------------
// | 开源协议  https://mit-license.org 
// +----------------------------------------------------------------------
// | 希望顺利吧
// +----------------------------------------------------------------------

namespace app\admin\controller\integral;


use app\admin\model\IntegralCashGift;
use app\common\controller\AdminController;
use think\App;
use think\facade\Log;

/**
 * Class CashGift
 * @package app\admin\controller\integral
 */
class CashGift extends AdminController
{

    //use \app\admin\traits\Curd;

    protected $sort = [
        'sort' => 'desc',
        'id'   => 'desc',
    ];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new IntegralCashGift();
    }

    public function index()
    {
        //$shortLink = input('shortLink')?input('shortLink'):'/abcdefghijklmn/';
        //$this->assign('shortLink',$shortLink);
        return $this->fetch();
    }

}