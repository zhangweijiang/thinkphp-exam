/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : exam

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-03-08 17:58:57
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
  `order` int(3) unsigned zerofill DEFAULT NULL COMMENT '排序',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态(备用)',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='课程表';

-- ----------------------------
-- Records of course
-- ----------------------------
INSERT INTO `course` VALUES ('1', '1', '计算机组成原理', '000', null, null, null);

-- ----------------------------
-- Table structure for exam
-- ----------------------------
DROP TABLE IF EXISTS `exam`;
CREATE TABLE `exam` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '考试ID',
  `title` varchar(255) DEFAULT NULL COMMENT '考试名称',
  `course_id` int(20) DEFAULT NULL COMMENT '课程ID',
  `paper_id` int(10) DEFAULT NULL COMMENT '关联试卷id',
  `rule` varchar(300) DEFAULT NULL COMMENT '考试相关规则',
  `focus` varchar(300) DEFAULT NULL COMMENT '注意事项',
  `start_date` datetime DEFAULT NULL COMMENT '考试开始时间',
  `max_end_date` datetime DEFAULT NULL COMMENT '考试最晚结束时间',
  `time` int(10) DEFAULT '0' COMMENT '考试时长',
  `is_analysis` tinyint(1) DEFAULT '1' COMMENT '是否显示答案解析',
  `is_check` tinyint(1) DEFAULT '0' COMMENT '是否人工审核',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT ' 状态（1-未开始 2-进行中 3-已结束）',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `img` varchar(255) DEFAULT NULL COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='试卷表';

-- ----------------------------
-- Records of exam
-- ----------------------------
INSERT INTO `exam` VALUES ('1', 'ds', '132', null, null, null, '2018-01-19 15:47:51', '2018-01-20 15:47:56', '0', '1', '0', '0', null, null, null);
INSERT INTO `exam` VALUES ('2', '计算机组成原理', null, null, '开卷考', '不许作弊', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '0', '1', '1', null, null, null);

