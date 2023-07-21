<?php
declare (strict_types = 1);

namespace app\command;

include_once "/app/extend/xlsxwriter.class.php";

use app\admin\model\BusinessGoods;
use app\admin\model\BusinessOrder;
use app\admin\model\SystemAdmin;
use app\admin\model\SystemScriptTask;
use EasyAdmin\tool\CommonTool;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;
use ZipArchive;

class ExportOrderList extends Command
{
    protected $taskModelObj;

    protected $orderModelObj;

    protected $goodsModelObj;

    protected $adminModelObj;

    protected function configure()
    {
        // 指令配置
        // 每分钟执行一次
        $this->setName('exportOrderList')
            ->setDescription('定时导出任务订单数据');
    }

    protected function execute(Input $input, Output $output)
    {
    	// 指令输出
        $output->writeln(date('Y-m-d H:i:s').' :开始执行定时导出订单任务!');

        if(!($this->orderModelObj instanceof BusinessOrder)){
            $this->orderModelObj = new \app\admin\model\BusinessOrder();
        }
        if(!($this->goodsModelObj instanceof BusinessGoods)){
            $this->goodsModelObj = new \app\admin\model\BusinessGoods();
        }
        if(!($this->adminModelObj instanceof SystemAdmin)){
            $this->adminModelObj = new \app\admin\model\SystemAdmin();
        }
        $taskInfo = $this->getTaskInfo();
        if(!$taskInfo){
            $output->writeln(date('Y-m-d H:i:s').' :没有可执行的导出订单任务！');
            return false;
        }
        $ret = $this->exportOrderData($taskInfo);
        $output->writeln(date('Y-m-d H:i:s').' :任务执行完成！');
        return true;
    }

    //获取任务表数据
    protected function getTaskInfo()
    {
        if(!($this->taskModelObj instanceof SystemScriptTask)){
            $this->taskModelObj = new \app\admin\model\SystemScriptTask();
        }
        //查询任务表中未开始或执行中的导出任务
        $taskInfo = $this->taskModelObj
            ->where('type',2)
            ->where('relation_id', '=', 0)
            ->where('task_status','<>',2)
            ->order('id','asc')
            ->select()->toArray();
        if(!$taskInfo){
            return [];
        }
        //判断第一条任务数据是处于执行中还是未执行
        //若处于执行中，停止运行脚本，若未执行则修改其状态为执行中并执行该任务
        if($taskInfo[0]['task_status'] != 0){
            //2022-06-01追加判断：若长时间任务状态为执行中发送警告
            $diffTime = time()-strtotime($taskInfo[0]['create_time']);
            if($diffTime >= 60*30){
                $this->sendWarningMsg();
            }
            return [];
        }
        //修改任务状态
        $this->modifyTaskStatus($taskInfo[0]['id'],1);
        return $taskInfo[0];
    }

