/*
 Navicat Premium Data Transfer

 Source Server         : db-test
 Source Server Type    : MariaDB
 Source Server Version : 100425 (10.4.25-MariaDB)
 Source Host           : 103.116.104.175:3306
 Source Schema         : giangthe

 Target Server Type    : MariaDB
 Target Server Version : 100425 (10.4.25-MariaDB)
 File Encoding         : 65001

 Date: 09/12/2022 02:12:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for player_class
-- ----------------------------
DROP TABLE IF EXISTS `player_class`;
CREATE TABLE `player_class`  (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of player_class
-- ----------------------------
INSERT INTO `player_class` VALUES (1, 'Tiên');
INSERT INTO `player_class` VALUES (2, 'Nhân');
INSERT INTO `player_class` VALUES (3, 'Ma');

SET FOREIGN_KEY_CHECKS = 1;
