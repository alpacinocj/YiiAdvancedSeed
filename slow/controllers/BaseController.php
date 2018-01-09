<?php
namespace slow\controllers;

use yii\rest\Controller;
use yii\web\Response;

/**
 * 小程序接口基类
 * Class BaseController
 * @package slow\controllers
 */
class BaseController extends Controller {


    /**
     * 统一格式定义
     * @return array
     */
    public function behaviors() {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;

        return $behaviors;
    }

    
    protected function isAuth() {

    }
}