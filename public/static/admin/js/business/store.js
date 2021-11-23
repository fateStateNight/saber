define(["jquery", "easy-admin", "formSelects", "miniTab"], function ($, ea, formSelects, miniTab) {

    var upload = layui.upload;
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'business.store/index',
        add_url: 'business.store/add',
        edit_url: 'business.store/edit',
        delete_url: 'business.store/delete',
        import_url: 'business.store/import',
        export_url: 'business.store/export',
        update_url: 'business.store/update',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                /*toolbar: ['refresh',
                    [{
                        text: '添加',
                        url: init.add_url,
                        method: 'open',
                        auth: 'add',
                        class: 'layui-btn layui-btn-normal layui-btn-sm',
                        icon: 'fa fa-plus ',
                        extend: 'data-full="true"',
                    }],
                    'delete', 'export'],*/
                toolbar: '#toolbarStore',
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id', hide: true},
                    {field: 'title', title: '商家标题'},
                    {field: 'qq_number', title: 'QQ号'},
                    {field: 'weixin', title: '微信号'},
                    {field: 'dingding', title: '钉钉号'},
                    {field: 'phone', title: '手机号'},
                    {field: 'share_level', title: '分享', search: 'select', selectList: {0: '关闭', 1: '开启'}, templet: ea.table.switch},
                    {field: 'creater_id', title: '创建者ID', hide: true},
                    {field: 'detail', title: '商家信息', hide: true},
                    {field: 'enclosure', title: '商家附件', templet: function(d){
                            if(d.enclosure != '' && d.enclosure != null){
                                showInfo = '<a class="layui-btn layui-btn-xs layui-btn-warm" href="'+d.enclosure+'">下载</a>';
                            }else{
                                showInfo = '';
                            }
                            return showInfo;
                        }},
                    {field: 'create_time', title: '创建时间', hide:true},
                    {field: 'systemAdmin.nickname', title: '昵称', hide:true},
                    {field: 'systemAdmin.head_img', title: '头像', search: false, templet: ea.table.image, hide: true},
                    {width: 250, title: '操作', toolbar: '#controlPlan'},
                    /*{
                        width: 250,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                            [{
                                text: '查看',
                                url: init.edit_url,
                                method: 'open',
                                auth: 'edit',
                                class: 'layui-btn layui-btn-xs layui-btn-success',
                                extend: 'data-full="true"',
                            }],
                            'delete']
                    },*/
                ]],
            });

            upload.render({
                elem: '#store-import',
                url: ea.url(init.import_url),
                accept: 'file', //普通文件
                exts: 'xlsx|xls',
                before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                    layer.load(); //上传loading
                },
                done: function(res, index, upload){
                    layer.closeAll('loading'); //关闭loading
                    ea.msg.success('导入成功！');
                },
                error: function(index, upload){
                    layer.closeAll('loading'); //关闭loading
                }
            });

            $("#store-update").on("click",function(){
                ea.request.get({
                    url: ea.url(init.update_url),
                    data: {},
                    //prefix: false,
                }, function (res) {
                    ea.msg.success(res.msg, function () {

                    })
                }, function(res){
                    ea.msg.error(res.msg);
                });
            });

            miniTab.listen();

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.msg.tips('注意啦，商家信息仅能修改5次哦！');
            var formInit = $("#app-form").serializeArray();
            var TextInit = JSON.stringify({ dataform: formInit });

            ea.listen(function(formData){
                var dataform = $("#app-form").serializeArray();
                var Text = JSON.stringify({ dataform: dataform });
                if(TextInit==Text) {
                    return false;
                }else{
                    return formData;
                }
            });
        },
    };
    return Controller;
});