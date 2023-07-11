<?php

namespace app\admin\controller\business;

include_once "/app/extend/xlsxwriter.class.php";
use app\admin\model\BusinessScene;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\tool\CommonTool;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
//use jianyan\excel\Excel;
use think\facade\Db;

/**
 * @ControllerAnnotation(title="活动商品")
 */
class Goods extends AdminController
{

    use \app\admin\traits\Curd;

    public $businessSceneModel;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\BusinessGoods();

        $this->businessSceneModel = new \app\admin\model\BusinessScene();
        
        $this->assign('getAuditStatusList', $this->model->getAuditStatusList());

        $this->assign('adminInfo', session('admin'));
    }


    public function buildTableParames($excludeFields = [])
    {
        $get = $this->request->get('', null, null);
        $page = isset($get['page']) && !empty($get['page']) ? $get['page'] : 1;
        $limit = isset($get['limit']) && !empty($get['limit']) ? $get['limit'] : 15;
        $filters = isset($get['filter']) && !empty($get['filter']) ? $get['filter'] : '{}';
        $ops = isset($get['op']) && !empty($get['op']) ? $get['op'] : '{}';
        // json转数组
        $filters = json_decode($filters, true);
        $ops = json_decode($ops, true);
        $where = [];
        $excludes = [];
        //var_export(session('admin')['id']);die;
        // 判断是否关联查询
        $tableName = CommonTool::humpToLine(lcfirst($this->model->getName()));

        foreach ($filters as $key => $val) {
            if (in_array($key, $excludeFields)) {
                $excludes[$key] = $val;
                continue;
            }
            $op = isset($ops[$key]) && !empty($ops[$key]) ? $ops[$key] : '%*%';
            if ($this->relationSerach && count(explode('.', $key)) == 1) {
                $key = "{$tableName}.{$key}";
            }
            //2023-02-20修改搜索商品ID功能，商品ID可搜索新旧版商品ID
            if($key == 'itemId' && strlen($val) >= 15){
                $str_position = strpos($val, '-');
                if($str_position !== false){
                    $key = 'mktItemId';
                    $val = substr($val, $str_position+1);
                }else{
                    $key = 'mktItemId';
                }
            }

            switch (strtolower($op)) {
                case '=':
                    $where[] = [$key, '=', $val];
                    break;
                case '%*%':
                    $where[] = [$key, 'LIKE', "%{$val}%"];
                    break;
                case '*%':
                    $where[] = [$key, 'LIKE', "{$val}%"];
                    break;
                case '%*':
                    $where[] = [$key, 'LIKE', "%{$val}"];
                    break;
                case 'range':
                    [$beginTime, $endTime] = explode(' - ', $val);
                    $where[] = [$key, '>=', strtotime($beginTime)];
                    $where[] = [$key, '<=', strtotime($endTime)];
                    break;
                case 'scope':
                    [$beginTime, $endTime] = explode(' - ', $val);
                    $where[] = [$key, '>=', $beginTime];
                    $where[] = [$key, '<=', $endTime];
                    break;
                default:
                    $where[] = [$key, $op, "%{$val}"];
            }
        }
        return [$page, $limit, $where, $excludes];
    }
    
    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        $eventId= $this->request->get('eventId');
        $this->assign('eventId',$eventId);
        if($eventId > 0){
            $showEventArr = $this->businessSceneModel->where('eventId', $eventId)->find()->toArray();
            $this->assign('showEventArr', $showEventArr);
        }
        if ($this->request->isAjax()) {
            if (input('selectFieds')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            if($eventId){
                //更新商品信息数据
                if($page <= 1){
                    $ret = $this->updateEventGoodsData($showEventArr);
                }
                if(session('admin')['auth_ids'] == 7 || session('admin')['auth_ids'] == 1){
                    //管理员和超级管理员能查看所有数据
                    $count = $this->model
                        ->withJoin('businessScene', 'LEFT')
                        ->where(['business_goods.eventId'=>$eventId])
                        ->where($where)
                        ->count();
                    $list = $this->model
                        ->withJoin('businessScene', 'LEFT')
                        ->where(['business_goods.eventId'=>$eventId])
                        ->where($where)
                        ->page($page, $limit)
                        ->order(['auditStatus'=>'asc','endTime'=>'desc','id'=>'desc'])
                        ->select();
                }else{
                    $where_1 = ['auditorId' => session('admin')['id']];
                    $count = $this->model
                        ->withJoin('businessScene', 'LEFT')
                        ->where(['business_goods.eventId'=>$eventId])
                        //->where(['auditorId'=>[session('admin')['id'],0]])
                        ->where(function($query) use ($where_1){
                            $where_2 = [
                                'auditorId' => 0,
                                'business_goods.auditStatus' => 1
                            ];
                            $query->whereOr(function($query1) use ($where_2){
                                $query1->where($where_2);
                            })
                            ->whereOr($where_1);
                        })
                        ->where($where)
                        ->count();
                    $list = $this->model
                        ->withJoin('businessScene', 'LEFT')
                        ->where(['business_goods.eventId'=>$eventId])
                        //->where(['auditorId'=>[session('admin')['id'],0]])
                        ->where(function($query) use ($where_1){
                            $where_2 = [
                                'auditorId' => 0,
                                'business_goods.auditStatus' => 1
                            ];
                            $query->whereOr(function($query1) use ($where_2){
                                $query1->where($where_2);
                            })
                                ->whereOr($where_1);
                        })
                        ->where($where)
                        ->page($page, $limit)
                        ->order(['auditStatus'=>'asc','endTime'=>'desc','id'=>'desc'])
                        ->select();
                }
            }else{
                if($where != null){
                    foreach($where as $rowKey=>$whereRow){
                        if($whereRow[0] == 'startTime'){
                            $where[$rowKey][0] = 'business_goods.startTime';
                        }elseif($whereRow[0] == 'endTime'){
                            $where[$rowKey][0] = 'business_goods.endTime';
                        }
                    }
                }
                if(session('admin')['auth_ids'] == 7 || session('admin')['auth_ids'] == 1){
                    if($where == null){
                        $dateTime = date('Y-m-d H:i:s', strtotime('-1 week'));
                        $where[] = ['business_goods.startTime','>=',$dateTime ];
                    }
                    $count = $this->model
                        ->withJoin('businessScene', 'LEFT')
                        //->where('auditorId','<>',0)
                        ->where($where)
                        ->count();
                    $list = $this->model
                        ->withJoin('businessScene', 'LEFT')
                        //->where('auditorId','<>',0)
                        ->where($where)
                        ->page($page, $limit)
                        ->order(['endTime'=>'desc','id'=>'desc'])
                        ->select();
                }else{
                    $count = $this->model
                        ->withJoin('businessScene', 'LEFT')
                        ->where(['auditorId'=>session('admin')['id']])
                        ->where($where)
                        ->count();
                    $list = $this->model
                        ->withJoin('businessScene', 'LEFT')
                        ->where(['auditorId'=>session('admin')['id']])
                        ->where($where)
                        ->page($page, $limit)
                        ->order(['endTime'=>'desc','id'=>'desc'])
                        ->select();
                }
            }
            if($count > 0){
                foreach($list as &$goodsInfo){
                    $goodsInfo['priveledge'] = session('admin')['auth_ids'];
                }
            }else{
                $this->error('暂时没有该商品信息！');
            }
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        //判断当前页面是否通过活动页面进入
        $this->assign('eventId',$eventId);
        return $this->fetch();
    }

    public function updateEventGoodsData($showEventArr)
    {
        $scene_update_time = $showEventArr['updateTime']?strtotime($showEventArr['updateTime']):0;
        $diffTime = time()-$scene_update_time;
        //更新时间超过30允许更新报名商品信息
        if($diffTime > 30){
            //获取账号cookie
            $accountInfo = $this->businessSceneModel->getALiAccountInfo('','',$showEventArr['accountId']);
            //检测账号是否在线
            $onlineInfo = $this->businessSceneModel->getOnlineAccountInfo($accountInfo[0]['token'], $accountInfo[0]['cookies']);
            $eventData[] = [
                'eventData' => [$showEventArr],
                'token' => $accountInfo[0]['token'],
                'accountId' => $accountInfo[0]['account_id'],
                'cookies' => $accountInfo[0]['cookies'],
            ];
            if(!$onlineInfo && !array_key_exists('data', $onlineInfo)){
                return false;
            }
            if($showEventArr['groupId'] > 0){
                $this->model->sycNewEventGoodsInfoToDB($eventData);
            }else{
                $this->model->sycEventGoodsInfoToDB($eventData);
            }
            //更新时间超过5分钟允许更新活动效果数据
            if($diffTime > 300){
                if($showEventArr['groupId'] > 0){
                    $this->businessSceneModel->sycNewEventEffectInfoToDB($accountInfo[0], $showEventArr['eventId'], $showEventArr['groupId'], $showEventArr['startTime']);
                }else{
                    $this->businessSceneModel->sycEventEffectInfoToDB($accountInfo[0], $showEventArr['eventId']);
                }
            }
        }
        return true;
    }

    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        if(strpos($id,',')){
            $ids = explode(',',$id);
        }else{
            $ids[] = $id;
        }
        $row = $this->model->whereIn('id', $id)->select();
        //$row = $this->model->find($id);
        $row->isEmpty() && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            //$rule = [];
            //$this->validate($post, $rule);
            if($post['auditorId'] == ''){
                $post['auditorId'] = 0;
                $post['auditorNick'] = '';
            }else{
                $adminArr = $this->model->getSystemAdminList();
                $post['auditorNick'] = $adminArr[$post['auditorId']];
            }
            try {
                foreach($ids as $id){
                    $save = $row->where('id',$id)->update($post);
                }
            } catch (\Exception $e) {
                $this->error('修改认领人失败');
            }
            $save ? $this->success('修改认领人成功') : $this->error('修改认领人失败');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

    //认领报名商品
    /**
     * @NodeAnotation(title="认领报名商品")
     */
    public function claim($id)
    {
        $row = $this->model->find($id);
        $row->isEmpty() && $this->error('报名商品数据不存在');
        try {
            //修改报名商品中的认领人信息
            if($row->auditorId > 0){
                $this->error('认领失败，该商品已经被认领！');
            }
            $modifyData['auditorId'] = session('admin')['id'];
            $modifyData['auditorNick'] = session('admin')['nickname'];
            $save = $row->save($modifyData);
        } catch (\Exception $e) {
            $this->error('认领失败，请联系管理员查看！');
        }
        $save ? $this->success('认领成功') : $this->error('认领失败');
    }

    //批量认领报名商品
    /**
     * @NodeAnotation(title="批量认领报名商品")
     */
    public function claimAll()
    {
        $ids = $this->request->post('id');
        $row = $this->model->whereIn('id', $ids)->select();
        //$row = $this->model->find($id);
        $row->isEmpty() && $this->error('报名商品数据不存在');
        try {
            //修改报名商品中的认领人信息
            $goodsArr = $row->toArray();
            foreach($goodsArr as $item){
                if($item['auditorId'] > 0 && $item['auditorId'] != session('admin')['id']){
                    $this->error('认领失败，该商品已经被认领！');
                }
                $modifyData['auditorId'] = session('admin')['id'];
                $modifyData['auditorNick'] = session('admin')['nickname'];
                $save = $row->where('id', $item['id'])->update($modifyData);
            }
        } catch (\Exception $e) {
            $this->error('认领失败，请联系管理员查看！');
        }
        $save ? $this->success('认领成功') : $this->error('认领失败');
    }

    //审核通过报名商品
    /**
     * @NodeAnotation(title="审核通过报名商品")
     */
    public function pass($id)
    {
        $row = $this->model->find($id);
        $row->isEmpty() && $this->error('报名商品数据不存在');

        if(session('admin')['taobao_accountId'] == ''){
            $this->error('未绑定联盟账号！');
        }
        $accountInfo = $this->businessSceneModel->getALiAccountInfo(session('admin')['taobao_accountId']);
        //初始化在线账号变量
        if($accountInfo == null){
            $this->error('联盟账号信息为空！');
        }

        $onlineInfo = $this->businessSceneModel->getOnlineAccountInfo($accountInfo[0]['token'], $accountInfo[0]['cookies']);
        if(!$onlineInfo || !array_key_exists('data', $onlineInfo)){
            $this->error($accountInfo[0]['user_name'].' 账号已离线！');
        }
        if($row->signUpRecordId > 0){
            $signGoodsInfo[] = [
                'signUpRecordId' => $row->signUpRecordId,
                'concernLevel' => 2
            ];
            $signGoodsStr = json_encode($signGoodsInfo);
            $verifyRet = $this->model->modifyNewItemStatus($accountInfo[0]['token'], $accountInfo[0]['cookies'],$signGoodsStr,1);
            if(!$verifyRet || !array_key_exists('data',$verifyRet) || !array_key_exists('success',$verifyRet)){
                $this->error('修改审核状态失败！');
            }
            //修改数据表中数据状态
            if($verifyRet['success']){
                try{
                    $modifyData['auditStatus'] = 2;
                    $ret = $row->save($modifyData);
                } catch (\Exception $e) {
                    $this->error('审核失败，请联系管理员查看！');
                }
            }else{
                $this->error('审核通过失败，团长账号未在线！');
            }
        }else{
            $verifyRet = $this->model->modifyItemStatus($accountInfo[0]['token'], $accountInfo[0]['cookies'],$row->eventId,$row->itemId,2);
            if(!$verifyRet || !array_key_exists('data',$verifyRet) || !array_key_exists('ok',$verifyRet)){
                $this->error('修改审核状态失败！');
            }
            //修改数据表中数据状态
            if($verifyRet['ok']){
                try{
                    $modifyData['auditStatus'] = 2;
                    $ret = $row->save($modifyData);
                } catch (\Exception $e) {
                    $this->error('审核失败，请联系管理员查看！');
                }
            }else{
                $this->error('审核通过失败，团长账号未在线！');
            }
        }
        $ret ? $this->success('审核通过成功！') : $this->error('通过失败！');
    }

    //批量审核通过报名商品
    /**
     * @NodeAnotation(title="批量审核通过报名商品")
     */
    public function passAll()
    {
        $ids = $this->request->post('id');
        $row = $this->model->whereIn('id', $ids)->select();
        //$row = $this->model->find($id);
        $row->isEmpty() && $this->error('报名商品数据不存在');

        if(session('admin')['taobao_accountId'] == ''){
            $this->error('未绑定联盟账号！');
        }
        //处理商品数据信息
        $goodsArr = $row->toArray();
        $itemArr = [];
        $goodsIdArr = [];
        $signGoodsInfo = [];
        $version = 'old';
        foreach($goodsArr as $goodsInfo){
            if($goodsInfo['auditorId'] <= 0){
                $this->error("请先认领商品！");
            }
            if($goodsInfo['signUpRecordId'] > 0){
                $itemArr[] = $goodsInfo['signUpRecordId'];
                $goodsIdArr[] = $goodsInfo['id'];
                $version = 'new';
                $signGoodsInfo[] = [
                    'signUpRecordId' => $goodsInfo['signUpRecordId'],
                    'concernLevel' => 2
                ];
            }else{
                $itemArr[$goodsInfo['eventId']][] = $goodsInfo['itemId'];
                $goodsIdArr[$goodsInfo['eventId']][] = $goodsInfo['id'];
            }
        }
        $accountInfo = $this->businessSceneModel->getALiAccountInfo(session('admin')['taobao_accountId']);
        //初始化在线账号变量
        if($accountInfo == null){
            $this->error('联盟账号信息为空！');
        }
        $logContent = '审核人：'.session('admin')['nickname'];
        $this->write_log('/tmp/goodsVerify.log',$logContent);
        $onlineInfo = $this->businessSceneModel->getOnlineAccountInfo($accountInfo[0]['token'], $accountInfo[0]['cookies']);
        $this->write_log('/tmp/goodsVerify.log',$accountInfo[0]['user_name']);
        if(!$onlineInfo || !array_key_exists('data', $onlineInfo)){
            $this->error($accountInfo[0]['user_name'].' 账号已离线！');
        }
        if($version == 'new'){
            $signGoodsStr = json_encode($signGoodsInfo);
            //$itemStr = '['.implode(',', $itemArr).']';
            $this->write_log('/tmp/goodsVerify.log',$signGoodsStr);
            $verifyRet = $this->model->modifyNewItemStatus($accountInfo[0]['token'], $accountInfo[0]['cookies'],$signGoodsStr,1);
            if(!$verifyRet || !array_key_exists('data',$verifyRet) || !array_key_exists('success',$verifyRet)){
                $this->error('批量修改审核状态失败！');
            }
            //20230519 根据修改结果判断是否成功
            if($verifyRet['data']){
                $this->error($verifyRet['data'][0]['extInfo']['title']."<br/>".$verifyRet['data'][0]['bizCheckErrorInfoList'][0]);
            }
            //修改数据表中数据状态
            if($verifyRet['success']){
                try{
                    $modifyData['auditStatus'] = 2;
                    $ret = $row->where('id', $goodsIdArr)->update($modifyData);
                } catch (\Exception $e) {
                $this->error('审核失败，请联系管理员查看！');
            }
            }else{
                $this->error('审核通过失败，团长账号未在线！');
            }
        }else{
            foreach($itemArr as $eventKey=>$itemIdArr){
                $itemIdStr = implode(',', $itemIdArr);
                $this->write_log('/tmp/goodsVerify.log',$itemIdStr);
                $verifyRet = $this->model->modifyAllItemStatus($accountInfo[0]['token'], $accountInfo[0]['cookies'],$eventKey,$itemIdStr,2);
                if(!$verifyRet || !array_key_exists('data',$verifyRet) || !array_key_exists('ok',$verifyRet)){
                    $this->error('批量修改审核状态失败！');
                }
                //修改数据表中数据状态
                if($verifyRet['ok']){
                    try{
                        foreach($goodsIdArr[$eventKey] as $goodsId){
                            $modifyData['auditStatus'] = 2;
                            $ret = $row->where('id', $goodsId)->update($modifyData);
                        }
                    } catch (\Exception $e) {
                        $this->error('审核失败，请联系管理员查看！');
                    }
                }else{
                    $this->error('审核通过失败，团长账号未在线！');
                }
            }
        }
        $ret ? $this->success('批量审核通过成功！') : $this->error('通过失败！');
    }

    //审核拒绝报名商品
    /**
     * @NodeAnotation(title="审核拒绝报名商品")
     */
    public function refuse($id)
    {
        $row = $this->model->find($id);
        $row->isEmpty() && $this->error('报名商品数据不存在');

        if(session('admin')['taobao_accountId'] == ''){
            $this->error('未绑定联盟账号！');
        }
        //var_export($row);
        $accountInfo = $this->businessSceneModel->getALiAccountInfo(session('admin')['taobao_accountId']);
        //初始化在线账号变量
        if($accountInfo == null){
            $this->error('联盟账号信息为空！');
        }

        $onlineInfo = $this->businessSceneModel->getOnlineAccountInfo($accountInfo[0]['token'], $accountInfo[0]['cookies']);
        //var_export($onlineInfo);die;
        if(!$onlineInfo || !array_key_exists('data', $onlineInfo)){
            $this->error($accountInfo[0]['user_name'].' 账号已离线！');
        }
        if($row->signUpRecordId > 0){
            $signGoodsInfo[] = [
                'signUpRecordId' => $row->signUpRecordId,
                'concernLevel' => 2
            ];
            $signGoodsStr = json_encode($signGoodsInfo);
            $verifyRet = $this->model->modifyNewItemStatus($accountInfo[0]['token'], $accountInfo[0]['cookies'],$signGoodsStr, 2);
            if(!$verifyRet || !array_key_exists('data',$verifyRet) || $verifyRet['data'] != true){
                $this->error('修改审核状态失败！');
            }
            //修改数据表中数据状态
            if($verifyRet['success']) {
                try{
                    $modifyData['auditStatus'] = 3;
                    $ret = $row->save($modifyData);
                } catch (\Exception $e) {
                    $this->write_log('/tmp/goodsVerify.log', $e->getMessage());
                    $this->error('审核失败，请联系管理员查看！');
                }
            }else{
                $this->error('审核拒绝失败，团长账号未在线！');
            }
        }else{
            $verifyRet = $this->model->modifyItemStatus($accountInfo[0]['token'], $accountInfo[0]['cookies'],$row->eventId,$row->itemId,3);
            if(!$verifyRet || !array_key_exists('data',$verifyRet) || $verifyRet['data'] != true){
                $this->error('修改审核状态失败！');
            }
            //修改数据表中数据状态
            if($verifyRet['ok']) {
                try{
                    $modifyData['auditStatus'] = 3;
                    $ret = $row->save($modifyData);
                } catch (\Exception $e) {
                    $this->write_log('/tmp/goodsVerify.log', $e->getMessage());
                    $this->error('审核失败，请联系管理员查看！');
                }
            }else{
                $this->error('审核拒绝失败，团长账号未在线！');
            }
        }
        $ret ? $this->success('审核拒绝成功！') : $this->error('拒绝失败！');
    }

    //批量审核拒绝报名商品
    /**
     * @NodeAnotation(title="批量审核拒绝报名商品")
     */
    public function refuseAll()
    {
        $ids = $this->request->post('id');
        $row = $this->model->whereIn('id', $ids)->select();
        //$row = $this->model->find($id);
        $row->isEmpty() && $this->error('报名商品数据不存在');

        if(session('admin')['taobao_accountId'] == ''){
            $this->error('未绑定联盟账号！');
        }
        //处理商品数据信息
        $goodsArr = $row->toArray();
        $itemArr = [];
        $goodsIdArr = [];
        $signGoodsInfo = [];
        $version = 'old';
        foreach($goodsArr as $goodsInfo){
            if($goodsInfo['auditorId'] <= 0){
                $this->error("请先认领商品！");
            }
            if($goodsInfo['signUpRecordId'] > 0){
                $itemArr[] = $goodsInfo['signUpRecordId'];
                $goodsIdArr[] = $goodsInfo['id'];
                $version = 'new';
                $signGoodsInfo[] = [
                    'signUpRecordId' => $goodsInfo['signUpRecordId'],
                    'concernLevel' => 2
                ];
            }else{
                $itemArr[$goodsInfo['eventId']][] = $goodsInfo['itemId'];
                $goodsIdArr[$goodsInfo['eventId']][] = $goodsInfo['id'];
            }
        }
        $accountInfo = $this->businessSceneModel->getALiAccountInfo(session('admin')['taobao_accountId']);
        //初始化在线账号变量
        if($accountInfo == null){
            $this->error('联盟账号信息为空！');
        }

        $onlineInfo = $this->businessSceneModel->getOnlineAccountInfo($accountInfo[0]['token'], $accountInfo[0]['cookies']);
        if(!$onlineInfo || !array_key_exists('data', $onlineInfo)){
            $this->error($accountInfo[0]['user_name'].' 账号已离线！');
        }
        if($version == 'new'){
            $signGoodsStr = json_encode($signGoodsInfo);
            //$itemStr = '['.implode(',', $itemArr).']';
            $verifyRet = $this->model->modifyNewItemStatus($accountInfo[0]['token'], $accountInfo[0]['cookies'],$signGoodsStr,2);
            if(!$verifyRet || !array_key_exists('data',$verifyRet) || !array_key_exists('success',$verifyRet)){
                $this->error('批量修改审核状态失败！');
            }
            //修改数据表中数据状态
            if($verifyRet['success']){
                try{
                    $modifyData['auditStatus'] = 3;
                    $ret = $row->where('id', $goodsIdArr)->update($modifyData);
                } catch (\Exception $e) {
                    $this->error('审核失败，请联系管理员查看！');
                }
            }else{
                $this->error('审核拒绝失败，团长账号未在线！');
            }
        }else{
            foreach($itemArr as $eventKey=>$itemIdArr){
                $itemIdStr = implode(',', $itemIdArr);
                $verifyRet = $this->model->modifyAllItemStatus($accountInfo[0]['token'], $accountInfo[0]['cookies'],$eventKey,$itemIdStr,3);
                if(!$verifyRet || !array_key_exists('data',$verifyRet) || !array_key_exists('ok',$verifyRet)){
                    $this->error('批量修改审核状态失败！');
                }
                //修改数据表中数据状态
                if($verifyRet['ok']){
                    try{
                        foreach($goodsIdArr[$eventKey] as $goodsId) {
                            $modifyData['auditStatus'] = 3;
                            $ret = $row->where('id', $goodsId)->update($modifyData);
                        }
                    } catch (\Exception $e) {
                        $this->error('审核失败，请联系管理员查看！');
                    }
                }else{
                    $this->error('审核拒绝失败，团长账号未在线！');
                }
            }
        }
        $ret ? $this->success('批量审核拒绝成功！') : $this->error('拒绝失败！');
    }

    //获取商品推广详情
    /**
     * @NodeAnotation(title="获取商品推广详情")
     */
    public function detail($id)
    {
        $row = $this->model->find($id)->toArray();


        //获取账号cookie
        $showEventArr = $this->businessSceneModel->where('eventId', $row['eventId'])->find()->toArray();
        $accountInfo = $this->businessSceneModel->getALiAccountInfo('','',$showEventArr['accountId']);
        //检测账号是否在线
        $onlineInfo = $this->businessSceneModel->getOnlineAccountInfo($accountInfo[0]['token'], $accountInfo[0]['cookies']);
        if($onlineInfo && array_key_exists('data', $onlineInfo)){
            //根据活动数据同步报名活动的商品数据
            $eventData[] = [
                'eventData' => [$showEventArr],
                'token' => $accountInfo[0]['token'],
                'accountId' => $accountInfo[0]['account_id'],
                'cookies' => $accountInfo[0]['cookies'],
            ];
            //根据活动数据同步报名活动商品的效果数据
            $this->model->sycEventGoodsEffectToDB($eventData);
            $this->model->sycNewEventGoodsEffectToDB($eventData);
        }

        $diffDay = abs(round((strtotime($row['endTime'])-strtotime($row['startTime'])) / 86400));
        $row['diffDay'] = $diffDay;
        //获取商品当前佣金
        $taolijinModel = new \app\admin\model\MallTaolijinGoods();
        $dataokeGoodsInfo = $taolijinModel->getGoodsInfo($row['itemId']);
        if($dataokeGoodsInfo && array_key_exists('commissionRate',$dataokeGoodsInfo)){
            $row['currentCommissionRate'] = $dataokeGoodsInfo['commissionRate']?$dataokeGoodsInfo['commissionRate']:'';
        }else{
            $row['currentCommissionRate'] = '';
        }
        $this->assign('goodsRow', $row);
        return $this->fetch();
    }

    public function write_log($filename, $content){
        $newContent = date("Y-m-d H:i:s")." ".$content."\r\n";
        file_put_contents($filename,$newContent,FILE_APPEND);
        return true;
    }



    //批量导出报名商品
    /**
     * @NodeAnotation(title="导出")
     */
    public function export()
    {
        $ids = $this->request->post('id');
        $allowField = ['itemId','eventId','shopTitle','title','auctionUrl','couponZkFinalPrice','auditStatus','commissionRate','serviceRate','startTime','endTime','alipayAmt','commissionHint'];
        $row = $this->model->whereIn('id', $ids)->field($allowField)->select();
        $row->isEmpty() && $this->error('报名商品数据不存在');

        $tableName = $this->model->getName();
        $tableName = CommonTool::humpToLine(lcfirst($tableName));
        $prefix = config('database.connections.mysql.prefix');
        $dbList = Db::query("show full columns from {$prefix}{$tableName}");
        $header = [];

        foreach ($dbList as $vo) {
            $comment = !empty($vo['Comment']) ? $vo['Comment'] : $vo['Field'];
            if(!in_array($vo['Field'],$allowField)){
                continue;
            }
            if(strpos($vo['Field'],'auditStatus')){
                $header['审核状态'] = 'string';
            }
            if(strpos($vo['Field'],'time')){
                $header[$comment] = 'datetime';
            }elseif(strpos($vo['Field'],'price')){
                $header[$comment] = 'price';
            }else{
                $header[$comment] = 'string';
            }
        }
        $writer = new \XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header );
        $auditStatus = ['0'=>'所有商品状态','1'=>'待团长审核','2'=>'通过','3'=>'拒绝','5'=>'取消报名','7'=>'超级U选通过'];
        $goodsArr = $row->toArray();
        foreach($goodsArr as $item){
            $item['auditStatus'] = $auditStatus[$item['auditStatus']];
            $writer->writeSheetRow('Sheet1', $item);
        }
        //判断文件夹不存在则创建
        if(!is_dir('/app/public/download/eventGoods/')){
            mkdir('/app/public/download/eventGoods/');
        }
        $fileName = 'eventGoods'.time();
        $filePath = '/app/public/download/eventGoods/'.$fileName.'.xlsx';
        $writer->writeToFile($filePath);
        sleep(3);
        $this->success('导出成功','','https://www.childrendream.cn/download/eventGoods/'.$fileName.'.xlsx');
    }

}