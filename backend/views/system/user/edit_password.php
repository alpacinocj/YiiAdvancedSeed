<?php
/**
 * 修改密码
 * @author: Gene
 */

use yii\helpers\Url;

$this->title = '修改密码';
?>
<style>
    .layui-form-label {
        float: left;
        display: block;
        padding: 9px 15px;
        width: 100px;
        font-weight: 400;
        text-align: right;
    }
</style>
<div class="col-md-12 animated fadeIn" style="margin-top:20px;">
    <form class="layui-form" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">旧密码:</label>
            <div class="layui-input-block">
                <input type="password" id="old_password" class="form-control" placeholder="输入您的旧密码" />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">新秘密:</label>
            <div class="layui-input-block">
                <input type="password" id="new_password" class="form-control" placeholder="请输入新密码" />
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">确认密码:</label>
            <div class="layui-input-block">
                <input type="password" id="not_password" class="form-control" placeholder="请输入确认密码" />
            </div>
        </div>
    </form>
</div>
<script>
    function submit(index, callback) {
        var data = new Object();
        data.old_password = $('#old_password').val();
        data.new_password = $('#new_password').val();
        data.not_password = $('#not_password').val();

        app.post("<?= Url::toRoute('/system/user/password') ?>", data, function(res) {
            if (res !== false) {
                app.showMsg(res, 'success');
                app.parent.layer.close(index);

                callback(true);
            }

            callback(false);
        });
    }
</script>
