define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'publish.groups_record/index',
        add_url: 'publish.groups_record/add',
        edit_url: 'publish.groups_record/edit',
        delete_url: 'publish.groups_record/delete',
        export_url: 'publish.groups_record/export',
        modify_url: 'publish.groups_record/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id'},
                    {field: 'group_id', title: '社群ID', hide: true},
                    {field: 'goods_id', title: '商品ID', hide: true},
                    {field: 'member_num', title: '群成员数'},
                    {field: 'mallIntegralGoods.title', title: '商品名称'},
                    {field: 'sale_num', title: '出单数'},
                    {field: 'remark', title: '备注说明', templet: ea.table.text},
                    {field: 'create_time', title: '创建时间'},
                    {field: 'publishWeixinGroups.title', title: '群名称'},
                    {field: 'publishWeixinGroups.belong_id', title: '管理账号ID'},
                    {width: 250, title: '操作', templet: ea.table.tool},
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
    };
    return Controller;
});