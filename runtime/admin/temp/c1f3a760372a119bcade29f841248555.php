<?php /*a:1:{s:46:"/app/app/admin/view/integral/person/index.html";i:1603365531;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>积分管理</title>
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <link href="/static/admin/css/integral/style.css" rel="stylesheet" type="text/css" />
    <link href="/static/admin/css/integral/person.css" rel="stylesheet" type="text/css" />
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
<section class="jui-flexView">
    <!--<header class="jui-navBar jui-navBar-fixed">
        <a href="javascript:;" class="jui-navBar-item"><i class="icon icon-return"></i></a>
        <div class="jui-center">
            <span class="jui-center-title">个人中心</span>
        </div>
        <a href="javascript:;" class="jui-navBar-item" data-ydui-actionsheet="{target:'#actionSheet',closeElement:'#cancel'}"><i class="icon icon-share"></i></a>
    </header>-->
    <section class="jui-scrollView">
        <div class="jui-flex">
            <div class="jui-pus-user">
                <img src="<?php echo htmlentities($userInfo['headimgurl']); ?>" alt="" />
            </div>
            <div class="jui-flex-box">
                <h2><?php echo htmlentities($userInfo['nickname']); ?></h2>
                <span><?php if($userInfo['sex'] == 1): ?>男<?php else: ?>女<?php endif; ?></span>
                <p><?php echo htmlentities($userInfo['province']); ?><?php echo htmlentities($userInfo['city']); ?></p>
            </div>
            <?php if($applyNum <= 3): ?>
            <div class="jui-arrow">
                <div id="goodsData" style="display: none"><?php echo htmlentities($goodsData); ?></div>
                <button class="apply-integral" data-title="申请积分" data-user-id="<?php echo htmlentities($userInfo['id']); ?>" data-width="60%" data-height="40%"><i class="icon icon-pic"></i>申请</button>
            </div>
            <?php endif; ?>
        </div>
        <div class="jui-palace jui-palace-one b-line">
            <a href="javascript:void(0);" class="jui-palace-grid">
                <div class="jui-palace-grid-text">
                    <h2><em><?php echo htmlentities($userInfo['integral']); ?></em>积分</h2>
                </div></a>
            <a href="javascript:void(0);" class="jui-palace-grid">
                <div class="jui-palace-grid-text">
                    <h2><em><span id="applyNum"><?php echo htmlentities($applyNum); ?></span></em>待通过申请数</h2>
                </div></a>
            <a href="javascript:void(0);" class="jui-palace-grid">
                <div class="jui-palace-grid-text">
                    <h2><em><?php echo htmlentities($consumeNum); ?></em>礼品兑换数</h2>
                </div></a>
        </div>
        <!--<div class="jui-palace">
            <a href="javascript:void(0);" class="jui-palace-grid">
                <div class="jui-palace-grid-icon">
                    <img src="/static/admin/images/integral/person/nav-001.png" alt="" />
                </div>
                <div class="jui-palace-grid-text">
                    <h2>文章</h2>
                </div></a>
            <a href="javascript:void(0);" class="jui-palace-grid">
                <div class="jui-palace-grid-icon">
                    <img src="/static/admin/images/integral/person/nav-002.png" alt="" />
                </div>
                <div class="jui-palace-grid-text">
                    <h2>收藏</h2>
                </div></a>
            <a href="javascript:void(0);" class="jui-palace-grid">
                <div class="jui-palace-grid-icon">
                    <img src="/static/admin/images/integral/person/nav-003.png" alt="" />
                </div>
                <div class="jui-palace-grid-text">
                    <h2>钱包</h2>
                </div></a>
            <a href="javascript:void(0);" class="jui-palace-grid">
                <div class="jui-palace-grid-icon">
                    <img src="/static/admin/images/integral/person/nav-004.png" alt="" />
                </div>
                <div class="jui-palace-grid-text">
                    <h2>照片</h2>
                </div></a>
            <a href="javascript:void(0);" class="jui-palace-grid">
                <div class="jui-palace-grid-icon">
                    <img src="/static/admin/images/integral/person/nav-005.png" alt="" />
                </div>
                <div class="jui-palace-grid-text">
                    <h2>日历</h2>
                </div></a>
        </div>-->
        <div class="divHeight"></div>
        <div class="jui-flex b-line" style="padding:10px 15px;">
            <div class="jui-flex-box">
                <h3>积分明细</h3>
            </div>
        </div>
        <div class="jui-prblish">
            <?php if(is_array($recordList) || $recordList instanceof \think\Collection || $recordList instanceof \think\Paginator): if( count($recordList)==0 ) : echo "" ;else: foreach($recordList as $recordKey=>$record): ?>
            <a href="javascript:void(0);" class="jui-flex">
                <div class="jui-flex-box">
                    <span><?php echo htmlentities($record['create_time']); ?></span>
                    <div>
                        <div style="float:left;width:50%;"><?php echo htmlentities($record['record_title']); ?></div>
                        <?php if(($record['integral_status'] == 0)): ?>
                        <div class="integral-status-consume">已消耗</div>
                        <div class="integral-consume-show">-<?php echo htmlentities($record['integral_value']); ?>分</div>
                        <?php elseif($record['integral_status'] == 1): ?>
                        <div class="integral-status-apply">申请中</div>
                        <div class="integral-add-show">+<?php echo htmlentities($record['integral_value']); ?>分</div>
                        <?php elseif($record['integral_status'] == 2): ?>
                        <div class="integral-status-accept">已通过</div>
                        <div class="integral-add-show">+<?php echo htmlentities($record['integral_value']); ?>分</div>
                        <?php elseif($record['integral_status'] == 3): ?>
                        <div class="integral-status-reject">已拒绝</div>
                        <div class="integral-add-show">+<?php echo htmlentities($record['integral_value']); ?>分</div>
                        <?php else: ?>
                        <div class="integral-status-reject">未知</div>
                        <div class="integral-add-show">+<?php echo htmlentities($record['integral_value']); ?>分</div>
                        <?php endif; ?>
                    </div>
                </div></a>
            <div class="divHeight"></div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div style="height:52px;"></div>
    </section>

    <footer class="aui-footer aui-footer-fixed">
        <!--<a href="javascript:void(0);" class="aui-tabBar-item">
                    <span class="aui-tabBar-item-icon">
                        <i class="icon icon-loan"></i>
                    </span>
            <span class="aui-tabBar-item-text">首页</span>
        </a>
        <a href="javascript:void(0);" class="aui-tabBar-item ">
                    <span class="aui-tabBar-item-icon">
                        <i class="icon icon-credit"></i>
                    </span>
            <span class="aui-tabBar-item-text">产品</span>
        </a>-->
        <a href="<?php echo url('integral.exchange/index'); ?>" class="aui-tabBar-item">
                    <span class="aui-tabBar-item-icon">
                        <i class="icon icon-ions"></i>
                    </span>
            <span class="aui-tabBar-item-text">福利</span>
        </a>
        <a href="javascript:void(0);" class="aui-tabBar-item aui-tabBar-item-active ">
                    <span class="aui-tabBar-item-icon">
                        <i class="icon icon-car"></i>
                    </span>
            <span class="aui-tabBar-item-text">我的</span>
        </a>
    </footer>

<!--    <footer class="jui-footer jui-footer-fixed">
        <a href="javascript:;" class="jui-tabBar-item "><span class="jui-tabBar-item-icon"><i class="icon icon-loan"></i></span><span class="jui-tabBar-item-text">发现</span></a>
        <a href="javascript:;" class="jui-tabBar-item "><span class="jui-tabBar-item-icon"><i class="icon icon-ions "></i></span><span class="jui-tabBar-item-text">朋友</span></a>
        <a href="javascript:;" class="jui-tabBar-item "><span class="jui-tabBar-item-icon"><i class="icon icon-credit"></i></span><span class="jui-tabBar-item-text">发布</span></a>
        <a href="javascript:;" class="jui-tabBar-item "><span class="jui-tabBar-item-icon"><i class="icon icon-news"></i></span><span class="jui-tabBar-item-text">福利</span></a>
        <a href="javascript:;" class="jui-tabBar-item jui-tabBar-item-active"><span class="jui-tabBar-item-icon"><i class="icon icon-mine"></i></span><span class="jui-tabBar-item-text">我的</span></a>
    </footer>-->
</section>
<div class="m-actionsheet" id="actionSheet">
    <div class="jui-show-box">
        <div class="jui-show-box-title jui-footer-flex">
            <div class="b-line jui-coll-share-box">
                <a href="javascript:;" class="jui-coll-share-item">
                    <div class="jui-coll-share-img">
                        <img src="/static/admin/images/integral/person/icon-wx.png" alt="" />
                    </div>
                    <div class="jui-coll-share-text">
                        微信好友
                    </div></a>
                <a href="javascript:;" class="jui-coll-share-item">
                    <div class="jui-coll-share-img">
                        <img src="/static/admin/images/integral/person/icon-pyq.png" alt="" />
                    </div>
                    <div class="jui-coll-share-text">
                        朋友圈
                    </div></a>
                <a href="javascript:;" class="jui-coll-share-item">
                    <div class="jui-coll-share-img">
                        <img src="/static/admin/images/integral/person/icon-qq.png" alt="" />
                    </div>
                    <div class="jui-coll-share-text">
                        QQ
                    </div></a>
                <a href="javascript:;" class="jui-coll-share-item">
                    <div class="jui-coll-share-img">
                        <img src="/static/admin/images/integral/person/icon-kj.png" alt="" />
                    </div>
                    <div class="jui-coll-share-text">
                        QQ空间
                    </div></a>
                <a href="javascript:;" class="jui-coll-share-item">
                    <div class="jui-coll-share-img">
                        <img src="/static/admin/images/integral/person/icon-txw.png" alt="" />
                    </div>
                    <div class="jui-coll-share-text">
                        腾讯微博
                    </div></a>
                <a href="javascript:;" class="jui-coll-share-item">
                    <div class="jui-coll-share-img">
                        <img src="/static/admin/images/integral/person/icon-wb.png" alt="" />
                    </div>
                    <div class="jui-coll-share-text">
                        新浪微博
                    </div></a>
            </div>
            <div class="jui-coll-cancel">
                <a href="javascript:;" id="cancel" class=""> 取消</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>