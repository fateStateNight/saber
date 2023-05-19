define(["jquery", "easy-admin"], function ($, ea) {

    var upload = layui.upload;
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'publish.customer/index',
        add_url: 'publish.customer/add',
        edit_url: 'publish.customer/edit',
        delete_url: 'publish.customer/delete',
        import_url: 'publish.customer/import',
        export_url: 'publish.customer/export',
        modify_url: 'publish.customer/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                /*toolbar: ['refresh','add', 'delete',
                    [{
                        text: '导入',
                        //url: init.import_url,
                        //method: 'request',
                        auth: 'import',
                        id: 'file-import',
                        class: 'layui-btn layui-btn-normal layui-btn-sm',
                        icon: 'layui-icon layui-icon-upload',
                    }], 'export'],*/
                toolbar: '#toolbarCustomer',
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id'},
                    {field: 'user_name', title: '用户名称'},
                    {field: 'phone', title: '手机号码'},
                    {field: 'province', title: '省'},
                    {field: 'city', title: '市'},
                    {field: 'area', title: '区'},
                    {field: 'address', title: '地址'},
                    {field: 'create_time', title: '创建时间', search: 'scope'},
                    {width: 250, title: '操作', templet: ea.table.tool},
                ]],
            });

            upload.render({
                elem: '#file-import',
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

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
    };
    return Controller;
});