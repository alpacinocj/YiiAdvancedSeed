<?php
namespace demo\db\controllers;
use demo\models\User;
use yii\web\Controller;


/**
 * 删除操作demo
 * Class DeleteController
 * @package demo\db\controllers
 */
class DeleteController extends Controller {

    // 根据ID删除
    public function actionDelById() {
        $userm = User::findOne(1);
        $num = $userm->delete();
        dd($num);
    }

    // 根据多条件删除
    public function actionDelByWhereAll() {
        $num = User::deleteAll('id>:id AND status=:status', [
            ':id' => 1,
            ':status' => 0
        ]);
        dd($num);
    }

    // 根据表名SQL删除
    public function actionDelSqlTable() {
        $db = \Yii::$app->db;
        $num = $db->createCommand()
            ->delete(User::tableName(), 'id=:id', [
                ':id' => 1
            ])->execute();
        dd($num);
    }

    // 根据原生SQL删除
    public function actionDelSql() {
        $db = \Yii::$app->db;
        $sql = 'delete from '. User::tableName() .' where id=:id';;
        $num = $db->createCommand($sql, [
            ':id' => 1
        ])->execute();
        dd($num);
    }
}