/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: silabor_miha
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-0ubuntu0.24.04.1

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
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `lab_id` bigint(20) unsigned NOT NULL,
  `jenis_peminjaman` enum('KBM','Khusus') NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `mata_pelajaran` varchar(100) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `guru_pengajar` varchar(150) DEFAULT NULL,
  `keperluan` text DEFAULT NULL,
  `penanggung_jawab` varchar(150) DEFAULT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak') NOT NULL DEFAULT 'pending',
  `catatan_admin` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_lab_id_foreign` (`lab_id`),
  CONSTRAINT `bookings_lab_id_foreign` FOREIGN KEY (`lab_id`) REFERENCES `laboratories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES
(1,1,1,'KBM','2026-02-23','2026-02-23','07:00:00','08:30:00','Fiqih','XII IPS 2','Ustadz',NULL,NULL,NULL,'disetujui',NULL,'2026-02-22 09:28:20','2026-02-22 09:35:12'),
(2,1,2,'Khusus','2026-02-24','2026-02-25','07:33:00','11:35:00',NULL,NULL,NULL,'Ujian komputer','ustadz','1771817701_81f20ec70fef3f3c18a0.pdf','disetujui',NULL,'2026-02-23 03:35:01','2026-02-23 03:35:11'),
(3,2,5,'KBM','2026-02-26','2026-02-26','07:00:00','08:30:00','KKA','CIC 8 PA','Satria Yudha',NULL,NULL,NULL,'disetujui',NULL,'2026-02-23 06:22:49','2026-02-23 06:23:21'),
(7,2,3,'KBM','2026-02-26','2026-02-26','07:00:00','08:30:00','Informatics','7 CIC Putri','Satria Yudha Pratama, S.Kom.',NULL,NULL,NULL,'disetujui',NULL,'2026-02-23 14:03:35','2026-02-23 14:13:46');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `downloads`
--

DROP TABLE IF EXISTS `downloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `downloads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `tipe_file` varchar(10) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `downloads`
--

