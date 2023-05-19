<?php
declare (strict_types = 1);

namespace app\command;

include_once "/app/extend/taobaosdk/TopSdk.php";
include_once "/app/extend/xlsxwriter.class.php";

use app\admin\model\BusinessOrder;
use app\admin\model\SystemScriptTask;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class SycOrderDatas extends Command
{
    protected $taskModelObj;

    protected $orderModelObj;

    protected function configure()
    {
        // 指令配置
        // 每分钟执行一次，时间段在00:00~02:00
        // 当天数据每小时更新一次，时间段在08:00~22:00
        $this->setName('sycOrderDatas')
            ->addArgument('currentDay', Argument::OPTIONAL, "更新日期")
            ->setDescription('同步近30天订单数据');
    }

    protected function execute(Input $input, Output $output)
    {
        /*$parameter = [
            'pageSize' => 10,
            'startTime' => '2022-11-01 00:00:00',
            'endTime' => '2022-11-01 03:00:00',
            'position' => ''
        ];
        $orderList = $this->getOrderListInfo($parameter);
        var_export($orderList);die;*/
        //获取指令参数
        $currentDay = $input->getArgument('currentDay');

    	// 暂时设定同步近30天数据，执行30次脚本，每次同步一天数据
        $output->writeln(date('Y-m-d H:i:s').' :开始检测订单数据同步任务');

        if(!($this->orderModelObj instanceof BusinessOrder)){
            $this->orderModelObj = new \app\admin\model\BusinessOrder();
        }
        //如果存在日期参数，按照日期更新数据，不读取任务数据
        if($currentDay == 'today'){
            $taskInfo = [
                'taskId' => 0,
                'carryDate' => date("Y-m-d 00:00:00")
            ];
        }elseif($currentDay){
            $currentDate = date("Y-m-d", strtotime($currentDay))." 00:00:00";
            $taskInfo = [
                'taskId' => 0,
                'carryDate' => $currentDate
            ];
        }else{
            //获取任务信息
            $taskInfo = $this->getTaskInfo();
            if(!$taskInfo){
                $output->writeln(date('Y-m-d H:i:s').' : 没有可执行任务或任务正在执行中!');
                return ;
            }
        }
        //根据任务信息开始同步订单数据
        $this->solveOrderData($taskInfo);
        $output->writeln(date('Y-m-d H:i:s').' : '.$taskInfo['taskId'].'任务已执行完成!');

    }

    //获取任务表数据
    protected function getTaskInfo()
    {
        if(!($this->taskModelObj instanceof SystemScriptTask)){
            $this->taskModelObj = new \app\admin\model\SystemScriptTask();
        }
        //读取任务列表检查当天更新任务是否存在，若存在继续检查是否完成所有任务
        $currentTaskDay = date('Y-m-d');
        $carryDate = date('Y-m-d 00:00:00',strtotime('-1 day'));
        $taskInfo = $this->taskModelObj->where('type',0)
            ->whereDay('create_time', $currentTaskDay)
            ->find();
        if(!$taskInfo){
            //任务数据不存在时主动创建一条当天任务数据，并在taskDetailInfo中标志前一天同步任务处于进行中
            $taskDetailInfo = [
                'taskDetail' => '同步'.date('Y-m-d 00:00:00',strtotime('-30 day')).'至'.$carryDate.'内的订单数据',
                'currentDay' => 1,//任务正在同步前1天数据
                $carryDate => 1,//0:未进行  1:进行中  2:已完成
            ];
            $taskInfo = [
                'title' => date('Y-m-d').'同步近30天订单数据',
                'relation_id' => 0,
                'type' => 0,
                'task_status' => 1,
                'task_content' => json_encode($taskDetailInfo),
                'creater_id' => 1,
                'create_time' => date("Y-m-d H:i:s"),
            ];
            try{
                $insertId = $this->taskModelObj->insert($taskInfo, true);
                return [
                    'taskId' => $insertId,
                    'carryDate' => $carryDate
                ];
            }catch(\Exception $e){
                echo $e->getMessage()."\r\n";
            }
        }else{
            //任务数据存在时检测当前同步任务日期内是否有正在同步的任务进行
            $taskDetailInfo = json_decode($taskInfo['task_content'], true);
            //判断同步天数是否超过30天
            if($taskDetailInfo['currentDay'] >=30){
                return false;
            }
            $currentCarryDate = date('Y-m-d 00:00:00', strtotime('-'.$taskDetailInfo['currentDay'].' day'));
            if($taskDetailInfo[$currentCarryDate] == 1){
                //当前更新天数的任务处于执行中时，退出当前执行脚本
                return false;
            } elseif($taskDetailInfo[$currentCarryDate] == 2){
                //当前更新天数的任务处于完成状态，自增天数，即更新当前任务的前一天数据
                $currentDay = $taskDetailInfo['currentDay'] + 1;
                $nextCarryDate = date('Y-m-d 00:00:00', strtotime('-'.$currentDay.' day'));
                $taskDetailInfo['currentDay'] = $currentDay;
                $taskDetailInfo[$nextCarryDate] = 1;
                $modifyTaskInfo['task_content'] = json_encode($taskDetailInfo);

                try{
                    $modifyRet = $this->taskModelObj->where('id',$taskInfo['id'])->update($modifyTaskInfo);
                    return [
                        'taskId' => $taskInfo['id'],
                        'carryDate' => $nextCarryDate
                    ];
                }catch(\Exception $e){
                    echo $e->getMessage()."\r\n";
                }
            }elseif($taskDetailInfo[$currentCarryDate] == 0){
                $taskDetailInfo[$currentCarryDate] = 1;
                $modifyTaskInfo['task_content'] = json_encode($taskDetailInfo);
                try{
                    $modifyRet = $this->taskModelObj->where('id',$taskInfo['id'])->update($modifyTaskInfo);
                    return [
                        'taskId' => $taskInfo['id'],
                        'carryDate' => $currentCarryDate
                    ];
                }catch(\Exception $e){
                    echo $e->getMessage()."\r\n";
                }
            }
        }
        return false;
    }

    //调用接口开始同步数据入库
    protected function solveOrderData($taskInfo){
        $currentStopTime = date('Y-m-d H:i:s', strtotime($taskInfo['carryDate'])+3600*24-1);
        //存在订单编号同样的数据问题，解决方案：先清空需要更新时间段内容的数据，之后获取时间段内容数据全部插入数据表中
        $deleteArr = [
            'startTime' => $taskInfo['carryDate'],
            'endTime' => $currentStopTime,
        ];
        $deleteRet = $this->updateOrderData('',$deleteArr);
        echo "delete data ".$taskInfo['carryDate']." , result: ".$deleteRet." \r\n";
        //开始循环获取时间段内数据
        $interval_time = 3600*3;
        $loopNum = 0;
        do{
            $startTime = date('Y-m-d H:i:s',strtotime($taskInfo['carryDate'])+$interval_time*$loopNum);
            $stopTime = date('Y-m-d H:i:s',strtotime($taskInfo['carryDate'])+$interval_time*($loopNum+1));
            //去掉两个时间间隔之间重复的一秒
            if($loopNum != 0){
                $startTime = date('Y-m-d H:i:s',strtotime($taskInfo['carryDate'])+$interval_time*$loopNum+1);;
            }
            if($stopTime > $currentStopTime){
                $stopTime = $currentStopTime;
            }
            //定位参数初始化
            $positionIndex = '';
            $has_next = false;

            echo '========================='."\r\n";
            echo 'solve loop number is : '.$loopNum."\r\n";
            echo 'starttime: '.$startTime."\r\n";
            echo 'stoptime: '.$stopTime."\r\n";
            echo "\r\n";


            do {
                $parameter = [
                    'pageSize' => 1000,
                    'startTime' => $startTime,
                    'endTime' => $stopTime,
                    'position' => $positionIndex
                ];
                $orderList = $this->getOrderListInfo($parameter);
                if(array_key_exists('data', $orderList) && array_key_exists('has_next', $orderList['data'])){
                    //$pageNum = $orderList['data']['page_no'];
                    $has_next = $orderList['data']['has_next'];
                    $positionIndex = $orderList['data']['position_index'];
                    echo date('Y-m-d H:i:s').' : '.$positionIndex."\r\n";
                    if(array_key_exists('results',$orderList['data']) && array_key_exists('cp_publisher_order_d_t_o',$orderList['data']['results'])){
                        $ret = $this->updateOrderData($orderList['data']['results']['cp_publisher_order_d_t_o']);
                    }else{
                        echo "the data of time is null ! \r\n";
                    }
                }
                else{
                    //调用接口失败，修改时间间隔为20分钟，重新开始循环
                    //$interval_time = 1200;
                    //$loopNum--;
                    echo date('Y-m-d H:i:s').' : '.json_encode($orderList)."\r\n";
                    return false;
                }
            }
            while ($has_next == 'true');
            //自增到下一个时间段
            $loopNum++;
        }
        while($stopTime < $currentStopTime);
        if($taskInfo['taskId']){
            //任务执行完成，更新任务状态
            $this->modifyTaskStatus($taskInfo['taskId'],2, $taskInfo['carryDate']);
        }
        echo date('Y-m-d H:i:s').' : task already execute !'."\r\n";
        echo "\r\n";
        return true;
    }

    //修改任务状态
    protected function modifyTaskStatus($id,$status,$carryDay){
        $taskInfo = $this->taskModelObj->where('id', $id)->find();
        $taskContentArr = json_decode($taskInfo['task_content'], true);
        $taskContentArr[$carryDay] = $status;
        $taskArr['task_content'] = json_encode($taskContentArr);
        $taskArr['update_time'] = date('Y-m-d H:i:s');
        if($status == 2 && $taskContentArr['currentDay'] >=30){
            $taskArr['task_status'] = 2;
            //增加钉钉提醒
            //$this->sendDingDingMsg($taskInfo);
        }
        $ret = $this->taskModelObj->where('id',$id)->update($taskArr);
        return $ret;
    }

    /*
     * 获取订单信息
     */
    public function getOrderListInfo($parameter)
    {
        $c = new \TopClient;
        $c->appkey = '32012766';//33775833
        //$c->appkey = '33775833';
        $c->secretKey = 'a585a4c0a95ea6a04eacf5573c4149f3';//0e39304e154d990819e6912bba7500b0
        //$c->secretKey = '0e39304e154d990819e6912bba7500b0';
        $c->format = 'json';

        $req = new \TbkCpOrderDetailsGetRequest;

        $req->setJumpType("1");
        $req->setPageSize($parameter['pageSize']);
        $req->setQueryType("1");
        //$req->setPageNo("1");
        $req->setStartTime($parameter['startTime']);
        $req->setPositionIndex($parameter['position']);
        $req->setEndTime($parameter['endTime']);
        //$req->setTkStatus("12");
        $resp = $c->execute($req);
        $resultData = json_decode(json_encode($resp),true);
        return $resultData;
    }

    protected function updateOrderData($orderData, $deleteParameter = false)
    {
        //存在订单编号同样的数据问题，解决方案：先清空需要更新时间段内容的数据，之后获取时间段内容数据全部插入数据表中
        //当前方法实现清空时间段内的数据以及新增数据
        if($deleteParameter){
            $ret = $this->orderModelObj->whereTime('tk_create_time','between',[$deleteParameter['startTime'],$deleteParameter['endTime']])
                ->delete();
        }else{
            if($orderData == null){
                return false;
            }
            foreach($orderData as $key=>$orderInfo){
                if(array_key_exists('tk_deposit_time', $orderInfo) && $orderInfo['tk_deposit_time'] == '--'){
                    $orderInfo['tk_deposit_time'] = null;
                }
                if(array_key_exists('tb_deposit_time', $orderInfo) && $orderInfo['tb_deposit_time'] == '--'){
                    $orderInfo['tb_deposit_time'] = null;
                }
                if(array_key_exists('tk_earning_time', $orderInfo) && $orderInfo['tk_earning_time'] == '--'){
                    $orderInfo['tk_earning_time'] = null;
                }
                $orderArr = [
                    'alipay_total_price' => '',
                    'click_time' => null,
                    'deposit_price' => '',
                    'event_id' => '',
                    'item_id' => '',
                    'item_num' => '',
                    'item_price' => '',
                    'item_title' => '',
                    'marketing_type' => '',
                    'modified_time' => null,
                    'pay_price' => '',
                    'pre_service_fee' => '',
                    'seller_nick' => '',
                    'seller_shop_title' => '',
                    'service_fee' => '',
                    'tb_deposit_time' => null,
                    'tb_paid_time' => null,
                    'tk_create_time' => null,
                    'tk_deposit_time' => null,
                    'tk_earning_time' => null,
                    'tk_paid_time' => null,
                    'tk_service_rate' => '',
                    'tk_status' => '',
                    'trade_parent_id' => '',
                    'cp_channel_id' => '',
                    'cp_channel_name' => '',
                    'pub_id' => '',
                    'pub_nick_name' => '',
                ];
                $orderData[$key] = array_merge($orderArr, $orderInfo);
                //$ret = $this->orderModelObj->strict(false)->insert($orderInfo);
            }
            $ret = $this->orderModelObj->strict(false)->insertAll($orderData);
            /*foreach($orderData as $orderInfo){
                $row = $this->orderModelObj->where('trade_parent_id','=',$orderInfo['trade_parent_id'])
                    ->find();
                if($row){
                    $ret = $this->orderModelObj->where('trade_parent_id','=',$orderInfo['trade_parent_id'])
                        ->strict(false)
                        ->update($orderInfo);
                }else{
                    $ret = $this->orderModelObj->strict(false)->insert($orderInfo);
                }
            }*/
        }
        return $ret;
    }


    //钉钉提醒
    public function sendDingDingMsg($taskInfo)
    {
        //获取任务信息
        $adminObj = new \app\admin\model\SystemAdmin();
        $name = $adminObj->where('id','=',$taskInfo['creater_id'])->value('nickname');
        $message = "Hello,".$name.",你的任务《".$taskInfo['title']."》已完成了哦，赶快处理吧！";
        $webhook = "https://oapi.dingtalk.com/robot/send?access_token=d61f7604aa6dfc87d2052d19ed4b29d24463ffdbfe6257068e4e6fad0cf1d4c5";
        $data = array ('msgtype' => 'text','text' => array ('content' => $message));
        $data_string = json_encode($data);
        $result = $this->request_by_curl($webhook, $data_string);
        return $result;
    }

    public function request_by_curl($remote_server, $post_string) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}
