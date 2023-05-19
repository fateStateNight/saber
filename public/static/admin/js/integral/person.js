define(["jquery", "easy-admin"], function ($, ea) {

    var form = layui.form;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'mall.integral_record/index',
        apply_url: ea.url('integral.person/add'),
        //goods_url: 'mall.taolijin_goods/getGoodsInfo',
    };

    var Controller = {
        index: function () {

            /*var app = new Vue({
                el: '#app',
                data: {
                    upload_type: upload_type
                }
            });

            form.on("radio(upload_type)", function (data) {
                app.upload_type = this.value;
            });*/
            $('title').html('积分中心');
            $('.apply-integral').on('click', function () {
                var goodsData = $.parseJSON($("#goodsData").html());
                var user_id = $(this).attr('data-user-id');
                var option_html = '';
                $.each(goodsData,function(goods_id,goodsInfo){
                    option_html += '<option value="'+goods_id+'">'+goodsInfo.title+'</option>\n';
                });

                var html = '<div style="text-align:justify; line-height: 22px;border-bottom:1px solid #e2e2e2;background-color: #fbfbfb;color: #666">\n' +
                    // '<form id="integral-form" class="layui-form layuimini-form">\n' +
                    //     '<div class="layui-form-item">\n' +
                            '<label style="margin:10px 10px;">已购买商品</label>\n' +
                            '<div style="padding-right:10px;">\n' +
                                '<select name="goods_id" id="goodsSelect" lay-filter="goodsSelect" style="width:95%;margin-left:10px;border-width: 1px;border-style: solid;background-color: #fff;border-radius: 2px;padding-left: 10px;height: 24px;" >\n' +
                                    '<option value=""></option>\n' +
                                    option_html+
                                '</select>\n' +
                            '</div>\n' +
                        // '</div>\n' +
                        // '<div class="layui-form-item layui-form-text">\n' +
                            '<label style="margin:10px 10px;">备注说明</label>\n' +
                            '<div>\n' +
                                '<textarea name="remark" id="remark" class="layui-textarea" style="width:92%;margin:10px;"  placeholder="请输入备注说明"></textarea>\n' +
                            '</div>\n' +
                        // '</div>\n' +
                        // '<div class="hr-line"></div>\n' +
                    // '</form>\n' +
                    '</div>\n';
                layer.open({
                    type: 1,
                    title: '申请积分',
                    area: ['100%','60%'],
                    shade: 0.8,
                    id: 'apply-integral',
                    btn: ['申请'],
                    btnAlign: 'c',
                    moveType: 1,
                    content: html,
                    success: function (layero) {
                        var btn = layero.find('.layui-layer-btn');
                        btn.find('.layui-layer-btn0').on('click',function () {
                            var goods_id = $("select[name='goods_id']").val();
                            if (goods_id <= 0){
                                ea.msg.error('请选择购买商品！');
                                return false;
                            }
                            var record_title = '购买'+goodsData[goods_id]['title']+'商品';
                            var integral_value = goodsData[goods_id]['goods_integral'];
                            var remark = $("#remark").val();
                            ea.request.post({
                                url: init.apply_url,
                                data: {
                                    'goods_id': goods_id,
                                    'record_title': record_title,
                                    'integral_status': 1,
                                    'integral_value': integral_value,
                                    'user_id':user_id,
                                    'remark':remark
                                },
                            }, function (res) {
                                ea.msg.success(res.msg, function () {
                                    location.reload();
                                })
                            }, function(res){
                                ea.msg.error(res.msg);
                            });
                        });
                    }
                });
            });

            ea.listen();
        },

        /*add: function () {
            //处理商品选项事件
            var goodsData = $.parseJSON($("#goodsData").html());
            form.on('select(goodsSelect)', function(data){
                var currentGoodsId = data.value;
                var record_title = '';
                var integral_value = '0';
                if(currentGoodsId > 0){
                    record_title = '购买'+goodsData[currentGoodsId]['title']+'商品';
                    integral_value = goodsData[currentGoodsId]['goods_integral'];
                }
                $("input[name='record_title']").val(record_title);
                $("input[name='integral_value']").val(integral_value);
            });
            ea.listen();
        }*/

    };
    return Controller;
});