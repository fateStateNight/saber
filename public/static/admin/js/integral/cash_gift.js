define(["jquery", "easy-admin"], function ($, ea) {

    var form = layui.form;
    var element = layui.element;
    var layer = layui.layer;

    var Controller = {
        index: function () {
            console.log(123456);

            function Copy(str){
                var save = function(e){
                    e.clipboardData.setData('text/plain', str);
                    e.preventDefault();
                };
                document.addEventListener('copy', save);
                document.execCommand('copy');
                document.removeEventListener('copy',save);
                ea.msg.success('复制成功！');
            }

            $("#itemCopy").click(function(){
                Copy($(this).attr('data-clipboard-text'));
            });
            ea.listen();
        }
    };
    return Controller;
});