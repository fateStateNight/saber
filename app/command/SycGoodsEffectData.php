<?php
declare (strict_types = 1);

namespace app\command;


use app\admin\model\BusinessGoods;
use app\admin\model\BusinessGoodsEffect;
use app\admin\model\BusinessScene;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class SycGoodsEffectData extends Command
{
    protected $businessSceneModel;

    protected $businessGoodsModel;

    protected $businessGoodsEffectModel;

    protected function configure()
    {
        // 指令配置
        // 每天12:00执行一次，检测日期为最后一天则开始同步商品推广数据
        $this->setName('sycGoodsEffectData')
            //->addArgument('status', Argument::OPTIONAL, "限制条件状态")
            ->setDescription('同步商品每日推广数据');
    }

    protected function execute(Input $input, Output $output)
    {
        //获取指令参数
        //$eventStatus = $input->getArgument('status');
    	// 指令输出
    	$output->writeln(date('Y-m-d H:i:s').' :Start to update goods effect of everyday info ! ');
        //初始化记录表对象
        if(!($this->businessSceneModel instanceof BusinessScene)){
            $this->businessSceneModel = new \app\admin\model\BusinessScene();
        }
        if(!($this->businessGoodsModel instanceof BusinessGoods)){
            $this->businessGoodsModel = new \app\admin\model\BusinessGoods();
        }
        if(!($this->businessGoodsEffectModel instanceof BusinessGoodsEffect)){
            $this->businessGoodsEffectModel = new \app\admin\model\BusinessGoodsEffect();
        }
        //获取淘宝联盟账号信息
        $accountInfo = $this->businessSceneModel->getALiAccountInfo();
        //检测联盟账号登录是否有效
        if($accountInfo == []){
            $output->writeln(date('Y-m-d H:i:s').' :the account is empty ! ');
            return true;
        }
        //初始化在线账号变量
        $accountArr = [];
        foreach($accountInfo as $key=>$account){
            $onlineInfo = $this->businessSceneModel->getOnlineAccountInfo($account['token'], $account['cookies']);
            if(!$onlineInfo || !array_key_exists('data', $onlineInfo)){
                $output->writeln(date('Y-m-d H:i:s').' : '.$account['user_name'].' is offline');
            }else{
                $accountArr = $account;
            }
        }
        //获取当天报名结束的商品数据
        $currendDay = date("Y-m-d 23:59:59", strtotime('-1 week'));
        $goodsInfo = $this->businessGoodsModel
            ->withJoin('businessScene', 'LEFT')
            ->where('business_goods.endTime',$currendDay)
            ->where('businessScene.groupId','>',0)
            ->select()->toArray();
        //var_export($goodsInfo[0]);die;
        if($goodsInfo == null){
            $output->writeln(date('Y-m-d H:i:s').' :the data of goods is null ! ');
            return true;
        }
        //$goodsInfo = [];
        $this->businessGoodsEffectModel->sycGoodsPublishEffectInfoToDB($accountArr, $goodsInfo);

        $output->writeln(date('Y-m-d H:i:s').' :The effect of goods already update !');
        return true;
    }


}
