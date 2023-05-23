<?php

$parameter = file_get_contents('php://input');
$headerInfo = getServerInfo();
//var_export($headerInfo['x-gitee-token']);
//var_export(json_decode($parameter,true));//die;
write_log('/tmp/20230523.log', $parameter);
if($headerInfo['x-gitee-token'] == '12345678'){
    $ret = pullCode();
    write_log('/tmp/20230523.log', json_encode($ret));
}


function pullCode(){
    //$shell = "cd /app/ && sudo git pull origin master >> /app/runtime/log/`date -d 'today' +\%Y\%m\%d`.log 2>&1";
    $shell = "cd /app/ && git fetch -all && git reset --hard origin/master && git pull origin/master 2>&1";
    exec($shell, $output);
    return $output;
}

function getServerInfo(){
    $headers = [];
    foreach($_SERVER as $key=>$value){
        if(substr($key, 0, 5)==='HTTP_'){
            $key = substr($key, 5);
            $key = str_replace('_', ' ', $key);
            $key = str_replace(' ', '-', $key);
            $key = strtolower($key);
            $headers[$key] = $value;
        }
    }
    return $headers;
}

function write_log($filename, $content){
    $newContent = date("Y-m-d H:i:s")." ".$content."\r\n";
    file_put_contents($filename,$newContent,FILE_APPEND);
    return true;
}
//钉钉提醒
function sendDingDingMsg()
{
    //获取任务信息
    $name = '';
    $message = "Hello,".$name.",你的任务《》已完成了哦，赶快处理吧！";
    $webhook = "https://oapi.dingtalk.com/robot/send?access_token=d61f7604aa6dfc87d2052d19ed4b29d24463ffdbfe6257068e4e6fad0cf1d4c5";
    $data = array ('msgtype' => 'text','text' => array ('content' => $message));
    $data_string = json_encode($data);
    $result = request_by_curl($webhook, $data_string);
    return $result;
}

function request_by_curl($remote_server, $post_string) {
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



