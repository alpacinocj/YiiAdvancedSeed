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
define('ENV_PROD', 'prod');         // 生产环境

// 总项目根路径
define('ROOT_PATH', dirname(dirname(__DIR__)));

// 定义超级用户和超级角色
defined('ROOT_USER') or define('ROOT_USER', 'admin');
defined('ROOT_USER_ROLE') or define('ROOT_USER_ROLE', '超级管理员');