<<<<<<< HEAD
<?php /*a:2:{s:49:"/app/app/admin/view/system/admin_group/index.html";i:1651143860;s:39:"/app/app/admin/view/layout/default.html";i:1602818599;}*/ ?>
=======
<?php /*a:2:{s:49:"/app/app/admin/view/system/admin_group/index.html";i:1606447388;s:39:"/app/app/admin/view/layout/default.html";i:1602818599;}*/ ?>
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
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
<link rel="stylesheet" href="/static/admin/css/admin/admin_group.css" media="all">
<div class="layuimini-container">
    <div class="layuimini-main">
        <div class="layui-card">
            <div class="layui-card-header" style="line-height: normal;height:auto;">
<<<<<<< HEAD
                <form class="layui-form layui-form-pane form-search" action="">


                    <div class="layui-col-md4">
                        <div class="grid-demo">
                            <div class="layui-form-item layui-inline">
                                <label class="layui-form-label">群组名称</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入群组名称" class="layui-input">
                                </div>
                            </div>
                        </div>
                    </div>

                        <div class="layui-col-md4">
                            <div class="grid-demo">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">群组创建时间</label>
                                    <div class="layui-input-inline">
=======
                <form class="layui-form" action="">
                    <div class="layui-row layui-col-space10">
                        <div class="layui-col-md3">
                            <div class="grid-demo grid-demo-bg1">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">群组名称</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入群组名称" class="layui-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-col-md3">
                            <div class="grid-demo">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">群组创建时间</label>
                                    <div class="layui-input-block">
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
                                        <input type="text" name="date" id="date" lay-verify="date" data-date="" data-date-type="date" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-col-md2">
                            <div class="grid-demo grid-demo-bg1">
                                <button type="button" class="layui-btn">
                                    <i class="layui-icon layui-icon-search"></i>搜索
                                </button>
                            </div>
                        </div>
<<<<<<< HEAD

                </form>

                <div class="layui-row layui-col-space10">
                    <div class="layui-col-md3">
=======
                    </div>
                </form>
                <div class="layui-row layui-col-space10">
                    <div class="layui-col-md2">
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
                        <button type="button" class="layui-btn" data-open="system.admin_group/add" data-full="true">
                            <i class="layui-icon">&#xe608;</i> 新建
                        </button>
                        <button type="button" class="layui-btn layui-btn-danger" id="deleteGroup">
                            <i class="layui-icon">&#xe640;</i> 删除
                        </button>
                    </div>
                </div>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <?php if($groupList == ''): ?>
                    <div style="width: 100%; height: 100px;text-align: center;line-height: 100px;">暂无群组</div>
                    <?php else: foreach($groupList as $id=>$groupInfo): ?>

                    <div class="layui-col-md3 card-box" style="margin:20px 40px;border-radius: 18px;border: 2px solid #d2d2d2;background-color: #fbfbfb;">
                        <div class="layuiadmin-card-text">
                            <div class="layui-text-top">
<<<<<<< HEAD
                                <div class="layui-row layui-form layui-col-space10">
=======
                                <div class="layui-row layui-form">
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
                                    <div class="layui-col-xs1">
                                        <?php if($groupInfo['create_id'] == $adminInfo['id']): ?>
                                        <div class="layui-form-item" style="margin-top:-10px;" pane="">
                                            <div class="layui-input-inline">
                                                <input type="checkbox" name="group_select" lay-skin="primary" title="" value="<?php echo htmlentities($groupInfo['id']); ?>" >
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="layui-col-xs4">
<<<<<<< HEAD
                                        <img src="<?php echo htmlentities($groupInfo['group_image']); ?>" style="width:70px;height:100px;">
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class=""> 群组名称：</label>
                                        <span class=""><?php echo htmlentities($groupInfo['title']); ?></span>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class=""> 群组人数：</label>
                                        <span class=""><?php echo htmlentities($groupInfo['member_num']); ?></span>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class=""> 备注：</label>
                                        <span class=""><?php echo htmlentities($groupInfo['remark']); ?></span>
                                    </div>
                                    <?php if($groupInfo['create_id'] == $adminInfo['id']): ?>
                                    <div class="layui-col-xs7">
                                        <label class="">公开：</label>
                                        <div class="layui-input-inline">
