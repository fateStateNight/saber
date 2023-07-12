<?php

namespace app\admin\controller\mall;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use http\Env;
use jianyan\excel\Excel;
use think\App;
use think\facade\Cache;
use function Qiniu\base64_urlSafeDecode;
use app\admin\model\SystemDouyinAccount;
use app\admin\model\BusinessScene;

/**
 * @ControllerAnnotation(title="常用工具")
 */
class CommonTools extends AdminController
{

    use \app\admin\traits\Curd;

    protected $douyinModel;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\MallCommonTools();

        $taoAccountModel = new \app\admin\model\MallTaolijinGoods();

        $this->douyinModel = new \app\admin\model\SystemDouyinAccount();

        $this->assign('getSystemTaobaoAccountList', $taoAccountModel->getSystemTaobaoAccountList());

        $this->assign('douAccountList', $this->douyinModel->getDouAccountList());

    }

    public function redisTest()
    {
        Cache::store('redis')->delete('test');die;
        Cache::store('redis')->set('test','11111');
        $result = Cache::store('redis')->get('test');
        return $result;
    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFieds')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->where($where)
                ->count();
            $list = $this->model
                ->where($where)
                ->page($page, $limit)
                ->order($this->sort)
                ->select();
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        return $this->fetch();
    }


    //抖音口令解析
    public function analysisDouCommand()
    {
        $post = $this->request->post();
        if($post['douCommand'] == ''){
            $this->error('解析口令不能为空！');
        }
        $accountInfo = $this->douyinModel->getDouyinAccountInfo(1);
        $result = $this->model->analysisDouCommand($accountInfo['accessToken'],$post['douCommand']);
        $data = [
            'code'  => 0,
            'msg'   => '',
            'data'  => $result,
        ];
        return json($data);
    }


    //抖音活动转链
    public function activityTransfer()
    {
        $post = $this->request->post();
        if($post['material_id'] == ''){
            $this->error('活动ID不能为空！');
        }
        if($post['dou_account_id'] == ''){
            $this->error('请选择转链账号！');
        }
        $accountInfo = $this->douyinModel->getDouyinAccountInfo($post['dou_account_id']);
        $result = $this->model->activityTransfer($accountInfo['accessToken'],$accountInfo['publishID'],$post['material_id']);
        $data = [
            'code'  => 0,
            'msg'   => '',
            'data'  => $result,
        ];
        return json($data);
    }

    //指定商品历史数据
    public function getGoodsHistory()
    {
        $post = $this->request->post();
        if($post['goods_option'] == ''){
            $this->error('查询的商品信息不能为空！');
        }
        $goodsOption = explode(" ",$post['goods_option']);
        if(strpos($goodsOption[0],',')){
            $goodsIdArr = explode(",", $goodsOption[0]);
        }else{
            $goodsIdArr[] = $goodsOption[0];
        }
        //获取淘宝联盟账号信息
        $businessSceneModel = new \app\admin\model\BusinessScene();
        //获取账号cookie
        $accountInfo = $businessSceneModel->getALiAccountInfo('','',$post['account_id']);
        //检测账号是否在线
        $onlineInfo = $businessSceneModel->getOnlineAccountInfo($accountInfo[0]['token'], $accountInfo[0]['cookies']);
        if(!$onlineInfo && !array_key_exists('data', $onlineInfo)){
            $this->error('团长账号不在线！');
        }
        if($goodsIdArr == null){
            $this->error('查询的商品ID不能为空！');
        }
        $result = [];
        foreach($goodsIdArr as $key=>$goodsId){
            $goodsHistoryData = $this->getDataPublicInfo($goodsId,$goodsOption[1],$goodsOption[2],$accountInfo[0]['token'],$accountInfo[0]['cookies']);
            $result[$goodsId] = $goodsHistoryData;
        }
        return json($result);
    }

    //导出招商数据
    public function exportBusinessData($eventIdArr, $cookies, $token = 'e3a5e3eee5e57')
    {
        $header = [];
        $keyWords = ['活动ID','商品名称','商品ID','店铺名称','店铺ID','销量','佣金比率','服务费率','劵后价','开始日期','结束日期'];
        foreach ($keyWords as $vo) {
            $header[$vo] = 'string';
        }
        $writer = new \XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header);
        //获取原始数据
        if($eventIdArr == []){
            return false;
        }
        foreach($eventIdArr as $eventId){
            $page = 0;
            $hasNext = false;
            do{
                $page++;
                $rowData = [];
                $businessData = $this->getAccountEnrollInfo($eventId,$token,$page,$cookies);
                //var_export($businessData);die;
                if(!array_key_exists('data',$businessData)){
                    break;
                }
                $hasNext = $businessData['data']['hasNext'];
                $businessArr = $businessData['data']['result'];
                if($businessArr == null){
                    break;
                }
                foreach($businessArr as $businessInfo){
                    $rowData = [
                        'eventID' => $eventId,
                        'title' => $businessInfo['title'],
                        'itemId' => $businessInfo['itemId'],
                        'shopTitle' => $businessInfo['shopTitle'],
                        'sellerId' => $businessInfo['sellerId'],
                        'biz30day' => $businessInfo['biz30day'],
                        'commissionRate' => $businessInfo['commissionRate'],
                        'serviceRate' => $businessInfo['serviceRate'],
                        'zkFinalPrice' => $businessInfo['zkFinalPrice'],
                        'startTime' => $businessInfo['startTime'],
                        'endTime' => $businessInfo['endTime'],
                    ];
                    $writer->writeSheetRow('Sheet1', $rowData);
                }
            }while($hasNext);
        }
        $fileName = 'businessFile'.time().'.xlsx';
        $filePath = app()->getRootPath().'public/download/'.$fileName;
        $writer->writeToFile($filePath);
        return $fileName;
    }

    //导出推广效果数据
    public function exportSaleData($eventIdArr, $cookies, $token = 'e3a5e3eee5e57')
    {
        $header = [];
        $keyWords = ['活动ID','商品名称','商品ID','店铺名称','店铺ID','劵后价','引流UV','付款笔数','付款金额','服务费率','预估付款服务费','结算笔数','结算金额','预估结算服务费','淘宝客数','开始日期','结束日期','排期'];
        foreach ($keyWords as $vo) {
            $header[$vo] = 'string';
        }
        $writer = new \XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header );
        //获取原始数据
        if($eventIdArr == []){
            return false;
        }
        foreach($eventIdArr as $eventId){
            $page = 0;
            $hasNext = false;
            do{
                $page++;
                $rowData = [];
                $businessData = $this->getAccountSaleInfo($eventId,$token,$page,$cookies);
                if(!array_key_exists('data',$businessData)){
                    break;
                }
                $hasNext = $businessData['data']['hasNext'];
                $businessArr = $businessData['data']['result'];
                if($businessArr == null){
                    break;
                }
                foreach($businessArr as $businessInfo){
                    $rowData = [
                        'eventID' => $eventId,
                        'title' => $businessInfo['title'],
                        'itemId' => $businessInfo['itemId'],
                        'shopTitle' => $businessInfo['shopTitle'],
                        'sellerId' => $businessInfo['sellerId'],
                        'zkFinalPrice' => $businessInfo['zkFinalPrice'],
                        'clickUv' => $businessInfo['clickUv'],
                        'alipayNum' => $businessInfo['alipayNum'],
                        'alipayAmt' => $businessInfo['alipayAmt'],
                        'avgServiceRate' => $businessInfo['avgServiceRate'],
                        'preServiceFee' => $businessInfo['preServiceFee'],
                        'settleNum' => $businessInfo['settleNum'],
                        'settleAmt' => $businessInfo['settleAmt'],
                        'cmServiceFee' => $businessInfo['cmServiceFee'],
                        'taokeNum' => $businessInfo['taokeNum'],
                        'startTime' => $businessInfo['startTime'],
                        'endTime' => $businessInfo['endTime'],
                        'scheduleTime' => $businessInfo['scheduleTime']
                    ];
                    $writer->writeSheetRow('Sheet1', $rowData);
                }
            }while($hasNext);
        }
        $fileName = 'saleFile'.time().'.xlsx';
        $filePath = app()->getRootPath().'public/download/saleFile'.time().'.xlsx';
        $writer->writeToFile($filePath);
        return $fileName;
    }


    //获取团长账号报名商品数据
    public function getAccountEnrollInfo($eventId,$token,$page,$cookies,$pageSize = 100)
    {
        $timeStr = $this->msectime();
        $url = 'https://pub.alimama.com/cp/event/item/list.json?t='.$timeStr.'&_tb_token_='.$token.'&eventId='.$eventId.'&toPage='.$page.'&perPageSize='.$pageSize.'&category=&auditorId=&auditStatus=&keyword=&shopkeeperLevel=0&recAuction=0';

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

    //获取团长账号推广效果数据
    public function getAccountSaleInfo($eventId,$token,$page,$cookies,$pageSize = 100)
    {
        $timeStr = $this->msectime();

        $url = 'https://pub.alimama.com/cp/item/effect.json?t='.$timeStr.'&_tb_token_='.$token.'&eventId='.$eventId.'&toPage='.$page.'&perPageSize='.$pageSize.'&keyword=&sort=&desc=';

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

    //获取指定商品的历史推广数据
    public function getDataPublicInfo($goodsId,$startDate,$stopDate,$token,$cookies)
    {
        $timeStr = $this->msectime();

        $sourceUrl = 'https://pub.alimama.com/openapi/json2/1/gateway.unionpub/xt.entry.json?';
        $parameter = array(
            't' => $timeStr,
            '_tb_token_' => $token,
            '_data_' => '{
                "floorId":69812,
                "pageSize":100,
                "pageNum":1,
                "variableMap":{
                    "itemId":"'.$goodsId.'",
                    "startDate":"'.$startDate.'",
                    "endDate":"'.$stopDate.'"
                }
            }',
        );
        $url = $sourceUrl.http_build_query($parameter);
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


    /**
     * @NodeAnotation(title="解析")
     */
    public function analysis()
    {
        $post = $this->request->post();
        //var_dump($post['content']);die;
        //使用正则表达式解析出字符串中所有的口令
        //$pattern = '/\(((\d|\w)?)(\(|\))$/i';
        //$pattern = '/(?<=\()(\d|\w)[^(\)|\()]+/i';
        /*$arr = explode('|',$post['content']);
        //var_dump($arr);die;
        $pattern = '/(?<=[\(|￥])((\d|\w)+)(?=(\)|\(|￥))/i';

        preg_match_all($pattern, $arr[0], $match1);
        preg_match_all($pattern, $arr[1], $match2);
        $strArr1 = array_unique($match1[0]);
        $strArr2 = array_unique($match2[0]);
        //var_dump($strArr1);
        //var_dump($strArr2);
        foreach($strArr2 as $value){
            if(!in_array($value, $strArr1)){
                $secretStr = '1('.$value.')/';
                $analysisResult = $this->model->analysisOnlySecret($secretStr);
                $newArr[] = $value;
                if($analysisResult['data'] != null && $analysisResult['code'] == 0){
                    echo $value;
                    var_dump($analysisResult);
                }
            }
        }
        var_dump($newArr);die;*/
        if(strpos($post['content'], 'https')){
            $analysisResult = $this->model->analysisSecret($post['content']);
            if(array_key_exists('data', $analysisResult) && $analysisResult['data'] != null && $analysisResult['code'] == 0){
                return $analysisResult['data'];
            }
        }else{
            $pattern = '/(?<=[\(|￥])((\d|\w)+)(?=(\)|\(|￥)?)/i';
            preg_match_all($pattern, $post['content'], $match);
            $secretArr = array_unique($match[0]);
            if($secretArr != null){
                foreach($secretArr as $key=>$secret){
                    if(in_array($secret, $this->errorKouling())){
                        continue;
                    }
                    $secretStr = '1('.$secret.')/';
                    $analysisResult = $this->model->analysisOnlySecret($secretStr);
                    //echo "    ".$key."===".$secret."\r\n";
                    //var_dump($analysisResult);die;
                    if(array_key_exists('data', $analysisResult) && $analysisResult['data'] != null && $analysisResult['code'] == 0){
                        return $analysisResult['data'];
                    }
                }
            }
        }
        $this->error('解析失败！');
        return false;
    }

    /**
     * @NodeAnotation(title="转换")
     */
    public function transfer()
    {
        $post = $this->request->post();
        if($post['link_url'] == ''){
            $this->error('转换的链接不能为空！');
        }
        //获取淘宝联盟账号信息
        $systemAccountModel = new \app\admin\model\SystemTaobaoAccount();
        $accountInfo = $systemAccountModel->getTaoBaoAccountInfo($post['account_id']);
        $transferResult = $this->model->transferSecret($accountInfo, $post['link_url']);
        //return $transferResult;die;
        $short_link = "0(".mb_substr($transferResult['data']['password_simple'],1,-1).")/";
        return $short_link;
    }


    /**
     * @NodeAnotation(title="转链")
     */
    public function effective()
    {
        $post = $this->request->post();
        //获取淘宝联盟账号信息
        $systemAccountModel = new \app\admin\model\SystemTaobaoAccount();
        $accountInfo = $systemAccountModel->getTaoBaoAccountInfo($post['account_id']);
        $transferResult = $this->model->effectiveTransferLink($accountInfo, $post['link_url']);
        if($transferResult['code'] == 0){
            return $transferResult['data'];
        }
        $this->error('转链失败！');
        return false;
    }
    
    /**
     * @NodeAnotation(title="获取商品信息")
     */
    public function getGoodsInfo()
    {
        $post = $this->request->post();
        $keyName = $post['order_parameter'];
        $c = new \TopClient;
        $c->appkey = '27995746';//27995746
        $c->secretKey = 'e0a04dc52507b3b15a92fc56759279fb';//e0a04dc52507b3b15a92fc56759279fb
        $c->format = 'json';

        $req = new \TbkDgMaterialOptionalRequest;
        //$req->setFields("user_id,shop_title,shop_type,seller_nick,pict_url,shop_url");
        $req->setQ($keyName);
        $req->setIsTmall("true");
        $req->setAdzoneId('109596100446');
        $resp = $c->execute($req);
        $resultData = json_decode(json_encode($resp),true);
        return $resultData;
    }


    /**
     * @NodeAnotation(title="获取店铺信息")
     */
    public function getShopList()
    {
        $post = $this->request->post();
        $categoryName = $post['order_parameter'];
        $c = new \TopClient;
        $c->appkey = '27995746';//27995746
        $c->secretKey = 'e0a04dc52507b3b15a92fc56759279fb';//e0a04dc52507b3b15a92fc56759279fb
        $c->format = 'json';

        $req = new \TbkShopGetRequest;
        $req->setFields("user_id,shop_title,shop_type,seller_nick,pict_url,shop_url");
        $req->setQ($categoryName);
        $resp = $c->execute($req);
        $resultData = json_decode(json_encode($resp),true);
        return $resultData;
    }

    /**
     * @NodeAnotation(title="获取店铺联系原始数据")
     */
    //根据店铺ID获取原始加密信息
    public function getShopContactInfo()
    {
        $post = $this->request->post();
        if($post['shopid'] == null){
            return ['message'=>'error'];
        }
        $url = 'http://node.yiquntui.com/api/contentData?shopid='.$post['shopid'].'&sellerid='.$post['shopid'].'&ver=512';
        //$data['shopid'] = '2153863260';
        //$data['sellerid'] = '2153863260';
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
        //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
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

    /**
     * @NodeAnotation(title="解密")
     */
    public function resolve()
    {
        $post = $this->request->post();
        //解密操作
        $key = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $keyLegth = strlen($key);
        if($post['source_string'] == null){
            $this->error('解密信息为空！');
        }
        $resolveStr = '';
        for($j=0;$j<strlen($post['source_string']);$j++){
            if (0 != ($j + 1) % 4) {
                $resolveStr .= substr($post['source_string'],$j,1);
            }
        }
        $arrLength = floor(strlen($resolveStr)/3);
        $sourceArr = [];
        $loopNum = 0;
        for($i=0;$i<$arrLength;$i++){
            $str1 = strpos($key,substr($resolveStr,$loopNum,1));
            $loopNum++;
            $str2 = strpos($key,substr($resolveStr,$loopNum,1));
            $loopNum++;
            $str3 = strpos($key,substr($resolveStr,$loopNum,1));
            $loopNum++;
            $sourceArr[$i] = $str1*$keyLegth*$keyLegth + $str2*$keyLegth + $str3;
        }
        $retStr = '';
        foreach($sourceArr as $value){
            //eval("\$s = $value;");
            $retStr .= chr($value);
        }
        //$newStr = implode(',',$sourceArr);
        $resultStr = base64_decode($retStr);
        return $resultStr;
    }

    public function createTaokoulin()
    {
        $post = $this->request->post();
        //获取淘宝联盟账号信息
        $systemAccountModel = new \app\admin\model\SystemTaobaoAccount();
        $accountInfo = $systemAccountModel->getTaoBaoAccountInfo($post['account_id']);
        $transferResult = $this->model->effectiveTransferLink($accountInfo, $post['link_url']);
        if($transferResult['code'] == 0){
            return $transferResult['data'];
        }
        $this->error('转链失败！');
        return false;
    }

    public function receiveGroupMsg()
    {
        echo 111;die;
    }

    public function getOrderList()
    {
        //$result = $this->model->getOrderListInfo();
        $result = $this->model->getTaoLiJinOrderInfo('R8o2P4giCyWR5POEUgDA4GmWKpEqMu1M');
        return $result;

        //读取文档数据
        $filePath = '/tmp/wanggang.xls';
        $fileData = Excel::import($filePath);
        if($fileData){
            $dataHeader = $fileData[1];//var_dump($fileData);die;
            foreach($dataHeader as $num=>$content){
                $header[] = [$content,$num];
            }
            unset($fileData[1]);
            $newData = [];
            foreach($fileData as $keyNum=>$data){
                if($data[19] != null){
                    $taolijinInfo = $this->model->getTaoLiJinOrderInfo($data[19]);
                    if(array_key_exists('result', $taolijinInfo) && $taolijinInfo['result']['success'] == 'true'){
                        $fileData[$keyNum][21] = $taolijinInfo['result']['model']['unfreeze_amount'];
                        $fileData[$keyNum][22] = $taolijinInfo['result']['model']['unfreeze_num'];
                        $fileData[$keyNum][23] = $taolijinInfo['result']['model']['refund_amount'];
                        $fileData[$keyNum][24] = $taolijinInfo['result']['model']['refund_num'];
                        $fileData[$keyNum][25] = $taolijinInfo['result']['model']['alipay_amount'];
                        $fileData[$keyNum][26] = $taolijinInfo['result']['model']['use_amount'];
                        $fileData[$keyNum][27] = $taolijinInfo['result']['model']['use_num'];
                        $fileData[$keyNum][28] = $taolijinInfo['result']['model']['win_amount'];
                        $fileData[$keyNum][29] = $taolijinInfo['result']['model']['win_num'];
                        $fileData[$keyNum][30] = $taolijinInfo['result']['model']['pre_commission_amount'];
                        $fileData[$keyNum][31] = $taolijinInfo['result']['model']['fp_refund_amount'];
                        $fileData[$keyNum][32] = $taolijinInfo['result']['model']['fp_refund_num'];
                    }
                }
                $newData[] = $fileData[$keyNum];
                //break;
            }
            //var_dump($header);die;
            return Excel::exportData($newData, $header, 'lilutest', 'xls', '/tmp/lihaijuntest.xls');
        }
        return $fileData;
    }

    public function post($url,$post_data,$location = 0,$reffer = null,$origin = null,$host = null){
        $header = array( //头部信息，上面的函数已说明
            'Host: union.jd.com',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:84.0) Gecko/20100101 Firefox/84.0',
            'Accept: application/json, text/plain, */*',
            'Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
            'Accept-Encoding: gzip, deflate, br',
            'Content-Type: application/json;charset=UTF-8',
            //'Content-Length: 124',
            'Origin: https://union.jd.com',
            'Connection: keep-alive',
            'Referer: https://union.jd.com/proManager/shopPromotion',
        );
        $cookies = 'sidebarStatus=1; login=true; __jda=209449046.16104424801021108574731.1610442480.1610442480.1610445881.2; __jdc=209449046; __jdv=95931165|direct|-|none|-|1610442480103; RT="z=1&dm=jd.com&si=wzovz1p8vl&ss=kjtrv91j&sl=4&tt=bcw&ld=2t19z"; __jdu=16104424801021108574731; 3AB9D23F7A4B3C9B=ZXVO2UG3Z2NPYLXUOCZKZ2JKAKZ3PGXAOMDJC7OA3JJLS6ELY5PYEVJN2FB4VBONL75ZEUIGI4366KZXOAJL7OQKDQ; TrackID=1DTDitRHj6MlspWUqzO5rspl7-7-yoDElhFH_eNiDyTH1llTlYlS03GxQ8qWbQrduwXXmp_b2fUszURAyQ4HDYdKeweFoCHgZc4NJkqF64gQGrghpmIUb5GG4ATAMB4Nj; thor=11DD9BBC31FF5AD97B0BB40E2EF4DC93E55FBF664E3AF093CCC6B41ADA6729C2F64403EFAE809F65E90A79C0BB62BC41CB60FD830D3BC8C780F66BB07C8D5E05EB520DCF110F8E7C11A845A75316A1FCB96C51CDBBFF1315AB418818382FD61000F41316D91B3B5C6B25DDD27557EA635237A39FC3E9C35D1DB4334425CF9EFE7E495D41E1C06FDDCBBB659357590764B3D5DF7FE1DF02B7CFCD559DEFC7096C; pinId=opqFSajvl53clumdDlyqFbV9-x-f3wj7; pin=jd_5995fe627c4c7; unick=jd_5995fe627c4c7; ceshi3.com=000; _tp=REDSa9sQs20hr1mxLN5tR%2FSUjJCATdJNruslyVh026U%3D; _pst=jd_5995fe627c4c7; MMsgIdjd_5995fe627c4c7=718624; ssid="9N5D1fzLRbmqy5b72typfQ=="; MNoticeIdjd_5995fe627c4c7=311; __jdb=209449046.2.16104424801021108574731|2.1610445881';

        $curl = curl_init();  //这里并没有带参数初始化

        curl_setopt($curl, CURLOPT_URL, $url);//这里传入url

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);//对认证证书来源的检查，不开启次功能

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);//从证书中检测 SSL 加密算法

        //curl_setopt($curl, CURLOPT_USERAGENT, " Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:84.0) Gecko/20100101 Firefox/84.0");
        //模拟用户使用的浏览器，自己设置，我的是"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:23.0) Gecko/20100101 Firefox/23.0"

        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);//自动设置referer

        curl_setopt($curl, CURLOPT_POST, 1);//开启post

        curl_setopt($curl, CURLOPT_ENCODING, "gzip, deflate" );

        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);//要传送的数据

        curl_setopt($curl, CURLOPT_COOKIE, $cookies);//以变量形式发送cookie

        curl_setopt($curl, CURLOPT_TIMEOUT, 30);//设置超时限制，防止死循环

        curl_setopt($curl, CURLOPT_HEADER, 0);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        //$tmpInfo = curl_exec($curl);
        $output = curl_exec($curl);
        $a = curl_error($curl);
        if(!empty($a)){
            return array('code'=>-10003,'msg'=>$a);
        }
        curl_close($curl);
        $result = json_decode($output,true);
        return $result;
    }



    public function apiTest()
    {
        try{
            if(1){
                $this->error('正常错误提示');
            }
        }catch(\Exception $e){
            $this->error('抛出异常啦！');
        }
        $c = new \TopClient;
        $c->appkey = '27995746';//27995746
        $c->secretKey = 'e0a04dc52507b3b15a92fc56759279fb';//e0a04dc52507b3b15a92fc56759279fb
        $c->format = 'json';

        $req = new \TbkItemInfoGetRequest;
        //$req->setFields("user_id,shop_title,shop_type,seller_nick,pict_url,shop_url");
        $req->setNumIids("JyvG3Vuoh05eKyta2hvta-kA0AQ8T8g4vPQrGUe,aYvG3ySxh7d8YzI7zcjty-XvevZPf0dR9NV00sX");
        $req->setPlatform("1");
        $resp = $c->execute($req);
        $resultData = json_decode(json_encode($resp),true);
        var_export($resultData);



        die();
        //测试接口
        /*[
            'key' => null,
            'orientedFlag' => 0,
            'property' => 'inOrderComm30Days',
            'shopType' => 'g',
            'sort' => 'desc',
        ]*/
        $urls = "https://union.jd.com/api/shop/queryShopLists";//URL地址填这里
        $parameter = json_encode([
            'data' => [
                'key' => null,
                'orientedFlag' => 0,
                'property' => 'inOrderComm30Days',
                'shopType' => 'g',
                'sort' => 'desc',
            ],
            'pageNo' => 1,
            'pageSize' => 100
        ]);
        //$parameter = '{"data":{"key":null,"orientedFlag":0,"property":"inOrderComm30Days","shopType":"g","sort":"desc"},"pageNo":1,"pageSize":20}';
        $ret = $this->post($urls,$parameter);
        //var_export($ret);die;
        if($ret['code'] != 200 || $ret['data'] == null){
            return false;
        }
        $totalNum = $ret['data']['page']['totalCount'];
        $pageNum = ceil($totalNum/100);
        $header = [
            ['类型',0],['店铺名称',1],['评分',2],['订单量',3],['累计佣金',4],['平均佣金比率',5]
        ];
        $newData = [];
        for($num=1;$num<=$pageNum;$num++){
            $parameter = json_encode([
                'data' => [
                    'key' => null,
                    'orientedFlag' => 0,
                    'property' => 'inOrderComm30Days',
                    'shopType' => 'g',
                    'sort' => 'desc',
                ],
                'pageNo' => $num,
                'pageSize' => 100
            ]);
            $ret = $this->post($urls,$parameter);
            if(empty($ret['data']['unionShopInfos'])){
                continue;
            }
            $shopInfo = $ret['data']['unionShopInfos'];
            //var_dump($shopInfo);die;
            foreach($shopInfo as $key=>$item){
                $newData[] = [
                    $item['mainCategories'],$item['shopName'],$item['overAllRating'],$item['inOrderCount30Days'],$item['inOrderComm30Days'],$item['averageCommision']
                ];
            }
        }
        //var_dump($newData);die;
        Excel::exportData($newData, $header, 'lilutest', 'xls', '/tmp/lilutest.xls');
        var_export($ret);die;


        //拼接请求地址
        //$url = 'http://9644.g.xql.idouxiong.com/groupList';
        /*$url = 'http://9644.g.xql.idouxiong.com/getgroupusers';
        $data['sign'] = '136326';
        $data['groupid'] = '25210947590@chatroom';
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
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $output = curl_exec($ch);
        $a = curl_error($ch);
        if(!empty($a)){
            return json_encode(array('code'=>-10003,'msg'=>$a));
        }
        curl_close($ch);
        $result = json_decode($output,true);*/
        /*foreach($result as $memberInfo){
            echo base64_decode($memberInfo['Wxid'])."\r\n";
            echo base64_decode($memberInfo['Nick'])."\r\n";
        }*/
        /*if($result != null){
            $groupObj = new \app\admin\model\PublishWeixinGroups();
            foreach($result as $groupInfo){
                if($groupInfo['List'] < 50 || ($groupInfo['WxNo'] != 'wxid_tepiw30p5o2f22' && $groupInfo['WxNo'] != 'lhj849081948' && $groupInfo['WxNo'] != 'wxid_8p84mdon1mf322' && $groupInfo['WxNo'] != 'wxid_lknmw4yvk9rv12' && $groupInfo['WxNo'] != 'wxid_alzlbm7exbrv12')){
                    continue;
                }
                $currentGroup = $groupObj->where('weixin_room_id','=',$groupInfo['Wxid'])
                    ->find();

                if($currentGroup == null){
                    $insertData = [
                        'member_num' => $groupInfo['List'],
                        'source_type' => 3,
                        'group_type' => 0,
                        'weixin_room_id' => $groupInfo['Wxid'],
                        'weixin_room_name' => base64_encode($groupInfo['Nick']),
                        'weixin_master_id' => $groupInfo['WxNo'],
                        'create_time' => date('Y-m-d H:i:s')
                    ];
                    $ret = $groupObj->insertGetId($insertData);
                }else{
                    $updateData = [
                        'member_num' => $groupInfo['List'],
                        'weixin_room_name' => base64_encode($groupInfo['Nick']),
                        'weixin_master_id' => $groupInfo['WxNo'],
                    ];
                    $ret = $currentGroup->save($updateData);
                }
            }
        }*/
        return $result;
    }

    private function errorKouling(){
        return ['XNDgc3yLKpJ',
            'HwRDcYyIXKY',
            'q2JRD3yPKdP',
            'HVs8cDyrpDi',
            '1hovc3Drtd' ,
            'ZrZBD3yIqJ' ,
            'eUWhD3yIbm' ,
            'vIAOc3yIg1' ,
            'jWmDc3yITx' ,
            'uO6GD3yIru' ,
            'jDpYc3DyIdU',
            'yLi4c3SSM0',
            'JpRBc3yI5Dn',
            'uPW1c3DyIrN',
            'hUj6c3yISDA',
            'LTORc3yDIeq',
            'ip6Mc3DyISZ',
            'Am2sc38IbCH',
            'RiJ3c2yr5rl',
            'GD1c3hr3sk',
            'vguJc3yrUy',
            'ydvec3yrDI' ,
            'uY83c3SIYL',
            'CJmac3yrVo',
            'oS8Kc3yrSR',
            'tNBec3yrhp',
            'b0hWc3DrMk',
            'N6oZc3Dys19',
            'slKwc33yIN5',
            'nNjDc3y5IkY',
            '2X0Rc3y7IJn',
            'XHjT93yIoJF',
            'ftTac3H9sjOe',
            'mHtjc3TBw9X',
            '2pjnT3yzqU9',
            'zPzqT3yzzRy',
            'KuGncTyzalw',
            'm9Frc3TBEzj',
            'g6J7cTyzAVi',
            '0wEtc3TzZ6S',
            'SMe3cTyzEJG',
            'mgPiT3yAW7B',
            'sAQ9c3Hysd9D',
            'LtNyc3yAm4',
            'BrKVc3adun',
            'rTGQcyA7Mo',
            'Ovqg3BaT8S',
            'qe3nc3B189',
            'UahWc3BcAe',
            'DspPc3B1J7',
            'K6Idc3ac48',
            '5Bv4c3yGuX',
            'daR2c3B6kN',
            'VApMcBaJn8',
            'zgqmcJ3yI508',
            '9WD8c3yI0DH',
            'kFRGc5yI58k',
            'iz1TP0yI9ED',
            'MqHHT3yIFCj',
            'VITic3yznMm',
            'cKA0T3yzypO',
            'q6ZRc3TzdLJ',
            'uFTIc3Yzet6',
            'KIqec3TyzcOt',
            'aZ6qcT3yzwPl',
            'zDEKcR3yARI7',
            'n4O1cT3yAUCi',
            'lH8uc3TyAlkT',
            'T2amF3yzkJl' ,
            'U4JqQ3yAdqE' ,
            '9d2ac3QAa1s' ,
            'sUrZQ3yA3PM' ,
            '1Zgyc3yQ9Kq' ,
            'fWLRc3QAgas' ,
            'tLtXcE3yzMR8',
            '9FHVcQyA5sb' ,
            'E7Qfc3yzz0s' ,
            '3JZEc3yAJyN' ,
            'QFnYR3yAYOG' ,
            'INm5c1yzxWm' ,
            'aEDHcPyzur8' ,
            'hIxoc1yzB0q' ,
            'klg2c3yAZes' ,
            'hm3nc3yASA6' ,
            'gktyc3GzGAZ' ,
            'tyPccPyAsnL' ,];
    }

    
}




