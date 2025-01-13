-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.39 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for foodbonda
CREATE DATABASE IF NOT EXISTS `foodbonda` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `foodbonda`;

-- Dumping structure for table foodbonda.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `customer_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `middle_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table foodbonda.customers: ~2 rows (approximately)
INSERT INTO `customers` (`customer_id`, `first_name`, `middle_name`, `last_name`, `email`, `password`, `created_at`, `last_login`, `status`) VALUES
	(1, 'Jean Roshan', 'Magbanua', 'Urbano', 'jrmurbano.chmsu@gmail.com', '$2y$10$csOFQB24.OjTfnj35rft/.OwwWdjsbD/337F4xiZTSURLpBSyrgdC', '2025-01-11 11:38:04', NULL, 'active'),
	(2, 'Charles Ivan', 'Colado', 'Monserate', 'cicmonserate.chmsu@gmail.com', '$2y$10$ahGEehvswWvLT.AQKNkAKeA1j5DiItzenwhuTGA4KRXQpoO/LuNNS', '2025-01-11 13:33:28', NULL, 'active');

-- Dumping structure for table foodbonda.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table foodbonda.notifications: ~11 rows (approximately)
INSERT INTO `notifications` (`notification_id`, `customer_id`, `message`, `is_read`, `created_at`) VALUES
	(1, 2, 'Your reservation has been approved.', 1, '2025-01-12 14:42:56'),
	(2, 2, 'Your reservation has been approved.', 1, '2025-01-12 14:43:01'),
	(3, 2, 'Your reservation has been canceled.', 1, '2025-01-12 15:07:20'),
	(4, 2, 'Your reservation has been canceled.', 1, '2025-01-12 15:07:22'),
	(5, 2, 'Your reservation has been canceled.', 1, '2025-01-12 15:12:38'),
	(6, 2, 'Your reservation has been canceled.', 1, '2025-01-12 15:12:40'),
	(7, 2, 'Your reservation has been approved.', 1, '2025-01-12 15:14:34'),
	(8, 2, 'Your reservation has been canceled.', 0, '2025-01-13 04:33:56'),
	(9, 2, 'Your reservation has been approved.', 0, '2025-01-13 04:34:29'),
	(10, 2, 'Your reservation has been approved.', 0, '2025-01-13 06:08:55'),
	(11, 2, 'Your reservation has been approved.', 0, '2025-01-13 07:49:15'),
	(12, 1, 'Your reservation has been approved.', 1, '2025-01-13 08:58:13');

-- Dumping structure for table foodbonda.owners
CREATE TABLE IF NOT EXISTS `owners` (
  `owner_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`owner_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table foodbonda.owners: ~2 rows (approximately)
INSERT INTO `owners` (`owner_id`, `username`, `password`, `first_name`, `last_name`, `email`) VALUES
	(2, 'jrmurbano', '$2y$10$qtDtPICQRSA99/SH3aZqh.Sbwcn1s8SZb6/rdOgxVkRhtRAKt7Ko2', 'Jean Roshan', 'Urbano', 'roshanurbano@gmail.com'),
	(3, 'icmonserate', '$2y$10$rAO/1kS/93e6v3QPvIoMQugkQtzjEJGUyILsQeqtOOypPAhavGGyu', 'Charles Ivan', 'Monserate', 'icmonserate@gmail.com');

-- Dumping structure for table foodbonda.packages
CREATE TABLE IF NOT EXISTS `packages` (
  `package_id` int NOT NULL AUTO_INCREMENT,
  `package_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `included_dishes` text COLLATE utf8mb4_general_ci NOT NULL,
  `additional_dishes_limit` int DEFAULT NULL,
  PRIMARY KEY (`package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table foodbonda.packages: ~11 rows (approximately)
INSERT INTO `packages` (`package_id`, `package_name`, `price`, `included_dishes`, `additional_dishes_limit`) VALUES
	(1, 'PACKAGE 1', 2999.00, 'FISH FILLET, CHOPSUEY, FRIED CHICKEN, PORK BBQ, PORK SOIMAI', 0),
	(2, 'PACKAGE 2', 2999.00, 'LUMPIA, PORK BBQ, CORDON, BIHON GUISADO, FISH FILLET, CHOPSUEY', NULL),
	(3, 'PACKAGE 3', 2999.00, 'LUMPIA, FISH SWEET AND SOUR, PORK BBQ, SOTANGHON GUISADO, BUFFALO WINGS, VALENCIANA', NULL),
	(4, 'PACKAGE 4', 2999.00, 'LUMPIA, FISH FILLET, FRIED WHOLE CHICKEN, PANCIT GUISADO, CHOPSUEY, BUFFALO WINGS', NULL),
	(5, 'PACKAGE 5', 2999.00, 'LUMPIA, SOTANGHON, CARBONARA, PORK BBQ, SOIMAI, FISH FILLET, PORK STEAK', NULL),
	(6, 'LECHON PACKAGE A (10-12 KGS)', 7000.00, 'LECHON WITH FREE SAUCE AND DINUGUAN', NULL),
	(7, 'LECHON PACKAGE A (12-13 KGS)', 7500.00, 'LECHON WITH FREE SAUCE AND DINUGUAN', NULL),
	(8, 'LECHON PACKAGE B', 9000.00, 'LECHON WITH FREE SAUCE AND DINUGUAN', 3),
	(9, 'LECHON PACKAGE C', 9500.00, 'LECHON WITH FREE SAUCE AND DINUGUAN', 4),
	(10, 'LECHON PACKAGE D', 10000.00, 'LECHON WITH FREE SAUCE AND DINUGUAN', 5),
	(11, 'LECHON PACKAGE E', 11000.00, 'LECHON WITH FREE SAUCE AND DINUGUAN', 6);

-- Dumping structure for table foodbonda.payment
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `reservation_id` int DEFAULT NULL,
  `owner_id` int DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `down_payment` decimal(10,2) DEFAULT NULL,
  `leftover_balance` decimal(10,2) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `reservation_id` (`reservation_id`),
  KEY `owner_id` (`owner_id`),
  CONSTRAINT `owner_foreign` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`owner_id`),
  CONSTRAINT `reserve_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table foodbonda.payment: ~2 rows (approximately)

-- Dumping structure for table foodbonda.reservations
CREATE TABLE IF NOT EXISTS `reservations` (
  `reservation_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `package_id` int DEFAULT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `middle_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `contact` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `event_date` date NOT NULL,
  `delivery_time` time NOT NULL,
  `delivery_address` text COLLATE utf8mb4_general_ci NOT NULL,
  `event_type` enum('wedding','birthday','christening','thanksgiving','fiesta') COLLATE utf8mb4_general_ci NOT NULL,
  `selected_dishes` text COLLATE utf8mb4_general_ci NOT NULL,
  `gcash_receipt_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','approved','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`reservation_id`),
  KEY `customer_id` (`customer_id`),
  KEY `package_id` (`package_id`),
  CONSTRAINT `customer_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  CONSTRAINT `package_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table foodbonda.reservations: ~3 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
