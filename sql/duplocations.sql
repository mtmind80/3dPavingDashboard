/*
 Navicat MySQL Data Transfer

 Source Server         : LocaMYSQL
 Source Server Type    : MySQL
 Source Server Version : 50740 (5.7.40)
 Source Host           : localhost:3306
 Source Schema         : cocobeach

 Target Server Type    : MySQL
 Target Server Version : 50740 (5.7.40)
 File Encoding         : 65001

 Date: 28/12/2023 11:51:56
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for duplocations
-- ----------------------------
DROP TABLE IF EXISTS `duplocations`;
CREATE TABLE `duplocations`  (
  `bad` bigint(20) NULL DEFAULT NULL,
  `gid` bigint(20) NULL DEFAULT NULL,
  `addr` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `postal_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
