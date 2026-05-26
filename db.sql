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


-- Dumping database structure for fl_cart
CREATE DATABASE IF NOT EXISTS `fl_cart` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `fl_cart`;

-- Dumping structure for table fl_cart.addresses
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

-- Dumping data for table fl_cart.addresses: ~2 rows (approximately)
DELETE FROM `addresses`;
INSERT INTO `addresses` (`id`, `user_id`, `type`, `name`, `phone`, `company_name`, `address_line_1`, `address_line_2`, `city`, `state`, `postal_code`, `country`, `is_default`, `created_at`, `updated_at`) VALUES
	(1, 3, 'billing', 'Prosenjit', '1234567890', NULL, 'Sonarpur', 'Daspara', 'Kolkata', 'WB', '700150', 'India', 0, '2026-05-23 01:51:50', '2026-05-23 03:02:36'),
	(2, 3, 'both', 'Prosenjit', '1234567890', NULL, 'Sonarpur', 'Daspara', 'Kolkata', 'WB', '700150', 'India', 0, '2026-05-23 03:14:54', '2026-05-23 03:14:54'),
	(3, 3, 'both', 'Prosenjit', '1234567890', NULL, 'Sonarpur', 'Daspara', 'Kolkata', 'WB', '700150', 'India', 0, '2026-05-25 02:44:12', '2026-05-25 02:44:12');

-- Dumping structure for table fl_cart.app_settings
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
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#2563eb',
  `secondary_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#1e40af',
  `accent_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3b82f6',
  `dark_mode_bg` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#1f2937',
  `dark_mode_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#f3f4f6',
  `dark_mode_accent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#60a5fa',
  `enable_dark_mode` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.app_settings: ~0 rows (approximately)
DELETE FROM `app_settings`;
INSERT INTO `app_settings` (`id`, `store_name`, `store_email`, `store_phone`, `store_address`, `store_city`, `store_state`, `store_zip`, `store_country`, `business_registration`, `tax_id`, `currency`, `timezone`, `items_per_page`, `enable_notifications`, `enable_api`, `footer_contact_description`, `logo_path`, `favicon_path`, `primary_color`, `secondary_color`, `accent_color`, `dark_mode_bg`, `dark_mode_text`, `dark_mode_accent`, `enable_dark_mode`, `created_at`, `updated_at`) VALUES
	(1, 'Ship Spare Parts', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'USD', NULL, 20, 1, 0, 'Your trusted provider of maritime spare parts and equipment.', NULL, NULL, '#2563eb', '#1e40af', '#3b82f6', '#1f2937', '#f3f4f6', '#60a5fa', 1, '2026-05-22 23:29:24', '2026-05-22 23:29:24');

-- Dumping structure for table fl_cart.audit_logs
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

-- Dumping data for table fl_cart.audit_logs: ~0 rows (approximately)
DELETE FROM `audit_logs`;

-- Dumping structure for table fl_cart.brands
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.brands: ~5 rows (approximately)
DELETE FROM `brands`;
INSERT INTO `brands` (`id`, `name`, `slug`, `description`, `logo_url`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Caterpillar', 'caterpillar', 'Leading manufacturer of heavy machinery', NULL, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(2, 'Volvo', 'volvo', 'Premium heavy equipment brand', NULL, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(3, 'Komatsu', 'komatsu', 'Japanese construction equipment leader', NULL, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(4, 'John Deere', 'john-deere', 'Trusted agricultural equipment brand', NULL, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(5, 'Hitachi', 'hitachi', 'Global equipment manufacturer', NULL, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51');

-- Dumping structure for table fl_cart.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.cache: ~0 rows (approximately)
DELETE FROM `cache`;

