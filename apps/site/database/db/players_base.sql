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

 Date: 09/12/2022 02:12:13
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for players_base
-- ----------------------------
DROP TABLE IF EXISTS `players_base`;
CREATE TABLE `players_base`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `class` tinyint(4) NOT NULL,
  `gioi_tinh` tinyint(1) NOT NULL DEFAULT 1,
  `class_img` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_str` bigint(20) NOT NULL,
  `base_agi` bigint(20) NOT NULL,
  `base_vit` bigint(20) NOT NULL,
  `base_ene` bigint(20) NOT NULL,
  `base_point` bigint(20) NOT NULL DEFAULT 5,
  `base_hp` double(12, 2) NOT NULL,
  `base_max_hp` double(12, 2) UNSIGNED NOT NULL,
  `base_hp_regen` double(4, 2) NOT NULL,
  `base_mp` double(12, 2) NOT NULL,
  `base_max_mp` double(12, 2) UNSIGNED NOT NULL,
  `base_mp_regen` double(4, 2) NOT NULL,
  `base_can_co` double(4, 2) UNSIGNED NOT NULL,
  `base_atk` double(12, 2) NOT NULL,
  `base_def` double(12, 2) NOT NULL,
  `base_atk_speed` double(4, 2) NOT NULL,
  `base_luk` double(4, 2) NOT NULL,
  `base_crit` double(4, 2) NOT NULL,
  `base_crit_dmg` double(5, 2) NOT NULL,
  `base_dodge` double(4, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `players_base_class_index`(`class`, `gioi_tinh`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of players_base
-- ----------------------------
INSERT INTO `players_base` VALUES (1, 1, 1, 'asset/class/Nam Tiên.png', 15, 10, 15, 5, 5, 450.00, 450.00, 0.01, 200.00, 200.00, 0.01, 5.00, 20.00, 15.00, 1.00, 1.00, 0.00, 0.00, 0.00);
INSERT INTO `players_base` VALUES (2, 1, 0, 'asset/class/Nữ Tiên.png', 15, 10, 15, 5, 5, 450.00, 450.00, 0.01, 200.00, 200.00, 0.01, 5.00, 20.00, 15.00, 1.00, 1.00, 0.00, 0.00, 0.00);
INSERT INTO `players_base` VALUES (3, 2, 1, 'asset/class/Nam Nhân.png', 10, 10, 15, 10, 5, 400.00, 400.00, 0.01, 250.00, 250.00, 0.01, 5.00, 15.00, 13.00, 1.00, 1.00, 0.00, 0.00, 0.00);
INSERT INTO `players_base` VALUES (4, 2, 0, 'asset/class/Nữ Nhân.png', 10, 10, 15, 10, 5, 400.00, 400.00, 0.01, 250.00, 250.00, 0.01, 5.00, 15.00, 13.00, 1.00, 1.00, 0.00, 0.00, 0.00);
INSERT INTO `players_base` VALUES (5, 3, 1, 'asset/class/Nam Ma.png', 5, 10, 15, 15, 5, 300.00, 300.00, 0.01, 400.00, 400.00, 0.01, 5.00, 30.00, 10.00, 1.00, 1.00, 0.00, 0.00, 0.00);
INSERT INTO `players_base` VALUES (6, 3, 0, 'asset/class/Nữ Ma.png', 5, 10, 15, 15, 5, 300.00, 300.00, 0.01, 400.00, 400.00, 0.01, 5.00, 30.00, 10.00, 1.00, 1.00, 0.00, 0.00, 0.00);

SET FOREIGN_KEY_CHECKS = 1;
