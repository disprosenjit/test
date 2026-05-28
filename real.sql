-- --------------------------------------------------------
-- Host:                         192.168.1.7
-- Server version:               8.0.45-0ubuntu0.22.04.1 - (Ubuntu)
-- Server OS:                    Linux
-- HeidiSQL Version:             12.17.1.1
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for fl_real
CREATE DATABASE IF NOT EXISTS `fl_real` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `fl_real`;

-- Dumping structure for table fl_real.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_real.cache: ~0 rows (approximately)
DELETE FROM `cache`;

-- Dumping structure for table fl_real.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_real.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table fl_real.contact_inquiries
CREATE TABLE IF NOT EXISTS `contact_inquiries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_inquiries_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_real.contact_inquiries: ~1 rows (approximately)
DELETE FROM `contact_inquiries`;
INSERT INTO `contact_inquiries` (`id`, `name`, `email`, `phone`, `message`, `subject`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'xZZXx', 'dfasda@xvdsf.asf', '2343242342344', 'd fdssd', 'czxfdasf sdfds fds', 'replied', '2026-05-25 16:30:35', '2026-05-25 16:31:06');

-- Dumping structure for table fl_real.failed_jobs
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

-- Dumping data for table fl_real.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table fl_real.investment_inquiries
CREATE TABLE IF NOT EXISTS `investment_inquiries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inquiry_details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `investment_amount` decimal(15,2) DEFAULT NULL,
  `investment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `investment_inquiries_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_real.investment_inquiries: ~0 rows (approximately)
DELETE FROM `investment_inquiries`;

-- Dumping structure for table fl_real.job_batches
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

-- Dumping data for table fl_real.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table fl_real.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
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

-- Dumping data for table fl_real.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table fl_real.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_real.migrations: ~9 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_05_25_184214_create_properties_table', 2),
	(5, '2026_05_25_184215_create_contact_inquiries_table', 2),
	(6, '2026_05_25_184215_create_property_images_table', 2),
	(7, '2026_05_25_184216_create_special_requests_table', 2),
	(8, '2026_05_25_184217_create_investment_inquiries_table', 2),
	(9, '2026_05_28_000001_add_location_coords_to_properties_table', 3);

