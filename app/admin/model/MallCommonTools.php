<?php

namespace app\admin\model;

include_once ROOT_PATH."extend/dataokesdk/ApiSdk.php";
include_once "/app/extend/taobaosdk/TopSdk.php";

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


}