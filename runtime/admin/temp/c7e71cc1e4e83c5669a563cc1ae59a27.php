<?php /*a:2:{s:45:"/app/app/admin/view/business/order/index.html";i:1652163312;s:39:"/app/app/admin/view/layout/default.html";i:1602818599;}*/ ?>
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
        <table id="currentTable" class="layui-table layui-hide" lay-filter="currentTable">
        </table>
    </div>
</div>



<script type="text/html" id="toolbarOrder">
    <div class="layui-btn-container">
        <div class="layui-table-tool-temp">
            <button class="layui-btn layui-btn-sm layuimini-btn-primary" data-table-refresh="currentTableRenderId"><i class="fa fa-refresh"></i> </button>
            <a class="layui-btn layui-btn-sm layui-bg-green" target="_self" href="javascript:void(0);" layuimini-content-href="<?php echo __url('system.script_task/index'); ?>" data-title="查看任务列表" ><i class="layui-icon layui-icon-list"></i>查看任务</a>
            <!--<button class="layui-btn layui-btn-normal layui-btn-sm" data-open="system.script_task/addOrder" data-title="创建数据任务"><i class="fa fa-plus"></i> 创建任务</button>
            <a class="layui-btn layui-btn-sm layui-bg-orange" target="_blank" href="https://www.childrendream.cn/grafana/" data-title="统计报表" ><i class="layui-icon layui-icon layui-icon-chart-screen"></i>统计报表</a>-->
            <button class="layui-btn layui-btn-sm layui-btn-success easyadmin-export-btn" data-url="business.order/export" data-table-export="currentTableRenderId"><i class="fa fa-file-excel-o"></i> 导出</button>
        </div>
    </div>
</script>

</body>
</html>