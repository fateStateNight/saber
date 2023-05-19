<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class BusinessScene extends TimeModel
{

    protected $name = "business_scene";

    protected $deleteTime = false;



    public function taobaoAccount()
    {
        return $this->belongsTo('\app\admin\model\SystemTaobaoAccount', 'accountId', 'account_id');
    }
    
    
    public function getStatusList()
    {
        return ['0'=>'所有状态','1'=>'草稿','2'=>'系统校验中','3'=>'报名未开始','4'=>'报名中','5'=>'报名截止','7'=>'推广中','8'=>'结束','9'=>'已失效',];
    }

    public function getSceneNameList()
    {
        return ['6'=>'普通活动招商','8'=>'内容招商','10'=>'天猫超市','11'=>'私域活动招商','13'=>'天猫国际','14'=>'阿里健康','16'=>'前N件高佣招商','27'=>'超级U选'];
    }

    //获取数据表中活动数据
    public function getEventInfoFromData($accountArr, $where = [])
    {
        $ret = [];
        $optDate = date('Y-m-d',strtotime('-1 months'));
        if($accountArr){
            foreach($accountArr as $account){
                $dataResult = $this->where('accountId', $account['account_id'])
                    ->where('endTime','>=', $optDate)
                    ->where($where)
                    ->field(['eventId','sceneId', 'groupId','startTime'])
                    ->select()->toArray();
                $ret[] = [
                    'token' => $account['token'],
                    'accountId' => $account['account_id'],
                    'cookies' => $account['cookies'],
                    'eventData' => $dataResult
                ];
            }
        }
        return $ret;
    }

    /*
     * 同步数据到数据库中
     * accountInfo:阿里账户信息
     * */
    public function sycEventInfoToDB($accountInfo)
    {
        $sceneIdArr = [6,8,10,11,13,14,16];
        if(!$accountInfo){
            return false;
        }
        foreach($accountInfo as $account){
            if($account['token'] == '' || $account['cookies'] == ''){
                continue;
            }
            foreach($sceneIdArr as $sceneId){
                //判断是否有下一页
                $hasNext = false;
                $page = 1;
                do{
                    $eventInfo = $this->getEventInfo($account['token'],$account['cookies'],$sceneId,$page);
                    //判断获取数据是否异常
                    if(!$eventInfo || !array_key_exists('data',$eventInfo)){
                        break;
                    }
                    if($eventInfo['data'] == '' || $eventInfo['ok'] != 'true'){
                        break;
                    }
                    $hasNext = $eventInfo['data']['hasNext'];
                    if(array_key_exists('result',$eventInfo['data']) && $eventInfo['data']['result'] != ''){
                        //处理活动数据
                        foreach($eventInfo['data']['result'] as $result){
                            $eventData = [
                                'eventId' => $result['eventId'],
                                'accountId' => $account['account_id'],
                                'sceneId' => $result['sceneId'],
                                'sceneName' => $result['sceneName'],
                                'title' => $result['title'],
                                'auditPassed' => $result['auditPassed'],
                                'auditTotal' => $result['auditTotal'],
                                'auditWait' => $result['auditWait'],
                                'itemNum' => $result['itemNum'],
                                'status' => $result['status'],
                                'dingTalkName' => $result['dingTalkName'],
                                'cmAlipayAmt' => $result['cmAlipayAmt'],
                                'serviceRate' => $result['serviceRate'],
                                'startTime' => $result['startTime'],
                                'endTime' => $result['endTime'],
                                'updateTime' => date('Y-m-d H:i:s'),
                            ];

                            $dataResult = $this->where('eventId',$result['eventId'])->field(['id','commissionRate'])->find();
                            if($dataResult){
                                if($dataResult['commissionRate'] == 0){
                                    $commissionInfo = $this->getEventCommissionInfo($account['token'],$account['cookies'],$result['eventId']);
                                    if($commissionInfo && array_key_exists('data',$commissionInfo) && array_key_exists('ruleRootCats',$commissionInfo['data'])){
                                        $eventData['commissionRate'] = $commissionInfo['data']['ruleRootCats'][0]['minCommissionRate'];
                                        $eventData['serviceRate'] = $commissionInfo['data']['serviceRate'];
                                    }
                                }
                                BusinessScene::update($eventData, ['eventId' => $result['eventId']]);
                            }else{
                                BusinessScene::create($eventData);
                            }
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
     * 同步活动效果概览数据
     *
     * */
    public function sycEventEffectInfoToDB($accountInfo, $eventId = null)
    {
        if($eventId){
            $newEventInfo = $this->getEventEffectInfo($accountInfo['token'],$accountInfo['cookies'],$eventId);
            if(!$newEventInfo || !array_key_exists('data',$newEventInfo)){
                return false;
            }
            if($newEventInfo['data'] == '' || $newEventInfo['ok'] != 'true'){
                return false;
            }
            $eventData = [
                'clickUv' => $newEventInfo['data']['clickUv'],
                'alipayAmt' => $newEventInfo['data']['alipayAmt'],
                'alipayNum' => $newEventInfo['data']['alipayNum'],
                'preServiceFee' => $newEventInfo['data']['preServiceFee'],
                'settleAmt' => $newEventInfo['data']['settleAmt'],
                'settleNum' => $newEventInfo['data']['settleNum'],
                'cmServiceFee' => $newEventInfo['data']['cmServiceFee'],
                'updateTime' => date('Y-m-d H:i:s'),
            ];
            BusinessScene::update($eventData,['eventId' => $eventId]);
        }else{
            $optDate = date('Y-m-d',strtotime('-1 months'));
            $eventArr = $this->where('accountId', $accountInfo['account_id'])
                ->where('endTime','>=', $optDate)
                ->field(['id','accountId','eventId'])
                ->select()->toArray();
            if($eventArr){
                return false;
            }
            foreach($eventArr as $eventItem){
                $newEventInfo = $this->getEventEffectInfo($accountInfo['token'],$accountInfo['cookies'],$eventItem['eventId']);
                if(!$newEventInfo || !array_key_exists('data',$newEventInfo)){
                    return false;
                }
                if($newEventInfo['data'] == '' || $newEventInfo['ok'] != 'true'){
                    return false;
                }
                $eventData = [
                    'clickUv' => $newEventInfo['data']['clickUv'],
                    'alipayAmt' => $newEventInfo['data']['alipayAmt'],
                    'alipayNum' => $newEventInfo['data']['alipayNum'],
                    'preServiceFee' => $newEventInfo['data']['preServiceFee'],
                    'settleAmt' => $newEventInfo['data']['settleAmt'],
                    'settleNum' => $newEventInfo['data']['settleNum'],
                    'cmServiceFee' => $newEventInfo['data']['cmServiceFee'],
                    'updateTime' => date('Y-m-d H:i:s'),
                ];
                BusinessScene::update($eventData,['eventId' => $eventId]);
            }
        }
        return true;
    }

    /*
     * 同步活动佣金服务费最低要求数据
     *
     * */
    public function sycEventCommissionInfoToDB($accountInfo, $eventId = null)
    {
        if($eventId){
            $newEventInfo = $this->getEventCommissionInfo($accountInfo['token'],$accountInfo['cookies'],$eventId);
            $eventData = [
                'commissionRate' => $newEventInfo['data']['ruleRootCats'][0]['minCommissionRate'],
                'serviceRate' => $newEventInfo['data']['serviceRate']?$newEventInfo['data']['serviceRate']:0,
            ];
            BusinessScene::update($eventData,['eventId' => $eventId]);
        }else{
            $optDate = date('Y-m-d',strtotime('-1 months'));
            $eventArr = $this->where('accountId', $accountInfo['account_id'])
                ->where('endTime','>=', $optDate)
                ->field(['id','accountId','eventId'])
                ->select()->toArray();
            if($eventArr){
                return false;
            }
            foreach($eventArr as $eventItem){
                $newEventInfo = $this->getEventCommissionInfo($accountInfo['token'],$accountInfo['cookies'],$eventItem['eventId']);
                $eventData = [
                    'commissionRate' => $newEventInfo['data']['ruleRootCats'][0]['minCommissionRate'],
                    'serviceRate' => $newEventInfo['data']['serviceRate']?$newEventInfo['data']['serviceRate']:0,
                ];
                BusinessScene::update($eventData,['eventId' => $eventId]);
            }
        }
        return true;
    }

    /*
     * 同步新版活动数据到数据库中
     * accountInfo:阿里账户信息
     * */
    public function sycNewEventInfoToDB($accountInfo)
    {
        $sceneIdArr = [6,8,10,13,14,16,27];
        if(!$accountInfo){
            return false;
        }
        foreach($accountInfo as $account){
            if($account['token'] == '' || $account['cookies'] == ''){
                continue;
            }
            foreach($sceneIdArr as $sceneId){
                //判断是否有下一页
                $hasNext = false;
                $page = 1;
                do{
                    $eventInfo = $this->getNewEventInfo($account['token'],$account['cookies'],$sceneId,$page);
                    //判断获取数据是否异常
                    if(!$eventInfo || !array_key_exists('data',$eventInfo)){
                        break;
                    }
                    if($eventInfo['data'] == '' || $eventInfo['success'] != 'true'){
                        break;
                    }
                    $hasNext = $eventInfo['data']['hasNext'];
                    if(array_key_exists('result',$eventInfo['data']) && $eventInfo['data']['result'] != ''){
                        //处理活动数据
                        foreach($eventInfo['data']['result'] as $result){
                            $eventData = [
                                'eventId' => $result['campaign']['campaignId'],
                                'accountId' => $account['account_id'],
                                'sceneId' => $result['campaign']['campaignTemplateId'],
                                'sceneName' => $result['campaign']['campaignTemplateName'],
                                'title' => $result['campaign']['campaignName'],
                                'groupId' => $result['campaign']['groupId'],
                                'auditPassed' => $result['campaign']['hasAuditCount'],
                                'auditTotal' => $result['campaign']['auditTotalCount'],
                                'auditWait' => $result['campaign']['needAuditCount'],
                                //'itemNum' => $result['campaign']['itemNum'],
                                'status' => $result['campaign']['showStatus'],
                                'dingTalkName' => $result['campaign']['campaignCreatorInfo']['dingTalkName'],
                                //'cmAlipayAmt' => $result['campaign']['alipayAmt'],
                                //'serviceRate' => $result['campaign']['serviceRate'],
                                'startTime' => $result['campaign']['publishStartTime'],
                                'endTime' => $result['campaign']['publishEndTime'],
                                'updateTime' => date('Y-m-d H:i:s'),
                                'version' => 1,
                            ];

                            $dataResult = $this->where('eventId',$result['campaign']['campaignId'])->field(['id','commissionRate'])->find();
                            if($dataResult){
                                if($dataResult['commissionRate'] == 0 && $sceneId != 27){
                                    $commissionInfo = $this->getNewEventCommissionInfo($account['token'],$account['cookies'],$result['campaign']['campaignId']);
                                    if($commissionInfo && array_key_exists('data',$commissionInfo) && array_key_exists('campaignRuleInstanceList',$commissionInfo['data'])){
                                        if(array_key_exists('value',$commissionInfo['data']['campaignRuleInstanceList'][1]['featureValue'])){
                                            $commissionRuleInfo = json_decode($commissionInfo['data']['campaignRuleInstanceList'][1]['featureValue']['value'],true);
                                            $eventData['commissionRate'] = $commissionRuleInfo[0]['minNormalCommissionRate'];
                                        }
                                        $eventData['serviceRate'] = $commissionInfo['data']['serviceRate'];
                                    }
                                }
                                BusinessScene::update($eventData, ['eventId' => $result['campaign']['campaignId']]);
                            }else{
                                BusinessScene::create($eventData);
                            }
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
     * 同步新版活动佣金服务费最低要求数据
     *
     * */
    public function sycNewEventCommissionInfoToDB($accountInfo, $eventId = null)
    {
        if($eventId){
            $newEventInfo = $this->getNewEventCommissionInfo($accountInfo['token'],$accountInfo['cookies'],$eventId);
            $commissionRuleInfo = json_decode($newEventInfo['data']['campaignRuleInstanceList'][1]['featureValue']['value'],true);
            $commissionRate = $commissionRuleInfo[0]['minNormalCommissionRate'];
            $eventData = [
                'commissionRate' => $commissionRate,
                'serviceRate' => $newEventInfo['data']['serviceRate']?$newEventInfo['data']['serviceRate']:0,
            ];
            BusinessScene::update($eventData,['eventId' => $eventId]);
        }else{
            $optDate = date('Y-m-d',strtotime('-1 months'));
            $eventArr = $this->where('accountId', $accountInfo['account_id'])
                ->where('endTime','>=', $optDate)
                ->field(['id','accountId','eventId'])
                ->select()->toArray();
            if($eventArr){
                return false;
            }
            foreach($eventArr as $eventItem){
                $newEventInfo = $this->getNewEventCommissionInfo($accountInfo['token'],$accountInfo['cookies'],$eventItem['eventId']);
                $commissionRuleInfo = json_decode($newEventInfo['data']['campaignRuleInstanceList'][1]['featureValue']['value'],true);
                $commissionRate = $commissionRuleInfo[0]['minNormalCommissionRate'];
                $eventData = [
                    'commissionRate' => $commissionRate,
                    'serviceRate' => $newEventInfo['data']['serviceRate']?$newEventInfo['data']['serviceRate']:0,
                ];
                BusinessScene::update($eventData,['eventId' => $eventId]);
            }
        }
        return true;
    }

    /*
     * 同步活动效果概览数据
     *
     * */
    public function sycNewEventEffectInfoToDB($accountInfo, $eventId = null, $groupId = null, $startTime = null)
    {
        if($eventId){
            $newEventInfo = $this->getNewEventEffectInfo($accountInfo['token'],$accountInfo['cookies'],$eventId, $groupId, $startTime);
            //var_export($newEventInfo);die;
            if(!$newEventInfo || !array_key_exists('data',$newEventInfo) || !array_key_exists('enterShopUvTk', $newEventInfo['data'])){
                return false;
            }
            if($newEventInfo['data'] == '' || $newEventInfo['success'] != 'true'){
                return false;
            }
            $eventData = [
                'clickUv' => $newEventInfo['data']['enterShopUvTk'],
                'alipayAmt' => $newEventInfo['data']['alipayAmt'],
                'alipayNum' => $newEventInfo['data']['alipayNum'],
                'preServiceFee' => $newEventInfo['data']['cpPreServiceShareFee'],
                'settleAmt' => $newEventInfo['data']['cpsSettleAmt'],
                'settleNum' => $newEventInfo['data']['cpsSettleNum'],
                'cmServiceFee' => $newEventInfo['data']['cpCmServiceShareFee'],
                'updateTime' => date('Y-m-d H:i:s'),
            ];
            BusinessScene::update($eventData,['eventId' => $eventId]);
        }else{
            $optDate = date('Y-m-d',strtotime('-1 months'));
            $eventArr = $this->where('accountId', $accountInfo['account_id'])
                ->where('endTime','>=', $optDate)
                ->field(['id','accountId','eventId','groupId','startTime'])
                ->select()->toArray();
            if($eventArr){
                return false;
            }
            foreach($eventArr as $eventItem){
                $newEventInfo = $this->getNewEventEffectInfo($accountInfo['token'],$accountInfo['cookies'],$eventItem['eventId'], $eventItem['groupId'], $eventItem['startTime']);
                if(!$newEventInfo || !array_key_exists('data',$newEventInfo) || !array_key_exists('enterShopUvTk', $newEventInfo['data'])){
                    return false;
                }
                if($newEventInfo['data'] == '' || $newEventInfo['success'] != 'true'){
                    return false;
                }
                $eventData = [
                    'clickUv' => $newEventInfo['data']['enterShopUvTk'],
                    'alipayAmt' => $newEventInfo['data']['alipayAmt'],
                    'alipayNum' => $newEventInfo['data']['alipayNum'],
                    'preServiceFee' => $newEventInfo['data']['cpPreServiceShareFee'],
                    'settleAmt' => $newEventInfo['data']['cpsSettleAmt'],
                    'settleNum' => $newEventInfo['data']['cpsSettleNum'],
                    'cmServiceFee' => $newEventInfo['data']['cpCmServiceShareFee'],
                    'updateTime' => date('Y-m-d H:i:s'),
                ];
                BusinessScene::update($eventData,['eventId' => $eventId]);
            }
        }
        return true;
    }

    
    //获取阿里妈妈账号信息
    public function getALiAccountInfo($Id=null,$user_name=null,$accountId=null)
    {
        //优先根据id获取账号信息，其次根据用户名获取账号信息，前两者都没有获取所有账号信息
        $taobaoModel = new \app\admin\model\SystemTaobaoAccount();
        if($Id){
            $accountArr = $taobaoModel->where('id',$Id)->field(['id','account_id','user_name','cookies'])
                ->select()->toArray();
        }elseif($accountId){
            $accountArr = $taobaoModel->where('account_id',$accountId)->field(['id','account_id','user_name','cookies'])
                ->select()->toArray();
        }elseif($user_name){
            $accountArr = $taobaoModel->where('user_name',$user_name)->field(['id','account_id','user_name','cookies'])
                ->select()->toArray();
        }else{
            $accountArr = $taobaoModel->where('user_name','<>','')
                ->where('cookies','<>','')->field(['id','account_id','user_name','cookies'])
                ->select()->toArray();
        }
        foreach($accountArr as $accountNum=>$account){
            if($account['cookies'] != ''){
                $cookieArr = explode(';',$account['cookies']);
                if($cookieArr != ''){
                    foreach($cookieArr as $item){
                        $itemArr = explode('=',$item);
                        if($itemArr[0] == '_tb_token_'){
                            $accountArr[$accountNum]['token'] = $itemArr[1];
                            break;
                        }
                    }
                }
            }
        }
        return $accountArr;
    }

    //获取活动信息
    public function getEventInfo($token,$cookies,$sceneId,$page=1,$pageSize=100)
    {
        //获取账号cookie信息
        //

        $timeStr = $this->msectime();
        $url = 'https://pub.alimama.com/cp/event/list.json?t='.$timeStr.'&_tb_token_='.$token.'&toPage='.$page.'&perPageSize='.$pageSize.'&status=&keyword=&sceneId='.$sceneId;

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

    //获取活动推广概览信息
    public function getEventEffectInfo($token,$cookies,$eventId)
    {
        //获取账号cookie信息
        $timeStr = $this->msectime();
        $url = 'https://pub.alimama.com/cp/item/effect/sum.json?t='.$timeStr.'&_tb_token_='.$token.'&eventId='.$eventId;

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

    //获取活动服务费佣金要求信息
    public function getEventCommissionInfo($token,$cookies,$eventId)
    {
        //获取账号cookie信息
        $timeStr = $this->msectime();
        $url = 'https://pub.alimama.com/cp/event/info.json?t='.$timeStr.'&_tb_token_='.$token.'&eventId='.$eventId;

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

    //获取联盟账号在线登录信息
    public function getOnlineAccountInfo($token,$cookies)
    {
        //获取账号cookie信息
        $timeStr = $this->msectime();
        $url = 'https://pub.alimama.com/cp/event/creator/info.json?t='.$timeStr.'&_tb_token_='.$token;

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
        $this->write_log('/tmp/goodsVerify.log',serialize($result));
        return $result;
    }

    //获取联盟账号成交金额数据
    public function getUnionInfo($token,$cookies,$dateTime)
    {
        //获取账号cookie信息
        $timeStr = $this->msectime();
        $url = 'https://fuwu.alimama.com/openapi/param2/1/gateway.unionpub/union.sp.management.data?t='
            .$timeStr.'&_tb_token_='.$token.'&endDate='.$dateTime.'&startDate='.$dateTime.'&period=month';

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

    //获取联盟账号618成交金额数据
    public function getEventUnionInfo($token,$cookies)
    {
        //获取账号cookie信息
        $timeStr = $this->msectime();
        $currentDate = date('Y-m-d');
        $url = 'https://pub.alimama.com/openapi/param2/1/gateway.unionpub/screenData.json?t='
            .$timeStr.'&_tb_token_='.$token.'&periodValue='.$currentDate.'&periodType=activity&pubGroup=tk_cp';

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


    //获取新版活动信息
    public function getNewEventInfo($token,$cookies,$sceneId,$page=1,$pageSize=100,$phaseType=31)
    {
        //获取账号cookie信息
        //

        $timeStr = $this->msectime();
        $url = 'https://fuwu.alimama.com/openapi/param2/1/gateway.unionpub/mkt.campaign.list.json?t='.$timeStr.'&_tb_token_='.$token.'&phaseType='.$phaseType.'&needEffect=true&pageNo='.$page.'&pageSize='.$pageSize.'&showStatus=0&keyword=&campaignTemplateId='.$sceneId;

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

    //获取新版活动推广概览信息
    public function getNewEventEffectInfo($token,$cookies,$eventId, $groupId, $startDate)
    {
        //获取账号cookie信息
        $timeStr = $this->msectime();
        $url = 'https://fuwu.alimama.com/openapi/param2/1/gateway.unionpub/investment.promote.data.overview.json?t='
            .$timeStr.'&_tb_token_='.$token.'&groupId='.$groupId.'&startDate='.urlencode($startDate).'&campaignId='.$eventId;

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

    //获取活动服务费佣金要求信息
    public function getNewEventCommissionInfo($token,$cookies,$eventId,$phaseType=32)
    {
        //获取账号cookie信息
        $timeStr = $this->msectime();
        $url = 'https://fuwu.alimama.com/openapi/param2/1/gateway.unionpub/mkt.campaign.detail.json?t='.$timeStr.'&_tb_token_='.$token.'&campaignId='.$eventId.'&phaseType='.$phaseType;

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

    public function write_log($filename, $content){
        $newContent = date("Y-m-d H:i:s")." ".$content."\r\n";
        file_put_contents($filename,$newContent,FILE_APPEND);
        return true;
    }

}