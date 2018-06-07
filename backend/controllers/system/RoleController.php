<?php

namespace backend\controllers\system;

use backend\models\Access;
use backend\models\Role;
use backend\models\RoleUser;
use common\utils\ResponseUtil;
use common\utils\Util;
use backend\controllers\BaseController;
use yii\data\Pagination;

/**
 * 角色管理
 * Class RoleController
 * @package app\controllers\systems
 */
class RoleController extends BaseController
{

    // 列表
    public function actionIndex()
    {
        $request = \Yii::$app->request;

        $name  = $request->get('name', false);
        $where = '1=1';
        $bindParam = [];
        if (!empty($name)) {
            $where .= ' AND name like :name';
            $bindParam[':name'] = "%{$name}%";
        }
        $query = Role::find()->where($where, $bindParam);

        $total = $query->count();
        $page = new Pagination([
            'pageSize'   => 20,
            'totalCount' => $total
        ]);

        $result = $query
            ->offset($page->offset)
            ->limit($page->limit)
            ->orderBy('id desc')
            ->all();

        return $this->render('index', [
            'result'    => $result,
            'page'      => $page,
            'name'      => $name
        ]);
    }


    // 删除
    public function actionDel()
    {
        $request = \Yii::$app->request;

        try {
            if (!$request->isPost) {
                throw new \Exception('没有发现该页面', 1000);
            }

            $id = (int)$request->post('id', 0);
            if (empty($id)) {
                throw new \Exception('请选择需要删除的角色', 1001);
            }
            // 删除角色
            Role::deleteAll('id=:id', [':id' => $id]);

            // 删除角色权限
            Access::deleteAll('role_id=:role_id', [':role_id'=>$id]);

            // 删除用户角色
            RoleUser::deleteAll('role_id=:role_id', [':role_id'=>$id]);

            return ResponseUtil::success('删除成功');
        } catch (\Exception $e) {
            $msg = $e->getCode() == 0 ? '删除失败' : $e->getMessage();

            return ResponseUtil::error($msg);
        }
    }


    // 修改信息
    public function actionUpdate()
    {
        $request = \Yii::$app->request;
        $id = $request->get('id', 0);

        $result = Role::find()->where([
            'id' => $id
        ])->one();
        if (empty($result)) {
            Util::alert('没有该角色信息');
        }

        return $this->render('update', [
            'result' => $result
        ]);
    }

    // 添加
    public function actionCreate()
    {

        return $this->render('create');
    }

    // 保存
    public function actionSave()
    {
        $request = \Yii::$app->request;

        $dbTrans = \Yii::$app->db->beginTransaction();
        try {
            if (!$request->isPost) {
                throw new \Exception('非法访问', 1001);
            }
            $data = $request->post();
            $id = (int)$data['id'];
            if (empty($id)) {// 添加
                $model = new Role();
            } else {// 修改
                $model = Role::findOne($id);
            }
            $model->attributes = $data;
            $model->remark = $data['remark'];
            $model->status = (int)$data['status'];
            if (!$model->validate()) {
                throw new \Exception(Util::getModelError($model->errors), 1001);
            }

            if ($model->save()) {
                if (!empty($data['nodes'])) {
                    $nodes = explode(',', $data['nodes']);
                    if (!is_array($nodes) || count($nodes) < 1) {
                        throw new \Exception('保存失败', 1002);
                    }
                    $roleId = $model->getAttribute('id');
                    // 1.删除旧权限
                    Access::deleteAll('role_id=:role_id', [':role_id' => $roleId]);

                    // 2.添加新权限
                    $newNode = [];
                    foreach ($nodes as $key => $nodeId) {
                        $newNode[$key][0] = $roleId;
                        $newNode[$key][1] = $nodeId;
                    }
                    \Yii::$app->db->createCommand()
                        ->batchInsert(Access::tableName(), ['role_id', 'node_id'], $newNode)
                        ->execute();
                }
                $dbTrans->commit();

                return ResponseUtil::success('保存成功');
            }

            throw new \Exception('保存失败', 1001);
        } catch (\Exception $e) {
            $dbTrans->rollBack();
            $msg = $e->getCode() == 0 ? '保存失败' : $e->getMessage();

            return ResponseUtil::error($msg);
        }
    }

    // 开关
    public function actionOnOff()
    {
        $request = \Yii::$app->request;

        try {
            if (!$request->isPost) throw new \Exception('非法访问', 1001);

            $id = $request->post('id', false);
            $model = Role::findOne($id);
            if (empty($model)) {
                throw new \Exception('不存在该记录信息', 1001);
            }
            if ($model->status == 1) {
                $status = 0;
            } else {
                $status = 1;
            }

            $model->status = $status;
            if ($model->save()) {
                return ResponseUtil::success(1);
            }

            throw new \Exception('删除失败', 1001);
        } catch (\Exception $e) {
            $msg = $e->getCode() == 0 ? '操作失败' : $e->getMessage();

            return ResponseUtil::error($msg);
        }
    }
}