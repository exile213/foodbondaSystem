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

-- Dumping data for table foodbonda.owners: ~0 rows (approximately)
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
	(1, 'PACKAGE 1', 2999.00, 'FISH FILLET, CHOPSUEY, FRIED CHICKEN, PORK BBQ, PORK SOIMAI', NULL),
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
  `amount` decimal(10,2) DEFAULT NULL,
  `receipt_path` varchar(70) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `reservation_id` (`reservation_id`),
  CONSTRAINT `payment_reserve_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table foodbonda.payment: ~0 rows (approximately)

-- Dumping structure for table foodbonda.reservations
CREATE TABLE IF NOT EXISTS `reservations` (
  `reservation_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `middle_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `contact` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `event_date` date NOT NULL,
  `delivery_time` time NOT NULL,
  `delivery_address` text COLLATE utf8mb4_general_ci NOT NULL,
  `event_type` enum('wedding','birthday','christening','thanksgiving','fiesta') COLLATE utf8mb4_general_ci NOT NULL,
  `package_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `selected_dishes` text COLLATE utf8mb4_general_ci NOT NULL,
  `package_price` decimal(10,2) NOT NULL,
  `payment_method` enum('Cash on Delivery','Downpayment 50%') COLLATE utf8mb4_general_ci NOT NULL,
  `gcash_receipt_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`reservation_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table foodbonda.reservations: ~5 rows (approximately)
INSERT INTO `reservations` (`reservation_id`, `customer_id`, `first_name`, `middle_name`, `last_name`, `contact`, `email`, `event_date`, `delivery_time`, `delivery_address`, `event_type`, `package_name`, `selected_dishes`, `package_price`, `payment_method`, `gcash_receipt_path`, `status`, `created_at`, `updated_at`) VALUES
	(6, 2, 'Peter', 'Castel', 'Hinolan', '09464519088', 'cicmonserate.chmsu@gmail.com', '2025-01-18', '00:07:00', '#55 Gomez Extension', 'birthday', 'PACKAGE 3', 'LUMPIA, FISH SWEET AND SOUR, PORK BBQ, SOTANGHON GUISADO, BUFFALO WINGS, VALENCIANA', 2999.00, 'Downpayment 50%', 'uploads/receipts/6782975d26108.png', 'pending', '2025-01-11 16:07:57', '2025-01-11 18:16:57'),
	(8, 2, 'Charles Ivan', 'Colado', 'Monserate', '09464519088', 'cicmonserate.chmsu@gmail.com', '2025-01-15', '00:07:00', '#55 Gomez Extension', 'birthday', 'PACKAGE 3', 'LUMPIA, FISH SWEET AND SOUR, PORK BBQ, SOTANGHON GUISADO, BUFFALO WINGS, VALENCIANA', 2999.00, 'Downpayment 50%', 'uploads/receipts/678298a5d736f.png', 'pending', '2025-01-11 16:13:25', '2025-01-11 16:13:25'),
	(9, 2, 'Charles Ivan', 'Colado', 'Monserate', '67879798798', 'cicmonserate.chmsu@gmail.com', '2025-01-17', '13:08:00', '#27\r\nJOVER ST', 'birthday', 'LECHON PACKAGE A (10-12 KGS)', 'LECHON WITH FREE SAUCE AND DINUGUAN', 7000.00, 'Downpayment 50%', 'uploads/receipts/6782a5aca140a.jpg', 'pending', '2025-01-11 17:09:00', '2025-01-11 17:09:00'),
	(11, 2, 'Peter Kent', 'Colado', 'Monserate', '09464519088', 'cicmonserate.chmsu@gmail.com', '2025-01-21', '02:44:00', 'rwqrewrwre', 'wedding', 'PACKAGE 2', 'LUMPIA, PORK BBQ, CORDON, BIHON GUISADO, FISH FILLET, CHOPSUEY', 2999.00, 'Downpayment 50%', 'uploads/receipts/6782bb85b8d0b.jpg', 'pending', '2025-01-11 18:42:13', '2025-01-11 18:43:07'),
	(12, 1, 'Jean Roshan', 'Magbanua', 'Urbano', '09291530238', 'jrmurbano.chmsu@gmail.com', '2025-01-18', '15:00:00', 'Figueroa St., Barangay III, Silay City', 'thanksgiving', 'PACKAGE 4', 'LUMPIA, FISH FILLET, FRIED WHOLE CHICKEN, PANCIT GUISADO, CHOPSUEY, BUFFALO WINGS', 2999.00, 'Downpayment 50%', 'uploads/receipts/6782bf69ed3aa.png', 'pending', '2025-01-11 18:58:49', '2025-01-11 18:59:28');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