=======
                                        <img src="<?php echo htmlentities($groupInfo['group_image']); ?>" style="width:100%;height:140px;">
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 群组名称：</label>
                                        <div class="layui-form-mid layui-word-aux"><?php echo htmlentities($groupInfo['title']); ?></div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 群组人数：</label>
                                        <div class="layui-form-mid layui-word-aux"><?php echo htmlentities($groupInfo['member_num']); ?></div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 备注：</label>
                                        <div class="layui-form-mid layui-word-aux"><?php echo htmlentities($groupInfo['remark']); ?></div>
                                    </div>
                                    <?php if($groupInfo['create_id'] == $adminInfo['id']): ?>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label">是否公开：</label>
                                        <div class="layui-input-block">
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
                                            <input type="checkbox" name="publish" publish-groupId="<?php echo htmlentities($groupInfo['id']); ?>" lay-filter="publish-switch" lay-skin="switch" lay-text="ON|OFF" <?php if($groupInfo['publish'] == '1'): ?>checked="checked"<?php endif; ?> >
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                            <div class="layui-row layui-col-space10">
                                <?php if($groupInfo['create_id'] == $adminInfo['id']): ?>
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-normal layui-btn-radius" data-open="system.admin_group/edit?id=<?php echo htmlentities($groupInfo['id']); ?>" data-full="true">编辑群组</button>
                                </div>
                                <?php else: if(in_array(($groupInfo['id']), is_array($innerGroupId)?$innerGroupId:explode(',',$innerGroupId))): ?>
                                    <div class="layui-col-md4">
                                        <button type="button" class="layui-btn layui-btn-danger layui-btn-radius" data-request="system.admin_group/quitGroup?groupId=<?php echo htmlentities($groupInfo['id']); ?>">退出群组</button>
                                    </div>
                                    <?php else: ?>
                                    <div class="layui-col-md4">
                                        <button type="button" class="layui-btn layui-btn-warm layui-btn-radius" data-request="system.admin_group/joinGroup?groupId=<?php echo htmlentities($groupInfo['id']); ?>">进入群组</button>
                                    </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php endforeach; ?>
                    <?php endif; ?>
                    <!--<div class="layui-col-md3 card-box" style="margin:20px 40px;border-radius: 18px;border: 2px solid #d2d2d2;background-color: #fbfbfb;">
                        <div class="layuiadmin-card-text">
                            <div class="layui-text-top">
                                <div class="layui-row layui-form">
&lt;!&ndash;                                    <form class="layui-form" action="">&ndash;&gt;
                                        <div class="layui-col-xs1">
                                            <div class="layui-form-item" style="margin-top:-10px;" pane="">
                                                <div class="layui-input-inline">
                                                    <input type="checkbox" name="group_select" lay-skin="primary" title="" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-col-xs4">
                                            <img src="https://www.childrendream.cn/upload/20201103/d7cacae74487ad1c04cee5cfbdc686ea.jpg" style="width:100%;height:140px;">
                                        </div>
                                        <div class="layui-col-xs7">
                                                <label class="layui-form-label"> 群组名称：</label>
                                                <div class="layui-form-mid layui-word-aux">奋达科技</div>
                                        </div>
                                        <div class="layui-col-xs7">
                                                <label class="layui-form-label"> 群组人数：</label>
                                                <div class="layui-form-mid layui-word-aux">22</div>
                                        </div>
                                        <div class="layui-col-xs7">
                                                <label class="layui-form-label"> 备注：</label>
                                                <div class="layui-form-mid layui-word-aux">似懂非懂</div>
                                        </div>
                                        <div class="layui-col-xs7">
                                                <label class="layui-form-label">是否公开：</label>
                                                <div class="layui-input-block">
                                                    <input type="checkbox" name="close" lay-skin="switch" lay-text="ON|OFF">
                                                </div>
                                        </div>
