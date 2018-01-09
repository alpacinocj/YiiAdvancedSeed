<?php
/**
 * 框架主体文件
 * @author: Gene
 */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::$app->params['app_name'];
$user = Yii::$app->user->identity;

$version = Yii::$app->params['version'];
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>" />
<title><?= Html::encode($this->title) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="https://cdn.bootcss.com/animate.css/3.5.2/animate.min.css" rel="stylesheet">
<link href="/js/plugins/toastr/toastr.min.css?v=1.1" rel="stylesheet">
<link rel="stylesheet" href="/js/plugins/layui/css/layui.css?v=<?= $version ?>" />
<link href="/js/plugins/h+/css/style.min.css?v=<?= $version ?>" rel="stylesheet">
<link rel="stylesheet" href="/css/style.css?v=<?= $version ?>">
</head>
<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i></div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span><img style="width:60px;height:60px;"
                                   alt="image" class="img-circle" src="/img/face.jpg" /></span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear">
                               <span class="block m-t-xs">
                                   <strong class="font-bold"><?= $user->real_name ?></strong>
                               </span>
                                <span class="text-muted text-xs block">
                                    超级管理员<b class="caret"></b>
                                </span>
                            </span>
                        </a>
                        <ul class="dropdown-menu animated fadeIn m-t-xs">
                            <li>
                                <a class="update_password"
                                   data-url="<?= Url::toRoute('/system/user/password') ?>"
                                   data-title="修改密码" href="#">修改密码</a>
                            </li>
                            <li><a class="open_page"
                                   data-url="<?= Url::toRoute('/system/user/view') ?>"
                                   data-title="个人信息"
                                   href="#">个人信息</a></li>
                            <li class="divider"></li>
                            <li><a href="<?= Url::toRoute('/logout') ?>">安全退出</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">AFD</div>
                </li>

                <?= $menu ? $this->render('menu', ['menu' => $menu]) : '' ?>
            </ul>
        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#">
                        <i class="fa fa-bars"></i>
                    </a>
                    <form role="search" class="navbar-form-custom" method="post" action="">
                        <div class="form-group">
                            <input type="text" placeholder="请输入您需要查找的内容 …" class="form-control" name="top-search" id="top-search">
                        </div>
                    </form>
                </div>

                <ul class="nav navbar-top-links navbar-right">
                    <li class="hidden-xs">
                        <a href="javascript:void(0)" id="refresh"><i class="fa fa-refresh"></i> 刷新</a>
                    </li>
                    <li class="dropdown hidden-xs">
                        <a class="right-sidebar-toggle" href="<?= Url::toRoute('/logout') ?>">
                            <i class="fa fa-power-off"></i> 退出
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="row content-tabs">
            <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
            </button>
            <nav class="page-tabs J_menuTabs">
                <div class="page-tabs-content">
                    <a href="javascript:;" class="active J_menuTab"
                       data-id="<?= Url::toRoute('site/home') ?>">首页</a>
                </div>
            </nav>
            <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
            </button>
            <div class="btn-group roll-nav roll-right">
                <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span>

                </button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li class="J_tabCloseAll"><a>关闭全部</a></li>
                    <li class="J_tabCloseOther"><a>关闭其他</a></li>
                </ul>
            </div>
        </div>

        <!-- 内容 -->
        <div class="row J_mainContent" id="content-main">
            <iframe class="J_iframe" name="iframe0"
                    width="100%" height="100%"
                    src="<?= Url::toRoute('site/welcome') ?>"
                    frameborder="0" data-id="<?= Url::toRoute('site/welcome') ?>" seamless></iframe>
        </div>

        <!-- 尾部 -->
        <div class="footer">
            <div class="pull-right">
                &copy; 2016-<?= date('Y') ?>
                <a href="<?= Yii::$app->params['app_url'] ?>" target="_blank"><?= Yii::$app->params['app_name'] ?></a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/js/plugins/layui/layui.js?v=<?= $version ?>"></script>
<script src="/js/plugins/toastr/toastr.min.js"></script>
<script src="/js/parent/contabs.min.js"></script>
<script src="/js/parent/main.min.js?v=<?= $version ?>"></script>
<script src="/js/parent/app.js?v=<?= $version ?>"></script>
<script>
    $(function() {
        // 修改密码
        $('.update_password').click(function() {
            var url = $(this).attr('data-url');
            var title = $(this).attr('data-title');

            app.openWin({
                url  : url,
                title: title,
                width: '600px',
                height: '400px',
                callback: function(index, dom) {
                    $(dom).find('iframe')[0].contentWindow.submit(index, function(res) {});
                }
            });
        });

        $('.open_page').click(function() {
            var url = $(this).attr('data-url');
            var title = $(this).attr('data-title');

            app.openWin({
                url  : url,
                title: title
            });
        });

        // 刷新
        $('#refresh').click(function() {
            var iframes = $('#content-main').find('iframe');
            var len = iframes.length;
            for (var i = 0; i < len; i++) {
                var dom = iframes.eq(i)[0];

                if (dom.style.display != 'none') {
                    let src = iframes.eq(i)[0].src;

                    iframes.eq(i)[0].src = src;
                    return false;
                }
            }
        });
    });
</script>
</body>
</html>