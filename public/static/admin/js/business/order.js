define(["jquery", "easy-admin", "miniTab"], function ($, ea, miniTab) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'business.order/index',
        add_url: 'business.order/add',
        edit_url: 'business.order/edit',
        delete_url: 'business.order/delete',
        export_url: 'business.order/export',
        synchronous_url: 'business.order/synchronous',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: '#toolbarOrder',
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id', hide:true},
                    {field: 'event_id', title: '活动ID', hide:true},
                    {field: 'trade_parent_id', title: '订单编号', hide:true},
                    {field: 'item_id', title: '商品ID', hide:true},
                    {field: 'item_title', title: '商品标题'},
                    {field: 'seller_shop_title', title: '店铺名称'},
                    {field: 'seller_nick', title: '掌柜旺旺', hide:true},
                    {field: 'item_price', title: '商品单价', hide:true},
                    {field: 'click_time', title: '点击时间'},
                    {field: 'tk_deposit_time', title: '预售付款时间', hide:true},
                    {field: 'tb_deposit_time', title: '淘宝预售付款时间', hide:true},
                    {field: 'deposit_price', title: '预售付款金额', hide:true},
                    {field: 'tk_create_time', title: '订单创建时间'},
                    {field: 'tb_paid_time', title: '淘宝付款时间', hide:true},
                    {field: 'tk_paid_time', title: '付款时间'},
                    {field: 'alipay_total_price', title: '付款金额'},
                    {field: 'tk_earning_time', title: '结算时间'},
                    {field: 'pay_price', title: '结算金额'},
                    {field: 'tk_status', search: 'select', selectList: {"3":"订单结算","12":"订单付款","13":"订单失效","14":"订单成功"}, title: '订单状态'},
                    {field: 'item_num', title: '商品数量'},
                    {field: 'tk_service_rate', title: '服务费率'},
                    {field: 'pre_service_fee', title: '预估付款服务费', hide:true},
                    {field: 'service_fee', title: '预估结算服务费', hide:true},
                    {field: 'cp_channel_id', title: '渠道ID', hide:true},
                    {field: 'cp_channel_name', title: '渠道名称', hide:true},
                    {field: 'pub_id', title: '推广者ID', hide:true},
                    {field: 'pub_nick_name', title: '推广者名称', hide:true},
                    // {width: 250, title: '操作', templet: ea.table.tool},
                ]],
            });

            miniTab.listen();

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