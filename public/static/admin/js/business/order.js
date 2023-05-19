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
<<<<<<< HEAD
                defaultToolbar: ['filter'],
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id', hide:true, search: false},
                    {field: 'auditorNick', title: '认领人', hide:true, search: false},
                    //{field: 'event_id', title: '活动ID', hide:true, searchOp:'='},
                    //{field: 'trade_parent_id', title: '订单编号', hide:true, searchOp:'='},
                    {field: 'item_id', title: '商品ID', hide:true, searchOp:'='},
                    {field: 'item_title', title: '商品标题'},
                    {field: 'seller_shop_title', title: '店铺名称'},
                    {field: 'seller_nick', title: '掌柜旺旺', hide:true, search: false},
                    {field: 'item_price', title: '商品单价', hide:true, search: false},
                    {field: 'click_time', title: '点击时间', search: false, hide: true},
                    {field: 'tk_deposit_time', title: '预售付款时间', hide:true, search: false},
                    {field: 'tb_deposit_time', title: '淘宝预售付款时间', hide:true, search: false},
                    {field: 'deposit_price', title: '预售付款金额', hide:true, search: false},
                    {field: 'tk_create_time', title: '订单创建时间', search: 'scope'},
                    {field: 'tb_paid_time', title: '淘宝付款时间', hide:true, search: false},
                    {field: 'tk_paid_time', title: '付款时间', search: false, hide: true},
                    {field: 'alipay_total_price', title: '付款金额', search: false},
                    {field: 'tk_earning_time', title: '结算时间', search: 'scope'},
                    {field: 'pay_price', title: '结算金额', search: false, hide: true},
                    {field: 'tk_status', search: 'select', selectList: {"3":"订单结算","12":"订单付款","13":"订单失效","14":"订单成功"}, title: '订单状态'},
                    {field: 'item_num', title: '商品数量', search: false},
                    {field: 'tk_service_rate', title: '服务费率', search: false, hide: true},
                    {field: 'pre_service_fee', title: '预估付款服务费', hide:true, search: false},
                    {field: 'service_fee', title: '预估结算服务费', hide:true, search: false},
                    {field: 'marketing_type', title: '营销类型', hide:true, search: false},
                    {field: 'cp_channel_id', title: '渠道ID', hide:true, search: false},
                    {field: 'cp_channel_name', title: '渠道名称', hide:true, search: false},
                    {field: 'pub_id', title: '推广者ID', hide:true, search: false},
                    {field: 'pub_nick_name', title: '推广者名称', hide:true, search: false},
=======
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
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
                    // {width: 250, title: '操作', templet: ea.table.tool},
                ]],
            });

            miniTab.listen();
<<<<<<< HEAD
            //不处理搜索表单的监听
=======

>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
<<<<<<< HEAD
        export: function () {
            ea.listen();
        },
=======
>>>>>>> 81d30d90cacb2d3f44cb1e832c96f4c5286f4d8e
    };
    return Controller;
});