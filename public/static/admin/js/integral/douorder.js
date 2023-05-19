define(["jquery", "easy-admin"], function ($, ea) {
    var form = layui.form,
        table = layui.table,
        flow = layui.flow,
        element = layui.element;
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: ea.url('integral.douorder/index'),
        reward_url: ea.url('integral.douorder/reward'),
    };
    var isShowReward = getUrlParam('showReward');
    var onlyReward = getUrlParam('onlyReward');

    if(!onlyReward){
        flow.load({
            elem: '#order_content' //流加载容器
            //,scrollElem: '#order_content' //滚动条所在元素，一般不用填，此处只是演示需要。
            ,done: function(page, next){ //执行下一页的回调
                let uId = getUrlParam('uId');
                let startDate = getUrlParam('startDate');
                let endDate = getUrlParam('endDate');
                ea.request.post({
                    url: init.index_url,
                    data: {"uId":uId,"page":page,"startDate":startDate,"endDate":endDate},
                    //prefix: false,
                }, function (res) {
                    //对页面元素赋值
                    $("#groupTitle").html(res.showData.groupTitle);
                    $("#totalNum").html(res.showData.count);
                    let lis = [];
                    $.each(res.data, function (key, val) {
                        lis.push('<div class="layui-row">\n' +
                            '\t\t\t\t<div class="layui-col-xs5 content-align-center">\n' +
                            '\t\t\t\t\t<span class="content-text-size">'+val.order_id+'</span>\n' +
                            '\t\t\t\t</div>\n' +
                            '\t\t\t\t<div class="layui-col-xs5 content-align-center">\n' +
                            '\t\t\t\t\t<span class="content-text-size">'+val.pay_success_time+'</span>\n' +
                            '\t\t\t\t</div>\n' +
                            '\t\t\t\t<div class="layui-col-xs2 content-align-center">\n' +
                            '\t\t\t\t\t<span class="content-text-size">'+val.order_status+'</span>\n' +
                            '\t\t\t\t</div>\n' +
                            '\t\t\t</div>')
                    });

                    //执行下一页渲染，第二参数为：满足“加载更多”的条件，即后面仍有分页
                    //pages为Ajax返回的总页数，只有当前页小于总页数的情况下，才会继续出现加载更多
                    next(lis.join(''), page < res.pageNum); //假设总页数为 10
                }, function(res){
                    //对页面元素赋值
                    $("#groupTitle").html(res.showData.groupTitle);
                    $("#totalNum").html(res.showData.count);
                    let lis = [];
                    $.each(res.data, function (key, val) {
                        lis.push('<div class="layui-row">\n' +
                            '\t\t\t\t<div class="layui-col-xs5 content-align-center">\n' +
                            '\t\t\t\t\t<span class="content-text-size">'+val.order_id+'</span>\n' +
                            '\t\t\t\t</div>\n' +
                            '\t\t\t\t<div class="layui-col-xs5 content-align-center">\n' +
                            '\t\t\t\t\t<span class="content-text-size">'+val.pay_success_time+'</span>\n' +
                            '\t\t\t\t</div>\n' +
                            '\t\t\t\t<div class="layui-col-xs2 content-align-center">\n' +
                            '\t\t\t\t\t<span class="content-text-size">'+val.order_status+'</span>\n' +
                            '\t\t\t\t</div>\n' +
                            '\t\t\t</div>')
                    });

                    //执行下一页渲染，第二参数为：满足“加载更多”的条件，即后面仍有分页
                    //pages为Ajax返回的总页数，只有当前页小于总页数的情况下，才会继续出现加载更多
                    next(lis.join(''), page < res.pageNum); //假设总页数为 10
                });
            }
        });
    }


    function getUrlParam(name)
    {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = window.location.search.substr(1).match(reg); //匹配目标参数
        if (r!=null) return unescape(r[2]); return null; //返回参数值
    }


    $("#totalNum").show();
    $("#totalRewardNum").hide();

    element.on('tab(dou-order)', function(data){
        if(data.index === 0){
            $("#totalNum").show();
            $("#totalRewardNum").hide();
        }else if(data.index === 1){
            $("#totalNum").hide();
            $("#totalRewardNum").show();
        }
    });

    if(isShowReward || onlyReward){
        if(!onlyReward){
            $("#order-tab-title").removeClass("tab-title-show");
        }else{
            $(".layui-tab-content .layui-tab-item:nth-child(1)").removeClass("layui-show");
            $(".layui-tab-content .layui-tab-item:nth-child(2)").addClass("layui-show");
            $("#totalNum").hide();
            $("#totalRewardNum").show();
        }
        flow.load({
            elem: '#reward_content' //流加载容器
            //,scrollElem: '#order_content' //滚动条所在元素，一般不用填，此处只是演示需要。
            ,done: function(page, next){ //执行下一页的回调
                let uId = getUrlParam('uId');
                let startDate = getUrlParam('startDate');
                let endDate = getUrlParam('endDate');
                ea.request.post({
                    url: init.reward_url,
                    data: {"uId":uId,"page":page,"startDate":startDate,"endDate":endDate},
                    //prefix: false,
                }, function (res) {
                    console.log(res);
                    //对页面元素赋值
                    $("#groupTitle").html(res.showData.groupTitle);
                    $("#totalNum").html(res.count);
                    let lis = [];
                    $.each(res.data, function (key, val) {
                        lis.push('<div class="layui-row">\n' +
                            '\t\t\t\t<div class="layui-col-xs5 content-align-center">\n' +
                            '\t\t\t\t\t<span class="content-text-size">'+val.reward_order_id+'</span>\n' +
                            '\t\t\t\t</div>\n' +
                            '\t\t\t\t<div class="layui-col-xs5 content-align-center">\n' +
                            '\t\t\t\t\t<span class="content-text-size">'+val.pay_date+'</span>\n' +
                            '\t\t\t\t</div>\n' +
                            '\t\t\t\t<div class="layui-col-xs2 content-align-center">\n' +
                            '\t\t\t\t\t<span class="content-text-size">'+val.reward_type+'</span>\n' +
                            '\t\t\t\t</div>\n' +
                            '\t\t\t</div>')
                    });
                    next(lis.join(''), page < res.pageNum); //假设总页数为 10
                }, function(res){
                    console.log(1111);
                    console.log(res);
                    //对页面元素赋值
                    $("#groupTitle").html(res.showData.groupTitle);
                    $("#totalRewardNum").html(res.count);
                    let lis = [];
                    $.each(res.data, function (key, val) {
                        lis.push('<div class="layui-row">\n' +
                            '\t\t\t\t<div class="layui-col-xs5 content-align-center">\n' +
                            '\t\t\t\t\t<span class="content-text-size">'+val.reward_order_id+'</span>\n' +
                            '\t\t\t\t</div>\n' +
                            '\t\t\t\t<div class="layui-col-xs5 content-align-center">\n' +
                            '\t\t\t\t\t<span class="content-text-size">'+val.pay_date+'</span>\n' +
                            '\t\t\t\t</div>\n' +
                            '\t\t\t\t<div class="layui-col-xs2 content-align-center">\n' +
                            '\t\t\t\t\t<span class="content-text-size">'+val.reward_type+'</span>\n' +
                            '\t\t\t\t</div>\n' +
                            '\t\t\t</div>')
                    });
                    next(lis.join(''), page < res.pageNum); //假设总页数为 10
                });
            }
        });
    }

    /*$('.layui-tab-title').on('click', function(title) {
        if(title.toElement.textContent === "近期订单"){
            $("#totalNum").show();
            $("#totalRewardNum").hide();
        }else if(title.toElement.textContent === "奖励订单"){
            $("#totalNum").hide();
            $("#totalRewardNum").show();
        }
    });*/

    var Controller = {
        index: function () {
            ea.listen();
        }
    };
    return Controller;
});