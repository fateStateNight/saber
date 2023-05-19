define(["jquery", "easy-admin", "echarts"], function ($, ea, echarts) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        apt_test_url: 'mall.common_tools/apiTest',
    };

    var Controller = {

        index: function () {

            let everyday_order_num = echarts.init(document.getElementById('everyday_order_num'));
            let optionRecords = {
                title: {
                    text: '每日订单数量统计'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['牛奶', '面包', '蛋糕', '小鱼干', '饮料']
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
                    data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
                },
                yAxis: {
                    type: 'value'
                },
                series: [
                    {
                        name: '牛奶',
                        type: 'line',
                        stack: '总量',
                        data: [120, 132, 101, 134, 90, 230, 210]
                    },
                    {
                        name: '面包',
                        type: 'line',
                        stack: '总量',
                        data: [220, 182, 191, 234, 290, 330, 310]
                    },
                    {
                        name: '蛋糕',
                        type: 'line',
                        stack: '总量',
                        data: [150, 232, 201, 154, 190, 330, 410]
                    },
                    {
                        name: '小鱼干',
                        type: 'line',
                        stack: '总量',
                        data: [320, 332, 301, 334, 390, 330, 320]
                    },
                    {
                        name: '饮料',
                        type: 'line',
                        stack: '总量',
                        data: [820, 932, 901, 934, 1290, 1330, 1320]
                    }
                ]
            };
            everyday_order_num.setOption(optionRecords);
            window.addEventListener("resize", function () {
                everyday_order_num.resize();
            });
            //获取招商效果数据
            $("#copy-btn").on('click', function () {
                var kouling = $("input[name='taokouling']").select();
                document.execCommand('copy', false, null);
                ea.msg.success('复制成功！');
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