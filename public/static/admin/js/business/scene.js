define(["jquery", "easy-admin", "miniTab"], function ($, ea, miniTab) {

    var table = layui.table;
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'business.scene/index',
        add_url: 'business.scene/add',
        edit_url: 'business.scene/edit',
        delete_url: 'business.scene/delete',
        export_url: 'business.scene/export',
        modify_url: 'business.scene/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                defaultToolbar: ['filter'],
                cols: [[
                    //{type: 'checkbox'},
                    {field: 'id', title: 'id', hide:true, search:false},
                    {field: 'eventId', title: '活动ID', hide:true},
                    {field: 'accountId', title: '团长ID', hide:true, search:false},
                    {field: 'sceneId', title: '活动类型', hide:true, search:'select',selectList:{"6":"普通活动招商","8":"内容招商","10":"天猫超市","11":"私域活动招商","13":"天猫国际","14":"阿里健康","16":"前N件高佣招商","27":"超级U选"}},
                    {field: 'sceneName', title: '活动类型名称', search:false},
                    {field: 'title', title: '活动标题', fieldAlias:'business_scene.title'},
                    {field: 'auditPassed', title: '审核通过', hide:true, search:false},
                    {field: 'auditTotal', title: '已报名', search:false},
                    {field: 'auditWait', title: '待审核', search:false},
                    {field: 'itemNum', title: '报名商品', hide:true, search:false},
                    {field: 'status', search: 'select', fieldAlias:'business_scene.status', selectList: {"0":"所有状态","1":"草稿","2":"系统校验中","3":"报名未开始","4":"报名中","5":"报名截止","7":"推广中","8":"结束","9":"已失效"}, title: '活动状态',},
                    //{field: 'dingTalkName', title: '钉钉名称'},
                    {field: 'cmAlipayAmt', title: '总付款金额', hide:true, search:false},
                    {field: 'commissionRate', title: '佣金率', templet: ea.table.percent, search:false},
                    {field: 'serviceRate', title: '服务费', templet: ea.table.percent, search:false},
                    {field: 'clickUv', title: '引流UV', hide:true, search:false},
                    {field: 'alipayAmt', title: '付款金额', search:false},
                    {field: 'alipayNum', title: '付款笔数', hide:true, search:false},
                    {field: 'preServiceFee', title: '预估付款服务费', hide:true, search:false},
                    {field: 'settleAmt', title: '结算金额', hide:true, search:false},
                    {field: 'settleNum', title: '结算笔数', hide:true, search:false},
                    {field: 'cmServiceFee', title: '预估结算服务费', hide:true, search:false},
                    {field: 'startTime', title: '活动开始时间', hide:true, search:'scope'},
                    {field: 'endTime', title: '活动结束时间', hide:true, search:'scope'},
                    {width: 150, title: '操作', toolbar: '#controlPlanScene'},
                ]],
            });

            //报名链接复制
            $('body').on('click', '#copy_link', function () {
                // 创建元素用于复制
                const copy_box = document.createElement('input');
                // 获取复制内容
                const content = $.trim($(this).attr("event_link"));
                // 设置元素内容
                copy_box.setAttribute('value', content);
                // 将元素插入页面进行调用
                document.body.appendChild(copy_box);
                // 复制内容
                copy_box.select();
                // 将内容复制到剪贴板
                document.execCommand('copy');
                // 删除创建元素
                document.body.removeChild(copy_box);
                ea.msg.success('复制成功！');
            });

            miniTab.listen();

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