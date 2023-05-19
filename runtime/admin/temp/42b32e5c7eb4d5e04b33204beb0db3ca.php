<?php /*a:2:{s:47:"/app/app/admin/view/publish/customer/index.html";i:1605165182;s:39:"/app/app/admin/view/layout/default.html";i:1602818599;}*/ ?>
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
               data-auth-add="<?php echo auth('publish.customer/add'); ?>"
               data-auth-edit="<?php echo auth('publish.customer/edit'); ?>"
               data-auth-delete="<?php echo auth('publish.customer/delete'); ?>"
               lay-filter="currentTable">
        </table>
    </div>
</div>

<script type="text/html" id="toolbarCustomer">
    <div class="layui-btn-container">
        <div class="layui-table-tool-temp">
        <button class="layui-btn layui-btn-sm layuimini-btn-primary" data-table-refresh="currentTableRenderId"><i class="fa fa-refresh"></i> </button>
        <button class="layui-btn layui-btn-normal layui-btn-sm" data-open="publish.customer/add" data-title="添加"><i class="fa fa-plus"></i> 添加</button>
        <button class="layui-btn layui-btn-sm layui-btn-danger" data-url="publish.customer/delete" data-table-delete="currentTableRenderId"><i class="fa fa-trash-o"></i> 删除</button>
        <button class="layui-btn layui-btn-sm" id="file-import"><i class="layui-icon">&#xe67c;</i>导入</button>
        <button class="layui-btn layui-btn-sm layui-btn-success easyadmin-export-btn" data-url="publish.customer/export" data-table-export="currentTableRenderId"><i class="fa fa-file-excel-o"></i> 导出</button>
        </div>
    </div>
</script>

</body>
</html>