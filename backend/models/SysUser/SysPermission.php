<?php
namespace backend\models\SysUser;

use yii\db\ActiveRecord;

/**
 * Class SysPermission 权限表
 * @package app\models\SysUser
 * @property int $role_id 角色ID
 * @property int $node_id 节点ID
 * @author Gene <https://github.com/Talkyunyun>
 */
class SysPermission extends ActiveRecord {
    public static function tableName() {
        return 'sys_permission';
    }
}

