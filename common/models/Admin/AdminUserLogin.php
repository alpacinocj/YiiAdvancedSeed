<?php

namespace common\models\Admin;

use yii\base\Model;

/**
 * Class AdminUserLogin 登录模型
 * @package common\models\Admin
 * @property string $username 用户名
 * @property string $password 密码
 */
class AdminUserLogin extends Model
{
    public $username;// 接收用户名
    public $password;// 接收密码

    // 用户对象
    private $adminUser;

    // 验证规则
    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'message' => '{attribute}不能为空'],
            ['password', 'validatePassword']
        ];
    }

    // 别名
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码'
        ];
    }

    /**
     * 验证密码是否正确
     * @param $attribute
     * @param $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $adminUser = $this->getAdminUser();
            if (empty($adminUser)) {
                $this->addError('username', '用户名或者密码错误1');
                return false;
            }

            if (!$adminUser->validatePassword($this->password)) {
                $this->addError('password', '用户名或者密码错误2');
                return false;
            }
        }
    }

    /**
     * 根据用户名获取用户对象模型
     * @return static
     */
    public function getAdminUser()
    {
        if($this->adminUser === null){
            $this->adminUser = AdminUser::findNormalByUserName($this->username);
        }

        return $this->adminUser;
    }

    /**
     * 登录操作
     * @return bool
     */
    public function login()
    {
        if ($this->validate()) {
            return \Yii::$app->user->login($this->getAdminUser(), 3600 * 24);
        } else {
            return false;
        }
    }
}