<?php
namespace backend\controllers\system;

use backend\models\SysUser\SysRole;
use backend\models\SysUser\SysUserRole;
use backend\models\SysUser;
use common\utils\ResponseUtil;
use common\utils\Util;
use backend\controllers\BaseController;
use yii\data\Pagination;

/**
 * Class UserController 管理员管理
 * @package app\controllers\systems
 * @author Gene <https://github.com/Talkyunyun>
 */
class UserController extends BaseController {


    /**
     * 系统管理员列表
     * @return string
     */
    public function actionIndex() {
        $request = \Yii::$app->request;

        $status = $request->get('status', 'all');
        $email  = $request->get('email', false);
        $where  = 'status<>9';
        $bindParam = [];
        if ($status != 'all') {
            $where .= ' AND status=:status';
            $bindParam[':status'] = $status;
        }
        if (!empty($email)) {
            $where .= ' AND email like :email';
            $bindParam[':email'] = "%{$email}%";
        }
        $query = SysUser::find()->where($where, $bindParam);
        $total = $query->count('id');
        $page  = new Pagination([
            'pageSize'   => 20,
            'totalCount' =>$total
        ]);

        $data = $query
            ->offset($page->offset)
            ->limit($page->limit)
            ->orderBy('created desc')
            ->asArray()
            ->all();
        foreach ($data as $key=>$item) {
            $data[$key]['roles'] = SysUser::getUserRolesByUid($item['id']);
        }

        return $this->render($this->action->id, [
            'result'    => $data,
            'page'      => $page,
            'total'     => $total,
            'statusList'=> SysUser::getStatusList(),
            'status'    => $status,
            'email'     => $email
        ]);
    }

    // 添加
    public function actionCreate() {
        // 获取所有角色
        $roles = SysRole::find()
            ->select('id, name')
            ->where(['status' => 1])
            ->asArray()
            ->all();

        return $this->render($this->action->id, [
            'roles' => $roles
        ]);
    }

    // 修改
    public function actionUpdate() {
        $request = \Yii::$app->request;

        $id     = $request->get('id', 0);
        $result = SysUser::getDataById($id);
        if (empty($result)) {
            Util::alert('没有该用户信息');
        }

        // 获取所有角色
        $roles = SysRole::find()
            ->select('id, name')
            ->where(['status' => 1])
            ->asArray()
            ->all();

        // 获取用户角色
        $userRoles = SysUserRole::getUserRoleAll($id);

        return $this->render($this->action->id, [
            'result'    => $result,
            'roles'     => $roles,
            'userRoles' => $userRoles
        ]);
    }

    /**
     * 保存用户信息
     * @return array
     */
    public function actionSave() {
        $request = \Yii::$app->request;

        $db = \Yii::$app->db;
        $dbTrans = $db->beginTransaction();
        try {
            if (!$request->isPost) {
                throw new \Exception('非法访问', 1001);
            }

            $data     = $request->post();
            $password = $request->post('password', false);
            $id       = (int)$request->post('id', 0);
            if (empty($id)) {// 添加
                $model = new SysUser();
            } else {// 修改
                $model = SysUser::findOne($id);
                if (empty($model)) {
                    throw new \Exception('不存在该用户信息', 1002);
                }
            }
            // 检查是否是admin用户
            if (strtolower($model->email) == \Yii::$app->params['sys_user_email']) {
                unset($data['email']);
                unset($data['roles']);
                unset($data['status']);
            }
            $password = SysUser::getNewPassword($password);
            $model->setAttributes($data, false);
            $model->password = $password;
            $model->updated  = date('Y-m-d H:i:s');
            if (!$model->validate()) {
                throw new \Exception(Util::getModelError($model->errors), 1001);
            }
            if (!$model->save()) {
                throw new \Exception('保存失败', 1002);
            }
            $roles = $request->post('roles', false);
            // 1、删除旧角色
            SysUserRole::deleteAll('user_id=:uid', [':uid' => $model->id]);
            if (!empty($roles)) {
                $roles = explode(',', $roles);
                if (is_array($roles) && count($roles) > 0) {
                    // 2、添加新角色
                    $newRole = [];
                    foreach ($roles as $key=>$row) {
                        $newRole[$key][0] = $row;
                        $newRole[$key][1] = $model->id;
                    }
                    if (!$db->createCommand()
                        ->batchInsert(SysUserRole::tableName(), ['role_id', 'user_id'], $newRole)
                        ->execute()) {
                        throw new \Exception('保存失败', 1003);
                    }
                }
            }
            $dbTrans->commit();

            return ResponseUtil::success('保存成功');
        } catch (\Exception $e) {
            $dbTrans->rollBack();
            $msg = $e->getCode() == 0 ? '保存失败' : $e->getMessage();

            return ResponseUtil::error($e->getMessage());
        }
    }



    // 修改密码
    public function actionPassword() {
        $request = \Yii::$app->request;

        if ($request->isPost) {
            try {
                $oldPassword = $request->post('old_password', false);
                $newPassword = $request->post('new_password', false);
                $notPassword = $request->post('not_password', false);

                $adminm = SysUser::getCurrent();
                $isOld = \Yii::$app->security->validatePassword($oldPassword, $adminm->password);
                if (!$isOld) {
                    throw new \Exception('旧密码输入错误', 1000);
                }

                if (empty($newPassword) || $newPassword != $notPassword) {
                    throw new \Exception('确认密码输入不正确', 1001);
                }
                $adminm->password = SysUser::getNewPassword($newPassword);
                $adminm->updated = date('Y-m-d H:i:s');
                if ($adminm->save()) {
                    return ResponseUtil::success('修改成功');
                }

                throw new \Exception('修改失败', 1002);
            } catch (\Exception $e) {
                $msg = $e->getCode() == 0 ? '修改失败' : $e->getMessage();

                return ResponseUtil::error($msg);
            }
        }

        return $this->render('edit_password');
    }


    // TODO 查看个人信息
    public function actionView() {

        return $this->render('view_info');
    }
}