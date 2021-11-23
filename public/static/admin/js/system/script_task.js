define(["jquery", "easy-admin"], function ($, ea) {
    var form = layui.form;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'system.script_task/index',
        add_url: 'system.script_task/add',
        edit_url: 'system.script_task/edit',
        delete_url: 'system.script_task/delete',
        export_url: 'system.script_task/export',
        add_order_url: 'system.script_task/addOrder',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh', 'delete', 'export'],
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id'},
                    {field: 'title', title: '任务标题'},
                    {field: 'type', title: '任务类型', search: 'select', selectList: ["系统任务","订单数据更新","订单数据导出"]},
                    {field: 'task_status', search: 'select', selectList: ["未执行","执行中","已完成"], title: '任务状态'},
                    {field: 'task_content', title: '任务内容'},
                    {field: 'creater_id', title: '创建人ID', hide: true},
                    {field: 'systemAdmin.nickname', title: '昵称'},
                    {field: 'systemAdmin.head_img', title: '头像', search: false, templet: ea.table.image},
                    {field: 'create_time', title: '创建时间'},
                    {width: 250, title: '操作', toolbar: '#controlPlan'},
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
        detail: function(){
            ea.listen();
        },
        addOrder: function(){
            form.on('select(type)', function(data){
                //console.log(data.elem); //得到radio原始DOM对象
                //console.log(data.value); //被点击的radio的value值
                if(data.value == '2'){
                    ea.msg.alert('请确定导出的数据是否已经同步更新完成！');
                }
            });

            ea.listen();
        },
    };
    return Controller;
});