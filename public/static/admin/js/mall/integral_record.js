define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'mall.integral_record/index',
        add_url: 'mall.integral_record/add',
        edit_url: 'mall.integral_record/edit',
        delete_url: 'mall.integral_record/delete',
        export_url: 'mall.integral_record/export',
        modify_url: 'mall.integral_record/modify',
        accept_url: 'mall.integral_record/accept',
        reject_url: 'mall.integral_record/reject',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id'},
                    {field: 'goods_id', title: '积分商品ID'},
                    {field: 'taolijin_goods_id', title: '淘礼金商品ID'},
                    {field: 'record_title', title: '记录标题'},
                    {field: 'integral_status', search: 'select', selectList: ["已消耗","申请中","已通过","已拒绝"], title: '积分状态'},
                    {field: 'user_id', title: '用户ID'},
                    {field: 'integral_value', title: '积分数值'},
                    {field: 'remark', title: '备注说明', templet: ea.table.text},
                    {field: 'create_time', title: '创建时间'},
                    {field: 'mallIntegralGoods.item_id', title: '商品ID'},
                    {field: 'mallIntegralGoods.title', title: '商品标题'},
                    {field: 'mallIntegralGoods.goods_image', title: '商品图片', templet: ea.table.image},
                    {field: 'systemWeixinUser.nickname', title: '用户昵称'},
                    {field: 'systemWeixinUser.headimgurl', title: '头像', templet: ea.table.image},
                    /*{
                        width: 250,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                            [{
                                text: '通过',
                                url: init.accept_url,
                                method: 'request',
                                auth: 'accept',
                                class: 'layui-btn layui-btn-xs layui-btn-normal',
                            },{
                                text: '拒绝',
                                url: init.reject_url,
                                method: 'request',
                                auth: 'reject',
                                class: 'layui-btn layui-btn-xs layui-btn-danger',
                            },{
                                text: '编辑',
                                url: init.edit_url,
                                method: 'open',
                                auth: 'edit',
                                class: 'layui-btn layui-btn-xs layui-btn-success',
                                extend: 'data-full="true"',
                            }],
                            'delete']
                    },*/
                    {width: 250, title: '操作', toolbar: '#controlPlan'}
                ]],
                done:function () {

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