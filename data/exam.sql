/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : exam

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2020-04-26 12:50:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for course
-- ----------------------------
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '课程ID',
  `major_id` int(20) unsigned NOT NULL COMMENT '专业ID',
  `name` varchar(50) NOT NULL COMMENT '课程名称',
  `order` int(3) unsigned zerofill DEFAULT '000' COMMENT '排序',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态(备用)',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='课程表';

-- ----------------------------
-- Records of course
-- ----------------------------
INSERT INTO `course` VALUES ('1', '1', 'HTML/CSS', '001', null, null, null);
INSERT INTO `course` VALUES ('2', '1', 'JavaScript', '002', null, null, null);
INSERT INTO `course` VALUES ('3', '1', 'jQuery', '003', null, null, null);
INSERT INTO `course` VALUES ('4', '2', 'PHP', '001', null, null, null);
INSERT INTO `course` VALUES ('5', '2', 'Java', '002', null, null, null);
INSERT INTO `course` VALUES ('6', '2', 'Python', '003', null, null, null);
INSERT INTO `course` VALUES ('7', '2', 'C', '004', null, null, null);
INSERT INTO `course` VALUES ('8', '2', 'C++', '005', null, null, null);
INSERT INTO `course` VALUES ('9', '2', 'go', '006', null, null, null);
INSERT INTO `course` VALUES ('10', '2', 'c#', '007', null, null, null);
INSERT INTO `course` VALUES ('11', '3', 'Android', '001', null, null, null);
INSERT INTO `course` VALUES ('12', '3', 'IOS', '002', null, null, null);
INSERT INTO `course` VALUES ('13', '3', 'Unity 3D', '003', null, null, null);
INSERT INTO `course` VALUES ('14', '3', 'Cocos2d-x', '004', null, null, null);
INSERT INTO `course` VALUES ('15', '4', 'MySQL', '001', null, null, null);
INSERT INTO `course` VALUES ('16', '4', 'MongoDB', '002', null, null, null);
INSERT INTO `course` VALUES ('17', '4', 'Oracle', '003', null, null, null);
INSERT INTO `course` VALUES ('18', '4', 'SQL Server', '004', null, null, null);
INSERT INTO `course` VALUES ('19', '5', '大数据', '001', null, null, null);
INSERT INTO `course` VALUES ('20', '5', '云计算', '002', null, null, null);
INSERT INTO `course` VALUES ('21', '6', '测试', '001', null, null, null);
INSERT INTO `course` VALUES ('22', '6', 'Linux', '002', null, null, null);
INSERT INTO `course` VALUES ('23', '7', '动效动画', '001', null, null, null);
INSERT INTO `course` VALUES ('24', '6', '设计工具', '002', null, null, null);
INSERT INTO `course` VALUES ('25', '8', '计算机网络', '034', '0', null, null);

-- ----------------------------
-- Table structure for exam
-- ----------------------------
DROP TABLE IF EXISTS `exam`;
CREATE TABLE `exam` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '考试ID',
  `title` varchar(255) DEFAULT NULL COMMENT '考试名称',
  `course_id` int(20) DEFAULT NULL COMMENT '课程ID',
  `course_name` varchar(300) DEFAULT NULL COMMENT '课程姓名',
  `major_id` int(10) DEFAULT NULL COMMENT '专业id',
  `major_name` varchar(300) DEFAULT NULL COMMENT '专业名称',
  `paper_id` int(10) DEFAULT NULL COMMENT '关联试卷id',
  `rule` varchar(300) DEFAULT NULL COMMENT '考试相关规则',
  `focus` varchar(300) DEFAULT NULL COMMENT '考试提示',
  `start_date` datetime DEFAULT NULL COMMENT '考试开始时间',
  `max_end_date` datetime DEFAULT NULL COMMENT '考试最晚结束时间',
  `time` int(10) DEFAULT '0' COMMENT '考试时长',
  `is_analysis` tinyint(1) DEFAULT '1' COMMENT '是否显示答案解析',
  `is_check` tinyint(1) DEFAULT '0' COMMENT '是否人工审核',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT ' 状态（1-未开始 2-进行中 3-审卷中 4-已结束）',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `img` varchar(255) DEFAULT NULL COMMENT '图片',
  `count` int(10) DEFAULT '0' COMMENT '报考人数',
  `score` int(10) NOT NULL DEFAULT '100' COMMENT '考试总分',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0为禁用，1为正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='试卷表';

