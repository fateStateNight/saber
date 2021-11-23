define(["jquery", "easy-admin"], function ($, ea) {

    var transfer = layui.transfer,
        form = layui.form;
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'system.admin_group/index',
        add_url: 'system.admin_group/add',
        edit_url: 'system.admin_group/edit',
        delete_url: 'system.admin_group/delete',
        all_member_url: 'system.admin_group/getAllMemberList',
        group_member_url: 'system.admin_group/getGroupMemberList',
        update_switch_url: 'system.admin_group/updateGroupPublish',
    };

    var Controller = {

        index: function () {

            form.on('switch(publish-switch)', function(data){
                let groupId = $("input[name='publish']").attr('publish-groupId');
                ea.request.get({
                    url:ea.url(init.update_switch_url),
                    data:{
                        groupId:groupId,
                        publish:data.value,
                    },
                }, function(res){
                    ea.msg.success('修改成功！',function(){
                        location.reload();
                    });
                });
            });

            $("#deleteGroup").click(function(){
                let groupId = new Array();
                $("input[name='group_select']:checked").each(function(index, element) {
                    //追加到数组中
                    groupId.push($(this).val());
                });
                if(groupId.length == 0){
                    ea.msg.error('请选择一个群组！');
                    return false;
                }else{
                    ea.request.get({
                        url:ea.url(init.delete_url),
                        data:{
                            id:groupId,
                        },
                    }, function(res){
                        ea.msg.success('删除成功！',function(){
                            location.reload();
                        });
                    });
                }
            });

            ea.listen();
        },
        add: function () {

            //模拟数据
            var data1 = [
                {"value": "1", "title": "李白"}
                ,{"value": "2", "title": "杜甫"}
                ,{"value": "3", "title": "苏轼"}
                ,{"value": "4", "title": "李清照"}
                ,{"value": "5", "title": "鲁迅", "disabled": true}
                ,{"value": "6", "title": "巴金"}
                ,{"value": "7", "title": "冰心"}
                ,{"value": "8", "title": "矛盾"}
                ,{"value": "9", "title": "贤心"}
            ];
            //获取群组内已有成员
            let groupMember = [];
            ea.request.get({
                url:ea.url(init.group_member_url),
                data:{
                    groupId:'',
                }
            }, function(res){
                groupMember = res.data;
            });

            //获取所有成员列表
            ea.request.get({
                url:ea.url(init.all_member_url),
            },function(res){
                data1 = res.data;
                //显示搜索框
                transfer.render({
                    elem: '#group_transfer'
                    ,id: 'group_member'
                    ,data: data1
                    ,title: ['待选择组员', '已选择组员']
                    ,showSearch: true
                    ,value:groupMember
                });
            }, function(res){
                console.log(222);
            });


            ea.listen(function(formData){
                let groupSelect = transfer.getData('group_member');
                formData.groupSelect = groupSelect;
                return formData;
            },function(res){
                if(res.code == 1){
                    ea.msg.success(res.msg)
                }else{
                    ea.msg.error(res.msg);
                }
                setTimeout(function(){
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                    parent.location.reload();
                },1000);
            });
        },
        edit: function () {
            //获取url参数
            function getQueryString(name) {
                const reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
                const urlObj = window.location;
                var r = urlObj.href.indexOf('#') > -1 ? urlObj.hash.split("?")[1].match(reg) : urlObj.search.substr(1).match(reg);
                if (r != null) return unescape(r[2]);
                return null;
            }
                //模拟数据
            var data2 = [];
            console.log(getQueryString('id'));
            //获取群组内已有成员
            let groupMember = [];
            ea.request.get({
                url:ea.url(init.group_member_url),
                data:{
                    groupId:getQueryString('id'),
                }
            }, function(res){
                groupMember = res.data;
            });

            //获取所有成员列表
            ea.request.get({
                url:ea.url(init.all_member_url),
            },function(res){
                data2 = res.data;
                //显示搜索框
                transfer.render({
                    elem: '#group_transfer'
                    ,id: 'group_member'
                    ,data: data2
                    ,title: ['待选择组员', '已选择组员']
                    ,showSearch: true
                    ,value:groupMember
                });
            }, function(res){
                console.log(222);
            });

            ea.listen(function(formData){
                let groupSelect = transfer.getData('group_member');
                formData.groupSelect = groupSelect;
                return formData;
            },function(res){
                if(res.code == 1){
                    ea.msg.success(res.msg)
                }else{
                    ea.msg.error(res.msg);
                }
                setTimeout(function(){
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                    parent.location.reload();
                },1000);
            });
        },
    };
    return Controller;
});