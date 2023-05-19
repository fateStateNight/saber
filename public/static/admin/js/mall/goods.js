define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'mall.goods/index',
        add_url: 'mall.goods/add',
        edit_url: 'mall.goods/edit',
        delete_url: 'mall.goods/delete',
        export_url: 'mall.goods/export',
        modify_url: 'mall.goods/modify',
        publish_url: 'mall.goods/publish',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh',
                    [{
                        text: '添加',
                        url: init.add_url,
                        method: 'open',
                        auth: 'add',
                        class: 'layui-btn layui-btn-normal layui-btn-sm',
                        icon: 'fa fa-plus ',
                        extend: 'data-full="true"',
                    }],
                    'delete', 'export'],
                cols: [[
                    {type: "checkbox"},
                    {field: 'id', width: 80, title: 'ID', hide:true},
                    {field: 'creater.nickname', width: 80 , title: '招商人', templet: function(d){
                            if(d.creater_id != 0 && d.creater_id != null && d.creater != ''){
                                createrName = d.creater.nickname;
                            }else{
                                createrName = '未知的神秘人';
                            }
                            return createrName;
                        }},
                    {field: 'admin.nickname', width: 80 , title: '推广人', templet: function(d){
                            if(d.admin_id != 0 && d.admin_id != null && d.admin != ''){
                                adminName = d.admin.nickname;
                            }else{
                                adminName = '';
                            }
                            return adminName;
                        }},
                    {field: 'sort', width: 80, title: '排序', edit: 'text', hide:true},
                    {field: 'cate.title', minWidth: 80, title: '商品分类', hide:true},
                    {field: 'title', minWidth: 80, title: '商品名称'},
                    {field: 'images', minWidth: 80, title: '商品图片', search: false, templet: ea.table.image},
                    {field: 'goods_link', minWidth: 80, title: '商品链接', search: false, templet: ea.table.url},
                    {field: 'coupon_link', minWidth: 80, title: '优惠券链接', search: false, templet: ea.table.url},
                    {field: 'market_price', width: 100, title: '市场价', templet: ea.table.price},
                    {field: 'discount_price', width: 100, title: '券后价', templet: ea.table.price},
                    /*{field: 'total_stock', width: 100, title: '库存统计'},
                    {field: 'stock', width: 100, title: '剩余库存'},
                    {field: 'virtual_sales', width: 100, title: '虚拟销量'},*/
                    {field: 'sales', width: 80, title: '销量', hide:true},
                    {field: 'status', title: '状态', width: 85, search: 'select', selectList: {0: '未推广', 1: '推广中', 2: '推广完成', 3: '已停止'}},
                    {field: 'create_time', minWidth: 80, title: '创建时间', search: 'range'},
                    {width: 250, title: '操作', toolbar: '#controlPlan'},
                    /*{
                        width: 250,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                            [{
                                text: '编辑',
                                url: init.edit_url,
                                method: 'open',
                                auth: 'edit',
                                class: 'layui-btn layui-btn-xs layui-btn-success',
                                extend: 'data-full="true"',
                            }, {
                                text: '开始推广',
                                url: init.publish_url,
                                method: 'open',
                                auth: 'publish',
                                class: 'layui-btn layui-btn-xs layui-btn-normal',
                            }],
                            'delete']
                    }*/
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
        publish: function () {
            ea.listen();
        },
    };
    return Controller;
});