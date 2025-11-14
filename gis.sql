/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100432
 Source Host           : localhost:3306
 Source Schema         : gis

 Target Server Type    : MySQL
 Target Server Version : 100432
 File Encoding         : 65001

 Date: 14/06/2025 08:58:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for gis_jenis
-- ----------------------------
DROP TABLE IF EXISTS `gis_jenis`;
CREATE TABLE `gis_jenis`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `warna_default` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '#000000',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kode`(`kode`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gis_jenis
-- ----------------------------
INSERT INTO `gis_jenis` VALUES (1, 'INFRA', 'Infrastruktur', NULL, '#FF0000', NULL, '2025-06-14 08:11:20');
INSERT INTO `gis_jenis` VALUES (2, 'SOSIAL', 'Fasilitas Sosial', NULL, '#0000FF', NULL, '2025-06-14 08:11:20');
INSERT INTO `gis_jenis` VALUES (3, 'EKONOMI', 'Ekonomi & UMKM', NULL, '#00AA00', NULL, '2025-06-14 08:11:20');

-- ----------------------------
-- Table structure for gis_kategori
-- ----------------------------
DROP TABLE IF EXISTS `gis_kategori`;
CREATE TABLE `gis_kategori`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_id` int(11) NOT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kode`(`kode`) USING BTREE,
  INDEX `jenis_id`(`jenis_id`) USING BTREE,
  CONSTRAINT `gis_kategori_ibfk_1` FOREIGN KEY (`jenis_id`) REFERENCES `gis_jenis` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gis_kategori
-- ----------------------------
INSERT INTO `gis_kategori` VALUES (1, 1, 'JALAN', 'Jalan Desa', NULL, NULL);
INSERT INTO `gis_kategori` VALUES (2, 1, 'JEMBATAN', 'Jembatan', NULL, NULL);
INSERT INTO `gis_kategori` VALUES (3, 2, 'SD', 'Sekolah Dasar', NULL, NULL);
INSERT INTO `gis_kategori` VALUES (4, 2, 'PUSKESMAS', 'Puskesmas', NULL, NULL);

-- ----------------------------
-- Table structure for gis_lokasi
-- ----------------------------
DROP TABLE IF EXISTS `gis_lokasi`;
CREATE TABLE `gis_lokasi`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tipe` enum('point','polygon','line') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `geojson` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `deskripsi` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gis_master_desa
-- ----------------------------
DROP TABLE IF EXISTS `gis_master_desa`;
CREATE TABLE `gis_master_desa`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kecamatan_id` int(11) NOT NULL,
  `kode_desa` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_desa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `geojson_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `warna` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '#E1E1E1',
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kode_desa`(`kode_desa`) USING BTREE,
  INDEX `kecamatan_id`(`kecamatan_id`) USING BTREE,
  CONSTRAINT `gis_master_desa_ibfk_1` FOREIGN KEY (`kecamatan_id`) REFERENCES `gis_master_kecamatan` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gis_master_kecamatan
-- ----------------------------
DROP TABLE IF EXISTS `gis_master_kecamatan`;
CREATE TABLE `gis_master_kecamatan`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_kecamatan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_kecamatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `geojson_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `warna` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '#CCCCCC',
  `jumlah_desa` int(11) NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kode_kecamatan`(`kode_kecamatan`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gis_user
-- ----------------------------
DROP TABLE IF EXISTS `gis_user`;
CREATE TABLE `gis_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `last_login` datetime(0) NULL DEFAULT NULL,
  `status` enum('aktif','nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'aktif',
  `user_group_id` int(11) NOT NULL,
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE,
  INDEX `user_group_id`(`user_group_id`) USING BTREE,
  CONSTRAINT `gis_user_ibfk_1` FOREIGN KEY (`user_group_id`) REFERENCES `gis_user_group` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gis_user
-- ----------------------------
INSERT INTO `gis_user` VALUES (1, 'admin', '0192023a7bbd73250516f069df18b500', 'Administrator', NULL, NULL, NULL, 'aktif', 1, '2025-06-14 07:49:16');

-- ----------------------------
-- Table structure for gis_user_group
-- ----------------------------
DROP TABLE IF EXISTS `gis_user_group`;
CREATE TABLE `gis_user_group`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_group` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `level` int(2) NOT NULL DEFAULT 5 COMMENT 'Semakin kecil semakin tinggi hak akses',
  `created_at` datetime(0) NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gis_user_group
-- ----------------------------
INSERT INTO `gis_user_group` VALUES (1, 'Administrator', 'Akses penuh ke semua modul', 1, '2025-06-14 07:46:17');
INSERT INTO `gis_user_group` VALUES (2, 'Editor', 'Bisa tambah/edit data GIS', 3, '2025-06-14 07:46:17');
INSERT INTO `gis_user_group` VALUES (3, 'Viewer', 'Hanya bisa melihat data', 5, '2025-06-14 07:46:17');

-- ----------------------------
-- Table structure for gis_user_group_akses
-- ----------------------------
DROP TABLE IF EXISTS `gis_user_group_akses`;
CREATE TABLE `gis_user_group_akses`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_group_id` int(11) NOT NULL,
  `modul` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `can_view` tinyint(1) NULL DEFAULT 1,
  `can_create` tinyint(1) NULL DEFAULT 0,
  `can_edit` tinyint(1) NULL DEFAULT 0,
  `can_delete` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_group_id`(`user_group_id`) USING BTREE,
  CONSTRAINT `gis_user_group_akses_ibfk_1` FOREIGN KEY (`user_group_id`) REFERENCES `gis_user_group` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gis_user_group_akses
-- ----------------------------
INSERT INTO `gis_user_group_akses` VALUES (1, 1, 'lokasi', 1, 1, 1, 1);
INSERT INTO `gis_user_group_akses` VALUES (2, 1, 'user', 1, 1, 1, 1);
INSERT INTO `gis_user_group_akses` VALUES (3, 1, 'dashboard', 1, 1, 1, 1);
INSERT INTO `gis_user_group_akses` VALUES (4, 3, 'lokasi', 1, 0, 0, 0);
INSERT INTO `gis_user_group_akses` VALUES (5, 3, 'dashboard', 1, 0, 0, 0);

SET FOREIGN_KEY_CHECKS = 1;
