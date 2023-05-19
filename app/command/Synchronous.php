<?php
declare (strict_types = 1);

namespace app\command;

include_once "/app/extend/taobaosdk/TopSdk.php";
include_once "/app/extend/xlsxwriter.class.php";

use app\admin\model\BusinessOrder;
use app\admin\model\SystemScriptTask;
use EasyAdmin\tool\CommonTool;
use jianyan\excel\Excel;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;
use ZipArchive;

class Synchronous extends Command
{
    protected $taskModelObj;

    protected $orderModelObj;

    protected function configure()
    {
        // 指令配置
        $this->setName('synchronous')
<<<<<<< HEAD
            ->setDescription('同步订单数据以及导出订单数据');
=======
            ->setDescription('the synchronous command');        
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
    }

    protected function execute(Input $input, Output $output)
    {
    	// 指令输出
        $output->writeln(date('Y-m-d H:i:s').' :Ready , GO!');

        if(!($this->orderModelObj instanceof BusinessOrder)){
            $this->orderModelObj = new \app\admin\model\BusinessOrder();
        }

        //没有任务数据等待
        $waitLoopNum = 100;
        $output->writeln(date('Y-m-d H:i:s').' :'.$waitLoopNum);
        for($wait=1;$wait<=$waitLoopNum;$wait++){
            $taskInfo = $this->getTaskInfo();
            if($taskInfo == null){
                $output->writeln(date('Y-m-d H:i:s').' :No task need to execute !');
                sleep(600);
                if($wait>=100){
                    return ;
                }
            }else{
                //存在任务数据跳出等待循环
                break;
            }
        }

        //判断任务类型
        if($taskInfo['type'] == 1){
            $this->solveOrderData($taskInfo);
        }elseif($taskInfo['type'] == 2){
            $this->exportOrderData($taskInfo);
        }



    }

    //获取任务表数据
    protected function getTaskInfo()
    {
        if(!($this->taskModelObj instanceof SystemScriptTask)){
            $this->taskModelObj = new \app\admin\model\SystemScriptTask();
        }
        $taskInfo = $this->taskModelObj->whereIn('type',[1,2])
            ->where('relation_id', '=', 0)
            ->where('task_status','<>',2)
            ->order('create_time','asc')
            ->select();
        if($taskInfo->isEmpty()){
            return [];
        }
        $taskArr = $taskInfo->toArray();
        //修改任务状态
        //$statusInfo = ['task_status' => 1];
        $this->modifyTaskStatus($taskArr[0]['id'],1);
        /*$this->taskModelObj->where('id', '=', $taskArr[0]['id'])
            ->update($statusInfo);*/
        //查询任务下是否有子任务
        $childTaskInfo = $this->taskModelObj->whereIn('type',[1,2])
            ->where('relation_id','=',$taskArr[0]['id'])
            ->where('task_status','=',0)
            ->order('id','asc')
            ->select();
        if(!($childTaskInfo->isEmpty())){
            $childTaskArr = $childTaskInfo->toArray();
            //修改子任务状态并返回任务数据
            $ret = $this->modifyTaskStatus($childTaskArr[0]['id'],1);
            //判断是否是最后一条子任务
            if(count($childTaskArr) <= 1){
                $childTaskArr[0]['lastData'] = true;
            }
            /*$ret = $this->taskModelObj->where('id','=',$childTaskInfo[0]['id'])
                ->update($statusInfo);*/
            if($ret){
                return $childTaskArr[0];
            }
        }elseif($taskArr[0]['task_status'] == 0){
            return $taskArr[0];
        }
        //异常数据返回为空，不执行同步订单数据
        return [];
    }

