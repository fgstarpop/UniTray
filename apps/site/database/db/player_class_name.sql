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

 Date: 09/12/2022 02:11:41
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for player_class_name
-- ----------------------------
DROP TABLE IF EXISTS `player_class_name`;
CREATE TABLE `player_class_name`  (
  `lv_id` tinyint(2) NOT NULL,
  `class_id` tinyint(3) UNSIGNED NOT NULL,
  `class_lv_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`lv_id`, `class_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of player_class_name
-- ----------------------------
INSERT INTO `player_class_name` VALUES (1, 1, 'Luyện khí');
INSERT INTO `player_class_name` VALUES (1, 2, 'Đoán Cốt');
INSERT INTO `player_class_name` VALUES (1, 3, 'Sa Đoạ');
INSERT INTO `player_class_name` VALUES (2, 1, 'Trúc cơ');
INSERT INTO `player_class_name` VALUES (2, 2, 'Khí Hải');
INSERT INTO `player_class_name` VALUES (2, 3, 'Hắc Hoá');
INSERT INTO `player_class_name` VALUES (3, 1, 'Kim đan');
INSERT INTO `player_class_name` VALUES (3, 2, 'Tụ Thần');
INSERT INTO `player_class_name` VALUES (3, 3, 'Ma Thần');
INSERT INTO `player_class_name` VALUES (4, 1, 'Nguyên Anh');
INSERT INTO `player_class_name` VALUES (4, 2, 'Luyện Hư');
INSERT INTO `player_class_name` VALUES (4, 3, 'Tâm Ma');
INSERT INTO `player_class_name` VALUES (5, 1, 'Bão đan');
INSERT INTO `player_class_name` VALUES (5, 2, 'Tông Sư');
INSERT INTO `player_class_name` VALUES (5, 3, 'Hắc Ma');
INSERT INTO `player_class_name` VALUES (6, 1, 'Hư thần');
INSERT INTO `player_class_name` VALUES (6, 2, 'Võ Vương');
INSERT INTO `player_class_name` VALUES (6, 3, 'Đại Ma');
INSERT INTO `player_class_name` VALUES (7, 1, 'Hóa thần');
INSERT INTO `player_class_name` VALUES (7, 2, 'Võ Quân');
INSERT INTO `player_class_name` VALUES (7, 3, 'Ma Vương');
INSERT INTO `player_class_name` VALUES (8, 1, 'Quy Khư');
INSERT INTO `player_class_name` VALUES (8, 2, 'Võ Hoàng');
INSERT INTO `player_class_name` VALUES (8, 3, 'Ma Hoàng');
INSERT INTO `player_class_name` VALUES (9, 1, 'Chân tiên');
INSERT INTO `player_class_name` VALUES (9, 2, 'Võ Đế');
INSERT INTO `player_class_name` VALUES (9, 3, 'Ma Tôn');
INSERT INTO `player_class_name` VALUES (10, 1, 'Kim tiên');
INSERT INTO `player_class_name` VALUES (10, 2, 'Phá Toái');
INSERT INTO `player_class_name` VALUES (10, 3, 'Ma Đế');
INSERT INTO `player_class_name` VALUES (11, 1, 'Tiên vương');
INSERT INTO `player_class_name` VALUES (11, 2, 'Võ Thần');
INSERT INTO `player_class_name` VALUES (11, 3, 'Ma Tổ');
INSERT INTO `player_class_name` VALUES (12, 1, 'Tiên đế');
INSERT INTO `player_class_name` VALUES (12, 2, 'Nhân Hoàng');
INSERT INTO `player_class_name` VALUES (12, 3, 'Cực Ma');

SET FOREIGN_KEY_CHECKS = 1;
