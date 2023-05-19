<?php /*a:2:{s:48:"/app/app/admin/view/mall/integral_goods/add.html";i:1602389472;s:39:"/app/app/admin/view/layout/default.html";i:1602818599;}*/ ?>
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
            <label class="layui-form-label">商品ID</label>
            <div class="layui-input-block">
                <input type="text" name="item_id" class="layui-input"  placeholder="请输入商品ID" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" class="layui-input"  placeholder="请输入商品标题" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">商品图片</label>
            <div class="layui-input-block layuimini-upload">
                <input name="goods_image" class="layui-input layui-col-xs6"   placeholder="请上传商品图片" value="">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="goods_image" data-upload-number="one" data-upload-exts="png|jpg|ico|jpeg" data-upload-icon="image"><i class="fa fa-upload"></i> 上传</a></span>
                    <span><a class="layui-btn layui-btn-normal" id="select_goods_image" data-upload-select="goods_image" data-upload-number="one" data-upload-mimetype="image/*"><i class="fa fa-list"></i> 选择</a></span>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品类型</label>
            <div class="layui-input-block">
                <?php foreach($getGoodsTypeList as $k=>$v): ?>
                <input type="radio" name="goods_type" lay-filter="goods_type" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), explode(',',"0"))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="layui-form-item goods_price_select form-hidden">
            <label class="layui-form-label">商品金额</label>
            <div class="layui-input-block">
                <input type="text" name="goods_price" class="layui-input"  placeholder="请输入商品礼金金额" value="0">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品积分</label>
            <div class="layui-input-block">
                <input type="text" name="goods_integral" class="layui-input"  placeholder="请输入商品积分" value="0">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品开始时间</label>
            <div class="layui-input-block">
                <input type="text" name="begin_time" data-date="" data-date-type="datetime" class="layui-input"  placeholder="请输入商品开始时间" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品结束时间</label>
            <div class="layui-input-block">
                <input type="text" name="end_time" data-date="" data-date-type="datetime" class="layui-input"  placeholder="请输入商品结束时间" value="">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注说明</label>
            <div class="layui-input-block">
                <textarea name="remark" class="layui-textarea"  placeholder="请输入备注说明"></textarea>
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