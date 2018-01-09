<?php
namespace backend\controllers;


use backend\models\SysUser\SysPermission;

class TestController extends BaseController {


    public function actionIndex() {
        $ids = [14, 13];

        SysPermission::deleteAll("node_id in(:ids)", [
            ':ids' => join(',', $ids)
        ]);

        echo 44444;
    }
}