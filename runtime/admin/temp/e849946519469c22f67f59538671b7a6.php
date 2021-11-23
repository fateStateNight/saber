<?php /*a:1:{s:48:"/app/app/admin/view/integral/exchange/index.html";i:1603434727;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>积分兑换</title>
	<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport" />
	<meta content="yes" name="apple-mobile-web-app-capable" />
	<meta content="black" name="apple-mobile-web-app-status-bar-style" />
	<meta content="telephone=no" name="format-detection" />
	<link href="/static/admin/css/integral/style.css" rel="stylesheet" type="text/css" />
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
<!--	<script src="/static/config-admin.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>-->
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
				<div class="aui-flex-box">
					<div id="user_id" style="display: none"><?php echo htmlentities($weiUserInfo['id']); ?></div>
					<h2>我的积分: <em><?php echo htmlentities($weiUserInfo['integral']); ?>积分</em></h2>
				</div>
				<!--<div class="aui-arrow">
					<span>积分明细</span>
				</div>-->
			</div>
			<!--<div class="aui-palace aui-palace-one">
				<a href="javascript:" class="aui-palace-grid">
					<div class="aui-palace-grid-icon">
						<img src="/static/admin/images/integral/icon-we-001.png" alt="">
					</div>
					<div class="aui-palace-grid-text">
						<span>签到</span>
					</div>
				</a>
				<a href="javascript:" class="aui-palace-grid">
					<div class="aui-palace-grid-icon">
						<img src="/static/admin/images/integral/icon-we-002.png" alt="">
					</div>
					<div class="aui-palace-grid-text">
						<span>活动</span>
					</div>
				</a>
				<a href="javascript:" class="aui-palace-grid">
					<div class="aui-palace-grid-icon">
						<img src="/static/admin/images/integral/icon-we-003.png" alt="">
					</div>
					<div class="aui-palace-grid-text">
						<span>积分</span>
					</div>
				</a>
				<a href="javascript:" class="aui-palace-grid">
					<div class="aui-palace-grid-icon">
						<img src="/static/admin/images/integral/icon-we-004.png" alt="">
					</div>
					<div class="aui-palace-grid-text">
						<span>商城</span>
					</div>
				</a>
			</div>-->
			<div class="divHeight"></div>
			<div class="aui-flex b-line">
				<div class="aui-flex-box">
					<h3> <i class="icon icon-jf"></i> 商品兑换</h3>
				</div>
			</div>
			<div class="aui-list-theme">
				<?php if($awardList == ''): ?>
				<div style="width: 100%; height: 100px;text-align: center;line-height: 100px;">暂无可兑换商品，请稍后再查看</div>
				<?php else: foreach($awardList as $id=>$award): ?>
					<a href="javascript:void(0);" class="aui-list-theme-item">
						<div class="aui-list-img">
							<img src="<?php echo htmlentities($award['goods_image']); ?>" class="award-image" alt="">
						</div>
						<div class="aui-list-title">
							<h3><?php echo htmlentities($award['title']); ?></h3>
							<div class="aui-list-spell">
								<span><?php echo htmlentities($award['goods_integral']); ?> <em>积分</em>
									<span id="showTime<?php echo htmlentities($award['id']); ?>" class="showTime" data-time-value="<?php echo htmlentities($award['end_time']); ?>">剩余时间：<em>00:00:00</em></span>
								</span>
							</div>
							<button class="exchange" data-id="<?php echo htmlentities($award['id']); ?>" data-item-id="<?php echo htmlentities($award['item_id']); ?>"
									data-goods-price="<?php echo htmlentities($award['goods_price']); ?>" data-goods-integral="<?php echo htmlentities($award['goods_integral']); ?>"
									<?php if($weiUserInfo['integral'] < $award['goods_integral']): ?>disabled="true" style="border:1px solid #999;color:#999;"<?php endif; ?>
							>立即兑换</button>
						</div>
					</a>
					<?php endforeach; ?>
				<?php endif; ?>
				<!--<a href="javascript:;" class="aui-list-theme-item">
					<div class="aui-list-img">
						<img src="/static/admin/images/integral/ad-002.png" alt="">
					</div>
					<div class="aui-list-title">
						<h3>维达抽纸3层M码</h3>
						<div class="aui-list-spell">
							<span>20000 <em>积分</em></span>
						</div>
						<button>立即兑换</button>
					</div>
				</a>
				<a href="javascript:;" class="aui-list-theme-item">
					<div class="aui-list-img aui-list-img-mar-top">
						<div class="aui-list-img-text">
							<h2>抵扣劵</h2>
							<p>￥20.00</p>
						</div>
						<img src="/static/admin/images/integral/red.png" alt="">
					</div>
					<div class="aui-list-title">
						<h3>20元折扣红包</h3>
						<div class="aui-list-spell">
							<span>20000 <em>积分</em></span>
						</div>
						<button>立即兑换</button>
					</div>
				</a>
				<a href="javascript:;" class="aui-list-theme-item">
					<div class="aui-list-img aui-list-img-mar-top">
						<div class="aui-list-img-text">
							<h2>抵扣劵</h2>
							<p>￥20.00</p>
						</div>
						<img src="/static/admin/images/integral/red.png" alt="">
					</div>
					<div class="aui-list-title">
						<h3>20元折扣红包</h3>
						<div class="aui-list-spell">
							<span>20000 <em>积分</em></span>
						</div>
						<button>立即兑换</button>
					</div>
				</a>
				<a href="javascript:;" class="aui-list-theme-item">
					<div class="aui-list-img aui-list-img-mar-top">
						<div class="aui-list-img-text">
							<h2>抵扣劵</h2>
							<p>￥20.00</p>
						</div>
						<img src="/static/admin/images/integral/blue.png" alt="">
					</div>
					<div class="aui-list-title">
						<h3>20元折扣红包</h3>
						<div class="aui-list-spell">
							<span>20000 <em>积分</em></span>
						</div>
						<button>立即兑换</button>
					</div>
				</a>
				<a href="javascript:;" class="aui-list-theme-item">
					<div class="aui-list-img aui-list-img-mar-top">
						<div class="aui-list-img-text">
							<h2>抵扣劵</h2>
							<p>￥20.00</p>
						</div>
						<img src="/static/admin/images/integral/blue.png" alt="">
					</div>
					<div class="aui-list-title">
						<h3>20元折扣红包</h3>
						<div class="aui-list-spell">
							<span>20000 <em>积分</em></span>
						</div>
						<button>立即兑换</button>
					</div>
				</a>-->
			</div>
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
		<a href="javascript:;" class="aui-tabBar-item ">
                    <span class="aui-tabBar-item-icon">
                        <i class="icon icon-credit"></i>
                    </span>
			<span class="aui-tabBar-item-text">产品</span>
		</a>-->
		<a href="javascript:;" class="aui-tabBar-item aui-tabBar-item-active">
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
		</a>
	</footer>
</section>
</body>

</html>
