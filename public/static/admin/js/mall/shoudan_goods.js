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
                    {type: 'checkbox'},                    {field: 'id', title: 'ID'},                    {field: 'item_id', title: '商品ID'},                    {field: 'title', title: '商品名称'},                    {field: 'image', title: '商品logo', templet: ea.table.image},                    {field: 'total_num', title: '库存'},                    {field: 'goods_price', title: '商品价格'},                    {field: 'commission', title: '佣金比率'},                    {field: 'send_start_time', title: '开始时间'},                    {field: 'send_end_time', title: '结束时间'},                    {field: 'item_status', search: 'select', selectList: {"1":"出售中","2":"已售完","0":"未出售"}, title: '商品状态'},                    {field: 'mode', search: 'select', selectList: {"1":"已充值商品","2":"未充值商品"}, title: '商品类型'},                    {field: 'status', search: 'select', selectList: ["下架","上架"], title: '状态', templet: ea.table.switch},                    {field: 'remark', title: '备注说明', templet: ea.table.text},                    {field: 'create_time', title: '创建时间'},                    {width: 250, title: '操作', templet: ea.table.tool},
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