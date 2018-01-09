<?php
/**
 * API项目总配置文件
 * @author Gene <https://github.com/Talkyunyun>
 */

$config = [
    'id'            => 'api-app',
    'basePath'      => dirname(__DIR__),
    'charset'       => 'utf-8',
    'language'      => 'zh-CN',
    'timeZone'      => 'Asia/Shanghai',
    'bootstrap'     => ['log'],

    // 版本模块列表
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module'
        ]
    ],

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
            'identityClass'   => 'common\models\Clients',
            'enableAutoLogin' => false,
            'enableSession'   => false,
            'loginUrl'        => null
        ],
        'urlManager' => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => true,
            'showScriptName'      => false,
            'rules' => require_once __DIR__ . '/routes.php'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class'   => 'yii\log\FileTarget',
                    'levels'  => ['error', 'warning', 'trace' ,'info'],
                    'logVars' => [],
                    'logFile' => '@api/runtime/logs/run_'.date('Y-m-d').'.log'
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