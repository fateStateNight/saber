<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class BusinessOrder extends TimeModel
{

    protected $name = "business_order";

    protected $deleteTime = false;

<<<<<<< HEAD
    /*public function goodsEvent()
    {
        return $this->belongsTo('\app\admin\model\BusinessGoods', 'event_id', 'eventId');
    }*/

    public function businessGoods()
    {
        return $this->belongsTo('\app\admin\model\BusinessGoods', 'item_id', 'itemId')
            ->field('itemId,eventId,auditorId');
    }
=======
    
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
    
    public function getTkStatusList()
    {
        return ['3'=>'订单结算','12'=>'订单付款','13'=>'订单失效','14'=>'订单成功',];
    }


}