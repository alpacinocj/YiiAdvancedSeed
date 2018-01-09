<?php
namespace backend\models\SysUser;

use yii\db\ActiveRecord;

/**
 * Class SysRole 角色表
 * @package app\models\SysUser
 * @property string $name 名称
 * @property int $status 状态值
 * @property string $remark 备注
 * @author Gene <https://github.com/Talkyunyun>
 */
class SysRole extends ActiveRecord {
    public static function tableName() {
        return 'sys_role';
    }


    public function rules() {
        return [
            [['name'], 'required', 'message' => '{attribute}为必填字段'],
            ['status', 'in', 'range' => [1, 0], 'message' => '非法的状态值'],
        ];
    }

    public function attributeLabels() {
        return [
            'name' => '角色名称'
        ];
    }

    /**
     * 获取状态数据
     * @return array
     */
    public static function getStatusList() {
        return [
            0 => '关闭',
            1 => '正常'
        ];
    }
}

