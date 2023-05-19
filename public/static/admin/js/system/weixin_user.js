define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'system.weixin_user/index',
        add_url: 'system.weixin_user/add',
        edit_url: 'system.weixin_user/edit',
        delete_url: 'system.weixin_user/delete',
        export_url: 'system.weixin_user/export',
        modify_url: 'system.weixin_user/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id'},
                    {field: 'nickname', title: '用户昵称'},
                    {field: 'openid', title: '用户ID'},
                    {field: 'sex', title: '性别'},
                    {field: 'province', title: '省份'},
                    {field: 'city', title: '城市'},
                    {field: 'group_id', title: '微信群ID'},
                    {field: 'publishWeixinGroups.title', title: '群名称'},
                    {field: 'headimgurl', title: '头像', templet: ea.table.image},
                    {field: 'status', search: 'select', selectList: ["禁用","启用"], title: '状态', templet: ea.table.switch},
                    {field: 'integral', title: '剩余积分'},
                    {field: 'remark', title: '备注说明', templet: ea.table.text},
                    {field: 'create_time', title: '创建时间'},
                    {width: 250, title: '操作', templet: ea.table.tool},
                ]],
            });

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