    //调用接口开始同步数据入库
    protected function solveOrderData($taskInfo){
        $taskContent = json_decode($taskInfo['task_content'], true);
        /*$startTime = $taskContent['start_time'];
        $endTime = $taskContent['end_time'];
        $loopNum = ceil((strtotime($taskContent['end_time']) - strtotime($taskContent['start_time']))/3600/3);*/
        //开始循环获取时间段内数据
        $interval_time = 3600*3;
        $loopNum = 0;
        do{
            $startTime = date('Y-m-d H:i:s',strtotime($taskContent['start_time'])+$interval_time*$loopNum);
            $stopTime = date('Y-m-d H:i:s',strtotime($taskContent['start_time'])+$interval_time*($loopNum+1));
            if($stopTime > $taskContent['end_time']){
                $stopTime = $taskContent['end_time'];
            }
            //定位参数初始化
            $positionIndex = '';

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
                    if(array_key_exists('results',$orderList['data']) && array_key_exists('result',$orderList['data']['results'])){
                        $ret = $this->updateOrderData($orderList['data']['results']['result']);
                    }
                }else{
                    //调用接口失败，修改时间间隔为20分钟，重新开始循环
                    $interval_time = 1200;
                    $loopNum--;
                }
            }
            while ($has_next == 'true');
            //自增到下一个时间段
            $loopNum++;
        }
        while($stopTime < $taskContent['end_time']);
        //任务执行完成，更新任务状态
        $this->modifyTaskStatus($taskInfo['id'],2);
        if(array_key_exists('lastData', $taskInfo) && $taskInfo['lastData'] == true){
            $this->modifyTaskStatus($taskInfo['relation_id'], 2);
            //增加钉钉提醒
            $this->sendDingDingMsg($taskInfo);
        }
        echo date('Y-m-d H:i:s').' : task already execute !'."\r\n";
        echo "\r\n";
    }

    //将数据导出到excel文件并压缩
    protected function exportOrderData($taskInfo){
        $taskContent = json_decode($taskInfo['task_content'], true);
        /*$startTime = $taskContent['start_time'];
        $endTime = $taskContent['end_time'];
        $loopNum = ceil((strtotime($taskContent['end_time']) - strtotime($taskContent['start_time']))/3600/3);*/
        //开始循环获取时间段内数据
        $interval_time = 3600*24;
        $loopNum = 0;
        //处理excel文件头部信息
        $tableName = $this->orderModelObj->getName();
        $tableName = CommonTool::humpToLine(lcfirst($tableName));
        $prefix = config('database.connections.mysql.prefix');
        $dbList = Db::query("show full columns from {$prefix}{$tableName}");
        $header = [];

        foreach ($dbList as $vo) {
            $comment = !empty($vo['Comment']) ? $vo['Comment'] : $vo['Field'];
            //$header[] = [$comment, $vo['Field']];
            if(strpos($vo['Field'],'time')){
                $header[$comment] = 'datetime';
            }elseif(strpos($vo['Field'],'price')){
                $header[$comment] = 'price';
            }else{
                $header[$comment] = 'string';
            }
        }
        //var_export($header);die;
        $writer = new \XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header );
        //创建文件夹
        $zipFilePath = '/app/public/download/businessOrder'.$taskInfo['relation_id'];
        if(!is_dir($zipFilePath)){
            mkdir($zipFilePath);
        }

        do{
            $startTime = date('Y-m-d H:i:s',strtotime($taskContent['start_time'])+$interval_time*$loopNum);
            $stopTime = date('Y-m-d H:i:s',strtotime($taskContent['start_time'])+$interval_time*($loopNum+1));
            if($stopTime > $taskContent['end_time']){
                $stopTime = $taskContent['end_time'];
            }

            echo '========================='."\r\n";
            echo 'export loop number is : '.$loopNum."\r\n";
            echo 'starttime: '.$startTime."\r\n";
            echo 'stoptime: '.$stopTime."\r\n";
            echo "\r\n";
            //$output->writeln(date('Y-m-d H:i:s').' :day loop');


            $pageNum = 0;
            do {
                //$output->writeln(date('Y-m-d H:i:s').' :get data time');
                $orderList = $this->orderModelObj->where('tk_create_time','>=',$startTime)
                    ->where('tk_create_time','<',$stopTime)
                    ->limit(100000*$pageNum,100000)
                    ->order('id', 'asc')
                    //->limit(1)
                    ->select();
                if($orderList->isEmpty()){
                    break;
                }
                $orderInfo = $orderList->toArray();
                foreach($orderInfo as $row){
                    $writer->writeSheetRow('Sheet1', $row );
                }
                $fileName = 'order'.time().$pageNum;
                $filePath = '/app/public/download/businessOrder'.$taskInfo['relation_id'].'/'.$fileName.'.xlsx';
                $writer->writeToFile($filePath);
                /*$fileName = 'order'.time().$pageNum;
                $filePath = '/app/public/download/businessOrder'.$taskInfo['relation_id'].'/'.$fileName.'.xls';
                Excel::exportData($orderInfo, $header, $fileName, 'xls', $filePath);*/
                $pageNum++;
            }
            while (!($orderList->isEmpty()));
            //自增到下一个时间段
            $loopNum++;
        }
        while($stopTime < $taskContent['end_time']);
        //任务执行完成，更新任务状态
        $this->modifyTaskStatus($taskInfo['id'],2);
        if(array_key_exists('lastData', $taskInfo) && $taskInfo['lastData'] == true){
            echo "start zip \r\n";
            $zip = new ZipArchive();
            $zipFileName = $zipFilePath.'.zip';
            $taskContent['file_url'] = 'https://www.childrendream.cn/download/businessOrder'.$taskInfo['relation_id'].'.zip';
            $this->modifyTaskStatus($taskInfo['relation_id'], 2, json_encode($taskContent));
            //增加钉钉提醒
            $this->sendDingDingMsg($taskInfo);
            //对当前任务导出的所有文档进行压缩
            if($zip->open($zipFileName, ZipArchive::CREATE)=== TRUE){
                echo "open zip \r\n";
                $this->addFileToZip($zipFilePath, $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
                $zip->close(); //关闭处理的zip文件
            }
            echo "zip end \r\n";
            //删除临时文件
            $this->deldir($zipFilePath);
        }
        echo date('Y-m-d H:i:s').' : task already execute !'."\r\n";
        echo "\r\n";
    }

    //修改任务状态
    protected function modifyTaskStatus($id,$status,$task_content=null){
        $taskInfo = [
            'task_status' => $status,
            'update_time' => date('Y-m-d H:i:s'),
        ];
        if($task_content != null){
            $taskInfo['task_content'] = $task_content;
        }
        $ret = $this->taskModelObj->where('id',$id)->update($taskInfo);
        return $ret;
    }

    /*
     * 获取订单信息
     */
    public function getOrderListInfo($parameter)
    {
        $c = new \TopClient;
        $c->appkey = '32012766';
        $c->secretKey = 'a585a4c0a95ea6a04eacf5573c4149f3';
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

    protected function updateOrderData($orderData)
    {
        if($orderData == null){
            return false;
        }
        foreach($orderData as $orderInfo){
            $row = $this->orderModelObj->where('trade_parent_id','=',$orderInfo['trade_parent_id'])
                ->find();
            if($row){
                $ret = $this->orderModelObj->where('trade_parent_id','=',$orderInfo['trade_parent_id'])
                    ->update($orderInfo);
            }else{
                $ret = $this->orderModelObj->insert($orderInfo);
            }
        }
        return $ret;
    }

    //将指定文件夹压缩
    protected function addFileToZip($path,$zip){
        $handler=opendir($path); //打开当前文件夹由$path指定。
        while(($filename=readdir($handler))!==false){
            if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
                    $this->addFileToZip($path."/".$filename, $zip);
                }else{ //将文件加入zip对象
                    $zip->addFile($path."/".$filename,$filename);
                }
            }
        }
        @closedir($handler);
    }

    //删除指定文件夹以及文件夹下的所有文件
    protected function deldir($dir) {
        //先删除目录下的文件：
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    deldir($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if(rmdir($dir)) {
            return true;
        } else {
            return false;
        }
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
