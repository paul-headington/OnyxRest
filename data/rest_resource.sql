/*
 Navicat MySQL Data Transfer

 Source Server         : Business Sorter Local
 Source Server Type    : MySQL
 Source Server Version : 50537
 Source Host           : localhost
 Source Database       : bs-dev

 Target Server Type    : MySQL
 Target Server Version : 50537
 File Encoding         : utf-8

 Date: 08/05/2014 16:11:03 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `onyx_rest_resource`
-- ----------------------------
DROP TABLE IF EXISTS `onyx_rest_resource`;
CREATE TABLE `onyx_rest_resource` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `factory` varchar(255) DEFAULT NULL,
  `modelfactory` varchar(255) DEFAULT NULL,
  `tablename` varchar(255) DEFAULT NULL,
  `auth` tinyint(1) DEFAULT '0',
  `get_only` tinyint(1) DEFAULT '0',
  `post_only` tinyint(1) DEFAULT '0',
  `updatedon` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `postdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS = 1;
