<?php

namespace backend\controllers;

use backend\filter\AuthFilter;
use yii\web\Controller;

/**
 * 后台基类
 * Class BaseController
 * @package backend\controllers
 */
class BaseController extends Controller
{

    public $layout = false;

    protected $assetsVersion;

    public function init()
    {
        parent::init();
        $assetsVersion = isset(\Yii::$app->params['version']) ? \Yii::$app->params['version'] : 'v1.0.0';
        if (YII_ENV != ENV_PROD) {
            $assetsVersion = time();
        }
        $this->view->params['assetsVersion'] = $assetsVersion;
    }

    // 行为控制
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AuthFilter::className()
            ]
        ];
    }

    /**
     * 纪录异常日志
     * @param \Exception $e
     */
    protected function logException(\Exception $e)
    {
        $format = "%s:%d\n%s\n%s";
        $error = sprintf($format, $e->getFile(), $e->getLine(), $e->getMessage(), $e->getTraceAsString());
        \Yii::info($error, 'exception');
    }
}