<?php /*a:2:{s:46:"/app/app/admin/view/business/goods/detail.html";i:1665542363;s:39:"/app/app/admin/view/layout/default.html";i:1602818599;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo sysconfig('site','site_name'); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/static/admin/css/public.css?v=<?php echo htmlentities($version); ?>" media="all">
    <script>
        window.CONFIG = {
            ADMIN: "<?php echo htmlentities((isset($adminModuleName) && ($adminModuleName !== '')?$adminModuleName:'admin')); ?>",
            CONTROLLER_JS_PATH: "<?php echo htmlentities((isset($thisControllerJsPath) && ($thisControllerJsPath !== '')?$thisControllerJsPath:'')); ?>",
            ACTION: "<?php echo htmlentities((isset($thisAction) && ($thisAction !== '')?$thisAction:'')); ?>",
            AUTOLOAD_JS: "<?php echo htmlentities((isset($autoloadJs) && ($autoloadJs !== '')?$autoloadJs:'false')); ?>",
            IS_SUPER_ADMIN: "<?php echo htmlentities((isset($isSuperAdmin) && ($isSuperAdmin !== '')?$isSuperAdmin:'false')); ?>",
            VERSION: "<?php echo htmlentities((isset($version) && ($version !== '')?$version:'1.0.0')); ?>",
        };
    </script>
    <script src="/static/plugs/layui-v2.5.6/layui.all.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
    <script src="/static/plugs/require-2.3.6/require.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
    <script src="/static/config-admin.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
</head>
<body>
<div class="layuimini-container">
    <div class="layui-card">
        <div class="layui-card-header"><?php echo htmlentities($goodsRow['title']); ?>   推广效果详情</div>
        <div class="layui-card-body">

            <div class="layui-row layui-col-space20">
                <div class="layui-col-md4">
                    <div class="layui-col-md12">商品名称  <i class="layui-icon layui-icon-about" lay-tips="商品标题" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['title']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">排期  <i class="layui-icon layui-icon-about" lay-tips="报名活动商品持续的时长" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['diffDay']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">引流UV  <i class="layui-icon layui-icon-about" lay-tips="在推广排期内所有商品推广后总去重引入UV" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['clickUv']); ?></div>
                </div>
            </div>

            <div class="layui-row layui-col-space20">
                <div class="layui-col-md4">
                    <div class="layui-col-md12">付款金额  <i class="layui-icon layui-icon-about" lay-tips="在推广排期内买家拍下并付款金额" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['alipayAmt']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">券使用量  <i class="layui-icon layui-icon-about" lay-tips="在排期时间内，该商品所匹配的优惠券，实际使用的数量" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['couponUseNum']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">付款笔数  <i class="layui-icon layui-icon-about" lay-tips="在推广排期内通过团长招商活动带来的买家付款的订单笔数" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['alipayNum']); ?></div>
                </div>
            </div>


            <div class="layui-row layui-col-space20">
                <div class="layui-col-md4">
                    <div class="layui-col-md12">当前佣金比率  <i class="layui-icon layui-icon-about" lay-tips="当前商家设置有效的佣金比率" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['currentCommissionRate']); ?>%</div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">报名佣金比率  <i class="layui-icon layui-icon-about" lay-tips="商家报名活动时设置的佣金比率" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['commissionRate']); ?>%</div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">最高佣金比率  <i class="layui-icon layui-icon-about" lay-tips="商家当前最高公开设置的佣金比率" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['currentMaxCommissionRate']); ?>%</div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">服务费率  <i class="layui-icon layui-icon-about" lay-tips="商家报名活动时设置的服务费率" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['serviceRate']); ?>%</div>
                </div>
            </div>


            <div class="layui-row layui-col-space20">
                <div class="layui-col-md4">
                    <div class="layui-col-md12">预估付款服务费  <i class="layui-icon layui-icon-about" lay-tips="审核通过商品的付款金额*对应服务费率的总和" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['preServiceFee']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">结算笔数  <i class="layui-icon layui-icon-about" lay-tips="在推广排期内通过团长招商活动带来的买家确认收货的订单笔数" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['settleNum']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">结算金额  <i class="layui-icon layui-icon-about" lay-tips="在推广排期内买家付款的订单，对应确认收货的付款金额（不包含运费金额）" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['settleAmt']); ?></div>
                </div>
            </div>


            <div class="layui-row layui-col-space20">
                <div class="layui-col-md4">
                    <div class="layui-col-md12">预估结算服务费  <i class="layui-icon layui-icon-about" lay-tips="审核通过商品的结算金额*对应服务费率的总和" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['cmServiceFee']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">淘宝客数  <i class="layui-icon layui-icon-about" lay-tips="在排期时间内有推广并达成消费者付款的淘宝客数" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($goodsRow['taokeNum']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">暂时置空</div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"></div>
                </div>
            </div>


        </div>
    </div>

</div>
</body>
</html>