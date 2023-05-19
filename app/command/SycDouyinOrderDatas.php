<?php
declare (strict_types = 1);

namespace app\command;

include_once "/app/extend/douyinsdk/autoload.php";
include_once "/app/extend/douyinsdk/open/core/GlobalConfig.php";

use app\admin\model\IntegralDouorder;
use app\admin\model\SystemDouyinAccount;
use app\admin\model\SystemScriptTask;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class SycDouyinOrderDatas extends Command
{
    protected $douyinAccountObj;

    protected $orderModelObj;

    protected $taskModelObj;

    protected $appKey;

    protected $appSecret;

    protected $requestHost;

    protected function configure()
    {
        // 指令配置
        // 每分钟执行一次，时间段在00:00~02:00
        // 当天数据每小时更新一次，时间段在08:00~22:00
        $this->setName('sycDouyinOrderDatas')
            ->addArgument('currentDay', Argument::OPTIONAL, "更新日期")//today
            ->setDescription('同步近7天订单数据');
    }

    public function setConfig()
    {
        \GlobalConfig::getGlobalConfig()->appKey = $this->appKey = "7216292262077072953";
        \GlobalConfig::getGlobalConfig()->appSecret = $this->appSecret = "769cf07c-0356-4e58-b789-1f0d0731beb2";
        $this->requestHost = 'https://openapi-fxg.jinritemai.com';

        if(!($this->douyinAccountObj instanceof SystemDouyinAccount)){
            $this->douyinAccountObj = new \app\admin\model\SystemDouyinAccount();
        }
        if(!($this->orderModelObj instanceof IntegralDouorder)){
            $this->orderModelObj = new \app\admin\model\IntegralDouorder();
        }
        if(!($this->taskModelObj instanceof SystemScriptTask)){
            $this->taskModelObj = new \app\admin\model\SystemScriptTask();
        }
    }

    protected function execute(Input $input, Output $output)
    {
    	// 暂时设定同步近7天数据，执行7次脚本，每次同步一天数据
        $output->writeln(date('Y-m-d H:i:s').' :开始检测抖音订单数据同步任务');
        //获取指令参数
        $currentDay = $input->getArgument('currentDay');
        $this->setConfig();
        //$ret = $this->getRewardOrder('48d75f4c-30eb-4eca-aa94-b01aecbf6340','20230407','20230407',0,10);
        //var_export($ret);die;
        //$ret = $this->getDouOrder('48d75f4c-30eb-4eca-aa94-b01aecbf6340','2023-03-25 05:10:00','2023-04-01 05:15:00',0,10);
        //var_export($ret);die;

        //检测accessToken是否过期，过期则更新token
        $this->getAccessToken();
        //根据任务信息开始同步订单数据
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
                $output->writeln(date('Y-m-d H:i:s').' : 没有可执行抖音订单任务或任务正在执行中!');
                return false;
            }
        }
        $this->solveOrderData($taskInfo);
        $output->writeln(date('Y-m-d H:i:s').' : 抖音任务已执行完成!');
    }


    // 序列化参数，入参必须为关联数组
    public function marshal(array $param): string {
        $this->rec_ksort($param); // 对关联数组中的kv，执行排序，需要递归
        $s = json_encode($param, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); // 重新序列化，确保所有key按字典序排序
        // 加入flag，确保斜杠不被escape，汉字不被escape
        return $s;
    }

    // 关联数组排序，递归
    public function rec_ksort(array &$arr) {
        $kstring = true;
        foreach ($arr as $k => &$v) {
            if (!is_string($k)) {
                $kstring = false;
            }
            if (is_array($v)) {
                $this->rec_ksort($v);
            }
        }
        if ($kstring) {
            ksort($arr);
        }
    }

    // 计算签名
    public function sign(string $appKey, string $appSecret, string $method, int $timestamp, string $paramJson): string {
        $paramPattern = 'app_key' . $appKey . 'method' . $method . 'param_json' . $paramJson . 'timestamp' . $timestamp . 'v2';
        $signPattern = $appSecret . $paramPattern . $appSecret;
        //print('sign_pattern:' . $signPattern . "\n");
        return hash_hmac("sha256", $signPattern, $appSecret);
        //return md5($signPattern);
    }

    // 调用Open Api，取回数据
    public function fetch(string $appKey, string $host, string $method, int $timestamp, string $paramJson, string $accessToken, string $sign): string {
        $methodPath = str_replace('.', '/', $method);
        $url = $host . '/' . $methodPath .
            '?method=' . urlencode($method) .
            '&app_key=' . urlencode($appKey) .
            '&access_token=' .urlencode($accessToken) .
            '&timestamp=' . urlencode(strval($timestamp)) .
            '&v=' . urlencode('2') .
            '&sign=' . urlencode($sign) .
            '&sign_method=' . urlencode('hmac-sha256');
            //'&sign_method=' . urlencode('md5');
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => "Accept: */*\r\n" .
                    "Content-type: application/json;charset=UTF-8\r\n",
                'content' => $paramJson
            )
        );
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    public function getRewardOrder($token, $startTime, $endTime, $page=0,$pageSize=200){
        // 收集参数
        $appKey = $this->appKey; //  替换成你的app_key
        $appSecret = $this->appSecret; // 替换成你的app_secret
        $accessToken = $token; // 替换成你的access_token
        $host = $this->requestHost;
        $method = 'buyin.doukeRewardOrders';
        $timestamp = time();
        $m = array(
            "page"=> $page,
            "page_size"=> $pageSize,
            "start_date"=> $startTime,
            "end_date"=> $endTime,
            //"order_ids"=>"",
            //"pid"=>"",
        );
        // 序列化参数
        $paramJson = $this->marshal($m);
        // 计算签名
        $signVal = $this->sign($appKey, $appSecret, $method, $timestamp, $paramJson);
        // 发起请求
        $responseVal = $this->fetch($appKey, $host, $method, $timestamp, $paramJson, $accessToken, $signVal);
        return json_decode($responseVal, true);
    }


    public function getDouOrder($token, $startTime, $endTime, $page=0,$pageSize=200){
        // 收集参数
        $appKey = $this->appKey; //  替换成你的app_key
        $appSecret = $this->appSecret; // 替换成你的app_secret
        $accessToken = $token; // 替换成你的access_token
        $host = $this->requestHost;
        $method = 'buyin.doukeOrderAds';
        $timestamp = time();
        $m = array(
            "size"=> $pageSize,
            "cursor"=> $page,
            "start_time"=> $startTime,
            "end_time"=> $endTime,
            //"order_ids"=>"",
            //"pid"=>"",
            //"distribution_type"=>"",
            "time_type"=>"pay",
            "query_order_type"=>"0"
        );
        // 序列化参数
        $paramJson = $this->marshal($m);
        // 计算签名
        $signVal = $this->sign($appKey, $appSecret, $method, $timestamp, $paramJson);
        // 发起请求
        $responseVal = $this->fetch($appKey, $host, $method, $timestamp, $paramJson, $accessToken, $signVal);
        //var_export($responseVal);die;
        return json_decode($responseVal, true);
    }

    public function getAccessToken()
    {
        //从数据库中获取code
        $accountInfo = $this->douyinAccountObj->field('any_value(doukeID) as doukeID,any_value(code) as code,any_value(accessToken) as accessToken,any_value(refreshToken) as refreshToken,any_value(updateTime) as updateTime')
            ->group('doukeID')->select()->toArray();
        if($accountInfo == null){
            return false;
        }
        foreach($accountInfo as $item){
            $diffTime = time()-strtotime($item['updateTime']);
            if($item['accessToken'] == ''){
                $accessToken = \AccessTokenBuilder::build($item['code'], ACCESS_TOKEN_CODE);
                $result = json_decode(json_encode($accessToken->getData()), true);
                $updateData = [
                    'accessToken' => $result['access_token'],
                    'refreshToken' => $result['refresh_token'],
                    'updateTime' => date("Y-m-d H:i:s"),
                ];
                $this->douyinAccountObj->where('doukeID','=',$item['doukeID'])->update($updateData);
            }elseif($diffTime >= 3600*168){
                //判断是否超过有效期
                $accessToken = \AccessTokenBuilder::refresh($item['refreshToken']);
                //var_export($accessToken);die;
                $result = json_decode(json_encode($accessToken->getData()), true);
                $updateData = [
                    'accessToken' => $result['access_token'],
                    'refreshToken' => $result['refresh_token'],
                    'updateTime' => date("Y-m-d H:i:s"),
                ];
                $this->douyinAccountObj->where('doukeID','=',$item['doukeID'])->update($updateData);
            }
        }
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
        //根据抖客账号获取所有账号订单数据
        $accountInfo = $this->douyinAccountObj->field('any_value(doukeID) as doukeID,any_value(code) as code,any_value(accessToken) as accessToken,any_value(refreshToken) as refreshToken,any_value(updateTime) as updateTime')
            ->group('doukeID')->select()->toArray();
        if($accountInfo == ''){
            return false;
        }
        foreach($accountInfo as $item){
            //开始循环获取时间段内数据
            $interval_time = 3600*6;
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
                $positionIndex = 0;
                echo '========================='."\r\n";
                echo 'solve loop number is : '.$loopNum."\r\n";
                echo 'starttime: '.$startTime."\r\n";
                echo 'stoptime: '.$stopTime."\r\n";
                echo "\r\n";

                do {
                    $orderList = $this->getDouOrder($item['accessToken'], $startTime, $stopTime, $positionIndex);
                    if(array_key_exists('data', $orderList) && $orderList['code'] == '10000'){
                        $positionIndex = $orderList['data']['cursor'];
                        echo date('Y-m-d H:i:s').' : '.$positionIndex."\r\n";
                        if(array_key_exists('orders',$orderList['data'])){
                            $ret = $this->updateOrderData($orderList['data']['orders']);
                        }else{
                            echo "the data of time is null ! \r\n";
                        }
                    } else{
                        //调用接口失败，修改时间间隔为20分钟，重新开始循环
                        //$interval_time = 1200;
                        //$loopNum--;
                        echo date('Y-m-d H:i:s').' : '.json_encode($orderList)."\r\n";
                        return false;
                    }
                }
                while ($positionIndex > 0);
                //自增到下一个时间段
                $loopNum++;
            }
            while($stopTime < $currentStopTime);
        }
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
        if($status == 2 && $taskContentArr['currentDay'] >= 7){
            $taskArr['task_status'] = 2;
            //增加钉钉提醒
            //$this->sendDingDingMsg($taskInfo);
        }
        $ret = $this->taskModelObj->where('id',$id)->update($taskArr);
        return $ret;
    }

    //获取任务表数据
    protected function getTaskInfo()
    {
        //读取任务列表检查当天更新任务是否存在，若存在继续检查是否完成所有任务
        //新增任务类型3：抖音订单数据同步任务
        $currentTaskDay = date('Y-m-d');
        $carryDate = date('Y-m-d 00:00:00',strtotime('-1 day'));
        $taskInfo = $this->taskModelObj->where('type',3)
            ->whereDay('create_time', $currentTaskDay)
            ->find();
        if(!$taskInfo){
            //任务数据不存在时主动创建一条当天任务数据，并在taskDetailInfo中标志前一天同步任务处于进行中
            $taskDetailInfo = [
                'taskDetail' => '同步'.date('Y-m-d 00:00:00',strtotime('-7 day')).'至'.$carryDate.'内的抖音订单数据',
                'currentDay' => 1,//任务正在同步前1天数据
                $carryDate => 1,//0:未进行  1:进行中  2:已完成
            ];
            $taskInfo = [
                'title' => date('Y-m-d').'同步近7天抖音订单数据',
                'relation_id' => 0,
                'type' => 3,
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
            if($taskDetailInfo['currentDay'] >=7){
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

    protected function updateOrderData($orderData, $deleteParameter = false)
    {
        //存在订单编号同样的数据问题，解决方案：先清空需要更新时间段内容的数据，之后获取时间段内容数据全部插入数据表中
        //当前方法实现清空时间段内的数据以及新增数据
        if($deleteParameter){
            $ret = $this->orderModelObj->whereTime('pay_success_time','between',[$deleteParameter['startTime'],$deleteParameter['endTime']])
                ->delete();
        }else{
            if($orderData == null){
                return false;
            }
            $orderArr = [];
            foreach($orderData as $key=>$orderInfo){
                $orderArr[] = [
                    'order_id' => $orderInfo['order_id'],
                    'pid' => $orderInfo['pid_info']['pid'],
                    'product_id' => $orderInfo['product_id'],
                    'product_name' => $orderInfo['product_name'],
                    'good_share_type' => $orderInfo['pid_info']['media_type_name'],
                    'shop_id' => $orderInfo['shop_id'],
                    'shop_name' => $orderInfo['shop_name'],
                    'commission_rate' => $orderInfo['ads_promotion_rate'],
                    'pay_amount' => $orderInfo['total_pay_amount'],
                    'pay_success_time' => $orderInfo['pay_success_time'],
                    'order_status' => $orderInfo['flow_point'],
                    'update_time' => $orderInfo['update_time'],
                ];
            }
            $ret = $this->orderModelObj->strict(false)->insertAll($orderArr);
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
