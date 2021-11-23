<?php /*a:2:{s:48:"/app/app/admin/view/system/weixin_user/edit.html";i:1603279872;s:39:"/app/app/admin/view/layout/default.html";i:1602818599;}*/ ?>
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
    <form id="app-form" class="layui-form layuimini-form">
        
        <div class="layui-form-item">
            <label class="layui-form-label">用户昵称</label>
            <div class="layui-input-block">
                <input type="text" name="nickname" class="layui-input" lay-verify="required" placeholder="请输入用户昵称" value="<?php echo htmlentities((isset($row['nickname']) && ($row['nickname'] !== '')?$row['nickname']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户ID</label>
            <div class="layui-input-block">
                <input type="text" name="openid" class="layui-input"  placeholder="请输入用户ID" value="<?php echo htmlentities((isset($row['openid']) && ($row['openid'] !== '')?$row['openid']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">性别</label>
            <div class="layui-input-block">
                <input type="text" name="sex" class="layui-input" lay-verify="required" placeholder="请输入性别" value="<?php echo htmlentities((isset($row['sex']) && ($row['sex'] !== '')?$row['sex']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">省份</label>
            <div class="layui-input-block">
                <input type="text" name="province" class="layui-input" lay-verify="required" placeholder="请输入省份" value="<?php echo htmlentities((isset($row['province']) && ($row['province'] !== '')?$row['province']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">城市</label>
            <div class="layui-input-block">
                <input type="text" name="city" class="layui-input" lay-verify="required" placeholder="请输入城市" value="<?php echo htmlentities((isset($row['city']) && ($row['city'] !== '')?$row['city']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">社群ID</label>
            <div class="layui-input-block">
                <select name="group_id" lay-verify="required" lay-search>
                    <option value=''></option>
                    <?php foreach($getPublishWeixinGroupsList as $k=>$v): ?>
                    <option value='<?php echo htmlentities($k); ?>' <?php if(in_array(($k), is_array($row['group_id'])?$row['group_id']:explode(',',$row['group_id']))): ?>selected=""<?php endif; ?>><?php echo htmlentities($v); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">头像</label>
            <div class="layui-input-block layuimini-upload">
                <input name="headimgurl" class="layui-input layui-col-xs6"   placeholder="请上传头像" value="<?php echo htmlentities((isset($row['headimgurl']) && ($row['headimgurl'] !== '')?$row['headimgurl']:'')); ?>">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="headimgurl" data-upload-number="one" data-upload-exts="png|jpg|ico|jpeg" data-upload-icon="image"><i class="fa fa-upload"></i> 上传</a></span>
                    <span><a class="layui-btn layui-btn-normal" id="select_headimgurl" data-upload-select="headimgurl" data-upload-number="one" data-upload-mimetype="image/*"><i class="fa fa-list"></i> 选择</a></span>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="text" name="status" class="layui-input"  placeholder="请输入状态" value="<?php echo htmlentities((isset($row['status']) && ($row['status'] !== '')?$row['status']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">剩余积分</label>
            <div class="layui-input-block">
                <input type="text" name="integral" class="layui-input"  placeholder="请输入积分" value="<?php echo htmlentities((isset($row['integral']) && ($row['integral'] !== '')?$row['integral']:'0')); ?>">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注说明</label>
            <div class="layui-input-block">
                <textarea name="remark" class="layui-textarea"  placeholder="请输入备注说明"><?php echo (isset($row['remark']) && ($row['remark'] !== '')?$row['remark']:''); ?></textarea>
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