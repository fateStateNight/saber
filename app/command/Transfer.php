<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Transfer extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('transfer')
            ->setDescription('the transfer command');        
    }

    protected function execute(Input $input, Output $output)
    {
    	// 指令输出
        $this->analysisData();
    	$output->writeln('transfer');
    }

    protected function analysisData()
    {
        $storeObj = new \app\admin\model\BusinessStore();
        $storeArr = $storeObj->where('id','=','7222')//156
            ->select()
            ->toArray();
        $loopNum = 0;
        foreach($storeArr as $storeInfo){
            /*if($loopNum>=1){
                break;
            }*/
            echo "====================================\r\n";
            echo $storeInfo['title'].$loopNum."\r\n";
            $updateData = [];
            $reallyData = $this->resolveData($storeInfo['detail']);
            echo $reallyData;die;
            if($reallyData){
                $dataArr = json_decode($reallyData,true);
                //过滤掉空数组
                if($dataArr == null){
                    continue;
                }
                //分析出号码
                if(array_key_exists('order', $dataArr) && $dataArr['order']['phone'] != null && strpos($dataArr['order']['phone'],'-') === false){
                    $updateData['phone'] = $dataArr['order']['phone'];
                }elseif(array_key_exists('qicc', $dataArr) && $dataArr['qicc']['phone'] != null){
                    $updateData['phone'] = $dataArr['qicc']['phone'];
                }elseif(array_key_exists('wayjd', $dataArr) && $dataArr['wayjd']['phone'] != null){
                    $updateData['phone'] = $dataArr['wayjd']['phone'];
                }
                //分析出微信QQ
                if(array_key_exists('alimama', $dataArr)){
                    if($dataArr['alimama']['qq'] != null){
                        $updateData['qq_number'] = $dataArr['alimama']['qq'];
                    }
                    if($dataArr['alimama']['wx'] != null){
                        $updateData['weixin'] = $dataArr['alimama']['wx'];
                    }
                }
                //解密后数据更新
                $updateData['detail'] = $reallyData;
                //var_export($updateData);
                echo var_export($updateData)."\r\n";
                $ret = $storeObj::update($updateData,['id'=>$storeInfo['id']]);
            }

            echo "====================================\r\n";
            $loopNum++;
        }
    }

    protected function resolveData($sourceString)
    {
        //解密操作
        $key = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $keyLegth = strlen($key);
        if($sourceString == null){
            echo '原始数据为空'."\r\n";
            return false;
        }
        $resolveStr = '';
        for($j=0;$j<strlen($sourceString);$j++){
            if (0 != ($j + 1) % 4) {
                $resolveStr .= substr($sourceString,$j,1);
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

}
