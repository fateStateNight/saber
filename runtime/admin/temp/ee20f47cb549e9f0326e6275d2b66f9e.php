<?php /*a:2:{s:48:"/app/app/admin/view/mall/common_tools/index.html";i:1630376126;s:39:"/app/app/admin/view/layout/default.html";i:1602818599;}*/ ?>
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
<div class="layui-tab layui-tab-card">
    <ul class="layui-tab-title">
        <li class="layui-this">招商效果数据导出</li>
        <li class="">淘口令解析</li>
        <li>生成淘口令</li>
        <li>高效转链</li>
        <li>商品查询</li>
        <li>店铺列表查询</li>
        <li>解密</li>
    </ul>
    <div class="layui-tab-content" style="height: 100px;">

        <div class="layui-tab-item layui-show">
            <div class="layui-col-xs6 layui-col-sm7 layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">招商效果数据导出</div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <div class="layui-col-xs6 layui-col-sm6 layui-col-md5">
                                <div class="">
                                    <textarea id="activeId" required="" lay-verify="required" placeholder="请输入活动ID，多个ID请用逗号隔开，注意是英文逗号" class="layui-textarea"></textarea>
                                </div>
                            </div>
                            <div class="layui-col-xs6 layui-col-sm6 layui-col-md2" style="min-height: 100px;">
                                <div class="" style="text-align: center;line-height: 100px;">
                                    <button id="business_get" type="button" class="layui-btn layui-btn-radius">开始获取</button>
                                </div>
                            </div>
                            <div class="layui-col-xs4 layui-col-sm12 layui-col-md5">
                                <div class="">
                                    <textarea id="cookie_content" name="" required="" lay-verify="required" placeholder="请输入账号cookie" class="layui-textarea"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="layui-row" style="margin-top:20px;">
                            <div id="order_file" class="layui-col-md2"></div>
                            <div id="sale_file" class="layui-col-md2"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="layui-tab-item">
            <div class="layui-col-xs6 layui-col-sm7 layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">淘口令解析</div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <div class="layui-col-xs6 layui-col-sm6 layui-col-md5">
                                <div class="">
                                    <textarea id="old_content" required="" lay-verify="required" placeholder="请输入" class="layui-textarea"></textarea>
                                </div>
                            </div>
                            <div class="layui-col-xs6 layui-col-sm6 layui-col-md2" style="min-height: 100px;">
                                <div class="" style="text-align: center;line-height: 100px;">
                                    <button id="analysis-btn" type="button" class="layui-btn layui-btn-radius">解析</button>
                                </div>
                            </div>
                            <div class="layui-col-xs4 layui-col-sm12 layui-col-md5">
                                <div class="">
                                    <textarea id="new_content" name="" required="" lay-verify="required" placeholder="解析结果" class="layui-textarea"></textarea>
                                </div>
                            </div>
                            <div class="layui-col-xs4 layui-col-sm12 layui-col-md12" style="min-height: 25px;">
                                <div class="" style="text-align: center;line-height: 25px;">
                                    <i class="">||</i>
                                </div>
                            </div>
                            <div class="layui-col-xs4 layui-col-sm12 layui-col-md12" style="min-height: 25px;">
                                <div class="" style="text-align: center;line-height: 25px;">
                                    <i class="layui-icon layui-icon-down"></i>
                                </div>
                            </div>
                            <div class="layui-col-xs4 layui-col-sm12 layui-col-md12" style="min-height: 50px;">
                                <div class="layui-form" style="text-align: center;line-height: 50px;">
                                    <div class="layui-inline">
                                        <div class="layui-input-inline">
                                            <select name="account_id" >
                                                <?php foreach($getSystemTaobaoAccountList as $k=>$v): ?>
                                                <option value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-col-xs4 layui-col-sm12 layui-col-md12" style="min-height: 50px;">
                                <div class="" style="text-align: center;line-height: 50px;">
                                    <button id="transfer-btn" type="button" class="layui-btn layui-btn-radius">转换口令</button>
                                </div>
                            </div>
                            <div class="layui-col-xs4 layui-col-sm12 layui-col-md12" style="min-height: 100px;">
                                <div class="" style="text-align: center;margin-top:30px;">
                                    <div style="layui-col-xs4 layui-col-sm4 layui-col-md4">
                                        <div class="layui-inline">
                                            <div class="layui-input-inline">
                                                <button id="quick-transfer-btn" type="button" class="layui-btn layui-btn-normal layui-btn-radius">一键转换</button>
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <div class="layui-input-inline">
                                                <input type="text" name="taokouling" class="layui-input" autocomplete="off"  placeholder="口令结果" value="">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <div class="layui-input-inline">
                                                <button id="copy-btn" type="button" class="layui-btn layui-btn-normal layui-btn-radius">复制口令</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="layui-tab-item">
            <div class="layui-col-xs6 layui-col-sm7 layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">测试接口</div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <div class="layui-col-xs6 layui-col-sm6 layui-col-md2" style="min-height: 100px;">
                                <div class="" style="text-align: center;line-height: 100px;">
                                    <div class="layui-inline">
                                        <div class="layui-input-inline">
                                            <button id="get-group-btn" type="button" class="layui-btn layui-btn-normal layui-btn-radius">获取</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-col-xs4 layui-col-sm12 layui-col-md5">
                                <div class="">
                                    <textarea id="group-list" rows="10" name="" required="" lay-verify="required" placeholder="获取结果" class="layui-textarea"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="layui-tab-item">
            <div class="layui-col-xs6 layui-col-sm7 layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">高效转链</div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <div class="layui-col-xs6 layui-col-sm6 layui-col-md5">
                                <div class="">
                                    <textarea id="goods_link" rows="10" required="" lay-verify="required" placeholder="请输入" class="layui-textarea"></textarea>
                                </div>
                            </div>
                            <div class="layui-col-xs6 layui-col-sm6 layui-col-md2" style="min-height: 100px;">
                                <div class="" style="text-align: center;line-height: 100px;">
                                    <div class="layui-inline layui-form">
                                        <div class="layui-input-inline">
                                            <select name="effective_account_id" >
                                                <?php foreach($getSystemTaobaoAccountList as $k=>$v): ?>
                                                <option value='<?php echo htmlentities($k); ?>' ><?php echo htmlentities($v); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <div class="layui-input-inline">
                                            <button id="effective-btn" type="button" class="layui-btn layui-btn-normal layui-btn-radius">转链</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-col-xs4 layui-col-sm12 layui-col-md5">
                                <div class="">
                                    <textarea id="new_goods_link" rows="10" name="" required="" lay-verify="required" placeholder="转链结果" class="layui-textarea"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="layui-tab-item">
            <div class="layui-col-xs6 layui-col-sm7 layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">商品数据</div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <div class="layui-col-xs6 layui-col-sm6 layui-col-md5">
                                <div class="">
                                    <textarea id="order_parameter" rows="10" required="" lay-verify="required" placeholder="请输入" class="layui-textarea"></textarea>
                                </div>
                            </div>
                            <div class="layui-col-xs6 layui-col-sm6 layui-col-md2" style="min-height: 100px;">
                                <div class="" style="text-align: center;line-height: 100px;">
                                    <div class="layui-inline">
                                        <div class="layui-input-inline">
                                            <button id="get-order-btn" type="button" class="layui-btn layui-btn-normal layui-btn-radius">获取</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-col-xs4 layui-col-sm12 layui-col-md5">
                                <div class="">
                                    <textarea id="order_result" rows="10" name="" required="" lay-verify="required" placeholder="订单数据" class="layui-textarea"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="layui-tab-item">
            <div class="layui-col-xs6 layui-col-sm7 layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">获取店铺列表信息</div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <div class="layui-col-xs6 layui-col-sm6 layui-col-md5">
                                <div class="">
                                    <textarea id="shop_category" rows="10" required="" lay-verify="required" placeholder="请输入" class="layui-textarea"></textarea>
                                </div>
                            </div>
                            <div class="layui-col-xs6 layui-col-sm6 layui-col-md2" style="min-height: 100px;">
                                <div class="" style="text-align: center;line-height: 100px;">
                                    <div class="layui-inline">
                                        <div class="layui-input-inline">
                                            <button id="category-btn" type="button" class="layui-btn layui-btn-normal layui-btn-radius">获取</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-col-xs4 layui-col-sm12 layui-col-md5">
                                <div class="">
                                    <textarea id="shop_result" rows="10" name="" required="" lay-verify="required" placeholder="店铺列表信息" class="layui-textarea"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="layui-tab-item">
            <div class="layui-col-xs6 layui-col-sm7 layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">解密</div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <div class="layui-col-xs6 layui-col-sm6 layui-col-md5">
                                <div class="">
                                    <textarea id="source_string" rows="10" required="" lay-verify="required" placeholder="请输入" class="layui-textarea"></textarea>
                                </div>
                            </div>
                            <div class="layui-col-xs6 layui-col-sm6 layui-col-md2" style="min-height: 100px;">
                                <div class="" style="text-align: center;line-height: 100px;">
                                    <div class="layui-inline">
                                        <div class="layui-input-inline">
                                            <button id="resolve-btn" type="button" class="layui-btn layui-btn-normal layui-btn-radius">解密</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-col-xs4 layui-col-sm12 layui-col-md5">
                                <div class="">
                                    <textarea id="resolve_result" rows="10" name="" required="" lay-verify="required" placeholder="解密结果" class="layui-textarea"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>