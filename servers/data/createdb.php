<?php header("Content-type: text/html; charset=utf-8");
require("db.php");

//$db->execut("drop table blueOrder");

// 1. 团队表 (Team)
$sql = "CREATE TABLE hyTeam (
	id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  	weId varchar(150) DEFAULT NULL COMMENT '小程序ID/微信ID',
	tName varchar(150) DEFAULT NULL COMMENT '团队名称',
	tPwd varchar(50) DEFAULT NULL COMMENT '团队口令(无须审核)',
	tCode varchar(50) DEFAULT NULL COMMENT '团队二维码(扫码入队)',
	admName varchar(150) DEFAULT NULL COMMENT '管理员姓名',	
	admTel varchar(50) DEFAULT NULL COMMENT '管理员电话',
	addTime datetime DEFAULT NULL COMMENT '创建时间',
	PRIMARY KEY (id)
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='团队信息表'";
$db->execut($sql);

// 2. 团队人员分组表 (Group for Users)
$sql = "CREATE TABLE hyGroup (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  tId int(11) DEFAULT NULL COMMENT '所属团队ID',
  gName varchar(50) DEFAULT NULL COMMENT '分组名称',
  gSort int(11) NOT NULL DEFAULT '0' COMMENT '排序权重',
  addTime datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (id),
  KEY idx_tId (tId),
  CONSTRAINT fk_hyGroup_tId FOREIGN KEY (tId) REFERENCES hyTeam (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='团队人员分组表'";
$db->execut($sql);

// 3. 用户/成员表 (User/Member)
$sql = "CREATE TABLE hyUser (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  weId varchar(150) DEFAULT NULL COMMENT '小程序OpenID',
  wxId varchar(150) DEFAULT NULL COMMENT '公众号OpenID',
  gId int(11) DEFAULT NULL COMMENT '所属/管理分组ID',
  gName varchar(50) DEFAULT NULL COMMENT '分组名称(冗余)',
  tId int(11) DEFAULT NULL COMMENT '所属团队ID',
  types int(11) NOT NULL DEFAULT '0' COMMENT '类型:0个人 1公司',
  imgurl varchar(50) DEFAULT NULL COMMENT '头像URL',
  gsname varchar(50) DEFAULT NULL COMMENT '公司名称',
  daima varchar(50) DEFAULT NULL COMMENT '信用代码',
  faren varchar(50) DEFAULT NULL COMMENT '法人姓名',
  gstypes varchar(50) DEFAULT NULL COMMENT '公司类型',
  address varchar(150) DEFAULT NULL COMMENT '地址',
  zhucetime varchar(50) DEFAULT NULL COMMENT '注册时间',
  business varchar(500) DEFAULT NULL COMMENT '经营范围',
  hyName varchar(150) DEFAULT NULL COMMENT '成员姓名',
  hyTel varchar(20) DEFAULT NULL COMMENT '成员电话',	
  hyPwd varchar(50) DEFAULT NULL COMMENT '成员密码',
  hyIdentity int(11) NOT NULL DEFAULT '0' COMMENT '身份:0普通 1主管 2管理员 -1群众',
  hyState int(11) NOT NULL DEFAULT '0' COMMENT '状态:0待审核 1已通过 2已拒绝',
  logTime datetime DEFAULT NULL COMMENT '最后登录时间',
  logNum int(11) NOT NULL DEFAULT '0' COMMENT '登录次数',
  addTime datetime DEFAULT NULL COMMENT '注册/添加时间',
  PRIMARY KEY (id),
  KEY idx_tId (tId),
  KEY idx_gId (gId),
  CONSTRAINT fk_hyUser_tId FOREIGN KEY (tId) REFERENCES hyTeam (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_hyUser_gId FOREIGN KEY (gId) REFERENCES hyGroup (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='团队成员/用户表'";
$db->execut($sql);

// 4. 隐患专业分组 (Hazard Specialty Group)
$sql = "CREATE TABLE yhzyGroup (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  tId int(11) DEFAULT NULL COMMENT '所属团队ID',
  gName varchar(50) DEFAULT NULL COMMENT '专业名称',
  gSort int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  addTime datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (id),
  KEY idx_tId (tId),
  CONSTRAINT fk_yhzyGroup_tId FOREIGN KEY (tId) REFERENCES hyTeam (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='隐患专业分组表'";
$db->execut($sql);

// 5. 隐患排查记录 (Hazard Inspection Record)
$sql = "CREATE TABLE inspect (
	id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
	tId int(11) DEFAULT NULL COMMENT '团队ID',
	tName varchar(50) DEFAULT NULL COMMENT '团队名称',	
	hyId int(11) DEFAULT NULL COMMENT '上报人ID',
	hyName varchar(50) DEFAULT NULL COMMENT '上报人姓名',	
	weId varchar(50) DEFAULT NULL COMMENT '上报人OpenID',	
	gsName varchar(100) DEFAULT NULL COMMENT '主要责任主体/公司名',
	yhAdd varchar(100) DEFAULT NULL COMMENT '隐患地址',
	yhPosition varchar(100) DEFAULT NULL COMMENT '隐患部位',
	yhContent varchar(500) DEFAULT NULL COMMENT '隐患内容',	
	yhSpeciality varchar(50) DEFAULT NULL COMMENT '隐患专业',	
	together varchar(150) DEFAULT NULL COMMENT '协同人',	
	yhPhoto varchar(1000) DEFAULT NULL COMMENT '隐患照片',	
	posAdd varchar(200) DEFAULT NULL COMMENT '定位地址',
	zgUserId int(11) DEFAULT NULL COMMENT '整改人ID',
	zgName varchar(50) DEFAULT NULL COMMENT '整改人姓名',
	zgPhoto varchar(1000) DEFAULT NULL COMMENT '整改照片',
	zgInfo varchar(500) DEFAULT NULL COMMENT '整改备注',
	zgAsk varchar(200) DEFAULT NULL COMMENT '整改要求',
	zgTime varchar(50) DEFAULT NULL COMMENT '整改期限',
	zgState int(11) NOT NULL DEFAULT '0' COMMENT '整改状态',
	addTime datetime DEFAULT NULL COMMENT '上报时间',	
	PRIMARY KEY (id),
	KEY idx_tId (tId),
	KEY idx_hyId (hyId),
	CONSTRAINT fk_inspect_tId FOREIGN KEY (tId) REFERENCES hyTeam (id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_inspect_hyId FOREIGN KEY (hyId) REFERENCES hyUser (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='隐患排查记录表'";
$db->execut($sql);

// 6. 巡检点分组 (Patrol/Blue Group)
$sql = "CREATE TABLE blueGroup (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  tId int(11) DEFAULT NULL COMMENT '团队ID',
  gName varchar(50) DEFAULT NULL COMMENT '分组名称',
  gSort int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  addTime datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (id),
  KEY idx_tId (tId),
  CONSTRAINT fk_blueGroup_tId FOREIGN KEY (tId) REFERENCES hyTeam (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='巡检点分组表'";
$db->execut($sql);

// 7. 巡检点表 (Inspection Point)
$sql = "CREATE TABLE blueInspect (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  tId int(11) DEFAULT NULL COMMENT '团队ID',
  hyId int(11) DEFAULT NULL COMMENT '创建/负责人员ID',
  dropNo varchar(150) DEFAULT NULL COMMENT '巡检点编号',
  dropPhoto varchar(150) DEFAULT NULL COMMENT '巡检点照片',
  dropName varchar(150) DEFAULT NULL COMMENT '巡检点名称',
  gId int(11) DEFAULT NULL COMMENT '巡检点分组ID',
  dropClass varchar(150) DEFAULT NULL COMMENT '分类',
  dropInfo varchar(500) DEFAULT NULL COMMENT '详细信息',
  patrolCycle varchar(10) DEFAULT NULL COMMENT '巡检周期(天/周/月)',
  patrolNum int(11) NOT NULL DEFAULT '1' COMMENT '周期内应巡检次数',
  patrolDiff int(11) NOT NULL DEFAULT '0' COMMENT '周期偏差/容差',
  deviceId varchar(100) DEFAULT NULL COMMENT '绑定设备ID', 
  inspectCode varchar(100) DEFAULT NULL COMMENT '二维码值', 
  hyAppoint varchar(100) DEFAULT NULL COMMENT '指定巡检人ID(多)', 
  hyAppointName varchar(500) DEFAULT '' COMMENT '指定巡检人姓名',  
  issfz int(11) NOT NULL DEFAULT '0' COMMENT '是否验证身份证',
  isxjfs int(11) NOT NULL DEFAULT '0' COMMENT '巡检方式',
  isaddress int(11) NOT NULL DEFAULT '0' COMMENT '是否校验地址',
  isphoto int(11) NOT NULL DEFAULT '0' COMMENT '是否必须拍照',
  drores int(11) NOT NULL DEFAULT '0' COMMENT '最后一次结果:0正常1异常',  
  drostate int(11) NOT NULL DEFAULT '0' COMMENT '周期内状态:0待检 1未到 2已完',
  inspectNum int(11) NOT NULL DEFAULT '0' COMMENT '周期内已巡次数',
  inspectTime datetime DEFAULT NULL COMMENT '最近巡检时间',
  inspectName varchar(50) DEFAULT '' COMMENT '最近巡检人', 
  addTime datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (id),
  KEY idx_tId (tId),
  KEY idx_gId (gId),
  CONSTRAINT fk_blueInspect_tId FOREIGN KEY (tId) REFERENCES hyTeam (id) ON DELETE CASCADE ON UPDATE CASCADE,
    -- gId relates to blueGroup (patrol group)
  CONSTRAINT fk_blueInspect_gId FOREIGN KEY (gId) REFERENCES blueGroup (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='巡检点信息表'";
$db->execut($sql);

// 8. 漏检记录 (Missed Check Record)
$sql = "CREATE TABLE noCheck (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  tId int(11) DEFAULT NULL COMMENT '团队ID',
  droId int(11) DEFAULT NULL COMMENT '巡检点ID (blueInspect.id)',
  gId int(11) DEFAULT NULL COMMENT '分组ID',
  dropClass varchar(150) DEFAULT NULL COMMENT '分类',
  dropPhoto varchar(150) DEFAULT NULL COMMENT '照片',
  dropNo varchar(150) DEFAULT NULL COMMENT '编号',
  dropName varchar(150) DEFAULT NULL COMMENT '名称',
  dropInfo varchar(500) DEFAULT NULL COMMENT '信息',
  patrolCycle varchar(10) DEFAULT NULL COMMENT '周期',
  patrolNum int(11) NOT NULL DEFAULT '1' COMMENT '应巡次数',
  patrolDiff int(11) NOT NULL DEFAULT '0' COMMENT '偏差',
  hyAppoint varchar(100) DEFAULT NULL COMMENT '指定人', 
  hyAppointName varchar(500) DEFAULT '' COMMENT '指定人名', 
  inspectNum int(11) NOT NULL DEFAULT '0' COMMENT '已巡次数',
  inspectTime datetime DEFAULT NULL COMMENT '时间',
  inspectName varchar(50) DEFAULT '' COMMENT '巡检人', 
  inspectRes int(11) NOT NULL DEFAULT '0' COMMENT '结果',
  startTime datetime DEFAULT NULL COMMENT '周期开始时间',
  endTime datetime DEFAULT NULL COMMENT '周期结束时间',
  addTime datetime DEFAULT NULL COMMENT '记录时间',
  PRIMARY KEY (id),
  KEY idx_tId (tId),
  KEY idx_droId (droId),
  CONSTRAINT fk_noCheck_tId FOREIGN KEY (tId) REFERENCES hyTeam (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_noCheck_droId FOREIGN KEY (droId) REFERENCES blueInspect (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='漏检/未检记录表'";
$db->execut($sql);

// 9. 巡检项目/检查项 (Inspect Item)
$sql = "CREATE TABLE bluePro (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  iId int(11) DEFAULT NULL COMMENT '关联巡检点ID (blueInspect.id)',
  proName varchar(200) DEFAULT NULL COMMENT '项目名称',
  proSort int(11) NOT NULL DEFAULT '0' COMMENT '排序',  
  addTime datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (id),
  KEY idx_iId (iId),
  CONSTRAINT fk_bluePro_iId FOREIGN KEY (iId) REFERENCES blueInspect (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='巡检点检查项目表'";
$db->execut($sql);

// 10. 巡检记录/工单 (Inspect Order)
$sql = "CREATE TABLE blueOrder (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  tId int(11) DEFAULT NULL COMMENT '团队ID',
  hyName varchar(200) DEFAULT NULL COMMENT '巡检人姓名',
  hyId int(11) DEFAULT NULL COMMENT '巡检人ID',
  weId varchar(50) DEFAULT NULL COMMENT '巡检人OpenID',  
  dropId int(11) DEFAULT NULL COMMENT '巡检点ID (blueInspect.id)',
  gId int(11) DEFAULT NULL COMMENT '分组ID',
  gName varchar(50) DEFAULT NULL COMMENT '分组名称',
  dropNo varchar(50) DEFAULT NULL COMMENT '点位编号',
  dropName varchar(200) DEFAULT NULL COMMENT '点位名称',
  odInfo varchar(500) DEFAULT NULL COMMENT '工单备注/情况',
  odtogether varchar(50) DEFAULT NULL COMMENT '协同人',
  odPhoto varchar(500) DEFAULT NULL COMMENT '现场照片',
  odState int(11) NOT NULL DEFAULT '0' COMMENT '状态:0正常 1异常',	
  zgAsk varchar(200) DEFAULT NULL COMMENT '整改要求',
  zgTime varchar(50) DEFAULT NULL COMMENT '整改时间',	
  posAdd varchar(200) DEFAULT NULL COMMENT '定位地址',
  issfz int(11) NOT NULL DEFAULT '0' COMMENT '是否验证身份证',
  sfzzmimg varchar(50) DEFAULT NULL COMMENT '身份证正面',	
  sfzfmimg varchar(50) DEFAULT NULL COMMENT '身份证反面',	
  sfzname varchar(50) DEFAULT NULL COMMENT '身份证姓名',	
  sfznum varchar(50) DEFAULT NULL COMMENT '身份证号',	
  gender varchar(50) DEFAULT NULL COMMENT '性别',	
  yyzzimg varchar(50) DEFAULT NULL COMMENT '营业执照',	
  gsname varchar(50) DEFAULT NULL COMMENT '公司名',	
  daima varchar(50) DEFAULT NULL COMMENT '代码',	
  faren varchar(50) DEFAULT NULL COMMENT '法人',	
  gstypes varchar(50) DEFAULT NULL COMMENT '类型',	
  address varchar(50) DEFAULT NULL COMMENT '地址',	
  zhucetime varchar(50) DEFAULT NULL COMMENT '注册时间',	
  business varchar(50) DEFAULT NULL COMMENT '业务',	
  addTime datetime DEFAULT NULL COMMENT '提交时间',
  PRIMARY KEY (id),
  KEY idx_tId (tId),
  KEY idx_dropId (dropId),
  KEY idx_hyId (hyId),
  CONSTRAINT fk_blueOrder_tId FOREIGN KEY (tId) REFERENCES hyTeam (id) ON DELETE CASCADE ON UPDATE CASCADE,
    -- User might be deleted but record kept? Let's use CASCADE for consistency with team reset.
  CONSTRAINT fk_blueOrder_hyId FOREIGN KEY (hyId) REFERENCES hyUser (id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT fk_blueOrder_dropId FOREIGN KEY (dropId) REFERENCES blueInspect (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='巡检记录/工单表'";
$i = $db->execut($sql);

// 11. 工单项目结果详情 (Order Process/Result)
$sql = "CREATE TABLE orderPro (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  oId int(11) DEFAULT NULL COMMENT '工单ID (blueOrder.id)',
  proName varchar(200) DEFAULT NULL COMMENT '项目名称',
  proState int(11) NOT NULL DEFAULT '0' COMMENT '结果:0合格 1不合格',	
  proInfo varchar(500) DEFAULT NULL COMMENT '备注信息',
  addTime datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (id),
  KEY idx_oId (oId),
  CONSTRAINT fk_orderPro_oId FOREIGN KEY (oId) REFERENCES blueOrder (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='巡检项目结果详情'";
$i = $db->execut($sql);

// 12. 摊位巡检点 (Stall Inspect Point)
$sql = "CREATE TABLE stallInspect (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  tId int(11) DEFAULT NULL COMMENT '团队ID',
  hyId int(11) DEFAULT NULL COMMENT '创建人ID',
  dropPhoto varchar(150) DEFAULT NULL COMMENT '摊位照片',
  dropName varchar(150) DEFAULT NULL COMMENT '摊位名称',
  stallnum int(11) NOT NULL DEFAULT '1' COMMENT '摊位号',
  dropInfo varchar(500) DEFAULT NULL COMMENT '摊位信息',
  
  addname varchar(50) DEFAULT NULL COMMENT '添加人姓名',	
  longitude varchar(50) DEFAULT NULL COMMENT '经度',
  latitude varchar(50) DEFAULT NULL COMMENT '纬度',
  starttime time DEFAULT NULL COMMENT '开始时间',
  endtime time DEFAULT NULL COMMENT '结束时间',
  exitzhouqi varchar(50) DEFAULT NULL COMMENT '退出周期',
  exitdate varchar(50) DEFAULT NULL COMMENT '退出日期',
  exittime time DEFAULT NULL COMMENT '退出时间',
  
  bmsfz int(11) NOT NULL DEFAULT '0' COMMENT '需身份证',
  bmjyhy int(11) NOT NULL DEFAULT '0' COMMENT '需经营人员',
  bmjygj int(11) NOT NULL DEFAULT '0' COMMENT '需经营工具',
  bmtel int(11) NOT NULL DEFAULT '0' COMMENT '需电话',
   
  tCode varchar(50) DEFAULT NULL COMMENT '二维码',
  addTime datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (id),
  KEY idx_tId (tId),
  CONSTRAINT fk_stallInspect_tId FOREIGN KEY (tId) REFERENCES hyTeam (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='摊位巡检点表'";
$db->execut($sql);

// 13. 摊位工单/记录 (Stall Order)
$sql = "CREATE TABLE stallOrder (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  tId int(11) DEFAULT NULL COMMENT '团队ID',
  hyName varchar(200) DEFAULT NULL COMMENT '巡检人',
  hyTel varchar(200) DEFAULT NULL COMMENT '电话',
  hyId int(11) DEFAULT NULL COMMENT '巡检人ID',
  weId varchar(50) DEFAULT NULL COMMENT 'OpenID',  
  stallId int(11) DEFAULT NULL COMMENT '摊位ID (stallInspect.id)',
  stallName varchar(200) DEFAULT NULL COMMENT '摊位名称',
  stallInfo varchar(500) DEFAULT NULL COMMENT '情况',
  stallPhoto varchar(500) DEFAULT NULL COMMENT '照片',
  stalljyhy varchar(50) DEFAULT NULL COMMENT '经营人员',
  stallgj varchar(100) DEFAULT NULL COMMENT '经营工具',
  
  posAdd varchar(200) DEFAULT NULL COMMENT '定位',
  issfz int(11) NOT NULL DEFAULT '0' COMMENT '是否有身份证',
  sfzzmimg varchar(50) DEFAULT NULL COMMENT '身份证正面',	
  sfzfmimg varchar(50) DEFAULT NULL COMMENT '身份证反面',	
  yyzzimg varchar(50) DEFAULT NULL COMMENT '执照',	
  addTime datetime DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (id),
  KEY idx_tId (tId),
  KEY idx_stallId (stallId),
  CONSTRAINT fk_stallOrder_tId FOREIGN KEY (tId) REFERENCES hyTeam (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_stallOrder_stallId FOREIGN KEY (stallId) REFERENCES stallInspect (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='摊位巡检记录表'";
$i = $db->execut($sql);

// 14. 系统公告 (Notice)
$sql = "CREATE TABLE notice (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  newstype varchar(50) DEFAULT NULL COMMENT '类型',
  newsphoto varchar(250) DEFAULT NULL COMMENT '图片',
  newstitle varchar(250) DEFAULT NULL COMMENT '标题',
  newsauthor varchar(50) DEFAULT NULL COMMENT '作者',
  newsdetail text COMMENT '内容',
  newsread int(11) NOT NULL DEFAULT '0' COMMENT '阅读数',
  newsort int(11) DEFAULT NULL COMMENT '排序',
  addTime datetime DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统公告表'";
$db->execut($sql);

// 15. 管理员表 (Admin)
$sql = "CREATE TABLE xjAdmin (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  admName varchar(150) DEFAULT NULL COMMENT '管理员用户名',
  admPwd varchar(150) DEFAULT NULL COMMENT '加密密码',
  addTime datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='后台管理员表'";
$db->execut($sql);

$sql = "INSERT INTO xjAdmin(admName,admPwd,addTime)VALUES('admin','" . md5("bangfa") . "',now())";
$db->execut($sql);
?>