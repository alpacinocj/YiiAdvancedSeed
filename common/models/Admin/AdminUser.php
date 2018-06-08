<?php

namespace common\models\Admin;

use common\models\BaseModel;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * 系统用户表
 * @package common\models\Admin
 * @property int $id
 * @property string $username 用户名
 * @property string $password 登录密码
 * @property string $real_name 真实姓名
 * @property string $phone 电话
 * @property string $email 邮箱
 * @property string $auth_key
 * @property string $access_token
 * @property int $status 状态(0禁用, 1启用)
 * @property string $tag 用户标签
 * @property int $created 新增时间
 * @property int $updated 更新时间
 */
class AdminUser extends BaseModel implements IdentityInterface
{

    // 表名
    public static function tableName()
    {
        return 'sys_admin_user';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'updated',
                'value' => function() {
                    return time();
                }
            ]
        ];
    }

    // 规则
    public function rules()
    {
        return [
            [['username', 'password', 'real_name'], 'required', 'message' => '{attribute}不能为空'],
            ['username', 'unique', 'message' => '该用户名已经存在了'],
            ['username', 'string', 'length' => [4, 18], 'message' => '用户名长度限制4-18个字符'],
            ['status', 'in', 'range' => [1, 0], 'message' => '非法的状态值'],
            ['email', 'email', 'message' => '非法的电子邮箱'],
            ['phone', 'match', 'pattern' => '/^1[3,4,5,7,8][0-9]{9}$/', 'message' => '非法的手机号码'],
        ];
    }

    // 别名
    public function attributeLabels()
    {
        return [
            'username' => '登录用户名',
            'password' => '密码',
            'real_name'=> '真实姓名'
        ];
    }

    // 场景定义
    public function scenarios()
    {
        return [
            'create' => ['username', 'password', 'real_name', 'status', 'email', 'phone'],
            'update' => ['username', 'real_name', 'status', 'email', 'phone'],
            'update_password' => ['password', 'updated']
        ];
    }


    // 获取用户状态值
    public static function getStatusList()
    {
        return [
            1 => '生效',
            0 => '失效'
        ];
    }


    /**
     * 获取当前登录者对象
     * @return bool|static
     */
    public static function getCurrent()
    {
        try {
            return self::findOne([
                'id' => \Yii::$app->user->identity->id
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * 根据登录用户名获取正常用户信息
     * @param $userName
     * @return bool|static
     */
    public static function findNormalByUserName($userName)
    {
        try {
            return static::findOne([
                'username' => $userName,
                'status'   => 1
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * 验证用户密码
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        try {
            return \Yii::$app->security->validatePassword($password, $this->password);
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * 生成密码
     * @param $password
     * @return string
     * @throws \yii\base\Exception
     */
    public static function getNewPassword($password)
    {
        return \Yii::$app->security->generatePasswordHash($password);
    }


    /**
     * 获取用户角色列表
     * @param int $uid
     * @return bool
     */
    public static function getUserRolesByUid($uid = 0)
    {
        try {
            $data = Role::find()
                ->from(RoleUser::tableName() . ' a')
                ->leftJoin(Role::tableName() . ' b', 'a.role_id=b.id')
                ->select('b.name')
                ->where(['a.user_id' => $uid])
                ->asArray()
                ->all();

            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * 根据ID获取用户信息
     * @param $id
     * @param bool $fields
     * @return array|bool|null|ActiveRecord
     */
    public static function getDataById($id, $fields = false)
    {
        try {
            $query = self::find()
                ->where(['id' => $id]);

            if ($fields !== false) {
                $query->select($fields);
            }

            return $query->one();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 通过id 找到identity
     * @param int|string $id
     * @return static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => 1]);
    }


    /**
     * 通过access_token 找到identity
     * @param mixed $token
     * @param null $type
     * @return static
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne([
            'access_token' => $token,
            'status'       => 1
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}



