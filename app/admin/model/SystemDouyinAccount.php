<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class SystemDouyinAccount extends TimeModel
{

    protected $name = "system_douyin_account";

    
    
    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'启用',];
    }

    public function getDouyinAccountInfo($account_id)
    {
        $result = SystemDouyinAccount::find($account_id)->toArray();
        return $result;
    }

    public function getDouAccountList()
    {
        return \app\admin\model\SystemDouyinAccount::column('title', 'id');
    }

}