<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class BusinessOrder extends TimeModel
{

    protected $name = "business_order";

    protected $deleteTime = false;

    /*public function goodsEvent()
    {
        return $this->belongsTo('\app\admin\model\BusinessGoods', 'event_id', 'eventId');
    }*/

    public function businessGoods()
    {
        return $this->belongsTo('\app\admin\model\BusinessGoods', 'item_id', 'itemId')
            ->field('itemId,eventId,auditorId');
    }
    
    public function getTkStatusList()
    {
        return ['3'=>'订单结算','12'=>'订单付款','13'=>'订单失效','14'=>'订单成功',];
    }


}