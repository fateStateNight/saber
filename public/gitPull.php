<?php

$parameter = $_POST;
var_dump(123123);
var_export($parameter);die;
//write_log('/tmp/cookies.log', $content);


function pullCode(){
    $shell = "cd /app/ && sudo git pull origin master >> /app/runtime/log/`date -d 'today' +\%Y\%m\%d`.log 2>&1";
    exec($shell, $output);
    return true;
}

function getAllHeaders(){
    $headers = array();
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




