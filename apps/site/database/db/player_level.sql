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

 Date: 09/12/2022 02:12:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for player_level
-- ----------------------------
DROP TABLE IF EXISTS `player_level`;
CREATE TABLE `player_level`  (
  `lv` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `exp_re` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`lv`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 121 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'Class 0 = Tiên\r\nclass 1 = nhân\r\nclass 2 = ma' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of player_level
-- ----------------------------
INSERT INTO `player_level` VALUES (1, 500);
INSERT INTO `player_level` VALUES (2, 1500);
INSERT INTO `player_level` VALUES (3, 2500);
INSERT INTO `player_level` VALUES (4, 3500);
INSERT INTO `player_level` VALUES (5, 4500);
INSERT INTO `player_level` VALUES (6, 5500);
INSERT INTO `player_level` VALUES (7, 6500);
INSERT INTO `player_level` VALUES (8, 7500);
INSERT INTO `player_level` VALUES (9, 8500);
INSERT INTO `player_level` VALUES (10, 11000);
INSERT INTO `player_level` VALUES (11, 13500);
INSERT INTO `player_level` VALUES (12, 16000);
INSERT INTO `player_level` VALUES (13, 18500);
INSERT INTO `player_level` VALUES (14, 21000);
INSERT INTO `player_level` VALUES (15, 23500);
INSERT INTO `player_level` VALUES (16, 26000);
INSERT INTO `player_level` VALUES (17, 28500);
INSERT INTO `player_level` VALUES (18, 31000);
INSERT INTO `player_level` VALUES (19, 33500);
INSERT INTO `player_level` VALUES (20, 43500);
INSERT INTO `player_level` VALUES (21, 53500);
INSERT INTO `player_level` VALUES (22, 63500);
INSERT INTO `player_level` VALUES (23, 73500);
INSERT INTO `player_level` VALUES (24, 83500);
INSERT INTO `player_level` VALUES (25, 93500);
INSERT INTO `player_level` VALUES (26, 103500);
INSERT INTO `player_level` VALUES (27, 113500);
INSERT INTO `player_level` VALUES (28, 123500);
INSERT INTO `player_level` VALUES (29, 133500);
INSERT INTO `player_level` VALUES (30, 158500);
INSERT INTO `player_level` VALUES (31, 183500);
INSERT INTO `player_level` VALUES (32, 208500);
INSERT INTO `player_level` VALUES (33, 233500);
INSERT INTO `player_level` VALUES (34, 258500);
INSERT INTO `player_level` VALUES (35, 283500);
INSERT INTO `player_level` VALUES (36, 308500);
INSERT INTO `player_level` VALUES (37, 333500);
INSERT INTO `player_level` VALUES (38, 358500);
INSERT INTO `player_level` VALUES (39, 383500);
INSERT INTO `player_level` VALUES (40, 433500);
INSERT INTO `player_level` VALUES (41, 483500);
INSERT INTO `player_level` VALUES (42, 533500);
INSERT INTO `player_level` VALUES (43, 583500);
INSERT INTO `player_level` VALUES (44, 633500);
INSERT INTO `player_level` VALUES (45, 683500);
INSERT INTO `player_level` VALUES (46, 733500);
INSERT INTO `player_level` VALUES (47, 783500);
INSERT INTO `player_level` VALUES (48, 833500);
INSERT INTO `player_level` VALUES (49, 883500);
INSERT INTO `player_level` VALUES (50, 983500);
INSERT INTO `player_level` VALUES (51, 1083500);
INSERT INTO `player_level` VALUES (52, 1183500);
INSERT INTO `player_level` VALUES (53, 1283500);
INSERT INTO `player_level` VALUES (54, 1383500);
INSERT INTO `player_level` VALUES (55, 1483500);
INSERT INTO `player_level` VALUES (56, 1583500);
INSERT INTO `player_level` VALUES (57, 1683500);
INSERT INTO `player_level` VALUES (58, 1783500);
INSERT INTO `player_level` VALUES (59, 1883500);
INSERT INTO `player_level` VALUES (60, 2183500);
INSERT INTO `player_level` VALUES (61, 2483500);
INSERT INTO `player_level` VALUES (62, 2783500);
INSERT INTO `player_level` VALUES (63, 3083500);
INSERT INTO `player_level` VALUES (64, 3383500);
INSERT INTO `player_level` VALUES (65, 3683500);
INSERT INTO `player_level` VALUES (66, 3983500);
INSERT INTO `player_level` VALUES (67, 4283500);
INSERT INTO `player_level` VALUES (68, 4583500);
INSERT INTO `player_level` VALUES (69, 4883500);
INSERT INTO `player_level` VALUES (70, 5383500);
INSERT INTO `player_level` VALUES (71, 5883500);
INSERT INTO `player_level` VALUES (72, 6383500);
INSERT INTO `player_level` VALUES (73, 6883500);
INSERT INTO `player_level` VALUES (74, 7383500);
INSERT INTO `player_level` VALUES (75, 7883500);
INSERT INTO `player_level` VALUES (76, 8383500);
INSERT INTO `player_level` VALUES (77, 8883500);
INSERT INTO `player_level` VALUES (78, 9383500);
INSERT INTO `player_level` VALUES (79, 9883500);
INSERT INTO `player_level` VALUES (80, 10683500);
INSERT INTO `player_level` VALUES (81, 11483500);
INSERT INTO `player_level` VALUES (82, 12283500);
INSERT INTO `player_level` VALUES (83, 13083500);
INSERT INTO `player_level` VALUES (84, 13883500);
INSERT INTO `player_level` VALUES (85, 14683500);
INSERT INTO `player_level` VALUES (86, 15483500);
INSERT INTO `player_level` VALUES (87, 16283500);
INSERT INTO `player_level` VALUES (88, 17083500);
INSERT INTO `player_level` VALUES (89, 17883500);
INSERT INTO `player_level` VALUES (90, 19083500);
INSERT INTO `player_level` VALUES (91, 20283500);
INSERT INTO `player_level` VALUES (92, 21483500);
INSERT INTO `player_level` VALUES (93, 22683500);
INSERT INTO `player_level` VALUES (94, 23883500);
INSERT INTO `player_level` VALUES (95, 25083500);
INSERT INTO `player_level` VALUES (96, 26283500);
INSERT INTO `player_level` VALUES (97, 27483500);
INSERT INTO `player_level` VALUES (98, 28683500);
INSERT INTO `player_level` VALUES (99, 29883500);
INSERT INTO `player_level` VALUES (100, 31383500);
INSERT INTO `player_level` VALUES (101, 32883500);
INSERT INTO `player_level` VALUES (102, 34383500);
INSERT INTO `player_level` VALUES (103, 35883500);
INSERT INTO `player_level` VALUES (104, 37383500);
INSERT INTO `player_level` VALUES (105, 38883500);
INSERT INTO `player_level` VALUES (106, 40383500);
INSERT INTO `player_level` VALUES (107, 41883500);
INSERT INTO `player_level` VALUES (108, 43383500);
INSERT INTO `player_level` VALUES (109, 44883500);
INSERT INTO `player_level` VALUES (110, 46883500);
INSERT INTO `player_level` VALUES (111, 48883500);
INSERT INTO `player_level` VALUES (112, 50883500);
INSERT INTO `player_level` VALUES (113, 52883500);
INSERT INTO `player_level` VALUES (114, 54883500);
INSERT INTO `player_level` VALUES (115, 56883500);
INSERT INTO `player_level` VALUES (116, 58883500);
INSERT INTO `player_level` VALUES (117, 60883500);
INSERT INTO `player_level` VALUES (118, 62883500);
INSERT INTO `player_level` VALUES (119, 64883500);
INSERT INTO `player_level` VALUES (120, 69883500);

SET FOREIGN_KEY_CHECKS = 1;
