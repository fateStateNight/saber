<?php

namespace app\admin\controller\integral;

use app\admin\model\IntegralDouorder;
use app\admin\model\SystemDouyinAccount;
use app\common\controller\AdminController;
use EasyAdmin\tool\CommonTool;
//use EasyAdmin\annotation\ControllerAnnotation;
//use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Log;

/**
 * Class Douorder
 * @package app\admin\controller\integral
 */
class Douorder extends AdminController
{

    use \app\admin\traits\Curd;


    protected $douyinModel;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\admin\model\IntegralDouorder();
        $this->douyinModel = new \app\admin\model\SystemDouyinAccount();
    }

    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFieds')) {
                return $this->selectList();
            }
            $limit = 40;
            $params = $this->request->post();
            //获取请求账号信息
            $accountInfo = $this->douyinModel->getDouyinAccountInfo($params['uId']);
            $startTime = date("Y-m-d", strtotime($params['startDate']))." 00:00:00";
            $endTime = date("Y-m-d", strtotime($params['endDate']))." 23:59:59";
            //$startTime = date('Y-m-d H:i:s', strtotime('-1 week'));
            if(time() > strtotime($endTime)){
                $endTime = date('Y-m-d H:i:s');
            }
            if(time()-strtotime($startTime) > 3600*24*30){
                $startTime = date('Y-m-d H:i:s', strtotime('-1 month'));
            }
            $count = $this->model->where('pid', '=', $accountInfo['publishID'])
                ->whereBetweenTime('pay_success_time', $startTime, $endTime)
                ->where('order_status', '<>', 'REFUND')
                ->count();
            $orderInfo = $this->model->where('pid', '=', $accountInfo['publishID'])
                ->whereBetweenTime('pay_success_time', $startTime, $endTime)
                ->page($params['page'], $limit)
                ->order(['pay_success_time'=>'desc','id'=>'desc'])
                ->select()->toArray();
            if($orderInfo){
                foreach($orderInfo as $key=>$item){
                    switch($item['order_status']){
                        case 'PAY_SUCC':
                            $orderInfo[$key]['order_status'] = '支付完成';
                            break;
                        case 'REFUND':
                            $orderInfo[$key]['order_status'] = '退款';
                            break;
                        case 'SETTLE':
                            $orderInfo[$key]['order_status'] = '结算';
                            break;
                        case 'CONFIRM':
                            $orderInfo[$key]['order_status'] = '确认收货';
                            break;
                        default:break;
                    }
                }
            }
            $showData = [
                'count' => $count,
                'groupTitle' => $accountInfo['title'],
            ];
            $data = [
                'code'  => 0,
                'msg'   => '',
                'showData' => $showData,
                'pageNum' => ceil($count/$limit),
                'count' => $count,
                'data'  => $orderInfo,
            ];
            return json($data);
        }
        return $this->fetch();
    }

    public function reward()
    {
        if ($this->request->isAjax()) {
            if (input('selectFieds')) {
                return $this->selectList();
            }
            $limit = 40;
            $params = $this->request->post();
            if($params['startDate'] == null || $params['endDate'] == null){
                $this->error("未设置日期时间");
            }
            //获取请求账号信息
            $accountInfo = $this->douyinModel->getDouyinAccountInfo($params['uId']);
            $startDate = $params['startDate'];
            $endDate = $params['endDate'];
            if(time() > strtotime($endDate)){
                $endDate = date('Ymd');
            }
            if(time()-strtotime($startDate) > 3600*24*30){
                $startDate = date('Ymd', strtotime('-1 month'));
            }
            $rewardData = $this->model->getRewardOrder($accountInfo['accessToken'], $accountInfo['publishID'], $startDate, $endDate, $params['page'],$limit);

            if(array_key_exists('data', $rewardData)){
                $showData = [
                    'count' => $rewardData['data']['total'],
                    'groupTitle' => $accountInfo['title'],
                ];
                $data = [
                    'code'  => 0,
                    'msg'   => '',
                    'showData' => $showData,
                    'pageNum' => ceil($rewardData['data']['total']/$limit),
                    'count' => $rewardData['data']['total'],
                    'data'  => $rewardData['data']['reward_orders'],
                ];
            }else{
                $showData = [
                    'count' => 0,
                    'groupTitle' => $accountInfo['title'],
                ];
                $data = [
                    'code'  => 0,
                    'msg'   => $rewardData['sub_msg'],
                    'showData' => $showData,
                    'pageNum' => 0,
                    'count' => 0,
                    'data'  => [],
                ];
            }
            return json($data);
        }
        return $this->fetch();
    }

}