-- ----------------------------
-- Records of exam
-- ----------------------------
INSERT INTO `exam` VALUES ('12', '软件设计师', '25', '计算机网络', '8', '信息管理', '4', '不准作弊', '不准作弊', '2018-04-10 05:10:00', '2018-04-30 00:00:00', '60', '1', '0', '4', null, null, '/upload/20180427\\f6ada843629301e205f7fa39899f5e19.jpg', '1', '0', '1');
INSERT INTO `exam` VALUES ('13', '设计师', '25', '计算机网络', '8', '信息管理', '3', '不准作弊', '不准作弊', '2018-04-27 05:11:00', '2018-04-27 05:18:00', '60', '1', '1', '4', null, null, '/upload/20180427\\fd0144278b62cf75758ce7ba6a09df92.jpg', '1', '20', '1');
INSERT INTO `exam` VALUES ('14', 'PHP攻城狮', '25', '计算机网络', '8', '信息管理', '5', '不准作弊', '线上考试', '2020-04-25 17:00:00', '2021-04-30 18:00:00', '30', '1', '1', '2', null, null, '/upload/20190411\\bf65918d1462b2670a95b09f7436cb34.jpg', '4', '100', '1');

-- ----------------------------
-- Table structure for major
-- ----------------------------
DROP TABLE IF EXISTS `major`;
CREATE TABLE `major` (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '专业ID',
  `name` varchar(50) DEFAULT NULL COMMENT '专业名称',
  `order` int(3) unsigned zerofill DEFAULT '000' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态(备用)',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='专业信息表';

-- ----------------------------
-- Records of major
-- ----------------------------
INSERT INTO `major` VALUES ('1', '前端开发', '001', null, null, null);
INSERT INTO `major` VALUES ('2', '后端开发', '002', null, null, null);
INSERT INTO `major` VALUES ('3', '移动开发', '003', null, null, null);
INSERT INTO `major` VALUES ('4', '数据库', '004', null, null, null);
INSERT INTO `major` VALUES ('5', '云计算&大数据', '005', null, null, null);
INSERT INTO `major` VALUES ('6', '运维&测试', '006', null, null, null);
INSERT INTO `major` VALUES ('7', 'UI设计', '007', null, null, null);
INSERT INTO `major` VALUES ('8', '信息管理', '008', '1', null, null);

-- ----------------------------
-- Table structure for manager
-- ----------------------------
DROP TABLE IF EXISTS `manager`;
CREATE TABLE `manager` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `username` varchar(50) DEFAULT NULL COMMENT '管理员账号',
  `password` varchar(64) DEFAULT NULL COMMENT '密码',
  `status` int(5) DEFAULT '1' COMMENT '状态(0-禁用，1-正常）',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of manager
-- ----------------------------
INSERT INTO `manager` VALUES ('34', 'admin', 'aaffebecec560fec66e75f24062224ffa4e07696d2ae9a1fee3707c3f8fd9373', '1', null, null);
INSERT INTO `manager` VALUES ('40', 'test', '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08', '0', null, null);
INSERT INTO `manager` VALUES ('41', 'adb', '7e16a033d8a9e716f5572ef0b23b296050bcf72c23a67c1198b995de62701b20', '1', null, null);
INSERT INTO `manager` VALUES ('42', '13665994204', 'd4735e3a265e16eee03f59718b9b5d03019c07d8b6c51f90da3a666eec13ab35', '1', null, null);
INSERT INTO `manager` VALUES ('43', '1', 'd4735e3a265e16eee03f59718b9b5d03019c07d8b6c51f90da3a666eec13ab35', '1', null, null);

-- ----------------------------
-- Table structure for paper
-- ----------------------------
DROP TABLE IF EXISTS `paper`;
CREATE TABLE `paper` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '试卷ID',
  `name` varchar(50) DEFAULT NULL COMMENT '试卷名称',
  `score` int(10) DEFAULT '0' COMMENT '试卷总分（试卷中试题分数之和）',
  `pass_score` int(10) DEFAULT '0' COMMENT '及格分数',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态(备用)',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='试卷表';

-- ----------------------------
-- Records of paper
-- ----------------------------
INSERT INTO `paper` VALUES ('3', 'php初级考试', '20', '10', '0', null, null);
INSERT INTO `paper` VALUES ('4', 'ASP', '0', '0', '0', null, null);
INSERT INTO `paper` VALUES ('5', 'PHP攻城狮', '100', '60', '0', null, null);

-- ----------------------------
-- Table structure for paper_question
-- ----------------------------
DROP TABLE IF EXISTS `paper_question`;
CREATE TABLE `paper_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `paper_id` int(20) DEFAULT NULL COMMENT '试卷id',
  `question_id` int(10) DEFAULT NULL COMMENT '试题id',
  `name` varchar(300) DEFAULT NULL COMMENT '试题标题',
  `title` varchar(30) DEFAULT NULL COMMENT '试题标题',
  `type` tinyint(1) DEFAULT NULL COMMENT '试题类型（1-判断题 2-单选题 3-多选题 4-填空题 5-简答题）',
  `options` varchar(300) DEFAULT NULL COMMENT ' 试题选项（用||隔开）',
  `answer` varchar(300) DEFAULT NULL COMMENT '试题答案',
  `analysis` varchar(300) DEFAULT NULL COMMENT '试题解析',
  `keyword` varchar(300) DEFAULT NULL COMMENT ' 试题关键词（简答题判分用）',
  `keyword_imp` varchar(300) DEFAULT NULL COMMENT '试题重点关键词（简答题判分用）',
  `score` float(10,1) DEFAULT '0.0' COMMENT '试题分数',
  `order` int(10) unsigned DEFAULT '0' COMMENT '试题排序',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COMMENT='试卷-试题表';

-- ----------------------------
-- Records of paper_question
-- ----------------------------
INSERT INTO `paper_question` VALUES ('34', '3', '53', '1+1为什么等于2', '<p>1+1为什么等于2？</p>', '1', '正确||错误', '1', '1+1=2', '', '', '20.0', '0', null, null);
INSERT INTO `paper_question` VALUES ('35', '5', '54', '1+1=2？', '<p>1+1=2？<br></p>', '1', '正确||错误', '1', '1', '', '', '100.0', '0', null, null);

-- ----------------------------
-- Table structure for question
-- ----------------------------
DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '试题ID',
  `name` varchar(300) DEFAULT NULL COMMENT '试题名称',
  `type` tinyint(1) unsigned DEFAULT NULL COMMENT '试题类型（1-判断题 2-单选题 3-多选题 4-填空题 5-简答题）',
  `title` varchar(500) DEFAULT NULL COMMENT '题目',
  `analysis` varchar(500) DEFAULT NULL COMMENT '解析',
  `options` varchar(500) DEFAULT NULL COMMENT '试题选项（用||隔开）',
  `answer` varchar(50) DEFAULT NULL COMMENT '答案:判断题是正确为1,错误为2',
  `keyword` varchar(300) DEFAULT NULL COMMENT '试题关键词（简答题判分用',
  `keyword_imp` varchar(300) DEFAULT NULL COMMENT '重要关键字',
  `status` int(5) unsigned DEFAULT NULL COMMENT '状态',
  `score` int(10) DEFAULT '0' COMMENT '试题分数',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  `order` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COMMENT='试题表';

-- ----------------------------
-- Records of question
-- ----------------------------
INSERT INTO `question` VALUES ('1', '1+1=2', '1', '<p>1+1=2<img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABaAAAAJECAYAAADg0oPSAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKTWlDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVN3WJP3Fj7f92UPVkLY8LGXbIEAIiOsCMgQWaIQkgBhhBASQMWFiApWFBURnEhVxILVCkidiOKgKLhnQYqIWotVXDjuH9yntX167+3t+9f7vOec5/zOec8PgBESJpHmomoAOVKFPDrYH49PSMTJvYACFUjgBCAQ5svCZwXFAADwA3l4fnSwP/wBr28AAgBw1S4kEsfh/4O6UCZXACCRAOAiEucLAZBSAMguVMgUAMgYALBTs2QKAJQAAGx5fEIiAKoNAOz0ST4FANipk9wXANiiHKkIAI0BAJkoRyQCQLsAYFWBUiwCwMIAoKxAIi4EwK4BgFm2MkcCgL0FAHaOWJAP', '无', '正确||错误', '1', '', '', '1', '10', null, null, '1');
INSERT INTO `question` VALUES ('2', '试题1', '1', '1+1=？', '无', '正确||错误', '2', '', '', '1', '10', null, null, '0');
INSERT INTO `question` VALUES ('3', '下列哪些是奇数?', '3', '下列哪些是奇数?', '11', '11||22||33||44', '1||4', '', '', '1', '10', null, null, '2');
INSERT INTO `question` VALUES ('5', '1+1为什么等于2？', '5', '1+1为什么等于2？', '无', '', '', '', '', '1', '0', null, null, '1');
INSERT INTO `question` VALUES ('12', '12', '2', '<p>12</p>', '3', '1||2||3||4', '3', '', '', '1', '1', null, null, '1');
INSERT INTO `question` VALUES ('13', '你自己牛逼吗', '4', '<p>你自己牛逼吗<br></p>', '你不牛逼你过意得去吗，你心不痛吗', 'ads||as', 'ads||as', '', '', null, '100', null, null, '1');
INSERT INTO `question` VALUES ('15', '下列哪些是奇数?', '3', '下列哪些是奇数?', '', '11||22||33||44', '11||44', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('16', '下列哪些是奇数?', '3', '<p><span style=\"color: rgb(80, 80, 80); font-family: OpenSans, Helvetica, Arial, sans-serif; font-size: 12px; white-space: pre;\">下列哪些是奇数?</span><br></p>', '无', '11||22||33||44', '1||4', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('17', '下列哪些是奇数?', '2', '<p><span style=\"color: rgb(80, 80, 80); font-family: OpenSans, Helvetica, Arial, sans-serif; font-size: 12px; white-space: pre-wrap;\">下列哪些是奇数?</span><br></p>', '无', '11||22||33||44', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('18', '下列哪些是奇数?', '2', '<p><span style=\"color: rgb(80, 80, 80); font-family: OpenSans, Helvetica, Arial, sans-serif; font-size: 12px; white-space: pre-wrap;\">下列哪些是奇数?</span><br></p>', '无', '11||22||33||44', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('19', '下列哪些是奇数?', '2', '<p><span style=\"color: rgb(80, 80, 80); font-family: OpenSans, Helvetica, Arial, sans-serif; font-size: 12px; white-space: pre-wrap;\">下列哪些是奇数?</span><br></p>', '无', '11||22||33||44', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('20', '下列哪些是奇数?', '2', '<p><span style=\"color: rgb(80, 80, 80); font-family: OpenSans, Helvetica, Arial, sans-serif; font-size: 12px; white-space: pre-wrap;\">下列哪些是奇数?</span><br></p>', '无', '11||22||33||44', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('21', '下列哪些是奇数?', '2', '<p><span style=\"color: rgb(80, 80, 80); font-family: OpenSans, Helvetica, Arial, sans-serif; font-size: 12px; white-space: pre-wrap;\">下列哪些是奇数?</span><br></p>', '无', '11||22||33||44', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('22', '下列哪些是奇数?', '2', '<p><span style=\"color: rgb(80, 80, 80); font-family: OpenSans, Helvetica, Arial, sans-serif; font-size: 12px; white-space: pre-wrap;\">下列哪些是奇数?</span><br></p>', '无', '11||22||33||44', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('23', '下列哪些是奇数?', '2', '<p><span style=\"color: rgb(80, 80, 80); font-family: OpenSans, Helvetica, Arial, sans-serif; font-size: 12px; white-space: pre-wrap;\">下列哪些是奇数?</span><br></p>', '无', '11||22||33||44', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('24', '下列哪些是奇数?', '3', '下列哪些是奇数?', '', '11||22||33||44', '11||44', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('25', '下列哪些是奇数?', '3', '下列哪些是奇数?', '', '11||22||33||44', '11||44', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('26', '下列哪些是奇数?', '3', '下列哪些是奇数?', '', '11||22||33||44', '11||44', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('27', '下列哪些是奇数?', '3', '下列哪些是奇数?', '', '11||22||33||44', '11||44', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('28', '下列哪些是奇数?', '2', '<p><span style=\"color: rgb(80, 80, 80); font-family: OpenSans, Helvetica, Arial, sans-serif; font-size: 12px; white-space: pre-wrap;\">下列哪些是奇数?</span><br></p>', '无', '11||22||33||44', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('29', '下列哪些是奇数?', '1', '<p><span style=\"color: rgb(80, 80, 80); font-family: OpenSans, Helvetica, Arial, sans-serif; font-size: 12px; white-space: pre-wrap;\">下列哪些是奇数?</span><br></p>', '无', '正确||错误', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('30', '下列哪些是奇数?', '1', '<p><span style=\"color: rgb(80, 80, 80); font-family: OpenSans, Helvetica, Arial, sans-serif; font-size: 12px; white-space: pre-wrap;\">下列哪些是奇数?</span><br></p>', '1', '正确||错误', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('31', '下列哪些是奇数?', '1', '<p><span style=\"color: rgb(80, 80, 80); font-family: OpenSans, Helvetica, Arial, sans-serif; font-size: 12px; white-space: pre-wrap;\">下列哪些是奇数?</span><br></p>', '1', '正确||错误', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('32', '下列哪些是奇数?', '1', '<p><span style=\"color: rgb(80, 80, 80); font-family: OpenSans, Helvetica, Arial, sans-serif; font-size: 12px; white-space: pre-wrap;\">下列哪些是奇数?</span><br></p>', '1', '正确||错误', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('33', '1+1=2', '1', '<p>1+1=2<br></p>', '无', '正确||错误', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('34', '1+1=2', '1', '<p>1+1=2<br></p>', '1+1=2', '正确||错误', '2', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('35', '1+1=2', '2', '<p>1+1=2<br></p>', '1+1=2', '1||2||3||4', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('36', '1+1=2', '1', '<p>1+1=2<br></p>', '1+1=2', '正确||错误', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('37', '1+1=2', '1', '<p>1+1=2<br></p>', '1+1=2', '正确||错误', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('38', '1+1=3', '1', '<p>1+1=3<br></p>', '1+1=3', '正确||错误', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('39', '1+1=4', '1', '<p>1+1=4<br></p>', '我', '正确||错误', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('40', '1+1=4', '1', '<p>1+1=4<br></p>', '1+1=4', '正确||错误', '2', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('41', '1+1=4', '1', '<p>1+1=4<br></p>', '1+1=4', '正确||错误', '2', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('42', '1+1=2', '1', '<p>1+1=2<br></p>', '1+1=2', '正确||错误', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('43', '1+1 = 2', '1', '<p>1+1 = 2<br></p>', '1+1 = 2', '正确||错误', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('44', '1+1=3', '1', '<p>1+1=3<br></p>', '1+1=3', '正确||错误', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('45', '1+12', '2', '<p>1+12<br></p>', '1+12', '11||22||33||44', '1', '', '', null, '20', null, null, '0');
INSERT INTO `question` VALUES ('46', '1+22', '1', '<p>1+22<br></p>', '1+22', '正确||错误', '2', '', '', null, '5', null, null, '0');
INSERT INTO `question` VALUES ('47', '1+1=2', '1', '<p>1+22<br></p>', '1+22', '正确||错误', '1', '', '', null, '5', null, null, '0');
INSERT INTO `question` VALUES ('48', '1+1=？', '2', '<p>1+1=？<br></p>', '1+1=2', '1||2||3||4', '2', '', '', null, '5', null, null, '0');
INSERT INTO `question` VALUES ('49', '1+1 =', '3', '<p>1+1 =&nbsp;<br></p>', '无', '2||3||4||5', '1||3', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('50', '1+1', '4', '<p>1+1&nbsp;&nbsp;&nbsp;&nbsp;</p>', '0', '2||3', '2||3', '', '', null, '5', null, null, '0');
INSERT INTO `question` VALUES ('51', '1+1', '3', '<p>1+1</p>', '是', '2||3||4||5', '1||3', '', '', null, '5', null, null, '0');
INSERT INTO `question` VALUES ('52', '1+2=3', '4', '<p>1+2=3<br></p>', 'wu', '2222||3333', '2222||3333', '', '', null, '5', null, null, '0');
INSERT INTO `question` VALUES ('53', '1+1为什么等于2？', '1', '<p>1+1为什么等于2？</p>', '1+1=2', '正确||错误', '1', '', '', null, '10', null, null, '0');
INSERT INTO `question` VALUES ('54', '1+1=2？', '1', '<p>1+1=2？<br></p>', '1', '正确||错误', '1', '', '', null, '100', null, null, '0');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(50) DEFAULT NULL COMMENT '用户登录名',
  `truename` varchar(50) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL COMMENT '密码',
  `sex` tinyint(1) DEFAULT '1' COMMENT '性别：1男,2女',
  `birth` datetime DEFAULT NULL COMMENT '生日',
  `age` int(10) DEFAULT '0' COMMENT '年龄',
  `phone` varchar(20) DEFAULT NULL,
  `status` int(5) DEFAULT '1',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  `avatar` varchar(500) DEFAULT NULL COMMENT '头像',
  `desc` varchar(500) DEFAULT NULL COMMENT '描述',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='考生表';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('22', '张先生', '张某某', 'aaffebecec560fec66e75f24062224ffa4e07696d2ae9a1fee3707c3f8fd9373', '1', '2017-04-15 00:00:00', '0', '13665994204', '1', '2018-03-08 11:00:00', '2018-03-10 01:00:00', '/upload/20180309\\8e8125942bdcd13f16480bb6689301eb.png', '名', null);
INSERT INTO `user` VALUES ('25', 'aaa', '张大师', 'aaffebecec560fec66e75f24062224ffa4e07696d2ae9a1fee3707c3f8fd9373', null, null, '0', null, '1', '2018-03-10 02:02:31', null, null, null, null);
INSERT INTO `user` VALUES ('26', 'admin', '管理员', '0d81684688d4057da4d9f6df64b28154b68afc2f1946a756302613c92fdd4986', '2', '2017-04-06 00:00:00', '0', '123', '1', '2018-03-10 06:33:22', '2018-04-26 15:08:38', '/upload/20180322\\ae4399a2336cf9dcc4964df64717878b.jpg', '', '1561302459@qq.com');
INSERT INTO `user` VALUES ('27', 'hyb', '何艺宝', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', null, null, '0', null, '1', '2018-03-16 09:30:47', null, null, null, null);
INSERT INTO `user` VALUES ('28', 'yjt', '严净汀', '19de6b5645734410950bbaaddc92e87df7e1ee9824ec959f4fa16ec3f4fcc6e2', null, null, '0', null, '1', '2018-03-16 09:34:49', null, null, null, null);
INSERT INTO `user` VALUES ('29', 'zwj', '13665994204', '0db533b171518482d345d3315d2d5951550b19927dfda99b734d3d4a185786c6', null, null, '0', null, '1', '2018-03-19 14:44:49', null, null, null, null);
INSERT INTO `user` VALUES ('30', '', 'hbz', '60a37c69716b54c33dffc94cbcd14569cd588fd5b97775209fc9375053bab1a7', null, null, '0', null, '1', '2018-03-20 20:37:15', null, null, null, null);
INSERT INTO `user` VALUES ('36', 'hades', 'hades', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '1', null, '0', null, '1', '2018-03-29 11:08:56', null, null, null, null);
INSERT INTO `user` VALUES ('37', 'hjl', 'hjl', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '1', null, '0', null, '1', '2018-03-29 11:09:11', null, null, null, null);
INSERT INTO `user` VALUES ('38', 'cdn', 'cdn', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '1', null, '0', null, '1', '2018-03-29 11:10:08', null, null, null, null);

-- ----------------------------
-- Table structure for user_exam
-- ----------------------------
DROP TABLE IF EXISTS `user_exam`;
CREATE TABLE `user_exam` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(10) DEFAULT NULL COMMENT '考生id',
  `username` varchar(300) DEFAULT NULL COMMENT '考生姓名',
  `exam_id` int(20) DEFAULT NULL COMMENT '考试id',
  `exam_time` datetime DEFAULT NULL COMMENT '考试开始时间',
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '考试成绩',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态（未报名则无记录，1-已报名 2-考试中 3-考试完成 4-缺考 5-批改完成）',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `pass` tinyint(1) DEFAULT '0' COMMENT '是否通过,1通过,0未通过',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='考试-考试表';

-- ----------------------------
-- Records of user_exam
-- ----------------------------
INSERT INTO `user_exam` VALUES ('15', '29', 'zwj', '14', '2020-04-26 12:40:54', '0', '1', '2020-04-26 12:33:36', '0');

-- ----------------------------
-- Table structure for user_question
-- ----------------------------
DROP TABLE IF EXISTS `user_question`;
CREATE TABLE `user_question` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(10) DEFAULT NULL COMMENT '考生id',
  `exam_id` int(20) DEFAULT NULL COMMENT '试卷id',
  `name` varchar(300) DEFAULT NULL COMMENT '试题名称',
  `title` varchar(500) DEFAULT NULL COMMENT '题目',
  `type` tinyint(1) DEFAULT NULL COMMENT '试题类型（1-判断题 2-单选题 3-多选题 4-填空题 5-简答题）',
  `options` varchar(500) DEFAULT NULL COMMENT '试题选项（用||隔开）',
  `answer` varchar(50) DEFAULT NULL COMMENT '试题答案',
  `analysis` varchar(500) DEFAULT NULL COMMENT '试题解析',
  `keyword` varchar(300) DEFAULT NULL COMMENT '试题关键词（简答题判分用',
  `keyword_imp` varchar(300) DEFAULT NULL COMMENT '试题重点关键词（简答题判分用）',
  `final_score` int(10) unsigned DEFAULT '0' COMMENT '最终成绩',
  `order` int(10) unsigned DEFAULT '0' COMMENT '排序',
  `score` int(10) DEFAULT '0' COMMENT '试题分数',
  `user_question_answer` varchar(300) DEFAULT NULL COMMENT '考生回答试题答案',
  `user_score` int(10) DEFAULT '0' COMMENT '考生得到分数',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='考试-试题表';

-- ----------------------------
-- Records of user_question
-- ----------------------------
INSERT INTO `user_question` VALUES ('27', '26', '12', '', '<p>1+1为什么等于2？</p>', '1', '正确||错误', '1', '1+1=2', '', '', '20', '0', '20', '1', '20', '0000-00-00 00:00:00', null);
INSERT INTO `user_question` VALUES ('30', '26', '13', '', '<p>1+1为什么等于2？</p>', '1', '正确||错误', '1', '1+1=2', '', '', '20', '0', '20', '1', '20', '0000-00-00 00:00:00', null);
INSERT INTO `user_question` VALUES ('31', '29', '14', '1+1为什么等于2？', '<p>1+1为什么等于2？</p>', '1', '正确||错误', '1', '1+1=2', '', '', '0', '0', '20', '1', '0', '0000-00-00 00:00:00', null);
INSERT INTO `user_question` VALUES ('32', '29', '14', '1+1=2？', '<p>1+1=2？<br></p>', '1', '正确||错误', '1', '1', '', '', '0', '0', '100', '1', '0', '0000-00-00 00:00:00', null);
INSERT INTO `user_question` VALUES ('33', '29', '14', '1+1=2？', '<p>1+1=2？<br></p>', '1', '正确||错误', '1', '1', '', '', '0', '0', '100', '2', '0', '0000-00-00 00:00:00', null);

-- ----------------------------
-- Procedure structure for exam_status_change
-- ----------------------------
DROP PROCEDURE IF EXISTS `exam_status_change`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `exam_status_change`()
BEGIN
	#Routine body goes here...
	IF EXISTS(SELECT id
              FROM exam
              WHERE `status` = 1 AND SYSDATE() >= start_date AND SYSDATE() < max_end_date)
    THEN
      UPDATE exam
      SET `status` = 2
      WHERE id in (
        SELECT *
        FROM (SELECT id
              FROM exam
              WHERE `status` = 1 AND SYSDATE() >= start_date AND SYSDATE() < max_end_date) T
      );
    END IF;
    IF EXISTS(SELECT id
              FROM exam
              WHERE `status` = 2 AND SYSDATE() >= max_end_date)
    THEN
      UPDATE exam
      SET `status` = 4
      WHERE id in (
        SELECT *
        FROM (SELECT id
              FROM exam
              WHERE `status` = 2 AND `is_check` = 0 AND SYSDATE() >= max_end_date) T
      );
			UPDATE exam
      SET `status` = 3
      WHERE id in (
        SELECT *
        FROM (SELECT id
              FROM exam
              WHERE `status` = 2 AND `is_check` = 1 AND SYSDATE() >= max_end_date) T
      );
    END IF;
			
END
;;
DELIMITER ;

-- ----------------------------
-- Event structure for change_exam_status
-- ----------------------------
DROP EVENT IF EXISTS `change_exam_status`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` EVENT `change_exam_status` ON SCHEDULE EVERY 1 SECOND STARTS '2018-01-19 15:58:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL exam_status_change()
;;
DELIMITER ;
