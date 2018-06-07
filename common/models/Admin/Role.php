<?php

namespace common\models\Admin;

use common\models\BaseModel;

/**
 * Class Role 角色表
 * @package common\models\Admin
 * @property string $name 名称
 * @property int $status 状态值
 * @property string $remark 备注
 */
class Role extends BaseModel
{
    public static function tableName()
    {
        return 'sys_role';
    }


    public function rules()
    {
        return [
            [['name'], 'required', 'message' => '{attribute}为必填字段']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '角色名称'
        ];
    }

    /**
     * 获取状态数据
     * @return array
     */
    public static function getStatusList()
    {
        return [
            0 => '关闭',
            1 => '正常'
        ];
    }
}

