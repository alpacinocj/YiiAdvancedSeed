<?php
/**
 * WEB入口文件,不建议在该文件添加或修改代码
 * @author Gene <https://github.com/Talkyunyun>
 */


require_once __DIR__ . '/../../common/config/bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
$config = require_once __DIR__ . '/../config/config.php';

appAlias();
(new \yii\web\Application($config))->run();