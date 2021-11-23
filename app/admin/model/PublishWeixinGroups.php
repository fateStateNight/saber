<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class PublishWeixinGroups extends TimeModel
{

    protected $name = "publish_weixin_groups";

    protected $deleteTime = "delete_time";

    
    public function systemAdmin()
    {
        return $this->belongsTo('\app\admin\model\SystemAdmin', 'belong_id', 'id');
    }

    
    public function getSystemAdminList()
    {
        return \app\admin\model\SystemAdmin::column('nickname', 'id');
    }
    public function getSourceTypeList()
    {
        return ['0'=>'短信','1'=>'抖音短视频','2'=>'闲鱼','3'=>'店铺券图','4'=>'快递','5'=>'包裹','6'=>'知乎','7'=>'抖音直播','8'=>'红包'];
    }

    public function getGroupTypeList()
    {
        return ['0'=>'泛人群','1'=>'购物群','2'=>'精准群',];
    }


}