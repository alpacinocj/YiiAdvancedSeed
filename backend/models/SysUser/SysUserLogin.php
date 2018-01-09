<?php
namespace backend\models\SysUser;

use yii\base\Model;
use backend\models\SysUser;

/**
 * Class SysUserLogin 系统用户登录模型
 * @package app\models\SysUser
 * @property string $account 登录账户
 * @property string $accountType 账户类型
 * @property string $password 密码
 * @author Gene <https://github.com/Talkyunyun>
 */
class SysUserLogin extends Model {

    public $account;        // 登录账户
    public $accountType;    // 账户类型
    public $password;       // 登录密码

    // 用户对象
    private $sysUser;

    // 验证规则
    public function rules() {
        return [
            [['account', 'password'], 'required', 'message' => '{attribute}不能为空'],
            ['password', 'validatePassword']
        ];
    }

    // 别名
    public function attributeLabels() {
        return [
            'account'    => '登录账户',
            'password' => '密码'
        ];
    }

    /**
     * 验证密码是否正确
     * @param $attribute
     * @param $params
     */
    public function validatePassword($attribute, $params){
        if (!$this->hasErrors()) {
            $sysUser = $this->getSysUser();
            if (empty($sysUser)) {
                $this->addError('email', '用户名或者密码错误');
                return false;
            }

            if (!$sysUser->validatePassword($this->password)) {
                $this->addError('password', '用户名或者密码错误');
                return false;
            }
        }
    }

    /**
     * 根据登录账户获取用户对象模型
     * @return static
     */
    public function getSysUser(){
        if($this->sysUser === null){
            $this->sysUser = SysUser::findNormalByAccount($this->account, $this->accountType);
        }

        return $this->sysUser;
    }

    /**
     * 登录操作
     * @return bool
     */
    public function login() {
        if ($this->validate()) {
            return \Yii::$app->user->login($this->getSysUser(), 3600 * 24);
        } else {
            return false;
        }
    }
}