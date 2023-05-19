<?php
declare (strict_types = 1);

namespace app\command;

use jianyan\excel\Excel;
use EasyAdmin\tool\CommonTool;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class Test extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('test')
            ->setDescription('the test command');        
    }

    protected function execute(Input $input, Output $output)
    {
    	// 指令输出
    	$output->writeln('test');
        $str = '{"data":[{"campaignName":"腾旭专享","bizCheckErrorInfoList":["亲，当前报名状态不支持该操作，请稍后刷新重试"],"campaignId":1001061502,"objectId":"706477837131","extInfo":{"title":"【520礼物 24h顺丰发货】TF新品黑金唇釉 129欢愉 121入戏122怦然","pictUrl":"//img.alicdn.com/bao/uploaded/i2/2200676153815/O1CN01DX3JrS1e3JotNCE9d_!!0-item_pic.jpg","auctionUrl":"//item.taobao.com/item.htm?id=706477837131","itemId":"706477837131"},"isSuccess":false,"objectType":3,"failReasonForShow":"亲，当前报名状态不支持该操作，请稍后刷新重试"},{"campaignName":"腾旭专享","bizCheckErrorInfoList":["亲，当前报名状态不支持该操作，请稍后刷新重试"],"campaignId":1001061502,"objectId":"587705855042","extInfo":{"title":"【520礼物 24h顺丰发货】TF唇香礼盒白麝香 香水口红套装高定刻字","pictUrl":"//img.alicdn.com/bao/uploaded/i2/2200676153815/O1CN01csHyG11e3Jp8QZWqs_!!0-item_pic.jpg","auctionUrl":"//item.taobao.com/item.htm?id=587705855042","itemId":"587705855042"},"isSuccess":false,"objectType":3,"failReasonForShow":"亲，当前报名状态不支持该操作，请稍后刷新重试"},{"campaignName":"腾旭专享","bizCheckErrorInfoList":["亲，当前报名状态不支持该操作，请稍后刷新重试"],"campaignId":1001061502,"objectId":"587551610385","extInfo":{"title":"【520礼物 24h顺丰发货】TF经典双支口红礼盒 金箔黑管 高定刻字","pictUrl":"//img.alicdn.com/bao/uploaded/i1/2200676153815/O1CN0189XpXz1e3Jp8mo8rO_!!0-item_pic.jpg","auctionUrl":"//item.taobao.com/item.htm?id=587551610385","itemId":"587551610385"},"isSuccess":false,"objectType":3,"failReasonForShow":"亲，当前报名状态不支持该操作，请稍后刷新重试"},{"campaignName":"腾旭专享","bizCheckErrorInfoList":["亲，当前报名状态不支持该操作，请稍后刷新重试"],"campaignId":1001061502,"objectId":"637911627764","extInfo":{"title":"【520礼物 24h顺丰发货】TF奢金气垫 持久防晒轻薄遮瑕保湿干皮","pictUrl":"//img.alicdn.com/bao/uploaded/i2/2200676153815/O1CN01NhxrNn1e3JoMECXy4_!!0-item_pic.jpg","auctionUrl":"//item.taobao.com/item.htm?id=637911627764","itemId":"637911627764"},"isSuccess":false,"objectType":3,"failReasonForShow":"亲，当前报名状态不支持该操作，请稍后刷新重试"},{"campaignName":"腾旭专享","bizCheckErrorInfoList":["亲，当前报名状态不支持该操作，请稍后刷新重试"],"campaignId":1001061502,"objectId":"676575774215","extInfo":{"title":"【520好礼速达】Aveda艾梵达头皮按摩气垫梳蓬松高颅顶造型梳子","pictUrl":"//img.alicdn.com/bao/uploaded/i4/2214076157032/O1CN01f3UeQH21ohyPzHdzK_!!0-item_pic.jpg","auctionUrl":"//item.taobao.com/item.htm?id=676575774215","itemId":"676575774215"},"isSuccess":false,"objectType":3,"failReasonForShow":"亲，当前报名状态不支持该操作，请稍后刷新重试"},{"campaignName":"腾旭专享","bizCheckErrorInfoList":["亲，当前报名状态不支持该操作，请稍后刷新重试"],"campaignId":1001061502,"objectId":"675292777457","extInfo":{"title":"【520好礼速达】Aveda艾梵达丰盈紫森林固发头皮精华高颅顶发量","pictUrl":"//img.alicdn.com/bao/uploaded/i2/2214076157032/O1CN01JnlwU521ohyN57sMz_!!0-item_pic.jpg","auctionUrl":"//item.taobao.com/item.htm?id=675292777457","itemId":"675292777457"},"isSuccess":false,"objectType":3,"failReasonForShow":"亲，当前报名状态不支持该操作，请稍后刷新重试"},{"campaignName":"腾旭专享","bizCheckErrorInfoList":["亲，当前报名状态不支持该操作，请稍后刷新重试"],"campaignId":1001061502,"objectId":"708808289807","extInfo":{"title":"【520好礼速达】Aveda艾梵达丰盈紫森林固发头皮精华高颅顶发量","pictUrl":"//img.alicdn.com/bao/uploaded/i1/2214076157032/O1CN01Ik7lAB21ohyL0dm5p_!!0-item_pic.jpg","auctionUrl":"//item.taobao.com/item.htm?id=708808289807","itemId":"708808289807"},"isSuccess":false,"objectType":3,"failReasonForShow":"亲，当前报名状态不支持该操作，请稍后刷新重试"}],"success":true,"resultCode":200,"bizErrorCode":0}';
        $arr1 = json_decode($str, true);
        var_export($arr1);die;

        /*$goodsObj = new \app\admin\model\MallTaolijinGoods();
        $ret = $goodsObj->where('account_id','=',13)
            ->where('create_time','>=','2020-12-01 00:00:00')
            ->where('create_time','<=','2020-12-31 23:59:59')
            ->field(['sales','per_face'])
            //->limit(1)
            ->select()
            ->toArray();
        $totalPrice = 0;
        $maxNum = 0;
        foreach($ret as $row){
            //var_export($row);die;
            $price = floatval(floatval($row['per_face'])*(intval($row['sales'])));
            if($price>$maxNum){
                $maxNum = $price;
            }
            $totalPrice += $price;
        }
        echo $totalPrice."\r\n";
        echo $maxNum;die;
        var_export($totalPrice);die;*/
    	//判断文件是否存在
        /*$filePath = '/tmp/shop.xlsx';
        if (!is_file($filePath)){
            $output->writeln('the file is not exist');
            return false;
        }
        $storeObj = new \app\admin\model\BusinessStore();
        $fileData = Excel::import($filePath);
        if($fileData != null){
            unset($fileData[1]);
            foreach ($fileData as $storeInfo){
                $existRet = $storeObj->where('title', '=', $storeInfo[0])->find();
                if(!$existRet && $storeInfo[0] != null){
                    $output->writeln($storeInfo[0]);
                    $inserData = [
                        'title' => $storeInfo[0],
                        'creater_id' => 1,
                        'create_time' => date('Y-m-d H:i:s'),
                    ];
                    $ret = $storeObj->insert($inserData);
                }
            }
        }
        if($ret){
            $output->writeln('the file import successfully');
        }else{
            $output->writeln('the file import faith');
        }*/
        $this->exportBusinessData();
        $this->exportSaleData();

        /*$webhook = "https://oapi.dingtalk.com/robot/send?access_token=d61f7604aa6dfc87d2052d19ed4b29d24463ffdbfe6257068e4e6fad0cf1d4c5";
        $message="我就是我, 是不一样的烟火";
        $data = array ('msgtype' => 'text','text' => array ('content' => $message));
        $data_string = json_encode($data);

        $result = $this->request_by_curl($webhook, $data_string);
        $output->writeln($result);*/

        return true;
    }

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

    public function request_by_curl($remote_server, $post_string) {
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

    public function update()
    {
        //获取已有的商家数据
        $storeObj = new \app\admin\model\BusinessStore();
        $storeArr = $storeObj->where('id','>=','7226')//7223、
            ->where('id','<=','15093')
            ->where('shop_id','>',0)
            ->select()
            ->toArray();

        //导出文件方法
        /*$tableName = $storeObj->getName();
        $tableName = CommonTool::humpToLine(lcfirst($tableName));
        $prefix = config('database.connections.mysql.prefix');
        $dbList = Db::query("show full columns from {$prefix}{$tableName}");
        $header = [];
        $size = 10000;
        foreach ($dbList as $vo) {
            $comment = !empty($vo['Comment']) ? $vo['Comment'] : $vo['Field'];
            $header[$comment] = 'string';
        }
        $writer = new \XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header );
        foreach($storeArr as $storeInfo){
            $writer->writeSheetRow('Sheet1', $storeInfo );
        }
        $filePath = '/tmp/shop123.xlsx';
        $writer->writeToFile($filePath);
        return true;*/

        $loopnum = 0;
        //更新店铺ID
        /*$taobaoApiObj = new \app\admin\model\MallTaolijinGoods();
        foreach($storeArr as $storeInfo){
            $storeResult = $taobaoApiObj->getShopInfo($storeInfo['title']);
            //var_export($storeResult);die;
            if($storeResult['total_results'] == 0){
                continue;
            }
            echo $storeInfo['title'].$loopnum."\r\n";
            foreach($storeResult['results']['n_tbk_shop'] as $keyNum=>$shopInfo){
                if($shopInfo['shop_title'] == $storeInfo['title']){
                    $updateData['shop_id'] = $storeResult['results']['n_tbk_shop'][$keyNum]['user_id'];
                    break;
                }
            }
            $save = $storeObj::update($updateData,['id'=>$storeInfo['id']]);
            $loopnum++;
        }
        echo "商家ID数据更新完毕！ \r\n";
        return false;*/
        $shopContactInfo = [];
        foreach($storeArr as $storeInfo){
            $updateData = [];
            $shopContactInfo = $this->getPublicShopContactInfo($storeInfo['shop_id'],'eb3d85b6b0350');
            //var_export($shopContactInfo);die;
            /*if(!is_array($shopContactInfo)){
                $shopContactInfo = $this->getPublicShopContactInfo($storeInfo['shop_id'],'f3e943131733a');
            }*/
            if(!is_array($shopContactInfo)){
                continue;
            }
            if(!array_key_exists('data',$shopContactInfo)){
                continue;
            }
            if($shopContactInfo['info']['ok'] != 'true'){
                continue;
            }

            if(array_key_exists('QQ',$shopContactInfo['data']['card']['jsonData']) && $shopContactInfo['data']['card']['jsonData']['QQ'] != ''){
                $updateData['qq_number'] = $shopContactInfo['data']['card']['jsonData']['QQ'];
            }
            if(array_key_exists('微信',$shopContactInfo['data']['card']['jsonData']) && $shopContactInfo['data']['card']['jsonData']['微信'] != ''){
                $updateData['weixin'] = $shopContactInfo['data']['card']['jsonData']['微信'];
            }
            $updateData['detail'] = $shopContactInfo['data']['card']['content'];

            //var_dump($updateData);die;
            $save = $storeObj::update($updateData,['id'=>$storeInfo['id']]);
            //var_dump($save);die;
            $loopnum++;
            //usleep(300000);
            echo $storeInfo['title'].'|'.$loopnum."\r\n";
        }
        $save ? $this->success('更新成功'.$loopnum) : $this->error('更新失败'.$loopnum);

    }

    //根据店铺ID获取公共店铺联系方式信息
    public function getPublicShopContactInfo($shopId,$token)
    {
        if($shopId == null){
            return ['message'=>'error'];
        }
        $timeStr = $this->msectime();

        $url = 'https://pub.alimama.com/shopdetail/shopinfo.json?oriMemberId='.$shopId.'&t='.$timeStr.'&pvid=undefined&_tb_token_='.$token.'&_input_charset=utf-8';
        //$data['shopid'] = '2153863260';
        //$data['sellerid'] = '2153863260';
        $cookies = 't=903865ab2fa4fcbf20274a49b3b0960f; cna=jrcVFkkHOgoCAXWY8dpsCkSG; _ga=GA1.2.1709063850.1613631129; _gid=GA1.2.904003747.1613631129; __guid=116136583.2915079402919763500.1613631245428.83; account-path-guide-s1=true; enc=ZjRvEh1dz8pwq1fOpVLnP6%2FpBpQoln99pOzt7R35lo2tDtBCoggnEDRzZdgUsTf2Kmfx0m%2FNRHSFZ7JY2PNKYA%3D%3D; pub-message-center=1; v=0; cookie2=10c01e7fd5bcf44b4ae01a0f550df71f; _tb_token_=5e388d9bd1e0; xlly_s=1; alimamapwag=TW96aWxsYS81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV09XNjQpIEFwcGxlV2ViS2l0LzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZS83OC4wLjM5MDQuMTA4IFNhZmFyaS81MzcuMzY%3D; cookie32=10ccba842ca5610cb7c0a618575ea16b; alimamapw=QHIhHCQmECJTRHELRnsMQycDQHMlHCdbECVWRHcOaggBAlYCBAdTCQBXAl9WAlMIAAsDVVFWVAdV%0AX1ZTUQMG; cookie31=MTE5NTE4NjQxLCVFNSU5MCU5QiVFNSVBRCU5MCVFNSVBNiU4MiVFNCVCOSVBNiVFOSU5QyVCMiw4MzcwNzA3NTZAcXEuY29tLFRC; login=W5iHLLyFOGW7aA%3D%3D; monitor_count=19; _gat_UA-141509622-12=1; x5sec=7b22756e696f6e2d7075623b32223a223765396537363132316533653335656236663439393966623266313131346331434f664676594547454b4f582f4a6670687158445a7a44436c372b4941773d3d227d; tfstk=cbrABgAeX_f0GnH8LrQk1MgY-TVOZ8ftNKGMX9vI_uld5vxOijZ3vsFClAMW4TC..; l=eBQr_UuPv0Zx_f_DBOfZourza77T7IRAguPzaNbMiOCP9v1W5rpFW6i3OuYXCnGVhsgkR3rEQAfvBeYBqhfnZrn_uBaE3jHmn; isg=BN7eb-LuMiYGOWbDtk84XS3eL3Qgn6IZJxeukohnVyEcq36F8C1FKzRBp7enk5ox';

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

    public function getShopContactInfo($shopId)
    {
        if($shopId == null){
            return ['message'=>'error'];
        }
        $url = 'http://node.yiquntui.com/api/contentData?shopid='.$shopId.'&sellerid='.$shopId;
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

    //返回当前的毫秒时间戳
    public function msectime()
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }

    //导出招商数据
    public function exportBusinessData()
    {
        $header = [];
        $eventIdArr = [
            '388303308',
            '388313004',
            '388249673',
            '388259572'
            ];
        $token = 'e3a5e3eee5e57';
        $cookies = 't=0ecdf92bab2384b3d0eea6ae7cc63597; cna=KMKcFtLcaGECASvzDBuMU/0O; 120343104-payment-time=true; account-path-guide-s1=true; _ga=GA1.1.1362209978.1625371690; _ga_Q39CBKW296=GS1.1.1625371689.1.1.1625371989.0; Hm_lvt_5d25c62064eb6506230d2e102c202eb9=1625371825,1625372217,1627188052,1627188067; enc=Gbx542M1WlrRxwgbcL3SD3v9pNy6NkAx0Vvp7qK9PF6writHCSUCjrkAymyy6LawPkcx5lDj%2FscZXmKzxiw0Tw%3D%3D; v=0; cookie2=15611cdf274b2895cc0b6e193009a8aa; _tb_token_=58137d68b53e3; JSESSIONID=AFE600C6D5FA5998E7E96CBF9B45CB53; xlly_s=1; alimamapwag=TW96aWxsYS81LjAgKE1hY2ludG9zaDsgSW50ZWwgTWFjIE9TIFggMTBfMTVfNykgQXBwbGVXZWJLaXQvNTM3LjM2IChLSFRNTCwgbGlrZSBHZWNrbykgQ2hyb21lLzkyLjAuNDUxNS4xMDcgU2FmYXJpLzUzNy4zNg%3D%3D; cookie32=0c8e6a49eda9f71bc61730e277d6ab3a; alimamapw=Q1VWAFgFAAFqVlVXCjwEB1MEAVZVBVFXAAMMVgYACVYHBQQDVQZVAwcBBFBQBg%3D%3D; cookie31=MTIwMzQzMTA0LHRiNDI5MDU1XzIwMTMsMTE3NDgxMjgzN0BxcS5jb20sVEI%3D; login=V32FPkk%2Fw0dUvg%3D%3D; rurl=aHR0cHM6Ly9wdWIuYWxpbWFtYS5jb20v; tfstk=c_ihBVMXo51XtcailHZILWDkqFxAwmbUO0oKb0ez6j-wWF10BN7qJmvg_XmRP; isg=BMTEs81feaf8nPKYJRRxXmf-lUS23ehHDgtsZN5lUA9SCWTTBu241_qrSaHRCiCf; l=eBxKsDruQJfumdD9BOfanurza77OSIRYYuPzaNbMiOCP_y5B5_yGW6HAznL6C3GVh60wR3J6nmcvBeYBcQAonxvTHwMHV3Mmn';

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
        $filePath = '/tmp/businessFile.xlsx';
        $writer->writeToFile($filePath);
        return true;
    }

    //导出推广效果数据
    public function exportSaleData()
    {
        $header = [];
        $eventIdArr = [
            '388303308',
            '388313004',
            '388249673',
            '388259572'
        ];
        $token = 'e3a5e3eee5e57';
        $cookies = 't=0ecdf92bab2384b3d0eea6ae7cc63597; cna=KMKcFtLcaGECASvzDBuMU/0O; 120343104-payment-time=true; account-path-guide-s1=true; _ga=GA1.1.1362209978.1625371690; _ga_Q39CBKW296=GS1.1.1625371689.1.1.1625371989.0; Hm_lvt_5d25c62064eb6506230d2e102c202eb9=1625371825,1625372217,1627188052,1627188067; enc=Gbx542M1WlrRxwgbcL3SD3v9pNy6NkAx0Vvp7qK9PF6writHCSUCjrkAymyy6LawPkcx5lDj%2FscZXmKzxiw0Tw%3D%3D; v=0; cookie2=15611cdf274b2895cc0b6e193009a8aa; _tb_token_=58137d68b53e3; JSESSIONID=AFE600C6D5FA5998E7E96CBF9B45CB53; xlly_s=1; alimamapwag=TW96aWxsYS81LjAgKE1hY2ludG9zaDsgSW50ZWwgTWFjIE9TIFggMTBfMTVfNykgQXBwbGVXZWJLaXQvNTM3LjM2IChLSFRNTCwgbGlrZSBHZWNrbykgQ2hyb21lLzkyLjAuNDUxNS4xMDcgU2FmYXJpLzUzNy4zNg%3D%3D; cookie32=0c8e6a49eda9f71bc61730e277d6ab3a; alimamapw=Q1VWAFgFAAFqVlVXCjwEB1MEAVZVBVFXAAMMVgYACVYHBQQDVQZVAwcBBFBQBg%3D%3D; cookie31=MTIwMzQzMTA0LHRiNDI5MDU1XzIwMTMsMTE3NDgxMjgzN0BxcS5jb20sVEI%3D; login=V32FPkk%2Fw0dUvg%3D%3D; rurl=aHR0cHM6Ly9wdWIuYWxpbWFtYS5jb20v; tfstk=c_ihBVMXo51XtcailHZILWDkqFxAwmbUO0oKb0ez6j-wWF10BN7qJmvg_XmRP; isg=BMTEs81feaf8nPKYJRRxXmf-lUS23ehHDgtsZN5lUA9SCWTTBu241_qrSaHRCiCf; l=eBxKsDruQJfumdD9BOfanurza77OSIRYYuPzaNbMiOCP_y5B5_yGW6HAznL6C3GVh60wR3J6nmcvBeYBcQAonxvTHwMHV3Mmn';

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
        $filePath = '/tmp/saleFile.xlsx';
        $writer->writeToFile($filePath);
        return true;
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


}
