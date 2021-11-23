<?php /*a:1:{s:44:"/app/app/admin/view/integral/draw/index.html";i:1602836833;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>领取免单</title>
	<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport" />
	<meta content="yes" name="apple-mobile-web-app-capable" />
	<meta content="black" name="apple-mobile-web-app-status-bar-style" />
	<meta content="telephone=no" name="format-detection" />
	<link href="/static/admin/css/integral/draw.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="/static/admin/css/public.css?v=<?php echo htmlentities($version); ?>" media="all">

	<script src="/static/plugs/layui-v2.5.6/layui.all.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
	<script src="/static/plugs/require-2.3.6/require.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
	<script>
		window.CONFIG = {
			ADMIN: "<?php echo htmlentities((isset($adminModuleName) && ($adminModuleName !== '')?$adminModuleName:'admin')); ?>",
			CONTROLLER_JS_PATH: "<?php echo htmlentities((isset($thisControllerJsPath) && ($thisControllerJsPath !== '')?$thisControllerJsPath:'')); ?>",
			ACTION: "<?php echo htmlentities((isset($thisAction) && ($thisAction !== '')?$thisAction:'')); ?>",
			AUTOLOAD_JS: "<?php echo htmlentities((isset($autoloadJs) && ($autoloadJs !== '')?$autoloadJs:'false')); ?>",
			IS_SUPER_ADMIN: "<?php echo htmlentities((isset($isSuperAdmin) && ($isSuperAdmin !== '')?$isSuperAdmin:'false')); ?>",
			VERSION: "<?php echo htmlentities((isset($version) && ($version !== '')?$version:'1.0.0')); ?>",
		};
		var BASE_URL = document.scripts[document.scripts.length - 1].src.substring(0, document.scripts[document.scripts.length - 1].src.lastIndexOf("/") + 1);
		BASE_URL = window.location.protocol + '//' + window.location.host + '/static/';
		window.BASE_URL = BASE_URL;
		require.config({
			urlArgs: "v=" + CONFIG.VERSION,//CONFIG.VERSION//new Date().getTime()
			baseUrl: BASE_URL,
			paths: {
				"jquery": ["plugs/jquery-3.4.1/jquery-3.4.1.min"],
				"jquery-particleground": ["plugs/jq-module/jquery.particleground.min"],
				"easy-admin": ["plugs/easy-admin/easy-admin"],
				"layuiall": ["plugs/layui-v2.5.6/layui.all"],
				"tableSelect": ["plugs/lay-module/tableSelect/tableSelect"],
				"ckeditor": ["plugs/ckeditor4/ckeditor"],
			}
		});

		// 路径配置信息
		var PATH_CONFIG = {
			iconLess: BASE_URL + "plugs/font-awesome-4.7.0/less/variables.less",
		};
		window.PATH_CONFIG = PATH_CONFIG;

		// 初始化控制器对应的JS自动加载
		if ("undefined" != typeof CONFIG.AUTOLOAD_JS && CONFIG.AUTOLOAD_JS) {
			require([BASE_URL + CONFIG.CONTROLLER_JS_PATH], function (Controller) {
				if (eval('Controller.' + CONFIG.ACTION)) {
					eval('Controller.' + CONFIG.ACTION + '()');
				}
			});
		}
	</script>
</head>
<body>
<!--


-->
<section class="aui-flexView">
	<header class="aui-navBar aui-navBar-fixed">
		<a href="javascript:void(0);" class="aui-navBar-item">
			<i class="icon "></i>
		</a>
		<div class="aui-center">
			<span class="aui-center-title">福利</span>
		</div>
		<a href="javascript:void(0);" class="aui-navBar-item">
			<i class="icon icon-sys"></i>
		</a>
	</header>
	<section class="aui-scrollView">
		<div class="aui-banner">
			<img src="/static/admin/images/integral/banner.png" alt="">
		</div>
		<div class="divHeight"></div>
		<div class="aui-back-white">
			<div class="aui-flex b-line">
				<div class="medicine-bag">
					<span id="koulin"><?php echo htmlentities($shortLink); ?></span>
				</div>
				<div type="button" data-clipboard-text="<?php echo htmlentities($shortLink); ?>" class="itemCopy" id="itemCopy" style="background-color: rgb(245, 77, 35); border-color: rgb(245, 77, 35);">一键复制</div>
			</div>
			<div class="aui-palace aui-palace-one">
				<!--<a href="javascript:" class="aui-palace-grid">
					<div class="aui-palace-grid-icon">
						<img src="/static/admin/images/integral/icon-we-001.png" alt="">
					</div>
					<div class="aui-palace-grid-text">
						<span>签到</span>
					</div>
				</a>-->
				<div style="text-align: center"><img style="width:200px;height:200px;" src="/static/admin/images/integral/erweima.jpg"></div>
				<p style="text-align: center;">领取口令异常可以重新打开页面领取，扫描上方二维码不定时可领取更多免单礼品哦</p>
			</div>
			<div class="divHeight"></div>
		</div>
		<div class="divHeight"></div>
	</section>
	<footer class="aui-footer aui-footer-fixed">
		<!--<a href="javascript:;" class="aui-tabBar-item">
                    <span class="aui-tabBar-item-icon">
                        <i class="icon icon-loan"></i>
                    </span>
			<span class="aui-tabBar-item-text">首页</span>
		</a>
		<a href="javascript:;" class="aui-tabBar-item aui-tabBar-item-active ">
                    <span class="aui-tabBar-item-icon">
                        <i class="icon icon-credit"></i>
                    </span>
			<span class="aui-tabBar-item-text">产品</span>
		</a>
		<a href="javascript:;" class="aui-tabBar-item">
                    <span class="aui-tabBar-item-icon">
                        <i class="icon icon-ions"></i>
                    </span>
			<span class="aui-tabBar-item-text">福利</span>
		</a>
		<a href="<?php echo url('integral.person/index'); ?>" class="aui-tabBar-item ">
                    <span class="aui-tabBar-item-icon">
                        <i class="icon icon-car"></i>
                    </span>
			<span class="aui-tabBar-item-text">我的</span>
		</a>-->
	</footer>
</section>
</body>

</html>
