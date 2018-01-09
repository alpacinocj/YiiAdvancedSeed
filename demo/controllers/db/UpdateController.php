<?php
namespace demo\db\controllers;
use demo\models\User;
use yii\web\Controller;


/**
 * 更新DEMO
 * Class UpdateController
 * @package demo\db\controllers
 */
class UpdateController extends Controller {

    // 自增和自减
    public function actionInc() {
        $num = User::updateAllCounters([
            'view' => -1
        ]);
        dd($num);
    }


    // 模型更新
    public function actionModel() {
        $userm = User::findOne(1);
        $userm->username = 'dddd';

        // 成功true,失败false
        $userm->save();// 或 $userm->update();
    }

    // SQL更新-表名
    public function actionSqlTable() {
        $db = \Yii::$app->db;
        $num = $db->createCommand()
            ->update(User::tableName(), [
                'username' => 'ddd'
            ], 'id=:id', [
                ':id' => 11
            ])->execute();

        dd($num);
    }

    // SQL更新-原生
    public function actionSql() {
        $db = \Yii::$app->db;
        $sql = 'UPDATE '.User::tableName().' SET username=:username,image=:image WHERE id=:id';
        $num = $db->createCommand($sql, [
            ':username' => 'hhhh',
            ':image'    => 'sfsdfsd',
            ':id'       => $id
        ])->execute();

        dd($num);
    }
}