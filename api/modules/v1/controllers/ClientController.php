<?php
namespace api\modules\v1\controllers;

use api\controllers\BaseController;

/**
 * 客户端接口
 * Class ClientController
 * @package api\modules\v1\controllers
 */
class ClientController extends BaseController {

    public $modelClass = 'common\models\Clients';

    public function actionIndex() {

        return [];
    }


}