<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class BusinessGoods extends TimeModel
{

    protected $name = "business_goods";

    protected $deleteTime = false;

    
    public function businessScene()
    {
        return $this->belongsTo('\app\admin\model\BusinessScene', 'eventId', 'eventId');
    }

    
    public function getAuditStatusList()
    {
        return ['0'=>'所有商品状态','1'=>'待团长审核','2'=>'通过','3'=>'拒绝','5'=>'取消报名','7'=>'超级U选通过'];
    }

    public function getSystemAdminList()
    {
        return \app\admin\model\SystemAdmin::column('nickname', 'id');
    }

    /*
     * 同步报名活动商品数据到数据库中
     *
     * */
    public function sycEventGoodsInfoToDB($eventInfo)
    {
        if(!$eventInfo){
            return false;
        }
        foreach($eventInfo as $eventArr){
            if(!$eventArr['token'] || !$eventArr['cookies'] || !$eventArr['eventData']){
                continue;
            }
            //$page = 1;
            foreach($eventArr['eventData'] as $eventData){
                if(!$eventData['eventId']){
                    continue;
                }
                //判断是否有下一页
                $update_time = time();
                $hasNext = false;
                $page = 1;
                $isUpdateData = false;
                do{
                    $goodsInfo = $this->getItemInfo($eventArr['token'], $eventArr['cookies'], $eventData['eventId'], $page);
                    //var_export($goodsInfo);die;
                    //判断获取数据是否异常
                    if(!$goodsInfo || !array_key_exists('data',$goodsInfo)){
                        break;
                    }
                    if($goodsInfo['data'] == '' || $goodsInfo['ok'] != 'true'){
                        break;
                    }
                    $hasNext = $goodsInfo['data']['hasNext'];
                    if(array_key_exists('result',$goodsInfo['data']) && $goodsInfo['data']['result'] != ''){
                        //处理商品数据
                        foreach($goodsInfo['data']['result'] as $result){
                            $eventGoodsData = [
                                'eventId' => $eventData['eventId'],
                                'itemId' => $result['itemId'],
                                'sellerId' => $result['sellerId'],
                                'shopTitle' => $result['shopTitle'],
                                'title' => $result['title'],
                                'auctionUrl' => 'https:'.$result['auctionUrl'],
                                'pictUrl' => 'https:'.$result['pictUrl'],
                                'zkFinalPrice' => $result['zkFinalPrice'],
                                'couponZkFinalPrice' => $result['couponZkFinalPrice'],
                                'biz30day' => $result['biz30day'],
                                'auditStatus' => $result['auditStatus'],
                                'commissionRate' => $result['commissionRate'],
                                'serviceRate' => $result['serviceRate'],
                                'couponChannelName' => $result['couponChannelName'],
                                'couponInfo' => $result['couponInfo'],
                                'couponLeftCount' => $result['couponLeftCount'],
                                'couponTotalCount' => $result['couponTotalCount'],
                                'couponEffectiveStartTime' => $result['couponEffectiveStartTime'],
                                'couponEffectiveEndTime' => $result['couponEffectiveEndTime'],
                                'startTime' => $result['startTime'],
                                'endTime' => $result['endTime'],
                                'updateTime' => $update_time,
                            ];
                            $urlArr = $this->getParams($eventGoodsData['auctionUrl']);
                            if(strpos($urlArr['id'],'-') !== false){
                                $itemArr = explode('-',$urlArr['id']);
                                $eventGoodsData['mktItemId'] = $itemArr[1];
                            }
                            $dataResult = $this->where('eventId',$eventData['eventId'])
                                ->where('itemId', $result['itemId'])
                                ->field(['id',])->find();
                            if($dataResult){
                                BusinessGoods::update($eventGoodsData, ['id' => $dataResult['id']]);
                            }else{
                                BusinessGoods::create($eventGoodsData);
                            }
                        }
                        $isUpdateData = true;
                    }else{
                        $hasNext = false;
                    }
                    //自增分页数
                    $page++;
                }while($hasNext);
                //检查商品数据是否需要删除
                if($isUpdateData){
                    $retNum = $this->where('eventId',$eventData['eventId'])
                        ->where('auditStatus','=', '1')
                        ->whereRaw('`updateTime` IS NULL or `updateTime` <> :update_time', ['update_time'=>$update_time])
                        ->delete();
                    $this->write_log('/tmp/sycGoodsData.log','活动ID：'.$eventData['eventId'].' 删除数据结果:'.$retNum);
                }
            }
        }
        return true;
    }

    /*
     * 同步报名活动商品的效果数据
     *
     *
     * */
    public function sycEventGoodsEffectToDB($eventInfo)
    {
        if(!$eventInfo){
            return false;
        }
        foreach($eventInfo as $eventArr){
            if(!$eventArr['token'] || !$eventArr['cookies'] || !$eventArr['eventData']){
                continue;
            }
            foreach($eventArr['eventData'] as $eventData){
                if(!$eventData['eventId']){
                    continue;
                }
                //判断是否有下一页
                $hasNext = false;
                $page = 1;
                do{
                    $goodsEffectInfo = $this->getItemEffectInfo($eventArr['token'], $eventArr['cookies'], $eventData['eventId'], $page);
                    //判断获取数据是否异常
                    if(!$goodsEffectInfo || !array_key_exists('data',$goodsEffectInfo)){
                        break;
                    }
                    if($goodsEffectInfo['data'] == '' || $goodsEffectInfo['ok'] != 'true'){
                        break;
                    }
                    $hasNext = $goodsEffectInfo['data']['hasNext'];
                    if(array_key_exists('result',$goodsEffectInfo['data']) && $goodsEffectInfo['data']['result'] != ''){
                        //处理商品数据
                        foreach($goodsEffectInfo['data']['result'] as $result){
                            $eventGoodsEffectData = [
                                'auditStatus' => $result['auditStatus'],
                                'clickUv' => $result['clickUv'],
                                'couponUseNum' => $result['couponUseNum'],
                                'alipayAmt' => $result['alipayAmt'],
                                'alipayNum' => $result['alipayNum'],
                                'preServiceFee' => $result['preServiceFee'],
                                'settleAmt' => $result['settleAmt'],
                                'settleNum' => $result['settleNum'],
                                'cmServiceFee' => $result['cmServiceFee'],
                                'taokeNum' => $result['taokeNum'],
                            ];
                            $dataResult = $this->where('eventId',$eventData['eventId'])
                                ->where('itemId', $result['itemId'])
                                ->field(['id',])->find();
                            if($dataResult){
                                BusinessGoods::update($eventGoodsEffectData, ['id' => $dataResult['id']]);
                            }
                            /*else{
                                BusinessGoods::create($eventGoodsEffectData);
                            }*/
                        }
                    }else{
                        $hasNext = false;
                    }
                    //自增分页数
                    $page++;
                }while($hasNext);
            }
        }
        return true;
    }

    /*
     * 同步新版报名活动商品数据到数据库中
     *
     * */
    public function sycNewEventGoodsInfoToDB($eventInfo)
    {
        if(!$eventInfo){
            return false;
        }
        foreach($eventInfo as $eventArr){
            if(!$eventArr['token'] || !$eventArr['cookies'] || !$eventArr['eventData']){
                continue;
            }
            //$page = 1;
            foreach($eventArr['eventData'] as $eventData){
                if(!$eventData['eventId']){
                    continue;
                }
                //判断是否有下一页
                $update_time = time();
                $hasNext = false;
                $page = 1;
                $isUpdateData = false;
                //echo $eventData['eventId']."\r\n";
                do{
                    //$goodsInfo = $this->getNewItemInfo($eventArr['token'], $eventArr['cookies'], $eventData['sceneId'] ,$eventData['eventId'], $page);
                    $goodsInfo = $this->getNewItemInfoV2($eventArr['token'], $eventArr['cookies'],$eventData['eventId'],$page,0);
                    //var_export($goodsInfo);die;
                    //判断获取数据是否异常
                    if(!$goodsInfo || !array_key_exists('data',$goodsInfo)){
                        $this->write_log('/tmp/sycGoodsData.log','活动ID：'.$eventData['eventId'].' 请求数据结果:'.json_encode($goodsInfo));
                        break;
                    }
                    if($goodsInfo['data'] == '' || $goodsInfo['success'] != 'true'){
                        break;
                    }
                    $hasNext = $goodsInfo['data']['hasNext'];
                    if(array_key_exists('result',$goodsInfo['data']) && $goodsInfo['data']['result'] != ''){
                        //处理商品数据
                        foreach($goodsInfo['data']['result'] as $result){
                            $eventGoodsData = [
                                'eventId' => $eventData['eventId'],
                                //'sellerId' => $result['resource'][0]['itemInfoDTO']['sellerId'],
                                'signUpRecordId' => $result['signUpRecordId'],
                                'shopTitle' => array_key_exists('shopTitle',$result['resource'][0]['itemInfoDTO'])?$result['resource'][0]['itemInfoDTO']['shopTitle']:'',
                                'title' => $result['resource'][0]['itemInfoDTO']['title'],
                                'auctionUrl' => 'https:'.$result['resource'][0]['itemInfoDTO']['auctionUrl'],
                                'pictUrl' => 'https:'.$result['resource'][0]['itemInfoDTO']['pictUrl'],
                                'zkFinalPrice' => $result['resource'][0]['itemInfoDTO']['zkFinalPrice'],
                                'couponZkFinalPrice' => $result['resource'][0]['itemInfoDTO']['couponZkFinalPrice'],
                                'biz30day' => $result['resource'][0]['itemInfoDTO']['biz30day'],
                                'auditStatus' => $result['showStatus'],
                                'commissionRate' => $result['advertisingUnit']['commissionRate'],
                                'serviceRate' => $result['advertisingUnit']['serviceRate'],
                                /*'currentMaxCommissionRate' => $result['currentMaxMktCommissionRate'],
                                'couponChannelName' => $result['couponChannelName'],
                                'couponInfo' => $result['couponInfo'],
                                'couponLeftCount' => $result['couponLeftCount'],
                                'couponTotalCount' => $result['couponTotalCount'],
                                'couponEffectiveStartTime' => $result['couponEffectiveStartTime'],
                                'couponEffectiveEndTime' => $result['couponEffectiveEndTime'],*/
                                'startTime' => $result['startTime'],
                                'endTime' => $result['endTime'],
                                'updateTime' => $update_time,
                            ];
                            //将取消报名的数据过滤掉
                            if($result['showStatus'] == 5){
                                continue;
                            }
                            //$testRet = $this->getUrlLocationResult($eventGoodsData['auctionUrl'],$eventArr['cookies']);
                            //$testRet = $this->get_location($eventGoodsData['auctionUrl'],$eventArr['cookies']);
                            //var_export($eventGoodsData);die;
                            if(array_key_exists('commissionHint',$result)){
                                $eventGoodsData['commissionHint'] = $result['commissionHint'];
                            }
                            if(array_key_exists('commissionHintDesc',$result)){
                                $eventGoodsData['commissionHintDesc'] = str_replace(['[',']','"'],'',$result['commissionHintDesc']);
                            }
                            if($result['commissionHintStatus'] == 1){
                                $eventGoodsData['commissionHint'] = '';
                                $eventGoodsData['commissionHintDesc'] = '';
                            }
                            if(array_key_exists('currentMaxMktCommissionRate',$result)){
                                $eventGoodsData['currentMaxCommissionRate'] = $result['currentMaxMktCommissionRate'];
                            }
                            $urlArr = $this->getParams($eventGoodsData['auctionUrl']);
                            if(strpos($urlArr['id'],'-') !== false){
                                $itemArr = explode('-',$urlArr['id']);
                                $eventGoodsData['mktItemId'] = $itemArr[1];
                            }
                            //2023-02-06 解决获取报名商品信息中没有商品ID的问题，通过新版商品ID去搜索查询
                            if(array_key_exists('itemId', $result['resource'][0]['itemInfoDTO'])){
                                $eventGoodsData['itemId'] = $result['resource'][0]['itemInfoDTO']['itemId'];
                                $dataResult = $this->where('eventId',$eventData['eventId'])
                                    ->where('itemId', $result['resource'][0]['itemInfoDTO']['itemId'])
                                    ->field(['id'])->find();
                            }else{
                                $eventGoodsData['itemId'] = $result['resource'][0]['itemInfoDTO']['mktItemId'];
                                $dataResult = $this->where('eventId',$eventData['eventId'])
                                    ->where('mktItemId', $eventGoodsData['mktItemId'])
                                    ->field(['id'])->find();
                            }
                            if($dataResult){
                                BusinessGoods::update($eventGoodsData, ['id' => $dataResult['id']]);
                            }else{
                                BusinessGoods::create($eventGoodsData);
                            }
                        }
                        $isUpdateData = true;
                    }else{
                        $hasNext = false;
                    }
                    //自增分页数
                    $page++;
                    sleep(1);
                }while($hasNext);
                //检查商品数据是否需要删除
                if($isUpdateData){
                    $retNum = $this->where('eventId',$eventData['eventId'])
                        ->where('auditStatus','=', '1')
                        ->whereRaw('`updateTime` IS NULL or `updateTime` <> :update_time', ['update_time'=>$update_time])
                        ->delete();
                    $this->write_log('/tmp/sycGoodsData.log','活动ID：'.$eventData['eventId'].' 删除数据结果:'.$retNum);
                }
            }
        }
        return true;
    }



    /*
     * 同步新版报名活动商品数据到数据库中
     *
     * */
    public function sycNewEventGoodsInfoToDBV2($eventInfo)
    {
        if(!$eventInfo){
            return false;
        }
        foreach($eventInfo as $eventArr){
            if(!$eventArr['token'] || !$eventArr['cookies'] || !$eventArr['eventData']){
                continue;
            }
            //判断是否有下一页
            $update_time = time();
            $hasNext = false;
            $page = 1;
            do{
                $goodsInfo = $this->getNewItemInfoV2($eventArr['token'], $eventArr['cookies'], '', $page);
                //var_export($goodsInfo);echo "\r\n";
                //判断获取数据是否异常
                if(!$goodsInfo || !array_key_exists('data',$goodsInfo)){
                    $this->write_log('/tmp/sycGoodsData.log','同步所有报名商品信息：'.json_encode($goodsInfo));
                    break;
                }
                if($goodsInfo['data'] == '' || $goodsInfo['success'] != 'true'){
                    break;
                }
                $hasNext = $goodsInfo['data']['hasNext'];
                if(array_key_exists('result',$goodsInfo['data']) && $goodsInfo['data']['result'] != ''){
                    //处理商品数据
                    foreach($goodsInfo['data']['result'] as $result){
                        $eventGoodsData = [
                            'eventId' => $result['campaignId'],
                            //'sellerId' => $result['resource'][0]['itemInfoDTO']['sellerId'],
                            'signUpRecordId' => $result['signUpRecordId'],
                            'shopTitle' => $result['resource'][0]['itemInfoDTO']['shopTitle'],
                            'title' => $result['resource'][0]['itemInfoDTO']['title'],
                            'auctionUrl' => 'https:'.$result['resource'][0]['itemInfoDTO']['auctionUrl'],
                            'pictUrl' => 'https:'.$result['resource'][0]['itemInfoDTO']['pictUrl'],
                            'zkFinalPrice' => $result['resource'][0]['itemInfoDTO']['zkFinalPrice'],
                            'couponZkFinalPrice' => $result['resource'][0]['itemInfoDTO']['couponZkFinalPrice'],
                            'biz30day' => $result['resource'][0]['itemInfoDTO']['biz30day'],
                            'auditStatus' => $result['showStatus'],
                            'commissionRate' => $result['advertisingUnit']['commissionRate'],
                            'serviceRate' => $result['advertisingUnit']['serviceRate'],
                            /*'currentMaxCommissionRate' => $result['currentMaxMktCommissionRate'],
                            'couponChannelName' => $result['couponChannelName'],
                            'couponInfo' => $result['couponInfo'],
                            'couponLeftCount' => $result['couponLeftCount'],
                            'couponTotalCount' => $result['couponTotalCount'],
                            'couponEffectiveStartTime' => $result['couponEffectiveStartTime'],
                            'couponEffectiveEndTime' => $result['couponEffectiveEndTime'],*/
                            'startTime' => $result['startTime'],
                            'endTime' => $result['endTime'],
                            'updateTime' => $update_time,
                        ];
                        //将取消报名的数据过滤掉
                        if($result['showStatus'] == 5){
                            continue;
                        }
                        if(array_key_exists('commissionHint',$result)){
                            $eventGoodsData['commissionHint'] = $result['commissionHint'];
                        }
                        if(array_key_exists('commissionHintDesc',$result)){
                            $eventGoodsData['commissionHintDesc'] = str_replace(['[',']','"'],'',$result['commissionHintDesc']);
                        }
                        if($result['commissionHintStatus'] == 1){
                            $eventGoodsData['commissionHint'] = '';
                            $eventGoodsData['commissionHintDesc'] = '';
                        }
                        if(array_key_exists('currentMaxMktCommissionRate',$result)){
                            $eventGoodsData['currentMaxCommissionRate'] = $result['currentMaxMktCommissionRate'];
                        }
                        $urlArr = $this->getParams($eventGoodsData['auctionUrl']);
                        if(strpos($urlArr['id'],'-') !== false){
                            $itemArr = explode('-',$urlArr['id']);
                            $eventGoodsData['mktItemId'] = $itemArr[1];
                        }
                        //2023-02-06 解决获取报名商品信息中没有商品ID的问题，通过新版商品ID去搜索查询
                        if(array_key_exists('itemId', $result['resource'][0]['itemInfoDTO'])){
                            $eventGoodsData['itemId'] = $result['resource'][0]['itemInfoDTO']['itemId'];
                            $dataResult = $this->where('eventId',$result['campaignId'])
                                ->where('itemId', $result['resource'][0]['itemInfoDTO']['itemId'])
                                ->field(['id',])->find();
                        }else{
                            $eventGoodsData['itemId'] = $result['resource'][0]['itemInfoDTO']['mktItemId'];
                            $dataResult = $this->where('eventId',$result['campaignId'])
                                ->where('mktItemId', $eventGoodsData['mktItemId'])
                                ->field(['id',])->find();
                        }
                        if($dataResult){
                            BusinessGoods::update($eventGoodsData, ['id' => $dataResult['id']]);
                        }else{
                            BusinessGoods::create($eventGoodsData);
                        }
                    }
                }else{
                    $hasNext = false;
                }
                //自增分页数
                $page++;
                sleep(1);
            }while($hasNext);
        }
        return true;
    }


    /*
     * 同步新版报名活动商品的效果数据
     *
     *
     * */
    public function sycNewEventGoodsEffectToDB($eventInfo)
    {
        if(!$eventInfo){
            return false;
        }
        foreach($eventInfo as $eventArr){
            if(!$eventArr['token'] || !$eventArr['cookies'] || !$eventArr['eventData']){
                continue;
            }
            foreach($eventArr['eventData'] as $eventData){
                if(!$eventData['eventId']){
                    continue;
                }
                //判断是否有下一页
                $page = 1;
                $pageSize = 100;
                do{
                    $goodsEffectInfo = $this->getNewItemEffectInfo($eventArr['token'], $eventArr['cookies'], $eventData['eventId'], $eventData['groupId'], $eventData['startTime'], $page, $pageSize);
                    //var_export($goodsEffectInfo);die;
                    //echo $eventData['eventId'];
                    //判断获取数据是否异常
                    if(!$goodsEffectInfo || !array_key_exists('data',$goodsEffectInfo)){
                        break;
                    }
                    if($goodsEffectInfo['data'] == '' || $goodsEffectInfo['success'] != 'true'){
                        break;
                    }
                    $count = $goodsEffectInfo['data']['count'];
                    $limitNum = ceil($count/$pageSize);
                    if(array_key_exists('list',$goodsEffectInfo['data']) && $goodsEffectInfo['data']['list'] != ''){
                        //处理商品数据
                        foreach($goodsEffectInfo['data']['list'] as $key=>$result){
                            $eventGoodsEffectData = [
                                //'auditStatus' => $result['auditStatus'],
                                'clickUv' => $result['enterShopUvTk'],
                                //'couponUseNum' => $result['couponUseNum'],
                                'alipayAmt' => $result['alipayAmt'],
                                'alipayNum' => $result['alipayNum'],
                                'preServiceFee' => $result['cpPreServiceShareFee'],
                                'settleAmt' => $result['cpsSettleAmt'],
                                'settleNum' => $result['cpsSettleNum'],
                                'cmServiceFee' => $result['cpCmServiceShareFee'],
                                'taokeNum' => $result['paymentTkNum'],
                            ];
                            //echo $eventData['eventId']."-----".$result['itemId']."\r\n";
                            //var_export($eventData);
                            if(!array_key_exists('itemId', $result) && array_key_exists('mktItemId', $result)){
                                if(strpos($result['mktItemId'],'-') !== false){
                                    $itemArr = explode('-',$result['mktItemId']);
                                    $dataResult = $this->where('eventId',$eventData['eventId'])
                                        ->where('mktItemId', $itemArr[1])
                                        ->field(['id',])->find();
                                }else{
                                    $dataResult = null;
                                }
                            }else{
                                $dataResult = $this->where('eventId',$eventData['eventId'])
                                    ->where('itemId', $result['itemId'])
                                    ->field(['id',])->find();
                            }
                            if($dataResult){
                                BusinessGoods::update($eventGoodsEffectData, ['id' => $dataResult['id']]);
                            }
                            /*else{
                                BusinessGoods::create($eventGoodsEffectData);
                            }*/
                        }
                    }
                    //自增分页数
                    $page++;
                }while($limitNum > $page);
            }
        }
        return true;
    }

    //获取报名活动商品信息
    public function getItemInfo($token,$cookies,$eventId,$page=1,$pageSize=100)
    {
        //获取账号cookie信息

        $timeStr = $this->msectime();
        $url = 'https://pub.alimama.com/cp/event/item/list.json?t='.$timeStr.'&_tb_token_='.$token
            .'&eventId='.$eventId.'&toPage='.$page.'&perPageSize='.$pageSize
            .'&category=&auditorId=&auditStatus=&keyword=&shopkeeperLevel=0&recAuction=0';

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

    //获取商品推广详情列表信息
    public function getItemEffectInfo($token,$cookies,$eventId,$page=1,$pageSize=100)
    {
        //获取账号cookie信息

        $timeStr = $this->msectime();
        $url = 'https://pub.alimama.com/cp/item/effect.json?t='.$timeStr.'&_tb_token_='.$token
            .'&eventId='.$eventId.'&toPage='.$page.'&perPageSize='.$pageSize
            .'&keyword=&sort=&desc=';

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


    //获取新版报名活动商品信息
    public function getNewItemInfo($token,$cookies,$sceneId,$eventId,$page=1,$pageSize=40,$phaseType=31)
    {
        //获取账号cookie信息

        $timeStr = $this->msectime();
        $url = 'https://fuwu.alimama.com/openapi/param2/1/gateway.unionpub/mkt.campaign.sign.list.full.json?t='.$timeStr.'&_tb_token_='.$token
            .'&phaseType='.$phaseType.'&needResource=true&creatorRoleType=3&pageNo='.$page.'&pageSize='.$pageSize.'&searchType=3&keyword=&sortItem=signUpTime&sortType=desc&campaignTemplateId='.$sceneId.'&campaignId='.$eventId;

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
        //var_export($output);die;
        $this->write_log('/tmp/sycGoodsData.log','获取详情页报名商品信息结果：'.$output);
        $result = json_decode($output,true);
        //var_export($result);die;
        $result = $result?$result:[];
        return $result;
    }


    //获取新版报名活动商品信息
    public function getNewItemInfoV2($token,$cookies,$eventId,$page=1,$status=1,$pageSize=20,$phaseType=31)
    {
        //获取账号cookie信息

        $timeStr = $this->msectime();
        $url = 'https://fuwu.alimama.com/openapi/param2/1/gateway.unionpub/cross.campaign.signup.search.full.json?t='.$timeStr
            .'&_tb_token_='.$token.'&pageNo='.$page.'&pageSize='.$pageSize.'&textSearchType=CAMPAIGN_ID&keyword='.$eventId.'&sortItem=&sortType=&showStatus='.$status;

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
        //var_export($output);die;
        //$this->write_log('/tmp/sycGoodsData.log','获取报名商品信息结果：'.$output);
        $result = json_decode($output,true);
        $result = $result?$result:[];
        return $result;
    }

    //获取新版商品推广详情列表信息
    public function getNewItemEffectInfo($token,$cookies,$eventId,$groupId,$startDate, $page=1,$pageSize=100)
    {
        //获取账号cookie信息

        $timeStr = $this->msectime();
        $url = 'https://fuwu.alimama.com/openapi/param2/1/gateway.unionpub/investment.promote.data.detail.list.json?t='.$timeStr.'&_tb_token_='.$token
            .'&groupId='.$groupId.'&startDate='.urlencode($startDate).'&campaignId='.$eventId.'&pageNum='.$page.'&pageSize='.$pageSize.'&keyword=&sortItem=&sortType=';

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

    //修改商品审核状态
    public function modifyItemStatus($token, $cookies, $eventId, $itemId, $auditStatus) {

        $timeStr = $this->msectime();
        $url = 'https://pub.alimama.com/cp/event/item/audit.json';
        $post_data = [
            't' => $timeStr,
            '_tb_token_' => $token,
            'auditStatus' => $auditStatus,
            'itemId' => $itemId,
            'eventId' => $eventId,
        ];
        //$post_string = json_encode($post_data);
        $post_string = "t=".urlencode($timeStr)."&_tb_token_=".urlencode($token)."&auditStatus=".urlencode($auditStatus)."&itemId=".urlencode($itemId)."&eventId=".urlencode($eventId);
        //echo $post_string;die;
        $this->write_log('/tmp/goodsVerify.log',$post_string);
        //执行请求获取数据
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //https调用
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $header = array(
            "content-type: application/x-www-form-urlencoded; charset=UTF-8",
        );
        $user_agent = "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        $output = curl_exec($ch);
        $this->write_log('/tmp/goodsVerify.log',$output);
        $a = curl_error($ch);
        if(!empty($a)){
            return json_encode(array('code'=>-10003,'msg'=>$a));
        }
        curl_close($ch);
        $result = json_decode($output,true);
        $result = $result?$result:[];
        return $result;
    }

    //批量修改商品审核状态
    public function modifyAllItemStatus($token, $cookies, $eventId, $itemId, $auditStatus) {

        $timeStr = $this->msectime();
        $url = 'https://pub.alimama.com/cp/event/item/batchAudit.json';
        $post_data = [
            't' => $timeStr,
            '_tb_token_' => $token,
            'auditStatus' => $auditStatus,
            'itemIds' => $itemId,
            'eventId' => $eventId,
        ];
        //$post_string = json_encode($post_data);
        $post_string = "t=".urlencode($timeStr)."&_tb_token_=".urlencode($token)."&auditStatus=".urlencode($auditStatus)."&itemIds=".urlencode($itemId)."&eventId=".urlencode($eventId);
        //echo $post_string;die;
        $this->write_log('/tmp/goodsVerify.log',$post_string);
        //执行请求获取数据
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //https调用
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $header = array(
            "content-type: application/x-www-form-urlencoded; charset=UTF-8",
        );
        $user_agent = "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        $output = curl_exec($ch);
        $this->write_log('/tmp/goodsVerify.log',$output);
        $a = curl_error($ch);
        if(!empty($a)){
            return json_encode(array('code'=>-10003,'msg'=>$a));
        }
        curl_close($ch);
        $result = json_decode($output,true);
        $result = $result?$result:[];
        return $result;
    }

    //修改商品审核状态
    public function modifyNewItemStatus($token, $cookies, $recordIdList, $auditStatus, $phaseType=41) {
        $timeStr = $this->msectime();
        $url = 'https://fuwu.alimama.com/openapi/param2/1/gateway.unionpub/mkt.campaign.sign.audit.json';
        $post_data = [
            't' => $timeStr,
            '_tb_token_' => $token,
            //'_tb_token_' => 'fbb3b33b373b3',
            'signUpRecordDTOList' => $recordIdList,
            'audit' => $auditStatus,
            'phaseType' => $phaseType,
            'refuseReason' => ''
        ];
        //$post_string = json_encode($post_data);
        $post_string = "t=".$timeStr."&_tb_token_=".$token."&signUpRecordDTOList=".urlencode($recordIdList)."&audit=".$auditStatus."&phaseType=41&refuseReason=";
        //echo $post_string;die;
        $this->write_log('/tmp/goodsVerify.log',$post_string);
        //执行请求获取数据
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //https调用
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $header = array(
            "content-type: application/x-www-form-urlencoded; charset=UTF-8",
        );
        $user_agent = "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        $output = curl_exec($ch);
        $this->write_log('/tmp/goodsVerify.log',$output);
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

    //获取跳转后的url地址信息
    public function getUrlLocationResult($url,$cookies,$referer='',$timeout=3){
        //获取账号cookie信息
        //$timeStr = $this->msectime();
        //执行请求获取数据
        //https调用
        //curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36';



        $redirect_url = false;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        //curl_setopt($ch, CURLOPT_NOBODY, TRUE);//不返回请求体内容
        curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//允许请求的链接跳转
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
              'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36',
              'Athority: uland.taobao.com'));

       if ($referer) {
         curl_setopt($ch, CURLOPT_REFERER, $referer);//设置referer
       }
       $cOntent= curl_exec($ch);
       if(!curl_errno($ch)) {
           //$info = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
         $redirect_url = curl_getinfo($ch);//获取最终请求的url地址
       }
       return $redirect_url;
    }


    function get_location($url,$cookies,$ua=0){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $httpheader[] = "Accept:*/*";

        $httpheader[] = "Accept-Encoding:gzip,deflate,sdch";

        $httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";

        $httpheader[] = "Connection:close";

        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);

        curl_setopt($ch, CURLOPT_HEADER, true);

        if ($ua) {

            curl_setopt($ch, CURLOPT_USERAGENT, $ua);

        } else {

            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36");

        }

        curl_setopt($ch, CURLOPT_NOBODY, 1);

        //curl_setopt($ch, CURLOPT_ENCODING, "gzip");

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        //curl_setopt($ch, CURLOPT_MAXREDIRS, 5);//最多重定向5次

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_COOKIE, $cookies);

        $ret = curl_exec($ch);

        curl_close($ch);

        preg_match("/Location: (.*?)\r\n/iU",$ret,$location);

        return $ret;

    }

    public function get_url_header($url,$aa){
        $header = get_headers($url,1);
        return $header;
        $info = [11];
        if (strpos($header[0],'301') || strpos($header[0],'302')) {
            if(is_array($header['Location'])) {
                $info = $header['Location'][count($header['Location'])-1];
            }else{
                $info = $header['Location'];
            }
        }
        return $info;
    }

}