<?php
namespace slow\models;


use yii\db\ActiveRecord;

/**
 * 客户端用户模型
 * Class Clients
 * @package common\models
 * @property string $mobile 手机号码
 */
class Clients extends ActiveRecord {

    /**
     * 表名
     * @return string
     */
    public static function tableName() {
        return '{{%clients}}';
    }


    /**
     * 验证规则
     * @return array
     */
    public function rules() {
        return [
            ['wechat_openid', 'required', 'message' => '缺省必要参数']
        ];
    }

    /**
     * 字段别名
     * @return array
     */
    public function attributeLabels() {
        return [
            'mobile'     => '手机号码',
            'email'      => '电子邮箱',
            'password'   => '密码',
            'first_name' => '名字',
            'last_name'  => '姓氏',
            'nick_name'   => '昵称'
        ];
    }
}