<?php
namespace api\modules\v1\controllers;


use api\controllers\BaseController;
use common\models\Goods;
use common\utils\ResponseUtil;
use yii\data\Pagination;

/**
 * rest案例
 * Class GoodsController
 * @package api\modules\v1\controllers
 */
class GoodsController extends BaseController {

    // 指定资源
    public $modelClass = true; //'api\modules\v1\models\Goods';


    /**
     * GET /demos 获取全部数据
     * @return array
     */
    public function actionIndex() {
        $request = \Yii::$app->request;

        // 分页获取
        $page  = $request->get('page', 1);
        $query = Goods::find();
        $total = $query->count('id');
        $pagem = new Pagination([
            'totalCount' => $total,
            'pageSize'   => 10
        ]);
        $pagem->setPage($page - 1);

        $result = $query
            ->offset($pagem->offset)
            ->limit($pagem->limit)
            ->asArray()
            ->all();

        $data['page']  = $page;
        $data['total'] = $total;
        $data['data']  = $result;

        return ResponseUtil::success($data);
    }

    /**
     * GET /demos/id 获取对应ID数据信息
     * @return int
     */
    public function actionView() {

        $model = new Goods();
        $model->title = 'sdf';
        dd($model);




        $request = \Yii::$app->request;

        $id = (int)$request->get('id', 0);
        if (empty($id)) {
            return ResponseUtil::error('请输入需要查询的记录ID', 1000);
        }
        $result = Goods::find()->where(['id' => $id])->asArray()->one();

        return ResponseUtil::success($result);
    }

    /**
     * POST /demos 创建一条记录
     * @return array
     */
    public function actionCreate() {
        $request = \Yii::$app->request;

        $now = date('Y-m-d H:i:s');

        $model = new Goods();
        $model->title = 'sdfs';
        dd($model);
        $model->title = 'ffff';
        dd($model);

        $model->attributes = $request->post();
        dd($model);


        $model->attributes = $request->post();
        $model->created = $now;
        $model->updated = $now;
        if (!$model->validate()) {
            return $model->errors;
        }

        if ($model->save()) {
            return ResponseUtil::success('保存成功');
        }

        return ResponseUtil::error('保存失败');
    }

    /**
     * PUT|PATCH /demos/id 更新对应ID记录信息
     * @return int
     */
    public function actionUpdate() {
        return 2222;
    }


    /**
     * DELETE /demos/id 删除对应ID记录
     * @return int
     */
    public function actionDelete() {
        return 3;
    }
}