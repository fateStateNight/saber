<?php
declare (strict_types = 1);

namespace app\command;

include_once "/app/extend/xlsxwriter.class.php";

use EasyAdmin\tool\CommonTool;
use Nullix\CryptoJsAes\CryptoJsAes;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class DecrpyCode extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('decrpyCode')
            ->setDescription('the decrpy code command');
    }

    protected function execute(Input $input, Output $output)
    {
    	// 指令输出
    	$output->writeln('decrpyCode');
    	//$this->updateMoreShopInfo();
    	//$this->updateShopNullData();
        //$this->getShopTransferData();
        $this->exportOrderData();
        $output->writeln('execute is over!');
    	return true;


    	/*$ret = $this->getShopContactInfo('299244686');
        $sourceData = $ret['data'];
        $decrpyData = $this->decrpyData($sourceData);
        $lastData = $this->resolve($decrpyData);
        var_export(urldecode($lastData));
        return true;*/
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

    //获取新的店铺数据并更新数据库
    public function updateMoreShopInfo()
    {
        $pageNum = 0;
        $endNum = $pageNum + 100;
        $keyName= '国际';
        $dataNum = 0;
        $storeObj = new \app\admin\model\BusinessStore();
        do{
            $pageNum++;
            $shopList = [];
            $shopInfo = $this->getShopList($keyName,$pageNum);
            if($shopInfo['total_results'] > 0){
                $shopList = $shopInfo['results']['n_tbk_shop'];
                if($shopList == null){
                    break;
                }
                foreach($shopList as $key=>$rowData){
                    echo $key."\r\n";
                    $dataNum++;
                    if($rowData['shop_type'] == 'B'){
                        $existRet = $storeObj->where('title', '=', $rowData['shop_title'])->find();
                        if(!$existRet){
                            $inserData = [
                                'title' => $rowData['shop_title'],
                                'shop_id' => $rowData['user_id'],
                                'creater_id' => 1,
                                'create_time' => date('Y-m-d H:i:s'),
                            ];
                            $ret = $storeObj->insert($inserData);
                            echo $rowData['shop_title']."\r\n";
                        }
                    }
                }
            }
        }while($shopInfo['total_results'] > 0 && $pageNum < $endNum);
        echo "当前关键词是：".$keyName.";总共".$dataNum."条数据\r\n";
    }

    //更新数据库中店铺数据ID为空的数据
    public function updateShopNullData()
    {
        //获取已有的商家数据
        $storeObj = new \app\admin\model\BusinessStore();
        $shopData = $storeObj->where('id','>=','40000')//7223、
            ->where('id','<','45265')
            ->where('shop_id','')
            ->select()
            ->toArray();
        if($shopData == null){
            echo "没有需要更新的店铺数据！\r\n";
            return true;
        }
        foreach($shopData as $rowData){
            if($rowData['title'] != null){
                $shopInfo = $this->getShopInfo($rowData['title']);
                if($shopInfo['total_results'] > 0){
                    $shopId = $shopInfo['results']['n_tbk_shop'][0]['user_id'];
                    $updateData['shop_id'] = $shopId;
                    $save = $storeObj::update($updateData,['id'=>$rowData['id']]);
                }
            }
            echo $rowData['title']."\r\n";
        }
        return true;
    }


    //获取需要查询的店铺数据
    public function getShopTransferData()
    {
        //获取已有的商家数据
        $storeObj = new \app\admin\model\BusinessStore();
        $storeArr = $storeObj->where('id','>=','225000')//7223、
            ->where('id','<','230000')
            ->where('shop_id','>', 0)
            ->select()
            ->toArray();
        if($storeArr == null){
            echo "没有需要处理的商家数据\r\n";
            return true;
        }
        $loopNum = 0;
        foreach($storeArr as $rowData){
            $updateData = [];
            if($rowData['shop_id'] != null){
                $ret = $this->getShopContactInfo($rowData['shop_id']);
                //var_export($ret);
                if(!is_array($ret) || !array_key_exists('data',$ret)){
                    echo date('Y-m-d H:i:s')." : ".$rowData['title']." 返回的数据信息格式异常\r\n";
                    continue;
                }
                $sourceData = $ret['data'];
                $decrpyData = $this->decrpyData($sourceData);
                //var_export($decrpyData);
                $dataStr = substr(urldecode($this->resolve($decrpyData)),0,-1);
                $lastData = json_decode($dataStr,true);
                //var_export($dataStr);
                //var_export($lastData);die;
                if($lastData == null || !is_array($lastData)){
                    continue;
                }

                /*//分析出号码
                if(array_key_exists('order', $lastData) && $lastData['order']['phone'] != null && strpos($lastData['order']['phone'],'-') === false){
                    $updateData['phone'] = $lastData['order']['phone'];
                }elseif(array_key_exists('qicc', $lastData) && $lastData['qicc']['phone'] != null){
                    $updateData['phone'] = $lastData['qicc']['phone'];
                }elseif(array_key_exists('wayjd', $lastData) && $lastData['wayjd']['phone'] != null){
                    $updateData['phone'] = $lastData['wayjd']['phone'];
                }


                $updateData['qq_number'] = '';
                $updateData['weixin'] = '';
                //分析出微信QQ
                if(array_key_exists('alimama', $lastData)){
                    if($lastData['alimama']['qq'] != null){
                        $updateData['qq_number'] = $lastData['alimama']['qq'];
                    }
                    if($lastData['alimama']['wx'] != null){
                        $updateData['weixin'] = $lastData['alimama']['wx'];
                    }
                }*/

                //分析出号码
                if($rowData['phone'] == null){
                    if(array_key_exists('order', $lastData) && $lastData['order']['phone'] != null && strpos($lastData['order']['phone'],'-') === false){
                        $updateData['phone'] = $lastData['order']['phone'];
                    }elseif(array_key_exists('qicc', $lastData) && $lastData['qicc']['phone'] != null){
                        $updateData['phone'] = $lastData['qicc']['phone'];
                    }elseif(array_key_exists('wayjd', $lastData) && $lastData['wayjd']['phone'] != null){
                        $updateData['phone'] = $lastData['wayjd']['phone'];
                    }
                }

                //分析出微信QQ
                if(array_key_exists('alimama', $lastData)){
                    if($lastData['alimama']['qq'] != null && $rowData['qq_number'] == null){
                        $updateData['qq_number'] = $lastData['alimama']['qq'];
                    }
                    if($lastData['alimama']['wx'] != null && $rowData['weixin'] == null){
                        $updateData['weixin'] = $lastData['alimama']['wx'];
                    }
                }
                //解密后数据更新
                if($rowData['detail'] == null){
                    $updateData['detail'] = $dataStr;
                }
                //var_export($updateData);
                //echo var_export($updateData)."\r\n";
                if($updateData != null){
                    $ret = $storeObj::update($updateData,['id'=>$rowData['id']]);
                    echo $rowData['title']."\r\n";
                }
                $loopNum++;
            }
        }
        echo "数据处理完成,已处理".$loopNum."条数据\r\n";
        return true;
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


    /*
     * 根据关键词获取店铺列表信息
     */
    public function getShopList($keyName,$pageNum=1,$pageSize=100)
    {
        $c = new \TopClient;
        $c->appkey = '27995746';//27995746
        $c->secretKey = 'e0a04dc52507b3b15a92fc56759279fb';//e0a04dc52507b3b15a92fc56759279fb
        $c->format = 'json';

        $req = new \TbkShopGetRequest;
        $req->setFields("user_id,shop_title,shop_type,seller_nick,pict_url,shop_url");
        $req->setQ($keyName);
        $req->setIsTmall("true");
        $req->setPageNo($pageNum);
        $req->setPageSize($pageSize);
        $resp = $c->execute($req);
        $resultData = json_decode(json_encode($resp),true);
        return $resultData;
    }

    /*public function update()
    {
        //获取已有的商家数据
        $storeObj = new \app\admin\model\BusinessStore();
        $storeArr = $storeObj->where('id','>=','7226')//7223、
            ->where('id','<=','15093')
            ->where('shop_id','>',0)
            ->select()
            ->toArray();



        $loopnum = 0;
        $shopContactInfo = [];
        foreach($storeArr as $storeInfo){
            $updateData = [];
            $shopContactInfo = $this->getPublicShopContactInfo($storeInfo['shop_id'],'eb3d85b6b0350');

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
            $save = $storeObj::update($updateData,['id'=>$storeInfo['id']]);
            $loopnum++;
            //usleep(300000);
            echo $storeInfo['title'].'|'.$loopnum."\r\n";
        }
        $save ? $this->success('更新成功'.$loopnum) : $this->error('更新失败'.$loopnum);
    }*/

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
        $url = 'http://node.yiquntui.com/api/contentData?shopid='.$shopId.'&sellerid='.$shopId.'&ver=512';
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
            return json_encode(array('code'=>-10003,'msg'=>$a,'data'=>[]));
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
    
    //解密数据方法
    public function decrpyData($sourceData,$key='',$iv='',$method='AES-128-CBC')
    {
        $keyString = strtoupper(md5('maybeitbetrue'));
        $key = substr($keyString,0,14).date("H");
        $iv = substr($keyString,17,14).date("i");
        //echo $key."\r\n";
        //echo $iv."\r\n";


        $replace = ['+','/'];
        $search = ['-','_'];

        //$str = openssl_decrypt(str_replace($search,$replace,$sourceData),$method,$key,OPENSSL_ZERO_PADDING,$iv);
        $str = openssl_decrypt($sourceData,$method,$key,OPENSSL_ZERO_PADDING,$iv);
        //var_export($str);die;
        //$text = substr($str,0,strrpos($str,"}")+1);
        //var_export($text);die;

        return $str;
    }

    public function resolve($sourceData)
    {
        //解密操作
        $key = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $keyLegth = strlen($key);
        if($sourceData == null){
            return false;
        }
        $resolveStr = '';
        for($j=0;$j<strlen($sourceData);$j++){
            if (0 != ($j + 1) % 4) {
                $resolveStr .= substr($sourceData,$j,1);
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
        //$resultStr = base64_decode($retStr);
        return $retStr;
    }

    //将数据导出到excel文件并压缩
    protected function exportOrderData(){
        //处理excel文件头部信息
        $storeObj = new \app\admin\model\BusinessStore();
        /*$tableName = $storeObj->getName();
        $tableName = CommonTool::humpToLine(lcfirst($tableName));
        $prefix = config('database.connections.mysql.prefix');
        $dbList = Db::query("show full columns from {$prefix}{$tableName}");
        $header = [];

        foreach ($dbList as $vo) {
            $comment = !empty($vo['Comment']) ? $vo['Comment'] : $vo['Field'];
            //$header[] = [$comment, $vo['Field']];
            if(strpos($vo['Field'],'time')){
                $header[$comment] = 'datetime';
            }elseif(strpos($vo['Field'],'price')){
                $header[$comment] = 'price';
            }else{
                $header[$comment] = 'string';
            }
        }*/
        //var_export($header);die;
        $header = array (
            '店铺ID' => 'string',
            '商家标题' => 'string',
            'QQ号' => 'string',
            '微信号' => 'string',
            '钉钉号' => 'string',
            '手机号' => 'string',
        );
        $writer = new \XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header );

        $pageNum = 0;
            //$output->writeln(date('Y-m-d H:i:s').' :get data time');
            $orderList = $storeObj->where('shop_id','>',0)
                ->field('shop_id,title,qq_number,weixin,dingding,phone')
                ->limit(0,230000)
                ->order('id', 'asc')
                //->limit(1)
                ->select();

            $orderInfo = $orderList->toArray();
            foreach($orderInfo as $row){
                $writer->writeSheetRow('Sheet1', $row );
            }
            $filePath = '/app/public/download/businessList.xlsx';
            $writer->writeToFile($filePath);
            $pageNum++;

        echo date('Y-m-d H:i:s').' : task already execute !'."\r\n";
        echo $pageNum."\r\n";
    }


}
