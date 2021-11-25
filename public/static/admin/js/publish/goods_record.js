define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'publish.goods_record/index',
        edit_url: 'publish.goods_record/edit',
        delete_url: 'publish.goods_record/delete',
        export_url: 'publish.goods_record/export',
        publish_url: 'publish.goods_record/publish',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh', 'delete', 'export'],
                cols: [[
                    {type: "checkbox"},
                    {field: 'id', width: 80, title: 'ID'},
                    {field: 'creater.nickname', title: '创建人', templet: function(d){
                            if(d.creater_id != 0 && d.creater_id != null && d.creater != ''){
                                createrName = d.creater.nickname;
                            }else{
                                createrName = '未知的神秘人';
                            }
                            return createrName;
                        }},
                    {field: 'admin.nickname', title: '推广人', templet: function(d){
                            if(d.admin_id != 0 && d.admin_id != null && d.admin != ''){
                                adminName = d.admin.nickname;
                            }else{
                                adminName = '';
                            }
                            return adminName;
                        }},
                    {field: 'sort', width: 80, title: '排序', edit: 'text', hide:true},
                    {field: 'cate.title', minWidth: 80, title: '商品分类'},
                    {field: 'title', minWidth: 80, title: '商品名称'},
                    {field: 'goods_link', minWidth: 80, title: '商品链接', search: false},
                    {field: 'coupon_link', minWidth: 80, title: '优惠券链接', search: false},
                    {field: 'market_price', width: 100, title: '市场价', templet: ea.table.price},
                    {field: 'discount_price', width: 100, title: '券后价', templet: ea.table.price},
                    /*{field: 'total_stock', width: 100, title: '库存统计'},
                    {field: 'stock', width: 100, title: '剩余库存'},
                    {field: 'virtual_sales', width: 100, title: '虚拟销量'},*/
                    {field: 'sales', width: 80, title: '销量'},
                    {field: 'status', title: '状态', width: 85, search: 'select', selectList: {0: '未推广', 1: '推广中', 2: '推广完成', 3: '已停止'}, templet: function(d){

                        switch(d.status){
                            case 0:
                                statusName = '<button class="layui-btn layui-btn-radius layui-btn-primary layui-btn-xs">'+this.selectList[d.status]+'</button>';
                                break;
                            case 1:
                                statusName = '<button class="layui-btn layui-btn-radius layui-btn-warm layui-btn-xs">'+this.selectList[d.status]+'</button>';
                                break;
                            case 2:
                                statusName = '<button class="layui-btn layui-btn-radius layui-btn-normal layui-btn-xs">'+this.selectList[d.status]+'</button>';
                                break;
                            case 3:
                                statusName = '<button class="layui-btn layui-btn-radius layui-btn-danger layui-btn-xs">'+this.selectList[d.status]+'</button>';
                                break;
                            default:
                                statusName = '<button class="layui-btn layui-btn-radius layui-btn-primary layui-btn-xs">'+this.selectList[d.status]+'</button>';
                                break;
                        }
                        return statusName;
                        }},
                    {field: 'create_time', minWidth: 80, title: '创建时间', search: 'range'},
                    {width: 300, title: '操作', toolbar: '#controlPlan'},
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