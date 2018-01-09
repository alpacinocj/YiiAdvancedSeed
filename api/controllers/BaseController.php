<?php
namespace api\controllers;

use yii\rest\ActiveController;
use yii\web\Response;

/**
 * 基类
 * Class BaseController
 * @package api\controllers
 */
class BaseController extends ActiveController {

    /**
     * 设置响应格式
     * @return array
     */
    public function behaviors() {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;

        return $behaviors;
    }

    /**
     * 重新默认rest路由方法
     * @return array
     */
    public function actions() {
        $actions = parent::actions();
        unset(
            $actions['index'],
            $actions['view'],
            $actions['create'],
            $actions['update'],
            $actions['delete']
        );

        return $actions;
    }
}