-- Dumping structure for table fl_real.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_real.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table fl_real.properties
CREATE TABLE IF NOT EXISTS `properties` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area` double NOT NULL,
  `bedrooms` int DEFAULT NULL,
  `bathrooms` int DEFAULT NULL,
  `parking_spaces` int DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `agent_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `properties_type_index` (`type`),
  KEY `properties_is_available_index` (`is_available`),
  KEY `properties_country_index` (`country`),
  KEY `properties_state_index` (`state`),
  KEY `properties_city_index` (`city`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_real.properties: ~8 rows (approximately)
DELETE FROM `properties`;
INSERT INTO `properties` (`id`, `title`, `description`, `price`, `type`, `location`, `country`, `state`, `city`, `latitude`, `longitude`, `address`, `area`, `bedrooms`, `bathrooms`, `parking_spaces`, `is_featured`, `is_available`, `agent_name`, `agent_contact`, `created_at`, `updated_at`) VALUES
	(1, 'Beautiful Downtown Apartment', 'Modern apartment in the heart of downtown with stunning city views, full amenities, and close proximity to restaurants and shopping centers.', 350000.00, 'residential', 'New York City, New York', 'United States', 'New York', 'New York City', 40.7128000, -74.0060000, '123 Main Street, Downtown District', 1500, 2, 2, 2, 1, 1, 'John Smith', '+1 (555) 123-4567', '2026-05-28 05:05:14', '2026-05-28 05:05:14'),
	(2, 'Modern Office Complex', 'Prime commercial space perfect for corporate offices. Features high-speed internet, professional workspace, parking, and modern amenities.', 750000.00, 'commercial', 'Los Angeles, California', 'United States', 'California', 'Los Angeles', 34.0522000, -118.2437000, '456 Commerce Avenue, Business Hub', 5000, NULL, 4, 15, 1, 1, 'Sarah Johnson', '+1 (555) 234-5678', '2026-05-28 05:05:14', '2026-05-28 05:05:14'),
	(3, 'Spacious Suburban Home', 'Beautiful family home in a quiet suburban neighborhood with large yard, modern kitchen, and excellent schools nearby.', 425000.00, 'residential', 'Houston, Texas', 'United States', 'Texas', 'Houston', 29.7604000, -95.3698000, '789 Oak Lane, Quiet Neighborhood', 2500, 4, 3, 3, 1, 1, 'Michael Brown', '+1 (555) 345-6789', '2026-05-28 05:05:14', '2026-05-28 05:05:14'),
	(4, 'Prime Development Land', 'Excellent investment opportunity. Zoned for mixed-use development with great potential for residential or commercial projects.', 500000.00, 'land', 'Miami, Florida', 'United States', 'Florida', 'Miami', 25.7617000, -80.1918000, '321 Future Development Way', 10000, NULL, NULL, NULL, 1, 1, 'John Smith', '+1 (555) 123-4567', '2026-05-28 05:05:14', '2026-05-28 05:05:14'),
	(5, 'Luxury Penthouse', 'Stunning penthouse with panoramic views, premium finishes, private balcony, and exclusive building amenities.', 890000.00, 'residential', 'Chicago, Illinois', 'United States', 'Illinois', 'Chicago', 41.8781000, -87.6298000, '999 Luxury Heights, Downtown', 3200, 3, 3, 3, 1, 1, 'Sarah Johnson', '+1 (555) 234-5678', '2026-05-28 05:05:14', '2026-05-28 05:05:14'),
	(6, 'Cozy Studio Apartment', 'Perfect starter home or investment property. Recently renovated with modern appliances and neutral decor.', 180000.00, 'residential', 'Seattle, Washington', 'United States', 'Washington', 'Seattle', 47.6062000, -122.3321000, '456 Central Park Avenue', 650, 1, 1, 1, 0, 1, 'Michael Brown', '+1 (555) 345-6789', '2026-05-28 05:05:14', '2026-05-28 05:05:14'),
	(7, 'Retail Shop Space', 'Excellent retail location with high foot traffic. Ideal for boutique, cafe, or any retail business.', 300000.00, 'commercial', 'San Francisco, California', 'United States', 'California', 'San Francisco', 37.7749000, -122.4194000, '555 Retail Plaza', 2000, NULL, 2, 8, 0, 1, 'John Smith', '+1 (555) 123-4567', '2026-05-28 05:05:14', '2026-05-28 05:05:14'),
	(8, 'Waterfront Property', 'Stunning waterfront residence with direct beach access, modern architecture, and breathtaking water views.', 1200000.00, 'residential', 'Miami Beach, Florida', 'United States', 'Florida', 'Miami Beach', 25.7907000, -80.1300000, '100 Ocean Drive, Beach Front', 4000, 5, 4, 4, 1, 1, 'Sarah Johnson', '+1 (555) 234-5678', '2026-05-28 05:05:14', '2026-05-28 05:05:14');

-- Dumping structure for table fl_real.property_images
CREATE TABLE IF NOT EXISTS `property_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `property_id` bigint unsigned NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `property_images_property_id_foreign` (`property_id`),
  CONSTRAINT `property_images_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_real.property_images: ~20 rows (approximately)
DELETE FROM `property_images`;
INSERT INTO `property_images` (`id`, `property_id`, `image_path`, `alt_text`, `order`, `created_at`, `updated_at`) VALUES
	(1, 1, 'images/properties/apartment-1.jpg', 'Modern downtown apartment living room', 1, '2026-05-25 15:58:57', '2026-05-25 15:58:57'),
	(2, 1, 'images/properties/apartment-2.jpg', 'Downtown apartment bedroom', 2, '2026-05-25 15:58:57', '2026-05-25 15:58:57'),
	(3, 1, 'images/properties/apartment-3.jpg', 'Downtown apartment kitchen', 3, '2026-05-25 15:58:57', '2026-05-25 15:58:57'),
	(4, 2, 'images/properties/office-1.jpg', 'Modern office workspace', 1, '2026-05-25 15:58:57', '2026-05-25 15:58:57'),
	(6, 3, 'images/properties/suburban-1.jpg', 'Spacious suburban home exterior', 1, '2026-05-25 15:58:57', '2026-05-25 15:58:57'),
	(7, 3, 'images/properties/suburban-2.jpg', 'Suburban home with pool', 2, '2026-05-25 15:58:57', '2026-05-25 15:58:57'),
	(8, 3, 'images/properties/suburban-3.jpg', 'Suburban home interior', 3, '2026-05-25 15:58:57', '2026-05-25 15:58:57'),
	(9, 4, 'images/properties/land-1.jpg', 'Green development land', 1, '2026-05-25 15:58:57', '2026-05-25 15:58:57'),
	(10, 4, 'images/properties/land-2.jpg', 'Land plot aerial view', 2, '2026-05-25 15:58:57', '2026-05-25 15:58:57'),
	(11, 5, 'images/properties/penthouse-1.jpg', 'Luxury penthouse living area', 1, '2026-05-25 15:58:57', '2026-05-25 15:58:57'),
	(12, 5, 'images/properties/penthouse-2.jpg', 'Penthouse terrace view', 2, '2026-05-25 15:58:57', '2026-05-25 15:58:57'),
	(13, 5, 'images/properties/penthouse-3.jpg', 'Penthouse master suite', 3, '2026-05-25 15:58:58', '2026-05-25 15:58:58'),
	(14, 6, 'images/properties/studio-1.jpg', 'Cozy studio apartment', 1, '2026-05-25 15:58:58', '2026-05-25 15:58:58'),
	(15, 6, 'images/properties/studio-2.jpg', 'Studio apartment living space', 2, '2026-05-25 15:58:58', '2026-05-25 15:58:58'),
	(16, 7, 'images/properties/retail-1.jpg', 'Retail shop interior', 1, '2026-05-25 15:58:58', '2026-05-25 15:58:58'),
	(17, 7, 'images/properties/retail-2.jpg', 'Retail space storefront', 2, '2026-05-25 15:58:58', '2026-05-25 15:58:58'),
	(18, 8, 'images/properties/waterfront-1.jpg', 'Luxury waterfront home', 1, '2026-05-25 15:58:58', '2026-05-25 15:58:58'),
	(19, 8, 'images/properties/waterfront-2.jpg', 'Waterfront property exterior', 2, '2026-05-25 15:58:58', '2026-05-25 15:58:58'),
	(20, 8, 'images/properties/waterfront-3.jpg', 'Waterfront home with garden', 3, '2026-05-25 15:58:58', '2026-05-25 15:58:58');

-- Dumping structure for table fl_real.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
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

-- Dumping data for table fl_real.sessions: ~3 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('2HJulTvvqeg5OsfoL029GmKuP9uAFXCFk2KjKwrh', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR2FjVUVlamJJVXNwU05hc0tuUHNFaXF2dnB2UHh4d0l5OGQ2T3NBSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6OTAwMSI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779967805),
	('EnA0AYkLIHb7W68UGWpiUXyPk91ypI9W0YHcTXfx', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUlY0SHZHZlFPV1psdzFWSnRIamRoQm44a3RQNGprV0t4bWpFd0NoYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbC5yZWFsZXN0YXRlL2NvbnRhY3QiO3M6NToicm91dGUiO3M6MTQ6ImNvbnRhY3QuY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779968847),
	('L9ouNNx76XEx8lfHSq1aQ0BKE8w4yATNaBT0ARxk', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicHg4UU5HdFdod1poV2hOdWsyZHFUOW11ODRhWlNSamk5MjNGMTJXTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6OTAwMSI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779967799);

-- Dumping structure for table fl_real.special_requests
CREATE TABLE IF NOT EXISTS `special_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget_range` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_preference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_type_preference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `special_requests_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_real.special_requests: ~0 rows (approximately)
DELETE FROM `special_requests`;

-- Dumping structure for table fl_real.users
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_real.users: ~2 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Test User', 'test@example.com', '2026-05-25 13:18:21', '$2y$12$vVoQ30aJguA9Ot04Z9OxLe1dr1unFv9X8m7u39M8FotVTPzsH0gja', 'VAIbaW9Gov', '2026-05-25 13:18:22', '2026-05-25 13:18:22'),
	(3, 'Admin User', 'admin@realestate.com', '2026-05-25 13:46:03', '$2y$12$DvNciVSRfUdah/ROBtx2COS7NaEikMrwVGSX0VbOcR/RFeWv.WzJ2', '2fzo5Dv5zcbNqqADkF3teQKVerGMnN8f4EbMLS5iCYmbqm4456CBQifLViFK', '2026-05-25 13:46:03', '2026-05-25 13:46:03');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
