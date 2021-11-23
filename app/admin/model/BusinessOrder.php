<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class BusinessOrder extends TimeModel
{

    protected $name = "business_order";

    protected $deleteTime = false;

    
    
    public function getTkStatusList()
    {
        return ['3'=>'订单结算','12'=>'订单付款','13'=>'订单失效','14'=>'订单成功',];
    }


}