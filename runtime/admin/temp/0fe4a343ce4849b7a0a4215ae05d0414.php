<?php /*a:1:{s:49:"/app/app/admin/view/integral/cash_gift/index.html";i:1611909662;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>礼金商品</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,400i,500,700" rel="stylesheet">
    <link rel="stylesheet" href="/static/admin/css/integral/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="/static/admin/css/integral/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="/static/admin/css/integral/page-style.css" type="text/css" />
    <link rel="stylesheet" href="/static/admin/css/integral/cards-style.css" type="text/css" />
    <link rel="stylesheet" href="/static/admin/css/public.css" media="all">
    <script src="/static/plugs/layui-v2.5.6/layui.all.js" charset="utf-8"></script>
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

<body  >

<article class="col-md-12">

    <div class="layui-tab">
        <ul class="layui-tab-title" style="text-align: center;">
            <li class="layui-this"><h2>已充值完成的首单礼金</h2></li>
            <li><h2>待充值的礼金商品</h2></li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <!-- PRODUCT CARDS -->
<!--                <h3>已充值完成的首单礼金</h3>-->
                <div class="complete-goods cards-8 section-gray">
                    <div class="container">
<!--                        <div class="row">-->
                            
                            <div class="col-md-3">
                                <div class="card card-product">
                                    <div class="card-image">
                                        <a href="#"> <img class="img" src="/static/assets/images/about/about-1.jpg"> </a>
                                    </div>
                                    <div class="table">
                                        <h6 class="category text-rose"><button class="layui-btn copy-btn">一键复制</button></h6>
                                        <h4 class="card-caption">
                                            <a href="#">【衣未】家用挂烫机电熨斗</a>
                                        </h4>
                                        <div class="card-description">【送量杯！】干熨/湿熨陶瓷顺滑大底板，三档温度调节，1800W强劲蒸汽，快速去皱平痕，持续出气，熨烫无死角，适用于各种面料，快捷软化除皱，精致生活，触手可及~【赠运费险】</div>
                                        <div class="ftr">
                                            <div class="price">
                                                <h4>10000单</h4> </div>
                                            <div class="" style="float:right;line-height: 25px;">
                                                <i class="fa fa-clock-o"></i> 10:00
                                            </div>
                                        </div>
                                        <textarea style="display: none" class="copy-content"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card card-product">
                                    <div class="card-image">
                                        <a href="#"> <img class="img" src="/static/assets/images/about/about-2.jpg"> </a>
                                    </div>
                                    <div class="table">
                                        <h6 class="category text-rose"><button class="layui-btn copy-btn">一键复制</button></h6>
                                        <h4 class="card-caption">
                                            <a href="#">【衣未】家用挂烫机电熨斗</a>
                                        </h4>
                                        <div class="card-description">【送量杯！】干熨/湿熨陶瓷顺滑大底板，三档温度调节，1800W强劲蒸汽，快速去皱平痕，持续出气，熨烫无死角，适用于各种面料，快捷软化除皱，精致生活，触手可及~【赠运费险】</div>
                                        <div class="ftr">
                                            <div class="price">
                                                <h4>10000单</h4> </div>
                                            <div class="" style="float:right;line-height: 25px;">
                                                <i class="fa fa-clock-o"></i> 10:00
                                            </div>
                                        </div>
                                        <textarea style="display: none" class="copy-content"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card card-product">
                                    <div class="card-image">
                                        <a href="#"> <img class="img" src="/static/assets/images/about/about-3.jpg"> </a>
                                    </div>
                                    <div class="table">
                                        <h6 class="category text-rose"><button class="layui-btn copy-btn">一键复制</button></h6>
                                        <h4 class="card-caption">
                                            <a href="#">【衣未】家用挂烫机电熨斗</a>
                                        </h4>
                                        <div class="card-description">【送量杯！】干熨/湿熨陶瓷顺滑大底板，三档温度调节，1800W强劲蒸汽，快速去皱平痕，持续出气，熨烫无死角，适用于各种面料，快捷软化除皱，精致生活，触手可及~【赠运费险】</div>
                                        <div class="ftr">
                                            <div class="price">
                                                <h4>10000单</h4> </div>
                                            <div class="" style="float:right;line-height: 25px;">
                                                <i class="fa fa-clock-o"></i> 10:00
                                            </div>
                                        </div>
                                        <textarea style="display: none" class="copy-content"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card card-product">
                                    <div class="card-image">
                                        <a href="#"> <img class="img" src="/static/assets/images/about/about-4.jpg"> </a>
                                    </div>
                                    <div class="table">
                                        <h6 class="category text-rose"><button class="layui-btn copy-btn">一键复制</button></h6>
                                        <h4 class="card-caption">
                                            <a href="#">【衣未】家用挂烫机电熨斗</a>
                                        </h4>
                                        <div class="card-description">【送量杯！】干熨/湿熨陶瓷顺滑大底板，三档温度调节，1800W强劲蒸汽，快速去皱平痕，持续出气，熨烫无死角，适用于各种面料，快捷软化除皱，精致生活，触手可及~【赠运费险】</div>
                                        <div class="ftr">
                                            <div class="price">
                                                <h4>10000单</h4> </div>
                                            <div class="" style="float:right;line-height: 25px;">
                                                <i class="fa fa-clock-o"></i> 10:00
                                            </div>
                                        </div>
                                        <textarea style="display: none" class="copy-content"></textarea>
                                    </div>
                                </div>
                            </div>


<!--                        </div>-->

                    </div>
                </div>
            </div>

            <div class="layui-tab-item">
                <!-- TESTIMONIAL CARDS -->
<!--                <h3>待充值的礼金商品</h3>-->
                <div class="arrearage-goods cards-4 section-gray">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card card-testimonial">
                                    <div class="icon"> <i class="fa fa-quote-right"></i> </div>
                                    <div class="table">
                                        <h5 class="card-description">
                                            "Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit. Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit!"
                                        </h5> </div>
                                    <div class="ftr">
                                        <h4 class="card-caption">Debbon Amet</h4>
                                        <h6 class="category">@debbonamet</h6>
                                        <div class="card-avatar">
                                            <a href="#"> <img class="img" src="/static/assets/images/about/about-5.jpg"> </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-testimonial">
                                    <div class="icon"> <i class="fa fa-quote-right"></i> </div>
                                    <div class="table">
                                        <h5 class="card-description">
                                            "Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit. Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit!"
                                        </h5> </div>
                                    <div class="ftr">
                                        <h4 class="card-caption">Mary Dunst</h4>
                                        <h6 class="category">@marydunst</h6>
                                        <div class="card-avatar">
                                            <a href="#"> <img class="img" src="/static/assets/images/about/about-6.jpg"> </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-testimonial">
                                    <div class="icon"> <i class="fa fa-quote-right"></i> </div>
                                    <div class="table">
                                        <h5 class="card-description">
                                            "Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit. Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit!"
                                        </h5> </div>
                                    <div class="ftr">
                                        <h4 class="card-caption">Patrick Wood</h4>
                                        <h6 class="category">@patrickwood</h6>
                                        <div class="card-avatar">
                                            <a href="#"> <img class="img" src="/static/assets/images/about/about-7.jpg"> </a>
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

</article>

</body>
</html>