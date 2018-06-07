-- MySQL dump 10.13  Distrib 5.7.17, for linux-glibc2.5 (x86_64)
--
-- Host: localhost    Database: qhyl_bi
-- ------------------------------------------------------
-- Server version	5.7.17-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `qhyl_bi`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `qhyl_bi` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `qhyl_bi`;

--
-- Table structure for table `sys_access`
--

DROP TABLE IF EXISTS `sys_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_access` (
  `role_id` int(11) unsigned NOT NULL COMMENT '角色ID',
  `node_id` int(11) unsigned NOT NULL COMMENT '节点ID,sys_node中的ID',
  KEY `role_id` (`role_id`),
  KEY `node_id` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_access`
--

LOCK TABLES `sys_access` WRITE;
/*!40000 ALTER TABLE `sys_access` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_admin_user`
--

DROP TABLE IF EXISTS `sys_admin_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='系统用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_admin_user`
--

LOCK TABLES `sys_admin_user` WRITE;
/*!40000 ALTER TABLE `sys_admin_user` DISABLE KEYS */;
INSERT INTO `sys_admin_user` VALUES (1,'admin','$2y$13$BQnSOa4K0M3t/L.3ZaoKM.LAELaj2KDlIEqUPNMLwH5/M7Phj9H9a','admin','','admin@126.com','','',1,'backend','2017-09-14 23:09:18','2018-04-13 07:07:40');
/*!40000 ALTER TABLE `sys_admin_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_admin_user_login_log`
--

DROP TABLE IF EXISTS `sys_admin_user_login_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_admin_user_login_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL COMMENT '登录UID',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `data` text COMMENT '请求参数,json格式',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '请求Url地址',
  `client_name` varchar(60) NOT NULL DEFAULT '' COMMENT '客户端名称',
  `client_version` varchar(60) NOT NULL DEFAULT '' COMMENT '客户端版本',
  `client_platform` varchar(60) NOT NULL DEFAULT '' COMMENT '客户端系统',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登录时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='系统用户登录日志表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_admin_user_login_log`
--

LOCK TABLES `sys_admin_user_login_log` WRITE;
/*!40000 ALTER TABLE `sys_admin_user_login_log` DISABLE KEYS */;
INSERT INTO `sys_admin_user_login_log` VALUES (1,1,'192.168.0.109','{\"post\":{\"account\":\"admin\",\"password\":\"123456\",\"token\":\"W2YJc0ojRTuBHUdZ_GJZxcsasCa8xS62rxHTDNG2VAQyUVAKJHV3frJtcj63J2yfh1PIUtiXW_vOWJFklI4NKQ==\"},\"get\":[]}','http://bi.backend.com/login/do','Google Chrome','66.0.3359.181','MAC','2018-06-07 09:02:31');
/*!40000 ALTER TABLE `sys_admin_user_login_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_node`
--

DROP TABLE IF EXISTS `sys_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='操作节点表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_node`
--

LOCK TABLES `sys_node` WRITE;
/*!40000 ALTER TABLE `sys_node` DISABLE KEYS */;
INSERT INTO `sys_node` VALUES (1,0,'系统管理','#',1,1,1,0,0,'cog'),(2,1,'菜单管理','/system/node/index',1,1,1,0,0,''),(3,1,'角色管理','/system/role/index',1,1,1,0,0,''),(4,1,'系统用户','/system/user/index',1,1,1,0,0,'');
/*!40000 ALTER TABLE `sys_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_role`
--

DROP TABLE IF EXISTS `sys_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '角色名字',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态，1正常 0禁用',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='角色表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_role`
--

LOCK TABLES `sys_role` WRITE;
/*!40000 ALTER TABLE `sys_role` DISABLE KEYS */;
INSERT INTO `sys_role` VALUES (1,'系统管理员',1,'系统管理员');
/*!40000 ALTER TABLE `sys_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_role_user`
--

DROP TABLE IF EXISTS `sys_role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_role_user` (
  `role_id` int(11) unsigned DEFAULT '0' COMMENT '角色ID，对应sys_role表主键',
  `user_id` int(11) unsigned DEFAULT '0' COMMENT '用户ID',
  KEY `role_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统用户角色表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_role_user`
--

LOCK TABLES `sys_role_user` WRITE;
/*!40000 ALTER TABLE `sys_role_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_role_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-06-07 18:40:04
