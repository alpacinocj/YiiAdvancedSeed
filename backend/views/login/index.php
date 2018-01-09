<?php
/**
 * 登录页面
 * @author: Gene
 */

use yii\helpers\Url;
use yii\helpers\Html;
$this->title = '欢迎登录 - ' . Yii::$app->params['app_name'];

$version = Yii::$app->params['version'];
?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/login.min.css?v=<?= $version ?>" rel="stylesheet">
</head>
<body class="signin">
<div class="signinpanel">
    <div class="row">
        <div class="col-sm-7">
            <div class="signin-info">
                <div class="logopanel m-b">
                    <h1><?= Yii::$app->params['app_name'] ?></h1>
                </div>
                <div class="m-b"></div>
                <ul class="m-b">
                    <li>
                        <i class="fa fa-arrow-circle-o-right m-r-xs"></i>
                        <?= Yii::$app->params['app_describe'] ?>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-sm-5">
            <form>
                <h4 class="no-margins"><?= $this->title ?></h4>
                <input type="text" id="account" class="form-control uname" placeholder="用户名/邮箱" />
                <input type="password" id="password" class="form-control pword m-b" placeholder="密码" />
                <button type="button" id="login_btn" class="btn btn-success btn-block">登录</button>
            </form>
        </div>
    </div>

    <div class="signup-footer">
        <div class="pull-left">
            &copy; 2016-<?= date('Y') ?> All Rights Reserved.
            <a style="color:#fff;"
               href="<?= Yii::$app->params['app_url'] ?>"
               target="_blank"><?= Yii::$app->params['app_name'] ?></a>
        </div>
    </div>
</div>
<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="/js/plugins/layui/layui.all.js"></script>
<script>
    $(function() {
        $(document).keypress(function(e) {
            if (e.keyCode == 13) $('#login_btn').click();
        });
        $('#account').focus();
        $('#login_btn').click(function() {
            var account = $('#account');
            var upass = $('#password');

            if (!account.val()) {
                account.focus();
                layer.msg('请输入用户名');
                return false;
            }

            if (!upass.val()) {
                layer.msg('请输入密码');
                upass.focus();
                return false;
            }

            var loading = layer.load(0, {shade: false});
            $.ajax({
                type : 'POST',
                url  : "<?= Url::toRoute('/login/do') ?>" ,
                data : {
                    account  : account.val(),
                    password : upass.val(),
                    token    : "<?= Yii::$app->request->csrfToken ?>"
                },
                success : function(res) {
                    layer.close(loading);
                    if (res.code == 0) {
                        window.location.href = '/';
                    } else {
                        layer.msg(res.msg);
                    }
                },
                beforeSend: function() {
                    loading = layer.load(0, {shade: false});
                },
                error : function() {
                    layer.msg('网络请求错误');
                    layer.close(loading);
                }
            });
        });
    });
</script>
</body>
</html>
