<?php

namespace app\admin\model;

include_once "/app/extend/dataokesdk/ApiSdk.php";
include_once "/app/extend/taobaosdk/TopSdk.php";

use app\common\model\TimeModel;
use think\facade\Db;

class MallTaolijinGoods extends TimeModel
{

    protected $name = "mall_taolijin_goods";

    protected $deleteTime = "delete_time";

    //appKey  必填
    protected $appKey = '5f31fec3029ce';
    //appSecret  必填
    protected $appSecret = 'fb59e893c9231a38f269ea257e09217e';
    //版本号  必填
    protected $version = 'v1.2.3';

    
    public function systemTaobaoAccount()
    {
        return $this->belongsTo('\app\admin\model\SystemTaobaoAccount', 'account_id', 'id');
    }

    
    public function getSystemTaobaoAccountList()
    {
        return \app\admin\model\SystemTaobaoAccount::column('name', 'id');
    }

    public function getCampaignTypeList()
    {
        return ['MKT'=>'营销','DX'=>'定向','LINK_EVENT'=>'鹊桥',];
    }

    public function getItemStatusList()
    {
        return ['1'=>'出售中','2'=>'已售完','0'=>'未出售',];
    }

    public function getModeList()
    {
        return ['1'=>'正常商品','2'=>'秒杀商品','3'=>'免单商品',];
    }

    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'启用',];
    }

    public function getGoodsInfo($item_id)
    {
        $c = new \CheckSign;
        //接口地址 必填
        $c->host = 'https://openapi.dataoke.com/api/goods/get-goods-details';
        //appKey  必填
        $c->appKey = $this->appKey;
        //appSecret  必填
        $c->appSecret = $this->appSecret;
        //版本号  必填
        $c->version = $this->version;
        //其他请求参数 根据接口文档需求选填
        $params['goodsId'] = $item_id;
        //$request = $c->request($parame,'POST'); //接口特别说明需要POST请求才使用
        $request = $c->request($params);

        $resultData = json_decode($request,true);
        if(array_key_exists('data', $resultData)){
            return $resultData['data'];
        }else{
            return [];
        }

    }

    /*
     * 根据链接解析商品ID
     * */
    public function analysisItemId($content)
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
        $request = $c->request($params);
        $resultData = json_decode($request,true);
        return $resultData;
    }

    /*public function getTaoBaoAccountInfo($account_id)
    {
        $systemAccountObj = new \app\admin\model\SystemTaobaoAccount();
        $accountArr =
        return $accountArr;
    }*/
    /*
     * 创建淘礼金接口
     */
    public function creatTaolijinGoods($account,$parameter)
    {
        $c = new \TopClient;
        $c->appkey = $account['appkey'];//27995746
        $c->secretKey = $account['appsecret'];//e0a04dc52507b3b15a92fc56759279fb
        $c->format = 'json';
        $adzoneArr = explode('_',$account['spread_id']);

        $req = new \TbkDgVegasTljCreateRequest;
        $req->setCampaignType($parameter['campaign_type']);
        $req->setAdzoneId($adzoneArr[3]);
        $req->setItemId($parameter['item_id']);
        $req->setTotalNum($parameter['total_num']);
        $req->setName("淘礼金来啦");
        $req->setUserTotalWinNumLimit($parameter['per_user_num']);
        $req->setSecuritySwitch("true");
        $req->setPerFace($parameter['per_face']);
        $req->setSendStartTime($parameter['send_start_time']);
        $req->setSendEndTime($parameter['send_end_time']);
        $req->setUseEndTime(substr($parameter['use_end_time'],0,10));//只需要日期参数
        $req->setUseEndTimeMode("2");
        $req->setUseStartTime(substr($parameter['use_start_time'],0,10));
        //$req->setSecurityLevel("1");
        $resp = $c->execute($req);
        $resultData = json_decode(json_encode($resp),true);
        //print_r($resultData);die;
        //$resultData = $this->rightData();
        return $resultData;
    }

    public function rightData()
    {
        return array(
            'result' => array(
                'model' => array(
                    'available_fee' => '37208.53',
                    'rights_id' => 'xw8fSERx3ISj4z5Umx3tLuyVLELrhzk%2B',
                    'send_url' => 'https://uland.taobao.com/taolijin/edetail?vegasCode=3ZRSVKF6&type=qtz&union_lens=lensId%3A2107a613_0640_174c4b6d68d_ae30%3Btraffic_flag%3Dlm',
                    'vegas_code' => 'GWQ8S3FC',
                ),
                'success' => 1,
            ),
            'request_id' => '5vmhun4l799x'
        );
    }

    public function errorData()
    {
        return array(
            'result' => array(
                'msg_code' => 'FAIL_CHECK_ITEM_DAILY_SEND_NUM_CHECK_ERROR',
                'msg_info' => '今日该商品淘礼金创建数已超上限，请您明日再试',
                'success' => ''
            ),
            'request_id' => 'uf2uhh08uf8s',
        );
    }

    /*
     * 生成口令接口
     */
    public function transferToPwd($account,$url)
    {
        $c = new \TopClient;
        $c->appkey = $account['appkey'];
        $c->secretKey = $account['appsecret'];
        $req = new \TbkTpwdCreateRequest;
        $req->setUserId("123");
        $req->setText("复制即可使用哦");
        $req->setUrl($url);
        //$req->setLogo();
        $req->setExt("{}");
        $resp = $c->execute($req);
        $resultData = json_decode(json_encode($resp),true);
        //var_dump($resultData);die;
        return $resultData;
    }

    /*
     * 获取店铺信息
     */
    public function getShopInfo($shopName)
    {
        $c = new \TopClient;
        $c->appkey = '27995746';//27995746
        $c->secretKey = 'e0a04dc52507b3b15a92fc56759279fb';//e0a04dc52507b3b15a92fc56759279fb
        $c->format = 'json';

        $req = new \TbkShopGetRequest;
        $req->setFields("user_id,shop_title,shop_type,seller_nick,pict_url,shop_url");
        $req->setQ($shopName);
        $resp = $c->execute($req);
        $resultData = json_decode(json_encode($resp),true);
        return $resultData;
    }


}