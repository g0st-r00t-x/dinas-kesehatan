-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for dinas_kesehatan_v2
DROP DATABASE IF EXISTS `dinas_kesehatan_v2`;
CREATE DATABASE IF NOT EXISTS `dinas_kesehatan_v2` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `dinas_kesehatan_v2`;

-- Dumping structure for table dinas_kesehatan_v2.data_serkoms
DROP TABLE IF EXISTS `data_serkoms`;
CREATE TABLE IF NOT EXISTS `data_serkoms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.data_serkoms: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.exports
DROP TABLE IF EXISTS `exports`;
CREATE TABLE IF NOT EXISTS `exports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exporter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `processed_rows` int unsigned NOT NULL DEFAULT '0',
  `total_rows` int unsigned NOT NULL,
  `successful_rows` int unsigned NOT NULL DEFAULT '0',
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exports_user_id_foreign` (`user_id`),
  CONSTRAINT `exports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.exports: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.failed_import_rows
DROP TABLE IF EXISTS `failed_import_rows`;
CREATE TABLE IF NOT EXISTS `failed_import_rows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `data` json NOT NULL,
  `import_id` bigint unsigned NOT NULL,
  `validation_error` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `failed_import_rows_import_id_foreign` (`import_id`),
  CONSTRAINT `failed_import_rows_import_id_foreign` FOREIGN KEY (`import_id`) REFERENCES `imports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.failed_import_rows: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
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

-- Dumping data for table dinas_kesehatan_v2.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.imports
DROP TABLE IF EXISTS `imports`;
CREATE TABLE IF NOT EXISTS `imports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `importer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `processed_rows` int unsigned NOT NULL DEFAULT '0',
  `total_rows` int unsigned NOT NULL,
  `successful_rows` int unsigned NOT NULL DEFAULT '0',
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `imports_user_id_foreign` (`user_id`),
  CONSTRAINT `imports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.imports: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.jenis_data_dukungans
DROP TABLE IF EXISTS `jenis_data_dukungans`;
CREATE TABLE IF NOT EXISTS `jenis_data_dukungans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.jenis_data_dukungans: ~0 rows (approximately)
REPLACE INTO `jenis_data_dukungans` (`id`, `jenis`, `created_at`, `updated_at`) VALUES
	(1, 'SK PEMBERIAN TUNJANGAN', '2025-01-21 19:59:34', '2025-01-21 19:59:34'),
	(2, 'SK PENCANTUMAN GELAR S-1', '2025-01-21 19:59:35', '2025-01-21 19:59:35'),
	(3, 'IBEL S-1', '2025-01-21 19:59:35', '2025-01-21 19:59:35'),
	(4, 'SK KP TERAKHIR', '2025-01-21 19:59:35', '2025-01-21 19:59:35'),
	(5, 'IJAZAH TERAKHIR', '2025-01-21 19:59:35', '2025-01-21 19:59:35');

-- Dumping structure for table dinas_kesehatan_v2.job_batches
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
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

-- Dumping data for table dinas_kesehatan_v2.job_batches: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.migrations: ~0 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2025_01_15_070059_create_usulan_penerbitan_a_j_j_s_table', 1),
	(6, '2025_01_16_161539_create_usulan_revisi_sk_pangkats_table', 1),
	(8, '2025_01_18_124415_create_job_batches_table', 1),
	(9, '2025_01_18_124416_create_notifications_table', 1),
	(10, '2025_01_18_124443_create_imports_table', 1),
	(11, '2025_01_18_124444_create_exports_table', 1),
	(12, '2025_01_18_124445_create_failed_import_rows_table', 1),
	(13, '2025_01_20_022225_create_data_dukungans_table', 1),
	(14, '2025_01_20_022226_create_inventaris_permasalahan_kepegawaians_table', 1),
	(15, '2025_01_21_143310_create_usulan_s_k_pemberhentian_sementaras_table', 1),
	(16, '2025_01_21_143404_create_usulan_permohonan_pensiuns_table', 1),
	(17, '2025_01_21_143429_create_usulan_rekomendasi_penelitians_table', 1),
	(18, '2025_01_21_143458_create_usulan_permohonan_cutis_table', 1),
	(19, '2025_01_21_143526_create_usulan_s_k_berkalas_table', 1),
	(20, '2025_01_21_143549_create_rekap_absen_a_s_n_s_table', 1),
	(21, '2025_01_22_002206_create_data_serkoms_table', 1),
	(22, '2025_01_22_002224_create_rekap_absensi_non_a_s_n_s_table', 1),
	(23, '2025_01_17_063746_create_permission_tables', 2);

