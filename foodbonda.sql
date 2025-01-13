-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 13, 2025 at 08:22 AM
-- Server version: 8.0.39
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodbonda`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `middle_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `first_name`, `middle_name`, `last_name`, `email`, `password`, `created_at`, `last_login`, `status`) VALUES
(1, 'Jean Roshan', 'Magbanua', 'Urbano', 'jrmurbano.chmsu@gmail.com', '$2y$10$csOFQB24.OjTfnj35rft/.OwwWdjsbD/337F4xiZTSURLpBSyrgdC', '2025-01-11 11:38:04', NULL, 'active'),
(2, 'Charles Ivan', 'Colado', 'Monserate', 'cicmonserate.chmsu@gmail.com', '$2y$10$ahGEehvswWvLT.AQKNkAKeA1j5DiItzenwhuTGA4KRXQpoO/LuNNS', '2025-01-11 13:33:28', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

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
(11, 2, 'Your reservation has been approved.', 0, '2025-01-13 07:49:15');

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `owner_id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`owner_id`, `username`, `password`, `first_name`, `last_name`, `email`) VALUES
(2, 'jrmurbano', '$2y$10$qtDtPICQRSA99/SH3aZqh.Sbwcn1s8SZb6/rdOgxVkRhtRAKt7Ko2', 'Jean Roshan', 'Urbano', 'roshanurbano@gmail.com'),
(3, 'icmonserate', '$2y$10$rAO/1kS/93e6v3QPvIoMQugkQtzjEJGUyILsQeqtOOypPAhavGGyu', 'Charles Ivan', 'Monserate', 'icmonserate@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `package_id` int NOT NULL,
  `package_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `included_dishes` text COLLATE utf8mb4_general_ci NOT NULL,
  `additional_dishes_limit` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int NOT NULL,
  `reservation_id` int DEFAULT NULL,
  `owner_id` int DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `reservation_id`, `owner_id`, `amount`, `payment_date`, `created_at`) VALUES
(3, 13, 3, 1499.50, '2025-01-13', '2025-01-13 07:27:00'),
(4, 14, 3, 1499.50, '2025-01-13', '2025-01-13 07:30:07'),
(5, 13, 3, 1499.50, '2025-01-13', '2025-01-13 07:33:15'),
(6, 14, 3, 1499.50, '2025-01-13', '2025-01-13 07:44:51'),
(7, 15, 3, 3500.00, '2025-01-13', '2025-01-13 07:52:13');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int NOT NULL,
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
  `package_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `selected_dishes` text COLLATE utf8mb4_general_ci NOT NULL,
  `package_price` decimal(10,2) NOT NULL,
  `payment_method` enum('Cash on Delivery','Downpayment 50%') COLLATE utf8mb4_general_ci NOT NULL,
  `gcash_receipt_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','approved','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `customer_id`, `package_id`, `first_name`, `middle_name`, `last_name`, `contact`, `email`, `event_date`, `delivery_time`, `delivery_address`, `event_type`, `package_name`, `selected_dishes`, `package_price`, `payment_method`, `gcash_receipt_path`, `status`, `created_at`, `updated_at`) VALUES
(13, 2, NULL, 'Charles Ivan', 'Colado', 'Monserate', '09464519088', 'cicmonserate.chmsu@gmail.com', '2025-01-16', '22:13:00', '#27\r\nJOVER ST', 'christening', 'PACKAGE 2', 'LUMPIA, PORK BBQ, CORDON, BIHON GUISADO, FISH FILLET, CHOPSUEY', 2999.00, 'Downpayment 50%', 'uploads/receipts/6783cdb9a30ab.png', 'completed', '2025-01-12 14:12:09', '2025-01-13 07:33:17'),
(14, 2, NULL, 'Charles Ivan', 'Colado', 'Monserate', '38247924793', 'cicmonserate.chmsu@gmail.com', '2025-01-17', '14:09:00', 'eiewouejf', 'thanksgiving', 'PACKAGE 2', 'LUMPIA, PORK BBQ, CORDON, BIHON GUISADO, FISH FILLET, CHOPSUEY', 2999.00, 'Downpayment 50%', 'uploads/receipts/6784ade6beaa7.png', 'completed', '2025-01-13 06:08:38', '2025-01-13 07:44:52'),
(15, 2, NULL, 'Charles Ivan', 'Colado', 'Monserate', '23424234223', 'cicmonserate.chmsu@gmail.com', '2025-02-01', '16:11:00', 'wqdqwrdqwqdwq', 'thanksgiving', 'LECHON PACKAGE A (10-12 KGS)', 'LECHON WITH FREE SAUCE AND DINUGUAN', 7000.00, 'Downpayment 50%', 'uploads/receipts/6784aeb377228.png', 'completed', '2025-01-13 06:12:03', '2025-01-13 07:52:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`owner_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `package_id` (`package_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `owners`
--
ALTER TABLE `owners`
  MODIFY `owner_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `package_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `owneer_foreign` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`owner_id`),
  ADD CONSTRAINT `payment_reserve_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `package_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`),
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
