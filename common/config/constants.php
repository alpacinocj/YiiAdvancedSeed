<?php
/**
 * 项目常量配置定义
 * @authro Gene <https://github.com/Talkyunyun>
 */

// 运行环境定义
define('ENV_LOCAL', 'local');       // 本地环境
define('ENV_DEV', 'dev');           // 开发环境
define('ENV_TEST', 'test');         // 测试环境
define('ENV_PREV', 'prev');         // 预发布环境
define('ENV_PRD', 'prod');          // 生产环境

// 总项目根路径
define('ROOT_PATH', dirname(dirname(__DIR__)));