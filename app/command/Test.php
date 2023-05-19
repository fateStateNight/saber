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

        /*$businessGoodsModel = new \app\admin\model\BusinessGoods();

        $tableName = $businessGoodsModel->getName();
        $tableName = CommonTool::humpToLine(lcfirst($tableName));
        $prefix = config('database.connections.mysql.prefix');
        $dbList = Db::query("show full columns from {$prefix}{$tableName}");
        $header = [];
        foreach ($dbList as $vo) {
            $comment = !empty($vo['Comment']) ? $vo['Comment'] : $vo['Field'];
            if (in_array($vo['Field'], ['shopTitle','alipayNum','endTime'])) {
                $header[] = [$comment, $vo['Field']];
            }
        }
        $dataResult = $businessGoodsModel//->whereTime('endTime','>=','2023-04-24 23:59:59')
            ->whereTime('endTime','>=','2023-04-01 00:00:00')
            ->group('shopTitle')
            ->fieldRaw('shopTitle,cast(SUM(alipayNum) as SIGNED) as alipayNum, MAX(endTime) as endTime')
            ->select()
            ->toArray();*/
        /*foreach($dataResult as $item){
            if($item['alipayNum'] < 20){
                $list[] = $item;
            }
        }*/
        //var_export($list);die;
        /*$fileName = 'goodsExcel';
        Excel::exportData($dataResult, $header, $fileName, 'xlsx','/tmp/goodsExcel.xlsx');
        echo "导出完成";
        die;*/

        //$this->update();
        //$businessGoodsModel = new \app\admin\model\BusinessGoods();
        //$cookie = 't=b7036dbdb4e187e4910b2fa587168791;cookie2=1ab8b72f3f71dc487ec0f05706797249;_tb_token_=e17bbe63a7e15;cna=qW8UG1khD14CAXdgKDspYKXK;Hm_lvt_1ff3673d088290dff716d51fd23686e5=1653399631,1653440938,1653441254,1653527366;xlly_s=1;_ga=GA1.2.329229243.1653527367;_gid=GA1.2.631358527.1653527367;v=0;alimamapwag=TW96aWxsYS81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV09XNjQpIEFwcGxlV2ViS2l0LzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZS84Ni4wLjQyNDAuMTk4IFNhZmFyaS81MzcuMzY%3D;cookie32=0c8e6a49eda9f71bc61730e277d6ab3a;alimamapw=FVYDVg9RBw1mB1VSAzpWAVIHV1UAUwYDBw8ABwYFAFBVAwUAAwUAVVBVA1xcVw%3D%3D;cookie31=MTIwMzQzMTA0LHRiNDI5MDU1XzIwMTMsMTE3NDgxMjgzN0BxcS5jb20sVEI%3D;login=W5iHLLyFOGW7aA%3D%3D;enc=80e435wNsDAq6qCKw5g882I5icP08NTJutygYX%2Bzw%2Bsi6ZFnaALPu07dd1cX76FbD8NwinLr99Dc16PxP5sE0w%3D%3D;Hm_lpvt_1ff3673d088290dff716d51fd23686e5=1653534102;isg=BFVVgNFmmxquIIEM4cRaWr-fZFEPUglkHm_r4df6EUwbLnUgn6IZNGPs_DKYNSEc;l=eBPAMfC4v5jgYvMzBOfanurza77OSIRYYuPzaNbMiOCPOUCB5MzNW6fUmAY6C3GVh64XR37vCcawBeYBqQAonxvTHwMHV3Mmn;tfstk=cNaPBIACAaQPwR0CX4gFdGgKY8VRwdVutElsqOb_gHM0SbfmuphcZlcqOYJsq;';
        //$ret = $businessGoodsModel->getItemEffectInfo('e17bbe63a7e15',$cookie,'389734160');
        //$dataRet = $businessGoodsModel->where('startTime', '>', '2022-09-01 00:00:00')->where('signUpRecordId','>',0)->whereNotNull('auctionUrl')
            //->limit(1)
            //->select()
            //->toArray();
        //var_export($dataRet);die;
        /*if($dataRet != null){
            foreach($dataRet as $data){
                $urlArr = $this->getParams($data['auctionUrl']);
                if(strpos($urlArr['id'],'-') !== false){
                    $itemArr = explode('-',$urlArr['id']);
                    $updateData = [
                        'mktItemId' => $itemArr[1],
                    ];
                    $businessGoodsModel::update($updateData,['id' => $data['id']]);
                    echo $urlArr['id']."\r\n";
                }
            }
        }*/
        //var_export($ret);die;
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
        //$this->exportBusinessData();
        //$this->exportSaleData();

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
        //$shopIDArr = $this->getshopIDArr();
        $storeArr = $storeObj
            //->where('id','>=','7226')
            ->where('total_commission','>','0')
            ->where('shop_id','>',0)
            //->whereIn('shop_id', $shopIDArr)
            ->orderRaw("`total_commission`+0 desc")
            ->limit(5000)
            //->limit(200000,50000)
            ->select()
            ->toArray();
        if($storeArr != null){
            $num = 0;
            foreach ($storeArr as $value){
                if($value['update_time'] < '2023-02-24 00:00:00'){
                    $num++;
                    $noStoreArr[] = $value;
                    //echo "商家名称：".$value['title']."  商家佣金：".$value['total_commission']."\r\n";
                }
                if($num > 100){
                    break;
                }
            }
        }
        echo $num;
        //return false;
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
        echo "2222\r\n";
        $writer->writeSheetHeader('Sheet1', $header );
        //foreach($storeArr as $storeInfo){
        //    $writer->writeSheetRow('Sheet1', $storeInfo );
        //}
        for($loop=0;$loop<=0;$loop++){
            $storeArr = $storeObj
                ->where('total_commission','>','0')
                ->where('shop_id','>',0)
                //->where('update_time','>','2023-02-24 00:00:00')
                ->orderRaw("`total_commission`+0 desc")
                ->limit(5000)
                //->limit($size*$loop,$size)
                ->select()
                ->toArray();
            echo $loop."\r\n";
            foreach($storeArr as $storeInfo){
                if($storeInfo['update_time'] < '2023-02-24 00:00:00'){
                    $writer->writeSheetRow('Sheet1', $storeInfo );
                }
            }
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
        echo '1111'."\r\n";
        $shopContactInfo = [];
        foreach($noStoreArr as $keynum=>$storeInfo){
            $updateData = [];
            echo "商家名称：".$storeInfo['title']."  商家佣金：".$storeInfo['total_commission']."\r\n";
            $shopContactInfo = $this-> getPublicShopContactInfo($storeInfo['shop_id'],'eb17be111bee5');
            if($keynum == 4){
                var_export($shopContactInfo);die;
            }else{
                continue;
            }
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
            if(!is_array($shopContactInfo['data']['cpsSh30data'])){
                continue;
            }
            if(array_key_exists('thirtyCmCommisionAmt',$shopContactInfo['data']['cpsSh30data'])){
                $updateData['total_commission'] = $shopContactInfo['data']['cpsSh30data']['thirtyCmCommisionAmt'];
            }
            /*if(array_key_exists('微信',$shopContactInfo['data']['card']['jsonData']) && $shopContactInfo['data']['card']['jsonData']['微信'] != ''){
                $updateData['weixin'] = $shopContactInfo['data']['card']['jsonData']['微信'];
            }
            $updateData['detail'] = $shopContactInfo['data']['card']['content'];*/
            //var_dump($updateData);die;
            $save = $storeObj::update($updateData,['id'=>$storeInfo['id']]);
            //var_dump($save);die;
            $loopnum++;
            //usleep(300000);
            echo $storeInfo['title'].'|'.$loopnum."\r\n";
        }
        echo '更新成功'.$loopnum."\r\n";
        //$save ? $this->success('更新成功'.$loopnum) : $this->error('更新失败'.$loopnum);
    }

    //根据店铺ID获取公共店铺联系方式信息
    public function getPublicShopContactInfo($shopId,$token)
    {
        if($shopId == null){
            return ['message'=>'error'];
        }
        $timeStr = $this->msectime();
        //$url = 'https://pub.alimama.com/shopdetail/shopinfo.json?oriMemberId='.$shopId.'&t='.$timeStr.'&pvid=undefined&_tb_token_='.$token.'&_input_charset=utf-8';
        $url = 'https://pub.alimama.com/shopdetail/keeper30DayRpt.json?oriMemberId='.$shopId.'&t='.$timeStr.'&pvid=undefined&_tb_token_='.$token.'&_input_charset=utf-8';
        //$data['shopid'] = '2153863260';
        //$data['sellerid'] = '2153863260';
        $cookies = 't=0e8be2df669f49ea41fd2b83cd859189; cna=+vscGbxObiQCARsTbHuOqLq+; _ga=GA1.1.664267944.1622714210; _ga_Q39CBKW296=GS1.1.1625724227.5.1.1625724303.0; t_alimama=3be8245eace528c826f0ea36cf872870; v_alimama=0; cookie2_alimama=1ebe15d5ef236ea76ab8b9602a3cd0e3; _tb_token_=7bbae553b8ef3; alimamapwag=TW96aWxsYS81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV2luNjQ7IHg2NCkgQXBwbGVXZWJLaXQvNTM3LjM2IChLSFRNTCwgbGlrZSBHZWNrbykgQ2hyb21lLzExMC4wLjAuMCBTYWZhcmkvNTM3LjM2; cookie32=0c8e6a49eda9f71bc61730e277d6ab3a; alimamapw=QFJWVAwHVwNtAwYEA2wFAl1WAlFVUQVVVwELA1VTAAYGAApRVgFVV1MDU1JXUw%3D%3D; cookie31=MTIwMzQzMTA0LHRiNDI5MDU1XzIwMTMsMTE3NDgxMjgzN0BxcS5jb20sVEI%3D; JSESSIONID=BA832743E085C467594F2B14EB026173; login=W5iHLLyFOGW7aA%3D%3D; xlly_s=1; isg=BGJi36-HcoMkgGpZewqPGYH0s-jEs2bNVM8XVKz7clWAfwP5lUPf3VK9r7uD795l; l=fB_HazBuj3jPgcQzBO5aourza77O6IRb8sPzaNbMiIEGa6sf9F1NENCs8-owWdtjgTCjletrl5ZFzdBzZ34daxDDBeXFfk5InxvtaQtJe; tfstk=c61PBFDaS7FrwU_LXIAF0j8v8AARa3Ql-j86Ekn-rAs3IRpMbsjjvE4rFE8IuDvl.';
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

    public function getshopIDArr()
    {
        return ['725677994',
            '2928278102',
            '2208831714149',
            '3937219703',
            '700459267',
            '749391658',
            '1975328959',
            '2738112600',
            '4144020062',
            '4022768816',
            '698461607',
            '92688455',
            '2200657724895',
            '4097272169',
            '2920895043',
            '2248304671',
            '2200654193901',
            '385132127',
            '3164711246',
            '1652554937',
            '3383443642',
            '2176017828',
            '94504595',
            '1664434765',
            '3527212490',
            '3102239719',
            '2203738468138',
            '2301367418',
            '2206405044336',
            '2978398582',
            '2200828292428',
            '3035493001',
            '1071280830',
            '353571709',
            '901409638',
            '2200676434400',
            '685092642',
            '2670735798',
            '2208027572168',
            '925814798',
            '880496338',
            '705235443',
            '746866993',
            '2259702327',
            '2259702327',
            '2528254892',
            '2786278078',
            '2995317050',
            '4129197626',
            '4129197626',
            '4204429722',
            '2560464671',
            '2207697805232',
            '1652864050',
            '839648224',
            '3321924298',
            '2206479404251',
            '355739614',
            '441622457',
            '1023696028',
            '2207138955258',
            '808734231',
            '2200793280818',
            '3423372923',
            '4147285566',
            '92686194',
            '379833581',
            '2200657724932',
            '641725918',
            '641725918',
            '755731755',
            '3423214569',
            '1712235030',
            '2206666168153',
            '2776482990',
            '3542829384',
            '2210843787782',
            '1986899349',
            '1785158051',
            '4008346408',
            '3369266294',
            '2261867204',
            '761942232',
            '839895996',
            '839895996',
            '1955943953',
            '3277585225',
            '1720075117',
            '2978671516',
            '845001562',
            '845001562',
            '520557274',
            '520557274',
            '4203998950',
            '3108435794',
            '858704520',
            '188124207',
            '2201498506788',
            '2894473266',
            '820521956',
            '822280525',
            '2091012777',
            '2457133736',
            '2129855716',
            '2135884598',
            '667454208',
            '2158075215',
            '2646612614',
            '2646612614',
            '699634751',
            '515369883',
            '2776344402',
            '743750137',
            '3278510613',
            '363607599',
            '2208657215455',
            '1046707508',
            '114141735',
            '2126650380',
            '2597705728',
            '165229494',
            '165229494',
            '1050505647',
            '2316003304',
            '392147177',
            '2191867066',
            '2191867066',
            '1042173208',
            '706084574',
            '706084574',
            '4263377845',
            '2405035918',
            '4207869750',
            '2201188731272',
            '394316726',
            '407916093',
            '2200657715182',
            '2785135471',
            '2785135471',
            '3978789820',
            '2807304908',
            '2207334181454',
            '2200552342840',
            '1761495540',
            '2210144838464',
            '2208156289515',
            '3301608143',
            '268691146',
            '719673819',
            '2204177871674',
            '4131045117',
            '2211907733473',
            '2201401720384',
            '2207340518885',
            '2707252427',
            '2926596407',
            '3189770892',
            '2206419108171',
            '2200618401076',
            '2279845441',
            '431979042',
            '2058238458',
            '2908951916',
            '2207716435515',
            '2142811280',
            '707890732',
            '2200766936524',
            '745949152',
            '3017400344',
            '2429534785',
            '748612647',
            '1818112330',
            '3099687535',
            '3099687535',
            '1057559553',
            '755579902',
            '2206385106039',
            '90919986',
            '92042735',
            '2222906716',
            '2207044164293',
            '676606897',
            '2200554932955',
            '1771485843',
            '407915987',
            '2201197361192',
            '2201197361192',
            '1029624918',
            '1705229452',
            '3371569905',
            '2200606797002',
            '2194426048',
            '2211910841304',
            '1602582004',
            '1602582004',
            '734584252',
            '734584252',
            '4004614770',
            '1824836051',
            '1737894507',
            '4065642251',
            '2113524478',
            '3599799148',
            '2674831085',
            '2207927254264',
            '2208170228854',
            '2207405595194',
            '925772082',
            '2206528468880',
            '4187840797',
            '1910949384',
            '126446588',
            '2208805709778',
            '2791921716',
            '673558948',
            '4066234693',
            '2967843444',
            '3173651031',
            '3173651031',
            '2098049097',
            '2508879272',
            '2793447635',
            '1588446985',
            '2206483780294',
            '2204174570383',
            '279887075',
            '787781047',
            '690315933',
            '693060164',
            '2209619192244',
            '2605193237',
            '2605193237',
            '1740894788',
            '479966771',
            '726984974',
            '897609396',
            '1751752896',
            '202224264',
            '740958699',
            '3913958532',
            '2074450097',
            '2200736037156',
            '2201505322719',
            '797517839',
            '2120683484',
            '1743582420',
            '1635636237',
            '1635636237',
            '4037320291',
            '4221684868',
            '4140235785',
            '4142775121',
            '1970838305',
            '167873659',
            '3170105444',
            '3641607835',
            '2820842454',
            '2209082384466',
            '2200573698992',
            '379092709',
            '1761902730',
            '508370014',
            '645039969',
            '1954078037',
            '458694874',
            '2200580634753',
            '2200580634753',
            '2509849149',
            '859230932',
            '2429621953',
            '776151038',
            '2207611419647',
            '3052400953',
            '2206514750748',
            '2340826582',
            '758644019',
            '916243692',
            '2927731773',
            '684553196',
            '883737303',
            '883737303',
            '2211535667469',
            '4029100736',
            '1940724523',
            '1940724523',
            '1700389626',
            '2215679551',
            '2207011968326',
            '4002489870',
            '2189874470',
            '2468261592',
            '4213179950',
            '260030441',
            '821690375',
            '3933984462',
            '3933984462',
            '1918385168',
            '2690231046',
            '2944743230',
            '3311179966',
            '3311179966',
            '1652553642',
            '1975427752',
            '1703307078',
            '1910887896',
            '2208935548741',
            '1711188389',
            '2794371653',
            '2794371653',
            '3932996313',
            '2757170196',
            '3981784227',
            '575083143',
            '739643635',
            '2200752064827',
            '671237914',
            '2212220054549',
            '2206427912415',
            '1122313708',
            '2107759029',
            '2204110765676',
            '2207910780404',
            '2984496242',
            '2201511327219',
            '2867731987',
            '305358018',
            '2114790937',
            '648476316',
            '3247031315',
            '3516216679',
            '3087951928',
            '91978383',
            '2120270425',
            '3157354417',
            '665542280',
            '2207945882640',
            '253285776',
            '3027922969',
            '2206737128417',
            '2206737128417',
            '92592768',
            '2201478854493',
            '353042353',
            '353042353',
            '2746434747',
            '2209424158',
            '2208158372418',
            '749385259',
            '374544688',
            '640739236',
            '1615368232',
            '1985641828',
            '3618771079',
            '2201304026163',
            '2207325464872',
            '1633803546',
            '429413615',
            '2206499432722',
            '500327991',
            '3027071523',
            '762351591',
            '2177009988',
            '3671431043',
            '3679135701',
            '2201168176213',
            '3948263976',
            '2108347738',
            '836315067',
            '2204152206427',
            '1028002856',
            '2206674670291',
            '3947124211',
            '3947124211',
            '2203090716636',
            '1943402959',
            '641875610',
            '2519768560',
            '1771842251',
            '2208389008910',
            '2112191563',
            '288885616',
            '288885616',
            '3838638297',
            '667286523',
            '2884685500',
            '2210967952007',
            '2067077515',
            '1665051977',
            '2208378550587',
            '2184341780',
            '2211677693484',
            '3012497603',
            '4091091457',
            '661559176',
            '2582702075',
            '2207922362722',
            '909438520',
            '2208415136522',
            '2208415136522',
            '2200758836245',
            '2500428739',
            '2500428739',
            '2621993737',
            '765321201',
            '756239978',
            '2212081375076',
            '765922982',
            '2798015967',
            '2205003042845',
            '2209050463674',
            '2208006954169',
            '397259828',
            '362409818',
            '2208697331675',
            '1077716829',
            '2930255252',
            '730053232',
            '2207995746380',
            '2424338511',
            '2424338511',
            '746173362',
            '1770528988',
            '279512537',
            '3555065600',
            '2200827831140',
            '298362896',
            '1672786620',
            '4143479973',
            '928417138',
            '928417138',
            '1625276795',
            '2291095291',
            '2115733820',
            '2380416887',
            '2129228676',
            '2073941317',
            '2073941317',
            '2207371648908',
            '814886726',
            '1666725436',
            '2201211920228',
            '740469814',
            '1640032446',
            '3087037216',
            '2455164953',
            '2200723927210',
            '1123492339',
            '2206523887236',
            '3949239623',
            '732501769',
            '485834253',
            '101450072',
            '828233086',
            '3283643000',
            '3885611078',
            '4163175068',
            '109723052',
            '2089529736',
            '2200706521386',
            '4275294927',
            '2208254892175',
            '3782289210',
            '2201505759031',
            '2201505759031',
            '688040957',
            '2844431906',
            '856460207',
            '729894901',
            '820804584',
            '2200655926572',
            '2207964723253',
            '2576722561',
            '2206358644076',
            '401420443',
            '4137593233',
            '2212088256693',
            '2735822823',
            '3159735360',
            '3991255852',
            '2193547442',
            '685563493',
            '2206678206615',
            '2830087192',
            '713464357',
            '4254205908',
            '2208028702568',
            '2200676153815',
            '2200676153815',
            '742982950',
            '2200537947339',
            '2207959261164',
            '2207959261164',
            '585646737',
            '441068731',
            '3817175345',
            '407446250',
            '2765010761',
            '2770381889',
            '3023094052',
            '3461363941',
            '2782874469',
            '2207405931840',
            '3309268105',
            '3897100430',
            '94399436',
            '579657000',
            '1704445753',
            '3973063381',
            '2204935584175',
            '1768794421',
            '754897779',
            '557302834',
            '692420117',
            '2575609138',
            '2210928292881',
            '2201224564156',
            '2201223443873',
            '4051547436',
            '4051547436',
            '2781001416',
            '2810669937',
            '2200646793209',
            '883072941',
            '722713217',
            '4035899060',
            '2200790391804',
            '2206385531048',
            '731633280',
            '3399871558',
            '2200728903172',
            '2189145381',
            '3363548061',
            '2005831794',
            '2859538876',
            '3029519939',
            '2208604396812',
            '3560042407',
            '3560042407',
            '4128631814',
            '2914096567',
            '3164328272',
            '2168464600',
            '2955374408',
            '2206781540050',
            '389335512',
            '2209888755124',
            '2071929990',
            '1650269249',
            '325418806',
            '3463541126',
            '1740722198',
            '2208140691530',
            '1638981783',
            '2924703009',
            '2208811775333',
            '2200619205921',
            '2201401441234',
            '2207853370270',
            '3234786849',
            '783329018',
            '357343440',
            '306146306',
            '2873829542',
            '2065786850',
            '2201227987016',
            '732956498',
            '1097805039',
            '3166931255',
            '1667651824',
            '3299471109',
            '2153355693',
            '1810380706',
            '2787861114',
            '2206942755958',
            '276449747',
            '3170524639',
            '2241881627',
            '1696191413',
            '2207888789298',
            '268451883',
            '2207467945974',
            '2207564322295',
            '2211431921970',
            '648580690',
            '648580690',
            '1602099579',
            '2255775604',
            '735965542',
            '1614831584',
            '1614831584',
            '2032840798',
            '2207625893980',
            '395601843',
            '2206525933008',
            '3525283028',
            '2097645566',
            '2032870312',
            '2207329896364',
            '1124587100',
            '1660558015',
            '1660558015',
            '1983407859',
            '2261228566',
            '2555064063',
            '759446530',
            '807408098',
            '2209348458535',
            '1650677660',
            '2201271748471',
            '3372144204',
            '3251681972',
            '2104900096',
            '2201197147826',
            '2929053804',
            '2400758027',
            '2024314280',
            '835893472',
            '1573475524',
            '2207656042463',
            '2211071964269',
            '605623409',
            '3718861889',
            '597385787',
            '902929889',
            '746251873',
            '2208365749343',
            '2206736426581',
            '2226443848',
            '2204940186440',
            '2201251211859',
            '4227566886',
            '2200634280915',
            '2208025939692',
            '2967366976',
            '3512189193',
            '2202822701999',
            '4075533129',
            '357342619',
            '525910381',
            '2209884616113',
            '3908079621',
            '2937413412',
            '2135187147',
            '2375538726',
            '699994102',
            '2209469580291',
            '3249482182',
            '3276808661',
            '2200779885702',
            '2114506240',
            '4256567714',
            '2200610362893',
            '1023197390',
            '3363331024',
            '3321469925',
            '1050250098',
            '366168649',
            '2207594307163',
            '94298837',
            '3037963981',
            '793375733',
            '1031105204',
            '807040407',
            '807040407',
            '1588913126',
            '2200542562614',
            '2210014229411',
            '2297955707',
            '3367357415',
            '3367357415',
            '3619780669',
            '1638195672',
            '2758433361',
            '4084223328',
            '3697612891',
            '748771569',
            '3400390945',
            '2256022143',
            '2098506058',
            '2754622571',
            '2211467409281',
            '3339231011',
            '2209498766759',
            '2210973120816',
            '3459228196',
            '2206346753961',
            '2207401814680',
            '2200536154118',
            '2072092771',
            '93733656',
            '2822748726',
            '478829986',
            '2206626571460',
            '2201196082363',
            '2201296918352',
            '2207458110599',
            '2326984312',
            '346621366',
            '512711107',
            '3940956987',
            '2137401477',
            '4170650833',
            '3866490896',
            '2024058652',
            '4126724638',
            '3822129442',
            '3429510198',
            '3961118975',
            '2201410209674',
            '1035579292',
            '2200594508657',
            '2200594508657',
            '3512576640',
            '1773095659',
            '3173173023',
            '2206835735880',
            '2731559259',
            '3008668235',
            '1137881867',
            '2207983598945',
            '2370381708',
            '4227749998',
            '3258471254',
            '4012961441',
            '3693245722',
            '240252102',
            '2200714846589',
            '4170355536',
            '1646579318',
            '1706950161',
            '2210084580089',
            '114133397',
            '2222850086',
            '2454544321',
            '3874835776',
            '737729038',
            '2207431632026',
            '876583493',
            '908483460',
            '2210560798884',
            '1601145275',
            '2745562235',
            '3039860834',
            '829273025',
            '2206991369124',
            '2207440821122',
            '2207440821122',
            '2242000680',
            '3079346051',
            '2200070681',
            '2205618816622',
            '2082313332',
            '2884884355',
            '2939847205',
            '1994050062',
            '2397897588',
            '765844183',
            '2200625831868',
            '2076907773',
            '1599515176',
            '2744586472',
            '2209085377694',
            '2206960024251',
            '2208794476020',
            '1614408057',
            '672165860',
            '1975415428',
            '1771000166',
            '901506476',
            '3793607024',
            '3793607024',
            '4014063730',
            '1589613703',
            '2200831699793',
            '3612650942',
            '2208051424668',
            '3230463886',
            '775930694',
            '874770972',
            '192579350',
            '2978217349',
            '3426733234',
            '2204169413922',
            '2655026518',
            '556608090',
            '3682611280',
            '2200732570858',
            '2209642955156',
            '3862422644',
            '2204432005457',
            '2204432005457',
            '2200805933721',
            '3070974002',
            '3010779630',
            '2756817964',
            '3173602226',
            '2116377718',
            '1718090727',
            '349740505',
            '349740505',
            '1645898031',
            '3578271683',
            '3199252068',
            '1612908143',
            '1775928416',
            '2836984312',
            '1962855395',
            '2600131029',
            '928622636',
            '3897002320',
            '2207801738991',
            '3203912792',
            '671381383',
            '2200741977409',
            '2934881346',
            '3140072758',
            '3258401644',
            '2201196294013',
            '2877976327',
            '1579139371',
            '2208350436605',
            '2207647704645',
            '2259702050',
            '1659916565',
            '2211367763390',
            '2208311123633',
            '2643613580',
            '3130860609',
            '4277857023',
            '2207267836043',
            '2057544689',
            '2587343936',
            '3932537894',
            '925649214',
            '1950971205',
            '373079721',
            '494858290',
            '2208651050356',
            '4131729138',
            '2813588735',
            '2209084027995',
            '2041105374',
            '2201204344910',
            '1664976033',
            '1621790841',
            '1589955207',
            '4108346689',
            '2200548101520',
            '3217147066',
            '96700915',
            '1744434502',
            '2436823285',
            '3440960054',
            '2207430160879',
            '2200615310934',
            '2206130731027',
            '3432793298',
            '899283711',
            '2200727411656',
            '4036309144',
            '3257346636',
            '840197627',
            '1650438331',
            '2414806177',
            '1773211220',
            '1695303019',
            '3930125919',
            '2143451202',
            '2206386581102',
            '2757793292',
            '1724234857',
            '3944956601',
            '3927982285',
            '2207803071128',
            '3695246029',
            '2843259230',
            '2345851092',
            '3043550854',
            '2208936586194',
            '2210394989911',
            '2410243258',
            '2201223152424',
            '389048191',
            '3357589328',
            '797419321',
            '3887089883',
            '3816993362',
            '2207966077846',
            '3027025555',
            '3287104402',
            '2208961427891',
            '4148083717',
            '2209898632448',
            '2207977264891',
            '340948728',
            '2005537184',
            '2211069827382',
            '1713885029',
            '3423401655',
            '2207644932146',
            '2436676752',
            '2612928539',
            '1821222375',
            '1579814274',
            '2645124890',
            '848066728',
            '3709840240',
            '4071737621',
            '2201191096468',
            '2649573952',
            '2649573952',
            '2203033996615',
            '2128031955',
            '2208478152357',
            '4142135308',
            '3919645743',
            '1113961279',
            '2962281655',
            '2207361495895',
            '3370073246',
            '2420284008',
            '686947088',
            '686947088',
            '1031524252',
            '746195036',
            '1761481772',
            '4040032736',
            '3643428960',
            '2207867569198',
            '3440693526',
            '2411055336',
            '2209520203546',
            '792975289',
            '2775741203',
            '4028944728',
            '1722183333',
            '2207948535255',
            '2209700609397',
            '2206471341788',
            '2200735243576',
            '4116538832',
            '2776560753',
            '2201297150398',
            '690422023',
            '831867534',
            '2401530034',
            '682487556',
            '3012860579',
            '2587461980',
            '2081848447',
            '467098491',
            '2208780097251',
            '4065950829',
            '2033033217',
            '2563613536',
            '2200714605763',
            '288922974',
            '782731205',
            '1023624380',
            '2957921311',
            '4147923270',
            '2206476180711',
            '1731961317',
            '509142617',
            '3855935192',
            '754742177',
            '2081592196',
            '2830837980',
            '570043893',
            '2098678687',
            '391817269',
            '398509203',
            '2930757530',
            '515374913',
            '2335305051',
            '3082658611',
            '411832242',
            '411832242',
            '3559856870',
            '288902762',
            '2209518547770',
            '2206484117440',
            '4245404962',
            '2206683374107',
            '3994245339',
            '807732188',
            '4163232129',
            '4256204489',
            '3017450704',
            '2209959210452',
            '2206604568056',
            '3163143818',
            '3031097455',
            '2258006982',
            '749240762',
            '3843373475',
            '2928628469',
            '2208291429522',
            '2204109362103',
            '2088045547',];
    }

}

