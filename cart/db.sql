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


-- Dumping database structure for fl_0001
DROP DATABASE IF EXISTS `fl_0001`;
CREATE DATABASE IF NOT EXISTS `fl_0001` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `fl_0001`;

-- Dumping structure for table fl_0001.addresses
DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('billing','shipping','both') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'both',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'India',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_index` (`user_id`),
  CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.addresses: ~1 rows (approximately)
DELETE FROM `addresses`;
INSERT INTO `addresses` (`id`, `user_id`, `type`, `name`, `phone`, `company_name`, `address_line_1`, `address_line_2`, `city`, `state`, `postal_code`, `country`, `is_default`, `created_at`, `updated_at`) VALUES
	(1, 3, 'both', 'a', 'a', NULL, 'a', 'a', 'a', 'a', 'a', 'a', 0, '2026-04-30 04:13:38', '2026-04-30 04:13:38'),
	(2, 3, 'both', 'b', 'b', NULL, 'b', 'b', 'b', 'b', 'b', 'India', 0, '2026-04-30 17:26:07', '2026-04-30 17:26:07'),
	(3, 2, 'both', 'এক্স', 'এক্স', NULL, 'এক্স', 'এক্স', 'এক্স', 'এক্স', 'এক্স', 'এক্স', 0, '2026-05-14 07:09:59', '2026-05-14 07:09:59');

-- Dumping structure for table fl_0001.app_settings
DROP TABLE IF EXISTS `app_settings`;
CREATE TABLE IF NOT EXISTS `app_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `store_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_registration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `items_per_page` int NOT NULL DEFAULT '20',
  `enable_notifications` tinyint(1) NOT NULL DEFAULT '1',
  `enable_api` tinyint(1) NOT NULL DEFAULT '0',
  `footer_contact_description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.app_settings: ~1 rows (approximately)
DELETE FROM `app_settings`;
INSERT INTO `app_settings` (`id`, `store_name`, `store_email`, `store_phone`, `store_address`, `store_city`, `store_state`, `store_zip`, `store_country`, `business_registration`, `tax_id`, `currency`, `timezone`, `items_per_page`, `enable_notifications`, `enable_api`, `footer_contact_description`, `created_at`, `updated_at`) VALUES
	(1, 'Ship Spare Parts', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'USD', NULL, 20, 1, 0, 'Your trusted provider of maritime spare parts and equipment.', '2026-05-16 12:14:35', '2026-05-16 12:14:35');

-- Dumping structure for table fl_0001.audit_logs
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint unsigned DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_admin_id_created_at_index` (`admin_id`,`created_at`),
  KEY `audit_logs_action_created_at_index` (`action`,`created_at`),
  CONSTRAINT `audit_logs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.audit_logs: ~0 rows (approximately)
DELETE FROM `audit_logs`;

-- Dumping structure for table fl_0001.brands
DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_name_unique` (`name`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.brands: ~5 rows (approximately)
DELETE FROM `brands`;
INSERT INTO `brands` (`id`, `name`, `slug`, `description`, `logo_url`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Caterpillar', 'caterpillar', 'Leading manufacturer of heavy machinery', NULL, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(2, 'Volvo', 'volvo', 'Premium heavy equipment brand', NULL, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(3, 'Komatsu', 'komatsu', 'Japanese construction equipment leader', NULL, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(4, 'John Deere', 'john-deere', 'Trusted agricultural equipment brand', NULL, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(5, 'Hitachi', 'hitachi', 'Global equipment manufacturer', NULL, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52');

-- Dumping structure for table fl_0001.cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.cache: ~0 rows (approximately)
DELETE FROM `cache`;

-- Dumping structure for table fl_0001.cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table fl_0001.cart_items
DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cart_items_cart_id_product_id_unique` (`cart_id`,`product_id`),
  KEY `cart_items_product_id_foreign` (`product_id`),
  KEY `cart_items_cart_id_product_id_index` (`cart_id`,`product_id`),
  CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.cart_items: ~9 rows (approximately)