-- ----------------------------
-- Table structure for major
-- ----------------------------
DROP TABLE IF EXISTS `major`;
CREATE TABLE `major` (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '专业ID',
  `name` varchar(50) DEFAULT NULL COMMENT '专业名称',
  `order` int(3) unsigned zerofill DEFAULT NULL COMMENT '排序',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态(备用)',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='专业信息表';

-- ----------------------------
-- Records of major
-- ----------------------------
INSERT INTO `major` VALUES ('1', '软件工程', '014', null, null, null);
INSERT INTO `major` VALUES ('2', '信息管理', '013', null, null, null);

-- ----------------------------
-- Table structure for manager
-- ----------------------------
DROP TABLE IF EXISTS `manager`;
CREATE TABLE `manager` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `username` varchar(50) DEFAULT NULL COMMENT '管理员账号',
  `password` varchar(64) DEFAULT NULL COMMENT '密码',
  `status` int(5) DEFAULT NULL COMMENT '状态(0-禁用，1-正常）',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of manager
-- ----------------------------
INSERT INTO `manager` VALUES ('34', 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', '1', null, null, null);
INSERT INTO `manager` VALUES ('40', 'test', '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08', '0', null, null, null);
INSERT INTO `manager` VALUES ('41', 'adb', '7e16a033d8a9e716f5572ef0b23b296050bcf72c23a67c1198b995de62701b20', '1', null, null, null);

-- ----------------------------
-- Table structure for pager_quesion
-- ----------------------------
DROP TABLE IF EXISTS `pager_quesion`;
CREATE TABLE `pager_quesion` (
  `id` int(11) NOT NULL COMMENT '主键id',
  `paper_id` int(20) DEFAULT NULL COMMENT '试卷id',
  `question_id` int(10) DEFAULT NULL COMMENT '试题id',
  `title` varchar(30) DEFAULT NULL COMMENT '试题标题',
  `type` tinyint(1) DEFAULT NULL COMMENT '试题类型（1-判断题 2-单选题 3-多选题 4-填空题 5-简答题）',
  `options` varchar(300) DEFAULT NULL COMMENT ' 试题选项（用||隔开）',
  `answer` varchar(300) DEFAULT NULL COMMENT '试题答案',
  `analysis` varchar(300) DEFAULT NULL COMMENT '试题解析',
  `keyword` varchar(300) DEFAULT NULL COMMENT ' 试题关键词（简答题判分用）',
  `keyword_imp` varchar(300) DEFAULT NULL COMMENT '试题重点关键词（简答题判分用）',
  `score` float(10,1) DEFAULT NULL COMMENT '试题分数',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='试卷-试题表';

-- ----------------------------
-- Records of pager_quesion
-- ----------------------------

-- ----------------------------
-- Table structure for paper
-- ----------------------------
DROP TABLE IF EXISTS `paper`;
CREATE TABLE `paper` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '试卷ID',
  `name` varchar(50) DEFAULT NULL COMMENT '试卷名称',
  `rule` varchar(255) DEFAULT NULL COMMENT '试卷规则',
  `score` float(10,1) DEFAULT NULL COMMENT '试卷总分（试卷中试题分数之和）',
  `pass_score` float(10,1) DEFAULT NULL COMMENT '及格分数',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态(备用)',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='试卷表';

-- ----------------------------
-- Records of paper
-- ----------------------------

-- ----------------------------
-- Table structure for question
-- ----------------------------
DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '试题ID',
  `type` tinyint(1) unsigned DEFAULT NULL COMMENT '试题类型（1-判断题 2-单选题 3-多选题 4-填空题 5-简答题）',
  `title` varchar(500) DEFAULT NULL COMMENT '题目',
  `analysis` varchar(500) DEFAULT NULL COMMENT '解析',
  `options` varchar(500) DEFAULT NULL COMMENT '试题选项（用||隔开）',
  `answner` varchar(50) DEFAULT NULL COMMENT '答案',
  `keyword` varchar(300) DEFAULT NULL COMMENT '试题关键词（简答题判分用',
  `keyword_imp` varchar(300) DEFAULT NULL COMMENT '重要关键字',
  `status` int(5) unsigned DEFAULT NULL COMMENT '状态',
  `score` float(10,1) DEFAULT NULL COMMENT '试题分数',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='试题表';

-- ----------------------------
-- Records of question
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(50) DEFAULT NULL COMMENT '用户登录名',
  `truename` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL COMMENT '密码',
  `sex` varchar(10) DEFAULT NULL COMMENT '性别',
  `birth` varchar(20) DEFAULT NULL COMMENT '生日',
  `age` int(10) DEFAULT NULL COMMENT '年龄',
  `phone` varchar(20) DEFAULT NULL,
  `status` int(5) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  `avatar` varchar(500) DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='考生表';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('4', '2', null, '2', '2', '2', '2', null, null, null, null, null);
INSERT INTO `user` VALUES ('11', '张', null, '丶', ' ', ' ', null, null, null, null, null, null);
INSERT INTO `user` VALUES ('12', '实施', null, null, null, null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for user_exam
-- ----------------------------
DROP TABLE IF EXISTS `user_exam`;
CREATE TABLE `user_exam` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(10) DEFAULT NULL COMMENT '考生id',
  `exam_id` int(20) DEFAULT NULL COMMENT '考试id',
  `exam_time` datetime DEFAULT NULL COMMENT '考试开始时间',
  `score` float(10,1) DEFAULT NULL COMMENT '考试成绩',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态（未报名则无记录，1-已报名 2-考试中 3-考试完成 4-缺考）',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='考试-考试表';

-- ----------------------------
-- Records of user_exam
-- ----------------------------

-- ----------------------------
-- Table structure for user_question
-- ----------------------------
DROP TABLE IF EXISTS `user_question`;
CREATE TABLE `user_question` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(10) DEFAULT NULL COMMENT '考生id',
  `exam_id` int(20) DEFAULT NULL COMMENT '试卷id',
  `title` varchar(500) DEFAULT NULL COMMENT '考试开始时间',
  `type` tinyint(1) DEFAULT NULL COMMENT '试题类型（1-判断题 2-单选题 3-多选题 4-填空题 5-简答题）',
  `options` varchar(500) DEFAULT NULL COMMENT '试题选项（用||隔开）',
  `answer` varchar(50) DEFAULT NULL COMMENT '试题答案',
  `analysis` varchar(500) DEFAULT NULL COMMENT '试题解析',
  `keyword` varchar(300) DEFAULT NULL COMMENT '试题关键词（简答题判分用',
  `keyword_imp` varchar(300) DEFAULT NULL COMMENT '试题重点关键词（简答题判分用）',
  `score` float(10,1) DEFAULT NULL COMMENT '试题分数',
  `user_question_type` tinyint(1) DEFAULT NULL COMMENT '考试回答试题类型（1-判断题 2-单选题 3-多选题 4-填空题 5-简答题）',
  `user_question_answer` varchar(300) DEFAULT NULL COMMENT '考生回答试题答案',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='考试-试题表';

-- ----------------------------
-- Records of user_question
-- ----------------------------

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
              WHERE `status` = 1 AND SYSDATE() >= startDate AND SYSDATE() < endDate)
    THEN
      UPDATE exam
      SET `status` = 2
      WHERE id = (
        SELECT *
        FROM (SELECT id
              FROM exam
              WHERE `status` = 1 AND SYSDATE() >= startDate AND SYSDATE() < endDate) T
      );
    END IF;
    IF EXISTS(SELECT id
              FROM exam
              WHERE `status` = 2 AND SYSDATE() >= endDate)
    THEN
      UPDATE exam
      SET `status` = 3
      WHERE id = (
        SELECT *
        FROM (SELECT id
              FROM exam
              WHERE `status` = 2 AND SYSDATE() >= endDate) T
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
