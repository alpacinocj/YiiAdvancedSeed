<?php

namespace backend\controllers;

use yii\web\Controller;

/**
 * 错误控制器处理
 * Class ErrorController
 * @package app\controllers
 */
class ErrorController extends Controller
{
    public function actionShow()
    {
        return $this->render('404');
    }
}