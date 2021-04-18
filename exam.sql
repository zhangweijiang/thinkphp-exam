/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : exam

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2021-04-01 18:08:36
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='考试表';

-- ----------------------------
-- Records of exam
-- ----------------------------
INSERT INTO `exam` VALUES ('14', 'PHP初级考试', '25', '计算机网络', '8', '信息管理', '1', '不准作弊', '线上考试', '2021-03-30 17:00:00', '2021-12-30 18:00:00', '30', '1', '1', '2', null, null, '/upload/20190411\\bf65918d1462b2670a95b09f7436cb34.jpg', '8', '40', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='专业表';

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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of manager
-- ----------------------------
INSERT INTO `manager` VALUES ('34', 'admin', 'aaffebecec560fec66e75f24062224ffa4e07696d2ae9a1fee3707c3f8fd9373', '1', null, null);

-- ----------------------------
-- Table structure for paper
-- ----------------------------
DROP TABLE IF EXISTS `paper`;
CREATE TABLE `paper` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '试卷ID',
  `major_id` int(10) DEFAULT '0' COMMENT '专业ID',
  `course_id` int(10) DEFAULT '0' COMMENT '课程ID',
  `name` varchar(50) DEFAULT NULL COMMENT '试卷名称',
  `score` int(10) DEFAULT '0' COMMENT '试卷总分（试卷中试题分数之和）',
  `pass_score` int(10) DEFAULT '0' COMMENT '及格分数',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态(备用)',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='试卷表';

-- ----------------------------
-- Records of paper
-- ----------------------------
INSERT INTO `paper` VALUES ('1', '2', '4', 'PHP初级考试', '50', '60', '0', null, null);

-- ----------------------------
-- Table structure for paper_question
-- ----------------------------
DROP TABLE IF EXISTS `paper_question`;
CREATE TABLE `paper_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `paper_id` int(20) DEFAULT NULL COMMENT '试卷id',
  `question_id` int(10) DEFAULT NULL COMMENT '试题id',
  `name` varchar(300) DEFAULT NULL COMMENT '试题标题',
  `title` varchar(500) DEFAULT NULL COMMENT '试题标题',
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='试卷试题表';

-- ----------------------------
-- Records of paper_question
-- ----------------------------
INSERT INTO `paper_question` VALUES ('1', '1', '1', 'PHP是世界上最好的语言？', '<p>PHP是世界上最好的语言？<br></p>', '1', '正确||错误', '1', 'PHP是世界上最好的语言', '', '', '10.0', '0', '0000-00-00 00:00:00', null);
INSERT INTO `paper_question` VALUES ('5', '1', '2', '下面针对PHP的描述错误的是( )', '<p>下面针对PHP的描述错误的是( )<br></p>', '2', '是一种脚本语言||是免费的，开源的||只能运行在windows操作系统中||执行效率很高', '3', 'PHP可以在windows和linux上运行', '', '', '10.0', '0', '0000-00-00 00:00:00', null);
INSERT INTO `paper_question` VALUES ('7', '1', '5', '判断一个变量是否存在的函数', '<p>判断一个变量是否存在的函数<br></p>', '4', 'isset', 'isset', 'isset', '', '', '10.0', '0', '0000-00-00 00:00:00', null);
INSERT INTO `paper_question` VALUES ('8', '1', '6', '请简述一下htmlspecialchars和htmlentities的区别？', '<p><span style=\"outline: 0px; --tw-shadow:0 0 #0000 ; --tw-ring-inset:var(--tw-empty, ); --tw-ring-offset-width:0px; --tw-ring-offset-color:#fff; --tw-ring-color:rgba(66, 153, 225, 0.5); --tw-ring-offset-shadow:0 0 #0000; --tw-ring-shadow:0 0 #0000 ; margin: 0px; padding: 0px; overflow-wrap: break-word; color: rgb(51, 51, 51); font-size: 16px; text-indent: -24px; font-family: 宋体;\">请简述一下</span><span style=\"outline: 0px; --tw-shadow:0 0 #0000 ; --tw-ring-inset:var(--tw-empty, ); --tw-ring-offset-w', '5', '', '', '这两个函数的功能都是将特殊字符转换为HTML字符编码（有& \' \" < 和 > 这几个特殊符号），防止字符标记被浏览器执行。但是htmlentities将转换所有的html标记，连同里面的它无法识别的中文字符也给转化了。', 'htmlentities', 'htmlentities ||', '10.0', '0', '0000-00-00 00:00:00', null);
INSERT INTO `paper_question` VALUES ('9', '1', '4', '函数的参数传递包括（ ）', '<p>函数的参数传递包括（ ）<br></p>', '3', '按值传递||按引用传递||按变量传递||按作用域传递', '1||2', '按值传递和按引用传递', '', '', '10.0', '0', '0000-00-00 00:00:00', null);

-- ----------------------------
-- Table structure for question
-- ----------------------------
DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '试题ID',
  `major_id` int(10) DEFAULT '0' COMMENT '试题ID',
  `course_id` int(10) DEFAULT '0' COMMENT '课程ID',
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='试题表';

-- ----------------------------
-- Records of question
-- ----------------------------
INSERT INTO `question` VALUES ('1', '2', '4', 'PHP是世界上最好的语言？', '1', '<p>PHP是世界上最好的语言？<br></p>', 'PHP是世界上最好的语言', '正确||错误', '1', '', '', null, '10', null, null, '1');
INSERT INTO `question` VALUES ('2', '2', '4', '下面针对PHP的描述错误的是( )', '2', '<p>下面针对PHP的描述错误的是( )<br></p>', 'PHP可以在windows和linux上运行', '是一种脚本语言||是免费的，开源的||只能运行在windows操作系统中||执行效率很高', '3', '', '', null, '10', null, null, '2');
INSERT INTO `question` VALUES ('3', '2', '4', 'php中，不等运算符是( )', '3', '<p>php中，不等运算符是( ) <br></p>', '!= 或 <>', '≠||!=||<>||><', '2||3', '', '', null, '10', null, null, '3');
INSERT INTO `question` VALUES ('4', '2', '4', '函数的参数传递包括（ ）', '3', '<p>函数的参数传递包括（ ）<br></p>', '按值传递和按引用传递', '按值传递||按引用传递||按变量传递||按作用域传递', '1||2', '', '', null, '10', null, null, '4');
INSERT INTO `question` VALUES ('5', '2', '4', '判断一个变量是否存在的函数', '4', '<p>判断一个变量是否存在的函数<br></p>', 'isset', 'isset', 'isset', '', '', null, '10', null, null, '5');
INSERT INTO `question` VALUES ('6', '2', '4', '请简述一下htmlspecialchars和htmlentities的区别？', '5', '<p><span style=\"outline: 0px; --tw-shadow:0 0 #0000 ; --tw-ring-inset:var(--tw-empty, ); --tw-ring-offset-width:0px; --tw-ring-offset-color:#fff; --tw-ring-color:rgba(66, 153, 225, 0.5); --tw-ring-offset-shadow:0 0 #0000; --tw-ring-shadow:0 0 #0000 ; margin: 0px; padding: 0px; overflow-wrap: break-word; color: rgb(51, 51, 51); font-size: 16px; text-indent: -24px; font-family: 宋体;\">请简述一下</span><span style=\"outline: 0px; --tw-shadow:0 0 #0000 ; --tw-ring-inset:var(--tw-empty, ); --tw-ring-offset-w', '这两个函数的功能都是将特殊字符转换为HTML字符编码（有& \' \" < 和 > 这几个特殊符号），防止字符标记被浏览器执行。但是htmlentities将转换所有的html标记，连同里面的它无法识别的中文字符也给转化了。', '', '', 'htmlentities', 'htmlentities ||', null, '10', null, null, '6');
INSERT INTO `question` VALUES ('8', '2', '4', 'php中布尔类型数据只有两个值：真和假。', '1', '<p><span style=\"color: rgb(77, 77, 77); font-family: -apple-system, &quot;SF UI Text&quot;, Arial, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 16px; font-variant-ligatures: common-ligatures;\">php中布尔类型数据只有两个值：真和假。</span><br></p>', '逻辑的真和假', '正确||错误', '1', '', '', null, '10', null, null, '7');
INSERT INTO `question` VALUES ('9', '1', '1', '1+1=3', '1', '<p>1+1=3<br></p>', '2', '正确||错误', '2', '', '', null, '20', null, null, '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COMMENT='考生表';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('22', '张先生', '张某某', 'aaffebecec560fec66e75f24062224ffa4e07696d2ae9a1fee3707c3f8fd9373', '1', '2017-04-15 00:00:00', '0', '13665994204', '1', '2018-03-08 11:00:00', '2018-03-10 01:00:00', '/upload/20180309\\8e8125942bdcd13f16480bb6689301eb.png', '名', null);
INSERT INTO `user` VALUES ('26', 'admin', '管理员', 'aaffebecec560fec66e75f24062224ffa4e07696d2ae9a1fee3707c3f8fd9373', '2', '2017-04-06 00:00:00', '0', '123', '1', '2018-03-10 06:33:22', '2018-04-26 15:08:38', '/upload/20180322\\ae4399a2336cf9dcc4964df64717878b.jpg', '', '1561302459@qq.com');
INSERT INTO `user` VALUES ('27', 'hyb', '何艺宝', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', null, null, '0', null, '1', '2018-03-16 09:30:47', null, null, null, null);
INSERT INTO `user` VALUES ('28', 'yjt', '严净汀', '19de6b5645734410950bbaaddc92e87df7e1ee9824ec959f4fa16ec3f4fcc6e2', null, null, '0', null, '1', '2018-03-16 09:34:49', null, null, null, null);
INSERT INTO `user` VALUES ('29', 'zwj', '13665994204', 'aaffebecec560fec66e75f24062224ffa4e07696d2ae9a1fee3707c3f8fd9373', null, null, '0', null, '1', '2018-03-19 14:44:49', null, null, null, null);
INSERT INTO `user` VALUES ('36', 'hades', 'hades', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '1', null, '0', null, '1', '2018-03-29 11:08:56', null, null, null, null);
INSERT INTO `user` VALUES ('37', 'hjl', 'hjl', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '1', null, '0', null, '1', '2018-03-29 11:09:11', null, null, null, null);

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
  `time` int(10) DEFAULT '0' COMMENT '考试时长：分钟为单位',
  `pass` tinyint(1) DEFAULT '0' COMMENT '是否通过,1通过,0未通过',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户考试表';

-- ----------------------------
-- Records of user_exam
-- ----------------------------
INSERT INTO `user_exam` VALUES ('1', '29', 'zwj', '14', '2021-04-01 12:08:15', '0', '2', '2021-04-01 16:20:25', '30', '0');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户试题表';

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
