<?php

use yii\helpers\Html;
use yii\helpers\Url;
$this->title = '欢迎首页';
?>

<div class="row">
    <div class="col-md-12">
        <a href="#"
           class="open_page"
           data-title="我是弹窗案例样式"
           data-url="<?= Url::toRoute('') ?>"
        >弹窗案例</a>


        <a href="#"
           class="open_save"
           data-url="<?= Url::toRoute('/demo/form') ?>"
           data-title="我是表单案例"
        >表单</a>




        <div>
            <a href="javascript:void(0)" onclick="tips(1)">成功弹窗</a>
            <a href="javascript:void(0)" onclick="tips(2)">警告弹窗</a>
            <a href="javascript:void(0)" onclick="tips(3)">提示弹窗</a>
            <a href="javascript:void(0)" onclick="tips(4)">错误弹窗</a>
        </div>

    </div>
</div>
<script>
$(function() {
    $('.open_save').click(function() {
        var url = $(this).attr('data-url');
        var title = $(this).attr('data-title');

        app.openParentWin({
            url  : url,
            title: title,
            callback: function(index, dom) {
                $(dom).find('iframe')[0].contentWindow.submit(index, function(res) {
                    if (res === true) {
                        window.location.reload();
                    }
                });
            }
        });
    });
});

function tips(type) {
    switch (type) {
        case 1:
            app.showMsg('我是成功弹窗');
            break;
        case 2:
            app.showMsg('我是警告弹窗', 'warning');
            break;
        case 3:
            app.showMsg('我是提示弹窗', 'info');
            break;
        case 4:
            app.showMsg('我是错误弹窗', 'error');
            break;
    }
}
</script>

