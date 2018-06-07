<?php

namespace backend\controllers;

use backend\models\AdminUser;
use backend\models\Node;

/**
 * 首页控制器
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends BaseController
{

    // 默认首页
    public function actionWelcome()
    {
        return $this->render('welcome');
    }

    /**
     * 框架主体
     * @return string|\yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionIndex()
    {

        // 当前用户角色
        $user = \Yii::$app->user->getIdentity();
        if (!$user) {
            return $this->redirect('/login');
        }
        $roleNames = [];
        if ($user->real_name == ROOT_USER) {
            $role = ROOT_USER_ROLE;
            $roleNames[] = $role;
        } else {
            $roleList = AdminUser::getUserRolesByUid($user->getId());
            $roleNames = array_column($roleList, 'name');
            if (count($roleNames) > 1) {
                $role = $roleNames[0] . '...';
            } else {
                $role = $roleNames[0];
            }
        }

        return $this->render('main', [
            'menu' => Node::getMenus(),
            'role' => $role,
            'roleNames' => $roleNames
        ]);

    }
}