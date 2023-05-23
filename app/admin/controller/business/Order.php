<?php
namespace app\admin\controller\business;

use app\admin\model\BusinessGoods;
use app\admin\model\SystemScriptTask;
use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use EasyAdmin\tool\CommonTool;
use think\App;
use think\facade\Db;


/**
 * @ControllerAnnotation(title="服务订单记录")
 */
class Order extends AdminController
{

    use \app\admin\traits\Curd;

    protected $taskModelObj;

    protected $adminModelObj;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\BusinessOrder();

        $this->adminModelObj = new \app\admin\model\SystemAdmin();
        
        $this->assign('getTkStatusList', $this->model->getTkStatusList());

        $this->sort = [
            'tk_create_time'   => 'desc',
        ];

    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFieds')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $businessGoodsModel = new \app\admin\model\BusinessGoods();
            if($where){
                $isTimeLimit = false;
                $isShopTitle = false;
                $goodsTitle = false;
                $goodsId = false;
                foreach($where as $key => $option){
                    if(in_array('tk_create_time', $option) || in_array('tk_earning_time', $option)){
                        $isTimeLimit = true;
                        if($option[1] == '>='){
                            $startTime = $option[2];
                        }elseif($option[1] == '<='){
                            $stopTime = $option[2];
                        }
                    }elseif(in_array('seller_shop_title', $option)){
                        $isShopTitle = true;
                    }elseif(in_array('item_title', $option)){
                        $goodsTitle = $option[2];
                    }elseif(in_array('item_id ', $option)){
                        $goodsId = $option[2];
                    }
                }
                if(!$isTimeLimit){
                    $this->error("请增加时间搜索条件");
                }
                if($startTime >= $stopTime){
                    $this->error("搜索截止时间必须大于搜索开始时间");
                }
                //无管理员权限不能搜索店铺，且必须是自己认领过的商品订单
                if(session('admin')['auth_ids'] != 7 && session('admin')['auth_ids'] != 1){
                    if($isShopTitle){
                        $this->error("请使用单品搜索查询，暂无权限使用店铺查询");
                    }
                    if($goodsTitle){
                        $goodsInfo = $businessGoodsModel->where('auditorId',session('admin')['id'])
                            ->where('title','like',$goodsTitle)
                            ->where('startTime', '<=', $startTime)
                            ->where('endTime', '>=', $stopTime)
                            ->limit(1)
                            ->field('auditorId')//->fetchSql()
                            ->select()
                            ->toArray();
                        //var_export($goodsInfo);die;
                        if($goodsInfo == null){
                            $this->error("请搜索自己认领过的商品");
                        }
                    }
                    //增加新的搜索组合，仅搜索时间查询订单
                    if(!$goodsTitle && !$goodsId && !$isShopTitle){
                        /*$goodsInfo = $businessGoodsModel->where('auditorId',session('admin')['id'])
                            ->whereNotExists(function($query) use ($startTime, $stopTime) {
                                $query->whereOr('startTime', '>', $stopTime)
                                    ->whereOr('endTime', '<', $startTime);
                            })
                            ->column(["concat('%',mktItemId)"=>"mktId"]);*/
                        $goodsInfo = Db::query("select concat('%',mktItemId) as mktId from think_business_goods where auditorId=:auditorId and not(startTime > :stopTime or endTime < :startTime)",
                            ['auditorId' => session('admin')['id'],'stopTime'=>$stopTime,'startTime'=>$startTime]);
                        $goodsInfo = array_column($goodsInfo,'mktId');
                        //var_export($goodsInfo);die;
                        if($goodsInfo == null){
                            $this->error("请搜索自己认领过的商品");
                        }
                        $where[] = [
                            0 => 'item_id',
                            1 => 'like',
                            2 => $goodsInfo,
                            3 => 'OR',
                        ];
                    }
                }else{
                    //暂不处理
                }
            }else{
                $defaultStartTime = date('Y-m-d').' 00:00:00';
                $defaultStopTime = date('Y-m-d').' 23:59:59';
                $where = [[
                    0 => 'tk_create_time',
                    1 => '>=',
                    2 => $defaultStartTime,
                ],[
                    0 => 'tk_create_time',
                    1 => '<=',
                    2 => $defaultStopTime,
                ]];
            }
            $count = $this->model->where($where)->count();
            $list = $this->model->where($where)
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

