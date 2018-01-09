<?php
namespace backend\controllers\system;


use backend\models\SysUser\SysNode;
use backend\models\SysUser\SysPermission;
use common\utils\ResponseUtil;
use common\utils\Util;
use backend\controllers\BaseController;

/**
 * 节点管理
 * Class NodeController
 * @package app\controllers\systems
 * @author Gene <https://github.com/Talkyunyun>
 */
class NodeController extends BaseController {

    // 列表
    public function actionIndex() {

        return $this->render('index');
    }

    // 获取全部节点列表
    public function actionGetData() {
        $request = \Yii::$app->request;

        try {
            $roleNodes= [];
            $roleId   = (int)$request->post('role_id', 0);
            if (!empty($roleId)) {
                $roleNode = SysPermission::find()
                    ->where(['role_id' => $roleId])
                    ->asArray()->all();
                foreach ($roleNode as $item) {
                    array_push($roleNodes, $item['node_id']);
                }
            }

            return ResponseUtil::success(SysNode::getTreeMenu(0, $roleNodes));
        } catch (\Exception $e) {
            $msg = $e->getCode() == 0 ? '获取失败' : $e->getMessage();

            return ResponseUtil::error($msg);
        }
    }

    // 根据ID获取信息
    public function actionGetInfo() {
        $request = \Yii::$app->request;

        try {
            $id = $request->post('id', 0);
            $result = SysNode::getDataById($id);

            return ResponseUtil::success($result);
        } catch (\Exception $e) {
            $msg = $e->getCode() == 0 ? '获取失败' : $e->getMessage();

            return ResponseUtil::error($msg);
        }
    }

    // 删除
    public function actionDel() {
        $request = \Yii::$app->request;

        try {
            if (!$request->isPost) {
                throw new \Exception('非法访问', 1000);
            }

            $id = $request->post('id', 0);
            if (empty($id)) {
                throw new \Exception('请选择需要删除的节点', 1001);
            }
            $model = SysNode::find()
                ->where([
                    'id'      => $id,
                    'can_del' => 1
                ])->asArray()->one();
            if (empty($model)) {
                throw new \Exception('该节点或者菜单不允许删除', 1002);
            }
            $ids   = SysNode::getMenuAllChildById($id);
            $ids[] = $id;

            # 1. 删除当前分类对应的所有子分类
            # 2. 删除在menu_id 对应到权限中的所有menu_id
            foreach ($ids as $id) {
                SysNode::deleteAll('can_del=1 AND id=:id', [
                    ':id' => $id
                ]);
                SysPermission::deleteAll('node_id=:id', [
                    ':id' => $id
                ]);
            }

            return ResponseUtil::success('删除成功');
        } catch (\Exception $e) {
            $msg = $e->getCode() == 0 ? '删除失败' : $e->getMessage();

            return ResponseUtil::error($msg);
        }
    }

    // 添加
    public function actionCreate() {
        $request = \Yii::$app->request;
        $pid = $request->get('pid', 0);

        return $this->render('create', [
            'pid' => $pid
        ]);
    }

    // 保存
    public function actionSave() {
        $request = \Yii::$app->request;

        try {
            if (!$request->isPost) {
                throw new \Exception('非法访问', 1001);
            }
            $data = $request->post();
            $id   = $request->post('id');
            if (empty($id)) {// 添加
                $model = new SysNode();
                $model->setScenario('create');
            } else {// 修改
                $model = SysNode::findOne($id);
                $model->setScenario('update');
            }

            $model->setAttributes($data);
            if (!$model->validate()) {
                throw new \Exception(Util::getModelError($model->errors), 1003);
            }
            if (!$model->save()) {
                throw new \Exception('保存失败', 1003);
            }

            return ResponseUtil::success('保存成功');
        } catch (\Exception $e) {
            $msg = $e->getCode() == 0 ? '保存失败' : $e->getMessage();

            return ResponseUtil::error($msg);
        }
    }
}