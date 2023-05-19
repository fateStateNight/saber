<?php
namespace app\admin\model;

include_once "/app/extend/douyinsdk/autoload.php";
include_once "/app/extend/douyinsdk/open/core/GlobalConfig.php";

use app\common\model\TimeModel;

class IntegralDouorder extends TimeModel
{
    protected $appKey;

    protected $appSecret;

    protected $requestHost;

    public function getRewardOrder($token, $pId, $startTime, $endTime, $page=0,$pageSize=200){
        // 收集参数
        $appKey = '7216292262077072953'; //  替换成你的app_key
        $appSecret = '769cf07c-0356-4e58-b789-1f0d0731beb2'; // 替换成你的app_secret
        $accessToken = $token; // 替换成你的access_token
        $host = 'https://openapi-fxg.jinritemai.com';
        $method = 'buyin.doukeRewardOrders';
        $timestamp = time();
        $m = array(
            "page"=> $page,
            "page_size"=> $pageSize,
            "start_date"=> $startTime,
            "end_date"=> $endTime,
            "pid" => $pId,
            //"order_ids"=>"",
            //"pid"=>"",
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

}