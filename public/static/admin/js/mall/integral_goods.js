define(["jquery", "easy-admin"], function ($, ea) {
    var form = layui.form;
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'mall.integral_goods/index',
        add_url: 'mall.integral_goods/add',
        edit_url: 'mall.integral_goods/edit',
        delete_url: 'mall.integral_goods/delete',
        export_url: 'mall.integral_goods/export',
        modify_url: 'mall.integral_goods/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id'},
                    {field: 'item_id', title: '商品ID'},
                    {field: 'title', title: '商品标题'},
                    {field: 'goods_image', title: '商品图片', templet: ea.table.image},
                    {field: 'goods_type', search: 'select', selectList: ["出售商品","兑换商品"], title: '商品类型'},
                    {field: 'goods_price', title: '商品金额'},
                    {field: 'goods_integral', title: '商品积分'},
                    {field: 'begin_time', title: '商品开始时间'},
                    {field: 'end_time', title: '商品结束时间'},
                    {field: 'remark', title: '备注说明', templet: ea.table.text},
                    {field: 'create_time', title: '创建时间'},
                    {width: 250, title: '操作', templet: ea.table.tool},
                ]],
            });

            ea.listen();
        },
        add: function () {
            form.on('radio(goods_type)', function(data){
                //console.log(data.elem); //得到radio原始DOM对象
                //console.log(data.value); //被点击的radio的value值
                if(data.value == '1'){
                    $(".goods_price_select").removeClass('form-hidden');
                }else{
                    $("input[name='goods_price']").val(0);
                    $(".goods_price_select").addClass('form-hidden');
                }
            });
            ea.listen();
        },
        edit: function () {
            form.on('radio(goods_type)', function(data){
                //console.log(data.elem); //得到radio原始DOM对象
                //console.log(data.value); //被点击的radio的value值
                if(data.value == '1'){
                    $(".goods_price_select").removeClass('form-hidden');
                }else{
                    $("input[name='goods_price']").val(0);
                    $(".goods_price_select").addClass('form-hidden');
                }
            });
            ea.listen();
        },
    };
    return Controller;
});