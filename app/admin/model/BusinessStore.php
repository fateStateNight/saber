<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class BusinessStore extends TimeModel
{

    protected $name = "business_store";

    protected $deleteTime = "delete_time";

    
    public function systemAdmin()
    {
        return $this->belongsTo('\app\admin\model\SystemAdmin', 'creater_id', 'id');
    }

    public function businessShare()
    {
        return $this->belongsTo('\app\admin\model\BusinessShare', 'id', 'store_id');
    }

    
    public function getSystemAdminList()
    {
        return \app\admin\model\SystemAdmin::column('nickname', 'id');
    }
    public function getShareLevelList()
    {
        return ['0'=>'关闭','1'=>'开启',];
    }

    public function getShareAdminList($store_id)
    {
        return \app\admin\model\BusinessShare::where('store_id', '=', $store_id)->column('admin_id');
    }


}