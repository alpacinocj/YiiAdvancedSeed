<?php
/**
 * 命令模块配置文件
 * @author Gene <https://github.com/Talkyunyun>
 */

$config = [
    'id'        => 'app-console',
    'basePath'  => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language'  => 'zh-CN',
    'charset'   => 'UTF-8',
    'timeZone'  => 'Asia/Shanghai',
    'controllerNamespace' => 'console\controllers',

    // 公共组件
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class'   => 'yii\log\FileTarget',
                    'levels'  => ['error', 'warning', 'trace' ,'info'],
                    'logVars' => [],
                    'logFile' => '@console/runtime/run_'.date('Y-m-d').'.log'
                ]
            ]
        ]
    ]
];


// 合并params参数配置
$config['params'] = \yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

// 加载对应环境配置文件
$envConfig = require_once __DIR__ . '/../../common/config/config-' . YII_ENV . '.php';

// 合并配置
$config['params']     = array_merge($config['params'], $envConfig['params']);
$config['components'] = array_merge($config['components'], $envConfig['components']);

return $config;