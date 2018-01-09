-- ================================== --
-- ========= 项目基本表结构 ========== --
-- ================================== --

-- 数据库名称 注意utf8mb4编码
-- CREATE DATABASE seed_project;


-- 系统用户表
CREATE TABLE sys_users (
    id int(11) unsigned PRIMARY KEY AUTO_INCREMENT comment '用户UID',
    email varchar(50) not null comment '登录邮箱',
    password char(60) not null comment '登录密码',
    real_name varchar(20) not null default '' comment '真实姓名',
    phone varchar(18) not null default '' comment '联系号码',
    auth_key varchar(60) not null default '' comment 'auth_key',
    access_token varchar(60) not null default '' comment 'access_token',
    status tinyint(1) unsigned not null default 1 comment '状态，0：禁用，1：正常',
    birth_date DATE DEFAULT NULL comment '生日日期',
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP comment '创建时间',
    updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP comment '更新时间',
    UNIQUE KEY email (email),
    key status(status)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COMMENT '系统用户表';

-- 插入admin@126.com用户密码123456
INSERT INTO `sys_users` (`id`, `email`, `password`, `real_name`, `phone`, `auth_key`, `access_token`, `status`, `birth_date`, `created`, `updated`) VALUES
	(1, 'admin@126.com', '$2y$13$2TY3rdo.Y3jUoZ6O3STC4OAWDFux1Q3h5yzRqDpLYJQSjmTxt6qxK','admin','','','',1,'2017-09-30','2017-09-15 15:09:18','2017-09-15 20:09:42');


-- 系统用户登录日志表
CREATE TABLE sys_users_login_logs (
    id bigint unsigned PRIMARY KEY AUTO_INCREMENT,
    uid int(11) unsigned not null comment '登录UID',
    ip varchar(15) not null default '' comment '登录IP',
    data text comment '请求参数,json格式',
    url varchar(255) not null default '' comment '请求Url地址',
    client_name varchar(60) not null default '' comment '客户端名称',
    client_version varchar(60) not null default '' comment '客户端版本',
    client_platform varchar(60) not null default '' comment '客户端系统',
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP comment '登录时间',
    key uid (uid)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 comment '系统用户登录日志表';


-- 权限表
CREATE TABLE sys_permission (
    role_id int(11) unsigned not null comment '角色ID',
    node_id int(11) unsigned not null comment '节点ID,sys_node中的ID',
    KEY role_id (role_id),
    KEY node_id (node_id)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT '权限表';

-- 操作节点表
CREATE TABLE sys_node (
    id int(11) unsigned PRIMARY KEY AUTO_INCREMENT,
    pid int(11) unsigned not null default 0 comment '父级ID,0:顶级节点',
    name varchar(100) not null comment '操作名称，或菜单名',
    url varchar(255) not null default '' comment 'url地址',
    status tinyint(1) unsigned not null default 1 comment '状态,1正常  0禁用',
    is_menu tinyint(1) unsigned not null default 0 comment '是否是菜单，0：否，1：是',
    level tinyint(1) unsigned not null default 1 comment '等级',
    can_del tinyint(1) unsigned not null default 1 comment '是否可以删除，0：不可以，1：可以',
    sort int(11) unsigned null default 0 comment '排序',
    font_icon varchar(100) null default '' comment '菜单字体图片',
    KEY level (level),
    KEY pid (pid),
    KEY status (status),
    KEY is_menu(is_menu)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT '操作节点表';

-- 插入记录
INSERT INTO `sys_node` (`id`, `pid`, `name`, `url`, `status`, `is_menu`, `level`, `can_del`, `sort`, `font_icon`) VALUES 
	(1,0,'系统管理','#',1,1,1,0,0,'cog'),
	(2,1,'菜单管理','/system/node/index',1,1,1,0,0,''),
	(3,1,'角色管理','/system/role/index',1,1,1,0,0,''),
	(4,1,'系统用户','/system/user/index',1,1,1,0,0,'');


-- 角色表
CREATE TABLE sys_role (
    id int(11) unsigned PRIMARY KEY AUTO_INCREMENT,
    name varchar(100) not null default '' comment '角色名字',
    status tinyint(1) unsigned not null default 1 comment '状态，1正常 0禁用',
    remark varchar(255) not null default '' comment '备注',
    KEY status (status)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT '角色表';

-- 插入角色数据
INSERT INTO `sys_role` (`id`, `name`, `status`, `remark`) VALUES 
	(1,'系统管理员',1,'系统管理员');


-- 用户角色表
CREATE TABLE sys_users_role (
    role_id int(11) unsigned default 0 comment '角色ID，对应sys_role表主键',
    user_id int(11) unsigned default 0 comment '用户ID',
    KEY role_id (role_id),
    KEY user_id (user_id)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT '系统用户角色表';






-- 用户表(客户端)
CREATE TABLE g_clients (
	id bigint unsigned PRIMARY KEY AUTO_INCREMENT,
	mobile varchar(14) not null default '' comment '手机号码',
	email varchar(100) not null default '' comment '联系邮箱',
	password char(60) not null default '' comment '密码',
	first_name varchar(50) not null default '',
	last_name varchar(50) not null default '',
	nickname varchar(40) not null default '' comment '昵称',
	avatar_url varchar(255) not null default '' comment '用户头像',
	gender tinyint(1) unsigned not null default 0 comment '性别,1:男 2:女 0:未知',
	language varchar(100) not null default '' comment '客户端语言',
	wechat_openid varchar(100) not null default '' comment '用户微信唯一标识',
	created datetime default CURRENT_TIMESTAMP comment '添加时间',
	updated datetime default CURRENT_TIMESTAMP comment '修改时间',
	status tinyint(1) unsigned not null default 1 comment '状态，0：禁用，1：正常',
	KEY wechat_openid(wechat_openid),
	KEY status(status)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COMMENT '用户表(客户端)';




