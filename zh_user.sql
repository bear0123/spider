/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : zhihu

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-08-19 10:43:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for zh_user
-- ----------------------------
DROP TABLE IF EXISTS `zh_user`;
CREATE TABLE `zh_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '用户名',
  `introduction` varchar(255) DEFAULT NULL COMMENT '简介',
  `url` varchar(255) DEFAULT NULL COMMENT '个人域名',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `img_url` varchar(255) DEFAULT NULL COMMENT '头像地址',
  `business` varchar(255) DEFAULT NULL COMMENT '行业',
  `sex` tinyint(1) DEFAULT NULL COMMENT '1为男性，0为女性',
  `education` varchar(255) DEFAULT NULL COMMENT '毕业院校',
  `major` varchar(255) DEFAULT NULL COMMENT '专业',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `followees_count` int(10) DEFAULT NULL COMMENT '关注着人数',
  `followers_count` int(10) DEFAULT NULL COMMENT '关注了人数',
  `special_count` int(10) DEFAULT NULL COMMENT '专栏数',
  `follow_topic_count` int(10) DEFAULT NULL COMMENT '关注话题数',
  `pv_count` int(10) DEFAULT NULL COMMENT '主页访问数',
  `approval_count` int(10) DEFAULT NULL COMMENT '获得赞同数',
  `thank_count` int(10) DEFAULT NULL COMMENT '获得感谢数',
  `ask_count` int(10) DEFAULT NULL COMMENT '提问数',
  `answer_count` int(10) DEFAULT NULL COMMENT '回答数量',
  `started_count` int(10) DEFAULT NULL COMMENT '被收藏数',
  `public_edit_count` int(10) DEFAULT NULL COMMENT '公共编辑数',
  `article_count` int(10) DEFAULT NULL COMMENT '文章数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zh_user
-- ----------------------------
