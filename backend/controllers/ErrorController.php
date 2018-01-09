<?php
namespace backend\controllers;

use yii\web\Controller;

/**
 * 错误控制器处理
 * Class ErrorController
 * @package app\controllers
 * @author Gene <https://github.com/Talkyunyun>
 */
class ErrorController extends Controller {

    public function actionShow() {

        return $this->render('404');
    }
}