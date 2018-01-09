<?php
namespace backend\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use backend\models\SysUser\SysRole;
use backend\models\SysUser\SysUserRole;

/**
 * 系统用户表
 * @package app\models
 * @property string $password 用户登录密码
 * @property string $updated 修改时间
 * @author Gene <https://github.com/Talkyunyun>
 */
class SysUser extends ActiveRecord implements IdentityInterface {

    // 表名
    public static function tableName() {
        return 'sys_users';
    }

    // 规则
    public function rules() {
        return [
            [['email', 'password', 'real_name'], 'required', 'message' => '{attribute}不能为空'],
            ['email', 'email', 'message' => '非法的电子邮箱'],
            ['email', 'unique', 'message' => '该邮箱已经存在了'],
            ['status', 'in', 'range' => [1, 0], 'message' => '非法的状态值'],
            ['phone', 'match', 'pattern' => '/^1[3,4,5,7,8][0-9]{9}$/', 'message' => '非法的手机号码'],
            ['birth_date', 'default', 'value' => null]
        ];
    }

    // 别名
    public function attributeLabels() {
        return [
            'email'     => '登录邮箱',
            'password'  => '密码',
            'real_name' => '真实姓名',
            'phone'     => '联系号码',
            'birth_date'=> '生日日期'
        ];
    }

    // 获取用户状态值
    public static function getStatusList() {
        return [
            1 => '正常',
            0 => '禁用'
        ];
    }


    /**
     * 获取当前登录者对象
     * @return bool|static
     */
    public static function getCurrent() {
        try {
            return self::findOne([
                'id' => \Yii::$app->user->identity->id
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 根据登录账户获取正常用户信息
     * @param $account
     * @param string $accountType
     * @return bool|null|static
     */
    public static function findNormalByAccount($account, $accountType = 'email') {
        $allowTypes = ['username', 'email'];
        if (empty($account) || !in_array($accountType, $allowTypes)) {
            return false;
        } elseif ($accountType == 'username') {
            $accountType = 'real_name';
        }
        try {
            $where = [];
            $where[$accountType] = $account;
            $where['status'] = 1;
            return static::findOne($where);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 验证用户密码
     * @param $password
     * @return bool
     */
    public function validatePassword($password) {
        try {
            return \Yii::$app->security->validatePassword($password, $this->password);
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * 生成密码
     * @param string $password
     * @return string
     */
    public static function getNewPassword($password) {
        return \Yii::$app->security->generatePasswordHash($password);
    }


    /**
     * 获取用户角色列表
     * @param int $uid
     * @return bool
     */
    public static function getUserRolesByUid($uid = 0) {
        $data = SysRole::find()
            ->from(SysUserRole::tableName() . ' a')
            ->leftJoin(SysRole::tableName() . ' b', 'a.role_id=b.id')
            ->select('b.name')
            ->where(['a.user_id' => $uid])
            ->asArray()
            ->all();

        return $data;
    }


    /**
     * 根据ID获取用户信息
     * @param $id
     */
    public static function getDataById($id, $fields = false) {
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
    public static function findIdentity($id)  {
        return static::findOne(['id' => $id, 'status' => 1]);
    }


    /**
     * 通过access_token 找到identity
     * @param mixed $token
     * @param null $type
     * @return static
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne([
            'access_token' => $token,
            'status'       => 1
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }
}