LOCK TABLES `downloads` WRITE;
/*!40000 ALTER TABLE `downloads` DISABLE KEYS */;
INSERT INTO `downloads` VALUES
(1,'Format Surat Peminjaman Lab','Berikut adalah format dokumen peminjaman laboratorium komputer agenda khusus.','1771862336_81d87aae468c3c6da519.docx','docx','2026-02-23 15:58:56','2026-02-23 15:58:56');
/*!40000 ALTER TABLE `downloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventories`
--

DROP TABLE IF EXISTS `inventories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lab_id` bigint(20) unsigned NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `jumlah_total` int(10) unsigned NOT NULL DEFAULT 0,
  `kondisi_baik` int(10) unsigned NOT NULL DEFAULT 0,
  `kondisi_rusak` int(10) unsigned NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_barang` (`kode_barang`),
  KEY `inventories_lab_id_foreign` (`lab_id`),
  CONSTRAINT `inventories_lab_id_foreign` FOREIGN KEY (`lab_id`) REFERENCES `laboratories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventories`
--

LOCK TABLES `inventories` WRITE;
/*!40000 ALTER TABLE `inventories` DISABLE KEYS */;
INSERT INTO `inventories` VALUES
(2,1,'PC-SERVER','PC Gigabyte Server','Elektronik & Komputer',1,1,0,'The best PC in the world','2026-02-23 05:08:54','2026-02-23 16:27:08'),
(3,2,'PC-Client','PC Full Set','Elektronik & Komputer',36,35,1,'It is a long established fact that a reader will be distracted by the readable content of a page when.','2026-02-23 05:13:09','2026-02-23 05:13:09');
/*!40000 ALTER TABLE `inventories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jam_pelajaran`
--

DROP TABLE IF EXISTS `jam_pelajaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jam_pelajaran` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` bigint(20) unsigned NOT NULL,
  `nama_sesi` varchar(100) NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jam_pelajaran_unit_id_foreign` (`unit_id`),
  CONSTRAINT `jam_pelajaran_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jam_pelajaran`
--

LOCK TABLES `jam_pelajaran` WRITE;
/*!40000 ALTER TABLE `jam_pelajaran` DISABLE KEYS */;
INSERT INTO `jam_pelajaran` VALUES
(1,1,'Jam ke-1 & 2','07:00:00','08:30:00'),
(2,4,'Jam ke-3 & 4','08:30:00','10:00:00'),
(3,6,'Jam ke-5 & 6','10:30:00','12:00:00');
/*!40000 ALTER TABLE `jam_pelajaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laboratories`
--

DROP TABLE IF EXISTS `laboratories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `laboratories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` bigint(20) unsigned DEFAULT NULL,
  `nama_lab` varchar(100) NOT NULL,
  `kapasitas` int(5) DEFAULT NULL,
  `status` enum('aktif','maintenance') NOT NULL DEFAULT 'aktif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `laboratories_unit_id_foreign` (`unit_id`),
  CONSTRAINT `laboratories_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laboratories`
--

LOCK TABLES `laboratories` WRITE;
/*!40000 ALTER TABLE `laboratories` DISABLE KEYS */;
INSERT INTO `laboratories` VALUES
(1,6,'Lab Komputer 1',36,'aktif','2026-02-22 08:38:13','2026-02-22 09:27:30'),
(2,1,'Lab Komputer 2',36,'aktif','2026-02-22 08:38:13','2026-02-22 09:27:24'),
(3,2,'Lab Komputer 3',30,'aktif','2026-02-22 08:38:13','2026-02-22 09:26:59'),
(5,4,'Lab Komputer 4',36,'aktif','2026-02-22 08:38:13','2026-02-22 09:27:15');
/*!40000 ALTER TABLE `laboratories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'2026-02-22-081455','App\\Database\\Migrations\\Bookings','default','App',1771749418,1),
(2,'2026-02-22-081455','App\\Database\\Migrations\\Laboratories','default','App',1771749418,1),
(3,'2026-02-22-081455','App\\Database\\Migrations\\Units','default','App',1771749418,1),
(4,'2026-02-22-081455','App\\Database\\Migrations\\Users','default','App',1771749418,1),
(5,'2026-02-22-094411','App\\Database\\Migrations\\JamPelajaran','default','App',1771753492,2),
(6,'2026-02-22-094411','App\\Database\\Migrations\\Settings','default','App',1771753492,2),
(7,'2026-02-23-021358','App\\Database\\Migrations\\News','default','App',1771813989,3),
(8,'2026-02-23-030930','App\\Database\\Migrations\\Inventory','default','App',1771816204,4),
(9,'2026-02-23-061232','App\\Database\\Migrations\\Notifications','default','App',1771827172,5),
(10,'2026-02-23-121337','App\\Database\\Migrations\\Settings','default','App',1771849006,6),
(11,'2026-02-23-150200','App\\Database\\Migrations\\StrukturOrganisasi','default','App',1771858946,7),
(12,'2026-02-23-152524','App\\Database\\Migrations\\Downloads','default','App',1771860448,8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `konten` text NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `status` enum('publish','draft') NOT NULL DEFAULT 'draft',
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `news_user_id_foreign` (`user_id`),
  CONSTRAINT `news_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES
(2,1,'Judul berita satu','judul-berita-satu','<p><strong style=\"color: rgb(0, 0, 0);\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0);\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p><p><span style=\"color: rgb(0, 0, 0);\"><span class=\"ql-cursor\">﻿</span></span><img src=\"http://localhost:8080/uploads/berita/konten/1771815678_7a493f9fae3d91399bf6.png\"></p>','1771815682_b40502587b0f8c25a7e2.jpg','publish',13,'2026-02-23 03:01:22','2026-02-23 16:39:45');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES
(1,1,'Pengajuan KBM Baru','Satria Yudha mengajukan peminjaman lab untuk KBM.','/bookings',1,'2026-02-23 06:22:49'),
(2,2,'Peminjaman Disetujui','Pengajuan laboratorium Anda telah disetujui.','/bookings',1,'2026-02-23 06:23:21'),
(3,1,'Pengajuan KBM Baru','Super Admin Lab mengajukan peminjaman lab untuk KBM.','/bookings',1,'2026-02-23 12:28:37'),
(4,1,'Peminjaman Ditolak','Maaf, pengajuan laboratorium Anda ditolak.','/bookings',1,'2026-02-23 12:29:05'),
(5,1,'Pengajuan KBM Baru','Satria Yudha mengajukan peminjaman lab untuk KBM.','/bookings',1,'2026-02-23 12:34:24'),
(6,2,'Peminjaman Ditolak','Maaf, pengajuan laboratorium Anda ditolak.','/bookings',1,'2026-02-23 12:34:54'),
(7,1,'Pengajuan KBM Baru','Satria Yudha mengajukan peminjaman lab untuk KBM.','/bookings',1,'2026-02-23 12:39:11'),
(8,2,'Peminjaman Ditolak','Maaf, pengajuan laboratorium Anda ditolak.','/bookings',1,'2026-02-23 12:56:39'),
(9,1,'Pengajuan KBM Baru','Satria Yudha mengajukan peminjaman lab untuk KBM.','/bookings',1,'2026-02-23 14:03:35'),
(10,2,'Peminjaman Disetujui','Pengajuan laboratorium Anda telah disetujui.','/bookings',1,'2026-02-23 14:13:46');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES
(1,'jam_buka','07:00'),
(2,'jam_tutup','22:00'),
(3,'lead_time','2');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `struktur_organisasi`
--

DROP TABLE IF EXISTS `struktur_organisasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `struktur_organisasi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `wa` varchar(50) DEFAULT NULL,
  `ig` varchar(150) DEFAULT NULL,
  `fb` varchar(150) DEFAULT NULL,
  `web` varchar(150) DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `struktur_organisasi`
--

LOCK TABLES `struktur_organisasi` WRITE;
/*!40000 ALTER TABLE `struktur_organisasi` DISABLE KEYS */;
INSERT INTO `struktur_organisasi` VALUES
(1,'Tatagraha Rahmanda, S.Pd., M.T.','Kepala Laboratorium Komputer','1771870142_843eaf65a2962f0149da.jpg','6285812400911','','','',1);
/*!40000 ALTER TABLE `struktur_organisasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_unit` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES
(1,'SMP PLUS CORDOVA','2026-02-22 08:38:13',NULL),
(2,'MTs MABADI\'UL IHSAN','2026-02-22 08:38:13',NULL),
(4,'SMA PLUS CORDOVA',NULL,NULL),
(5,'SMK CORDOVA',NULL,NULL),
(6,'MA MABADI\'UL IHSAN',NULL,NULL),
(7,'UNIVERSITAS CORDOBA',NULL,NULL);
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` bigint(20) unsigned DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('guru','staff','panitia','admin_unit','admin_lab','super_admin') NOT NULL DEFAULT 'guru',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `users_unit_id_foreign` (`unit_id`),
  CONSTRAINT `users_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,NULL,'Super Admin Lab','superadmin','$2y$12$HyqJsRcae6lmwQPXca3ig.y87ENh1XBAnjwgz4SnyybcW68.8jZUK','super_admin',1,'2026-02-22 08:38:13','2026-02-23 01:33:31'),
(2,1,'Satria Yudha','gurusmp','$2y$12$GFHleBwG/fqX4aDbMLVLD.fWq3FoqulNLgkrt0XO7Kwc7LHTS7jfO','guru',1,'2026-02-23 01:03:20','2026-02-23 01:03:20');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-24  1:32:47
