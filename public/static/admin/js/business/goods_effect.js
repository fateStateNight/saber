define(["jquery", "easy-admin", "echarts"], function ($, ea, echarts) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        apt_test_url: 'mall.common_tools/apiTest',
    };


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
                        saveAsImage: {}
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