-- Dumping structure for table fl_cart.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table fl_cart.cart_items
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.cart_items: ~1 rows (approximately)
DELETE FROM `cart_items`;
INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`, `price`, `subtotal`, `created_at`, `updated_at`) VALUES
	(4, 3, 2, 1, 12500.00, 12500.00, '2026-05-26 08:49:33', '2026-05-26 08:49:33');

-- Dumping structure for table fl_cart.carts
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.carts: ~9 rows (approximately)
DELETE FROM `carts`;
INSERT INTO `carts` (`id`, `user_id`, `session_id`, `subtotal`, `tax`, `total`, `expires_at`, `created_at`, `updated_at`) VALUES
	(1, NULL, '0WuPCNzDxlBHa9uArAtiFkJM6kF5OZRvDVyROarD', 0.00, 0.00, 0.00, '2026-05-30 01:49:29', '2026-05-23 01:49:29', '2026-05-23 01:49:29'),
	(2, NULL, 'PFiBJWqPRLRMxPw0wJZVXiv8ZcyGaPKZVUojiWPT', 0.00, 0.00, 0.00, '2026-05-30 01:50:33', '2026-05-23 01:50:33', '2026-05-23 01:50:33'),
	(3, 3, 'hK797KyamlmoIbQ1L69Vk9yMQysK5Jm9A6PZIpSl', 12500.00, 2250.00, 14750.00, '2026-05-30 01:51:09', '2026-05-23 01:51:09', '2026-05-26 08:49:34'),
	(4, NULL, 'WftAJCMUHGFebohSY7xvlowp7svkE8s7b9YjsPyU', 0.00, 0.00, 0.00, '2026-05-30 12:25:48', '2026-05-23 12:25:48', '2026-05-23 12:25:48'),
	(5, NULL, 'jqLJ7aty8gywoXsOIexr8chaVaehkEUqMOancD25', 0.00, 0.00, 0.00, '2026-05-30 12:26:54', '2026-05-23 12:26:54', '2026-05-23 12:26:54'),
	(6, NULL, 'p6c7vqOzmljh8AXsgXiuLrQ7mhybmRzAqJoBsbos', 0.00, 0.00, 0.00, '2026-05-30 12:27:18', '2026-05-23 12:27:18', '2026-05-23 12:27:18'),
	(7, NULL, 'yUyGfOfLNkwZsTWAvM55bXpUiuxyiWNPXPJkrNM7', 0.00, 0.00, 0.00, '2026-06-01 02:09:26', '2026-05-25 02:09:26', '2026-05-25 02:09:26'),
	(8, NULL, 'PKgPQwaaPcsBsIExFQESrWvMVF7pbx1ttaO0O6fo', 0.00, 0.00, 0.00, '2026-06-01 02:18:10', '2026-05-25 02:18:10', '2026-05-25 02:18:10'),
	(9, NULL, 'sLuAaRxU0FE3sXTpfqqu0064bQD8R1EZlz68s0Su', 0.00, 0.00, 0.00, '2026-06-01 09:07:20', '2026-05-25 09:07:20', '2026-05-25 09:07:20'),
	(10, NULL, 'M2iDB5u0jaPnBIZPK0htMYmPGuExa1i23nF302RM', 0.00, 0.00, 0.00, '2026-06-02 08:04:49', '2026-05-26 08:04:49', '2026-05-26 08:04:49');

-- Dumping structure for table fl_cart.categories
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

-- Dumping data for table fl_cart.categories: ~5 rows (approximately)
DELETE FROM `categories`;
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image_url`, `parent_id`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Engine Parts', 'engine-parts', 'Engine components and accessories', NULL, NULL, 0, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(2, 'Hydraulic Components', 'hydraulic-components', 'Hydraulic systems and parts', NULL, NULL, 0, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(3, 'Transmission Parts', 'transmission-parts', 'Transmission and gearbox parts', NULL, NULL, 0, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(4, 'Electrical Components', 'electrical-components', 'Electrical systems and components', NULL, NULL, 0, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(5, 'Undercarriage Parts', 'undercarriage-parts', 'Track and undercarriage parts', NULL, NULL, 0, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51');

-- Dumping structure for table fl_cart.chatbot_conversations
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.chatbot_conversations: ~0 rows (approximately)
DELETE FROM `chatbot_conversations`;

-- Dumping structure for table fl_cart.chatbot_faqs
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