DELETE FROM `cart_items`;
INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`, `price`, `subtotal`, `created_at`, `updated_at`) VALUES
	(1, 5, 1, 1, 85000.00, 85000.00, '2026-04-29 11:43:58', '2026-04-29 11:43:58'),
	(2, 9, 1, 1, 85000.00, 85000.00, '2026-04-29 11:45:44', '2026-04-29 11:45:44'),
	(3, 11, 1, 2, 85000.00, 170000.00, '2026-04-29 11:46:09', '2026-04-29 11:46:44'),
	(4, 17, 2, 1, 12500.00, 12500.00, '2026-04-30 03:35:16', '2026-04-30 03:35:16'),
	(5, 18, 3, 1, 250000.00, 250000.00, '2026-04-30 03:35:52', '2026-04-30 03:35:52'),
	(6, 21, 2, 1, 12500.00, 12500.00, '2026-04-30 03:36:25', '2026-04-30 03:36:25'),
	(7, 23, 2, 1, 12500.00, 12500.00, '2026-04-30 03:38:28', '2026-04-30 03:38:28'),
	(12, 28, 2, 1, 12500.00, 12500.00, '2026-05-14 07:14:13', '2026-05-14 07:14:13'),
	(13, 28, 4, 1, 45000.00, 45000.00, '2026-05-14 07:14:29', '2026-05-14 07:14:29');

-- Dumping structure for table fl_0001.carts
DROP TABLE IF EXISTS `carts`;
CREATE TABLE IF NOT EXISTS `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_index` (`user_id`),
  KEY `carts_session_id_index` (`session_id`),
  KEY `carts_expires_at_index` (`expires_at`),
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.carts: ~26 rows (approximately)
DELETE FROM `carts`;
INSERT INTO `carts` (`id`, `user_id`, `session_id`, `subtotal`, `tax`, `total`, `expires_at`, `created_at`, `updated_at`) VALUES
	(1, 3, 'QAIIpEqDnRvkFyJt1NjAGjGIHj8ikkpRvqwBembJ', 0.00, 0.00, 0.00, '2026-05-06 04:07:48', '2026-04-29 04:07:48', '2026-04-30 17:26:07'),
	(2, NULL, 'Jg0Fnd23C9ubX18dfRfP4fsO1ysKKPBzBwspAm8i', 0.00, 0.00, 0.00, '2026-05-06 11:33:58', '2026-04-29 11:33:58', '2026-04-29 11:33:58'),
	(3, NULL, 'u8krkMEORpFbzzyh3ewW3rljjHiOPQYF1z8RU91m', 0.00, 0.00, 0.00, '2026-05-06 11:42:45', '2026-04-29 11:42:45', '2026-04-29 11:42:45'),
	(4, NULL, 'b4ZEuusEy5wrK9PjhsjkbsYAhCgUSJFzqXh7OPVB', 0.00, 0.00, 0.00, '2026-05-06 11:43:31', '2026-04-29 11:43:31', '2026-04-29 11:43:31'),
	(5, NULL, 'mkJmYKoI9QnyN4cZXeNSAXAodlZWbdfLxK9eEtIC', 85000.00, 15300.00, 100300.00, '2026-05-06 11:43:58', '2026-04-29 11:43:58', '2026-04-29 11:43:58'),
	(6, NULL, '9SLoWEHl7r1TqTLKMyhMVIw1uPrflsCIwyvGa6qX', 0.00, 0.00, 0.00, '2026-05-06 11:44:46', '2026-04-29 11:44:46', '2026-04-29 11:44:46'),
	(7, NULL, '4SIqVji2CXRd6l1ixYMUBG2TUMbUYYEZXrUTXm7w', 0.00, 0.00, 0.00, '2026-05-06 11:45:23', '2026-04-29 11:45:23', '2026-04-29 11:45:23'),
	(8, NULL, 'Qrd0jMJzS61eYaO0YycQTY7WXL21qtsYi8Hj2zRz', 0.00, 0.00, 0.00, '2026-05-06 11:45:33', '2026-04-29 11:45:33', '2026-04-29 11:45:33'),
	(9, NULL, 'XrVMQ6AyeXPo16POqFTouUpLasfVdDZviGcnH7xq', 85000.00, 15300.00, 100300.00, '2026-05-06 11:45:39', '2026-04-29 11:45:39', '2026-04-29 11:45:44'),
	(10, NULL, 'jVz5S3XSFD9oCphjRijX9U8qBNmIBM81cFRt9ofs', 0.00, 0.00, 0.00, '2026-05-06 11:45:55', '2026-04-29 11:45:55', '2026-04-29 11:45:55'),
	(11, NULL, 'LUmCNfCKz1ekksQxXQlCAcKUNPtLIUJN4EFjE2Vw', 170000.00, 30600.00, 200600.00, '2026-05-06 11:45:59', '2026-04-29 11:45:59', '2026-04-29 11:46:44'),
	(12, NULL, '5texAdHLLhEpwe6V8mMcGCaCpuciUnjgKwbAWG9J', 0.00, 0.00, 0.00, '2026-05-06 11:46:54', '2026-04-29 11:46:54', '2026-04-29 11:46:54'),
	(13, NULL, 'B8Dj2ooAPCJf1PyM2xK7SwI0RsqbHVtdAUkE3R0w', 0.00, 0.00, 0.00, '2026-05-06 11:46:55', '2026-04-29 11:46:55', '2026-04-29 11:46:55'),
	(14, NULL, 'LnrQo5XQQQiVDZEr6pGxkdYPovRrGCTH7qvJpEwn', 0.00, 0.00, 0.00, '2026-05-07 00:50:20', '2026-04-30 00:50:20', '2026-04-30 00:50:20'),
	(15, NULL, '6RdCuo95SbMEOM5gBiqqiObpwSrLfdN3DwS8Toxd', 0.00, 0.00, 0.00, '2026-05-07 00:50:23', '2026-04-30 00:50:23', '2026-04-30 00:50:23'),
	(16, NULL, '0qjnK0JBmdQtrWP5GXuHLJ1PFg18CdxsD61Jdr4V', 0.00, 0.00, 0.00, '2026-05-07 00:50:30', '2026-04-30 00:50:30', '2026-04-30 00:50:30'),
	(17, NULL, 'vU6o6nRuMFDgFS7P3Q3Pd2WYYBJj7OHUc1wumpmq', 12500.00, 2250.00, 14750.00, '2026-05-07 03:35:16', '2026-04-30 03:35:16', '2026-04-30 03:35:16'),
	(18, NULL, 'cgIwS8V0112aAKqXOqrTUwumY0BJhf46519T5PsO', 250000.00, 45000.00, 295000.00, '2026-05-07 03:35:49', '2026-04-30 03:35:49', '2026-04-30 03:35:53'),
	(19, NULL, 'gAeHhuN8L0JFYru4UlaUqaqEssUgEL8yUXNLn9k5', 0.00, 0.00, 0.00, '2026-05-07 03:35:59', '2026-04-30 03:35:59', '2026-04-30 03:35:59'),
	(20, NULL, 'ftc34ehSGeabMo4EWyzzemYEX63ugJANYx2Frm2i', 0.00, 0.00, 0.00, '2026-05-07 03:36:01', '2026-04-30 03:36:01', '2026-04-30 03:36:01'),
	(21, NULL, 'BGFzO0hkGEDpigIkdQ5kuaU58QEIcSHmVApByT4L', 12500.00, 2250.00, 14750.00, '2026-05-07 03:36:21', '2026-04-30 03:36:21', '2026-04-30 03:36:25'),
	(22, NULL, 'VoaU8J72bEyjJIBikgF9ktFjjIQBAnKPZhZihCTy', 0.00, 0.00, 0.00, '2026-05-07 03:36:49', '2026-04-30 03:36:49', '2026-04-30 03:36:49'),
	(23, NULL, '1cyaXYhi7ArN5odkuwOoFnAbIYetQYwD3kKVzBEY', 12500.00, 2250.00, 14750.00, '2026-05-07 03:38:28', '2026-04-30 03:38:28', '2026-04-30 03:38:28'),
	(24, NULL, 'px7Ao5mzGlOE4sxWKzQgaxNEQdSNCAi2z9JGN9oU', 0.00, 0.00, 0.00, '2026-05-07 09:18:29', '2026-04-30 09:18:29', '2026-04-30 09:18:29'),
	(25, 2, 'sOcPCgAKgdjs3WNXhE6lA7HYJkPK28MCBjClMNoO', 0.00, 0.00, 0.00, '2026-05-07 09:18:55', '2026-04-30 09:18:55', '2026-04-30 09:18:55'),
	(26, NULL, 'c2IcLK3lIyxUlhkcjfAOTubocCXQCENPlgUimRNs', 0.00, 0.00, 0.00, '2026-05-07 17:23:06', '2026-04-30 17:23:06', '2026-04-30 17:23:06'),
	(27, NULL, 'Kq9dawq9Yb8b1X1IOc78att7UN9tYvDqNXlDHLKU', 0.00, 0.00, 0.00, '2026-05-07 22:46:57', '2026-04-30 22:46:57', '2026-04-30 22:46:57'),
	(28, 2, 'KNFMwMFGb8MiI1Few1UHIW1mRfaLZihDTHGDlTxX', 57500.00, 10350.00, 67850.00, '2026-05-21 06:50:50', '2026-05-14 06:50:50', '2026-05-14 07:14:29');

-- Dumping structure for table fl_0001.categories
DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_index` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.categories: ~5 rows (approximately)
DELETE FROM `categories`;
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image_url`, `parent_id`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Engine Parts', 'engine-parts', 'Engine components and accessories', NULL, NULL, 0, 1, '2026-04-27 02:00:52', '2026-04-27 16:11:53'),
	(2, 'Hydraulic Components', 'hydraulic-components', 'Hydraulic systems and parts', NULL, NULL, 0, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(3, 'Transmission Parts', 'transmission-parts', 'Transmission and gearbox parts', NULL, NULL, 0, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(4, 'Electrical Components', 'electrical-components', 'Electrical systems and components', NULL, NULL, 0, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(5, 'Undercarriage Parts', 'undercarriage-parts', 'Track and undercarriage parts', NULL, NULL, 0, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52');

-- Dumping structure for table fl_0001.chatbot_conversations
DROP TABLE IF EXISTS `chatbot_conversations`;
CREATE TABLE IF NOT EXISTS `chatbot_conversations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `messages` json NOT NULL COMMENT 'Array of messages with role (user/bot) and content',
  `status` enum('active','closed','escalated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `escalated_to_support` tinyint(1) NOT NULL DEFAULT '0',
  `support_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `escalation_reason` text COLLATE utf8mb4_unicode_ci,
  `escalated_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chatbot_conversations_user_id_index` (`user_id`),
  KEY `chatbot_conversations_status_index` (`status`),
  KEY `chatbot_conversations_session_id_index` (`session_id`),
  CONSTRAINT `chatbot_conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.chatbot_conversations: ~8 rows (approximately)
DELETE FROM `chatbot_conversations`;
INSERT INTO `chatbot_conversations` (`id`, `user_id`, `session_id`, `messages`, `status`, `escalated_to_support`, `support_email`, `escalation_reason`, `escalated_at`, `closed_at`, `created_at`, `updated_at`) VALUES
	(1, NULL, '67344a75-c6ba-4ce8-8fa0-cadaeb69fbfd', '[{"role": "user", "content": "What is your shipping policy?", "timestamp": "2026-04-29T08:52:56+00:00"}, {"role": "bot", "content": "We ship all orders within 2-3 business days. Free shipping on orders above ₹50,000.", "timestamp": "2026-04-29T08:52:56+00:00"}]', 'active', 0, NULL, NULL, NULL, NULL, '2026-04-29 03:22:56', '2026-04-29 03:22:56'),
	(2, NULL, '6a8bedcd-c197-4ec0-9a79-7141d1dfbe29', '[{"role": "user", "content": "Hello", "timestamp": "2026-04-29T09:17:36+00:00"}, {"role": "bot", "content": "Your question is important to us. Let me transfer you to a specialist who can assist you.", "timestamp": "2026-04-29T09:17:36+00:00"}]', 'active', 0, NULL, NULL, NULL, NULL, '2026-04-29 03:47:36', '2026-04-29 03:47:36'),
	(3, NULL, '4b8df745-1bea-4a8e-a2e7-26077254a9b5', '[{"role": "user", "content": "shipping policy", "timestamp": "2026-04-29T09:26:52+00:00"}, {"role": "bot", "content": "Your question is important to us. Let me transfer you to a specialist who can assist you.", "timestamp": "2026-04-29T09:26:52+00:00"}]', 'active', 0, NULL, NULL, NULL, NULL, '2026-04-29 03:56:52', '2026-04-29 03:56:52'),
	(4, NULL, '10f1d76d-6251-49c4-94ec-6b3fcf17d3d0', '[{"role": "user", "content": "shipping", "timestamp": "2026-04-29T09:27:05+00:00"}, {"role": "bot", "content": "Thank you for your question. I\'ll connect you with our support team who can provide more detailed assistance.", "timestamp": "2026-04-29T09:27:05+00:00"}]', 'active', 0, NULL, NULL, NULL, NULL, '2026-04-29 03:57:05', '2026-04-29 03:57:05'),
	(5, NULL, '1db1eca3-b311-45d8-bab7-41e8cf907cf8', '[{"role": "user", "content": "Order status", "timestamp": "2026-04-29T09:27:32+00:00"}, {"role": "bot", "content": "Thank you for your question. I\'ll connect you with our support team who can provide more detailed assistance.", "timestamp": "2026-04-29T09:27:32+00:00"}]', 'active', 0, NULL, NULL, NULL, NULL, '2026-04-29 03:57:32', '2026-04-29 03:57:32'),
	(6, NULL, 'f5229951-dd63-46da-9d2d-d3f0bd8ab8cf', '[{"role": "user", "content": "Help", "timestamp": "2026-04-29T09:27:37+00:00"}, {"role": "bot", "content": "I\'m not sure I understand your question completely. Our support team would be happy to help you better.", "timestamp": "2026-04-29T09:27:37+00:00"}]', 'active', 0, NULL, NULL, NULL, NULL, '2026-04-29 03:57:37', '2026-04-29 03:57:37'),
	(7, NULL, '098f538b-d331-4975-b6b9-5cb030ebc6ed', '[{"role": "user", "content": "Support", "timestamp": "2026-04-29T09:27:42+00:00"}, {"role": "bot", "content": "Your question is important to us. Let me transfer you to a specialist who can assist you.", "timestamp": "2026-04-29T09:27:42+00:00"}]', 'active', 0, NULL, NULL, NULL, NULL, '2026-04-29 03:57:42', '2026-04-29 03:57:42'),
	(8, NULL, '15f420d9-025e-4483-9370-0c0259f03aed', '[{"role": "user", "content": "Order status", "timestamp": "2026-04-29T09:28:04+00:00"}, {"role": "bot", "content": "Thank you for your question. I\'ll connect you with our support team who can provide more detailed assistance.", "timestamp": "2026-04-29T09:28:04+00:00"}]', 'active', 0, NULL, NULL, NULL, NULL, '2026-04-29 03:58:04', '2026-04-29 03:58:04'),
	(9, NULL, '3e9f903b-1e58-4c17-9950-9c6fe8cdf7a2', '[{"role": "user", "content": "hi", "timestamp": "2026-04-30T19:12:17+00:00"}, {"role": "bot", "content": "I\'m not sure I understand your question completely. Our support team would be happy to help you better.", "timestamp": "2026-04-30T19:12:17+00:00"}]', 'active', 0, NULL, NULL, NULL, NULL, '2026-04-30 13:42:17', '2026-04-30 13:42:17');

-- Dumping structure for table fl_0001.chatbot_faqs
DROP TABLE IF EXISTS `chatbot_faqs`;
CREATE TABLE IF NOT EXISTS `chatbot_faqs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Comma-separated keywords for matching',
  `display_order` int NOT NULL DEFAULT '0',
  `view_count` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chatbot_faqs_category_index` (`category`),
  FULLTEXT KEY `chatbot_faqs_question_answer_keywords_fulltext` (`question`,`answer`,`keywords`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.chatbot_faqs: ~5 rows (approximately)
DELETE FROM `chatbot_faqs`;
INSERT INTO `chatbot_faqs` (`id`, `question`, `answer`, `category`, `keywords`, `display_order`, `view_count`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'What is your shipping policy?', 'We ship all orders within 2-3 business days. Free shipping on orders above ₹50,000.', 'Shipping', NULL, 0, 0, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(2, 'Do you offer bulk discounts?', 'Yes! We offer 10-15% discounts on bulk orders. Contact our sales team for details.', 'Pricing', NULL, 0, 1, 1, '2026-04-27 02:00:52', '2026-04-28 13:10:41'),
	(3, 'How long do spare parts typically last?', 'Our genuine parts are designed to last as long as OEM parts, typically 3-5 years depending on usage.', 'Products', NULL, 0, 0, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(4, 'Can I return a part if it doesn\'t fit?', 'Yes, we offer 30-day returns on all products. The item must be unused and in original packaging.', 'Returns', NULL, 0, 0, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(5, 'What payment methods do you accept?', 'We accept bank transfers, UPI, credit/debit cards, and corporate checks.', 'Payment', NULL, 0, 0, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52');

-- Dumping structure for table fl_0001.failed_jobs
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

-- Dumping data for table fl_0001.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table fl_0001.inventories
DROP TABLE IF EXISTS `inventories`;
CREATE TABLE IF NOT EXISTS `inventories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `warehouse_qty` int NOT NULL DEFAULT '0',
  `reserved_qty` int NOT NULL DEFAULT '0',
  `available_qty` int NOT NULL DEFAULT '0',
  `last_restock_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventories_product_id_unique` (`product_id`),
  KEY `inventories_available_qty_index` (`available_qty`),
  CONSTRAINT `inventories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.inventories: ~10 rows (approximately)
DELETE FROM `inventories`;
INSERT INTO `inventories` (`id`, `product_id`, `warehouse_qty`, `reserved_qty`, `available_qty`, `last_restock_at`, `created_at`, `updated_at`) VALUES
	(1, 1, 63, 10, 53, NULL, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(2, 2, 80, 4, 76, NULL, '2026-04-27 02:00:52', '2026-05-14 07:15:40'),
	(3, 3, 80, 0, 80, NULL, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(4, 4, 57, 10, 47, NULL, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(5, 5, 40, 4, 36, NULL, '2026-04-27 02:00:52', '2026-04-30 04:13:38'),
	(6, 6, 61, 7, 54, NULL, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(7, 7, 70, 1, 69, NULL, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(8, 8, 56, 4, 52, NULL, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(9, 9, 63, 2, 61, NULL, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(10, 10, 91, 4, 87, NULL, '2026-04-27 02:00:52', '2026-04-27 02:00:52');

-- Dumping structure for table fl_0001.job_batches
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

-- Dumping data for table fl_0001.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table fl_0001.jobs
DROP TABLE IF EXISTS `jobs`;
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

-- Dumping data for table fl_0001.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table fl_0001.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.migrations: ~28 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_04_25_000001_create_brands_table', 1),
	(5, '2026_04_25_000002_create_categories_table', 1),
	(6, '2026_04_25_000003_create_vessel_types_table', 1),
	(7, '2026_04_25_000004_create_products_table', 1),
	(8, '2026_04_25_000005_create_inventory_table', 1),
	(9, '2026_04_25_000006_create_addresses_table', 1),
	(10, '2026_04_25_000007_create_carts_table', 1),
	(11, '2026_04_25_000008_create_cart_items_table', 1),
	(12, '2026_04_25_000009_create_orders_table', 1),
	(13, '2026_04_25_000010_create_order_items_table', 1),
	(14, '2026_04_25_000011_create_payments_table', 1),
	(15, '2026_04_25_000012_create_chatbot_faqs_table', 1),
	(16, '2026_04_25_000013_create_chatbot_conversations_table', 1),
	(17, '2026_04_25_000014_create_shipments_table', 1),
	(18, '2026_04_25_000015_create_audit_logs_table', 1),
	(20, '2026_04_28_000001_create_pages_table', 2),
	(21, '2026_04_29_170221_create_personal_access_tokens_table', 3),
	(22, '2026_04_30_000100_add_stripe_paypal_to_payment_enums', 4),
	(23, '2026_05_01_000001_create_stripe_settings_table', 5),
	(24, '2026_05_01_000002_create_stripe_refunds_table', 5),
	(25, '2026_05_01_000003_create_paypal_settings_table', 6),
	(26, '2026_05_01_000004_create_paypal_ipns_table', 7),
	(27, '2026_05_01_000005_create_payment_methods_table', 8),
	(28, '2026_05_01_000001_create_paypal_settings_table', 9),
	(29, '2026_05_01_000002_create_paypal_ipn_logs_table', 10),
	(30, '2026_05_16_000001_create_app_settings_table', 10);

-- Dumping structure for table fl_0001.order_items
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_order_id_index` (`order_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.order_items: ~3 rows (approximately)
DELETE FROM `order_items`;
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_sku`, `product_name`, `quantity`, `unit_price`, `subtotal`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 'MFF-002', 'Marine Fuel Filter', 1, 12500.00, 12500.00, '2026-04-30 04:13:38', '2026-04-30 04:13:38'),
	(2, 1, 5, 'TLA-005', 'Track Link Assembly', 1, 15000.00, 15000.00, '2026-04-30 04:13:38', '2026-04-30 04:13:38'),
	(3, 2, 2, 'MFF-002', 'Marine Fuel Filter', 1, 12500.00, 12500.00, '2026-04-30 17:26:07', '2026-04-30 17:26:07'),
	(4, 3, 2, 'MFF-002', 'Marine Fuel Filter', 1, 12500.00, 12500.00, '2026-05-14 07:09:59', '2026-05-14 07:09:59');

-- Dumping structure for table fl_0001.orders
DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` enum('pending','approved','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` enum('bank_transfer','upi','credit_card','debit_card','stripe','paypal') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address_id` bigint unsigned DEFAULT NULL,
  `billing_address_id` bigint unsigned DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `shipping` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_shipping_address_id_foreign` (`shipping_address_id`),
  KEY `orders_billing_address_id_foreign` (`billing_address_id`),
  KEY `orders_user_id_index` (`user_id`),
  KEY `orders_status_index` (`status`),
  KEY `orders_payment_status_index` (`payment_status`),
  KEY `orders_created_at_index` (`created_at`),
  CONSTRAINT `orders_billing_address_id_foreign` FOREIGN KEY (`billing_address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_shipping_address_id_foreign` FOREIGN KEY (`shipping_address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.orders: ~3 rows (approximately)
DELETE FROM `orders`;
INSERT INTO `orders` (`id`, `order_number`, `user_id`, `status`, `payment_status`, `payment_method`, `shipping_address_id`, `billing_address_id`, `subtotal`, `tax`, `shipping`, `discount`, `total`, `notes`, `admin_notes`, `confirmed_at`, `shipped_at`, `delivered_at`, `created_at`, `updated_at`) VALUES
	(1, 'ORD2026043000001', 3, 'pending', 'pending', NULL, 1, 1, 27500.00, 4950.00, 0.00, 0.00, 32450.00, NULL, NULL, NULL, NULL, NULL, '2026-04-30 04:13:38', '2026-04-30 04:13:38'),
	(2, 'ORD2026043000002', 3, 'pending', 'pending', NULL, 2, 2, 12500.00, 2250.00, 0.00, 0.00, 14750.00, NULL, NULL, NULL, NULL, NULL, '2026-04-30 17:26:07', '2026-04-30 17:26:07'),
	(3, 'ORD2026051400001', 2, 'cancelled', 'pending', NULL, 3, 3, 12500.00, 2250.00, 0.00, 0.00, 14750.00, '\n[2026-05-14 12:45:40] Cancelled: Cancelled by admin', NULL, NULL, NULL, NULL, '2026-05-14 07:09:59', '2026-05-14 07:15:40');

-- Dumping structure for table fl_0001.pages
DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_title_unique` (`title`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.pages: ~2 rows (approximately)
DELETE FROM `pages`;
INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `meta_description`, `is_active`, `created_at`, `updated_at`) VALUES
	(3, 'About Us', 'about-us', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam finibus posuere nibh eu congue. Donec eu tincidunt nulla, vitae dignissim lectus. Etiam volutpat condimentum enim id semper. Suspendisse eros orci, malesuada vitae odio nec, euismod elementum ante. Nunc neque velit, scelerisque eu neque sed, posuere bibendum neque. Etiam venenatis odio a odio blandit facilisis. Fusce vestibulum vestibulum augue, eu ullamcorper sapien porta ut. Phasellus ultrices tristique tellus ac dapibus. Suspendisse aliquam tristique rutrum. Praesent id augue id risus auctor cursus. Phasellus ultricies rutrum arcu, vel varius elit gravida commodo. Duis lobortis mauris lacinia luctus rhoncus. Maecenas libero urna, lobortis ut mauris sed, pulvinar pharetra tellus. Cras nisi sapien, varius et gravida ac, rhoncus ut purus. Sed sit amet ultrices nulla. Donec id libero eros.</p>\r\n\r\n<p>Aliquam cursus felis sed metus fringilla ornare. Suspendisse vulputate, turpis dictum pretium sodales, odio quam viverra purus, nec fermentum nibh metus eget purus. Nulla sed ornare nisi. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque in est eget odio ultrices dapibus. Aenean et facilisis massa. In facilisis lacus porta tortor venenatis blandit. Aenean in fermentum diam. Pellentesque eu ante convallis, pulvinar nisl sollicitudin, blandit elit. Donec semper sed nisi id dignissim. Aenean a convallis dui.</p>\r\n\r\n<p>Suspendisse imperdiet erat est, sed pellentesque lacus fringilla in. Nulla placerat mattis tristique. Aliquam et pellentesque orci. Sed lectus erat, fermentum vitae ultricies a, ullamcorper vel justo. Nunc nec rutrum sem. Vestibulum vestibulum commodo orci a pretium. Mauris rutrum libero odio, quis efficitur nulla auctor sed. Praesent commodo vulputate auctor. Nulla convallis, ante at mattis posuere, turpis lectus suscipit magna, in blandit justo nisl id enim. Cras tempus consequat nunc, in lobortis nulla pulvinar quis. Sed et tortor fringilla, ultricies ipsum sit amet, consectetur lorem. Sed ornare elit mollis eros laoreet pretium. Praesent at orci nisl. Aliquam molestie quam metus, a eleifend nunc euismod vel.</p>\r\n\r\n<p>Nam non neque felis. Praesent a massa nec tellus tincidunt aliquet ac sed ex. Maecenas egestas quam a nunc aliquam cursus. Donec tincidunt cursus mollis. Pellentesque vel fermentum nisi. Integer tincidunt ornare eros. Aliquam erat volutpat. In hac habitasse platea dictumst. Nullam diam ex, hendrerit a lacinia et, mollis vel lectus. Maecenas et pellentesque elit. Quisque vestibulum augue vel velit placerat congue. Suspendisse ac interdum nulla. Nulla massa nibh, convallis vel orci eget, lobortis mollis sem. Fusce ullamcorper feugiat dui, ut malesuada orci. Proin id dignissim eros. Donec lectus purus, hendrerit quis nulla in, pulvinar fringilla nisi.</p>\r\n\r\n<p>Vivamus non leo id dui pharetra bibendum et et lacus. Praesent facilisis vestibulum erat, eget ullamcorper erat venenatis in. Sed vitae pulvinar nunc, quis luctus nisi. Vivamus congue pharetra pharetra. Nulla sed nisi magna. Cras feugiat eros ultrices lectus fringilla consectetur. Maecenas in risus at metus tempus elementum at in dui. Aenean tortor est, pellentesque a nulla sit amet, tincidunt condimentum quam.</p>', 'About Us', 1, '2026-04-27 14:53:52', '2026-04-27 14:53:52'),
	(4, 'Privacy Policy', 'privacy-policy', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam finibus posuere nibh eu congue. Donec eu tincidunt nulla, vitae dignissim lectus. Etiam volutpat condimentum enim id semper. Suspendisse eros orci, malesuada vitae odio nec, euismod elementum ante. Nunc neque velit, scelerisque eu neque sed, posuere bibendum neque. Etiam venenatis odio a odio blandit facilisis. Fusce vestibulum vestibulum augue, eu ullamcorper sapien porta ut. Phasellus ultrices tristique tellus ac dapibus. Suspendisse aliquam tristique rutrum. Praesent id augue id risus auctor cursus. Phasellus ultricies rutrum arcu, vel varius elit gravida commodo. Duis lobortis mauris lacinia luctus rhoncus. Maecenas libero urna, lobortis ut mauris sed, pulvinar pharetra tellus. Cras nisi sapien, varius et gravida ac, rhoncus ut purus. Sed sit amet ultrices nulla. Donec id libero eros.</p>\r\n\r\n<p>Aliquam cursus felis sed metus fringilla ornare. Suspendisse vulputate, turpis dictum pretium sodales, odio quam viverra purus, nec fermentum nibh metus eget purus. Nulla sed ornare nisi. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque in est eget odio ultrices dapibus. Aenean et facilisis massa. In facilisis lacus porta tortor venenatis blandit. Aenean in fermentum diam. Pellentesque eu ante convallis, pulvinar nisl sollicitudin, blandit elit. Donec semper sed nisi id dignissim. Aenean a convallis dui.</p>\r\n\r\n<p>Suspendisse imperdiet erat est, sed pellentesque lacus fringilla in. Nulla placerat mattis tristique. Aliquam et pellentesque orci. Sed lectus erat, fermentum vitae ultricies a, ullamcorper vel justo. Nunc nec rutrum sem. Vestibulum vestibulum commodo orci a pretium. Mauris rutrum libero odio, quis efficitur nulla auctor sed. Praesent commodo vulputate auctor. Nulla convallis, ante at mattis posuere, turpis lectus suscipit magna, in blandit justo nisl id enim. Cras tempus consequat nunc, in lobortis nulla pulvinar quis. Sed et tortor fringilla, ultricies ipsum sit amet, consectetur lorem. Sed ornare elit mollis eros laoreet pretium. Praesent at orci nisl. Aliquam molestie quam metus, a eleifend nunc euismod vel.</p>\r\n\r\n<p>Nam non neque felis. Praesent a massa nec tellus tincidunt aliquet ac sed ex. Maecenas egestas quam a nunc aliquam cursus. Donec tincidunt cursus mollis. Pellentesque vel fermentum nisi. Integer tincidunt ornare eros. Aliquam erat volutpat. In hac habitasse platea dictumst. Nullam diam ex, hendrerit a lacinia et, mollis vel lectus. Maecenas et pellentesque elit. Quisque vestibulum augue vel velit placerat congue. Suspendisse ac interdum nulla. Nulla massa nibh, convallis vel orci eget, lobortis mollis sem. Fusce ullamcorper feugiat dui, ut malesuada orci. Proin id dignissim eros. Donec lectus purus, hendrerit quis nulla in, pulvinar fringilla nisi.</p>\r\n\r\n<p>Vivamus non leo id dui pharetra bibendum et et lacus. Praesent facilisis vestibulum erat, eget ullamcorper erat venenatis in. Sed vitae pulvinar nunc, quis luctus nisi. Vivamus congue pharetra pharetra. Nulla sed nisi magna. Cras feugiat eros ultrices lectus fringilla consectetur. Maecenas in risus at metus tempus elementum at in dui. Aenean tortor est, pellentesque a nulla sit amet, tincidunt condimentum quam.</p>', 'Privacy', 1, '2026-04-27 14:54:17', '2026-04-27 14:54:17');

-- Dumping structure for table fl_0001.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table fl_0001.payment_methods
DROP TABLE IF EXISTS `payment_methods`;
CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique identifier (stripe, paypal, card, bank_transfer)',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Display name',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Description for admin',
  `type` enum('card','wallet','bank','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'card' COMMENT 'Payment method type',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is payment method active',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is this the default method',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT 'Display order',
  `minimum_amount` decimal(12,2) DEFAULT NULL COMMENT 'Minimum transaction amount',
  `maximum_amount` decimal(12,2) DEFAULT NULL COMMENT 'Maximum transaction amount',
  `settings` json DEFAULT NULL COMMENT 'Method-specific settings',
  `icon_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Font Awesome icon class',
  `controller_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Controller class path',
  `service_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Service class path',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_methods_key_unique` (`key`),
  KEY `payment_methods_is_enabled_index` (`is_enabled`),
  KEY `payment_methods_sort_order_index` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.payment_methods: ~4 rows (approximately)
DELETE FROM `payment_methods`;
INSERT INTO `payment_methods` (`id`, `key`, `name`, `description`, `type`, `is_enabled`, `is_default`, `sort_order`, `minimum_amount`, `maximum_amount`, `settings`, `icon_class`, `controller_path`, `service_path`, `created_at`, `updated_at`) VALUES
	(1, 'stripe', 'Credit/Debit Card (Stripe)', 'Accept payments via Stripe with credit and debit cards', 'card', 1, 1, 1, NULL, NULL, NULL, 'fas fa-credit-card', 'App\\Http\\Controllers\\Frontend\\CardPaymentController', 'App\\Services\\StripeService', '2026-05-01 03:25:25', '2026-05-01 03:25:25'),
	(2, 'paypal', 'PayPal', 'Accept payments via PayPal checkout', 'wallet', 1, 0, 2, NULL, NULL, NULL, 'fab fa-paypal', 'App\\Http\\Controllers\\Frontend\\PayPalController', 'App\\Services\\PayPalService', '2026-05-01 03:25:25', '2026-05-01 10:31:06'),
	(3, 'card', 'Card Payment (Direct)', 'Accept card details directly from checkout form', 'card', 0, 0, 3, NULL, NULL, NULL, 'fas fa-credit-card', 'App\\Http\\Controllers\\Frontend\\CardPaymentController', 'App\\Services\\CardPaymentService', '2026-05-01 03:25:25', '2026-05-01 03:25:25'),
	(4, 'bank_transfer', 'Bank Transfer', 'Accept payments via manual bank transfer with proof upload', 'bank', 0, 0, 4, NULL, NULL, NULL, 'fas fa-university', NULL, NULL, '2026-05-01 03:25:25', '2026-05-01 03:25:25');

-- Dumping structure for table fl_0001.payments
DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `method` enum('bank_transfer','upi','credit_card','debit_card','stripe','paypal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `amount` decimal(12,2) NOT NULL,
  `transaction_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_response` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `proof_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'For bank transfer proofs',
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_order_id_transaction_reference_unique` (`order_id`,`transaction_reference`),
  KEY `payments_order_id_status_index` (`order_id`,`status`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.payments: ~4 rows (approximately)
DELETE FROM `payments`;
INSERT INTO `payments` (`id`, `order_id`, `method`, `status`, `amount`, `transaction_reference`, `gateway_response`, `metadata`, `proof_file_path`, `verified_at`, `created_at`, `updated_at`) VALUES
	(1, 1, 'upi', 'pending', 32450.00, NULL, NULL, NULL, NULL, NULL, '2026-04-30 17:38:20', '2026-04-30 17:38:20'),
	(2, 1, 'paypal', 'pending', 32450.00, 'PAYPAL-54F33B380652', NULL, '{"provider": "paypal", "checkout_token": "PAYPAL-54F33B380652"}', NULL, NULL, '2026-04-30 17:38:45', '2026-04-30 17:38:45'),
	(3, 2, 'paypal', 'pending', 14750.00, 'PAYPAL-C669B830F1C3', NULL, '{"provider": "paypal", "checkout_token": "PAYPAL-C669B830F1C3"}', NULL, NULL, '2026-04-30 17:39:16', '2026-04-30 17:39:16'),
	(4, 1, 'paypal', 'pending', 32450.00, 'PAYPAL-813F2A2474D6', NULL, '{"provider": "paypal", "checkout_token": "PAYPAL-813F2A2474D6"}', NULL, NULL, '2026-04-30 22:43:34', '2026-04-30 22:43:34'),
	(5, 2, 'paypal', 'pending', 14750.00, 'PAYPAL-0CD9CBD671991B45', NULL, '{"provider": "paypal", "order_number": "ORD2026043000002"}', NULL, NULL, '2026-05-01 10:32:09', '2026-05-01 10:32:09');

-- Dumping structure for table fl_0001.paypal_ipn_logs
DROP TABLE IF EXISTS `paypal_ipn_logs`;
CREATE TABLE IF NOT EXISTS `paypal_ipn_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` bigint unsigned DEFAULT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `txn_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `txn_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mc_gross` decimal(12,2) DEFAULT NULL,
  `mc_currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_status` enum('pending','verified','invalid','duplicate') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `raw_post_data` text COLLATE utf8mb4_unicode_ci,
  `parsed_data` json DEFAULT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT '0',
  `processing_error` text COLLATE utf8mb4_unicode_ci,
  `processed_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paypal_ipn_logs_payment_id_foreign` (`payment_id`),
  KEY `paypal_ipn_logs_order_id_foreign` (`order_id`),
  KEY `paypal_ipn_logs_txn_id_index` (`txn_id`),
  KEY `paypal_ipn_logs_verification_status_processed_index` (`verification_status`,`processed`),
  KEY `paypal_ipn_logs_created_at_index` (`created_at`),
  CONSTRAINT `paypal_ipn_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `paypal_ipn_logs_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.paypal_ipn_logs: ~0 rows (approximately)
DELETE FROM `paypal_ipn_logs`;

-- Dumping structure for table fl_0001.paypal_ipns
DROP TABLE IF EXISTS `paypal_ipns`;
CREATE TABLE IF NOT EXISTS `paypal_ipns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'PayPal transaction ID',
  `parent_txn_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Parent transaction ID for refunds/reversals',
  `txn_type` enum('web_accept','subscr_signup','subscr_payment','subscr_failed','subscr_cancel','subscr_eot','recurring_payment','recurring_payment_profile_created','recurring_payment_suspended_due_to_max_failed_payments','recurring_payment_suspended','recurring_payment_reactivated','new_case','recurring_payment_skipped','recurring_payment_failed','cart','send_money','reversal','adjustment','express_checkout','masspay','virtual_terminal','check_echeck','payer_creation_account','update','mp_signup','merch_pmt','ebay_txn_id','auction_closing','capture','web_accept_refund','refund') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'PayPal transaction type',
  `payment_status` enum('Canceled_Reversal','Completed','Denied','Expired','Failed','Pending','Processed','Refunded','Reversed','Voided') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending' COMMENT 'Payment status from PayPal',
  `order_id` bigint unsigned DEFAULT NULL,
  `payment_id` bigint unsigned DEFAULT NULL,
  `payer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mc_gross` decimal(12,2) DEFAULT NULL COMMENT 'Gross amount',
  `mc_fee` decimal(12,2) DEFAULT NULL COMMENT 'PayPal fee',
  `mc_currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD' COMMENT 'Currency code',
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `custom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Custom field (usually order ID)',
  `invoice` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notify_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verify_sign` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_type` enum('instant','echeck') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `test_ipn` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is this from sandbox',
  `verified` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'IPN signature verified',
  `processed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Payment processed',
  `raw_data` text COLLATE utf8mb4_unicode_ci COMMENT 'Raw POST data from PayPal',
  `error_message` text COLLATE utf8mb4_unicode_ci COMMENT 'Verification error',
  `paypal_timestamp` timestamp NULL DEFAULT NULL COMMENT 'Timestamp from PayPal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `paypal_ipns_txn_id_unique` (`txn_id`),
  KEY `paypal_ipns_txn_id_index` (`txn_id`),
  KEY `paypal_ipns_order_id_index` (`order_id`),
  KEY `paypal_ipns_payment_id_index` (`payment_id`),
  KEY `paypal_ipns_verified_index` (`verified`),
  KEY `paypal_ipns_processed_index` (`processed`),
  KEY `paypal_ipns_created_at_index` (`created_at`),
  CONSTRAINT `paypal_ipns_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `paypal_ipns_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.paypal_ipns: ~0 rows (approximately)
DELETE FROM `paypal_ipns`;

-- Dumping structure for table fl_0001.paypal_settings
DROP TABLE IF EXISTS `paypal_settings`;
CREATE TABLE IF NOT EXISTS `paypal_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `environment` enum('sandbox','live') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sandbox',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `minimum_amount` decimal(10,2) NOT NULL DEFAULT '0.01',
  `maximum_amount` decimal(14,2) NOT NULL DEFAULT '999999.99',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `configured_at` timestamp NULL DEFAULT NULL,
  `configured_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paypal_settings_configured_by_foreign` (`configured_by`),
  CONSTRAINT `paypal_settings_configured_by_foreign` FOREIGN KEY (`configured_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.paypal_settings: ~0 rows (approximately)
DELETE FROM `paypal_settings`;

-- Dumping structure for table fl_0001.personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.personal_access_tokens: ~0 rows (approximately)
DELETE FROM `personal_access_tokens`;

-- Dumping structure for table fl_0001.products
DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `part_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_id` bigint unsigned DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `vessel_type_id` bigint unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `specifications` json DEFAULT NULL COMMENT 'JSON format for flexible specs',
  `price` decimal(12,2) NOT NULL,
  `cost` decimal(12,2) DEFAULT NULL,
  `stock_qty` int NOT NULL DEFAULT '0',
  `weight` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dimensions` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images` json DEFAULT NULL COMMENT 'Array of image URLs',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `view_count` int NOT NULL DEFAULT '0',
  `rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_brand_id_is_active_index` (`brand_id`,`is_active`),
  KEY `products_category_id_is_active_index` (`category_id`,`is_active`),
  KEY `products_vessel_type_id_is_active_index` (`vessel_type_id`,`is_active`),
  KEY `products_part_number_index` (`part_number`),
  FULLTEXT KEY `products_name_description_part_number_fulltext` (`name`,`description`,`part_number`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_vessel_type_id_foreign` FOREIGN KEY (`vessel_type_id`) REFERENCES `vessel_types` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.products: ~10 rows (approximately)
DELETE FROM `products`;
INSERT INTO `products` (`id`, `sku`, `part_number`, `name`, `brand_id`, `category_id`, `vessel_type_id`, `description`, `specifications`, `price`, `cost`, `stock_qty`, `weight`, `dimensions`, `image_url`, `images`, `is_active`, `view_count`, `rating`, `created_at`, `updated_at`) VALUES
	(1, 'ECH-001', 'CAT-ECH-001', 'Engine Cylinder Head', 1, 1, 1, 'Genuine Caterpillar engine cylinder head, compatible with marine engines', '{"test1": "test1", "test2": "test2", "weight": "45kg", "material": "Cast Iron"}', 85000.00, 65000.00, 100, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?w=400\\r\\nhttps://images.unsplash.com/photo-1581092162562-40038f56386e?w=400\\r\\nhttps://images.unsplash.com/photo-1581092149645-c123dba35e04?w=400"]', 1, 6, 0.00, '2026-04-27 02:00:52', '2026-04-29 11:43:50'),
	(2, 'MFF-002', 'VOL-MFF-002', 'Marine Fuel Filter', 2, 1, 2, 'High-performance fuel filter for container ships', '"{\\"capacity\\":\\"50 micron\\",\\"flow_rate\\":\\"100gph\\"}"', 12500.00, 9000.00, -3, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1581092160562-40038f14a55a?w=400", "https://images.unsplash.com/photo-1581092134237-87e58d9e2b7a?w=400", "https://images.unsplash.com/photo-1581092945236-87e58d9e2a9a?w=400"]', 1, 9, 0.00, '2026-04-27 02:00:52', '2026-05-14 07:09:59'),
	(3, 'HPA-003', 'KOM-HPA-003', 'Hydraulic Pump Assembly', 3, 2, 3, 'Complete hydraulic pump assembly for bulk carriers', '"{\\"pressure\\":\\"280 bar\\",\\"displacement\\":\\"40cc\\"}"', 250000.00, 180000.00, 0, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1581092160562-40038f56386b?w=400", "https://images.unsplash.com/photo-1581092134237-87e58d9e2b2a?w=400", "https://images.unsplash.com/photo-1581092145263-87e58d9e2a0a?w=400", "https://images.unsplash.com/photo-1581092160562-40038f56386c?w=400"]', 1, 1, 0.00, '2026-04-27 02:00:52', '2026-04-28 14:17:23'),
	(4, 'ALT-004', 'JD-ALT-004', 'Alternator 100A', 4, 4, 4, 'Marine-grade alternator for tanker vessels', '"{\\"output\\":\\"100A\\",\\"voltage\\":\\"24V\\"}"', 45000.00, 32000.00, 0, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1581092160562-40038f14a55f?w=400", "https://images.unsplash.com/photo-1581092945236-87e58d9e2b3a?w=400", "https://images.unsplash.com/photo-1581092145263-87e58d9e2a1a?w=400"]', 1, 0, 0.00, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(5, 'TLA-005', 'HIT-TLA-005', 'Track Link Assembly', 5, 5, 5, 'Heavy-duty undercarriage track link for excavators', '"{\\"pitch\\":\\"100mm\\",\\"material\\":\\"Steel\\"}"', 15000.00, 10000.00, -1, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1581092160562-40038f56386d?w=400", "https://images.unsplash.com/photo-1581092134237-87e58d9e2b4a?w=400"]', 1, 0, 0.00, '2026-04-27 02:00:52', '2026-04-30 04:13:38'),
	(6, 'TUR-006', 'CAT-TUR-006', 'Turbocharger Assembly', 1, 1, 1, 'Genuine turbo for marine diesel engines', '"{\\"boost\\":\\"2.5 bar\\",\\"type\\":\\"Fixed\\"}"', 180000.00, 125000.00, 0, NULL, NULL, NULL, '"[\\"https:\\\\/\\\\/images.unsplash.com\\\\/photo-1581092960562-40038f14a55e?w=400\\",\\"https:\\\\/\\\\/images.unsplash.com\\\\/photo-1581092945236-87e58d9e2b1a?w=400\\",\\"https:\\\\/\\\\/images.unsplash.com\\\\/photo-1581092145263-87e58d9e2a0a?w=400\\"]"', 1, 1, 0.00, '2026-04-27 02:00:52', '2026-04-28 11:20:15'),
	(7, 'OCU-007', 'VOL-OCU-007', 'Oil Cooler Unit', 2, 2, 2, 'Plate frame oil cooler for ship engines', '"{\\"capacity\\":\\"50 kW\\",\\"flow\\":\\"80 m3\\\\/h\\"}"', 95000.00, 68000.00, 0, NULL, NULL, NULL, '"[\\"https:\\\\/\\\\/images.unsplash.com\\\\/photo-1581092160562-40038f56386b?w=400\\",\\"https:\\\\/\\\\/images.unsplash.com\\\\/photo-1581092134237-87e58d9e2b2a?w=400\\"]"', 1, 1, 0.00, '2026-04-27 02:00:52', '2026-04-27 23:41:04'),
	(8, 'GBR-008', 'KOM-GBR-008', 'Gearbox Bearing', 3, 3, 3, 'Precision roller bearing for ship transmission', '"{\\"bore\\":\\"50mm\\",\\"type\\":\\"Roller\\"}"', 32000.00, 22000.00, 0, NULL, NULL, NULL, '"[\\"https:\\\\/\\\\/images.unsplash.com\\\\/photo-1581092160562-40038f14a55f?w=400\\",\\"https:\\\\/\\\\/images.unsplash.com\\\\/photo-1581092945236-87e58d9e2b3a?w=400\\",\\"https:\\\\/\\\\/images.unsplash.com\\\\/photo-1581092145263-87e58d9e2a1a?w=400\\"]"', 1, 1, 0.00, '2026-04-27 02:00:52', '2026-04-28 17:13:59'),
	(9, 'WPA-009', 'JD-WPA-009', 'Water Pump Assembly', 4, 1, 4, 'Centrifugal cooling water pump for marine engines', '"{\\"flow\\":\\"120 m3\\\\/h\\",\\"pressure\\":\\"3 bar\\"}"', 28000.00, 18000.00, 0, NULL, NULL, NULL, '"[\\"https:\\\\/\\\\/images.unsplash.com\\\\/photo-1581092160562-40038f56386c?w=400\\",\\"https:\\\\/\\\\/images.unsplash.com\\\\/photo-1581092134237-87e58d9e2b4a?w=400\\"]"', 1, 2, 0.00, '2026-04-27 02:00:52', '2026-04-29 03:00:09'),
	(10, 'ECP-010', 'HIT-ECP-010', 'Electrical Control Panel', 5, 4, 5, 'Complete electrical control panel for heavy machinery', '"{\\"voltage\\":\\"415V\\",\\"phase\\":\\"3-phase\\"}"', 125000.00, 85000.00, 0, NULL, NULL, NULL, '"[\\"https:\\\\/\\\\/images.unsplash.com\\\\/photo-1581092160562-40038f14a55g?w=400\\",\\"https:\\\\/\\\\/images.unsplash.com\\\\/photo-1581092945236-87e58d9e2b5a?w=400\\",\\"https:\\\\/\\\\/images.unsplash.com\\\\/photo-1581092145263-87e58d9e2a2a?w=400\\",\\"https:\\\\/\\\\/images.unsplash.com\\\\/photo-1581092160562-40038f56386d?w=400\\"]"', 1, 2, 0.00, '2026-04-27 02:00:52', '2026-04-27 02:20:44');

-- Dumping structure for table fl_0001.sessions
DROP TABLE IF EXISTS `sessions`;
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

-- Dumping data for table fl_0001.sessions: ~0 rows (approximately)
DELETE FROM `sessions`;

-- Dumping structure for table fl_0001.shipments
DROP TABLE IF EXISTS `shipments`;
CREATE TABLE IF NOT EXISTS `shipments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `tracking_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carrier` enum('in_transit','delivered','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `carrier_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tracking_details` json DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shipments_order_id_unique` (`order_id`),
  UNIQUE KEY `shipments_tracking_number_unique` (`tracking_number`),
  KEY `shipments_tracking_number_index` (`tracking_number`),
  CONSTRAINT `shipments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.shipments: ~0 rows (approximately)
DELETE FROM `shipments`;

-- Dumping structure for table fl_0001.stripe_refunds
DROP TABLE IF EXISTS `stripe_refunds`;
CREATE TABLE IF NOT EXISTS `stripe_refunds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` bigint unsigned NOT NULL,
  `stripe_charge_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_refund_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `type` enum('full','partial') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full',
  `status` enum('pending','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `stripe_response` json DEFAULT NULL,
  `processed_by` bigint unsigned DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stripe_refunds_payment_id_foreign` (`payment_id`),
  KEY `stripe_refunds_processed_by_foreign` (`processed_by`),
  KEY `stripe_refunds_stripe_charge_id_index` (`stripe_charge_id`),
  KEY `stripe_refunds_stripe_refund_id_index` (`stripe_refund_id`),
  KEY `stripe_refunds_status_index` (`status`),
  CONSTRAINT `stripe_refunds_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stripe_refunds_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.stripe_refunds: ~0 rows (approximately)
DELETE FROM `stripe_refunds`;

-- Dumping structure for table fl_0001.stripe_settings
DROP TABLE IF EXISTS `stripe_settings`;
CREATE TABLE IF NOT EXISTS `stripe_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `publishable_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `webhook_secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `environment` enum('test','live') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'test',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `minimum_amount` decimal(10,2) NOT NULL DEFAULT '0.50',
  `maximum_amount` decimal(14,2) NOT NULL DEFAULT '999999.99',
  `webhook_events` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `configured_at` timestamp NULL DEFAULT NULL,
  `configured_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stripe_settings_configured_by_foreign` (`configured_by`),
  CONSTRAINT `stripe_settings_configured_by_foreign` FOREIGN KEY (`configured_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.stripe_settings: ~0 rows (approximately)
DELETE FROM `stripe_settings`;

-- Dumping structure for table fl_0001.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('customer','admin','vendor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`),
  KEY `users_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.users: ~2 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Test User', 'test@example.com', '9876543210', NULL, '$2y$12$z9WRc8gmIT9n1XunNzMEw.QFeHN.yNkYDxh1R6Ug7AP0t.Zmg5r8y', 'customer', 1, NULL, '2026-04-27 02:00:51', '2026-04-27 02:00:51'),
	(2, 'Admin User', 'admin@example.com', '9999999999', NULL, '$2y$12$uvtrSNP7VU1t6Ez4080JwupDZ.kEdVUI9hilncjWhb1dDxwkV2xSe', 'admin', 1, '9sGceygLLNDcYnsfyGY6YQg3hwnOPsVsNv17tKFhn0YVSAHJlzCOofcsG9Mn', '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(3, 'Prosenjit Pramanicki', 'prosenjit@gmail.com', '1234567890', NULL, '$2y$12$lsK8o04vDVW3wL4vACIVseAYPFJfg6qx0XdSoP9RIqi9QPvWVMYbq', 'customer', 1, 'WWfHpLwpjMR3rUuHcSrop0dy41O7Bfh8pjOCmoD6ZniXKRQulmR2WbuQTfE9', '2026-04-28 13:09:32', '2026-04-28 13:09:32');

-- Dumping structure for table fl_0001.vessel_types
DROP TABLE IF EXISTS `vessel_types`;
CREATE TABLE IF NOT EXISTS `vessel_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g., CONTAINER_SHIP, TANKER, BULK_CARRIER',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vessel_types_name_unique` (`name`),
  UNIQUE KEY `vessel_types_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_0001.vessel_types: ~5 rows (approximately)
DELETE FROM `vessel_types`;
INSERT INTO `vessel_types` (`id`, `name`, `code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'General Cargo Ship', 'GENERAL_CARGO', NULL, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(2, 'Container Ship', 'CONTAINER_SHIP', NULL, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(3, 'Bulk Carrier', 'BULK_CARRIER', NULL, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(4, 'Tanker', 'TANKER', NULL, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52'),
	(5, 'Heavy Equipment', 'HEAVY_EQUIPMENT', NULL, 1, '2026-04-27 02:00:52', '2026-04-27 02:00:52');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
