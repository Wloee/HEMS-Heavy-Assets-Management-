-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: hems
-- ------------------------------------------------------
-- Server version	8.0.41

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `addendum`
--

DROP TABLE IF EXISTS `addendum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addendum` (
  `id_addendum` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `id_proyek` bigint unsigned NOT NULL COMMENT 'ID Proyek',
  `nama_addendum` varchar(50) NOT NULL COMMENT 'Nama Satuan',
  `image_addendum` varchar(100) NOT NULL COMMENT 'Nomor Addendum',
  `created_at` datetime DEFAULT NULL COMMENT 'Create At',
  `updated_at` datetime DEFAULT NULL COMMENT 'Update At',
  PRIMARY KEY (`id_addendum`),
  KEY `id_proyek` (`id_proyek`),
  CONSTRAINT `addendum_ibfk_1` FOREIGN KEY (`id_proyek`) REFERENCES `proyek` (`id_proyek`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client` (
  `id_client` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_client` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `no_handphone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `departemen`
--

DROP TABLE IF EXISTS `departemen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departemen` (
  `id_departemen` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_departemen` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_departemen`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `detail_biaya_pekerjaan`
--

DROP TABLE IF EXISTS `detail_biaya_pekerjaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_biaya_pekerjaan` (
  `id_detail_biaya` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proyek_id` bigint unsigned NOT NULL,
  `jenis_pekerjaan_id` bigint unsigned NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `biaya_total` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_detail_biaya`),
  KEY `detail_biaya_pekerjaan_proyek_id_foreign` (`proyek_id`),
  KEY `detail_biaya_pekerjaan_jenis_pekerjaan_id_foreign` (`jenis_pekerjaan_id`),
  CONSTRAINT `detail_biaya_pekerjaan_jenis_pekerjaan_id_foreign` FOREIGN KEY (`jenis_pekerjaan_id`) REFERENCES `jenis_pekerjaan` (`id_jenis_pekerjaan`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `detail_biaya_pekerjaan_proyek_id_foreign` FOREIGN KEY (`proyek_id`) REFERENCES `proyek` (`id_proyek`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dokumen_karyawan`
--

DROP TABLE IF EXISTS `dokumen_karyawan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dokumen_karyawan` (
  `id_dokumen` bigint unsigned NOT NULL AUTO_INCREMENT,
  `karyawan_id` bigint unsigned NOT NULL,
  `image_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path ke gambar KTP',
  `surat_lamaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path ke file lamaran',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_dokumen`),
  KEY `dokumen_karyawan_karyawan_id_foreign` (`karyawan_id`),
  CONSTRAINT `dokumen_karyawan_karyawan_id_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gambar_unit`
--

DROP TABLE IF EXISTS `gambar_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gambar_unit` (
  `id_gambar` bigint unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` bigint unsigned NOT NULL,
  `gambar_depan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar_belakang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar_kiri` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar_kanan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_gambar`),
  KEY `gambar_unit_unit_id_foreign` (`unit_id`),
  CONSTRAINT `gambar_unit_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id_unit`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invoice`
--

DROP TABLE IF EXISTS `invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice` (
  `id_invoice` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proyek_id` bigint unsigned NOT NULL,
  `tanggal_invoice` date NOT NULL,
  `tanggal_jatuh_tempo` date DEFAULT NULL,
  `jumlah_tagihan` decimal(15,2) NOT NULL,
  `sisa_piutang` decimal(15,2) DEFAULT NULL,
  `status` enum('draft','terkirim','dibayar_sebagian','lunas','jatuh_tempo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_invoice`),
  KEY `invoice_proyek_id_foreign` (`proyek_id`),
  KEY `idx_invoice_status` (`status`),
  CONSTRAINT `invoice_proyek_id_foreign` FOREIGN KEY (`proyek_id`) REFERENCES `proyek` (`id_proyek`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jenis_pekerjaan`
--

DROP TABLE IF EXISTS `jenis_pekerjaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_pekerjaan` (
  `id_jenis_pekerjaan` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_jenis_pekerjaan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_jenis_pekerjaan`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jenis_unit`
--

DROP TABLE IF EXISTS `jenis_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_unit` (
  `id_jenis_unit` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_jenis_unit`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `karyawan`
--

DROP TABLE IF EXISTS `karyawan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `karyawan` (
  `id_karyawan` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_karyawan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_nik` char(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nomor NIK KTP',
  `tanggal_lahir` date NOT NULL,
  `no_handphone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nomor WhatsApp',
  `tanggal_bergabung` date NOT NULL,
  `departemen_id` bigint unsigned NOT NULL,
  `posisi_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `Gaji` decimal(10,2) DEFAULT NULL,
  `Tunjangan` decimal(10,2) DEFAULT NULL,
  `Intensif` decimal(10,2) DEFAULT NULL,
  `Status` enum('Aktif','Cuti','TidakAktif') COLLATE utf8mb4_unicode_ci DEFAULT 'Aktif',
  PRIMARY KEY (`id_karyawan`),
  UNIQUE KEY `karyawan_no_nik_unique` (`no_nik`),
  KEY `karyawan_departemen_id_foreign` (`departemen_id`),
  KEY `karyawan_posisi_id_foreign` (`posisi_id`),
  CONSTRAINT `karyawan_departemen_id_foreign` FOREIGN KEY (`departemen_id`) REFERENCES `departemen` (`id_departemen`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `karyawan_posisi_id_foreign` FOREIGN KEY (`posisi_id`) REFERENCES `posisi` (`id_posisi`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_operasional`
--

DROP TABLE IF EXISTS `log_operasional`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_operasional` (
  `id_log` bigint unsigned NOT NULL AUTO_INCREMENT,
  `unit_proyek_id` bigint unsigned NOT NULL,
  `tanggal_operasi` date NOT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `jam_operasi` decimal(4,2) DEFAULT NULL COMMENT 'Jam operasi hari ini',
  `jam_idle` decimal(4,2) DEFAULT NULL COMMENT 'Jam menganggur',
  `operator_id` bigint unsigned DEFAULT NULL,
  `lokasi_kerja` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_log`),
  KEY `log_operasional_unit_proyek_id_foreign` (`unit_proyek_id`),
  KEY `log_operasional_operator_id_foreign` (`operator_id`),
  KEY `idx_log_operasional_tanggal` (`tanggal_operasi`),
  CONSTRAINT `log_operasional_operator_id_foreign` FOREIGN KEY (`operator_id`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `log_operasional_unit_proyek_id_foreign` FOREIGN KEY (`unit_proyek_id`) REFERENCES `unit_proyek` (`id_unit_proyek`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `maintenance_log`
--

DROP TABLE IF EXISTS `maintenance_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_log` (
  `id_maintenance` bigint unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` bigint unsigned NOT NULL,
  `schedule_id` bigint unsigned DEFAULT NULL,
  `tanggal_maintenance` date NOT NULL,
  `jenis_maintenance` enum('rutin','perbaikan','darurat','overhaul') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'rutin',
  `deskripsi_pekerjaan` text COLLATE utf8mb4_unicode_ci,
  `teknisi_id` bigint unsigned DEFAULT NULL,
  `workshop` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jam_operasi_saat_maintenance` bigint DEFAULT NULL,
  `biaya_jasa` decimal(15,2) NOT NULL DEFAULT '0.00',
  `biaya_sparepart` decimal(15,2) NOT NULL DEFAULT '0.00',
  `biaya_total` decimal(15,2) DEFAULT NULL,
  `status` enum('dijadwalkan','dalam_proses','selesai','ditunda') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dijadwalkan',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_maintenance`),
  KEY `maintenance_log_unit_id_foreign` (`unit_id`),
  KEY `maintenance_log_schedule_id_foreign` (`schedule_id`),
  KEY `maintenance_log_teknisi_id_foreign` (`teknisi_id`),
  KEY `idx_maintenance_tanggal` (`tanggal_maintenance`),
  CONSTRAINT `maintenance_log_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `maintenance_schedule` (`id_schedule`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `maintenance_log_teknisi_id_foreign` FOREIGN KEY (`teknisi_id`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `maintenance_log_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id_unit`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `maintenance_schedule`
--

DROP TABLE IF EXISTS `maintenance_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_schedule` (
  `id_schedule` bigint unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` bigint unsigned NOT NULL,
  `jenis_maintenance` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interval_jam` int DEFAULT NULL COMMENT 'Setiap berapa jam operasi',
  `interval_hari` int DEFAULT NULL COMMENT 'Setiap berapa hari',
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_schedule`),
  KEY `maintenance_schedule_unit_id_foreign` (`unit_id`),
  CONSTRAINT `maintenance_schedule_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id_unit`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `maintenance_sparepart`
--

DROP TABLE IF EXISTS `maintenance_sparepart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_sparepart` (
  `id_maintenance_sparepart` bigint unsigned NOT NULL AUTO_INCREMENT,
  `maintenance_id` bigint unsigned NOT NULL,
  `sparepart_id` bigint unsigned NOT NULL,
  `jumlah_digunakan` decimal(10,2) NOT NULL,
  `satuan_id` bigint unsigned NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `total_harga` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_maintenance_sparepart`),
  KEY `maintenance_sparepart_maintenance_id_foreign` (`maintenance_id`),
  KEY `maintenance_sparepart_sparepart_id_foreign` (`sparepart_id`),
  KEY `maintenance_sparepart_satuan_id_foreign` (`satuan_id`),
  CONSTRAINT `maintenance_sparepart_maintenance_id_foreign` FOREIGN KEY (`maintenance_id`) REFERENCES `maintenance_log` (`id_maintenance`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `maintenance_sparepart_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `satuan` (`id_satuan`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `maintenance_sparepart_sparepart_id_foreign` FOREIGN KEY (`sparepart_id`) REFERENCES `sparepart` (`id_sparepart`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pemilik_unit`
--

DROP TABLE IF EXISTS `pemilik_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pemilik_unit` (
  `id_pemilik` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_pemilik` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint DEFAULT '1',
  PRIMARY KEY (`id_pemilik`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permintaan_sparepart`
--

DROP TABLE IF EXISTS `permintaan_sparepart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permintaan_sparepart` (
  `id_permintaan` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_karyawan` bigint unsigned NOT NULL,
  `tanggal_permintaan` date NOT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_permintaan`),
  KEY `id_karyawan` (`id_karyawan`),
  CONSTRAINT `permintaan_sparepart_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permintaan_sparepart_detail`
--

DROP TABLE IF EXISTS `permintaan_sparepart_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permintaan_sparepart_detail` (
  `id_detail` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_permintaan` bigint unsigned NOT NULL,
  `id_sparepart` bigint unsigned NOT NULL,
  `jumlah` int NOT NULL,
  `satuan_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `id_permintaan` (`id_permintaan`),
  KEY `id_sparepart` (`id_sparepart`),
  KEY `satuan_id` (`satuan_id`),
  CONSTRAINT `permintaan_sparepart_detail_ibfk_1` FOREIGN KEY (`id_permintaan`) REFERENCES `permintaan_sparepart` (`id_permintaan`),
  CONSTRAINT `permintaan_sparepart_detail_ibfk_2` FOREIGN KEY (`id_sparepart`) REFERENCES `sparepart` (`id_sparepart`),
  CONSTRAINT `permintaan_sparepart_detail_ibfk_3` FOREIGN KEY (`satuan_id`) REFERENCES `satuan` (`id_satuan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `posisi`
--

DROP TABLE IF EXISTS `posisi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posisi` (
  `id_posisi` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_posisi` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_posisi`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `proyek`
--

DROP TABLE IF EXISTS `proyek`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proyek` (
  `id_proyek` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_proyek` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai_aktual` date DEFAULT NULL,
  `id_addendum` bigint DEFAULT NULL,
  `nama_client` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_proyek` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','aktif','selesai','ditunda','dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_proyek`),
  KEY `proyek_client_id_foreign` (`nama_client`),
  KEY `idx_proyek_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `satuan`
--

DROP TABLE IF EXISTS `satuan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `satuan` (
  `id_satuan` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `nama_satuan` varchar(50) NOT NULL COMMENT 'Nama Satuan',
  `created_at` datetime DEFAULT NULL COMMENT 'Create At',
  `updated_at` datetime DEFAULT NULL COMMENT 'Update At',
  `is_active` tinyint DEFAULT '1',
  PRIMARY KEY (`id_satuan`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sparepart`
--

DROP TABLE IF EXISTS `sparepart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sparepart` (
  `id_sparepart` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_sparepart` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_sparepart` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merk` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `stok_minimum` bigint NOT NULL DEFAULT '5',
  `stok_saat_ini` bigint NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deksripsi_produk` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_sparepart`),
  UNIQUE KEY `sparepart_kode_sparepart_unique` (`kode_sparepart`),
  KEY `sparepart_supplier_id_foreign` (`supplier_id`),
  KEY `idx_sparepart_stok` (`stok_saat_ini`),
  CONSTRAINT `sparepart_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id_supplier`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sparepart_pengadaan`
--

DROP TABLE IF EXISTS `sparepart_pengadaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sparepart_pengadaan` (
  `id_pembelian` bigint unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint unsigned NOT NULL,
  `tanggal_pembelian` date NOT NULL,
  `total_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pembelian`),
  KEY `sparepart_pembelian_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `sparepart_pembelian_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id_supplier`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sparepart_pengadaan_detail`
--

DROP TABLE IF EXISTS `sparepart_pengadaan_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sparepart_pengadaan_detail` (
  `id_detail` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pembelian_id` bigint unsigned NOT NULL,
  `sparepart_id` bigint unsigned NOT NULL,
  `satuan_id` bigint unsigned NOT NULL,
  `jumlah` bigint NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `kode_sparepart` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `pembelian_detail_pembelian_id_foreign` (`pembelian_id`),
  KEY `pembelian_detail_sparepart_id_foreign` (`sparepart_id`),
  KEY `pembelian_detail_satuan_id_foreign` (`satuan_id`),
  CONSTRAINT `pembelian_detail_pembelian_id_foreign` FOREIGN KEY (`pembelian_id`) REFERENCES `sparepart_pengadaan` (`id_pembelian`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pembelian_detail_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `satuan` (`id_satuan`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pembelian_detail_sparepart_id_foreign` FOREIGN KEY (`sparepart_id`) REFERENCES `sparepart` (`id_sparepart`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sparepart_satuan`
--

DROP TABLE IF EXISTS `sparepart_satuan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sparepart_satuan` (
  `id_sparepart_satuan` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sparepart_id` bigint unsigned NOT NULL,
  `satuan_id` bigint unsigned NOT NULL,
  `konversi` int NOT NULL DEFAULT '1' COMMENT 'Jumlah unit terkecil per satuan ini',
  `harga_satuan` decimal(15,2) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_sparepart_satuan`),
  KEY `sparepart_satuan_sparepart_id_foreign` (`sparepart_id`),
  KEY `sparepart_satuan_satuan_id_foreign` (`satuan_id`),
  CONSTRAINT `sparepart_satuan_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `satuan` (`id_satuan`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `sparepart_satuan_sparepart_id_foreign` FOREIGN KEY (`sparepart_id`) REFERENCES `sparepart` (`id_sparepart`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `supplier`
--

DROP TABLE IF EXISTS `supplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `supplier` (
  `id_supplier` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_supplier` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `no_handphone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `unit`
--

DROP TABLE IF EXISTS `unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unit` (
  `id_unit` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_unit_id` bigint unsigned NOT NULL,
  `merk` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_pembuatan` year DEFAULT NULL,
  `no_rangka` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_mesin` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_polisi` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pemilik_id` bigint unsigned DEFAULT NULL,
  `alamat_unit` text COLLATE utf8mb4_unicode_ci,
  `kota` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provinsi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jam_operasi` bigint NOT NULL DEFAULT '0' COMMENT 'Total jam operasi',
  `status_kepemilikan` enum('milik_sendiri','sewa','kontrak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'milik_sendiri',
  `status_kondisi` enum('baik','perlu_maintenance','rusak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baik',
  `status_operasional` enum('operasional','maintenance','standby','tidak_aktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'standby',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_unit`),
  UNIQUE KEY `unit_kode_unit_unique` (`kode_unit`),
  UNIQUE KEY `unit_no_rangka_unique` (`no_rangka`),
  UNIQUE KEY `unit_no_mesin_unique` (`no_mesin`),
  KEY `unit_jenis_unit_id_foreign` (`jenis_unit_id`),
  KEY `unit_pemilik_id_foreign` (`pemilik_id`),
  KEY `idx_unit_status` (`status_operasional`,`status_kondisi`),
  CONSTRAINT `unit_jenis_unit_id_foreign` FOREIGN KEY (`jenis_unit_id`) REFERENCES `jenis_unit` (`id_jenis_unit`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `unit_pemilik_id_foreign` FOREIGN KEY (`pemilik_id`) REFERENCES `pemilik_unit` (`id_pemilik`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `unit_proyek`
--

DROP TABLE IF EXISTS `unit_proyek`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unit_proyek` (
  `id_unit_proyek` bigint unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` bigint unsigned NOT NULL,
  `proyek_id` bigint unsigned NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `operator_id` bigint unsigned DEFAULT NULL,
  `tarif_sewa_harian` decimal(15,2) DEFAULT NULL,
  `status` enum('aktif','selesai','ditunda') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_unit_proyek`),
  KEY `unit_proyek_unit_id_foreign` (`unit_id`),
  KEY `unit_proyek_proyek_id_foreign` (`proyek_id`),
  KEY `unit_proyek_operator_id_foreign` (`operator_id`),
  CONSTRAINT `unit_proyek_operator_id_foreign` FOREIGN KEY (`operator_id`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `unit_proyek_proyek_id_foreign` FOREIGN KEY (`proyek_id`) REFERENCES `proyek` (`id_proyek`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `unit_proyek_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id_unit`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','manager','operator','teknisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'operator',
  `karyawan_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username_unique` (`name`),
  UNIQUE KEY `user_email_unique` (`email`),
  KEY `user_karyawan_id_foreign` (`karyawan_id`),
  CONSTRAINT `user_karyawan_id_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'hems'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-26 22:09:07