&lt;!&ndash;                                    </form>&ndash;&gt;
                                </div>
                            </div>
                            <div class="layui-row layui-col-space10">
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-normal layui-btn-radius">编辑群组</button>
                                </div>
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-warm layui-btn-radius">进入群组</button>
                                </div>
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-danger layui-btn-radius">退出群组</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="layui-col-md3 card-box" style="margin:20px 40px;border-radius: 18px;border: 2px solid #d2d2d2;background-color: #fbfbfb;">
                        <div class="layuiadmin-card-text">
                            <div class="layui-text-top">
                                <div class="layui-row layui-form">
                                    &lt;!&ndash;                                    <form class="layui-form" action="">&ndash;&gt;
                                    <div class="layui-col-xs1">
                                        <div class="layui-form-item" style="margin-top:-10px;" pane="">
                                            <div class="layui-input-inline">
                                                <input type="checkbox" name="group_select" lay-skin="primary" title="" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-col-xs4">
                                        <img src="https://www.childrendream.cn/upload/20201103/d7cacae74487ad1c04cee5cfbdc686ea.jpg" style="width:100%;height:140px;">
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 群组名称：</label>
                                        <div class="layui-form-mid layui-word-aux">奋达科技</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 群组人数：</label>
                                        <div class="layui-form-mid layui-word-aux">22</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 备注：</label>
                                        <div class="layui-form-mid layui-word-aux">似懂非懂</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label">是否公开：</label>
                                        <div class="layui-input-block">
                                            <input type="checkbox" name="close" lay-skin="switch" lay-text="ON|OFF">
                                        </div>
                                    </div>
                                    &lt;!&ndash;                                    </form>&ndash;&gt;
                                </div>
                            </div>
                            <div class="layui-row layui-col-space10">
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-normal layui-btn-radius">编辑群组</button>
                                </div>
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-warm layui-btn-radius">进入群组</button>
                                </div>
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-danger layui-btn-radius">退出群组</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="layui-col-md3 card-box" style="margin:20px 40px;border-radius: 18px;border: 2px solid #d2d2d2;background-color: #fbfbfb;">
                        <div class="layuiadmin-card-text">
                            <div class="layui-text-top">
                                <div class="layui-row layui-form">
                                    &lt;!&ndash;                                    <form class="layui-form" action="">&ndash;&gt;
                                    <div class="layui-col-xs1">
                                        <div class="layui-form-item" style="margin-top:-10px;" pane="">
                                            <div class="layui-input-inline">
                                                <input type="checkbox" name="group_select" lay-skin="primary" title="" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-col-xs4">
                                        <img src="https://www.childrendream.cn/upload/20201103/d7cacae74487ad1c04cee5cfbdc686ea.jpg" style="width:100%;height:140px;">
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 群组名称：</label>
                                        <div class="layui-form-mid layui-word-aux">奋达科技</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 群组人数：</label>
                                        <div class="layui-form-mid layui-word-aux">22</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 备注：</label>
                                        <div class="layui-form-mid layui-word-aux">似懂非懂</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label">是否公开：</label>
                                        <div class="layui-input-block">
                                            <input type="checkbox" name="close" lay-skin="switch" lay-text="ON|OFF">
                                        </div>
                                    </div>
                                    &lt;!&ndash;                                    </form>&ndash;&gt;
                                </div>
                            </div>
                            <div class="layui-row layui-col-space10">
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-normal layui-btn-radius">编辑群组</button>
                                </div>
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-warm layui-btn-radius">进入群组</button>
                                </div>
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-danger layui-btn-radius">退出群组</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="layui-col-md3 card-box" style="margin:20px 40px;border-radius: 18px;border: 2px solid #d2d2d2;background-color: #fbfbfb;">
                        <div class="layuiadmin-card-text">
                            <div class="layui-text-top">
                                <div class="layui-row layui-form">
                                    &lt;!&ndash;                                    <form class="layui-form" action="">&ndash;&gt;
                                    <div class="layui-col-xs1">
                                        <div class="layui-form-item" style="margin-top:-10px;" pane="">
                                            <div class="layui-input-inline">
                                                <input type="checkbox" name="group_select" lay-skin="primary" title="" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-col-xs4">
                                        <img src="https://www.childrendream.cn/upload/20201103/d7cacae74487ad1c04cee5cfbdc686ea.jpg" style="width:100%;height:140px;">
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 群组名称：</label>
                                        <div class="layui-form-mid layui-word-aux">奋达科技</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 群组人数：</label>
                                        <div class="layui-form-mid layui-word-aux">22</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 备注：</label>
                                        <div class="layui-form-mid layui-word-aux">似懂非懂</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label">是否公开：</label>
                                        <div class="layui-input-block">
                                            <input type="checkbox" name="close" lay-skin="switch" lay-text="ON|OFF">
                                        </div>
                                    </div>
                                    &lt;!&ndash;                                    </form>&ndash;&gt;
                                </div>
                            </div>
                            <div class="layui-row layui-col-space10">
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-normal layui-btn-radius">编辑群组</button>
                                </div>
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-warm layui-btn-radius">进入群组</button>
                                </div>
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-danger layui-btn-radius">退出群组</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="layui-col-md3 card-box" style="margin:20px 40px;border-radius: 18px;border: 2px solid #d2d2d2;background-color: #fbfbfb;">
                        <div class="layuiadmin-card-text">
                            <div class="layui-text-top">
                                <div class="layui-row layui-form">
                                    &lt;!&ndash;                                    <form class="layui-form" action="">&ndash;&gt;
                                    <div class="layui-col-xs1">
                                        <div class="layui-form-item" style="margin-top:-10px;" pane="">
                                            <div class="layui-input-inline">
                                                <input type="checkbox" name="group_select" lay-skin="primary" title="" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-col-xs4">
                                        <img src="https://www.childrendream.cn/upload/20201103/d7cacae74487ad1c04cee5cfbdc686ea.jpg" style="width:100%;height:140px;">
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 群组名称：</label>
                                        <div class="layui-form-mid layui-word-aux">奋达科技</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 群组人数：</label>
                                        <div class="layui-form-mid layui-word-aux">22</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 备注：</label>
                                        <div class="layui-form-mid layui-word-aux">似懂非懂</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label">是否公开：</label>
                                        <div class="layui-input-block">
                                            <input type="checkbox" name="close" lay-skin="switch" lay-text="ON|OFF">
                                        </div>
                                    </div>
                                    &lt;!&ndash;                                    </form>&ndash;&gt;
                                </div>
                            </div>
                            <div class="layui-row layui-col-space10">
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-normal layui-btn-radius">编辑群组</button>
                                </div>
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-warm layui-btn-radius">进入群组</button>
                                </div>
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-danger layui-btn-radius">退出群组</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="layui-col-md3 card-box" style="margin:20px 40px;border-radius: 18px;border: 2px solid #d2d2d2;background-color: #fbfbfb;">
                        <div class="layuiadmin-card-text">
                            <div class="layui-text-top">
                                <div class="layui-row layui-form">
                                    &lt;!&ndash;                                    <form class="layui-form" action="">&ndash;&gt;
                                    <div class="layui-col-xs1">
                                        <div class="layui-form-item" style="margin-top:-10px;" pane="">
                                            <div class="layui-input-inline">
                                                <input type="checkbox" name="group_select" lay-skin="primary" title="" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-col-xs4">
                                        <img src="https://www.childrendream.cn/upload/20201103/d7cacae74487ad1c04cee5cfbdc686ea.jpg" style="width:100%;height:140px;">
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 群组名称：</label>
                                        <div class="layui-form-mid layui-word-aux">奋达科技</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 群组人数：</label>
                                        <div class="layui-form-mid layui-word-aux">22</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label"> 备注：</label>
                                        <div class="layui-form-mid layui-word-aux">似懂非懂</div>
                                    </div>
                                    <div class="layui-col-xs7">
                                        <label class="layui-form-label">是否公开：</label>
                                        <div class="layui-input-block">
                                            <input type="checkbox" name="close" lay-skin="switch" lay-text="ON|OFF">
                                        </div>
                                    </div>
                                    &lt;!&ndash;                                    </form>&ndash;&gt;
                                </div>
                            </div>
                            <div class="layui-row layui-col-space10">
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-normal layui-btn-radius">编辑群组</button>
                                </div>
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-warm layui-btn-radius">进入群组</button>
                                </div>
                                <div class="layui-col-md4">
                                    <button type="button" class="layui-btn layui-btn-danger layui-btn-radius">退出群组</button>
                                </div>
                            </div>
                        </div>
                    </div>-->

                </div>
            </div>
        </div>



    </div>
</div>
</body>
</html>