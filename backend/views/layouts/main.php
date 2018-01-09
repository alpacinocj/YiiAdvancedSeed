<?php
/**
 * 基本模板框架
 * @author: Gene
 */

use yii\helpers\Html;

$version = Yii::$app->params['version'];
$this->beginPage();
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
    <link rel="stylesheet" href="/js/plugins/layui/css/layui.css?v=<?= $version ?>" />
    <link rel="stylesheet" href="/css/style.css?v=<?= $version ?>">
    <?php $this->head() ?>
    <script type="text/javascript">
        window.token = "<?= Yii::$app->request->csrfToken ?>";
    </script>
</head>
<body style="background:#ffffff">
<?php $this->beginBody() ?>
<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="/js/template.js"></script>
<script src="/js/plugins/layui/layui.js?v=<?= $version ?>"></script>
<script src="/js/app.js?v=<?= $version ?>"></script>

<div class="container-fluid"><?= $content ?></div>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage() ?>