-- Dumping data for table fl_cart.chatbot_faqs: ~5 rows (approximately)
DELETE FROM `chatbot_faqs`;
INSERT INTO `chatbot_faqs` (`id`, `question`, `answer`, `category`, `keywords`, `display_order`, `view_count`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'What is your shipping policy?', 'We ship all orders within 2-3 business days. Free shipping on orders above ₹50,000.', 'Shipping', NULL, 0, 0, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(2, 'Do you offer bulk discounts?', 'Yes! We offer 10-15% discounts on bulk orders. Contact our sales team for details.', 'Pricing', NULL, 0, 0, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(3, 'How long do spare parts typically last?', 'Our genuine parts are designed to last as long as OEM parts, typically 3-5 years depending on usage.', 'Products', NULL, 0, 0, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(4, 'Can I return a part if it doesn\'t fit?', 'Yes, we offer 30-day returns on all products. The item must be unused and in original packaging.', 'Returns', NULL, 0, 0, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(5, 'What payment methods do you accept?', 'We accept bank transfers, UPI, credit/debit cards, and corporate checks.', 'Payment', NULL, 0, 0, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51');

-- Dumping structure for table fl_cart.failed_jobs
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

-- Dumping data for table fl_cart.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table fl_cart.inventories
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

-- Dumping data for table fl_cart.inventories: ~10 rows (approximately)
DELETE FROM `inventories`;
INSERT INTO `inventories` (`id`, `product_id`, `warehouse_qty`, `reserved_qty`, `available_qty`, `last_restock_at`, `created_at`, `updated_at`) VALUES
	(1, 1, 94, 9, 85, NULL, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(2, 2, 49, 5, 44, NULL, '2026-05-22 23:28:51', '2026-05-25 02:44:12'),
	(3, 3, 22, 9, 13, NULL, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(4, 4, 94, 3, 91, NULL, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(5, 5, 47, 0, 47, NULL, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(6, 6, 40, 7, 33, NULL, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(7, 7, 79, 4, 75, NULL, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(8, 8, 52, 3, 49, NULL, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(9, 9, 71, 0, 71, NULL, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(10, 10, 49, 1, 48, NULL, '2026-05-22 23:28:51', '2026-05-22 23:28:51');

-- Dumping structure for table fl_cart.job_batches
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

-- Dumping data for table fl_cart.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table fl_cart.jobs
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

-- Dumping data for table fl_cart.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table fl_cart.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.migrations: ~28 rows (approximately)
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
	(19, '2026_04_28_000001_create_pages_table', 1),
	(20, '2026_04_29_170221_create_personal_access_tokens_table', 1),
	(21, '2026_04_30_000100_add_stripe_paypal_to_payment_enums', 1),
	(22, '2026_05_01_000001_create_stripe_settings_table', 1),
	(23, '2026_05_01_000002_create_stripe_refunds_table', 1),
	(24, '2026_05_01_000003_create_paypal_settings_table', 1),
	(25, '2026_05_01_000004_create_paypal_ipns_table', 1),
	(26, '2026_05_01_000005_create_payment_methods_table', 1),
	(27, '2026_05_01_000005_create_paypal_ipn_logs_table', 1),
	(28, '2026_05_16_000001_create_app_settings_table', 1),
	(29, '2026_05_25_000001_create_stripe_customers_table', 2),
	(30, '2026_05_25_000002_create_stripe_cards_table', 2),
	(31, '2026_05_25_000003_create_stripe_charges_table', 2);

-- Dumping structure for table fl_cart.order_items
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.order_items: ~2 rows (approximately)
DELETE FROM `order_items`;
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_sku`, `product_name`, `quantity`, `unit_price`, `subtotal`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 'MFF-002', 'Marine Fuel Filter', 1, 12500.00, 12500.00, '2026-05-23 01:51:50', '2026-05-23 01:51:50'),
	(2, 2, 2, 'MFF-002', 'Marine Fuel Filter', 1, 12500.00, 12500.00, '2026-05-23 03:14:55', '2026-05-23 03:14:55'),
	(3, 3, 2, 'MFF-002', 'Marine Fuel Filter', 1, 12500.00, 12500.00, '2026-05-25 02:44:12', '2026-05-25 02:44:12');

-- Dumping structure for table fl_cart.orders
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

-- Dumping data for table fl_cart.orders: ~1 rows (approximately)
DELETE FROM `orders`;
INSERT INTO `orders` (`id`, `order_number`, `user_id`, `status`, `payment_status`, `payment_method`, `shipping_address_id`, `billing_address_id`, `subtotal`, `tax`, `shipping`, `discount`, `total`, `notes`, `admin_notes`, `confirmed_at`, `shipped_at`, `delivered_at`, `created_at`, `updated_at`) VALUES
	(1, 'ORD2026052300001', 3, 'cancelled', 'pending', NULL, 1, 1, 12500.00, 2250.00, 0.00, 0.00, 14750.00, '\n[2026-05-23 08:21:38] Cancelled: Cancelled by admin', NULL, NULL, NULL, NULL, '2026-05-23 01:51:50', '2026-05-23 02:51:38'),
	(2, 'ORD2026052300002', 3, 'pending', 'pending', NULL, 2, 2, 12500.00, 2250.00, 0.00, 0.00, 14750.00, NULL, NULL, NULL, NULL, NULL, '2026-05-23 03:14:55', '2026-05-23 03:14:55'),
	(3, 'ORD2026052500001', 3, 'pending', 'pending', NULL, 3, 3, 12500.00, 2250.00, 0.00, 0.00, 14750.00, NULL, NULL, NULL, NULL, NULL, '2026-05-25 02:44:12', '2026-05-25 02:44:12');

-- Dumping structure for table fl_cart.pages
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.pages: ~0 rows (approximately)
DELETE FROM `pages`;

-- Dumping structure for table fl_cart.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table fl_cart.payment_methods
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

-- Dumping data for table fl_cart.payment_methods: ~4 rows (approximately)
DELETE FROM `payment_methods`;
INSERT INTO `payment_methods` (`id`, `key`, `name`, `description`, `type`, `is_enabled`, `is_default`, `sort_order`, `minimum_amount`, `maximum_amount`, `settings`, `icon_class`, `controller_path`, `service_path`, `created_at`, `updated_at`) VALUES
	(1, 'stripe', 'Credit/Debit Card (Stripe)', 'Accept payments via Stripe with credit and debit cards', 'card', 1, 1, 1, NULL, NULL, NULL, 'fas fa-credit-card', 'Plugins\\PaymentStripe\\Controllers\\StripePaymentController', 'Plugins\\PaymentStripe\\Services\\StripeService', '2026-05-23 01:44:38', '2026-05-23 01:44:38'),
	(2, 'paypal', 'PayPal', 'Accept payments via PayPal checkout', 'wallet', 0, 0, 2, NULL, NULL, NULL, 'fab fa-paypal', 'Plugins\\PaymentPayPal\\Controllers\\PayPalPaymentController', 'Plugins\\PaymentPayPal\\Services\\PayPalService', '2026-05-23 01:44:38', '2026-05-23 01:44:38'),
	(3, 'card', 'Card Payment (Direct)', 'Accept card details directly from checkout form', 'card', 0, 0, 3, NULL, NULL, NULL, 'fas fa-credit-card', 'Plugins\\PaymentStripe\\Controllers\\StripePaymentController', 'Plugins\\PaymentStripe\\Services\\StripeService', '2026-05-23 01:44:38', '2026-05-23 01:44:38'),
	(4, 'bank_transfer', 'Bank Transfer', 'Accept payments via manual bank transfer with proof upload', 'bank', 0, 0, 4, NULL, NULL, NULL, 'fas fa-university', NULL, NULL, '2026-05-23 01:44:38', '2026-05-23 01:44:38');

-- Dumping structure for table fl_cart.payments
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.payments: ~0 rows (approximately)
DELETE FROM `payments`;

-- Dumping structure for table fl_cart.paypal_ipn_logs
CREATE TABLE IF NOT EXISTS `paypal_ipn_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ipn_log_id` bigint unsigned DEFAULT NULL,
  `raw_post_data` longtext COLLATE utf8mb4_unicode_ci,
  `response_status` enum('verified','invalid','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `response_data` json DEFAULT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paypal_ipn_logs_ipn_log_id_foreign` (`ipn_log_id`),
  CONSTRAINT `paypal_ipn_logs_ipn_log_id_foreign` FOREIGN KEY (`ipn_log_id`) REFERENCES `paypal_ipns` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.paypal_ipn_logs: ~0 rows (approximately)
DELETE FROM `paypal_ipn_logs`;

-- Dumping structure for table fl_cart.paypal_ipns
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

-- Dumping data for table fl_cart.paypal_ipns: ~0 rows (approximately)
DELETE FROM `paypal_ipns`;

-- Dumping structure for table fl_cart.paypal_settings
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

-- Dumping data for table fl_cart.paypal_settings: ~0 rows (approximately)
DELETE FROM `paypal_settings`;

-- Dumping structure for table fl_cart.personal_access_tokens
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

-- Dumping data for table fl_cart.personal_access_tokens: ~0 rows (approximately)
DELETE FROM `personal_access_tokens`;

-- Dumping structure for table fl_cart.products
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

-- Dumping data for table fl_cart.products: ~10 rows (approximately)
DELETE FROM `products`;
INSERT INTO `products` (`id`, `sku`, `part_number`, `name`, `brand_id`, `category_id`, `vessel_type_id`, `description`, `specifications`, `price`, `cost`, `stock_qty`, `weight`, `dimensions`, `image_url`, `images`, `is_active`, `view_count`, `rating`, `created_at`, `updated_at`) VALUES
	(1, 'ECH-001', 'CAT-ECH-001', 'Engine Cylinder Head', 1, 1, 1, 'Genuine Caterpillar engine cylinder head, compatible with marine engines', '"{\\"material\\":\\"Cast Iron\\",\\"weight\\":\\"45kg\\"}"', 85000.00, 65000.00, 0, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1621905252507-b35492cc74b4?w=600&q=80", "https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?w=600&q=80", "https://images.unsplash.com/photo-1567789884554-0b844b597180?w=600&q=80"]', 1, 0, 0.00, '2026-05-22 23:28:51', '2026-05-26 08:17:44'),
	(2, 'MFF-002', 'VOL-MFF-002', 'Marine Fuel Filter', 2, 1, 2, 'High-performance fuel filter for container ships', '"{\\"capacity\\":\\"50 micron\\",\\"flow_rate\\":\\"100gph\\"}"', 12500.00, 9000.00, -3, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1582735689369-4fe89db7114c?w=600&q=80", "https://images.unsplash.com/photo-1537944434965-cf4679d1a598?w=600&q=80"]', 1, 0, 0.00, '2026-05-22 23:28:51', '2026-05-26 08:17:44'),
	(3, 'HPA-003', 'KOM-HPA-003', 'Hydraulic Pump Assembly', 3, 2, 3, 'Complete hydraulic pump assembly for bulk carriers', '"{\\"pressure\\":\\"280 bar\\",\\"displacement\\":\\"40cc\\"}"', 250000.00, 180000.00, 0, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1513828583688-c52646db42da?w=600&q=80", "https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?w=600&q=80", "https://images.unsplash.com/photo-1562408590-e32931084e23?w=600&q=80", "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80"]', 1, 0, 0.00, '2026-05-22 23:28:51', '2026-05-26 08:17:44'),
	(4, 'ALT-004', 'JD-ALT-004', 'Alternator 100A', 4, 4, 4, 'Marine-grade alternator for tanker vessels', '"{\\"output\\":\\"100A\\",\\"voltage\\":\\"24V\\"}"', 45000.00, 32000.00, 0, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&q=80", "https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?w=600&q=80", "https://images.unsplash.com/photo-1537944434965-cf4679d1a598?w=600&q=80"]', 1, 0, 0.00, '2026-05-22 23:28:51', '2026-05-26 08:17:44'),
	(5, 'TLA-005', 'HIT-TLA-005', 'Track Link Assembly', 5, 5, 5, 'Heavy-duty undercarriage track link for excavators', '"{\\"pitch\\":\\"100mm\\",\\"material\\":\\"Steel\\"}"', 15000.00, 10000.00, 0, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=600&q=80", "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80"]', 1, 0, 0.00, '2026-05-22 23:28:51', '2026-05-26 08:17:44'),
	(6, 'TUR-006', 'CAT-TUR-006', 'Turbocharger Assembly', 1, 1, 1, 'Genuine turbo for marine diesel engines', '"{\\"boost\\":\\"2.5 bar\\",\\"type\\":\\"Fixed\\"}"', 180000.00, 125000.00, 0, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1621905252507-b35492cc74b4?w=600&q=80", "https://images.unsplash.com/photo-1567789884554-0b844b597180?w=600&q=80", "https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?w=600&q=80"]', 1, 0, 0.00, '2026-05-22 23:28:51', '2026-05-26 08:17:44'),
	(7, 'OCU-007', 'VOL-OCU-007', 'Oil Cooler Unit', 2, 2, 2, 'Plate frame oil cooler for ship engines', '"{\\"capacity\\":\\"50 kW\\",\\"flow\\":\\"80 m3\\\\/h\\"}"', 95000.00, 68000.00, 0, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1513828583688-c52646db42da?w=600&q=80", "https://images.unsplash.com/photo-1562408590-e32931084e23?w=600&q=80"]', 1, 0, 0.00, '2026-05-22 23:28:51', '2026-05-26 08:17:44'),
	(8, 'GBR-008', 'KOM-GBR-008', 'Gearbox Bearing', 3, 3, 3, 'Precision roller bearing for ship transmission', '"{\\"bore\\":\\"50mm\\",\\"type\\":\\"Roller\\"}"', 32000.00, 22000.00, 0, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=600&q=80", "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80", "https://images.unsplash.com/photo-1582735689369-4fe89db7114c?w=600&q=80"]', 1, 0, 0.00, '2026-05-22 23:28:51', '2026-05-26 08:17:44'),
	(9, 'WPA-009', 'JD-WPA-009', 'Water Pump Assembly', 4, 1, 4, 'Centrifugal cooling water pump for marine engines', '"{\\"flow\\":\\"120 m3\\\\/h\\",\\"pressure\\":\\"3 bar\\"}"', 28000.00, 18000.00, 0, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1513828583688-c52646db42da?w=600&q=80", "https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?w=600&q=80"]', 1, 0, 0.00, '2026-05-22 23:28:51', '2026-05-26 08:17:44'),
	(10, 'ECP-010', 'HIT-ECP-010', 'Electrical Control Panel', 5, 4, 5, 'Complete electrical control panel for heavy machinery', '"{\\"voltage\\":\\"415V\\",\\"phase\\":\\"3-phase\\"}"', 125000.00, 85000.00, 0, NULL, NULL, NULL, '["https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&q=80", "https://images.unsplash.com/photo-1537944434965-cf4679d1a598?w=600&q=80", "https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?w=600&q=80", "https://images.unsplash.com/photo-1562408590-e32931084e23?w=600&q=80"]', 1, 0, 0.00, '2026-05-22 23:28:51', '2026-05-26 08:17:44');

-- Dumping structure for table fl_cart.sessions
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

-- Dumping data for table fl_cart.sessions: ~0 rows (approximately)
DELETE FROM `sessions`;

-- Dumping structure for table fl_cart.shipments
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

-- Dumping data for table fl_cart.shipments: ~0 rows (approximately)
DELETE FROM `shipments`;

-- Dumping structure for table fl_cart.stripe_cards
CREATE TABLE IF NOT EXISTS `stripe_cards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stripe_customer_id` bigint unsigned NOT NULL,
  `payment_method_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_four` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exp_month` smallint unsigned NOT NULL,
  `exp_year` smallint unsigned NOT NULL,
  `cardholder_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `metadata` json DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stripe_cards_payment_method_id_unique` (`payment_method_id`),
  KEY `stripe_cards_stripe_customer_id_foreign` (`stripe_customer_id`),
  KEY `stripe_cards_payment_method_id_index` (`payment_method_id`),
  CONSTRAINT `stripe_cards_stripe_customer_id_foreign` FOREIGN KEY (`stripe_customer_id`) REFERENCES `stripe_customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.stripe_cards: ~0 rows (approximately)
DELETE FROM `stripe_cards`;

-- Dumping structure for table fl_cart.stripe_charges
CREATE TABLE IF NOT EXISTS `stripe_charges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` bigint unsigned NOT NULL,
  `stripe_customer_id` bigint unsigned NOT NULL,
  `stripe_card_id` bigint unsigned DEFAULT NULL,
  `stripe_charge_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_payment_intent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','succeeded','failed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `failure_message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `failure_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `receipt_data` json DEFAULT NULL,
  `charged_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stripe_charges_stripe_charge_id_unique` (`stripe_charge_id`),
  UNIQUE KEY `stripe_charges_stripe_payment_intent_id_unique` (`stripe_payment_intent_id`),
  KEY `stripe_charges_payment_id_foreign` (`payment_id`),
  KEY `stripe_charges_stripe_customer_id_foreign` (`stripe_customer_id`),
  KEY `stripe_charges_stripe_card_id_foreign` (`stripe_card_id`),
  KEY `stripe_charges_stripe_charge_id_index` (`stripe_charge_id`),
  KEY `stripe_charges_stripe_payment_intent_id_index` (`stripe_payment_intent_id`),
  CONSTRAINT `stripe_charges_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stripe_charges_stripe_card_id_foreign` FOREIGN KEY (`stripe_card_id`) REFERENCES `stripe_cards` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stripe_charges_stripe_customer_id_foreign` FOREIGN KEY (`stripe_customer_id`) REFERENCES `stripe_customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.stripe_charges: ~0 rows (approximately)
DELETE FROM `stripe_charges`;

-- Dumping structure for table fl_cart.stripe_customers
CREATE TABLE IF NOT EXISTS `stripe_customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `stripe_customer_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stripe_customers_user_id_unique` (`user_id`),
  UNIQUE KEY `stripe_customers_stripe_customer_id_unique` (`stripe_customer_id`),
  CONSTRAINT `stripe_customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_cart.stripe_customers: ~0 rows (approximately)
DELETE FROM `stripe_customers`;

-- Dumping structure for table fl_cart.stripe_refunds
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

-- Dumping data for table fl_cart.stripe_refunds: ~0 rows (approximately)
DELETE FROM `stripe_refunds`;

-- Dumping structure for table fl_cart.stripe_settings
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

-- Dumping data for table fl_cart.stripe_settings: ~0 rows (approximately)
DELETE FROM `stripe_settings`;

-- Dumping structure for table fl_cart.users
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

-- Dumping data for table fl_cart.users: ~2 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Test User', 'test@example.com', '9876543210', NULL, '$2y$12$J.ED02LdRsuEZy.cl7h/m.Wd.adD.6xARox14gi75.JjW8NZcdxtu', 'customer', 1, NULL, '2026-05-22 23:28:50', '2026-05-22 23:28:50'),
	(2, 'Admin User', 'admin@example.com', '9999999999', NULL, '$2y$12$zZZOJZ1VXNLQGpbST4VbU.r5aq1A1C5Eu7RPx83WhvV/lh.8xZkQu', 'admin', 1, NULL, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(3, 'prosenjit', 'prosenjit@email.com', '1234567890', NULL, '$2y$12$ZN70jPSnG6tmFVZ7XnxAcOZCa2qGKi/miSU2MpP8wB/Zk2WGxnrFy', 'customer', 1, NULL, '2026-05-23 01:51:08', '2026-05-23 01:51:08');

-- Dumping structure for table fl_cart.vessel_types
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

-- Dumping data for table fl_cart.vessel_types: ~5 rows (approximately)
DELETE FROM `vessel_types`;
INSERT INTO `vessel_types` (`id`, `name`, `code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'General Cargo Ship', 'GENERAL_CARGO', NULL, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(2, 'Container Ship', 'CONTAINER_SHIP', NULL, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(3, 'Bulk Carrier', 'BULK_CARRIER', NULL, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(4, 'Tanker', 'TANKER', NULL, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51'),
	(5, 'Heavy Equipment', 'HEAVY_EQUIPMENT', NULL, 1, '2026-05-22 23:28:51', '2026-05-22 23:28:51');


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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_real.migrations: ~8 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_05_25_184214_create_properties_table', 2),
	(5, '2026_05_25_184215_create_contact_inquiries_table', 2),
	(6, '2026_05_25_184215_create_property_images_table', 2),
	(7, '2026_05_25_184216_create_special_requests_table', 2),
	(8, '2026_05_25_184217_create_investment_inquiries_table', 2);

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
  KEY `properties_is_available_index` (`is_available`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_real.properties: ~8 rows (approximately)
DELETE FROM `properties`;
INSERT INTO `properties` (`id`, `title`, `description`, `price`, `type`, `location`, `address`, `area`, `bedrooms`, `bathrooms`, `parking_spaces`, `is_featured`, `is_available`, `agent_name`, `agent_contact`, `created_at`, `updated_at`) VALUES
	(1, 'Beautiful Downtown Apartment', 'Modern apartment in the heart of downtown with stunning city views, full amenities, and close proximity to restaurants and shopping centers.', 350000.00, 'residential', 'Downtown', '123 Main Street, Downtown District', 1500, 2, 2, 2, 1, 1, 'John Smith', '+1 (555) 123-4567', '2026-05-25 13:18:22', '2026-05-25 13:18:22'),
	(2, 'Modern Office Complex', 'Prime commercial space perfect for corporate offices. Features high-speed internet, professional workspace, parking, and modern amenities.', 750000.00, 'commercial', 'Business District', '456 Commerce Avenue, Business Hub', 5000, NULL, 4, 15, 1, 1, 'Sarah Johnson', '+1 (555) 234-5678', '2026-05-25 13:18:22', '2026-05-25 13:18:22'),
	(3, 'Spacious Suburban Home', 'Beautiful family home in a quiet suburban neighborhood with large yard, modern kitchen, and excellent schools nearby.', 425000.00, 'residential', 'Suburban Area', '789 Oak Lane, Quiet Neighborhood', 2500, 4, 3, 3, 1, 1, 'Michael Brown', '+1 (555) 345-6789', '2026-05-25 13:18:22', '2026-05-25 13:18:22'),
	(4, 'Prime Development Land', 'Excellent investment opportunity. Zoned for mixed-use development with great potential for residential or commercial projects.', 500000.00, 'land', 'Emerging District', '321 Future Development Way', 10000, NULL, NULL, NULL, 1, 1, 'John Smith', '+1 (555) 123-4567', '2026-05-25 13:18:22', '2026-05-25 13:18:22'),
	(5, 'Luxury Penthouse', 'Stunning penthouse with panoramic views, premium finishes, private balcony, and exclusive building amenities.', 890000.00, 'residential', 'Premium District', '999 Luxury Heights, Downtown', 3200, 3, 3, 3, 1, 1, 'Sarah Johnson', '+1 (555) 234-5678', '2026-05-25 13:18:22', '2026-05-25 13:18:22'),
	(6, 'Cozy Studio Apartment', 'Perfect starter home or investment property. Recently renovated with modern appliances and neutral decor.', 180000.00, 'residential', 'Central District', '456 Central Park Avenue', 650, 1, 1, 1, 0, 1, 'Michael Brown', '+1 (555) 345-6789', '2026-05-25 13:18:22', '2026-05-25 13:18:22'),
	(7, 'Retail Shop Space', 'Excellent retail location with high foot traffic. Ideal for boutique, cafe, or any retail business.', 300000.00, 'commercial', 'Shopping District', '555 Retail Plaza', 2000, NULL, 2, 8, 0, 1, 'John Smith', '+1 (555) 123-4567', '2026-05-25 13:18:22', '2026-05-25 13:18:22'),
	(8, 'Waterfront Property', 'Stunning waterfront residence with direct beach access, modern architecture, and breathtaking water views.', 1200000.00, 'residential', 'Waterfront', '100 Ocean Drive, Beach Front', 4000, 5, 4, 4, 1, 1, 'Sarah Johnson', '+1 (555) 234-5678', '2026-05-25 13:18:22', '2026-05-25 13:18:22');

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

-- Dumping data for table fl_real.property_images: ~19 rows (approximately)
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

-- Dumping data for table fl_real.sessions: ~18 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('3mvThNitfChyoRuE9kyi6Rru4BThLz8d7XmhZMQ0', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYk16T1lWelJ6SkZUTDJLaFBQU29LZDFxVEpLa1hQc1A5Y0owZWx6MyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbC5yZWFsZXN0YXRlL2ludmVzdG9ycyI7czo1OiJyb3V0ZSI7czoxNToiaW52ZXN0b3JzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779805142),
	('3VPxLcSeKb5pPmi1stmLnMrnvwX1YsmMfYXN7AUr', 3, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQkhLbkRwcnNQNkQ2VVBLU052N3dtZTcwY2pyeE1wRFZ3bDZNSkFydCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMSI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1779770874),
	('7hqVTuR5EGG730UgF4Kq0BKvf8hdEmLaNNItBT4K', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUk5aenRiNzVONXJLb2ExblR3cTRibER5NDRZT2lDc1AzbDIxWkl5VyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODExMS9pbnZlc3RvcnMiO3M6NToicm91dGUiO3M6MTU6ImludmVzdG9ycy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1779744033),
	('AWTJm0ICooSxzztwvM0SkIGlaQAlEVtCOZn4IJyI', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUFlPeWhQQ1BJalJpdjZYaVc2V2VQbGpEb1RiTEFyNnZIdnpCTDNhTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODExMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1779744033),
	('cw93m66zM3hLiYzZmHEEjuAHtFENfNBj3t6ZZzKV', 3, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNnUyQklxanRjVU0zRERaNWZ3dnhlY25RZ2tqZ1V2blVRMVIwSzFuNCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAxL2ludmVzdG9ycyI7czo1OiJyb3V0ZSI7czoxNToiaW52ZXN0b3JzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779802461),
	('d2WgAkV0AT5N1qKlemtmNv1q0HMgfqb6mrtScYVI', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTFVBVTNnRTBUSXFNc2ZwNmtkcWZVVGp6WmVrR25zTVd2eGxROU0ybSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODExMS9zcGVjaWFsLXJlcXVlc3RzIjtzOjU6InJvdXRlIjtzOjIzOiJzcGVjaWFsLXJlcXVlc3RzLmNyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1779744033),
	('DoIoadkMAJqN1ysUQQ1Yffz0N7tLHFdkGI7OL3ai', 3, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZWZzaFBSWVBWdFM1blNFTFAxOGt4QzVSMHR2SjhEcDVlUnFwS0VVVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9jb250YWN0IjtzOjU6InJvdXRlIjtzOjE0OiJjb250YWN0LmNyZWF0ZSI7fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1779747132),
	('FRzaIEDQ1yt4RQqjGTL0ekXnr9HRMVxmvrT4LITm', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMHFSM2NpOEtMMWlzMmxzMVhWRXg0Q2hxaGhwQkJHUDl3SVpOenZ2SiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODExMi9wcm9wZXJ0aWVzIjtzOjU6InJvdXRlIjtzOjE2OiJwcm9wZXJ0aWVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779744653),
	('ggt13kB6Vp7O5u62NpGtFKTDFd6p5QMOrhOS0bRQ', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic3h4Qmd1UG5ib0xDT0VrbEkyWmFKck5hTzhBSXlBUXpzRlQ4aTZpQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODExMi9wcm9wZXJ0aWVzLzUiO3M6NToicm91dGUiO3M6MTU6InByb3BlcnRpZXMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1779744668),
	('hJMkMi25GPNFcrTC5R0OxqMVV3aCk7MEOIdJNOUO', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkhqamd1RmlmWHRtVEV0WUs4dnZoNmxLbkh2Q1lCc282cHY3OWZZQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODExMSI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779744026),
	('hVSTfIGRgSfjMg9ZOjMyNYvwD8ewgaMyGtmKjug6', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaXZ6WEdXZW1sRG1HcktMZ01INlhSd0ljVnExM1NteDVPeExJbFZsTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODExMS9hYm91dCI7czo1OiJyb3V0ZSI7czo1OiJhYm91dCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1779744033),
	('iTQOLshqTNtmUHhh6hA1eguOUeLLNgzj3GgKxql8', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjl2MVk4a1E1N295R2tyWGxWQkFEOVNuVUl3U0tkSTY3Q05MMzI0dSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODExMi9wcm9wZXJ0aWVzLzUiO3M6NToicm91dGUiO3M6MTU6InByb3BlcnRpZXMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1779744653),
	('K67Si2Ow7tNZk5Tj3q391sByEtgANygdqLCXYKV4', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicjBhM3FBTXJucEtGeXU2ZkdpWjlCU2hzejFZMk1yZE9ydFNJS3ZBbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODExMS9wcm9wZXJ0aWVzIjtzOjU6InJvdXRlIjtzOjE2OiJwcm9wZXJ0aWVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779744033),
	('lsJFm5JpCqfhLKrlAXxxgSzaGGsVJvR355bzQxe9', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSkhBVm9NZkRrZXpRREI5TlB0YVBHblNpTHc2ZzliQVpncmllZVRaMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODExMS9jb250YWN0IjtzOjU6InJvdXRlIjtzOjE0OiJjb250YWN0LmNyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1779744033),
	('MNUS4TindjRCFpRXAOpFFmdSOjFsU2rasGTzeuR3', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY0wxZGx2VnREcjFpVUdaejltZmM4NEc1YXVWMjlnWm9XRUVSTzhMdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODExMiI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779744661),
	('U27koYeB2N8zIaLc9IuFrdiEBiD2p7Bsij9K4Kvv', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN21JMVJHS2NCT1ZUTHhvSVRqZkZMeHdqVmlza2JpM3FJY3JpQ1FRaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODExMiI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779744653),
	('VkQ3TvTRnnbifWyrUTkKYph5bXG5WVThEUg28nOs', NULL, '127.0.0.1', 'curl/7.81.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN1BlbWFlQlEyUGtEUzVIY2xvd3VleWxkczRackUzeWFJWmk3Y0pxdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODExMi9wcm9wZXJ0aWVzLzEiO3M6NToicm91dGUiO3M6MTU6InByb3BlcnRpZXMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1779744653),
	('Vm6ZE4L7yuwLzoGxS2yMcMBrAn4TmTOc4eKHV87B', NULL, '127.0.0.1', 'curl/7.81.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibjUyb3hTeXdqWUlBRTIyN3J4OFd3cnkzZ1V3OVhKZmpHN2JBakJMRSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NToiaHR0cDovLzEyNy4wLjAuMTo4MTEzL2FkbWluL3Byb3BlcnRpZXMvMi9lZGl0Ijt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODExMy9hZG1pbi9wcm9wZXJ0aWVzLzIvZWRpdCI7czo1OiJyb3V0ZSI7czoyMToiYWRtaW4ucHJvcGVydGllcy5lZGl0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779746127);

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


-- Dumping database structure for fl_websolai
CREATE DATABASE IF NOT EXISTS `fl_websolai` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `fl_websolai`;

-- Dumping structure for table fl_websolai.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_websolai.cache: ~0 rows (approximately)
DELETE FROM `cache`;

-- Dumping structure for table fl_websolai.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_websolai.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table fl_websolai.contacts
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_websolai.contacts: ~0 rows (approximately)
DELETE FROM `contacts`;

-- Dumping structure for table fl_websolai.failed_jobs
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

-- Dumping data for table fl_websolai.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table fl_websolai.job_batches
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

-- Dumping data for table fl_websolai.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table fl_websolai.jobs
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

-- Dumping data for table fl_websolai.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table fl_websolai.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_websolai.migrations: ~4 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_05_26_131259_create_contacts_table', 2);

-- Dumping structure for table fl_websolai.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_websolai.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table fl_websolai.sessions
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

-- Dumping data for table fl_websolai.sessions: ~6 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('4rpi4ABfv8z4ZvVOjpDNYbSZtqV6Bux8ig37LSNM', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicm45ZVdJOWhaYlhhcndlamZwbXRuTU13SU1ldU43RkFxclVIcERmRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbC53ZWJzb2xhaS9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1779801471),
	('69RLeyrKFLpqt68nz45M92kj2p8Dlxyf832mhmx2', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTEJNaklCTUhFUjBvOTlEVlNjZG01UEpDZlRQbThlYXd1T1QyMU5TRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbC53ZWJzb2xhaS9jb250YWN0IjtzOjU6InJvdXRlIjtzOjEyOiJjb250YWN0LmZvcm0iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjE5OiJhZG1pbl9hdXRoZW50aWNhdGVkIjtiOjE7fQ==', 1779807582),
	('aR4n3NWN7giPlQmJGv11QaCEIQ1x9XSrUQaDzy81', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieHZhbFhqRjQxTGRORW04U0plWGFYdzdpZUdaa2I1TWNFYVd1U3BWNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbC53ZWJzb2xhaS9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1779802094),
	('bhdzP51s3XdHRcSGQM2CP4KbXeOfQSDgHYma088j', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRUliREFxNWpCcmFLemlVNUhRZWk5b1lvODBVblVTdkZCNXR2Z3l6ZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbC53ZWJzb2xhaS9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1779802123),
	('nOCsQ9jOnCzqD51fUs1bFT9sTXAucDHT4yYTfAWB', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNmwyMmNMU2FuNVBHb0JUN3FWb3FmMHVFQk5VVGVKUXhqM3VSY3NGZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbC53ZWJzb2xhaSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxOToiYWRtaW5fYXV0aGVudGljYXRlZCI7YjoxO30=', 1779802079),
	('wIEsz5hjc7yjfY8j0VW8NkiKSVEFuQjvfchEGXO8', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTFZSV0ZHY0JJSGFwRmoySlF1bHB4bG1Rd3pHMDdFMlhIZVRvZWxDNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbC53ZWJzb2xhaS9hZG1pbi9jb250YWN0cyI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4uY29udGFjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjE5OiJhZG1pbl9hdXRoZW50aWNhdGVkIjtiOjE7fQ==', 1779802119);

-- Dumping structure for table fl_websolai.users
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fl_websolai.users: ~0 rows (approximately)
DELETE FROM `users`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
