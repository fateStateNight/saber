define(["jquery", "easy-admin"], function ($, ea) {

    var form = layui.form,
        laydate = layui.laydate;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'mall.taolijin_goods/index',
        add_url: 'mall.taolijin_goods/add',
        edit_url: 'mall.taolijin_goods/edit',
        delete_url: 'mall.taolijin_goods/delete',
        export_url: 'mall.taolijin_goods/export',
        modify_url: 'mall.taolijin_goods/modify',
        goods_url: 'mall.taolijin_goods/getGoodsInfo',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                id:'id',
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'ID', hide:true},
                    {field: 'item_id', title: '商品ID', hide:false},
                    {field: 'title', title: '商品名称', hide:false},
                    {field: 'image', title: '商品logo', templet: ea.table.image, hide:false},
                    {field: 'account_id', title: '淘宝联盟账号ID', hide:true},
                    {field: 'total_num', title: '礼金个数', hide:true},
                    {field: 'sales', title: '销量', hide:true},
                    {field: 'per_user_num', title: '每个用户领取个数', hide:true},
                    {field: 'per_face', title: '每个礼金面额', hide:true},
                    {field: 'send_start_time', title: '领取开始时间', hide:true},
                    {field: 'send_end_time', title: '领取结束时间', hide:true},
                    {field: 'use_start_time', title: '使用开始时间', hide:true},
                    {field: 'use_end_time', title: '使用结束时间', hide:true},
                    {field: 'campaign_type', search: 'select', selectList: {"MKT":"营销","DX":"定向","LINK_EVENT":"鹊桥"}, title: '佣金计划类型', hide:true},
                    {field: 'market_price', title: '市场价', hide:true},
                    {field: 'discount_price', title: '折扣价', hide:true},
                    {field: 'goods_link', title: '商品口令'},
                    {field: 'goods_content', title: '商品内容', hide:true},
                    {field: 'item_status', search: 'select', selectList: {"1":"出售中","2":"已售完","0":"未出售"}, title: '商品状态'},
                    {field: 'mode', search: 'select', selectList: {"1":"正常商品","2":"秒杀商品","3":"免单商品"}, title: '商品类型'},
                    {field: 'discript', title: '商品描述', hide:true},
                    {field: 'status', search: 'select', selectList: ["禁用","启用"], title: '状态', templet: ea.table.switch},
                    {field: 'remark', title: '备注说明', templet: ea.table.text, hide:true},
                    {field: 'create_time', title: '创建时间'},
                    {field: 'systemTaobaoAccount.id', title: '', hide:true},
                    {field: 'systemTaobaoAccount.name', title: '账户昵称'},
                    {field: 'systemTaobaoAccount.account_id', title: '账户ID', hide:true},
                    {field: 'systemTaobaoAccount.appkey', title: '账号', hide:true},
                    {field: 'systemTaobaoAccount.appsecret', title: '账号密钥', hide:true},
                    {field: 'systemTaobaoAccount.spread_id', title: '推广位ID', hide:true},
                    {field: 'systemTaobaoAccount.status', title: '状态', templet: ea.table.switch, hide:true},
                    {field: 'systemTaobaoAccount.remark', title: '备注说明', templet: ea.table.text, hide:true},
                    {field: 'systemTaobaoAccount.create_time', title: '创建时间', hide:true},
                    {width: 250, title: '操作', templet: ea.table.tool},
                ]],
                /*done: function(res, curr, count){
                    $("[data-field='id']").addClass('layui-hide');
                    $("input[name='id']").next().removeClass('layui-form-checked');
                    $("[data-field='item_id']").addClass('layui-hide');
                    $("input[name='item_id']").next().removeClass('layui-form-checked');
                    $("[data-field='title']").addClass('layui-hide');
                    $("input[name='title']").next().removeClass('layui-form-checked');
                    $("[data-field='image']").addClass('layui-hide');
                    $("input[name='image']").next().removeClass('layui-form-checked');
                }*/
            });

            ea.listen();
        },
        add: function () {
            form.verify({
                item_id: function(value){
                    if(value.length < 5){
                        return '商品ID不能为空';
                    }
                },
                title: function(value){
                    if(value.length < 4){
                        return '请输入至少4位的标题';
                    }
                }
            });

            laydate.render({
                elem: '#send_start_time'
                , type: 'datetime'
                , value: getRecentDay(0),//获取前三天时间
            });
            laydate.render({
                elem: '#send_end_time'
                , type: 'datetime'
                , value: getCurrentDay(0),
            });
            laydate.render({
                elem: '#use_start_time'
                , type: 'datetime'
                , value: getRecentDay(0),//获取前三天时间
            });
            laydate.render({
                elem: '#use_end_time'
                , type: 'datetime'
                , value: getCurrentDay(0),
            });

            function getRecentDay(day) {
                var today = new Date();
                var targetday_milliseconds = today.getTime() + 1000 * 60 * 60 * 24 * day;
                today.setTime(targetday_milliseconds);
                var tYear = today.getFullYear();
                var tMonth = today.getMonth();
                var tDate = today.getDate();
                var tHours = today.getHours();
                var tMinutes = today.getMinutes();
                var tSeconds = today.getSeconds();
                tMonth = doHandleMonth(tMonth + 1);
                tDate = doHandleMonth(tDate);
                tHours = doHandleMonth(tHours);
                tMinutes = doHandleMonth(tMinutes);
                tSeconds = doHandleMonth(tSeconds);

                return tYear + "-" + tMonth + "-" + tDate + ' ' + tHours + ':' + tMinutes + ':' + tSeconds;
            }

            function getCurrentDay(day){
                var today = new Date();
                var targetday_milliseconds = today.getTime() + 1000 * 60 * 60 * 24 * day;
                today.setTime(targetday_milliseconds);
                var tYear = today.getFullYear();
                var tMonth = today.getMonth();
                var tDate = today.getDate();
                var tHours = today.getHours();
                var tMinutes = today.getMinutes();
                var tSeconds = today.getSeconds();
                tMonth = doHandleMonth(tMonth + 1);
                tDate = doHandleMonth(tDate);
                tHours = doHandleMonth(tHours);
                tMinutes = doHandleMonth(tMinutes);
                tSeconds = doHandleMonth(tSeconds);

                return tYear + "-" + tMonth + "-" + tDate + ' 23:59:59';
            }

            function doHandleMonth(month) {
                var m = month;
                if (month.toString().length == 1) {
                    m = "0" + month;
                }
                return m;
            }


            //商品ID输入框失去焦点后自动获取商品信息
            $("input[name='item_id']").blur(function(){
                var node = this;
                var item_id = this.value;
                if (item_id == ''){
                    return false;
                }
                $.ajax({
                    type: "GET",
                    url: ea.url(init.goods_url),
                    data: {"item_id":item_id},
                    dataType: "json",
                    success: function(data){
                        //展示商品数据
                        var yunfeixian = '包运费险';
                        var remoteDistrict = '偏远地区包邮';
                        if (data.data.yunfeixian !== 1){
                            yunfeixian = '不包运费险';
                        }
                        if (data.data.freeshipRemoteDistrict !== 1){
                            remoteDistrict = '偏远地区不包邮';
                        }
                        var couponCondition = '';
                        if (data.data.couponConditions !== 0 && data.data.couponPrice !== 0){
                            couponCondition = '满'+data.data.couponConditions+'元减'+data.data.couponPrice+'元'
                        }else{
                            couponCondition = data.data.specialText[0];
                        }
                        var goods_html =
                            '<div class="layui-input-block show-goods" data-id="1" style="height: auto;">' +
                            '<img src="'+data.data.mainPic+'" alt="" class="goods_fl">' +
                            '<div class="content">' +
                            '<p class="tit">'+data.data.dtitle+'</p>' +
                            '<ul>' +
                            '<li class="old_price">券后价<span>￥ <i style="font-style: normal">'+data.data.actualPrice+'</i></span></li>' +
                            '<li>优惠券 <span>'+data.data.couponPrice+'</span></li>' +
                            '<li>优惠券剩余量 <span>'+(data.data.couponTotalNum-data.data.couponReceiveNum)+'</span></li>' +
                            '<li>佣金 <span>'+data.data.commissionRate+'%</span></li>' +
                            '<li class="pre_amount">预估佣金 <span>'+(data.data.actualPrice*data.data.commissionRate*0.01)+'</span></li>' +
                            '</ul>' +
                            '<p class="explain">'+couponCondition+'</p>' +
                            '<p class="explain">'+yunfeixian+'</p>' +
                            '<p class="explain">'+remoteDistrict+'</p>' +
                            '</div>' +
                            '</div>';
                        $("#show_goods").empty();
                        $("#show_goods").append(goods_html);

                        //获取商品信息后自动填充表单
                        $("input[name='item_id']").val(data.item_id);
                        $("input[name='title']").val(data.data.dtitle);
                        $("input[name='image']").val(data.data.mainPic);
                        //显示的是券后价
                        $("input[name='market_price']").val(data.data.actualPrice);
                        //$("input[name='discount_price']").val(data.data.mainPic);
                        //console.log(data);
                        return data;
                    }
                });
            });

            //礼金面额输入框失去焦点后触发
            $("input[name='per_face']").blur(function(){
                var per_face = this.value;
                var old_price = $("input[name='market_price']").val();
                console.log(old_price);
                console.log(per_face);
                var discount_price = 0;
                if(old_price !== 0){
                    discount_price = parseFloat(old_price) - parseFloat(per_face);
                    $("input[name='discount_price']").val(discount_price);
                }
            });


            ea.listen('noCallBack',function(res){
                if(res.code == 1){
                    ea.msg.success(res.msg)
                }else{
                    ea.msg.error(res.msg);
                }
                setTimeout(function(){
                    let index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                    parent.location.reload();
                },1000);
                //parent.location.reload();
            });
        },
        edit: function () {
            ea.listen();
        },
    };
    return Controller;
});

