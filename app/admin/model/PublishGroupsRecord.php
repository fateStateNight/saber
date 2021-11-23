<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class PublishGroupsRecord extends TimeModel
{

    protected $name = "publish_groups_record";

    protected $deleteTime = "delete_time";

    
    public function publishWeixinGroups()
    {
        return $this->belongsTo('\app\admin\model\PublishWeixinGroups', 'group_id', 'id');
    }

    public function mallIntegralGoods()
    {
        return $this->belongsTo('\app\admin\model\MallIntegralGoods', 'goods_id', 'id');
    }

    //获取出售商品列表
    public function getCurrentDayGoodsList($goodsType)
    {
        $startTime = date('Y-m-d').' 00:00:00';
        $stopTime = date('Y-m-d').' 23:59:59';
        $goodsInfo = MallIntegralGoods::where('begin_time','>=',$startTime)
            ->where('begin_time','<=',$stopTime)
            ->where('goods_type', '=', $goodsType)
            ->field(['id','title','goods_integral','goods_image','item_id','goods_price','begin_time'])
            ->column('title','id');
            //->select();
        return $goodsInfo;
        /*if($goodsInfo->isEmpty()){
            return null;
        }else{
            return $goodsInfo->toArray();
        }*/
    }
    
    public function getPublishWeixinGroupsList()
    {
        if(session('admin')['auth_ids'] == 7 || session('admin')['auth_ids'] == 1){
            return \app\admin\model\PublishWeixinGroups::column('title', 'id');
        }else{
            return \app\admin\model\PublishWeixinGroups::where('belong_id','=',session('admin')['id'])->column('title', 'id');
        }
    }

}