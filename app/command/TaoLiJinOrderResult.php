<?php
declare (strict_types = 1);

namespace app\command;

include_once "/app/extend/taobaosdk/TopSdk.php";

use app\admin\model\MallTaolijinGoods;
use app\admin\model\SystemTaobaoAccount;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class TaoLiJinOrderResult extends Command
{
    protected $taoLiJinGoodsObj;

    protected function configure()
    {
        // 指令配置
        $this->setName('taolijinOrderResult')
            ->setDescription('更新淘礼金投放结果的数据');
    }

    protected function execute(Input $input, Output $output)
    {
    	// 指令输出
    	$output->writeln(date('Y-m-d H:i:s').' :Start to update tao li jin goods info ! ');
        //初始化记录表对象
        if(!($this->taoLiJinGoodsObj instanceof MallTaolijinGoods)){
            $this->taoLiJinGoodsObj = new \app\admin\model\MallTaolijinGoods();
        }
        //获取淘宝联盟账号信息
        $systemAccountModel = new \app\admin\model\SystemTaobaoAccount();
        $goodsArr = $this->getGoodsRecordInfo();
        if($goodsArr == null){
            $output->writeln(date('Y-m-d H:i:s').' :No record need to update !');
            return false;
        }
        foreach($goodsArr as $dataInfo){
            $accountInfo = $systemAccountModel->getTaoBaoAccountInfo($dataInfo['account_id']);
            $updateInfo = [];
            $taolijinContent = json_decode($dataInfo['goods_content'], true);
            if(array_key_exists('rights_id', $taolijinContent['model'])){
                $orderInfo = $this->getTaoLiJinOrderInfo($accountInfo, $taolijinContent['model']['rights_id']);
                if(array_key_exists('result', $orderInfo) && $orderInfo['result']['success'] == 'true' && array_key_exists('use_num', $orderInfo['result']['model'])){
                    $taolijinContent['usedInfo'] = $orderInfo['result']['model'];
                    $updateInfo['goods_content'] = json_encode($taolijinContent);
                    $updateInfo['sales'] = $orderInfo['result']['model']['use_num'];
                }else{
                    $output->writeln(date('Y-m-d H:i:s').' :the data is error : '.$taolijinContent['model']['rights_id']);
                    continue;
                }
            }
            //更新记录数据状态
            $updateInfo['item_status'] = 2;
            $this->taoLiJinGoodsObj->where('id',$dataInfo['id'])->update($updateInfo);
        }
        $output->writeln(date('Y-m-d H:i:s').' :The record already update !');
        return true;
    }

    /*
     * 获取需要更新的淘礼金商品数据
     */
    public function getGoodsRecordInfo()
    {
        $currentTime = date("Y-m-d H:i:s");
        $goodsInfo = $this->taoLiJinGoodsObj->where('use_end_time','<=', $currentTime)
            ->where('mode','=',3)
            ->where('item_status','<>', 2)
            ->where('status','=',1)
            ->select();
        $goodsArr = [];
        if(!($goodsInfo->isEmpty())){
            $goodsArr = $goodsInfo->toArray();
        }
        return $goodsArr;
    }

    /*
     * 获取淘礼金使用信息
     */
    public function getTaoLiJinOrderInfo($account, $right_id)
    {
        $c = new \TopClient;
        $c->appkey = $account['appkey'];
        $c->secretKey = $account['appsecret'];
        $c->format = 'json';

        $req = new \TbkDgVegasTljInstanceReportRequest;

        $req->setRightsId($right_id);
        $resp = $c->execute($req);
        $resultData = json_decode(json_encode($resp),true);
        return $resultData;
    }

}
