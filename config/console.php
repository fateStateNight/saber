<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'curd'      => 'app\common\command\Curd',
        'node'      => 'app\common\command\Node',
        'OssStatic' => 'app\common\command\OssStatic',
        'transfer'      => 'app\command\Transfer',
<<<<<<< HEAD
        'synchronous'      => 'app\command\Synchronous',        //同步推广订单数据
        'taolijinOrderResult'      => 'app\command\TaoLiJinOrderResult',        //同步淘礼金推广数据
        'test'      => 'app\command\Test',
        'decrpyCode'    => 'app\command\DecrpyCode',
        'sycEventData'    => 'app\command\SycEventData',        //同步招商活动数据
        'sycEventGoodsData'    => 'app\command\SycEventGoodsData',        //同步报名招商活动商品数据
        'sycOrderDatas'    => 'app\command\SycOrderDatas',        //同步近30天订单数据
        'exportOrderList'    => 'app\command\ExportOrderList',        //导出订单数据
        'sycGoodsEffectData'    => 'app\command\SycGoodsEffectData',        //同步商品每日推广数据
        'sycDouyinOrderDatas'    => 'app\command\SycDouyinOrderDatas',        //
=======
        'synchronous'      => 'app\command\Synchronous',
        'taolijinOrderResult'      => 'app\command\TaoLiJinOrderResult',
        'test'      => 'app\command\Test',
        'decrpyCode'    => 'app\command\DecrpyCode',
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
    ],
];
