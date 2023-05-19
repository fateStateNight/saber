define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'system.admin/index',
        add_url: 'system.admin/add',
        edit_url: 'system.admin/edit',
        delete_url: 'system.admin/delete',
        modify_url: 'system.admin/modify',
        export_url: 'system.admin/export',
        password_url: 'system.admin/password',
    };

    var Controller = {

        index: function () {

            ea.table.render({
                init: init,
                cols: [[
                    {type: "checkbox"},
<<<<<<< HEAD
                    {field: 'id', width: 80, title: 'ID', hide:true},
                    {field: 'sort', width: 80, title: '排序', edit: 'text', hide:true},
=======
                    {field: 'id', width: 80, title: 'ID'},
                    {field: 'sort', width: 80, title: '排序', edit: 'text'},
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
                    {field: 'username', minWidth: 80, title: '登录账户'},
                    {field: 'nickname', minWidth: 80, title: '昵称'},
                    {field: 'head_img', minWidth: 80, title: '头像', search: false, templet: ea.table.image},
                    {field: 'phone', minWidth: 80, title: '手机'},
<<<<<<< HEAD
                    {field: 'login_num', minWidth: 80, title: '登录次数', hide:true},
                    {field: 'remark', minWidth: 80, title: '备注信息'},
                    {field: 'status', title: '状态', width: 85, search: 'select', selectList: {0: '禁用', 1: '启用'}, templet: ea.table.switch},
                    {field: 'create_time', minWidth: 80, title: '创建时间', search: 'range', hide:true},
=======
                    {field: 'login_num', minWidth: 80, title: '登录次数'},
                    {field: 'remark', minWidth: 80, title: '备注信息'},
                    {field: 'status', title: '状态', width: 85, search: 'select', selectList: {0: '禁用', 1: '启用'}, templet: ea.table.switch},
                    {field: 'create_time', minWidth: 80, title: '创建时间', search: 'range'},
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
                    {
                        width: 250,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                            'edit',
                            [{
                                text: '设置密码',
                                url: init.password_url,
                                method: 'open',
                                auth: 'password',
                                class: 'layui-btn layui-btn-normal layui-btn-xs',
                            }],
                            'delete'
                        ]
                    }
                ]],
            });

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
        password: function () {
            ea.listen();
        }
    };
    return Controller;
});