<?php
/**
 * 基本模板框架
 * @author: Gene
 */

use yii\helpers\Html;
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php $this->beginBody() ?>

<h4>layout.html</h4>
<?= $content ?>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage() ?>