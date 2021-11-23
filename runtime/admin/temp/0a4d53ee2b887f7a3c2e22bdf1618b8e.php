<?php /*a:2:{s:47:"/app/app/admin/view/mall/shoudan_goods/add.html";i:1611906025;s:39:"/app/app/admin/view/layout/default.html";i:1602818599;}*/ ?>
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
            <label class="layui-form-label">商品名称</label>
            <div class="layui-input-block">
                <input type="text" name="title" class="layui-input" lay-verify="required" placeholder="请输入商品名称" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">商品logo</label>
            <div class="layui-input-block layuimini-upload">
                <input name="image" class="layui-input layui-col-xs6"   placeholder="请上传商品logo" value="">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="image" data-upload-number="one" data-upload-exts="png|jpg|ico|jpeg" data-upload-icon="image"><i class="fa fa-upload"></i> 上传</a></span>
                    <span><a class="layui-btn layui-btn-normal" id="select_image" data-upload-select="image" data-upload-number="one" data-upload-mimetype="image/*"><i class="fa fa-list"></i> 选择</a></span>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">库存</label>
            <div class="layui-input-block">
                <input type="text" name="total_num" class="layui-input"  placeholder="请输入库存" value="0">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品价格</label>
            <div class="layui-input-block">
                <input type="text" name="goods_price" class="layui-input"  placeholder="请输入商品价格" value="0.00">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">佣金比率</label>
            <div class="layui-input-block">
                <input type="text" name="commission" class="layui-input"  placeholder="请输入佣金比率" value="0">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">开始时间</label>
            <div class="layui-input-block">
                <input type="text" name="send_start_time" class="layui-input" data-date="" data-date-type="datetime"  placeholder="请输入开始时间" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">结束时间</label>
            <div class="layui-input-block">
                <input type="text" name="send_end_time" class="layui-input" data-date="" data-date-type="datetime"  placeholder="请输入结束时间" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品状态</label>
            <div class="layui-input-block">
                <?php foreach($getItemStatusList as $k=>$v): ?>
                <input type="radio" name="item_status" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), explode(',',"0"))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">商品类型</label>
            <div class="layui-input-block">
                <select name="mode" >
                    <option value=''></option>
                    <?php foreach($getModeList as $k=>$v): ?>
                    <option value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品描述</label>
            <div class="layui-input-block">
                <textarea name="describe" rows="20" class="layui-textarea editor" 商品描述 placeholder="请输入商品描述"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <?php foreach($getStatusList as $k=>$v): ?>
                <input type="radio" name="status" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), explode(',',"1"))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
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