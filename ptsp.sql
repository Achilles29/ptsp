/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.10-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: ptsp
-- ------------------------------------------------------
-- Server version	10.11.10-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `antrian`
--

DROP TABLE IF EXISTS `antrian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `antrian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `layanan_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time DEFAULT NULL,
  `status` enum('terdaftar','dipanggil','selesai','batal') DEFAULT 'terdaftar',
  `nomor_antrian` varchar(20) NOT NULL,
  `hadir` tinyint(1) DEFAULT 0,
  `updated_by` int(11) DEFAULT NULL,
  `updated_role` enum('superadmin','admin_layanan','masyarakat') DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `called_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `fk_antrian_layanan` (`layanan_id`) USING BTREE,
  CONSTRAINT `antrian_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_antrian_layanan` FOREIGN KEY (`layanan_id`) REFERENCES `jenis_layanan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antrian`
--

LOCK TABLES `antrian` WRITE;
/*!40000 ALTER TABLE `antrian` DISABLE KEYS */;
INSERT INTO `antrian` VALUES
(1,30,1,'2026-02-12',NULL,'dipanggil','A001',1,30,'masyarakat','2026-02-12 09:31:06','2026-02-12 09:08:58','2026-02-12 09:08:58'),
(2,NULL,3,'2026-02-12',NULL,'terdaftar','A002',1,NULL,NULL,'2026-02-12 09:31:50','2026-02-12 09:31:50',NULL),
(3,NULL,30,'2026-02-16',NULL,'terdaftar','E001',1,NULL,NULL,'2026-02-16 11:58:25','2026-02-16 11:58:25',NULL),
(4,NULL,68,'2026-02-16',NULL,'terdaftar','P001',1,NULL,NULL,'2026-02-16 11:59:54','2026-02-16 11:59:54',NULL),
(5,NULL,91,'2026-02-16',NULL,'terdaftar','R001',1,NULL,NULL,'2026-02-16 12:01:00','2026-02-16 12:01:00',NULL),
(6,NULL,69,'2026-02-16',NULL,'terdaftar','P002',1,NULL,NULL,'2026-02-16 12:01:30','2026-02-16 12:01:30',NULL),
(7,NULL,76,'2026-02-16',NULL,'terdaftar','P003',1,NULL,NULL,'2026-02-16 12:02:23','2026-02-16 12:02:23',NULL),
(8,NULL,69,'2026-02-16',NULL,'terdaftar','P004',1,NULL,NULL,'2026-02-16 12:08:46','2026-02-16 12:08:46',NULL),
(9,NULL,69,'2026-02-16',NULL,'terdaftar','P005',1,NULL,NULL,'2026-02-16 12:09:50','2026-02-16 12:09:50',NULL),
(10,NULL,26,'2026-02-16',NULL,'terdaftar','D001',1,NULL,NULL,'2026-02-16 12:10:03','2026-02-16 12:10:03',NULL),
(11,NULL,32,'2026-02-16',NULL,'terdaftar','F001',1,NULL,NULL,'2026-02-16 12:12:07','2026-02-16 12:12:07',NULL),
(12,NULL,77,'2026-02-16',NULL,'terdaftar','P006',1,NULL,NULL,'2026-02-16 12:12:27','2026-02-16 12:12:27',NULL),
(13,NULL,51,'2026-02-16',NULL,'terdaftar','K001',1,NULL,NULL,'2026-02-16 12:12:45','2026-02-16 12:12:45',NULL),
(14,NULL,69,'2026-02-16',NULL,'terdaftar','P007',1,NULL,NULL,'2026-02-16 12:13:59','2026-02-16 12:13:59',NULL),
(15,NULL,69,'2026-02-16',NULL,'terdaftar','P008',1,NULL,NULL,'2026-02-16 12:14:16','2026-02-16 12:14:16',NULL),
(16,NULL,68,'2026-02-16',NULL,'terdaftar','P009',1,NULL,NULL,'2026-02-16 12:14:33','2026-02-16 12:14:33',NULL),
(17,NULL,77,'2026-02-16',NULL,'terdaftar','P010',1,NULL,NULL,'2026-02-16 12:14:43','2026-02-16 12:14:43',NULL),
(18,NULL,69,'2026-02-16',NULL,'terdaftar','P011',1,NULL,NULL,'2026-02-16 12:16:25','2026-02-16 12:16:25',NULL),
(19,NULL,69,'2026-02-16',NULL,'terdaftar','P012',1,NULL,NULL,'2026-02-16 12:16:35','2026-02-16 12:16:35',NULL),
(20,NULL,8,'2026-02-16',NULL,'terdaftar','A001',1,NULL,NULL,'2026-02-16 12:16:38','2026-02-16 12:16:38',NULL),
(21,NULL,68,'2026-02-16',NULL,'terdaftar','P013',1,NULL,NULL,'2026-02-16 12:16:52','2026-02-16 12:16:52',NULL),
(22,NULL,77,'2026-02-16',NULL,'terdaftar','P014',1,NULL,NULL,'2026-02-16 12:17:29','2026-02-16 12:17:29',NULL),
(23,NULL,51,'2026-02-16',NULL,'terdaftar','K002',1,NULL,NULL,'2026-02-16 14:01:59','2026-02-16 14:01:59',NULL),
(24,NULL,75,'2026-02-16',NULL,'terdaftar','P015',1,NULL,NULL,'2026-02-16 14:06:13','2026-02-16 14:06:13',NULL),
(25,NULL,30,'2026-02-16',NULL,'terdaftar','E002',1,NULL,NULL,'2026-02-16 14:12:23','2026-02-16 14:12:23',NULL),
(26,NULL,62,'2026-02-16',NULL,'terdaftar','N001',1,NULL,NULL,'2026-02-16 14:12:52','2026-02-16 14:12:52',NULL),
(27,NULL,76,'2026-02-16',NULL,'terdaftar','P016',1,NULL,NULL,'2026-02-16 14:13:42','2026-02-16 14:13:42',NULL),
(28,NULL,83,'2026-02-16',NULL,'terdaftar','R002',1,NULL,NULL,'2026-02-16 14:15:32','2026-02-16 14:15:32',NULL),
(29,NULL,83,'2026-02-16',NULL,'terdaftar','R003',1,NULL,NULL,'2026-02-16 14:16:04','2026-02-16 14:16:04',NULL),
(30,NULL,91,'2026-02-16',NULL,'terdaftar','R004',1,NULL,NULL,'2026-02-16 14:24:14','2026-02-16 14:24:14',NULL),
(31,NULL,91,'2026-02-16',NULL,'terdaftar','R005',1,NULL,NULL,'2026-02-16 14:24:27','2026-02-16 14:24:27',NULL),
(32,NULL,31,'2026-02-16',NULL,'terdaftar','E003',1,NULL,NULL,'2026-02-16 14:29:24','2026-02-16 14:29:24',NULL),
(33,NULL,29,'2026-02-16',NULL,'terdaftar','D002',1,NULL,NULL,'2026-02-16 14:31:25','2026-02-16 14:31:25',NULL),
(34,NULL,42,'2026-02-16',NULL,'terdaftar','H001',1,NULL,NULL,'2026-02-16 14:31:29','2026-02-16 14:31:29',NULL),
(35,NULL,51,'2026-02-16',NULL,'terdaftar','K003',1,NULL,NULL,'2026-02-16 14:31:32','2026-02-16 14:31:32',NULL),
(36,NULL,85,'2026-02-16',NULL,'terdaftar','R006',1,NULL,NULL,'2026-02-16 14:39:40','2026-02-16 14:39:40',NULL),
(37,NULL,91,'2026-02-16',NULL,'terdaftar','R007',1,NULL,NULL,'2026-02-16 14:41:40','2026-02-16 14:41:40',NULL),
(38,NULL,89,'2026-02-16',NULL,'terdaftar','R008',1,NULL,NULL,'2026-02-16 14:42:40','2026-02-16 14:42:40',NULL),
(39,NULL,91,'2026-02-16',NULL,'terdaftar','R009',1,NULL,NULL,'2026-02-16 14:42:46','2026-02-16 14:42:46',NULL),
(40,NULL,90,'2026-02-16',NULL,'terdaftar','R010',1,NULL,NULL,'2026-02-16 14:50:40','2026-02-16 14:50:40',NULL),
(41,NULL,45,'2026-02-16',NULL,'terdaftar','I001',1,NULL,NULL,'2026-02-16 14:53:00','2026-02-16 14:53:00',NULL),
(42,NULL,45,'2026-02-16',NULL,'terdaftar','I002',1,NULL,NULL,'2026-02-16 14:55:53','2026-02-16 14:55:53',NULL),
(43,NULL,69,'2026-02-16',NULL,'terdaftar','P017',1,NULL,NULL,'2026-02-16 14:58:59','2026-02-16 14:58:59',NULL);
/*!40000 ALTER TABLE `antrian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pengirim_id` int(11) NOT NULL,
  `penerima_id` int(11) NOT NULL,
  `pesan` text NOT NULL,
  `waktu` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  KEY `pengirim_id` (`pengirim_id`) USING BTREE,
  KEY `penerima_id` (`penerima_id`) USING BTREE,
  CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`pengirim_id`) REFERENCES `users` (`id`),
  CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`penerima_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat`
--

LOCK TABLES `chat` WRITE;
/*!40000 ALTER TABLE `chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hasil_layanan`
--

DROP TABLE IF EXISTS `hasil_layanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hasil_layanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `antrian_id` int(11) NOT NULL,
  `jenis_hasil` enum('konsultasi','produk_hukum') NOT NULL,
  `ringkasan_konsultasi` text DEFAULT NULL,
  `jenis_produk_hukum` varchar(100) DEFAULT NULL,
  `nomor_produk` varchar(100) DEFAULT NULL,
  `tanggal_produk` date DEFAULT NULL,
  `catatan_petugas` text DEFAULT NULL,
  `selesai_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_role` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_hasil_antrian` (`antrian_id`),
  CONSTRAINT `fk_hasil_antrian` FOREIGN KEY (`antrian_id`) REFERENCES `antrian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hasil_layanan`
--

LOCK TABLES `hasil_layanan` WRITE;
/*!40000 ALTER TABLE `hasil_layanan` DISABLE KEYS */;
/*!40000 ALTER TABLE `hasil_layanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instansi`
--

DROP TABLE IF EXISTS `instansi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instansi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_instansi` varchar(50) NOT NULL,
  `nama_instansi` varchar(255) NOT NULL,
  `sektor_id` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `loket` varchar(100) DEFAULT NULL,
  `status_layanan` enum('buka','tutup') DEFAULT 'buka',
  `jam_tutup_pendaftaran` time NOT NULL DEFAULT '15:30:00',
  `jam_layanan_mulai` time NOT NULL DEFAULT '08:30:00',
  `jam_layanan_selesai` time NOT NULL DEFAULT '16:00:00',
  `jam_tutup_kantor` time NOT NULL DEFAULT '16:30:00',
  `status_layanan_mode` enum('otomatis','buka','tutup') NOT NULL DEFAULT 'otomatis',
  `is_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_instansi_sektor` (`sektor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instansi`
--

LOCK TABLES `instansi` WRITE;
/*!40000 ALTER TABLE `instansi` DISABLE KEYS */;
INSERT INTO `instansi` VALUES
(1,'DPMPTSP','DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU',2,'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU','1','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 07:23:48','2026-02-12 10:07:03'),
(2,'DINSOSPPKB','DINAS SOSIAL PEMBERDAYAAN PEREMPUAN DAN KELUARGA BERENCANA',1,'DINAS SOSIAL PEMBERDAYAAN PEREMPUAN DAN KELUARGA BERENCANA','2','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:52','2026-02-12 09:04:00'),
(3,'DINKES','DINAS KESEHATAN',1,'DINAS KESEHATAN','3','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:52','2026-02-12 09:04:00'),
(4,'DLH','DINAS LINGKUNGAN HIDUP',1,'DINAS LINGKUNGAN HIDUP','4','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:52','2026-02-12 09:04:00'),
(5,'BPPKAD','BADAN PENDAPATAN, PENGELOLAAN KEUANGAN DAN ASET DAERAH',1,'BADAN PENDAPATAN, PENGELOLAAN KEUANGAN DAN ASET DAERAH','5','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:52','2026-02-12 09:04:00'),
(6,'DISDUKCAPIL','DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL',1,'DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL','6','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:52','2026-02-12 09:04:00'),
(7,'DPUTARU','DINAS PEKERJAAN UMUM DAN PENATAAN RUANG',1,'DINAS PEKERJAAN UMUM DAN PENATAAN RUANG','7','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:52','2026-02-12 09:04:00'),
(8,'DINPERINNAKER','DINAS PERINDUSTRIAN DAN TENAGA KERJA',1,'DINAS PERINDUSTRIAN DAN TENAGA KERJA','8','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:52','2026-02-12 09:04:00'),
(9,'PDAM','PERUSAHAAN DAERAH AIR MINUM',1,'PERUSAHAAN DAERAH AIR MINUM','9','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:52','2026-02-12 09:04:00'),
(10,'KEJARI','KEJAKSAAN NEGERI',1,'KEJAKSAAN NEGERI','10','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:52','2026-02-12 09:04:00'),
(11,'ATR/BPN','BADAN PERTANAHAN NASIONAL',1,'BADAN PERTANAHAN NASIONAL','11','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:52','2026-02-12 09:04:00'),
(12,'KEMENAG','KEMENTERIAN AGAMA',1,'KEMENTERIAN AGAMA','12','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:52','2026-02-12 09:04:00'),
(13,'KP2KP','KANTOR PELAYANAN, PENYULUHAN, DAN KONSULTASI PERPAJAKAN',1,'KANTOR PELAYANAN, PENYULUHAN, DAN KONSULTASI PERPAJAKAN','13','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:53','2026-02-12 09:04:00'),
(14,'PENGADILAN AGAMA','PENGADILAN AGAMA',1,'PENGADILAN AGAMA','14','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:53','2026-02-12 09:04:00'),
(15,'POLRES','KEPOLISIAN NEGARA REPUBLIK INDONESIA',1,'KEPOLISIAN NEGARA REPUBLIK INDONESIA','15','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:53','2026-02-12 09:04:00'),
(16,'BPJS KESEHATAN','BPJS KESEHATAN',1,'BPJS KESEHATAN','16','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:53','2026-02-12 09:04:00'),
(17,'BPJS KETENAGAKERJAAN','BPJS KETENAGAKERJAAN',1,'BPJS KETENAGAKERJAAN','17','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:53','2026-02-12 09:04:00'),
(18,'BRI','BANK RAKYAT INDONESIA',1,'BANK RAKYAT INDONESIA','18','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',1,'2025-11-08 06:24:53','2026-02-12 09:04:00'),
(19,'BANK JATENG','BANK BPD JATENG',1,'BANK BPD JATENG','19','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',0,'2025-11-08 06:24:53','2026-02-12 09:04:00'),
(20,'TASPEN','TABUNGAN DAN ASURANSI PEGAWAI NEGERI',1,'TABUNGAN DAN ASURANSI PEGAWAI NEGERI','20','buka','15:30:00','08:30:00','16:00:00','16:30:00','otomatis',0,'2025-11-08 06:24:53','2026-02-12 09:04:00');
/*!40000 ALTER TABLE `instansi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jenis_layanan`
--

DROP TABLE IF EXISTS `jenis_layanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jenis_layanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instansi_id` int(11) DEFAULT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `kode_huruf` varchar(10) DEFAULT NULL,
  `nama_layanan` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jenis_layanan`
--

LOCK TABLES `jenis_layanan` WRITE;
/*!40000 ALTER TABLE `jenis_layanan` DISABLE KEYS */;
INSERT INTO `jenis_layanan` VALUES
(1,1,'DPM1','A','Pelayanan Perizinan Berusaha OSS-RBA','Pelayanan Perizinan Berusaha OSS-RBA','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(2,1,'DPM2','A','Pemenuhan Komitmen OSS yang menjadi kewenangan Kabupaten','Pemenuhan Komitmen OSS yang menjadi kewenangan Kabupaten','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(3,1,'DPM3','A','Izin sesuai dengan pelimpahan kewenangan ','Izin sesuai dengan pelimpahan kewenangan ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(4,1,'DPM4','A','Pelayanan SICANTIK','Pelayanan SICANTIK','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(5,1,'DPM5','A','Pelayanan perizinan Nonberusaha','Pelayanan perizinan Nonberusaha','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(6,1,'DPM6','A','Konsultasi Perizinan','Konsultasi Perizinan','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(7,1,'DPM7','A','Pengaduan','Pengaduan','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(8,1,'DPM8','A','Pelayanan Legalisir','Pelayanan Legalisir','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(9,2,'PBI KIS','B','Rekomendasi PBI KIS','Rekomendasi PBI KIS','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(10,3,'DKK1','C','Izin Operasional Puskesmas ','Izin Operasional Puskesmas ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(11,3,'DKK2','C','Izin Mendirikan RS type C dan D','Izin Mendirikan RS type C dan D','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(12,3,'DKK3','C','Izin Operasional RS type C dan D','Izin Operasional RS type C dan D','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(13,3,'DKK4','C','Izin Penyelenggaraan Optical','Izin Penyelenggaraan Optical','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(14,3,'DKK5','C','Izin Apotik ','Izin Apotik ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(15,3,'DKK6','C','Izin Operasional Klinik ','Izin Operasional Klinik ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(16,3,'DKK7','C','Izin Toko Obat ','Izin Toko Obat ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(17,3,'DKK8','C','Izin PIRT','Izin PIRT','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(18,3,'DKK9','C','Izin Operasional Laboratorium Klinik ','Izin Operasional Laboratorium Klinik ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(19,3,'DKK10','C','Izin Praktek Nakes ','Izin Praktek Nakes ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(20,3,'DKK11','C','Sertifikat Laik Higiene Sanitasi ','Sertifikat Laik Higiene Sanitasi ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(21,3,'DKK12','C','Izin Tenaga HATTRA','Izin Tenaga HATTRA','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(22,3,'DKK13','C','Rekomendasi Jaminan Kesehatan Rembang Sehat (JKRS)','Rekomendasi Jaminan Kesehatan Rembang Sehat (JKRS)','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(23,4,'DLH1','D','Konsultasi  Perizinan Lingkungan ','Konsultasi  Perizinan Lingkungan ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(24,4,'DLH2','D','Rekomendasi Izin SPPL','Rekomendasi Izin SPPL','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(25,4,'DLH3','D','Rekomendasi Izin UKL/UPL   ','Rekomendasi Izin UKL/UPL   ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(26,4,'DLH4','D','Rekomendasi Izin AMDAL  ','Rekomendasi Izin AMDAL  ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(27,4,'DLH5','D','Pertek Baku Mutu Air Limbah (BMAL)','Pertek Baku Mutu Air Limbah (BMAL)','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(28,4,'DLH6','D','Rintek TPS LB3','Rintek TPS LB3','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(29,4,'DLH7','D','Rekomendasi Izin Kubur / Pemakaman ','Rekomendasi Izin Kubur / Pemakaman ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(30,5,'BPPKAD1','E','Pelayanan Pajak Daerah','Pelayanan Pajak Daerah','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(31,5,'BPPKAD2','E','Pelayanan Perubahan SPPT PBB-PP ','Pelayanan Perubahan SPPT PBB-PP ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(32,6,'CAPIL1','F','Pelayanan  Akta Kelahiran','Pelayanan  Akta Kelahiran','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(33,6,'CAPIL2','F','Pelayanan Akta Kematian','Pelayanan Akta Kematian','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(34,6,'CAPIL3','F','Pelayanan Kutipan Kedua ','Pelayanan Kutipan Kedua ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(35,6,'CAPIL4','F','Pelayanan  KTP','Pelayanan  KTP','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(36,6,'CAPIL5','F','Pelayanan KK','Pelayanan KK','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(37,6,'CAPIL6','F','Pelayanan KIA','Pelayanan KIA','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(38,6,'CAPIL7','F','Pelayanan Surat Pindah ','Pelayanan Surat Pindah ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(39,7,'PU1','G','Pelayanan Informasi Tata Ruang ','Pelayanan Informasi Tata Ruang ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(40,7,'PU2','G','Pelayanan Persetujuan Bangunan Gedung (PBG)','Pelayanan Persetujuan Bangunan Gedung (PBG)','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(41,7,'PU3','G','Pelayanan Sertifikat Laik Fungsi (SLF) ','Pelayanan Sertifikat Laik Fungsi (SLF) ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(42,8,'SIINAS','H','Pelayanan SIINAS','Pelayanan SIINAS','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(43,9,'PDAM1','I','Pelayanan Pengaduan Pelanggan ','Pelayanan Pengaduan Pelanggan ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(44,9,'PDAM2','I','Pendaftaran Sambungan Rumah ','Pendaftaran Sambungan Rumah ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(45,9,'PDAM3','I','Pembayaran rekening PDAM','Pembayaran rekening PDAM','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(46,9,'PDAM4','I','Pembayaran Sambungan Rumah Baru PDAM ','Pembayaran Sambungan Rumah Baru PDAM ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(47,10,'KJKS1','J','Pelayanan Hukum','Pelayanan Hukum','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(48,10,'KJKS2','J','Pelayanan Informasi Publik dan Laporan Pengaduan','Pelayanan Informasi Publik dan Laporan Pengaduan','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(49,10,'KJKS3','J','Pelayanan Informasi Barang Bukti dan Barang Rampasan','Pelayanan Informasi Barang Bukti dan Barang Rampasan','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(50,11,'BPN1','K','Pelayanan Informasi tentang Pertanahan','Pelayanan Informasi tentang Pertanahan','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(51,11,'BPN2','K','Pelayanan Konsultasi tentangPertanahan','Pelayanan Konsultasi tentangPertanahan','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(52,11,'BPN3','K','Pelayanan Pengaduan tentang Pertanahan','Pelayanan Pengaduan tentang Pertanahan','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(53,12,'KMA1','L','Pelayanan Surat Rekomendasi Paspor Umroh ','Pelayanan Surat Rekomendasi Paspor Umroh ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(54,12,'KMA2','L','Pelayanan Rekomendasi Sertifikat Produk Halal ','Pelayanan Rekomendasi Sertifikat Produk Halal ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(55,12,'KMA3','L','Pelayanan Ijin Operasional Madrasah, Pon-Pes, Majlis Taklim, TPQ','Pelayanan Ijin Operasional Madrasah, Pon-Pes, Majlis Taklim, TPQ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(56,12,'KMA4','L','Pelayanan Pengukuran Arah Kiblat ','Pelayanan Pengukuran Arah Kiblat ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(57,12,'KMA5','L','Layanan Konsultasi Seputar Haji dan Umroh ','Layanan Konsultasi Seputar Haji dan Umroh ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(58,12,'KMA6','L','Layanan ID Masjid ','Layanan ID Masjid ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(59,13,'KP1','M','Pelayanan NPWP ','Pelayanan NPWP ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(60,13,'KP2','M','Aktifvasi / lupa EFIN ','Aktifvasi / lupa EFIN ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(61,13,'KP3','M','Konsultasi perpajakan ','Konsultasi perpajakan ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(62,14,'PA1','N','Pendaftaran perkara melalui e-Court ','Pendaftaran perkara melalui e-Court ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(63,14,'PA2','N','Pelayanan Informasi dan Pengaduan terkait PA','Pelayanan Informasi dan Pengaduan terkait PA','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(64,14,'PA3','N','Pelayanan Gugatan Mandiri ','Pelayanan Gugatan Mandiri ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(65,14,'PA4','N','Pengambilan Akta Cerai ','Pengambilan Akta Cerai ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(66,15,'PLRS1','O','Perpanjangan Surat Keterangan dan Catatan Kepolisian (SKCK)','Perpanjangan Surat Keterangan dan Catatan Kepolisian (SKCK)','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(67,15,'PLRS2','O','Surat Keterangan Tanda Lapor Kehilangan (SKTLK)','Surat Keterangan Tanda Lapor Kehilangan (SKTLK)','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(68,16,'BPJS1','P','Pelayanan pendaftaran peserta baru untuk peserta mandiri dan badan usaha ','Pelayanan pendaftaran peserta baru untuk peserta mandiri dan badan usaha ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(69,16,'BPJS2','P','Pelayanan Perubahan data ','Pelayanan Perubahan data ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(70,16,'BPJS3','P','Penambahan/Pengurangan Anggota Keluarga','Penambahan/Pengurangan Anggota Keluarga','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(71,16,'BPJS4','P','Perubahan FKTP','Perubahan FKTP','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(72,16,'BPJS5','P','Perubahan Kelas Rawat','Perubahan Kelas Rawat','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(73,16,'BPJS6','P','Perubahan Identitas','Perubahan Identitas','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(74,16,'BPJS7','P','Pengaktifan atau Penonaktifan WNI dari dan ke Luar Negeri','Pengaktifan atau Penonaktifan WNI dari dan ke Luar Negeri','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(75,16,'BPJS8','P','Peralihan Jenis Kepesertaan ke PBPU/BP','Peralihan Jenis Kepesertaan ke PBPU/BP','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(76,16,'BPJS9','P','Pemutakhiran Data','Pemutakhiran Data','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(77,16,'BPJS10','P','Pelayanan informasi dan penanganan Pengaduan','Pelayanan informasi dan penanganan Pengaduan','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(78,17,'BPJSK1','Q','Pelayanan Informasi Program BPJS Naker ','Pelayanan Informasi Program BPJS Naker ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(79,17,'BPJSK2','Q','Pendaftaran BPJS  Ketenagakerjaan','Pendaftaran BPJS  Ketenagakerjaan','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(80,17,'BPJSK3','Q','Pengecekan status kepesertaan dan iuran peserta ','Pengecekan status kepesertaan dan iuran peserta ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(81,17,'BPJSK4','Q','Pelayanan jaminan klaim program BPJS Naker ','Pelayanan jaminan klaim program BPJS Naker ','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(82,18,'BRI1','R','Transfer','Transfer','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(83,18,'BRI2','R','Setor Tunai','Setor Tunai','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(84,18,'BRI3','R','Tarik Tunai','Tarik Tunai','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(85,18,'BRI4','R','Telkom','Telkom','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(86,18,'BRI5','R','Indihome','Indihome','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(87,18,'BRI6','R','Cicilan : FIF, BAF, WOM, OTO, dll','Cicilan : FIF, BAF, WOM, OTO, dll','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(88,18,'BRI7','R','Tiket','Tiket','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(89,18,'BRI8','R','Zakat/Infaq','Zakat/Infaq','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(90,18,'BRI9','R','Pembelian Pulsa/Paket Data','Pembelian Pulsa/Paket Data','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(91,18,'BRI10','R','Pembayaran SKCK dan Surat Kehilangan dari POLRES','Pembayaran SKCK dan Surat Kehilangan dari POLRES','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(92,19,'BJTG1','S','Pembayaran PBB','Pembayaran PBB','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(93,19,'BJTG2','S','E-TAX','E-TAX','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(94,19,'BJTG3','S','Pembayaran MPN','Pembayaran MPN','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(95,19,'BJTG4','S','Pembayaran PBJS Ketenagakerjaan','Pembayaran PBJS Ketenagakerjaan','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(96,19,'BJTG5','S','Pembayaran PDAM','Pembayaran PDAM','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(97,19,'BJTG6','S','Pembayaran PLN','Pembayaran PLN','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(98,20,'TSPN1','T','Informasi Ketaspenan','Informasi Ketaspenan','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(99,20,'TSPN2','T','Pensiun Pertama','Pensiun Pertama','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(100,20,'TSPN3','T','Tabungan Hari Tua','Tabungan Hari Tua','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(101,20,'TSPN4','T','Uang Duka Wafat','Uang Duka Wafat','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(102,20,'TSPN5','T','Asuransi Kematian','Asuransi Kematian','2025-11-08 11:38:10','2025-11-08 11:38:10'),
(103,20,'TSPN6','T','Pensiun Janda/Duda/Yatim','Pensiun Janda/Duda/Yatim','2025-11-08 11:38:10','2025-11-08 11:38:10');
/*!40000 ALTER TABLE `jenis_layanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_role` enum('super_admin','admin_layanan','cs_layanan','masyarakat') NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'super_admin'),
(2,'admin_layanan'),
(3,'cs_layanan'),
(4,'masyarakat');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sektor_display`
--

DROP TABLE IF EXISTS `sektor_display`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sektor_display` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_sektor` varchar(30) NOT NULL,
  `nama_sektor` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `lokasi_display` varchar(150) DEFAULT NULL,
  `is_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_sektor` (`kode_sektor`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sektor_display`
--

LOCK TABLES `sektor_display` WRITE;
/*!40000 ALTER TABLE `sektor_display` DISABLE KEYS */;
INSERT INTO `sektor_display` VALUES
(1,'UMUM','Sektor Umum','sektor-umum','Display Utama',1,'2026-02-12 10:04:00','2026-02-12 10:04:00'),
(2,'1','Sektor 1','sektor-1','1',1,'2026-02-12 10:06:03','2026-02-12 10:06:03'),
(3,'2','Sektor 2','sektor-2','2',1,'2026-02-12 10:06:13','2026-02-12 10:06:13');
/*!40000 ALTER TABLE `sektor_display` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `instansi_id` int(11) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `verify_token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `username` (`username`) USING BTREE,
  KEY `role_id` (`role_id`) USING BTREE,
  KEY `fk_users_instansi` (`instansi_id`) USING BTREE,
  CONSTRAINT `fk_users_instansi` FOREIGN KEY (`instansi_id`) REFERENCES `instansi` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'superadmin','$2y$10$GkdUu.ycFOLo.nk/DexiS.pDsFWqHODACEn1c2gxKc1ygBuDOmqKW','Super Admin','','',1,NULL,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(2,'admin_dpmptsp','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin DPMPTSP','','',2,1,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(3,'admin_dinsosppkb','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin DINSOSPPKB','','',2,2,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(4,'admin_dinkes','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin DINKES','','',2,3,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(5,'admin_dlh','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin DLH','','',2,4,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(6,'admin_bppkad','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin BPPKAD','','',2,5,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(7,'admin_disdukcapil','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin DISDUKCAPIL','','',2,6,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(8,'admin_dputaru','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin DPUTARU','','',2,7,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(9,'admin_dinperinnaker','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin DINPERINNAKER','','',2,8,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(10,'admin_pdam','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin PDAM','','',2,9,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(11,'admin_kejari','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin KEJARI','','',2,10,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(12,'admin_atr/bpn','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin ATR/BPN','','',2,11,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(13,'admin_kemenag','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin KEMENAG','','',2,12,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(14,'admin_kp2kp','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin KP2KP','','',2,13,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(15,'admin_pengadilan agama','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin PENGADILAN AGAMA','','',2,14,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(16,'joko','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','JOKO','','asxsd',4,NULL,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(19,'fuadi','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Mukhammad Anwar Fuadi','','Tanjungsari',4,NULL,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(20,'jono','$2y$10$fdMyRon5jDJe60hUjG0cn.p46NGi0CdeemW8n/s24RXDWtHY80w1O','JONO','','fdsd',4,NULL,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(21,'admin_polres','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin POLRES','','',2,15,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(22,'admin_bpjs kesehatan','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin BPJS KESEHATAN','','',2,16,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(23,'admin_bpjs ketenagakerjaan','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin BPJS KETENAGAKERJAAN','','',2,17,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(24,'admin_bri','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin BRI','','',2,18,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(25,'admin_bank jateng','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin BANK JATENG','','',2,19,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(26,'admin_taspen','$2y$10$rNL6aOp1kC8z/3TWtPAZ.u7egf725mwqK3XvoF.rJ8oSnHA8oEJ3u','Admin TASPEN','','',2,20,'','',1,'','2025-06-23 06:39:00','2025-06-23 06:39:00',1),
(27,'budi','$2y$10$f0C/8MF3UkP5sHES9bm4puE1k4O7YJwg.D4cUvfdQMFMU2N2GzRpe','edi budi santoso','3317090405870002','ds. gunungsari rt/rw 6/2 kec. kaliori kab. rembang',4,NULL,'082134803434','budygrafika@gmail',1,NULL,'2025-12-03 10:15:01','2026-02-01 22:58:47',1),
(28,'wahyu','$2y$10$2mlYVhwpXFxzsbUUrErRAOMdcPxg7qAebE9yDdNcbeSvPHqL/vMi.','wahyu setyo utomo, S.Kom','3317032701930001','Desa telgawah kec. gunem kab. rembang',4,NULL,'085174351753','wahyusetyo27@gmail.com',1,NULL,'2025-12-04 09:53:03',NULL,1),
(29,'Irul','$2y$10$0gi73j7WKD2WxGW86AcmOOBSj0H0QU4yhLpe2yDwWJPAp1ZQZu0L6','Irul','3317071806920003','Bangnrejo',4,NULL,'082111704344','irul.ppm@gmail.com',1,NULL,'2025-12-04 10:24:46',NULL,1),
(30,'fuadmuhammad','$2y$10$kLPY8T8Kw1ZZfQldN/YGnuWCMx1o/sMI.thQFKJPRQPKFPXbe9egO','Fuad','08657','asxsd',4,NULL,'085701547942','fuadmuhammad29@gmail.com',1,NULL,'2026-01-24 14:45:51',NULL,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video_setting`
--

DROP TABLE IF EXISTS `video_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source_type` enum('file','youtube') NOT NULL DEFAULT 'file',
  `file_path` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `is_muted` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `audio_speed` decimal(3,2) NOT NULL DEFAULT 1.50,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video_setting`
--

LOCK TABLES `video_setting` WRITE;
/*!40000 ALTER TABLE `video_setting` DISABLE KEYS */;
INSERT INTO `video_setting` VALUES
(1,'youtube',NULL,'https://www.youtube.com/watch?v=EykoywxULJA',1,'2026-02-12 02:53:27',1.20);
/*!40000 ALTER TABLE `video_setting` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-24 10:26:31
