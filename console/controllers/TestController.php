<?php
namespace console\controllers;

use yii\console\Controller;

/**
 * 测试命令
 * Class TestController
 * @package console\controllers
 */
class TestController extends Controller {


    public function actionIndex() {

        echo "我是控制台输出的内容\r\n";
    }
}