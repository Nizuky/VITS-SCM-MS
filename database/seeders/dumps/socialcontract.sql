-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: socialcontract
-- ------------------------------------------------------
-- Server version	9.4.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_password_change_tokens`
--

DROP TABLE IF EXISTS `admin_password_change_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_password_change_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_password_hash` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_password_change_tokens_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_password_change_tokens`
--

LOCK TABLES `admin_password_change_tokens` WRITE;
/*!40000 ALTER TABLE `admin_password_change_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_password_change_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_users_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'adminRaf','janarafael.sanandres@gmail.com','$2y$12$xFVWSZao4fDiZ2sXmPCF0.8b9hHXnxXvL/wasR8oKeTOMm8DB97H6','2025-10-12 19:20:23','2025-10-23 00:51:39'),(2,'admin2','janarafael.sanandres@gmail.com','$2y$12$qhMotcVElaeUw0Igqwykuekxx9Rt5fhjNkdQikTaVlwQO8HKIGfAS','2025-10-12 19:20:24','2025-10-13 05:07:05');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `approvals`
--

DROP TABLE IF EXISTS `approvals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `approvals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` bigint unsigned NOT NULL,
  `admin_id` bigint unsigned DEFAULT NULL,
  `approval_date` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `approvals_contract_id_foreign` (`contract_id`),
  KEY `approvals_admin_id_foreign` (`admin_id`),
  CONSTRAINT `approvals_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `approvals_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `social_contracts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approvals`
--

LOCK TABLES `approvals` WRITE;
/*!40000 ALTER TABLE `approvals` DISABLE KEYS */;
/*!40000 ALTER TABLE `approvals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `archives`
--

DROP TABLE IF EXISTS `archives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `archives` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` bigint unsigned NOT NULL,
  `final_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `archived_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `archives_contract_id_foreign` (`contract_id`),
  CONSTRAINT `archives_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `social_contracts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archives`
--

LOCK TABLES `archives` WRITE;
/*!40000 ALTER TABLE `archives` DISABLE KEYS */;
/*!40000 ALTER TABLE `archives` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_09_02_075243_add_two_factor_columns_to_users_table',1),(29,'2025_10_11_000002_add_role_and_student_number_to_users',2),(30,'2025_10_11_000003_create_social_contracts_table',2),(31,'2025_10_11_000004_create_verifications_table',2),(32,'2025_10_11_000005_create_approvals_table',2),(33,'2025_10_11_000006_create_transaction_logs_table',2),(34,'2025_10_11_000007_create_archives_table',2),(35,'2025_10_12_000010_add_student_id_to_users_table',2),(36,'2025_10_12_000020_create_super_admins_table',2),(37,'2025_10_13_000001_create_superadmin_activity_logs_table',2),(38,'2025_10_13_000010_create_admin_users_table',2),(39,'2025_10_13_000011_create_password_resets_table',2),(40,'2025_10_18_000100_create_social_contract_records_table',2),(41,'2025_10_22_000001_create_social_contract_record_verifications_table',3),(42,'2025_10_22_000002_create_social_contract_record_rejections_table',3),(43,'2025_10_22_000003_create_social_contract_record_approvals_table',3),(44,'2025_10_22_000004_create_social_contract_record_status_history_table',3),(45,'2025_10_22_000005_add_approved_status_to_social_contract_records',3),(46,'2025_10_23_000001_add_description_and_metadata_to_superadmin_activity_logs',4),(47,'2025_10_22_125010_create_social_contract_approvals_table',5),(48,'2025_10_23_000002_add_current_session_id_to_users_table',6),(49,'2025_10_23_065012_create_super_admin_password_change_tokens_table',7),(50,'2025_10_23_120000_create_admin_password_change_tokens_table',8),(51,'2025_10_23_000001_add_rejection_reason_to_social_contract_records',9),(52,'2025_10_23_000002_create_student_notifications_table',10);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('0mH6CP6HZWZiKr4OurhnmhBYksajnrcPO10l9cKu',13,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoidmpPeXMyc05PUkl1c283NEt1VGhlTXFXaVk0OUR0N3BiemVyR1dmOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9zY21zLnRlc3QvYWRtaW4vbG9naW4iO31zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxMDoiYXV0aF9ndWFyZCI7czo1OiJhZG1pbiI7czoyMDoiYWRtaW5fc2Vzc2lvbl9hY3RpdmUiO2I6MTt9',1761211461),('333eXUxgwJBrN6oHsIe9ONj4xVNGJFUBuNe17V7T',13,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiNTBjeFlSMEUzb0g0VjlnQnZYRWdaU1hGT3JrVkxReTRXdWQyTXliaSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMjoiaHR0cDovL3NjbXMudGVzdC9hZG1pbi9kYXNoYm9hcmQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyODoiaHR0cDovL3NjbXMudGVzdC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjEwOiJhdXRoX2d1YXJkIjtzOjU6ImFkbWluIjtzOjIwOiJhZG1pbl9zZXNzaW9uX2FjdGl2ZSI7YjoxO30=',1761211471),('64Yq9fG6QfMIzy4TSgmotJGnBtKP21j7hJnniBLF',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoib3hYRm91ZnlVOHU2R2NyaTlsTG9XaXJRclF0U096WHhPSmcwaG9vMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9zY21zLnRlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1761283281),('6yqv4VmVje2a08WQm8Ay0aLUYGbBeFfqLOlEuhvn',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YToyOntzOjY6Il90b2tlbiI7czo0MDoiNjdlUzNkUWF2VDZDVlJZQWNBd2FpQlRGSjR5dGxCek5tUmhETVNzNSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1761215492),('7A85amEsBjDBGCX7RUoiS4MFkIXSTj9udQV1OclQ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOW95VGxyZXpkQjRROVZ0N3JKOFhTZkpGUlpRcGZJblBkdHMyd0lhaCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9zY21zLnRlc3QvbG9naW4iO319',1761230356),('8LfL7Xcg07e4r8C1ojAlaGmFG4jMpYkM31Qs9R6s',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.22.3 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicjZFaENWUkV0TXFQNGVsNzRIWGJSVUZjUU9xbUs0Q01KYUJHV0xDbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9zY21zLnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1761289703),('AxaVMZiEiCAWLKlqUCxstN1QLhUONdiYsDX6xDMo',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVk9pemkyMXdpdldjR1VsWHNQOHhQOGVVRDFVWnRGRWkwWG5KbTJrRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9zY21zLnRlc3QvbG9naW4iO319',1761283245),('BwqvyiHXrMHXp26qcD8WiE6z0TYxSVRWtoqUtYyW',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.22.3 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTkNyQXVwTVhtOEdpRTI0a0xqM1VkMzFxMVY2eDNDVTFIMUl0YWVPZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9zY21zLnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1761283066),('Cylx6kyWjDop8gv0G6jaEGkGsVHh20ZKrTgg9Eph',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.22.3 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV1RjV1YzSVVOSG5OVldEVDBmU1hEMGd1bVhPQXNlZjl1aVI5ckRxUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9zY21zLnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1761283067),('DbHb7076zJh2TLl6w5M57tuLaPU7Nkv0rEf2pzVl',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUXhDQUd0RXE2OVZySHYzNGxjcUVHYWpnSjRwcW85T2ZMOVRzTkJtSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9zY21zLnRlc3QvbG9naW4iO319',1761212669),('dIb2iWz90osXmOki1E3ZqjXr7E2XHcuNV38GeoGs',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTHFmcTJ5Y25RN3pScFgzTHVqOG9wTk9UNXhzNk0xUU1rRExwM3I2cyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9zY21zLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1761215499),('doIW0THfuWVBlJrqSd2uSKxtGHoywT6xvG3URlIZ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUXdWaEFjQ21FenNyQUNUWVlva1RqTktsY285ekdLb1RUQUVSNmtpZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9zY21zLnRlc3QvbG9naW4iO319',1761208719),('eh6piofQ3E4Var24cwLO2jXghHNamluEI1vWvE75',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRklnT0tPZXlCMDVnWElqdmVWQmFUUUNKVUZBblJEbU44SFVsM2FYQiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNjoiaHR0cDovL3NjbXMudGVzdC9kYXNoYm9hcmQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyMjoiaHR0cDovL3NjbXMudGVzdC9sb2dpbiI7fX0=',1761208583),('F9zNErCjdpjkA1CX4WIFCXx8RPAh2gU3C38GdIaa',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiTkJKSnhZbjB0T0lsbkdISm9CdW5obGlxdWVNSUxDQmVWT3Q0Mmd1bCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNjoiaHR0cDovL3NjbXMudGVzdC9kYXNoYm9hcmQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMjoiaHR0cDovL3NjbXMudGVzdC9hZG1pbi9kYXNoYm9hcmQiO31zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxMDoiYXV0aF9ndWFyZCI7czo1OiJhZG1pbiI7czoyMDoiYWRtaW5fc2Vzc2lvbl9hY3RpdmUiO2I6MTt9',1761208619),('g4ctUKWoktAYS64Bu47i1ZY85xwYTNr9K71LMDiG',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRGdpS01GT0VnS2tRcERFTXB1dmlsSzFkUWMwYXNoTkFHRXVKQmFhcyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMjoiaHR0cDovL3NjbXMudGVzdC9hZG1pbi9kYXNoYm9hcmQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyMjoiaHR0cDovL3NjbXMudGVzdC9sb2dpbiI7fX0=',1761208712),('hWNpbtGtdlLypXim8xXgznHZ1A0ia3uhrGAADFan',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiTkJKSnhZbjB0T0lsbkdISm9CdW5obGlxdWVNSUxDQmVWT3Q0Mmd1bCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNjoiaHR0cDovL3NjbXMudGVzdC9kYXNoYm9hcmQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMjoiaHR0cDovL3NjbXMudGVzdC9hZG1pbi9kYXNoYm9hcmQiO31zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxMDoiYXV0aF9ndWFyZCI7czo1OiJhZG1pbiI7czoyMDoiYWRtaW5fc2Vzc2lvbl9hY3RpdmUiO2I6MTt9',1761208619),('IWLNmeVuqQmRc80ZDhFZ5wZgk5IzV01mLnM0lG4a',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiTkJKSnhZbjB0T0lsbkdISm9CdW5obGlxdWVNSUxDQmVWT3Q0Mmd1bCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNjoiaHR0cDovL3NjbXMudGVzdC9kYXNoYm9hcmQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMjoiaHR0cDovL3NjbXMudGVzdC9hZG1pbi9kYXNoYm9hcmQiO31zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxMDoiYXV0aF9ndWFyZCI7czo1OiJhZG1pbiI7czoyMDoiYWRtaW5fc2Vzc2lvbl9hY3RpdmUiO2I6MTt9',1761208619),('Jrnar7Lu3cW648Ha1jiXcmzs3GDrpsYfYVWIvr4C',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YToyOntzOjY6Il90b2tlbiI7czo0MDoiTVdNNTFIMTJzTDdMT2pBRGhKTFZKWU0wUmRhTG05NzlseGhLaUZzZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1761283281),('laOjqCHNmc7nynDZdhdocQKSqUs2wSp49fu9666Y',13,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTo3OntzOjY6Il90b2tlbiI7czo0MDoicEwxSmZ5VW5WRFVnNHZzdjlkQ040d2lVM2NZeEhGQlBwSE1jZGp6RCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovL3NjbXMudGVzdC9zdXBlci1hZG1pbi9kYXNoYm9hcmQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cDovL3NjbXMudGVzdC9zdXBlci1hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTc6ImxvZ2luX3N1cGVyYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6MTA6ImF1dGhfZ3VhcmQiO3M6MTA6InN1cGVyYWRtaW4iO3M6MjU6InN1cGVyYWRtaW5fc2Vzc2lvbl9hY3RpdmUiO2I6MTt9',1761217016),('NV5BWikMEdSjsH1NHLROApXCC9nR8tTIZlwMldm6',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWjIzU09SWlcwdFpEdW9EODRVNmhCdnJvcXg1MERuVWl3UzBnSHFneSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9zY21zLnRlc3QvbG9naW4iO319',1761219160),('oPpVajQDJ4h9DsibqSsKbzGUnyxyYl13NsPFJiAX',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.22.3 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiczlOWGZvaFlDc0huRktsa3V1akMwMUNYMEQ1WExzN3dZYmRudzFiZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9zY21zLnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1761283066),('P4zaUOn7OFbJucTk6PP3jVLSNuiyvndnVga4kMsq',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNExDQ1Nwc1RXaXdKVzdrT25WSWd6NjFIc2tsR3Vwbnk5QXNWbUdydSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9zY21zLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjQ2OiJodHRwOi8vc2Ntcy50ZXN0L2xvZ291dC1iZWFjb24/Xz0xNzYxMjE1NDkyNjE0Ijt9fQ==',1761215492),('PKNZya2i56anOCjbBLt4dmgN80rktlkcwDkVw6ei',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiVVhoa1dKd0ZQY29UQ2NoS0RoMVVjbWpXc1BUWm9QRU1hVHR5OU82eSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9zY21zLnRlc3QvbG9naW4iO31zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxMDoiYXV0aF9ndWFyZCI7czo1OiJhZG1pbiI7czoyMDoiYWRtaW5fc2Vzc2lvbl9hY3RpdmUiO2I6MTtzOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjI2OiJodHRwOi8vc2Ntcy50ZXN0L2Rhc2hib2FyZCI7fX0=',1761219426),('PUgSkz8bHv5iV73VrMNTbBlc5q9CFhaST8v6aK5Y',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWjhlMXhjUm1xdG1LbG5ocXFoWHdtaXVjODlZbkJlOTBFdTR0NU9lYyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9zY21zLnRlc3QvbG9naW4iO319',1761229605),('RQEouQmX6LrYYFBKHHPsmCouU3i3VBzFPjpo7jVH',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRWVIVEJOQlRTUDlIREJGcnE2ZFZjeHVkRTBBZnhsVGIzS1J2U2NZcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9zY21zLnRlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1761289703),('sjmyeGsh9p5C8LEzgHVKlntFxP1BXNwlb90bWtN1',13,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiNHoxZXllMlMwTG1GRDFPeXJ1ekQwZUZMQ2RVcjc5bDcyQmFSUFJyRiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovL3NjbXMudGVzdC9zdXBlci1hZG1pbi9kYXNoYm9hcmQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNDoiaHR0cDovL3NjbXMudGVzdC9zdXBlci1hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTc6ImxvZ2luX3N1cGVyYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6MTA6ImF1dGhfZ3VhcmQiO3M6MTA6InN1cGVyYWRtaW4iO3M6MjU6InN1cGVyYWRtaW5fc2Vzc2lvbl9hY3RpdmUiO2I6MTt9',1761217014),('SM5S8to26lbG4OVDPzEpeVQg8aWdLnpzhJRrdWYc',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM1ZSVXk0VGJTa3Q5ZUVtRW1rMmpiZXVQdTBSSko3WjNrRzlJWjZsNyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9zY21zLnRlc3QvbG9naW4iO319',1761215326),('T2xWgJa5HjwPTHRLIDHpLRWemUhdjSoyg9p6V5qV',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRkhSYVdDZFp0Zko1QVRUdE1oQUpITmxFVG5mamRQS1Blb0ViMWhTeSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9zY21zLnRlc3QvbG9naW4iO319',1761229687),('T97cB0ctDyQKtR33DkM0X1fbZATYNmjKAe1HlfSw',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR2VZRWxpRTU0VmhUUFkzNDZ2SVFFZWNmdG1Qb0FLTUVhVkNpSlVrcCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9zY21zLnRlc3QvbG9naW4iO319',1761223423),('Uq97JX4Dc86InxvfIjZs0HC4HllGVxwCnZsgRphz',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTo5OntzOjY6Il90b2tlbiI7czo0MDoiTlY2NkgxZUlUVUdwdnNzZTBiOXBoUERnYTBUY3RrYk1MV1BQM1JBWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9zY21zLnRlc3QvbG9naW4iO31zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxMDoiYXV0aF9ndWFyZCI7czoxMDoic3VwZXJhZG1pbiI7czoyMDoiYWRtaW5fc2Vzc2lvbl9hY3RpdmUiO2I6MTtzOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjI2OiJodHRwOi8vc2Ntcy50ZXN0L2Rhc2hib2FyZCI7fXM6NTc6ImxvZ2luX3N1cGVyYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6MjU6InN1cGVyYWRtaW5fc2Vzc2lvbl9hY3RpdmUiO2I6MTt9',1761218309),('vVrVS7OA21RwIeU0uMMEOh3KMlXJY7aWCWWtYOaL',13,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVngwdXN3dHgxb1RUalhaaFdOQUcyeEVVUUhrbVNVQzJXY1BPRzM1OSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9zY21zLnRlc3Qvc3VwZXItYWRtaW4vbG9naW4iO31zOjU3OiJsb2dpbl9zdXBlcmFkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjEwOiJhdXRoX2d1YXJkIjtzOjEwOiJzdXBlcmFkbWluIjtzOjI1OiJzdXBlcmFkbWluX3Nlc3Npb25fYWN0aXZlIjtiOjE7fQ==',1761217011),('wVxc5Lrr4qpgaEIjLX7KobZoQ3nZlUlUC6fxg0B7',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM3pQNHQ3RXhlR2xsNFU1a0ltdm9jd2ZSM1dYUEl5M3N0bWxwZ2NXWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9zY21zLnRlc3Qvc3VwZXItYWRtaW4vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1761208569),('yZITsoWnDKzhm56gAzkT9d49VMnmZCqj9DjFUAjb',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.22.3 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZWoyVlBLbExua3JYWVZxVEhnMVhKRXNTajJDRjNCaHBvOUlwb1ZKRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9zY21zLnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1761283066),('zK7JyquX8zRMdYAzGCVR9QSosUm0LSx4SIs0sxHa',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YToyOntzOjY6Il90b2tlbiI7czo0MDoiUjN4Ylp4bGxRalBFVzlJM0duTHo3dzE0VTJ3QUJCQVpPY1ljbEJwbSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1761283281),('zQek81iTyDIYsmYDIcy08fnysxQHJYKNByV5wog6',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRU9LSHJSVlB2aEJwV3JlQTRQejljMUdVdHQ4UGZWWENUdEw4QjNIVCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNjoiaHR0cDovL3NjbXMudGVzdC9kYXNoYm9hcmQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyMjoiaHR0cDovL3NjbXMudGVzdC9sb2dpbiI7fX0=',1761208593);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_contract_approvals`
--

DROP TABLE IF EXISTS `social_contract_approvals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_contract_approvals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `social_contract_record_id` bigint unsigned NOT NULL,
  `student_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organization` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `venue` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hours_rendered` int NOT NULL,
  `date` date NOT NULL,
  `status` enum('Verified','Approved','Rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Verified',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `verified_by` bigint unsigned DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `social_contract_approvals_social_contract_record_id_foreign` (`social_contract_record_id`),
  KEY `social_contract_approvals_verified_by_foreign` (`verified_by`),
  KEY `social_contract_approvals_approved_by_foreign` (`approved_by`),
  CONSTRAINT `social_contract_approvals_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `super_admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `social_contract_approvals_social_contract_record_id_foreign` FOREIGN KEY (`social_contract_record_id`) REFERENCES `social_contract_records` (`id`) ON DELETE CASCADE,
  CONSTRAINT `social_contract_approvals_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_contract_approvals`
--

LOCK TABLES `social_contract_approvals` WRITE;
/*!40000 ALTER TABLE `social_contract_approvals` DISABLE KEYS */;
INSERT INTO `social_contract_approvals` VALUES (1,1,'23-3171','Leila Sarte','Community Clean-up Drive','Barangay Council','Barangay Hall, District 1',8,'2025-10-14','Verified',NULL,NULL,NULL,'2025-10-19 03:25:40',NULL,'2025-10-22 04:55:44','2025-10-22 23:30:50'),(2,2,'23-3171','Leila Sarte','Tree Planting Activity','Environmental Club','PLV Campus Grounds',6,'2025-10-10','Verified',NULL,NULL,NULL,'2025-10-20 03:25:40',NULL,'2025-10-22 04:55:44','2025-10-22 23:30:50'),(3,4,'23-6969','Jet Pagaduan','Blood Donation Drive','Philippine Red Cross','Red Cross Center',4,'2025-10-07','Verified',NULL,NULL,NULL,'2025-10-17 03:25:40',NULL,'2025-10-22 04:55:44','2025-10-22 23:30:50'),(4,5,'23-6969','Jet Pagaduan','Outreach Program','Student Council','Orphanage Home',10,'2025-10-12','Approved',NULL,NULL,2,'2025-10-21 03:25:40','2025-10-22 08:48:54','2025-10-22 04:55:44','2025-10-22 23:30:50'),(5,7,'23-3371','Angel Dimatulac','Tutorial Program','Education Foundation','Public Library',12,'2025-10-08','Verified',NULL,NULL,NULL,'2025-10-18 03:25:40',NULL,'2025-10-22 04:55:44','2025-10-22 23:30:50'),(6,10,'23-3401','Jan Rafael San Andres','Coastal Cleanup','Ocean Warriors','Manila Bay',9,'2025-10-11','Approved',NULL,NULL,2,'2025-10-16 03:25:40','2025-10-22 05:28:57','2025-10-22 04:55:44','2025-10-22 23:30:50'),(7,1,'23-3171','Leila Sarte','Community Clean-up Drive','Barangay Council','Barangay Hall, District 1',8,'2025-10-14','Approved',NULL,NULL,NULL,'2025-10-19 03:25:40','2025-10-22 05:06:42','2025-10-22 05:06:42','2025-10-22 23:30:50'),(8,1,'23-3171','Leila Sarte','Community Clean-up Drive','Barangay Council','Barangay Hall, District 1',8,'2025-10-14','Rejected','Test rejection for demo',NULL,NULL,'2025-10-19 03:25:40','2025-10-22 05:06:42','2025-10-22 05:06:42','2025-10-22 23:30:50'),(9,13,'23-3401','Jan Rafael San Andres','Youth Leadership Summit','Youth Council','Convention Center',6,'2025-10-20','Approved',NULL,1,2,'2025-10-22 07:03:10','2025-10-22 07:03:24','2025-10-22 07:03:10','2025-10-22 23:30:50'),(11,12,'23-3401','Jan Rafael San Andres','Cultural Festival Volunteer','Cultural Affairs','City Plaza',10,'2025-10-17','Approved',NULL,1,2,'2025-10-22 07:18:48','2025-10-22 07:19:06','2025-10-22 07:18:48','2025-10-22 23:30:50'),(12,14,'23-3401','Jan Rafael San Andres','Vitskwela','Psalmuelle Balite','CEIT',8,'2025-10-21','Verified',NULL,1,NULL,'2025-10-22 08:33:19',NULL,'2025-10-22 08:33:19','2025-10-22 08:33:19'),(13,16,'23-3495','Psalmuelle Balite','Vitskwela','Samantha Luayon','CEIT',6,'2025-10-21','Approved',NULL,1,2,'2025-10-22 23:53:10','2025-10-22 23:53:23','2025-10-22 23:53:10','2025-10-22 23:53:23'),(14,15,'23-3401','Jan Rafael San Andres','Library','Alea Escala','CEIT',5,'2025-10-21','Verified',NULL,1,NULL,'2025-10-22 23:57:30',NULL,'2025-10-22 23:57:30','2025-10-22 23:57:30'),(15,17,'23-3401','Jan Rafael San Andres','ITLYMPICS','Psalmuelle Balite','CEIT',6,'2025-10-09','Rejected','Duplicate',1,2,'2025-10-23 00:04:22','2025-10-23 03:18:16','2025-10-23 00:04:22','2025-10-23 03:18:16'),(16,21,'23-3495','Psalmuelle Balite','ITLYMPICS','Alea Escala','CEIT',0,'2025-10-09','Verified',NULL,1,NULL,'2025-10-23 00:25:18',NULL,'2025-10-23 00:25:18','2025-10-23 00:25:18'),(17,20,'23-3495','Psalmuelle Balite','Planting Tree','Samantha Luayon','Public Ad',5,'2025-10-21','Approved',NULL,NULL,2,'2025-10-23 00:27:03','2025-10-23 04:49:34','2025-10-23 00:27:03','2025-10-23 04:49:34'),(18,19,'23-3495','Psalmuelle Balite','Gamecon','Kenmar Bernardino','CEIT',5,'2025-10-22','Verified',NULL,NULL,NULL,'2025-10-23 03:58:55',NULL,'2025-10-23 03:58:55','2025-10-23 03:58:55'),(19,23,'23-3495','Psalmuelle Balite','ITLYMPICS','Samantha Luayon','CEIT',5,'2025-10-22','Rejected','Duplicate',NULL,2,'2025-10-23 04:44:58','2025-10-23 04:45:08','2025-10-23 04:44:58','2025-10-23 04:45:08');
/*!40000 ALTER TABLE `social_contract_approvals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_contract_record_status_history`
--

DROP TABLE IF EXISTS `social_contract_record_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_contract_record_status_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `social_contract_record_id` bigint unsigned NOT NULL,
  `old_status` enum('Pending','Verified','Rejected','Approved') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_status` enum('Pending','Verified','Rejected','Approved') COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` bigint unsigned DEFAULT NULL,
  `changed_at` timestamp NOT NULL,
  `change_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scr_history_admin_fk` (`changed_by`),
  KEY `scr_history_record_date_idx` (`social_contract_record_id`,`changed_at`),
  KEY `scr_history_status_idx` (`new_status`),
  CONSTRAINT `scr_history_admin_fk` FOREIGN KEY (`changed_by`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `scr_history_record_fk` FOREIGN KEY (`social_contract_record_id`) REFERENCES `social_contract_records` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_contract_record_status_history`
--

LOCK TABLES `social_contract_record_status_history` WRITE;
/*!40000 ALTER TABLE `social_contract_record_status_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_contract_record_status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_contract_records`
--

DROP TABLE IF EXISTS `social_contract_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_contract_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `social_contract_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `event_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `venue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organization` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hours_rendered` int unsigned NOT NULL,
  `status` enum('Pending','Verified','Rejected','Approved') COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `social_contract_records_social_contract_id_foreign` (`social_contract_id`),
  CONSTRAINT `social_contract_records_social_contract_id_foreign` FOREIGN KEY (`social_contract_id`) REFERENCES `social_contracts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_contract_records`
--

LOCK TABLES `social_contract_records` WRITE;
/*!40000 ALTER TABLE `social_contract_records` DISABLE KEYS */;
INSERT INTO `social_contract_records` VALUES (1,1,'2025-10-14','Community Clean-up Drive','Barangay Hall, District 1','Barangay Council',8,'Verified',NULL,'2025-10-12 03:25:40','2025-10-19 03:25:40'),(2,1,'2025-10-10','Tree Planting Activity','PLV Campus Grounds','Environmental Club',6,'Verified',NULL,'2025-10-07 03:25:40','2025-10-20 03:25:40'),(3,1,'2025-10-17','Feeding Program','Elementary School','Lions Club',5,'Pending',NULL,'2025-10-19 03:25:40','2025-10-19 03:25:40'),(4,2,'2025-10-07','Blood Donation Drive','Red Cross Center','Philippine Red Cross',4,'Verified',NULL,'2025-10-10 03:25:40','2025-10-17 03:25:40'),(5,2,'2025-10-12','Outreach Program','Orphanage Home','Student Council',10,'Approved',NULL,'2025-10-14 03:25:40','2025-10-22 08:48:54'),(6,2,'2025-10-15','Medical Mission','Community Center','Health Department',7,'Pending',NULL,'2025-10-14 03:25:40','2025-10-14 03:25:40'),(7,3,'2025-10-08','Tutorial Program','Public Library','Education Foundation',12,'Verified',NULL,'2025-10-12 03:25:40','2025-10-18 03:25:40'),(8,3,'2025-10-13','Disaster Preparedness Seminar','City Hall','NDRRMC',6,'Rejected',NULL,'2025-10-15 03:25:40','2025-10-20 03:25:40'),(9,3,'2025-10-18','Sports Clinic','Sports Complex','Athletics Department',8,'Rejected',NULL,'2025-10-20 03:25:40','2025-10-23 00:04:12'),(10,4,'2025-10-11','Coastal Cleanup','Manila Bay','Ocean Warriors',9,'Approved',NULL,'2025-10-14 03:25:40','2025-10-22 05:28:57'),(11,4,'2025-10-13','Book Drive','School Campus','Library Committee',5,'Rejected',NULL,'2025-10-15 03:25:40','2025-10-18 03:25:40'),(12,4,'2025-10-17','Cultural Festival Volunteer','City Plaza','Cultural Affairs',10,'Approved',NULL,'2025-10-19 03:25:40','2025-10-22 07:19:06'),(13,4,'2025-10-20','Youth Leadership Summit','Convention Center','Youth Council',6,'Approved',NULL,'2025-10-21 03:25:40','2025-10-22 07:03:24'),(14,4,'2025-10-21','Vitskwela','CEIT','Psalmuelle Balite',8,'Verified',NULL,'2025-10-22 07:19:51','2025-10-22 08:33:19'),(15,4,'2025-10-21','Library','CEIT','Alea Escala',5,'Verified',NULL,'2025-10-22 09:12:25','2025-10-22 23:57:30'),(16,5,'2025-10-21','Vitskwela','CEIT','Samantha Luayon',6,'Approved',NULL,'2025-10-22 23:52:11','2025-10-22 23:53:23'),(17,4,'2025-10-09','ITLYMPICS','CEIT','Psalmuelle Balite',6,'Rejected',NULL,'2025-10-23 00:00:01','2025-10-23 03:18:16'),(18,5,'2025-10-19','ITLYMPICS','CEIT','Alea Escala',10,'Rejected','Duplicate','2025-10-23 00:08:10','2025-10-23 03:32:30'),(19,5,'2025-10-22','Gamecon','CEIT','Kenmar Bernardino',5,'Verified',NULL,'2025-10-23 00:14:20','2025-10-23 03:58:55'),(20,5,'2025-10-21','Planting Tree','Public Ad','Samantha Luayon',5,'Approved',NULL,'2025-10-23 00:19:28','2025-10-23 04:49:34'),(21,5,'2025-10-09','ITLYMPICS','CEIT','Alea Escala',0,'Verified',NULL,'2025-10-23 00:20:10','2025-10-23 00:25:18'),(22,5,'2025-10-24','ITLYMPICS','CEIT','Samantha Luayon',4,'Rejected','Duplicate','2025-10-23 03:56:17','2025-10-23 03:58:51'),(23,5,'2025-10-22','ITLYMPICS','CEIT','Samantha Luayon',5,'Rejected','Duplicate','2025-10-23 04:44:06','2025-10-23 04:45:08'),(24,5,'2025-10-17','ITLYMPICS','CEIT','Samantha Luayon',4,'Rejected','Duplicate','2025-10-23 04:44:26','2025-10-23 04:44:52');
/*!40000 ALTER TABLE `social_contract_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_contracts`
--

DROP TABLE IF EXISTS `social_contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_contracts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `submission_date` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `social_contracts_student_id_foreign` (`student_id`),
  CONSTRAINT `social_contracts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_contracts`
--

LOCK TABLES `social_contracts` WRITE;
/*!40000 ALTER TABLE `social_contracts` DISABLE KEYS */;
INSERT INTO `social_contracts` VALUES (1,3,'2025-09-22 02:49:40','submitted','2025-09-22 02:49:40','2025-09-22 02:49:40'),(2,5,'2025-09-27 02:49:40','submitted','2025-09-27 02:49:40','2025-09-27 02:49:40'),(3,6,'2025-10-02 02:49:40','submitted','2025-10-02 02:49:40','2025-10-02 02:49:40'),(4,13,'2025-10-07 02:49:40','submitted','2025-10-07 02:49:40','2025-10-07 02:49:40'),(5,14,'2025-10-22 03:16:27','submitted','2025-10-22 03:16:27','2025-10-22 03:16:27');
/*!40000 ALTER TABLE `social_contracts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_notifications`
--

DROP TABLE IF EXISTS `student_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `social_contract_record_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_notifications_social_contract_record_id_foreign` (`social_contract_record_id`),
  KEY `student_notifications_user_id_is_read_index` (`user_id`,`is_read`),
  KEY `student_notifications_created_at_index` (`created_at`),
  CONSTRAINT `student_notifications_social_contract_record_id_foreign` FOREIGN KEY (`social_contract_record_id`) REFERENCES `social_contract_records` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_notifications`
--

LOCK TABLES `student_notifications` WRITE;
/*!40000 ALTER TABLE `student_notifications` DISABLE KEYS */;
INSERT INTO `student_notifications` VALUES (1,14,22,'rejected','Your social contract submission has been rejected by the super admin.','Event documentation is incomplete. Please provide proper venue details.',1,'2025-10-23 04:13:42','2025-10-23 04:24:42'),(2,14,22,'rejected','Your submission was rejected','Missing attachments. Please attach the required documents.',0,'2025-10-23 04:29:43','2025-10-23 04:29:43'),(3,14,23,'rejected','Your social contract submission has been rejected by the super admin.','Duplicate',0,'2025-10-23 04:45:08','2025-10-23 04:45:08'),(4,14,20,'approved','Your social contract submission has been approved by the super admin.',NULL,0,'2025-10-23 04:49:34','2025-10-23 04:49:34');
/*!40000 ALTER TABLE `student_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `super_admin_password_change_tokens`
--

DROP TABLE IF EXISTS `super_admin_password_change_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `super_admin_password_change_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_password_hash` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `super_admin_password_change_tokens_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `super_admin_password_change_tokens`
--

LOCK TABLES `super_admin_password_change_tokens` WRITE;
/*!40000 ALTER TABLE `super_admin_password_change_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `super_admin_password_change_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `super_admins`
--

DROP TABLE IF EXISTS `super_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `super_admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `super_admins_name_unique` (`name`),
  UNIQUE KEY `super_admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `super_admins`
--

LOCK TABLES `super_admins` WRITE;
/*!40000 ALTER TABLE `super_admins` DISABLE KEYS */;
INSERT INTO `super_admins` VALUES (2,'adminKenmar','janarafael.sanandres@gmail.com','$2y$12$c9P4a/GxI0zqCufIykEpuuxemSRdUxW4oGav3V3cCq2G/1V9ZjD9u','2025-10-22 02:49:41',NULL,'2025-10-12 06:00:04','2025-10-22 23:01:32');
/*!40000 ALTER TABLE `super_admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `superadmin_activity_logs`
--

DROP TABLE IF EXISTS `superadmin_activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `superadmin_activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `super_admin_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `superadmin_activity_logs_super_admin_id_foreign` (`super_admin_id`),
  CONSTRAINT `superadmin_activity_logs_super_admin_id_foreign` FOREIGN KEY (`super_admin_id`) REFERENCES `super_admins` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `superadmin_activity_logs`
--

LOCK TABLES `superadmin_activity_logs` WRITE;
/*!40000 ALTER TABLE `superadmin_activity_logs` DISABLE KEYS */;
INSERT INTO `superadmin_activity_logs` VALUES (1,2,'approved_submission','Approved submission for Coastal Cleanup','\"{\\\"approval_id\\\":6,\\\"record_id\\\":10,\\\"event_name\\\":\\\"Coastal Cleanup\\\",\\\"student_name\\\":\\\"Jan Rafael San Andres\\\"}\"',NULL,NULL,'2025-10-22 13:28:57'),(2,2,'verified_submission','Verified submission for Youth Leadership Summit','\"{\\\"record_id\\\":13,\\\"event_name\\\":\\\"Youth Leadership Summit\\\",\\\"old_status\\\":\\\"Pending\\\",\\\"new_status\\\":\\\"Verified\\\"}\"',NULL,NULL,'2025-10-22 15:03:10'),(3,2,'approved_submission','Approved submission for Youth Leadership Summit','\"{\\\"approval_id\\\":9,\\\"record_id\\\":13,\\\"event_name\\\":\\\"Youth Leadership Summit\\\",\\\"student_name\\\":\\\"Jan Rafael San Andres\\\"}\"',NULL,NULL,'2025-10-22 15:03:24'),(4,2,'approved_submission','Approved submission for Cultural Festival Volunteer','\"{\\\"approval_id\\\":11,\\\"record_id\\\":12,\\\"event_name\\\":\\\"Cultural Festival Volunteer\\\",\\\"student_name\\\":\\\"Jan Rafael San Andres\\\"}\"',NULL,NULL,'2025-10-22 15:19:06'),(5,2,'approved_submission','Approved submission for Outreach Program','\"{\\\"approval_id\\\":4,\\\"record_id\\\":5,\\\"event_name\\\":\\\"Outreach Program\\\",\\\"student_name\\\":\\\"Jet Pagaduan\\\"}\"',NULL,NULL,'2025-10-22 16:48:54'),(6,2,'verified_submission','Verified submission for Vitskwela','\"{\\\"record_id\\\":16,\\\"event_name\\\":\\\"Vitskwela\\\",\\\"old_status\\\":\\\"Pending\\\",\\\"new_status\\\":\\\"Verified\\\"}\"',NULL,NULL,'2025-10-23 07:53:10'),(7,2,'approved_submission','Approved submission for Vitskwela','\"{\\\"approval_id\\\":13,\\\"record_id\\\":16,\\\"event_name\\\":\\\"Vitskwela\\\",\\\"student_name\\\":\\\"Psalmuelle Balite\\\"}\"',NULL,NULL,'2025-10-23 07:53:23'),(8,2,'verified_submission','Verified submission for Planting Tree','\"{\\\"record_id\\\":20,\\\"event_name\\\":\\\"Planting Tree\\\",\\\"old_status\\\":\\\"Pending\\\",\\\"new_status\\\":\\\"Verified\\\"}\"',NULL,NULL,'2025-10-23 08:27:03'),(9,2,'rejected_submission','Rejected submission for ITLYMPICS','\"{\\\"approval_id\\\":15,\\\"record_id\\\":17,\\\"event_name\\\":\\\"ITLYMPICS\\\",\\\"student_name\\\":\\\"Jan Rafael San Andres\\\",\\\"reason\\\":\\\"Duplicate\\\"}\"',NULL,NULL,'2025-10-23 11:18:16'),(10,2,'rejected_submission','Rejected submission for ITLYMPICS','\"{\\\"record_id\\\":22,\\\"event_name\\\":\\\"ITLYMPICS\\\",\\\"reason\\\":\\\"Duplicate\\\"}\"',NULL,NULL,'2025-10-23 11:58:51'),(11,2,'verified_submission','Verified submission for Gamecon','\"{\\\"record_id\\\":19,\\\"event_name\\\":\\\"Gamecon\\\",\\\"old_status\\\":\\\"Pending\\\",\\\"new_status\\\":\\\"Verified\\\"}\"',NULL,NULL,'2025-10-23 11:58:55'),(12,2,'rejected_submission','Rejected submission for ITLYMPICS','\"{\\\"record_id\\\":24,\\\"event_name\\\":\\\"ITLYMPICS\\\",\\\"reason\\\":\\\"Duplicate\\\"}\"',NULL,NULL,'2025-10-23 12:44:52'),(13,2,'verified_submission','Verified submission for ITLYMPICS','\"{\\\"record_id\\\":23,\\\"event_name\\\":\\\"ITLYMPICS\\\",\\\"old_status\\\":\\\"Pending\\\",\\\"new_status\\\":\\\"Verified\\\"}\"',NULL,NULL,'2025-10-23 12:44:58'),(14,2,'rejected_submission','Rejected submission for ITLYMPICS','\"{\\\"approval_id\\\":19,\\\"record_id\\\":23,\\\"event_name\\\":\\\"ITLYMPICS\\\",\\\"student_name\\\":\\\"Psalmuelle Balite\\\",\\\"reason\\\":\\\"Duplicate\\\"}\"',NULL,NULL,'2025-10-23 12:45:08'),(15,2,'approved_submission','Approved submission for Planting Tree','\"{\\\"approval_id\\\":17,\\\"record_id\\\":20,\\\"event_name\\\":\\\"Planting Tree\\\",\\\"student_name\\\":\\\"Psalmuelle Balite\\\"}\"',NULL,NULL,'2025-10-23 12:49:34');
/*!40000 ALTER TABLE `superadmin_activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_logs`
--

DROP TABLE IF EXISTS `transaction_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `contract_id` bigint unsigned NOT NULL,
  `user_role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `transaction_logs_user_id_foreign` (`user_id`),
  KEY `transaction_logs_contract_id_foreign` (`contract_id`),
  CONSTRAINT `transaction_logs_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `social_contracts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_logs`
--

LOCK TABLES `transaction_logs` WRITE;
/*!40000 ALTER TABLE `transaction_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `student_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_student_id_unique` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'Leila Sarte','leilanicolesarte@plv.edu.ph','23-3171',NULL,'$2y$12$.s5Vx4KtveV2uTngGpMLHu1zDZgO51t7lfPbAGIUFCVokypJSZDJa','student',NULL,NULL,NULL,NULL,NULL,NULL,'2025-10-12 09:01:04','2025-10-12 09:01:04'),(5,'Jet Pagaduan','jetangelopagaduan@plv.edu.ph','23-6969',NULL,'$2y$12$9PkviPCHun3BfCMPm0rB6uxTU/jEVs4QRU5yVXBNY/HLZ3P0Rmthm','student',NULL,NULL,NULL,NULL,NULL,NULL,'2025-10-13 05:33:48','2025-10-13 05:33:48'),(6,'Angel Dimatulac','angelcoleendimatulac@plv.edu.ph','23-3371',NULL,'$2y$12$2SrjxlGWWQYfnOVqG7ST1OFzu16VOV4Z5shjLGEzMufAbG/ecjRZ6','student',NULL,NULL,NULL,NULL,'BZrLbh4f3HWWXQjVPp8XKrc7w7rPiVp6sCXd6VqcjemHuAOpiRHVmX7hDJBy',NULL,'2025-10-13 06:07:12','2025-10-13 06:07:12'),(13,'Jan Rafael San Andres','janrafaelsanandres@plv.edu.ph','23-3401','2025-10-13 09:51:02','$2y$12$WdRxaG6Jmgo6nInoqxInq.B9XW.ZcaoS0kvEYt9/l4Ef2aZ8.75lO','student',NULL,NULL,NULL,NULL,'FScdqFaPF3UGN1fM0Mj0Tvzwqz5BuC8CNL2QE1J4ERIu0IuSQvVmJ5Mr6Vfq',NULL,'2025-10-13 09:50:44','2025-10-22 09:52:11'),(14,'Psalmuelle Balite','psalmuelledekbalite@plv.edu.ph','23-3495','2025-10-22 03:16:14','$2y$12$Nnc9zFytdTCjm2vB47V3m.ZHNzobIlIlzhFas/xxvUxh7rXp0wYyC','student',NULL,NULL,NULL,NULL,'mkjEE1Eggy0ZFwvl97BlIyiRCyxgwnRKsaH6F3MvBkc0q8Z3bsB9hKKVyTIC','2kRIyCFiael0WGhWhsMpFvMfP5J2uuImxJRW3MYY','2025-10-22 03:15:48','2025-10-22 09:52:18');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verifications`
--

DROP TABLE IF EXISTS `verifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` bigint unsigned NOT NULL,
  `supervisor_id` bigint unsigned DEFAULT NULL,
  `verification_date` timestamp NULL DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `verifications_contract_id_foreign` (`contract_id`),
  KEY `verifications_supervisor_id_foreign` (`supervisor_id`),
  CONSTRAINT `verifications_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `social_contracts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `verifications_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verifications`
--

LOCK TABLES `verifications` WRITE;
/*!40000 ALTER TABLE `verifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `verifications` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-24 15:39:46
