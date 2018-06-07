<?php

namespace common\models\Admin;

use common\models\BaseModel;

/**
 * Class Access 权限表
 * @package common\models\Admin
 * @property int $role_id 角色ID
 * @property int $node_id 节点ID
 */
class Access extends BaseModel
{
    public static function tableName()
    {
        return 'sys_access';
    }
}

