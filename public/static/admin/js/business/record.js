define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'business.record/index',
    };

    var Controller = {

        index: function () {

            $('body').on('click', '.layuimini-notice', function () {
                var title = $(this).children('.layuimini-notice-title').text(),
                    noticeTime = $(this).children('.layuimini-notice-extra').text(),
                    content = $(this).children('.layuimini-notice-content').html();
                var html = '<div style="padding:15px 20px; text-align:justify; line-height: 22px;border-bottom:1px solid #e2e2e2;background-color: #2f4056;color: #ffffff">\n' +
                    content +
                    '</div>\n';
                layer.open({
                    type: 1,
                    title: title + '<span style="float: right;right: 1px;font-size: 12px;color: #b1b3b9;margin-top: 1px">' + noticeTime + '</span>',
                    area: '50%;',
                    shade: 0.8,
                    shadeClose:true,
                    id: 'layuimini-notice',
                    btn: ['取消'],
                    btnAlign: 'c',
                    moveType: 1,
                    content: html,
                    success: function (layero) {

                    }
                });
            });

            ea.listen();
        }
    };
    return Controller;
});