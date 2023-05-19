<?php

namespace app\admin\model;

include_once ROOT_PATH."extend/dataokesdk/ApiSdk.php";
include_once "/app/extend/taobaosdk/TopSdk.php";
include_once "/app/extend/douyinsdk/autoload.php";

use app\common\model\TimeModel;

class MallCommonTools extends TimeModel
{

    protected $deleteTime = "delete_time";

    //appKey  必填
    protected $appKey = '5f31fec3029ce';
    //appSecret  必填
    protected $appSecret = 'fb59e893c9231a38f269ea257e09217e';
    //版本号  必填
    protected $version = 'v1.2.3';

    //抖音接口公共参数
    protected $douAppKey = '7216292262077072953';

    protected $douAppSecret = '769cf07c-0356-4e58-b789-1f0d0731beb2';

    protected $douHost = 'https://openapi-fxg.jinritemai.com';


    public function analysisSecret($content)
    {
        $c = new \CheckSign;
        //接口地址 必填
        $c->host = 'https://openapi.dataoke.com/api/tb-service/parse-content';
        //appKey  必填
        $c->appKey = $this->appKey;
        //appSecret  必填
        $c->appSecret = $this->appSecret;
        //版本号  必填
        $c->version = $this->version;
        //其他请求参数 根据接口文档需求选填
        $params['content'] = $content;
        //$request = $c->request($parame,'POST'); //接口特别说明需要POST请求才使用
        $request = $c->request($params);
        $resultData = json_decode($request,true);
        return $resultData;
    }

    public function analysisOnlySecret($content)
    {
        $c = new \CheckSign;
        //接口地址 必填
        $c->host = 'https://openapi.dataoke.com/api/tb-service/parse-taokouling';
        //appKey  必填
        $c->appKey = $this->appKey;
        //appSecret  必填
        $c->appSecret = $this->appSecret;
        //版本号  必填
        $c->version = $this->version;
        //其他请求参数 根据接口文档需求选填
        $params['content'] = $content;
        //$request = $c->request($parame,'POST'); //接口特别说明需要POST请求才使用
        $request = $c->request($params);
        $resultData = json_decode($request,true);
        return $resultData;
    }

    public function transferSecret($account, $url)
    {
        $taoLiJinModel = new \app\admin\model\MallTaolijinGoods();
        $secret = $taoLiJinModel->transferToPwd($account, $url);
        return $secret;
    }

    public function effectiveTransferLink($accountInfo, $itemId)
    {
        $c = new \CheckSign;
        //接口地址 必填
        $c->host = 'https://openapi.dataoke.com/api/tb-service/get-privilege-link';
        //appKey  必填
        $c->appKey = $this->appKey;
        //appSecret  必填
        $c->appSecret = $this->appSecret;
        //版本号  必填
        $c->version = $this->version;
        //其他请求参数 根据接口文档需求选填
        $params['goodsId'] = $itemId;
        $params['pid'] = $accountInfo['spread_id'];
        $request = $c->request($params);
        $resultData = json_decode($request,true);
        return $resultData;
    }


    /*
     * 获取订单信息
     */
    public function getOrderListInfo()
    {
        $c = new \TopClient;
        $c->appkey = '32012766';//27995746
        $c->secretKey = 'a585a4c0a95ea6a04eacf5573c4149f3';//e0a04dc52507b3b15a92fc56759279fb
        $c->format = 'json';

        $req = new \TbkCpOrderDetailsGetRequest;

        $req->setJumpType("1");
        $req->setPageSize("1000");
        $req->setQueryType("1");
        $req->setPageNo("1");
        $req->setStartTime("2020-12-01 12:00:00");
        $req->setPositionIndex("");
        $req->setEndTime("2020-12-01 15:00:00");
        //$req->setTkStatus("12");
        $resp = $c->execute($req);
        $resultData = json_decode(json_encode($resp),true);
        return $resultData;
    }

    /*
     * 获取淘礼金使用信息
     */
    public function getTaoLiJinOrderInfo($rights_id)
    {
        $c = new \TopClient;
        $c->appkey = '31201179';
        $c->secretKey = 'a453f1476f583b22df581e836b6e4aa6';
        $c->format = 'json';

        $req = new \TbkDgVegasTljInstanceReportRequest;

        $req->setRightsId($rights_id);
        $resp = $c->execute($req);
        $resultData = json_decode(json_encode($resp),true);
        return $resultData;
    }

    //抖音API接口
    //抖口令解析
    public function analysisDouCommand($token, $command){
        // 收集参数
        $appKey = $this->douAppKey; //  替换成你的app_key
        $appSecret = $this->douAppSecret; // 替换成你的app_secret
        $accessToken = $token; // 替换成你的access_token
        $host = $this->douHost;
        $method = 'buyin.commonShareCommandParse';
        $timestamp = time();
        $m = array(
            "command"=> $command,
        );
        // 序列化参数
        $paramJson = $this->marshal($m);
        // 计算签名
        $signVal = $this->sign($appKey, $appSecret, $method, $timestamp, $paramJson);
        // 发起请求
        $responseVal = $this->fetch($appKey, $host, $method, $timestamp, $paramJson, $accessToken, $signVal);
        return json_decode($responseVal, true);
    }

    //抖音活动转链
    public function activityTransfer($token, $pid, $material_id){
        // 收集参数
        $appKey = $this->douAppKey; //  替换成你的app_key
        $appSecret = $this->douAppSecret; // 替换成你的app_secret
        $accessToken = $token; // 替换成你的access_token
        $host = $this->douHost;
        $method = 'buyin.doukeActivityShare';
        $timestamp = time();
        $m = array(
            'material_id'=>$material_id,
            'need_qr_code'=>'false',
            "pid"=> $pid,
        );
        // 序列化参数
        $paramJson = $this->marshal($m);
        // 计算签名
        $signVal = $this->sign($appKey, $appSecret, $method, $timestamp, $paramJson);
        // 发起请求
        $responseVal = $this->fetch($appKey, $host, $method, $timestamp, $paramJson, $accessToken, $signVal);
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