<?php
namespace demo\db\controllers;
use demo\models\User;
use demo\models\UserExt;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\Controller;


/**
 * 查询DEMO
 * Class SelectController
 * @package demo\db\controllers
 */
class SelectController extends Controller {

    // 基本查询，findOne()、findAll()
    public function actionBase() {
        // 1.返回ID为10的用户
        $data = User::findOne(10);

        // 2.返回ID为10且状态为1的用户
        $data = User::findOne([
            'id' => 10,
            'status' => 1
        ]);

        // 3.返回ID为10、2、3的一组用户
        $data = User::findAll([10, 2, 3]);

        // 4.返回所有禁用的用户
        $data = User::findAll([
            'status' => 0
        ]);

        dd($data);
    }


    // 基本查询
    public function actionBaseTwo() {
        $data = User::find()
            ->select('id, username')
            ->where(['status' => 1])
            ->orderBy(['id' => SORT_DESC])
            ->indexBy('id') // 返回以id为索引的数组
            ->asArray()
            ->all();// all()、one()、count()、sum()
    }


    // 原生SQL查询
    public function actionSql() {

        // 1.模型sql
        $sql  = 'SELECT * FROM users WHERE id=:id';
        $data = User::findBySql($sql, [
                ':id' => 1
            ])
            ->asArray()
            ->all();

        // 2.创建sql
        $data = \Yii::$app->db->createCommand($sql)
            ->bindParam(':id', 1)
            ->queryAll();// queryOne()

        dd($data);
    }


    // 多表查询生成器
    public function actionQuery() {

        // 普通查询
        $data = (new Query())
            ->from(User::tableName())
            ->select('id uid, username')
            ->addSelect('image')
            ->where(['status' => 1])
            ->limit(10)
            ->all();
        dd($data);


        // 分页查询
        $page = new Pagination([
            'pageSize' => 10,
            'totalCount' => 10
        ]);
        $data = (new Query())
            ->from(User::tableName() . ' u')
            ->select('u.id, e.phone')
            ->leftJoin(UserExt::tableName() . ' e', 'u.id=e.uid')
            ->where($where, $bindParam)
            ->offset($page->offset)
            ->limit($page->limit)
            ->all();
        dd($data);
    }



    // 批量获取数据，当数据量大时，减少内存暂用
    public function actionBig() {
        // 1.一次提取3个用户信息
        foreach (User::find()->batch(3) as $user) {
            // $user是3个的用户对象数组
        }

        // 2.一次提取10个用户并一个一个的遍历处理
        foreach (User::find()->each(10) as $user) {
            // $user是一个用户对象
        }

        // 3.贪婪加载模式的批量处理查询
        foreach (User::find()->with(UserExt::tableName())->each() as $user) {
            // dd($user);
        }
    }
}