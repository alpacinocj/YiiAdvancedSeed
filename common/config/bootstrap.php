<?php
/**
 * APP启动最先执行程序
 * @author Gene <https://github.com/Talkyunyun>
 */

// 加载常量配置文件
require_once __DIR__ . '/constants.php';

// 设置运行环境，控制所有模块
defined('YII_ENV') or define('YII_ENV', ENV_LOCAL);

// 设置本地开发配置
if (
    YII_ENV == ENV_LOCAL &&
    file_exists(__DIR__ . '/config-example.data') &&
    !file_exists(__DIR__ . '/config-local.php')
) {
    $cnf = file_get_contents(__DIR__ . '/config-example.data');
    file_put_contents(__DIR__ . '/config-local.php', $cnf);
}

// 只有正式环境debug关闭，其他默认打开
defined('YII_DEBUG') or define('YII_DEBUG', YII_ENV == ENV_PROD ? false : true);

function dd($data = []) {
    var_dump($data);die;
}

function appAlias() {
    // 公共模块
    Yii::setAlias('@common', dirname(__DIR__));

    // 后台
    Yii::setAlias('@backend', ROOT_PATH . '/backend');

    // 前台
    Yii::setAlias('@website', ROOT_PATH . '/website');

    // 客户端项目接口开发
    Yii::setAlias('@api', ROOT_PATH . '/api');

    // 命令模式
    Yii::setAlias('@console', ROOT_PATH . '/console');

    // 小程序项目接口开发
    Yii::setAlias('@slow', ROOT_PATH . '/slow');
}