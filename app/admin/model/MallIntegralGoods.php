<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class MallIntegralGoods extends TimeModel
{

    protected $name = "mall_integral_goods";

    protected $deleteTime = "delete_time";

    
    
    public function getGoodsTypeList()
    {
        return ['0'=>'出售商品','1'=>'兑换商品',];
    }


}