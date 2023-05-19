define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'mall.shoudan_goods/index',
        add_url: 'mall.shoudan_goods/add',
        edit_url: 'mall.shoudan_goods/edit',
        delete_url: 'mall.shoudan_goods/delete',
        export_url: 'mall.shoudan_goods/export',
        modify_url: 'mall.shoudan_goods/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},
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