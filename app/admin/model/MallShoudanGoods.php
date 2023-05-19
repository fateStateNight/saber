<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class MallShoudanGoods extends TimeModel
{

    protected $name = "mall_shoudan_goods";

    protected $deleteTime = "delete_time";

    
    
    public function getItemStatusList()
    {
        return ['1'=>'出售中','2'=>'已售完','0'=>'未出售',];
    }

    public function getModeList()
    {
        return ['1'=>'已充值商品','2'=>'未充值商品',];
    }

    public function getStatusList()
    {
        return ['0'=>'下架','1'=>'上架',];
    }


}