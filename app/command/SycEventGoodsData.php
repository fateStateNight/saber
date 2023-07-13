<?php
declare (strict_types = 1);

namespace app\command;


use app\admin\model\BusinessGoods;
use app\admin\model\BusinessScene;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class SycEventGoodsData extends Command
{
    protected $businessSceneModel;

    protected $businessGoodsModel;

    protected function configure()
    {
        // 指令配置
        // 每30分钟执行一次，时间段在9:00~00:00
        // 每天凌晨执行一次
        $this->setName('sycEventGoodsData')
            ->addArgument('status', Argument::OPTIONAL, "限制条件状态")
            ->setDescription('同步报名招商活动商品数据');
    }

    protected function execute(Input $input, Output $output)
    {
        //获取指令参数
        $eventStatus = $input->getArgument('status');
    	// 指令输出
    	$output->writeln(date('Y-m-d H:i:s').' :Start to update business event goods info ! ');
        //初始化记录表对象
        if(!($this->businessSceneModel instanceof BusinessScene)){
            $this->businessSceneModel = new \app\admin\model\BusinessScene();
        }
        if(!($this->businessGoodsModel instanceof BusinessGoods)){
            $this->businessGoodsModel = new \app\admin\model\BusinessGoods();
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
                //unset($accountInfo[$key]);
            }else{
                $accountArr[] = $account;
            }
        }
        //获取活动数据
        $where = [];
        if($eventStatus){
            $where[] = ['status', '=', $eventStatus];
        }
        //判断新旧版本数据
        //$where1 = $where;
        //$where1[] = ['version', '=', 0];
        //$where1[] = ['eventId', '=', '390300532'];
        //$eventData = $this->businessSceneModel->getEventInfoFromData($accountArr, $where1);
        $where2 = $where;
        $where2[] = ['version', '=', 1];
        //$where2[] = ['eventId', '=', '1000961852'];
        $eventData1 = $this->businessSceneModel->getEventInfoFromData($accountArr, $where2);
        /*if($eventData){
            //根据活动数据同步报名活动的商品数据
            $this->businessGoodsModel->sycEventGoodsInfoToDB($eventData);
            //根据活动数据同步报名活动商品的效果数据
            $this->businessGoodsModel->sycEventGoodsEffectToDB($eventData);
        }*/
        //var_export($eventData1);die;
        if($eventData1){
            //根据新版活动数据同步报名活动的商品数据
            $this->businessGoodsModel->sycNewEventGoodsInfoToDBV2($eventData1);
            //根据新版活动数据同步报名活动商品的效果数据
            $this->businessGoodsModel->sycNewEventGoodsEffectToDB($eventData1);
        }
        //var_export($eventData);die;




        $output->writeln(date('Y-m-d H:i:s').' :The business goods already update !');
        return true;
    }


}
