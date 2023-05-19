<?php
declare (strict_types = 1);

namespace app\command;


use app\admin\model\BusinessScene;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class SycEventData extends Command
{
    protected $businessSceneModel;

    protected function configure()
    {
        // 指令配置
        // 每10分钟执行一次，时间段在10:00~22:00
        $this->setName('sycEventData')
            ->setDescription('同步招商活动数据');
    }

    protected function execute(Input $input, Output $output)
    {
    	// 指令输出
    	$output->writeln(date('Y-m-d H:i:s').' :Start to update business event info ! ');
        //初始化记录表对象
        if(!($this->businessSceneModel instanceof BusinessScene)){
            $this->businessSceneModel = new \app\admin\model\BusinessScene();
        }
        //获取淘宝联盟账号信息
        $accountInfo = $this->businessSceneModel->getALiAccountInfo();
        //检测联盟账号登录是否有效
        if($accountInfo == []){
            $output->writeln(date('Y-m-d H:i:s').' :the account is empty ! ');
            return true;
        }
        //$output->writeln(date('Y-m-d H:i:s').' : '.json_encode($accountInfo));
        //初始化在线账号变量
        $accountArr = [];
        foreach($accountInfo as $key=>$account){
            //$output->writeln(date('Y-m-d H:i:s').' : '.$account['user_name'].' -------'.$account['cookies']);
            $onlineInfo = $this->businessSceneModel->getOnlineAccountInfo($account['token'], $account['cookies']);
            //$output->writeln(date('Y-m-d H:i:s').' : '.json_encode($onlineInfo));
            if(!$onlineInfo || !array_key_exists('data', $onlineInfo)){
                $output->writeln(date('Y-m-d H:i:s').' : '.$account['user_name'].' is offline');
                //unset($accountInfo[$key]);
            }else{
                $accountArr[] = $account;
            }
        }
        //$output->writeln(date('Y-m-d H:i:s').' : '.json_encode($accountArr));
        //根据联盟账号同步活动数据
        $this->businessSceneModel->sycEventInfoToDB($accountArr);
        $this->businessSceneModel->sycNewEventInfoToDB($accountArr);

        $output->writeln(date('Y-m-d H:i:s').' :The business event already update !');
        return true;
    }


}
