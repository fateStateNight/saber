define(["jquery", "easy-admin", "echarts"], function ($, ea, echarts) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        export_url: ea.url('business.goods_effect/export'),
    };

    var goodsId = getUrlParam('id');

    function getUrlParam(name)
    {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = window.location.search.substr(1).match(reg); //匹配目标参数
        if (r!=null) return unescape(r[2]); return null; //返回参数值
    }

    var Controller = {

        index: function () {
            let goods_type = ['引流UV','成交淘客数','付款金额','付款笔数','结算金额','结算笔数','预估付款服务费','预估结算服务费','预估付款佣金','预估结算佣金'];
            let xAxis_date = [];
            let series_data = [
                {name: '引流UV',type: 'line',stack: '总量',data: []},
                {name: '成交淘客数',type: 'line',stack: '总量',data: []},
                {name: '付款金额',type: 'line',stack: '总量',data: []},
                {name: '付款笔数',type: 'line',stack: '总量',data: []},
                {name: '结算金额',type: 'line',stack: '总量',data: []},
                {name: '结算笔数',type: 'line',stack: '总量',data: []},
                {name: '预估付款服务费',type: 'line',stack: '总量',data: []},
                {name: '预估结算服务费',type: 'line',stack: '总量',data: []},
                {name: '预估付款佣金',type: 'line',stack: '总量',data: []},
                {name: '预估结算佣金',type: 'line',stack: '总量',data: []}
            ];
            if(effectData != null){
                $.each($.parseJSON(effectData), function(num,goodsData){
                    xAxis_date.push(goodsData['publish_date']);
                    series_data[0].data.push(goodsData['enterShopUvTk']);
                    series_data[1].data.push(goodsData['paymentTkNum']);
                    series_data[2].data.push(goodsData['alipayAmt']);
                    series_data[3].data.push(goodsData['alipayNum']);
                    series_data[4].data.push(goodsData['cpsSettleAmt']);
                    series_data[5].data.push(goodsData['cpsSettleNum']);
                    series_data[6].data.push(goodsData['cpPreServiceShareFee']);
                    series_data[7].data.push(goodsData['cpCmServiceShareFee']);
                    series_data[8].data.push(goodsData['preCommissionFee']);
                    series_data[9].data.push(goodsData['cmCommissionFee']);

                });
            }
            let goods_publish_effect = echarts.init(document.getElementById('everyday_goods_publish_effect'));
            let optionRecords = {
                title: {
                    text: '每日推广效果'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: goods_type,
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                toolbox: {
                    feature: {
                        saveAsImage: {},
                        myTools: {
                            show: true,
                            title: '导出为excel',
                            icon: 'path://M6.75 19.25H17.25C18.3546 19.25 19.25 18.3546 19.25 17.25V9.82843C19.25 9.29799 19.0393 8.78929 18.6642 8.41421L15.5858 5.33579C15.2107 4.96071 14.702 4.75 14.1716 4.75H6.75C5.64543 4.75 4.75 5.64543 4.75 6.75V17.25C4.75 18.3546 5.64543 19.25 6.75 19.25Z',
                            color:'red',
                            onclick: function () {
                                ea.request.post({
                                    url: init.export_url,
                                    data: {"Id":goodsId},
                                    dataType: "json"
                                }, function (res) {
                                    ea.msg.success(res.msg, function () {
                                        let url = res.url;
                                        let xhr = new XMLHttpRequest();
                                        xhr.open('get', url, true);
                                        xhr.responseType = "blob";
                                        xhr.onload = function () {
                                            if (this.status === 200) {
                                                var blob = this.response;
                                                var href = window.URL.createObjectURL(blob);  // 创建下载链接
                                                // 判断是否是IE浏览器，是则返回true
                                                if (window.navigator.msSaveBlob) {
                                                    try {
                                                        window.navigator.msSaveBlob(blob, '商品推广效果表.xlsx')
                                                    } catch (e) {
                                                        console.log(e);
                                                    }
                                                } else {
                                                    // 非IE浏览器，创建a标签，添加download属性下载
                                                    let a = document.createElement('a');  // 创建下载链接
                                                    a.href = href;
                                                    a.target = '_blank';  // 新开页下载
                                                    a.download = '商品推广效果表.xlsx'; // 下载文件名
                                                    document.body.appendChild(a);  // 添加dom元素
                                                    a.click();  //  点击下载
                                                    document.body.removeChild(a); // 下载后移除元素
                                                    window.URL.revokeObjectURL(href); // 下载后释放blob对象
                                                }
                                            }
                                        }
                                        xhr.send();
                                    });
                                }, function(){
                                    //返回失败
                                    ea.msg.error('导出失败！');
                                });
                            },
                        }
                    }
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: xAxis_date,
                },
                yAxis: {
                    type: 'value'
                },
                series: series_data
            };
            goods_publish_effect.setOption(optionRecords);
            window.addEventListener("resize", function () {
                goods_publish_effect.resize();
            });


            //处理鼠标悬停提示
            $('*[lay-tips]').on('mouseenter', function(){
                var content = $(this).attr('lay-tips');
                this.index = layer.tips('<div style="padding: 10px; font-size: 14px; color: #eee;">'+ content + '</div>', this, {
                    time: -1
                    ,maxWidth: 280
                    ,tips: [3, '#3A3D49']
                });
            }).on('mouseleave', function(){
                layer.close(this.index);
            });

            ea.listen();
        },

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