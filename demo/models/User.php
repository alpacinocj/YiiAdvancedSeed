<?php
namespace demo\models;


use yii\db\ActiveRecord;

/**
 * 用户模型
 * Class User
 * @package demo\models
 */
class User extends ActiveRecord {

    /**
     * 表名
     * @return string
     */
    public static function tableName() {
        return '{{%user}}';
    }
}