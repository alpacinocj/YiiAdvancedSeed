<?php
namespace website\controllers;


/**
 * 首页控制器
 * Class SiteController
 * @package website\controllers
 * @author Gene <https://github.com/Talkyunyun>
 */
class SiteController extends BaseController {

    public function actionIndex() {

        return $this->render('index');
    }
}