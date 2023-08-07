define(["jquery", "easy-admin", "cryptojs"], function ($, ea, crypto) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        analysis_dou_command_url: 'mall.common_tools/analysisDouCommand',
        activity_transfer_url: 'mall.common_tools/activityTransfer',
        get_goods_history_url: 'mall.common_tools/getGoodsHistory',
        analysis_url: 'mall.common_tools/analysis',
        transfer_url: 'mall.common_tools/transfer',
        effective_url: 'mall.common_tools/effective',
        get_shop_url: 'mall.common_tools/getShopContactInfo',
        resolve_url: 'mall.common_tools/resolve',
        apt_test_url: 'mall.common_tools/apiTest',
        //get_order_url: 'mall.common_tools/getOrderList',
        get_order_url: 'mall.common_tools/getGoodsInfo',
        get_shop_list_url: 'mall.common_tools/getShopList',
    };

    var Controller = {
        index: function () {
            //抖音口令解析
            $("#dou_command_analysis").on('click', function () {
                let douCommand = $('#douCommand').val();
                if(douCommand === ''){
                    ea.msg.error('请输入抖口令！');
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: ea.url(init.analysis_dou_command_url),
                    data: {"douCommand": douCommand},
                    dataType: "json",
                    success: function (data) {
                        $("#dou_command_result").val(JSON.stringify(data.data.data));
                        ea.msg.success('解析成功！');
                    }
                });

            });


            //抖音活动转链
            $("#dou_activity_btn").on('click', function () {
                let material_id = $('#material_id').val();
                let dou_account_id = $("select[name='dou_account_id']").val();
                if(material_id === ''){
                    ea.msg.error('活动ID不能为空！');
                    return false;
                }
                if(dou_account_id === ''){
                    ea.msg.error('请选择转链账号！');
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: ea.url(init.activity_transfer_url),
                    data: {"material_id": material_id,"dou_account_id": dou_account_id},
                    dataType: "json",
                    success: function (transfer_data) {
                        //console.log(transfer_data);
                        let html = "商品口令："+ transfer_data.data.data.share_command +"\n" +
                            "商品短链接："+ transfer_data.data.data.z_link +"\n";
                        $("#dou_transfer_result").val(html);
                        ea.msg.success('转链成功！');
                    }
                });

            });

            //指定商品历史推广数据
            $("#search-history-btn").on('click', function () {
                var goods_option = $('#goods_option').val();
                var taobao_accountId = $("select[name='taobao_account_id']").val();
                if(goods_option === ''){
                    ea.msg.error('商品信息不能为空！');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: ea.url(init.get_goods_history_url),
                    data: {"goods_option": goods_option,"account_id": taobao_accountId},
                    dataType: "json",
                    success: function (goods_data) {
                        var html = "";
                        if(goods_data.hasOwnProperty("msg")){
                            ea.msg.error(goods_data.msg);
                            return false;
                        }
                        $.each(goods_data, function (i, goods_info) {
                            html += "商品ID："+i+"\r\n"+
                                "商品历史数据：日期 | 价格 | 券面额 | 佣金率 | 佣金 | 销量 | 推广销量 | 推广淘客数 \r\n";
                            $.each(goods_info, function(key, value){
                                html += value.date +" | "+value.price+" | "+value.couponAmount+" | "+value.commissionRate+" | "+value.commissionFee+" | "+value.sales+" | "+value.promotionSales+" | "+value.promotionTkCount+"\r\n";
                            });
                            html += "\r\n";
                        });
                        $("#goods-history-data").val(html);
                        ea.msg.success('查询成功！');
                    }
                });

            });


            $("#analysis-btn").on('click', function () {
                var inputContent = $('#old_content').val();
                if(inputContent == ''){
                    ea.msg.error('请输入待解析的内容！');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: ea.url(init.analysis_url),
                    data: {"content": inputContent},
                    dataType: "json",
                    success: function (data) {
                        $("#new_content").val(data['originUrl']);
                        ea.msg.success('解析成功！');
                    }
                });

            });

            $("#transfer-btn").on('click', function () {
                var newContent = $('#new_content').val();
                var accountId = $("select[name='account_id']").val();
                if(newContent == ''){
                    ea.msg.error('解析的内容不能为空！');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: ea.url(init.transfer_url),
                    data: {"link_url": newContent,"account_id": accountId},
                    dataType: "json",
                    success: function (short_secret) {
                        $("input[name='taokouling']").val(short_secret);
                        ea.msg.success('转换成功！');
                    }
                });

            });

            $("#quick-transfer-btn").on('click', function () {
                var inputContent = $('#old_content').val();
                if(inputContent == ''){
                    ea.msg.error('请输入待解析的内容！');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: ea.url(init.analysis_url),
                    data: {"content": inputContent},
                    dataType: "json",
                    success: function (data) {
                        $("#new_content").val(data['originUrl']);
                        var accountId = $("select[name='account_id']").val();
                        $.ajax({
                            type: "POST",
                            url: ea.url(init.transfer_url),
                            data: {"link_url": data['originUrl'],"account_id": accountId},
                            dataType: "json",
                            success: function (short_secret) {
                                $("input[name='taokouling']").val(short_secret);
                                ea.msg.success('转换成功！');
                            }
                        });
                    }
                });
            });

            $("#copy-btn").on('click', function () {
                var kouling = $("input[name='taokouling']").select();
                document.execCommand('copy', false, null);
                ea.msg.success('复制成功！');
            });

            //高效转链功能
            $("#effective-btn").on('click', function () {
                var goods_link = $('#goods_link').val();
                var efc_accountId = $("select[name='effective_account_id']").val();
                if(goods_link == ''){
                    ea.msg.error('商品链接不能为空！');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: ea.url(init.effective_url),
                    data: {"link_url": goods_link,"account_id": efc_accountId},
                    dataType: "json",
                    success: function (transfer_data) {
                        var html = "商品口令："+ transfer_data['tpwd'] +"\n" +
                        "商品短链接："+ transfer_data['shortUrl'] +"\n" +
                        "商品长链接："+ transfer_data['couponClickUrl'] +"\n";
                        $("#new_goods_link").val(html);
                        ea.msg.success('转链成功！');
                    }
                });

            });


            //测试
            $("#get-group-btn").on('click', function () {
                $.ajax({
                    type: "POST",
                    url: ea.url(init.apt_test_url),
                    data: {},
                    dataType: "json",
                    success: function (group_data) {
                        $("#group-list").val(group_data);
                        ea.msg.success('获取结束！');
                    }
                });

            });

            //解密功能
            $("#resolve-btn").on('click', function () {
                var source_string = $('#source_string').val();
                if(source_string == ''){
                    ea.msg.error('待解密信息不能为空！');
                    return false;
                }

                let ret_data = Controller.littleWork(source_string,function(sign, text){
                    $("#resolve_result").val(text);
                    ea.msg.success('解密成功！');
                });
                return true;

                //第一步 根据店铺ID获取加密信息
                $.ajax({
                    type: "POST",
                    url: ea.url(init.get_shop_url),
                    data: {"requestUrl":"http://node.yiquntui.com/api/contentData","shopid": source_string,"sellerid":source_string,"ver":"512"},
                    dataType: "json",
                    success: function (result_data) {
                        //$("#resolve_result").val(result_data.data);
                        //ea.msg.success('获取数据成功！');

                        //第二步 通过现有的解密方法对信息解密
                        let ret_data = Controller.littleWork(result_data.data,function(sign, text){
                            $("#resolve_result").val(text);
                            ea.msg.success('解密成功！');
                        });

                    }
                });


                //第三步 将解密的信息输出到页面显示出来

                /*$.ajax({
                    type: "POST",
                    url: ea.url(init.resolve_url),
                    data: {"source_string": source_string},
                    dataType: "json",
                    success: function (result_data) {
                        $("#resolve_result").val(result_data);
                        ea.msg.success('解密成功！');
                    }
                });*/

            });

            //获取商品数据功能
            $("#get-order-btn").on('click', function () {
                var order_parameter = $('#order_parameter').val();
                /*if(order_parameter == ''){
                    ea.msg.error('待解密信息不能为空！');
                    return false;
                }*/

                $.ajax({
                    type: "POST",
                    url: ea.url(init.get_order_url),
                    data: {"order_parameter": order_parameter},
                    dataType: "json",
                    success: function (result_data) {
                        console.log(result_data);
                        $("#order_result").val(result_data);
                        ea.msg.success('获取成功！');
                    }
                });

            });

            //根据类目获取店铺列表信息
            $("#category-btn").on('click', function () {
                var order_parameter = $('#shop_category').val();
                /*if(order_parameter == ''){
                    ea.msg.error('待解密信息不能为空！');
                    return false;
                }*/

                $.ajax({
                    type: "POST",
                    url: ea.url(init.get_shop_list_url),
                    data: {"order_parameter": order_parameter},
                    dataType: "json",
                    success: function (result_data) {
                        console.log(result_data);
                        $("#shop_result").val(JSON.stringify(result_data.results.n_tbk_shop));
                        ea.msg.success('获取成功！');
                    }
                });

            });

            ea.listen();
        },

        littleWork: function (msg, cb) {
            console.log('1111');
            let $this = Controller;
            let res = 'maybeitbetrue';
            if (res) {
                let sign = MD5(res).toUpperCase();
                let sign1 = sign.slice(0, 14) + new Date().format("hh"),
                    sign2 = sign.slice(17, 31) + new Date().format("mm");

                console.log(sign1);
                console.log(sign2);

                let str = $this.aesCrypt(msg, sign1, sign2).toString();
                console.log(str);
                let text = decodeURIComponent($this.theCode(str));
                cb (null, text);
            } else cb ('no Refer');
        },
        aesCrypt: function (data, key, iv) {
            data = (data + '').replace(/\n*$/g, '').replace(/\n/g, ''); //增加这一行，将换行符替换为空
            var decrypt = CryptoJS.AES.decrypt(data, CryptoJS.enc.Utf8.parse(key), {
                iv: CryptoJS.enc.Latin1.parse(iv),
                mode: CryptoJS.mode.CBC,
                padding: CryptoJS.pad.Pkcs7
            });
            return decrypt.toString(CryptoJS.enc.Utf8);
        },
        theCode: function (msg) {
            var key = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            var l = key.length;
            var b, b1, b2, b3, d = 0, s, str = '';
            for (var i = 0; i < msg.length; i++) {
                if (0 != (i + 1) % 4) {
                    str += msg.charAt(i);
                }
            }
            s = new Array(Math.floor(str.length / 3));
            b = s.length;
            for (var i = 0; i < b; i++) {
                b1 = key.indexOf(str.charAt(d));
                d ++;
                b2 = key.indexOf(str.charAt(d));
                d ++;
                b3 = key.indexOf(str.charAt(d));
                d ++;
                s[i] = b1 * l * l + b2 * l + b3
            }
            b = eval("String.fromCharCode(" + s.join(',') + ")");
            return b ;
        }

    };
    return Controller;
});

Date.prototype.format = function (fmt) {
    var o = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S": this.getMilliseconds()
    };
    if (/(y+)/.test(fmt)) {
        fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    }
    for (var k in o) {
        if (new RegExp("(" + k + ")").test(fmt)) {
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        }
    }
    return fmt;
};