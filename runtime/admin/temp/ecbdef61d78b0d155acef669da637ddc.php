<?php /*a:2:{s:45:"/app/app/admin/view/business/scene/index.html";i:1649933101;s:39:"/app/app/admin/view/layout/default.html";i:1602818599;}*/ ?>
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
    <div class="layuimini-main">
        <table id="currentTable" class="layui-table layui-hide"
               data-auth-add="<?php echo auth('business.scene/add'); ?>"
               data-auth-edit="<?php echo auth('business.scene/edit'); ?>"
               data-auth-delete="<?php echo auth('business.scene/delete'); ?>"
               lay-filter="currentTable">
        </table>
    </div>
</div>




<script type="text/html" id="controlPlanScene">
    <a class="layui-btn layui-btn-xs layui-btn-normal" target="_self" layuimini-content-href="<?php echo __url('business.goods/index'); ?>?eventId={{d.eventId}}" href="javascript:void(0);" lay-event="check" data-title="报名商品列表">审核</a>

    <a class="layui-btn layui-btn-xs layui-btn-success" event_link="ad.alimama.com/zhaoshang/cpevent/index.htm?keyword={{d.eventId}}&from=cp" lay-event="copy_link" id="copy_link" data-title="复制活动链接" >活动链接</a>
</script>
</body>
</html>