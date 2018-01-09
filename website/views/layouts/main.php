<?php
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
    <link rel="stylesheet" href="/libs/bootstrap-3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/style.css?v=<?= $version ?>">
    <?php $this->head() ?>
    <script type="text/javascript">
        window.token = "<?= Yii::$app->request->csrfToken ?>";
    </script>
</head>
<body>
<?php $this->beginBody() ?>
<script src="/js/jquery.min.js"></script>
<script src="/js/app.js?v=<?= $version ?>"></script>

<div class="container_box"><?= $content ?></div>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage() ?>