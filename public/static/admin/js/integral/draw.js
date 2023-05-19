define(["jquery", "easy-admin"], function ($, ea) {

    var form = layui.form;

    var Controller = {
        index: function () {

            /*var app = new Vue({
                el: '#app',
                data: {
                    upload_type: upload_type
                }
            });

            form.on("radio(upload_type)", function (data) {
                app.upload_type = this.value;
            });*/
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