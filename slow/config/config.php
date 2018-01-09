<?php
/**
 * 小程序接口项目配置文件
 * @author Gene <https://github.com/Talkyunyun>
 */

$config = [
    'id'        => 'app-slow-api',
    'basePath'  => dirname(__DIR__),
    'charset'   => 'utf-8',
    'language'  => 'zh-CN',
    'timeZone'  => 'Asia/Shanghai',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'slow\controllers',

    // 公共组件
    'components' => [
        'request' => [
            'csrfParam' => 'token',
            'cookieValidationKey' => '37db8804c94bae1d0e198c754022e9a2552cc6fa'
        ],
        'redisCache' => [
            'class' => 'yii\redis\Cache',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass'   => 'slow\models\Clients',
            'enableAutoLogin' => false,
            'enableSession'   => false,
            'loginUrl'        => null
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules' => require_once __DIR__ . '/routes.php'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class'   => 'yii\log\FileTarget',
                    'levels'  => ['error', 'warning', 'trace' ,'info'],
                    'logVars' => [],
                    'logFile' => '@slow/runtime/logs/run_'.date('Y-m-d').'.log'
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


// 只有正式环境才会显示友好的错误页面
if (!YII_DEBUG) {
    $config['components']['errorHandler'] = [
        'errorAction' => 'error/show'
    ];
}


return $config;