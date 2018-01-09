<?php
namespace slow\controllers;


class TestController extends BaseController {

    public function actionIndex() {

        dd(date('Y-m-d H:i:s', 1485058920));

        return sha1(microtime(true));
    }
}