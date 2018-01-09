<?php
namespace demo\db\controllers;
use demo\models\User;
use yii\web\Controller;


/**
 * 插入查询DEMO
 * Class InsertController
 * @package demo\db\controllers
 */
class InsertController extends Controller {

    // 模型插入
    public function actionModel() {
        $userm = new User();
        $userm->username = 'admin';
        $userm->password = md5('123456');

        // 成功返回true, 失败返回false
        $userm->save(); // 或者 $userm->insert();
    }

    // 原生SQL添加，按表名
    public function actionSqlTable() {
        $db  = \Yii::$app->db;
        $res = $db->createCommand()->insert(User::tableName(), [
            'username' => rand(99, 1000),
            'password' => md5('123456')
        ])->execute();

        dd($res);// 返回影响的行数
    }

    // 原生SQL添加，普通
    public function actionSql() {
        $db  = \Yii::$app->db;
        $sql = 'insert into '.User::tableName().'(username, password) values(:username, :password)';
        $res = $db->createCommand($sql, [
            ':username' => rand(99, 1000),
            ':password' => md5('123456')
        ])->execute();

        dd($res);// 返回影响的好行数
    }


    // 原生SQL批量添加
    public function actionSqlAll() {
        $db = \Yii::$app->db;

        $res = $db->createCommand()
            ->batchInsert(User::tableName(), ['username', 'password'], [
                [rand(1, 999), md5(123456)],
                [rand(3, 88888), md5(123456)]
            ])->execute();

        dd($res);// 返回影响的行数
    }


    // 事务处理
    public function actionTransaction() {
        $dbTrans = \Yii::$app->db->beginTransaction();

        try {
            // 处理其他逻辑

            $dbTrans->commit();
        } catch (\Exception $e) {
            $dbTrans->rollBack();
            dd($e->getMessage());
        }
    }
}