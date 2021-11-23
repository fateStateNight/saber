define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'system.taobao_account/index',
        add_url: 'system.taobao_account/add',
        edit_url: 'system.taobao_account/edit',
        delete_url: 'system.taobao_account/delete',
        export_url: 'system.taobao_account/export',
        modify_url: 'system.taobao_account/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', title: 'id'},                    {field: 'name', title: '账户昵称'},                    {field: 'account_id', title: '账户ID'},                    {field: 'appkey', title: '账号'},                    {field: 'appsecret', title: '账号密钥'},                    {field: 'spread_id', title: '推广位ID'},                    {field: 'status', search: 'select', selectList: ["禁用","启用"], title: '状态', templet: ea.table.switch},                    {field: 'remark', title: '备注说明', templet: ea.table.text},                    {field: 'create_time', title: '创建时间'},                    {width: 250, title: '操作', templet: ea.table.tool},
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