    //将数据导出到excel文件并压缩
    protected function exportOrderData($taskInfo){
        $taskContent = json_decode($taskInfo['task_content'], true);
        //开始循环获取时间段内数据
        $interval_time = 3600*24;
        $loopNum = 0;
        //处理excel文件头部信息
        $tableName = $this->orderModelObj->getName();
        $tableName = CommonTool::humpToLine(lcfirst($tableName));
        $prefix = config('database.connections.mysql.prefix');
        $dbList = Db::query("show full columns from {$prefix}{$tableName}");
        $header = [
            '创建时间' => 'datetime',
            '点击时间' => 'datetime',
            '商品信息' => 'string',
            '商品ID' => 'string',
            '掌柜旺旺' => 'string',
            '所属店铺' => 'string',
            '商品数' => 'string',
            '商品单价' => 'price',
            '订单状态' => 'string',
            '服务费率' => 'string',
            '付款金额' => 'price',
            '预估付款服务费' => 'string',
            '付款时间' => 'datetime',
            '结算时间' => 'datetime',
            '结算金额' => 'price',
            '预估结算服务费' => 'string',
            '淘宝订单编号' => 'string',
            '淘宝子订单编号' => 'string',
            '活动id' => 'string',
            '定金付款时间' => 'string',
            '定金淘宝付款时间' => 'string',
            '定金付款金额' => 'price',
            '招商渠道ID' => 'string',
            '招商渠道名称' => 'string',
            '推广者ID' => 'string',
            '推广者昵称' => 'string',
            '佣金类型' => 'string',
            '营销类型' => 'string',
            '产品类型' => 'string',
            '推广名称' => 'string'
        ];
        $templateArr = [
            'tk_create_time'=>'',
            'click_time'=>'',
            'item_title'=>'',
            'item_id'=>'',
            'seller_nick'=>'',
            'seller_shop_title'=>'',
            'item_num'=>'',
            'item_price'=>'',
            'tk_status'=>'',
            'tk_service_rate'=>'',
            'alipay_total_price'=>'',
            'pre_service_fee'=>'',
            'tk_paid_time'=>'',
            'tk_earning_time'=>'',
            'pay_price'=>'',
            'service_fee'=>'',
            'trade_parent_id'=>'',
            'trade_children_id'=>'',
            'event_id'=>'',
            'tk_deposit_time'=>'',
            'tb_deposit_time'=>'',
            'deposit_price'=>'',
            'cp_channel_id'=>'',
            'cp_channel_name'=>'',
            'pub_id'=>'',
            'pub_nick_name'=>'',
            'commission_type'=>'',
            'marketing_type'=>'',
            'product_type'=>'',
            'pub_name'=>''
        ];

        foreach ($dbList as $vo) {
            $comment = !empty($vo['Comment']) ? $vo['Comment'] : $vo['Field'];
            //$header[] = [$comment, $vo['Field']];
            if($vo['Field'] == 'tk_deposit_time' || $vo['Field'] == 'tb_deposit_time'){
                $header[$comment] = 'string';
            }elseif(strpos($vo['Field'],'time')){
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
        $zipFilePath = '/app/public/download/businessOrder'.$taskInfo['id'];
        if(!is_dir($zipFilePath)){
            mkdir($zipFilePath);
        }
        //echo "读取导出的数据 \r\n";
        //判断条件中是否存在审核人信息
        if($taskContent['where']){
            $isTimeLimit = false;
            $isShopTitle = false;
            $goodsTitle = false;
            foreach($taskContent['where'] as $key => $option){
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
                }
            }
            if(!$isTimeLimit){
                echo "请增加时间搜索条件 \r\n";
                return false;
            }
            //无管理员权限不能搜索店铺，且必须是自己认领过的商品订单
            if($taskContent['auth_ids'] != 7 && $taskContent['auth_ids'] != 1){
                if($isShopTitle){
                    echo "请使用单品搜索查询，暂无权限使用店铺查询 \r\n";
                    return false;
                }
                if($goodsTitle){
                    $goodsInfo = $this->goodsModelObj->where('auditorId',$taskInfo['creater_id'])
                        ->where('title','like',$goodsTitle)
                        ->where('startTime', '<=', $startTime)
                        ->where('endTime', '>=', $stopTime)
                        ->limit(1)
                        ->field('auditorId')//->fetchSql()
                        ->select()
                        ->toArray();
                    //var_export($goodsInfo);die;
                    if($goodsInfo == null){
                        echo "请搜索自己认领过的商品 \r\n";
                        return false;
                    }
                }
            }
        }else{
            $defaultStartTime = date('Y-m-d').' 00:00:00';
            $defaultStopTime = date('Y-m-d').' 23:59:59';
            $taskContent['where'] = [[
                0 => 'tk_create_time',
                1 => '>=',
                2 => $defaultStartTime,
            ],[
                0 => 'tk_create_time',
                1 => '<=',
                2 => $defaultStopTime,
            ]];
        }

        $pageNum = 0;
        $pageSize = 5000;
        $orderStatus = [
            '3' => '订单结算',
            '12' => '订单付款',
            '13' => '订单失效',
            '14' => '订单成功',
        ];
        //分批读取并写入数据到文件中
        do{
            $dataNum = 0;
            $orderList = $this->orderModelObj->where($taskContent['where'])
                ->page($pageNum*$pageSize, $pageSize)
                ->field(['tk_create_time','click_time','item_title','item_id','seller_nick','seller_shop_title','item_num',
                    'item_price','tk_status','tk_service_rate','alipay_total_price','pre_service_fee','	tk_paid_time','tk_earning_time',
                    'pay_price','service_fee','trade_parent_id','event_id','tk_deposit_time',
                    'tb_deposit_time','deposit_price','cp_channel_id','cp_channel_name','pub_id','pub_nick_name',
                    'marketing_type'])
                ->order('id', 'asc')
                ->select();
            if($orderList->isEmpty()){
                echo "数据为空 \r\n";
                break;
            }
            $orderInfo = $orderList->toArray();

            //echo "读取数据完毕开始写入 \r\n";
            foreach($orderInfo as $row){
                $row['tk_status'] = $orderStatus[$row['tk_status']];
                $newRow = array_merge($templateArr, $row);
                $writer->writeSheetRow('Sheet1', $newRow );
                $dataNum++;
            }
            $pageNum++;
        }while($dataNum>=$pageSize);

        $fileName = 'order'.time();
        $filePath = '/app/public/download/businessOrder'.$taskInfo['id'].'/'.$fileName.'.xlsx';
        $writer->writeToFile($filePath);

        //任务执行完成，更新任务状态
        echo "start zip \r\n";
        $zip = new ZipArchive();
        $zipFileName = $zipFilePath.'.zip';
        $taskContent['file_url'] = 'https://www.childrendream.cn/download/businessOrder'.$taskInfo['id'].'.zip';
        $this->modifyTaskStatus($taskInfo['id'], 2, json_encode($taskContent));
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
        echo date('Y-m-d H:i:s').' : task already execute !'."\r\n";
        echo "\r\n";
        return true;
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

    //钉钉警告提醒
    public function sendWarningMsg()
    {
        $message = "注意！！！有任务疑似处于异常状态，赶快屁颠儿去解决掉问题，ps（解决不掉问题就去解决掉搞出问题的人）";
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
