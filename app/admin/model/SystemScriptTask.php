<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class SystemScriptTask extends TimeModel
{

    protected $name = "system_script_task";

    protected $deleteTime = false;


    public function systemAdmin()
    {
        return $this->belongsTo('\app\admin\model\SystemAdmin', 'creater_id', 'id');
    }
    
    public function getTaskStatusList()
    {
        return ['0'=>'未执行','1'=>'执行中','2'=>'已完成',];
    }
    public function getTypeList()
    {
        return ['0'=>'系统任务','1'=>'订单数据更新','2'=>'订单数据导出'];
    }


}