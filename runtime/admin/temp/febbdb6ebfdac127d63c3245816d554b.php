<?php /*a:2:{s:44:"/app/app/admin/view/business/store/edit.html";i:1604116138;s:39:"/app/app/admin/view/layout/default.html";i:1602818599;}*/ ?>
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
<link rel="stylesheet" href="/static/plugs/lay-module/formSelects/formSelects-v4.css" media="all"/>
<div class="layuimini-container">
    <form id="app-form" class="layui-form layuimini-form">

        <div class="layui-form-item">
            <label class="layui-form-label">商家标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" class="layui-input"  placeholder="请输入商家标题" value="<?php echo htmlentities((isset($row['title']) && ($row['title'] !== '')?$row['title']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商家QQ</label>
            <div class="layui-input-block">
                <input type="text" name="qq_number" class="layui-input"  placeholder="请输入商家QQ" value="<?php echo htmlentities((isset($row['qq_number']) && ($row['qq_number'] !== '')?$row['qq_number']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商家微信</label>
            <div class="layui-input-block">
                <input type="text" name="weixin" class="layui-input"  placeholder="请输入商家微信" value="<?php echo htmlentities((isset($row['weixin']) && ($row['weixin'] !== '')?$row['weixin']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商家钉钉</label>
            <div class="layui-input-block">
                <input type="text" name="dingding" class="layui-input"  placeholder="请输入商家钉钉" value="<?php echo htmlentities((isset($row['dingding']) && ($row['dingding'] !== '')?$row['dingding']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商家手机号</label>
            <div class="layui-input-block">
                <input type="text" name="phone" class="layui-input"  placeholder="请输入商家手机号" value="<?php echo htmlentities((isset($row['phone']) && ($row['phone'] !== '')?$row['phone']:'')); ?>">
            </div>
        </div>

        <?php if($row['share_level'] == 1): ?>
        <div class="layui-form-item">
            <label class="layui-form-label">共享成员</label>
            <div class="layui-input-block">
                <select name="admin_id" xm-select="select7_1" xm-select-search="">
                    <option value=''></option>
                    <?php foreach($getSystemAdminList as $k=>$v): ?>
                    <option value='<?php echo htmlentities($k); ?>' <?php if($adminInfo['id'] == $k): ?> selected="selected" disabled="disabled" <?php elseif(in_array($k,$shareIdInfo)): ?> selected="selected" <?php else: ?>  <?php endif; ?> ><?php echo htmlentities($v); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php endif; ?>

        <div class="layui-form-item">
            <label class="layui-form-label">商家附件</label>
            <div class="layui-input-block layuimini-upload">
                <input name="enclosure" class="layui-input layui-col-xs6"  placeholder="请上传商家附件" value="<?php echo htmlentities((isset($row['enclosure']) && ($row['enclosure'] !== '')?$row['enclosure']:'')); ?>">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="enclosure" data-upload-number="one" data-upload-exts="ppt|xls|xlsx|ico|jpeg" data-upload-icon="image"><i class="fa fa-upload"></i> 上传</a></span>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商家信息</label>
            <div class="layui-input-block">
                <textarea name="detail" rows="20" class="layui-textarea editor" placeholder="商家信息"><?php echo (isset($row['detail']) && ($row['detail'] !== '')?$row['detail']:''); ?></textarea>
            </div>
        </div>
        <div class="hr-line"></div>

        <?php if($row['creater_id'] == $adminInfo['id'] || $adminInfo['auth_ids'] == 7): ?>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit lay-filter="store_modify">确认</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
        </div>
        <?php endif; ?>

    </form>
</div>


</body>
</html>