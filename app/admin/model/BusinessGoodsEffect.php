<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class BusinessGoodsEffect extends TimeModel
{

    protected $name = "business_goods_effect";



    //同步商品每日推广数据到数据库中
    public function sycGoodsPublishEffectInfoToDB($accountInfo, $goodsInfo)
    {
        if(!$goodsInfo){
            return false;
        }

        foreach($goodsInfo as $goodsArr){
            $startDate = date("Y-m-d", strtotime($goodsArr['startTime']));
            $endDate = date("Y-m-d", strtotime($goodsArr['endTime']));
            $urlArr = $this->getParams($goodsArr['auctionUrl']);
            $goodsEffectInfo = $this->getGoodsEveryDayEffectInfo($accountInfo['token'], $accountInfo['cookies'],$startDate,$endDate, $urlArr['id'],$goodsArr['businessScene']['groupId']);
            //var_export($goodsEffectInfo);die;
            if($goodsEffectInfo['success'] != 'true' || $goodsEffectInfo['data'] == null){
                continue;
            }
            $delRet = $this->where('goods_id',$goodsArr['id'])->delete();
            //echo $goodsArr['id']." 店铺名称：".$goodsArr['title'].$delRet." \r\n";
            $goodsData = [];
            foreach($goodsEffectInfo['data'] as $dataValue){
                $goodsData[] = [
                    'goods_id' => $goodsArr['id'],
                    'publish_date' => $dataValue['theDateTime'],
                    'enterShopUvTk' => $dataValue['enterShopUvTk'],
                    'alipayAmt' => $dataValue['alipayAmt'],
                    'paymentTkNum' => $dataValue['paymentTkNum'],
                    'alipayNum' => $dataValue['alipayNum'],
                    'cpPreServiceShareFee' => $dataValue['cpPreServiceShareFee'],
                    'cpsSettleAmt' => $dataValue['cpsSettleAmt'],
                    'cpsSettleNum' => $dataValue['cpsSettleNum'],
                    'cpCmServiceShareFee' => $dataValue['cpCmServiceShareFee'],
                    'preCommissionFee' => $dataValue['preCommissionFee'],
                    'cmCommissionFee' => $dataValue['cmCommissionFee'],
                    'update_time' => date('Y-m-d H:i:s'),
                ];
            }
            $ret = $this->strict(false)->insertAll($goodsData);
        }
        return true;
    }


    //获取商品每日推广数据
    public function getGoodsEveryDayEffectInfo($token, $cookies, $startDate, $endDate, $itemId, $groupId)
    {
        //获取账号cookie信息

        $timeStr = $this->msectime();
        $url = 'https://fuwu.alimama.com/openapi/param2/1/gateway.unionpub/event.item.report.daytrend.json?t='.$timeStr.'&_tb_token_='.$token
            .'&startDate='.$startDate.'&endDate='.$endDate.'&itemId='.$itemId.'&groupId='.$groupId;

        //执行请求获取数据
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //https调用
        //curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $header = [
            'Content-Type: application/json'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
        $output = curl_exec($ch);
        $a = curl_error($ch);
        if(!empty($a)){
            return json_encode(array('code'=>-10003,'msg'=>$a));
        }
        curl_close($ch);
        $result = json_decode($output,true);
        $result = $result?$result:[];
        return $result;
    }



    //返回当前的毫秒时间戳
    public function msectime()
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }

    public function write_log($filename, $content){
        $newContent = date("Y-m-d H:i:s")." ".$content."\r\n";
        file_put_contents($filename,$newContent,FILE_APPEND);
        return true;
    }

    //处理链接参数信息
    public function getParams($url)
    {
        $refer_url = parse_url($url);
        $params = $refer_url['query'];
        $arr = array();
        if(!empty($params))
        {
            $paramsArr = explode('&',$params);
            foreach($paramsArr as $k=>$v)
            {
                $a = explode('=',$v);
                $arr[$a[0]] = $a[1];
            }
        }
        return $arr;
    }

}