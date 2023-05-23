<?php /*a:2:{s:43:"/app/app/admin/view/business/store/add.html";i:1603966709;s:39:"/app/app/admin/view/layout/default.html";i:1678949704;}*/ ?>
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
<!--    <script src="/static/plugs/lay-module/layim-v3.7.6/dist/layui.all.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>-->
    <script src="/static/plugs/require-2.3.6/require.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
    <script src="/static/config-admin.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
</head>
<body>
<div class="layuimini-container">
    <form id="app-form" class="layui-form layuimini-form">
        
        <div class="layui-form-item">
            <label class="layui-form-label">商家标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" class="layui-input"  placeholder="请输入商家标题" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商家QQ</label>
            <div class="layui-input-block">
                <input type="text" name="qq_number" class="layui-input"  placeholder="请输入QQ" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商家微信</label>
            <div class="layui-input-block">
                <input type="text" name="weixin" class="layui-input"  placeholder="请输入商家微信" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商家钉钉</label>
            <div class="layui-input-block">
                <input type="text" name="dingding" class="layui-input"  placeholder="请输入商家钉钉" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商家手机号</label>
            <div class="layui-input-block">
                <input type="text" name="phone" class="layui-input"  placeholder="请输入商家手机号" value="">
            </div>
        </div>
        <!--<div class="layui-form-item">
            <label class="layui-form-label">分享</label>
            <div class="layui-input-block">
                <input type="text" name="share_level" class="layui-input"  placeholder="请输入分享" value="0">
            </div>
        </div>-->
        <!--<div class="layui-form-item">
            <label class="layui-form-label">商家附件</label>
            <div class="layui-input-block">
                <input type="text" name="enclosure" class="layui-input"  placeholder="请输入商家附件" value="">
            </div>
        </div>-->
        <div class="layui-form-item">
            <label class="layui-form-label">商家附件</label>
            <div class="layui-input-block layuimini-upload">
                <input name="enclosure" class="layui-input layui-col-xs6"  placeholder="请上传商家附件" value="">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="enclosure" data-upload-number="one" data-upload-exts="ppt|xls|xlsx|ico|jpeg" data-upload-icon="image"><i class="fa fa-upload"></i> 上传</a></span>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商家信息</label>
            <div class="layui-input-block">
                <textarea name="detail" rows="20" class="layui-textarea editor" placeholder="商家信息"></textarea>
<!--                <input type="text" name="detail" rows="20" class="layui-textarea editor"  placeholder="请输入商家信息" value="">-->
            </div>
        </div>
        <div class="hr-line"></div>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>确认</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
        </div>

    </form>
</div>
</body>
</html>