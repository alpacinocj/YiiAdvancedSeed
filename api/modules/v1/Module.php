<?php
namespace api\modules\v1;

/**
 * 版本模块入口文件
 * @author Gene <https://github.com/Talkyunyun>
 */
class Module extends \yii\base\Module {

    // 指定模板布局文件
    public $layout = 'main';

    // 初始化
    public function init() {
        parent::init();

        // 加载对应模块配置文件
        $params = require_once __DIR__ . '/config/params.php';
        $config['params'] = array_merge($params, \Yii::$app->params);

        \Yii::configure(\Yii::$app, $config);
    }
}