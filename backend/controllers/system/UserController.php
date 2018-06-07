<?php

namespace backend\controllers\system;

use backend\models\AdminUser;
use backend\models\Role;
use backend\models\RoleUser;
use common\utils\ResponseUtil;
use common\utils\Util;
use backend\controllers\BaseController;
use yii\data\Pagination;

/**
 * Class UserController 管理员管理
 * @package app\controllers\systems
 */
class UserController extends BaseController
{

    /**
     * 管理员列表
     * @return string
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;

        $status     = $request->get('status', 'all');
        $userName   = $request->get('username', false);
        $phone      = $request->get('phone', false);
        $email      = $request->get('email', false);
        $dateType   = $request->get('dateType', false);
        $startDate  = $request->get('start_date', false);
        $endDate    = $request->get('end_date', false);

        $where = '';
        $bindParam = [];
        if ($status != 'all') {
            $where .= ' AND status=:status';
            $bindParam[':status'] = $status;
        }
        if (!empty($userName)) {
            $where .= ' AND username like :username';
            $bindParam[':username'] = "%{$userName}%";
        }
        if (!empty($phone)) {
            $where .= ' AND phone=:phone';
            $bindParam[':phone'] = $phone;
        }
        if (!empty($email)) {
            $where .= ' AND email like :email';
            $bindParam[':email'] = "%{$email}%";
        }

        // 处理时间筛选
        if (!empty($dateType)) {
            if ($dateType == -1) {
                $beginTime = strtotime($startDate);
                $endTime = strtotime($endDate);
            } else {
                $day = $dateType == 1 ? 0 : $dateType;
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - $day, date('Y'));
                $endTime = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
            }

            $where .= ' AND created>=:beginTime AND created<=:endTime';
            $bindParam[':beginTime'] = $beginTime;
            $bindParam[':endTime'] = $endTime;
        }

        $query = AdminUser::find()
        ->where($where, $bindParam);

        $total = $query->count();
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

        foreach ($data as $key=>$row) {
            $data[$key]['roles'] = AdminUser::getUserRolesByUid($row['id']);
        }

        return $this->render($this->action->id, [
            'result' => $data,
            'page' => $page,
            'statusList' => AdminUser::getStatusList(),
            'status'    => $status,
            'username'  => $userName,
            'phone'    => $phone,
            'email'     => $email,
            'dateType'  => $dateType,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]);
    }


    // 添加
    public function actionCreate()
    {
        // 获取所有角色
        $roles = Role::find()
            ->select('id, name')
            ->where(['status' => 1])
            ->asArray()
            ->all();

        return $this->render($this->action->id, [
            'roles' => $roles
        ]);
    }

    // 修改
    public function actionUpdate()
    {
        $request = \Yii::$app->request;

        $id     = $request->get('id', 0);
        $result = AdminUser::getDataById($id);
        if (empty($result)) {
            Util::alert('没有该用户信息');
        }

        // 获取所有角色
        $roles = Role::find()->select('id, name')
            ->where(['status' => 1])
            ->all();

        // 获取用户角色
        $userRoles = RoleUser::getUserRoleAll($id);

        return $this->render($this->action->id, [
            'result'    => $result,
            'roles'     => $roles,
            'userRoles' => $userRoles
        ]);
    }


    /**
     * 保存用户信息
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionSave()
    {
        $request = \Yii::$app->request;

        $db      = \Yii::$app->db;
        $dbTrans = $db->beginTransaction();
        try {
            if (!$request->isPost) {
                throw new \Exception('非法访问', 1001);
            }

            $isPassword = false;
            $data       = $request->post();
            $password   = $request->post('password', false);
            $id         = $request->post('id', 0);
            if (empty($id)) {// 添加
                $model      = new AdminUser();
                $isPassword = true;

                $model->setScenario('create');
            } else {// 修改
                $model = AdminUser::findOne($id);
                if (empty($model)) {
                    throw new \Exception('不存在该用户信息', 1002);
                }
                if (!empty($password)) $isPassword = true;

                $model->setScenario('update');
            }

            // 检查是否是admin用户
            if ($model->username == ROOT_USER) {
                unset($data['username']);
                unset($data['roles']);
                unset($data['status']);
            }

            $model->attributes = $data;
            if (!empty($isPassword)) {
                $model->password = AdminUser::getNewPassword($password);
            }
            $model->updated    = date('Y-m-d');
            if (!$model->validate()) {
                throw new \Exception(Util::getModelError($model->errors), 1001);
            }

            if (!$model->save()) {
                throw new \Exception('保存失败', 1002);
            }
            $roles = $request->post('roles', false);
            if (!empty($roles)) {
                $roles = explode(',', $roles);
                if (is_array($roles) && count($roles) > 0) {
                    // 1、删除旧角色
                    RoleUser::deleteAll('user_id=:uid', [':uid' => $model->id]);

                    // 2、添加新角色
                    $newRole = [];
                    foreach ($roles as $key=>$row) {
                        $newRole[$key][0] = $row;
                        $newRole[$key][1] = $model->id;
                    }
                    if (!$db->createCommand()
                        ->batchInsert(RoleUser::tableName(), ['role_id', 'user_id'], $newRole)
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

            return ResponseUtil::error($msg);
        }
    }



    // 修改密码
    public function actionPassword()
    {
        $request = \Yii::$app->request;

        if ($request->isPost) {
            try {
                $oldPassword = $request->post('old_password', false);
                $newPassword = $request->post('new_password', false);
                $notPassword = $request->post('not_password', false);

                $adminm = AdminUser::getCurrent();
                $isOld = \Yii::$app->security->validatePassword($oldPassword, $adminm->password);
                if (!$isOld) {
                    throw new \Exception('旧密码输入错误', 1000);
                }

                if (empty($newPassword) || $newPassword != $notPassword) {
                    throw new \Exception('确认密码输入不正确', 1001);
                }
                $adminm->setScenario('update_password');
                $adminm->password = AdminUser::getNewPassword($newPassword);
                $adminm->updated = date('Y-m-d H:i:s');
                if ($adminm->save()) {
                    return ResponseUtil::success('修改成功');
                }

                throw new \Exception('修改失败', 1002);
            } catch (\Exception $e) {
                $msg = $e->getCode() == 0 ? '修改失败' : $e->getMessage();

                return ResponseUtil::error($e->getMessage());
            }
        }

        return $this->render('edit_password');
    }


    // TODO 查看个人信息
    public function actionView()
    {
        return $this->render('view_info');
    }
}