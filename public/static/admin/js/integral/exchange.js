define(["jquery", "easy-admin"], function ($, ea) {

    var form = layui.form;
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        create_award_url: ea.url('integral.exchange/createAward'),
        draw_url: ea.url('integral.draw/index'),
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
            $(".showTime").each(function(){
                var endTime = new Date($(this).attr('data-time-value'));
                var nowTime = new Date();//当前时间
                var diffTimeValue = endTime.getTime()-nowTime.getTime();
                var eleId = $(this).attr('id');
                TimeDown(eleId,diffTimeValue);
            });
            //商品有效期倒计时
            function TimeDown(id, value) {
                //倒计时的总秒数
                var totalSeconds = parseInt(value / 1000);
                //天数
                var days = Math.floor(totalSeconds / (60 * 60 * 24));
                //取模（余数）
                var modulo = totalSeconds % (60 * 60 * 24);
                //小时数
                var hours = Math.floor(modulo / (60 * 60));
                modulo = modulo % (60 * 60);
                //分钟
                var minutes = Math.floor(modulo / 60);
                //秒
                var seconds = modulo % 60;

                hours = hours.toString().length == 1 ? '0' + hours : hours;
                minutes = minutes.toString().length == 1 ? '0' + minutes : minutes;
                seconds = seconds.toString().length == 1 ? '0' + seconds : seconds;

                //输出到页面
                //document.getElementById(id).innerHTML = hours + ":" + minutes + ":" + seconds;
                $('#'+id+' em').html(days + '天' + hours + ":" + minutes + ":" + seconds);
                //延迟一秒执行自己
                if(hours == "00" && minutes == "00" && parseInt(seconds)-1<0){

                }else{
                    setTimeout(function () {
                        TimeDown(id, value-1000);
                    }, 1000)
                }

            }
            //兑换操作
            $('.exchange').on('click', function () {
                var item_id = $(this).attr('data-item-id');
                var goods_price = $(this).attr('data-goods-price');
                var goods_integral = $(this).attr('data-goods-integral');
                var goods_id = $(this).attr('data-id');
                var goods_title = $(this).prevAll("h3").text();
                var goods_image = $(this).parent().prev().children().attr('src');
                var user_id = $("#user_id").text();
                var html = '<div style="padding:15px 20px; text-align:justify; line-height: 22px;border-bottom:1px solid #e2e2e2;background-color: #2f4056;color: #ffffff">\n' +
                    '<div style="text-align: center;margin-bottom: 20px;font-weight: bold;border-bottom:1px solid #718fb5;padding-bottom: 5px"><h4 class="text-danger">确认兑换吗？</h4></div>\n' +
                    '</div>\n';
                layer.open({
                    type: 1,
                    title: '兑换礼品',
                    area: '60%;',
                    shade: 0.8,
                    id: 'exchange-notice',
                    btn: ['确定', '取消'],
                    btnAlign: 'c',
                    moveType: 1,
                    content: html,
                    success: function (layero) {
                        var btn = layero.find('.layui-layer-btn');
                        btn.find('.layui-layer-btn0').on('click',function () {
                            ea.request.post({
                                url: init.create_award_url,
                                data: {
                                    'item_id': item_id,
                                    'title': goods_title,
                                    'goods_price': goods_price,
                                    'goods_integral': goods_integral,
                                    'image': goods_image,
                                    'user_id':user_id,
                                    'goods_id':goods_id
                                },
                                //prefix: false,
                            }, function (res) {
                                ea.msg.success(res.msg, function () {
                                    window.location.href = init.draw_url+"?shortLink="+res.data.shortLink;
                                })
                            }, function(res){
                                ea.msg.error(res.msg);
                            });
                        });

                        /*btn.find('.layui-layer-btn0').attr({
                            href: 'https://gitee.com/zhongshaofa/layuimini',
                            target: '_blank'
                        });*/
                    }
                });
            });
            /*$(".exchange").click(function(){
                var item_id = $(this).attr('data-item-id');
                var goods_price = $(this).attr('data-goods-price');
                var goods_integral = $(this).attr('data-goods-integral');
                var goods_id = $(this).attr('data-id');
                var goods_title = $(this).prevAll("h3").text();
                var goods_image = $(this).parent().prev().children().attr('src');
                var user_id = $("#user_id").text();
                console.log(item_id);
                console.log(goods_price);
                console.log(goods_integral);
                console.log(goods_title);
                console.log(goods_image);
                console.log(user_id);
                layer.confirm('确认兑换吗？', {
                        btn: ['确认', '取消'],
                        title: '兑换礼品'
                    },
                    function () {
                        //alert('确认');
                        //生成淘礼金口令
                        $.ajax({
                            type: 'POST',
                            url: ea.url(init.create_award_url),
                            async: false,
                            data: {
                                'item_id': item_id,
                                'title': goods_title,
                                'goods_price': goods_price,
                                'goods_integral': goods_integral,
                                'image': goods_image,
                                'user_id':user_id,
                                'goods_id':goods_id
                            },
                            success: function (data) {
                                alert(data);return false;
                                //console.log(data);return false;
                                if (data.result == 'success') {
                                    //跳转到复制口令页面
                                    window.location.href = ea.url(init.draw_url)+"?shortLink="+data.data.shortLink;
                                    // layer.msg('保存成功',{time: 1000, icon:6,end:function () {
                                    //         location.reload();
                                    //     }})

                                }else {
                                    layer.msg('保存失败',{time: 2000, icon:5})
                                }
                            }
                        });
                        //新增积分记录扣除积分
                    }
                    , function (index) {
                        //alert('取消');
                        layer.close(index);
                        return false;
                    });
            });*/

            ea.listen();
        }
    };
    return Controller;
});