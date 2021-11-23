<?php /*a:2:{s:46:"/app/app/admin/view/business/record/index.html";i:1604127252;s:39:"/app/app/admin/view/layout/default.html";i:1602818599;}*/ ?>
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
<div class="layui-col-md12">
    <div class="layui-card">
        <div class="layui-card-header">商家详情</div>
        <div class="layui-card-body">

            <ul class="layui-timeline">
                <?php foreach($recordData as $k=>$record): ?>
                <li class="layui-timeline-item">
                    <i class="layui-icon layui-timeline-axis"></i>
                    <div class="layui-timeline-content layui-text">
                        <h3 class="layui-timeline-title"><?php echo htmlentities($record['create_time']); ?></h3>
                        <div><span>商家名称：</span><span><?php echo htmlentities($record['title']); ?></span></div>
                        <div><span><i class="layui-icon layui-icon-login-qq"></i>QQ号：</span><span><?php echo htmlentities($record['qq_number']); ?></span></div>
                        <div><span><i class="layui-icon layui-icon-login-wechat"></i>微信号：</span><span><?php echo htmlentities($record['weixin']); ?></span></div>
                        <div><span>钉钉号：</span><span><?php echo htmlentities($record['dingding']); ?></span></div>
                        <div><span><i class="layui-icon layui-icon-cellphone"></i>手机号：</span><span><?php echo htmlentities($record['phone']); ?></span></div>
                        <?php if($record['enclosure'] != ''): ?>
                        <blockquote class="layui-elem-quote"><a href="<?php echo htmlentities($record['enclosure']); ?>"><?php echo htmlentities($record['enclosure']); ?></a></blockquote>
                        <?php endif; if($record['detail'] != ''): ?>
                        <div class="layuimini-notice">
                            <span class="layuimini-notice-title">商家描述</span>
                            <span class="layuimini-notice-extra"><?php echo htmlentities($record['create_time']); ?></span>
                            <span style="margin-left:20px;color:green;cursor: pointer;">(点击查看)</span>
                            <div class="layuimini-notice-content layui-hide">
                                <?php echo html_entity_decode(htmlspecialchars_decode($record['detail'])); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endforeach; ?>
                <li class="layui-timeline-item">
                    <i class="layui-icon layui-timeline-axis"></i>
                    <div class="layui-timeline-content layui-text">
                        <div class="layui-timeline-title">过去</div>
                    </div>
                </li>
            </ul>


        </div>
    </div>
</div>

</body>
</html>