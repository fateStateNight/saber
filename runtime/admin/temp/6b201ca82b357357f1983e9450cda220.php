<?php /*a:2:{s:45:"/app/app/admin/view/business/goods/index.html";i:1654137990;s:39:"/app/app/admin/view/layout/default.html";i:1602818599;}*/ ?>
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

    <?php if($eventId > 0): ?>
    <div class="layui-card">
        <div class="layui-card-header">推广效果数据汇总</div>
        <div class="layui-card-body">

            <div class="layui-row layui-col-space20">
                <div class="layui-col-md4">
                    <div class="layui-col-md12">活动名称  <i class="layui-icon layui-icon-about" lay-tips="活动标题" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 18px; font-weight: bold;"><?php echo htmlentities($showEventArr['title']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">活动时间  <i class="layui-icon layui-icon-about" lay-tips="报名活动商品持续的时间" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12"><?php echo htmlentities($showEventArr['startTime']); ?> 至 <?php echo htmlentities($showEventArr['endTime']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">推广商品数  <i class="layui-icon layui-icon-about" lay-tips="在推广期内总共审核通过的商品数量" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($showEventArr['auditPassed']); ?></div>
                </div>
            </div>

            <div class="layui-row layui-col-space20">
                <div class="layui-col-md4">
                    <div class="layui-col-md12">引流UV  <i class="layui-icon layui-icon-about" lay-tips="在推广排期内所有商品推广后总去重引入UV" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($showEventArr['clickUv']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">付款金额  <i class="layui-icon layui-icon-about" lay-tips="在推广排期内买家拍下并付款金额" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($showEventArr['alipayAmt']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">付款笔数  <i class="layui-icon layui-icon-about" lay-tips="在推广排期内通过团长招商活动带来的买家付款的订单笔数" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($showEventArr['alipayNum']); ?></div>
                </div>
            </div>


            <div class="layui-row layui-col-space20">
                <div class="layui-col-md4">
                    <div class="layui-col-md12">预估付款服务费  <i class="layui-icon layui-icon-about" lay-tips="审核通过商品的付款金额*对应服务费率的总和" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($showEventArr['preServiceFee']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">结算笔数  <i class="layui-icon layui-icon-about" lay-tips="在推广排期内通过团长招商活动带来的买家确认收货的订单笔数" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($showEventArr['settleNum']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">结算金额  <i class="layui-icon layui-icon-about" lay-tips="在推广排期内买家付款的订单，对应确认收货的付款金额（不包含运费金额）" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($showEventArr['settleAmt']); ?></div>
                </div>
            </div>


            <div class="layui-row layui-col-space20">
                <div class="layui-col-md4">
                    <div class="layui-col-md12">预估结算服务费  <i class="layui-icon layui-icon-about" lay-tips="审核通过商品的结算金额*对应服务费率的总和" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($showEventArr['cmServiceFee']); ?></div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">活动佣金比率要求  <i class="layui-icon layui-icon-about" lay-tips="活动设置的最低报名佣金比率" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"> 大于或等于<?php echo htmlentities($showEventArr['commissionRate']); ?>%</div>
                </div>
                <div class="layui-col-md4">
                    <div class="layui-col-md12">活动服务费率要求  <i class="layui-icon layui-icon-about" lay-tips="活动设置的报名服务费率" style="font-size: 20px;"></i></div>
                    <div class="layui-col-md12" style="font-size: 20px; font-weight: bold;"><?php echo htmlentities($showEventArr['serviceRate']); ?>%</div>
                </div>
            </div>


        </div>
    </div>
    <?php endif; ?>

    <div class="layuimini-main">
        <table id="currentTable" class="layui-table layui-hide"
               data-auth-add="<?php echo auth('business.goods/add'); ?>"
               data-auth-edit="<?php echo auth('business.goods/edit'); ?>"
               data-auth-delete="<?php echo auth('business.goods/delete'); ?>"
               lay-filter="currentTable">
        </table>
    </div>
</div>


<script type="text/html" id="toolbarGoods">
    <div class="layui-btn-container">
        <div class="layui-table-tool-temp">
            <button class="layui-btn layui-btn-sm layuimini-btn-primary" data-table-refresh="currentTableRenderId"><i class="fa fa-refresh"></i> </button>
            <?php if($adminInfo['auth_ids'] == 7 || $adminInfo['auth_ids'] == 1): ?>

            <button class="layui-btn layui-btn-sm" admin-data="<?php echo htmlentities($adminInfo['id']); ?>" data-url="business.goods/edit" data-table-edit="currentTableRenderId" data-title="修改" data-width="50%" data-height="50%"><i class="layui-icon layui-icon-edit"></i>修改 </button>

            <?php endif; ?>

            <button id="claimAll" class="layui-btn layui-btn-sm layui-btn-warm claimAll" admin-data="<?php echo htmlentities($adminInfo['id']); ?>" data-checkbox="true" data-request="business.goods/claimAll" data-title="批量认领" ><i class="layui-icon layui-icon-edit"></i>批量认领 </button>

            <button id="passAll" class="layui-btn layui-btn-sm layui-btn-normal claimAll" admin-data="<?php echo htmlentities($adminInfo['id']); ?>" data-checkbox="true" data-request="business.goods/passAll" data-title="批量通过" ><i class="layui-icon layui-icon-ok-circle"></i>批量通过 </button>

            <button id="refuseAll" class="layui-btn layui-btn-sm layui-btn-danger claimAll" admin-data="<?php echo htmlentities($adminInfo['id']); ?>" data-checkbox="true" data-request="business.goods/refuseAll" data-title="批量拒绝" ><i class="layui-icon layui-icon-close-fill"></i>批量拒绝 </button>
<!--            <button class="layui-btn layui-btn-sm" data-url="business.goods/edit" data-table-delete="currentTableRenderId"><i class="fa fa-trash-o"></i> 修改</button>-->
        </div>
    </div>
</script>


<script type="text/html" id="controlPlanGoods">
    {{#  if(d.auditorId == 0 && d.auditStatus == 1){ }}
    <a class="layui-btn layui-btn-xs layui-btn-warm" data-request="business.goods/claim?id={{d.id}}" href="javascript:void(0);" lay-event="claim" data-title="认领">认领</a>
    {{#  } }}
    {{#  if(d.auditorId != 0 && d.auditStatus == 1){ }}
    <a class="layui-btn layui-btn-xs layui-btn-normal" data-request="business.goods/pass?id={{d.id}}" href="javascript:void(0);" lay-event="pass" data-title="通过">通过</a>

    <a class="layui-btn layui-btn-xs layui-btn-danger" data-request="business.goods/refuse?id={{d.id}}" href="javascript:void(0);" lay-event="refuse" data-title="拒绝">拒绝</a>
    {{#  } }}
    {{#  if(d.priveledge == 7 || d.priveledge == 1){ }}
    <a class="layui-btn layui-btn-xs" data-open="business.goods/edit?id={{d.id}}" data-title="修改" data-width="50%" data-height="50%">修改</a>
    {{# } }}
    <a class="layui-btn layui-btn-xs layui-btn-success" data-open="business.goods/detail?id={{d.id}}" href="javascript:void(0);" data-title="商品推广效果详情" data-full="false">查看</a>
</script>


<style type="text/css">
    .claimAll{
        display: none;
    }
</style>
</body>
</html>