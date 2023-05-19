<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class MallIntegralRecord extends TimeModel
{

    protected $name = "mall_integral_record";

    protected $deleteTime = "delete_time";

    
    public function mallIntegralGoods()
    {
        return $this->belongsTo('\app\admin\model\MallIntegralGoods', 'goods_id', 'id');
    }

    public function systemWeixinUser()
    {
        return $this->belongsTo('\app\admin\model\SystemWeixinUser', 'user_id', 'id');
    }

    
    public function getMallIntegralGoodsList()
    {
        $currentTime = date('Y-m-d H:i:s');
        return \app\admin\model\MallIntegralGoods::where('begin_time','<=',$currentTime)
            ->where('end_time','>=',$currentTime)
            ->where('goods_type', '=', '0')
            ->column('title', 'id');
    }

    //获取出售商品列表
    public function getMallSellGoodsList($goodsType)
    {
        $currentTime = date('Y-m-d H:i:s');
        $goodsInfo = MallIntegralGoods::where('begin_time','<=',$currentTime)
            ->where('end_time','>=',$currentTime)
            ->where('goods_type', '=', $goodsType)
            ->field(['id','title','goods_integral','goods_image','item_id','goods_price','end_time'])
            ->select();
        if($goodsInfo->isEmpty()){
            return null;
        }else{
            return $goodsInfo->toArray();
        }
    }

    public function getSystemWeixinUserList()
    {
        return \app\admin\model\SystemWeixinUser::column('nickname', 'id');
    }
    public function getIntegralStatusList()
    {
        return ['0'=>'已消耗','1'=>'申请中','2'=>'已通过','3'=>'已拒绝',];
    }

    //获取最近十五条记录数据
    public function getIntegralRecordRecent($userId)
    {
        $recordList = MallIntegralRecord::where('user_id','=',$userId)
            ->field(['id','record_title','integral_status','user_id','integral_value','create_time'])
            ->order(['create_time'=>'desc'])
            ->limit(0,15)
            ->select();
        if($recordList->isEmpty()){
            return null;
        }
        return $recordList->toArray();
    }

    //获取未通过积分申请记录数
    public function getApplyRecordNum($userId)
    {
        $recordArr = MallIntegralRecord::where('user_id','=',$userId)
            ->group('integral_status')
            //->fetchSql()
            ->field('integral_status,count(*) as recordNum')
            ->select();
        if($recordArr->isEmpty()){
            return null;
        }else{
            return $recordArr->toArray();
        }
    }

}