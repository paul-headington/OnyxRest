/*
 Navicat MySQL Data Transfer

 Source Server         : Example Local
 Source Server Type    : MySQL
 Source Server Version : 50537
 Source Host           : localhost
 Source Database       : bs-dev

 Target Server Type    : MySQL
 Target Server Version : 50537
 File Encoding         : utf-8

 Date: 07/28/2014 09:26:58 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `rest_resource`
-- ----------------------------
DROP TABLE IF EXISTS `rest_resource`;
CREATE TABLE `rest_resource` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `factory` varchar(255) DEFAULT NULL,
  `modelfactory` varchar(255) DEFAULT NULL,
  `tablename` varchar(255) DEFAULT NULL,
  `auth` tinyint(1) DEFAULT '0',
  `updatedon` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `postdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `rest_resource`
-- ----------------------------
BEGIN;
INSERT INTO `rest_resource` VALUES ('17', 'sorterstate', 'SorterStateTable', 'SorterState', 'sorter_state', '0', null, '2014-05-25 23:39:16'), ('18', 'test', 'TestTable', 'Test', 'test', '0', null, '2014-05-25 23:39:16'), ('19', 'user', 'OnyxUserTable', 'OnyxUser', 'user', '0', null, '2014-05-25 23:39:16');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
