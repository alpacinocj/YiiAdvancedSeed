<?php
/**
 * 后台应用配置文件
 * @author Gene <https://github.com/Talkyunyun>
 */

$config = [
    'id'        => 'app-backend',
    'basePath'  => dirname(__DIR__),
    'charset'   => 'utf-8',
    'language'  => 'zh-CN',
    'timeZone'  => 'Asia/Shanghai',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'backend\controllers',

    // 公共组件
    'components' => [
        'request' => [
            'csrfParam' => 'token',
            'cookieValidationKey' => 'sa2g5UDQPsDd3ImHgXwAa3yrFP6OHovOd3gq5RM'
        ],
        'redisCache' => [
            'class' => 'yii\redis\Cache',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass'   => 'backend\models\AdminUser',
            'enableAutoLogin' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules' => require_once __DIR__ . '/routes.php'
        ],
        'view' => [
            'class' => 'yii\web\View',
            'defaultExtension' => 'twig',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/twig/cache',
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'globals' => [
                        'html' => ['class' => \yii\helpers\Html::class],
                        'url' => ['class' => \yii\helpers\Url::class],
                        'yii' => ['class' => \Yii::class],
                        'linkPagerWidget' => ['class' => backend\components\LinkPagerWidget::class],
                    ],
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'logVars' => [],
                    'categories' => ['user'],
                    'logFile' => '@backend/runtime/logs/user_' . date('Ymd') . '.log',
                ],
                // exception category log
                [
                    'class' => 'yii\log\FileTarget',
                    'logVars' => [],
                    'categories' => ['exception'],
                    'logFile' => '@backend/runtime/logs/exception_' . date('Ymd') . '.log',
                ],
                // exception category log
                [
                    'class' => 'yii\log\FileTarget',
                    'logVars' => [],
                    'categories' => ['event'],
                    'logFile' => '@backend/runtime/logs/event_' . date('Ymd') . '.log',
                ],
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


// 只有正式环境才会显示友好的错误页面
if (!YII_DEBUG) {
    $config['components']['errorHandler'] = [
        'errorAction' => 'error/show'
    ];
}


return $config;