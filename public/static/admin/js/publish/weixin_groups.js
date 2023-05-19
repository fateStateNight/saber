define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'publish.weixin_groups/index',
        add_url: 'publish.weixin_groups/add',
        edit_url: 'publish.weixin_groups/edit',
        delete_url: 'publish.weixin_groups/delete',
        export_url: 'publish.weixin_groups/export',
        modify_url: 'publish.weixin_groups/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id'},
                    {field: 'title', title: '群标题'},
                    {field: 'weixin_room_name', title: '群名称'},
                    {field: 'member_num', title: '群成员数'},
                    {field: 'man_num', title: '男成员数', hide:true},
                    {field: 'woman_num', title: '女成员数', hide:true},
                    {field: 'group_image', title: '社群图片', templet: ea.table.image},
                    {field: 'source_type', search: 'select', selectList: ["短信","抖音短视频","闲鱼","店铺券图","快递","包裹","知乎","抖音直播","红包"], title: '来源类型'},
                    {field: 'belong_id', title: '管理账号ID', hide: true},
                    {field: 'group_type', search: 'select', selectList: ["泛人群","购物群","精准群"], title: '群类型'},
                    {field: 'group_create_time', title: '群创建时间'},
                    {field: 'remark', title: '备注说明', templet: ea.table.text, hide: true},
                    {field: 'create_time', title: '创建时间', hide:true},
                    {field: 'systemAdmin.head_img', title: '头像', templet: ea.table.image},
                    {field: 'systemAdmin.nickname', title: '管理客服'},
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