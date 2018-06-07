<?php

namespace backend\controllers;

use Yii;
use common\utils\Util;
use yii\web\Controller;
use common\utils\ResponseUtil;
use backend\models\AdminUserLogin;
use backend\models\AdminUserLoginLog;

/**
 * 登录控制器
 * Class LoginController
 * @package app\controllers
 */
class LoginController extends Controller
{

    public $layout = false;

    /**
     * 登录页面
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = false;

        // 判断是否已登录
        $isGuest = Yii::$app->user->isGuest;
        if(!$isGuest) {
            return $this->redirect('/', 200);
        }

        return $this->render('index');
    }

    /**
     * 登录处理
     * @return array
     */
    public function actionDo()
    {
        $request = Yii::$app->request;

        try {
            if (!$request->isPost) {
                throw new \Exception('没有发现该页面', 1000);
            }

            $isGuest = Yii::$app->user->isGuest;
            if(!$isGuest) {
                return ResponseUtil::success('登录成功');
            }
            $model = new AdminUserLogin();
            $model->username = $request->post('username', false);
            $model->password = $request->post('password', false);
            if (!$model->login()) {
                throw new \Exception(Util::getModelError($model->errors), 1001);
            }
            // 记录登录日志
            AdminUserLoginLog::add();

            return ResponseUtil::success('登录成功');
        } catch (\Exception $e) {
            $msg = $e->getCode() == 0 ? '登录失败' : $e->getMessage();
            //var_dump($e->getMessage()); die;
            return ResponseUtil::error($msg);
        }
    }


    // 退出登录
    public function actionLogout()
    {
        $isGuest = Yii::$app->user->isGuest;
        if(!$isGuest){
            Yii::$app->user->logout();
        }

        return $this->redirect('/login', 200);
    }
}