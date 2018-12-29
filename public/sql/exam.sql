/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : exam

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-01-19 16:27:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for course
-- ----------------------------
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '课程ID',
  `majorId` int(20) unsigned NOT NULL COMMENT '专业ID',
  `name` varchar(50) NOT NULL COMMENT '课程名称',
  `order` int(3) unsigned zerofill DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of course
-- ----------------------------

-- ----------------------------
-- Table structure for exam
-- ----------------------------
DROP TABLE IF EXISTS `exam`;
CREATE TABLE `exam` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '考试ID',
  `title` varchar(255) DEFAULT NULL COMMENT '考试名称',
  `courseId` int(20) DEFAULT NULL COMMENT '课程ID',
  `startDate` datetime DEFAULT NULL COMMENT '开始时间',
  `endDate` datetime DEFAULT NULL COMMENT '结束时间',
  `time` int(10) DEFAULT NULL COMMENT '考试时长',
  `isAnalysis` tinyint(1) DEFAULT NULL COMMENT '是否显示答案解析',
  `isCheck` tinyint(1) DEFAULT NULL COMMENT '是否人工审核',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of exam
-- ----------------------------
INSERT INTO `exam` VALUES ('1', 'ds', '132', '2018-01-19 15:47:51', '2018-01-20 15:47:56', null, null, null, '2');

-- ----------------------------
-- Table structure for major
-- ----------------------------
DROP TABLE IF EXISTS `major`;
CREATE TABLE `major` (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '专业ID',
  `name` varchar(50) DEFAULT NULL COMMENT '专业名称',
  `order` int(3) unsigned zerofill DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of major
-- ----------------------------
INSERT INTO `major` VALUES ('1', '软件工程', '014');
INSERT INTO `major` VALUES ('2', '信息管理', '013');

-- ----------------------------
-- Table structure for manager
-- ----------------------------
DROP TABLE IF EXISTS `manager`;
CREATE TABLE `manager` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `username` varchar(50) DEFAULT NULL COMMENT '管理员账号',
  `password` varchar(64) DEFAULT NULL COMMENT '密码',
  `state` int(5) DEFAULT NULL COMMENT '状态(0-禁用，1-正常）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of manager
-- ----------------------------
INSERT INTO `manager` VALUES ('34', 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', '1');
INSERT INTO `manager` VALUES ('40', 'test', '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08', '0');
INSERT INTO `manager` VALUES ('41', 'adb', '7e16a033d8a9e716f5572ef0b23b296050bcf72c23a67c1198b995de62701b20', '1');

-- ----------------------------
-- Table structure for paper
-- ----------------------------
DROP TABLE IF EXISTS `paper`;
CREATE TABLE `paper` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '试卷ID',
  `name` varchar(50) DEFAULT NULL COMMENT '试卷名称',
  `rule` varchar(255) DEFAULT NULL COMMENT '试卷规则',
  `totalScore` int(10) DEFAULT NULL COMMENT '总分',
  `passScore` int(10) DEFAULT NULL COMMENT '及格分',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of paper
-- ----------------------------

-- ----------------------------
-- Table structure for paper_question
-- ----------------------------
DROP TABLE IF EXISTS `paper_question`;
CREATE TABLE `paper_question` (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '试题ID',
  `paperId` int(20) unsigned DEFAULT NULL COMMENT '试卷ID',
  `questionId` int(20) unsigned DEFAULT NULL COMMENT '试题ID',
  `type` tinyint(10) unsigned DEFAULT NULL COMMENT '试题类型',
  `title` varchar(500) DEFAULT NULL COMMENT '题目',
  `analysis` varchar(500) DEFAULT NULL COMMENT '解析',
  `options` varchar(500) DEFAULT NULL COMMENT '选项',
  `answner` varchar(50) DEFAULT NULL COMMENT '答案',
  `keyword` varchar(300) DEFAULT NULL COMMENT '关键字',
  `keywordIMP` varchar(300) DEFAULT NULL COMMENT '重要关键字',
  `score` int(10) unsigned DEFAULT NULL COMMENT '分数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of paper_question
-- ----------------------------

-- ----------------------------
-- Table structure for question
-- ----------------------------
DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '试题ID',
  `courseId` int(20) unsigned DEFAULT NULL COMMENT '课程ID',
  `type` tinyint(10) unsigned DEFAULT NULL COMMENT '试题类型',
  `title` varchar(500) DEFAULT NULL COMMENT '题目',
  `analysis` varchar(500) DEFAULT NULL COMMENT '解析',
  `options` varchar(500) DEFAULT NULL COMMENT '选项',
  `answner` varchar(50) DEFAULT NULL COMMENT '答案',
  `keyword` varchar(300) DEFAULT NULL COMMENT '关键字',
  `keywordIMP` varchar(300) DEFAULT NULL COMMENT '重要关键字',
  `status` int(5) unsigned DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('4', '2', null, '2', '2', '2', '2', null, null, null);
INSERT INTO `user` VALUES ('11', '张', null, '丶', ' ', ' ', null, null, null, null);
INSERT INTO `user` VALUES ('12', '实施', null, null, null, null, null, null, null, null);

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