            /*if(session('admin')['auth_ids'] == 7 || session('admin')['auth_ids'] == 1){
                $data = $this->makeSelectSql($page, $limit, $where);
            }else{
                $data = $this->makeSelectSql($page, $limit, $where, true);
            }
            return json($data);*/
        }
        return $this->fetch();
    }

    public function makeSelectSql($page, $limit, $where, $person = false)
    {
        //判断条件中是否存在审核人信息
        $personExist = false;
        $defaultStartTime = date('Y-m-d H:i:s', strtotime('-1 month'));
        $defaultStopTime = date('Y-m-d H:i:s');
        $itemIdOption = '';
        $eventIdOption = '';
        if($where != null){
            $newWhere = [];
            foreach($where as $key => $option){
                if(in_array('auditorNick', $option)){
                    $personExist = true;
                    $auditorNick = str_replace('%','',$option[2]);
                    $auditorid = $this->adminModelObj->where('nickname',$auditorNick)->value('id');
                }else{
                    if(in_array('tk_create_time', $option) || in_array('tk_earning_time', $option)){
                        if($option[1] == '>='){
                            $defaultStartTime = date('Y-m-d H:i:s', strtotime('-35 days', strtotime($option[2])));
                        }elseif($option[1] == '<='){
                            $defaultStopTime = $option[2];
                        }
                    }elseif(in_array('item_id', $option)){
                        $itemIdOption = str_replace('%','',$option[2]);
                    }elseif(in_array('event_id', $option)){
                        $eventIdOption = str_replace('%','',$option[2]);
                    }
                    $newWhere[] = $option;
                }
            }
            if($itemIdOption != null || $eventIdOption != null){
                $defaultStartTime = date('Y-m-d H:i:s', strtotime('-1 month'));
                $defaultStopTime = date('Y-m-d H:i:s');
            }
            $where = $newWhere;
        }
        $businessGoodsModel = new \app\admin\model\BusinessGoods();
        if($person){
            //普通权限查询逻辑
            $itemIdArr = [];
            $eventIdArr = [];
            if($itemIdOption){
                $goodsInfo = $businessGoodsModel->where('auditorId',session('admin')['id'])
                    ->where('itemId', $itemIdOption)
                    //->limit($limit)
                    ->field('itemId,eventId')
                    ->select()
                    ->toArray();
            }elseif($eventIdOption){
                $goodsInfo = $businessGoodsModel->where('auditorId',session('admin')['id'])
                    ->where('eventId', $eventIdOption)
                    //->limit($limit)
                    ->field('itemId,eventId')
                    ->select()
                    ->toArray();
            }else{
                $goodsInfo = $businessGoodsModel->where('auditorId',session('admin')['id'])
                    ->whereBetweenTime('startTime', $defaultStartTime, $defaultStopTime)
                    //->limit($limit)
                    ->field('itemId,eventId')
                    ->select()
                    ->toArray();
            }
            foreach($goodsInfo as $goodsArr){
                $itemIdArr[] = $goodsArr['itemId'];
                $eventIdArr[] = $goodsArr['eventId'];
            }
            $count = $this->model->where('item_id', 'in',$itemIdArr)
                ->where('event_id', 'in', $eventIdArr)
                ->where($where)
                ->count();
            $list = $this->model->where('item_id', 'in',$itemIdArr)
                ->where('event_id', 'in', $eventIdArr)
                ->where($where)
                ->page($page, $limit)
                ->order($this->sort)
                ->select();
            if($count > 0){
                foreach($list as &$orderInfo){
                    $orderInfo['auditorNick'] = session('admin')['nickname'];
                }
            }
        }else{
            //管理员查询逻辑
            if($personExist){
                $itemIdArr = [];
                $eventIdArr = [];
                if($itemIdOption){
                    $goodsInfo = $businessGoodsModel->where('auditorId',$auditorid)
                        ->where('itemId', $itemIdOption)
                        ->field('itemId,eventId')
                        ->select()
                        ->toArray();
                }elseif($eventIdOption){
                    $goodsInfo = $businessGoodsModel->where('auditorId',$auditorid)
                        ->where('eventId', $eventIdOption)
                        ->field('itemId,eventId')
                        ->select()
                        ->toArray();
                }else{
                    $goodsInfo = $businessGoodsModel->where('auditorId',$auditorid)
                        ->whereBetweenTime('startTime', $defaultStartTime, $defaultStopTime)
                        //->limit($limit)
                        ->field('itemId,eventId')
                        ->select()
                        ->toArray();
                }
                foreach($goodsInfo as $goodsArr){
                    $itemIdArr[] = $goodsArr['itemId'];
                    $eventIdArr[] = $goodsArr['eventId'];
                }
                //var_export($itemIdArr);die;
                $count = $this->model->where('item_id', 'in',$itemIdArr)
                    ->where('event_id', 'in', $eventIdArr)
                    ->where($where)
                    ->count();
                $list = $this->model->where('item_id', 'in',$itemIdArr)
                    ->where('event_id', 'in', $eventIdArr)
                    ->where($where)
                    ->page($page, $limit)
                    ->order($this->sort)
                    ->select();
                if($count > 0){
                    foreach($list as &$orderInfo){
                        $orderInfo['auditorNick'] = $auditorNick;
                    }
                }
            }else{
                $count = $this->model
                    ->where($where)
                    ->count();

                $list = $this->model
                    ->where($where)
                    ->page($page, $limit)
                    ->order($this->sort)
                    ->select();
            }
        }
        $data = [
            'code'  => 0,
            'msg'   => '',
            'count' => $count,
            'data'  => $list,
        ];
        return $data;
    }

    /**
     * @NodeAnotation(title="创建订单导出任务")
     */
    public function export()
    {
        if(!($this->taskModelObj instanceof SystemScriptTask)){
            $this->taskModelObj = new \app\admin\model\SystemScriptTask();
        }
        $businessGoodsModel = new \app\admin\model\BusinessGoods();
        //导出改为后台导出，创建导出任务
        list($page, $limit, $where) = $this->buildTableParames();
        if($where){
            $isTimeLimit = false;
            $isShopTitle = false;
            $goodsTitle = false;
            $goodsId = false;
            foreach($where as $key => $option){
                if(in_array('tk_create_time', $option) || in_array('tk_earning_time', $option)){
                    $isTimeLimit = true;
                    if($option[1] == '>='){
                        $startTime = $option[2];
                    }elseif($option[1] == '<='){
                        $stopTime = $option[2];
                    }
                }elseif(in_array('seller_shop_title', $option)){
                    $isShopTitle = true;
                }elseif(in_array('item_title', $option)){
                    $goodsTitle = $option[2];
                }elseif(in_array('item_id ', $option)){
                    $goodsId = $option[2];
                }
            }
            if(!$isTimeLimit){
                $this->error("请增加时间搜索条件");
            }
            //无管理员权限不能搜索店铺，且必须是自己认领过的商品订单
            if(session('admin')['auth_ids'] != 7 && session('admin')['auth_ids'] != 1){
                if($isShopTitle){
                    $this->error("请使用单品搜索查询，暂无权限使用店铺查询");
                }
                if($goodsTitle){
                    $goodsInfo = $businessGoodsModel->where('auditorId',session('admin')['id'])
                        ->where('title','like',$goodsTitle)
                        ->where('startTime', '<=', $startTime)
                        ->where('endTime', '>=', $stopTime)
                        ->limit(1)
                        ->field('auditorId')//->fetchSql()
                        ->select()
                        ->toArray();
                    if($goodsInfo == null){
                        $this->error("请搜索自己认领过的商品");
                    }
                }
                //增加新的搜索组合，仅搜索时间查询订单
                if(!$goodsTitle && !$goodsId && !$isShopTitle){
                    /*$goodsInfo = $businessGoodsModel->where('auditorId',session('admin')['id'])
                        ->where('startTime', '<=', $startTime)
                        ->where('endTime', '>=', $stopTime)
                        ->column(["concat('%',mktItemId)"=>"mktId"]);*/
                    $goodsInfo = Db::query("select concat('%',mktItemId) as mktId from think_business_goods where auditorId=:auditorId and not(startTime > :stopTime or endTime < :startTime)",
                        ['auditorId' => session('admin')['id'],'stopTime'=>$stopTime,'startTime'=>$startTime]);
                    $goodsInfo = array_column($goodsInfo,'mktId');
                    if($goodsInfo == null){
                        $this->error("请搜索自己认领过的商品");
                    }
                    $where[] = [
                        0 => 'item_id',
                        1 => 'like',
                        2 => $goodsInfo,
                        3 => 'OR',
                    ];
                }
            }
            $count = $this->model->where($where)->count();
        }else{
            $defaultStartTime = date('Y-m-d').' 00:00:00';
            $defaultStopTime = date('Y-m-d').' 23:59:59';
            $count = $this->model->where($where)
                ->whereBetweenTime('tk_create_time', $defaultStartTime, $defaultStopTime)->count();
        }

        if($count > 100000){
            $this->error("导出的数据不能超过100000条");
            return false;
        }
        $content = [
            'where' => $where,
            'auth_ids' => session('admin')['auth_ids'],
        ];
        $taskInfo = [
            'title' => date("Y-m-d H:i:s").'导出订单数据',
            'type' => 2,
            'task_status' => 0,
            'task_content' => json_encode($content),
            'creater_id' => session('admin')['id'],
            'create_time' => date('Y-m-d H:i:s'),
        ];
        try {
            $save = $this->taskModelObj->save($taskInfo);
        } catch (\Exception $e) {
            $this->error('创建失败');
        }
        $save?$this->success("导出任务已创建，请稍后在任务列表中下载！"):$this->error("导出任务创建失败，请稍后再试！");
        return true;
    }
    
}