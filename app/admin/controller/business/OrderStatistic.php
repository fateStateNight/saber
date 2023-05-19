<?php

namespace app\admin\controller\business;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Cache;

/**
 * @ControllerAnnotation(title="订单统计")
 */
class OrderStatistic extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\BusinessOrderStatistic();

        $taoAccountModel = new \app\admin\model\MallTaolijinGoods();

        $this->assign('getSystemTaobaoAccountList', $taoAccountModel->getSystemTaobaoAccountList());

    }

    public function redisTest()
    {
        Cache::store('redis')->delete('test');die;
        Cache::store('redis')->set('test','11111');
        $result = Cache::store('redis')->get('test');
        return $result;
    }

    /**
     * @NodeAnotation(title="统计首页")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFieds')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->where($where)
                ->count();
            $list = $this->model
                ->where($where)
                ->page($page, $limit)
                ->order($this->sort)
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

    //返回当前的毫秒时间戳
    public function msectime()
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }

    
}




