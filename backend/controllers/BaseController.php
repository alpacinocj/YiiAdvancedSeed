<?php
namespace backend\controllers;


use backend\filter\AuthFilter;
use yii\web\Controller;

/**
 * 后台基类
 * Class BaseController
 * @package backend\controllers
 */
class BaseController extends Controller {

    // 行为控制
    public function behaviors() {
        return [
            'access' => [
                'class' => AuthFilter::className()
            ]
        ];
    }
}