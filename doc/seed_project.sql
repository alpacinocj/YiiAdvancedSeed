# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 192.168.0.210 (MySQL 5.7.17-log)
# Database: qhyl_bi
# Generation Time: 2018-06-08 02:13:22 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table sys_access
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_access`;

CREATE TABLE `sys_access` (
  `role_id` int(11) unsigned NOT NULL COMMENT '角色ID',
  `node_id` int(11) unsigned NOT NULL COMMENT '节点ID,sys_node中的ID',
  KEY `role_id` (`role_id`),
  KEY `node_id` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

LOCK TABLES `sys_access` WRITE;
/*!40000 ALTER TABLE `sys_access` DISABLE KEYS */;

INSERT INTO `sys_access` (`role_id`, `node_id`)
VALUES
	(2,2);

/*!40000 ALTER TABLE `sys_access` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sys_admin_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_admin_user`;

CREATE TABLE `sys_admin_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户UID',
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `password` char(60) NOT NULL COMMENT '登录密码',
  `real_name` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `phone` varchar(18) NOT NULL DEFAULT '' COMMENT '联系号码',
  `email` varchar(30) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `auth_key` varchar(60) NOT NULL DEFAULT '' COMMENT 'auth_key',
  `access_token` varchar(60) NOT NULL DEFAULT '' COMMENT 'access_token',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，0：禁用，1：正常',
  `tag` varchar(10) NOT NULL DEFAULT 'backend' COMMENT '用户标签',
  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统用户表';

LOCK TABLES `sys_admin_user` WRITE;
/*!40000 ALTER TABLE `sys_admin_user` DISABLE KEYS */;

INSERT INTO `sys_admin_user` (`id`, `username`, `password`, `real_name`, `phone`, `email`, `auth_key`, `access_token`, `status`, `tag`, `created`, `updated`)
VALUES
	(1,'admin','$2y$13$BQnSOa4K0M3t/L.3ZaoKM.LAELaj2KDlIEqUPNMLwH5/M7Phj9H9a','admin','','admin@126.com','','',1,'backend',0,0),
	(3,'jack','$2y$13$4DLzprNhuZfyXJ6OzPsYou8s7a5q9wt4sV.wpG3wlCF7tMsqPqrwa','啊啊','','alsjfls@qq.com','','',1,'backend',1528423386,1528423386);

/*!40000 ALTER TABLE `sys_admin_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sys_admin_user_login_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_admin_user_login_log`;

CREATE TABLE `sys_admin_user_login_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL COMMENT '登录UID',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `data` text COMMENT '请求参数,json格式',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '请求Url地址',
  `client_name` varchar(60) NOT NULL DEFAULT '' COMMENT '客户端名称',
  `client_version` varchar(60) NOT NULL DEFAULT '' COMMENT '客户端版本',
  `platform` varchar(60) NOT NULL DEFAULT '' COMMENT '客户端系统',
  `created` int(11) NOT NULL DEFAULT '0' COMMENT '登录时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='系统用户登录日志表';

LOCK TABLES `sys_admin_user_login_log` WRITE;
/*!40000 ALTER TABLE `sys_admin_user_login_log` DISABLE KEYS */;

INSERT INTO `sys_admin_user_login_log` (`id`, `uid`, `ip`, `data`, `url`, `client_name`, `client_version`, `platform`, `created`)
VALUES
	(2,1,'192.168.0.112','{\"post\":{\"username\":\"admin\",\"token\":\"-gJYkNB5C64ja0gBH9kLXa2Gg7inyontGKqdWxzlNAyTNQHpvi856xAbfWZUnD4H4c_7zMOY_KB5498zWd1tIQ==\"},\"get\":[]}','http://bi.backend.com/login/do','Google Chrome','66.0.3359.181','MAC',1528422102);

/*!40000 ALTER TABLE `sys_admin_user_login_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sys_node
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_node`;

CREATE TABLE `sys_node` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID,0:顶级节点',
  `name` varchar(100) NOT NULL COMMENT '操作名称，或菜单名',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'url地址',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态,1正常  0禁用',
  `is_menu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是菜单，0：否，1：是',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `can_del` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可以删除，0：不可以，1：可以',
  `sort` int(11) unsigned DEFAULT '0' COMMENT '排序',
  `font_icon` varchar(100) DEFAULT '' COMMENT '菜单字体图片',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `is_menu` (`is_menu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='操作节点表';

LOCK TABLES `sys_node` WRITE;
/*!40000 ALTER TABLE `sys_node` DISABLE KEYS */;

INSERT INTO `sys_node` (`id`, `pid`, `name`, `url`, `status`, `is_menu`, `level`, `can_del`, `sort`, `font_icon`)
VALUES
	(1,0,'系统管理','#',1,1,1,0,0,'cog'),
	(2,1,'菜单管理','/system/node/index',1,1,1,0,0,''),
	(3,1,'角色管理','/system/role/index',1,1,1,0,0,''),
	(4,1,'系统用户','/system/user/index',1,1,1,0,0,'');

/*!40000 ALTER TABLE `sys_node` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sys_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_role`;

CREATE TABLE `sys_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '角色名字',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态，1正常 0禁用',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色表';

LOCK TABLES `sys_role` WRITE;
/*!40000 ALTER TABLE `sys_role` DISABLE KEYS */;

INSERT INTO `sys_role` (`id`, `name`, `status`, `remark`)
VALUES
	(1,'系统管理员',1,'系统管理员'),
	(2,'测试',1,'');

/*!40000 ALTER TABLE `sys_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sys_role_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_role_user`;

CREATE TABLE `sys_role_user` (
  `role_id` int(11) unsigned DEFAULT '0' COMMENT '角色ID，对应sys_role表主键',
  `user_id` int(11) unsigned DEFAULT '0' COMMENT '用户ID',
  KEY `role_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统用户角色表';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
