<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class SystemTaobaoAccount extends TimeModel
{

    protected $name = "system_taobao_account";

    protected $deleteTime = "delete_time";

    
    
    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'启用',];
    }

    public function getTaoBaoAccountInfo($account_id)
    {
        $result = SystemTaobaoAccount::find($account_id)->toArray();
        return $result;
    }

}