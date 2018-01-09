<?php
namespace backend\controllers;

use backend\models\SysUser\SysNode;

/**
 * 首页控制器
 * Class SiteController
 * @package app\controllers
 * @author Gene <https://github.com/Talkyunyun>
 */
class SiteController extends BaseController {

    // 默认首页
    public function actionWelcome() {

        return $this->render('welcome');
    }

    /**
     * 框架主体
     * @return string
     */
    public function actionIndex() {
        $this->layout = false;

        return $this->render('main', [
            'menu' => SysNode::getMenus()
        ]);
    }
}