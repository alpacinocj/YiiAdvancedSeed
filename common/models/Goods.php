<?php
namespace common\models;


use yii\db\ActiveRecord;

/**
 * Goods模型案例
 * Class Goods
 * @package api\modules\v1\models
 * @property string $title 标题
 * @property string $desc 描述
 * @property string $price 价格
 */
class Goods extends ActiveRecord {

    // 表名
    public static function tableName() {
        return '{{%goods}}';
    }

    // 验证规则
    public function rules() {
        return [
            [['title', 'price'], 'required', 'message' => '{attribute}不能为空']
        ];
    }


    // 字段别名
    public function attributeLabels() {
        return [
            'title' => '标题',
            'desc'  => '描述',
            'price' => '价格'
        ];
    }
}