-- Dumping structure for table dinas_kesehatan_v2.model_has_permissions
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.model_has_roles
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.model_has_roles: ~2 rows (approximately)
REPLACE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(2, 'App\\Models\\User', 1),
	(1, 'App\\Models\\User', 2);

-- Dumping structure for table dinas_kesehatan_v2.notifications
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.notifications: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.permasalahan_kepegawaian
DROP TABLE IF EXISTS `permasalahan_kepegawaian`;
CREATE TABLE IF NOT EXISTS `permasalahan_kepegawaian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pangkat_golongan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_kerja` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permasalahan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_dukungan_id` bigint unsigned NOT NULL,
  `file_upload` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surat_pengantar_unit_kerja` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permasalahan_kepegawaian_nip_unique` (`nip`),
  KEY `permasalahan_kepegawaian_data_dukungan_id_foreign` (`data_dukungan_id`),
  CONSTRAINT `permasalahan_kepegawaian_data_dukungan_id_foreign` FOREIGN KEY (`data_dukungan_id`) REFERENCES `jenis_data_dukungans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.permasalahan_kepegawaian: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.permissions
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.permissions: ~150 rows (approximately)
REPLACE INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'view_data::serkom', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(2, 'view_any_data::serkom', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(3, 'create_data::serkom', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(4, 'update_data::serkom', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(5, 'restore_data::serkom', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(6, 'restore_any_data::serkom', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(7, 'replicate_data::serkom', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(8, 'reorder_data::serkom', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(9, 'delete_data::serkom', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(10, 'delete_any_data::serkom', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(11, 'force_delete_data::serkom', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(12, 'force_delete_any_data::serkom', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(13, 'view_inventaris::permasalahan::kepegawaian', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(14, 'view_any_inventaris::permasalahan::kepegawaian', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(15, 'create_inventaris::permasalahan::kepegawaian', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(16, 'update_inventaris::permasalahan::kepegawaian', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(17, 'restore_inventaris::permasalahan::kepegawaian', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(18, 'restore_any_inventaris::permasalahan::kepegawaian', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(19, 'replicate_inventaris::permasalahan::kepegawaian', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(20, 'reorder_inventaris::permasalahan::kepegawaian', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(21, 'delete_inventaris::permasalahan::kepegawaian', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(22, 'delete_any_inventaris::permasalahan::kepegawaian', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(23, 'force_delete_inventaris::permasalahan::kepegawaian', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(24, 'force_delete_any_inventaris::permasalahan::kepegawaian', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(25, 'view_pengajuan::a::j::j', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(26, 'view_any_pengajuan::a::j::j', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(27, 'create_pengajuan::a::j::j', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(28, 'update_pengajuan::a::j::j', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(29, 'restore_pengajuan::a::j::j', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(30, 'restore_any_pengajuan::a::j::j', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(31, 'replicate_pengajuan::a::j::j', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(32, 'reorder_pengajuan::a::j::j', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(33, 'delete_pengajuan::a::j::j', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(34, 'delete_any_pengajuan::a::j::j', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(35, 'force_delete_pengajuan::a::j::j', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(36, 'force_delete_any_pengajuan::a::j::j', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(37, 'view_rekap::absen::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(38, 'view_any_rekap::absen::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(39, 'create_rekap::absen::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(40, 'update_rekap::absen::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(41, 'restore_rekap::absen::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(42, 'restore_any_rekap::absen::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(43, 'replicate_rekap::absen::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(44, 'reorder_rekap::absen::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(45, 'delete_rekap::absen::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(46, 'delete_any_rekap::absen::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(47, 'force_delete_rekap::absen::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(48, 'force_delete_any_rekap::absen::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(49, 'view_rekap::absensi::non::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(50, 'view_any_rekap::absensi::non::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(51, 'create_rekap::absensi::non::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(52, 'update_rekap::absensi::non::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(53, 'restore_rekap::absensi::non::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(54, 'restore_any_rekap::absensi::non::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(55, 'replicate_rekap::absensi::non::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(56, 'reorder_rekap::absensi::non::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(57, 'delete_rekap::absensi::non::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(58, 'delete_any_rekap::absensi::non::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(59, 'force_delete_rekap::absensi::non::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(60, 'force_delete_any_rekap::absensi::non::a::s::n', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(61, 'view_role', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(62, 'view_any_role', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(63, 'create_role', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(64, 'update_role', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(65, 'delete_role', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(66, 'delete_any_role', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(67, 'view_user', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(68, 'view_any_user', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(69, 'create_user', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(70, 'update_user', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(71, 'restore_user', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(72, 'restore_any_user', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(73, 'replicate_user', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(74, 'reorder_user', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(75, 'delete_user', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(76, 'delete_any_user', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(77, 'force_delete_user', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(78, 'force_delete_any_user', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(79, 'view_usulan::permohonan::cuti', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(80, 'view_any_usulan::permohonan::cuti', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(81, 'create_usulan::permohonan::cuti', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(82, 'update_usulan::permohonan::cuti', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(83, 'restore_usulan::permohonan::cuti', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(84, 'restore_any_usulan::permohonan::cuti', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(85, 'replicate_usulan::permohonan::cuti', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(86, 'reorder_usulan::permohonan::cuti', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(87, 'delete_usulan::permohonan::cuti', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(88, 'delete_any_usulan::permohonan::cuti', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(89, 'force_delete_usulan::permohonan::cuti', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(90, 'force_delete_any_usulan::permohonan::cuti', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(91, 'view_usulan::permohonan::pensiun', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(92, 'view_any_usulan::permohonan::pensiun', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(93, 'create_usulan::permohonan::pensiun', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(94, 'update_usulan::permohonan::pensiun', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(95, 'restore_usulan::permohonan::pensiun', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(96, 'restore_any_usulan::permohonan::pensiun', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(97, 'replicate_usulan::permohonan::pensiun', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(98, 'reorder_usulan::permohonan::pensiun', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(99, 'delete_usulan::permohonan::pensiun', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(100, 'delete_any_usulan::permohonan::pensiun', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(101, 'force_delete_usulan::permohonan::pensiun', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(102, 'force_delete_any_usulan::permohonan::pensiun', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(103, 'view_usulan::rekomendasi::penelitian', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(104, 'view_any_usulan::rekomendasi::penelitian', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(105, 'create_usulan::rekomendasi::penelitian', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(106, 'update_usulan::rekomendasi::penelitian', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(107, 'restore_usulan::rekomendasi::penelitian', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(108, 'restore_any_usulan::rekomendasi::penelitian', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(109, 'replicate_usulan::rekomendasi::penelitian', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(110, 'reorder_usulan::rekomendasi::penelitian', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(111, 'delete_usulan::rekomendasi::penelitian', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(112, 'delete_any_usulan::rekomendasi::penelitian', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(113, 'force_delete_usulan::rekomendasi::penelitian', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(114, 'force_delete_any_usulan::rekomendasi::penelitian', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(115, 'view_usulan::revisi::sk::pangkat', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(116, 'view_any_usulan::revisi::sk::pangkat', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(117, 'create_usulan::revisi::sk::pangkat', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(118, 'update_usulan::revisi::sk::pangkat', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(119, 'restore_usulan::revisi::sk::pangkat', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(120, 'restore_any_usulan::revisi::sk::pangkat', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(121, 'replicate_usulan::revisi::sk::pangkat', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(122, 'reorder_usulan::revisi::sk::pangkat', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(123, 'delete_usulan::revisi::sk::pangkat', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(124, 'delete_any_usulan::revisi::sk::pangkat', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(125, 'force_delete_usulan::revisi::sk::pangkat', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(126, 'force_delete_any_usulan::revisi::sk::pangkat', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(127, 'view_usulan::s::k::berkala', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(128, 'view_any_usulan::s::k::berkala', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(129, 'create_usulan::s::k::berkala', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(130, 'update_usulan::s::k::berkala', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(131, 'restore_usulan::s::k::berkala', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(132, 'restore_any_usulan::s::k::berkala', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(133, 'replicate_usulan::s::k::berkala', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(134, 'reorder_usulan::s::k::berkala', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(135, 'delete_usulan::s::k::berkala', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(136, 'delete_any_usulan::s::k::berkala', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(137, 'force_delete_usulan::s::k::berkala', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(138, 'force_delete_any_usulan::s::k::berkala', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(139, 'view_usulan::s::k::pemberhentian::sementara', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(140, 'view_any_usulan::s::k::pemberhentian::sementara', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(141, 'create_usulan::s::k::pemberhentian::sementara', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(142, 'update_usulan::s::k::pemberhentian::sementara', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(143, 'restore_usulan::s::k::pemberhentian::sementara', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(144, 'restore_any_usulan::s::k::pemberhentian::sementara', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(145, 'replicate_usulan::s::k::pemberhentian::sementara', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(146, 'reorder_usulan::s::k::pemberhentian::sementara', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(147, 'delete_usulan::s::k::pemberhentian::sementara', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(148, 'delete_any_usulan::s::k::pemberhentian::sementara', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(149, 'force_delete_usulan::s::k::pemberhentian::sementara', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34'),
	(150, 'force_delete_any_usulan::s::k::pemberhentian::sementara', 'web', '2025-01-21 20:00:34', '2025-01-21 20:00:34');

-- Dumping structure for table dinas_kesehatan_v2.personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.rekap_absensi_non_a_s_n_s
DROP TABLE IF EXISTS `rekap_absensi_non_a_s_n_s`;
CREATE TABLE IF NOT EXISTS `rekap_absensi_non_a_s_n_s` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.rekap_absensi_non_a_s_n_s: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.rekap_absen_a_s_n_s
DROP TABLE IF EXISTS `rekap_absen_a_s_n_s`;
CREATE TABLE IF NOT EXISTS `rekap_absen_a_s_n_s` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.rekap_absen_a_s_n_s: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.roles: ~2 rows (approximately)
REPLACE INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'super_admin', 'web', '2025-01-21 20:00:33', '2025-01-21 20:00:33'),
	(2, 'Testing', 'web', '2025-01-21 20:07:45', '2025-01-21 20:07:45');

-- Dumping structure for table dinas_kesehatan_v2.role_has_permissions
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.role_has_permissions: ~162 rows (approximately)
REPLACE INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(11, 1),
	(12, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(16, 1),
	(17, 1),
	(18, 1),
	(19, 1),
	(20, 1),
	(21, 1),
	(22, 1),
	(23, 1),
	(24, 1),
	(25, 1),
	(26, 1),
	(27, 1),
	(28, 1),
	(29, 1),
	(30, 1),
	(31, 1),
	(32, 1),
	(33, 1),
	(34, 1),
	(35, 1),
	(36, 1),
	(37, 1),
	(38, 1),
	(39, 1),
	(40, 1),
	(41, 1),
	(42, 1),
	(43, 1),
	(44, 1),
	(45, 1),
	(46, 1),
	(47, 1),
	(48, 1),
	(49, 1),
	(50, 1),
	(51, 1),
	(52, 1),
	(53, 1),
	(54, 1),
	(55, 1),
	(56, 1),
	(57, 1),
	(58, 1),
	(59, 1),
	(60, 1),
	(61, 1),
	(62, 1),
	(63, 1),
	(64, 1),
	(65, 1),
	(66, 1),
	(67, 1),
	(68, 1),
	(69, 1),
	(70, 1),
	(71, 1),
	(72, 1),
	(73, 1),
	(74, 1),
	(75, 1),
	(76, 1),
	(77, 1),
	(78, 1),
	(79, 1),
	(80, 1),
	(81, 1),
	(82, 1),
	(83, 1),
	(84, 1),
	(85, 1),
	(86, 1),
	(87, 1),
	(88, 1),
	(89, 1),
	(90, 1),
	(91, 1),
	(92, 1),
	(93, 1),
	(94, 1),
	(95, 1),
	(96, 1),
	(97, 1),
	(98, 1),
	(99, 1),
	(100, 1),
	(101, 1),
	(102, 1),
	(103, 1),
	(104, 1),
	(105, 1),
	(106, 1),
	(107, 1),
	(108, 1),
	(109, 1),
	(110, 1),
	(111, 1),
	(112, 1),
	(113, 1),
	(114, 1),
	(115, 1),
	(116, 1),
	(117, 1),
	(118, 1),
	(119, 1),
	(120, 1),
	(121, 1),
	(122, 1),
	(123, 1),
	(124, 1),
	(125, 1),
	(126, 1),
	(127, 1),
	(128, 1),
	(129, 1),
	(130, 1),
	(131, 1),
	(132, 1),
	(133, 1),
	(134, 1),
	(135, 1),
	(136, 1),
	(137, 1),
	(138, 1),
	(139, 1),
	(140, 1),
	(141, 1),
	(142, 1),
	(143, 1),
	(144, 1),
	(145, 1),
	(146, 1),
	(147, 1),
	(148, 1),
	(149, 1),
	(150, 1),
	(13, 2),
	(14, 2),
	(15, 2),
	(16, 2),
	(17, 2),
	(18, 2),
	(19, 2),
	(20, 2),
	(21, 2),
	(22, 2),
	(23, 2),
	(24, 2);

-- Dumping structure for table dinas_kesehatan_v2.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.users: ~0 rows (approximately)
REPLACE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Moh Sabri', 'sabri@garasi.com', NULL, '$2y$12$1e2ElAI4Q8cxmVG/Uhf8ieicRUhKGOKRl9cA4VKvYgO1QzXrs./X.', NULL, '2025-01-21 19:29:25', '2025-01-21 19:29:25'),
	(2, 'Super Admin', 'superadmin@example.com', NULL, '$2y$12$6plYmo1VWvQQ.UVEwbj6fuE5L6RH2OAhEkO0NVyGJoMZ7mq6HAgbW', NULL, '2025-01-21 20:03:17', '2025-01-21 20:03:17');

-- Dumping structure for table dinas_kesehatan_v2.usulan_penerbitan_ajj
DROP TABLE IF EXISTS `usulan_penerbitan_ajj`;
CREATE TABLE IF NOT EXISTS `usulan_penerbitan_ajj` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_kerja` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tmt_pemberian_tunjangan` date NOT NULL,
  `sk_jabatan` enum('ADA','TIDAK ADA') COLLATE utf8mb4_unicode_ci NOT NULL,
  `upload_berkas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surat_pengantar_unit_kerja` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.usulan_penerbitan_ajj: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.usulan_permohonan_cutis
DROP TABLE IF EXISTS `usulan_permohonan_cutis`;
CREATE TABLE IF NOT EXISTS `usulan_permohonan_cutis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.usulan_permohonan_cutis: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.usulan_permohonan_pensiuns
DROP TABLE IF EXISTS `usulan_permohonan_pensiuns`;
CREATE TABLE IF NOT EXISTS `usulan_permohonan_pensiuns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.usulan_permohonan_pensiuns: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.usulan_rekomendasi_penelitians
DROP TABLE IF EXISTS `usulan_rekomendasi_penelitians`;
CREATE TABLE IF NOT EXISTS `usulan_rekomendasi_penelitians` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.usulan_rekomendasi_penelitians: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.usulan_revisi_sk_pangkat
DROP TABLE IF EXISTS `usulan_revisi_sk_pangkat`;
CREATE TABLE IF NOT EXISTS `usulan_revisi_sk_pangkat` (
  `id_usulan` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pangkat_golongan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alasan_revisi_sk` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kesalahan_tertulis_sk` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `upload_sk_salah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upload_data_dukung` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surat_pengantar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_wa` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_usulan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.usulan_revisi_sk_pangkat: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.usulan_s_k_berkalas
DROP TABLE IF EXISTS `usulan_s_k_berkalas`;
CREATE TABLE IF NOT EXISTS `usulan_s_k_berkalas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.usulan_s_k_berkalas: ~0 rows (approximately)

-- Dumping structure for table dinas_kesehatan_v2.usulan_s_k_pemberhentian_sementaras
DROP TABLE IF EXISTS `usulan_s_k_pemberhentian_sementaras`;
CREATE TABLE IF NOT EXISTS `usulan_s_k_pemberhentian_sementaras` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dinas_kesehatan_v2.usulan_s_k_pemberhentian_sementaras: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
