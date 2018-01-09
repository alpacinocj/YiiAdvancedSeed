<?php
namespace backend\controllers\system;

use common\utils\Util;
use yii\data\Pagination;
use common\utils\ResponseUtil;
use backend\models\SysUser\SysRole;
use backend\models\SysUser\SysUserRole;
use backend\controllers\BaseController;
use backend\models\SysUser\SysPermission;

/**
 * 角色管理
 * Class RoleController
 * @package app\controllers\systems
 * @author Gene <https://github.com/Talkyunyun>
 */
class RoleController extends BaseController {

    // 列表
    public function actionIndex() {
        $request = \Yii::$app->request;

        $name  = $request->get('name', false);
        $where = '1=1';
        $bindParam = [];
        if (!empty($name)) {
            $where .= ' AND name like :name';
            $bindParam[':name'] = "%{$name}%";
        }
        $query = SysRole::find()->where($where, $bindParam);

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
            'total'     => $total,
            'name'      => $name,
            'statusList'=> SysRole::getStatusList()
        ]);
    }


    // 删除
    public function actionDel() {
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
            SysRole::deleteAll('id=:id', [':id' => $id]);

            // 删除角色权限
            SysPermission::deleteAll('role_id=:role_id', [':role_id'=>$id]);

            // 删除用户角色
            SysUserRole::deleteAll('role_id=:role_id', [':role_id'=>$id]);

            return ResponseUtil::success('删除成功');
        } catch (\Exception $e) {
            $msg = $e->getCode() == 0 ? '删除失败' : $e->getMessage();

            return ResponseUtil::error($msg);
        }
    }


    // 修改信息
    public function actionUpdate() {
        $request = \Yii::$app->request;
        $id = $request->get('id', 0);

        $result = SysRole::find()->where([
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
    public function actionCreate() {

        return $this->render('create');
    }

    // 保存
    public function actionSave() {
        $request = \Yii::$app->request;

        $db = \Yii::$app->db;
        $dbTrans = $db->beginTransaction();
        try {
            if (!$request->isPost) {
                throw new \Exception('非法访问', 1001);
            }
            $data = $request->post();
            $id   = $request->post('id', 0);
            $nodes= $request->post('nodes', false);
            if (empty($id)) {// 添加
                $model = new SysRole();
            } else {// 修改
                $model = SysRole::findOne($id);
                if (empty($model)) {
                    throw new \Exception('不存在该角色信息', 1001);
                }
            }
            $model->setAttributes($data, false);
            if (!$model->validate()) {
                throw new \Exception(Util::getModelError($model->errors), 1001);
            }
            if ($model->save()) {
                if (!empty($nodes)) {
                    $nodes = explode(',', $nodes);
                    if (!is_array($nodes) || count($nodes) < 1) {
                        throw new \Exception('保存失败', 1002);
                    }
                    // 1.删除旧权限
                    SysPermission::deleteAll('role_id=:role_id', [':role_id' => $model->id]);

                    // 2.添加新权限
                    $newNode = [];
                    foreach ($nodes as $key => $nodeId) {
                        $newNode[$key][0] = $model->id;
                        $newNode[$key][1] = $nodeId;
                    }
                    $db->createCommand()
                        ->batchInsert(SysPermission::tableName(), ['role_id', 'node_id'], $newNode)
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

}