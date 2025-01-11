-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2025 at 05:20 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

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
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `first_name`, `middle_name`, `last_name`, `email`, `password`, `created_at`, `last_login`, `status`) VALUES
(1, 'Jean Roshan', 'Magbanua', 'Urbano', 'jrmurbano.chmsu@gmail.com', '$2y$10$csOFQB24.OjTfnj35rft/.OwwWdjsbD/337F4xiZTSURLpBSyrgdC', '2025-01-11 11:38:04', NULL, 'active'),
(2, 'Charles Ivan', 'Colado', 'Monserate', 'cicmonserate.chmsu@gmail.com', '$2y$10$ahGEehvswWvLT.AQKNkAKeA1j5DiItzenwhuTGA4KRXQpoO/LuNNS', '2025-01-11 13:33:28', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `package_id` int(11) NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `included_dishes` text NOT NULL,
  `additional_dishes_limit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `delivery_time` time NOT NULL,
  `delivery_address` text NOT NULL,
  `event_type` enum('wedding','birthday','christening','thanksgiving','fiesta') NOT NULL,
  `package_name` varchar(100) NOT NULL,
  `selected_dishes` text NOT NULL,
  `package_price` decimal(10,2) NOT NULL,
  `payment_method` enum('Cash on Delivery','Downpayment 50%') NOT NULL,
  `gcash_receipt_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `customer_id`, `first_name`, `middle_name`, `last_name`, `contact`, `email`, `event_date`, `delivery_time`, `delivery_address`, `event_type`, `package_name`, `selected_dishes`, `package_price`, `payment_method`, `gcash_receipt_path`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Jean Roshan', 'Magbanua', 'Urbano', '09291530238', 'jrmurbano.chmsu@gmail.com', '2025-01-25', '11:45:00', 'Figueroa St., Barangay III, Silay City', 'birthday', 'PACKAGE 4', 'LUMPIA, FISH FILLET, FRIED WHOLE CHICKEN, PANCIT GUISADO, CHOPSUEY, BUFFALO WINGS', 2999.00, 'Downpayment 50%', 'uploads/receipts/6782922007262.png', 'pending', '2025-01-11 15:45:36', '2025-01-11 15:45:36'),
(2, 2, 'Charles Ivan', 'Colado', 'Monserate', '09464519088', 'cicmonserate.chmsu@gmail.com', '2025-01-25', '11:56:00', '#55 Gomez Extension', 'wedding', 'PACKAGE 5', 'LUMPIA, SOTANGHON, CARBONARA, PORK BBQ, SOIMAI, FISH FILLET, PORK STEAK', 2999.00, 'Downpayment 50%', 'uploads/receipts/678294ba6a264.png', 'pending', '2025-01-11 15:56:42', '2025-01-11 15:56:42'),
(3, 2, 'Charles Ivan', 'Colado', 'Monserate', '09464519088', 'cicmonserate.chmsu@gmail.com', '2025-01-17', '00:00:00', '#55 Gomez Extension', 'wedding', 'LECHON PACKAGE A (10-12 KGS)', 'LECHON WITH FREE SAUCE AND DINUGUAN', 7000.00, 'Downpayment 50%', 'uploads/receipts/678295d014d91.png', 'pending', '2025-01-11 16:01:20', '2025-01-11 16:01:20'),
(4, 2, 'Charles Ivan', 'Colado', 'Monserate', '09464519088', 'cicmonserate.chmsu@gmail.com', '2025-01-17', '00:03:00', '#55 Gomez Extension', 'thanksgiving', 'LECHON PACKAGE C', 'LECHON WITH FREE SAUCE AND DINUGUAN, PLUS ADDITIONAL DISHES: Lumpia shanghai-6opcs, Fish sweet and sour, Chicken sisig, Chicken pastel', 9500.00, 'Downpayment 50%', 'uploads/receipts/6782962344ceb.png', 'pending', '2025-01-11 16:02:43', '2025-01-11 16:02:43'),
(5, 2, 'Charles Ivan', 'Colado', 'Monserate', '09291530238', 'cicmonserate.chmsu@gmail.com', '2025-01-23', '00:05:00', 'Figueroa St., Barangay III, Silay City', 'thanksgiving', 'PACKAGE 2', 'LUMPIA, PORK BBQ, CORDON, BIHON GUISADO, FISH FILLET, CHOPSUEY', 2999.00, 'Downpayment 50%', 'uploads/receipts/678296f7728f1.png', 'pending', '2025-01-11 16:06:15', '2025-01-11 16:06:15'),
(6, 2, 'Charles Ivan', 'Colado', 'Monserate', '09464519088', 'cicmonserate.chmsu@gmail.com', '2025-01-15', '00:07:00', '#55 Gomez Extension', 'birthday', 'PACKAGE 3', 'LUMPIA, FISH SWEET AND SOUR, PORK BBQ, SOTANGHON GUISADO, BUFFALO WINGS, VALENCIANA', 2999.00, 'Downpayment 50%', 'uploads/receipts/6782975d26108.png', 'pending', '2025-01-11 16:07:57', '2025-01-11 16:07:57'),
(7, 2, 'Charles Ivan', 'Colado', 'Monserate', '09464519088', 'cicmonserate.chmsu@gmail.com', '2025-01-15', '00:07:00', '#55 Gomez Extension', 'birthday', 'PACKAGE 1', 'FISH FILLET, CHOPSUEY, FRIED CHICKEN, PORK BBQ, PORK SOIMAI', 2999.00, 'Downpayment 50%', 'uploads/receipts/678297f433523.png', 'pending', '2025-01-11 16:10:28', '2025-01-11 16:10:28'),
(8, 2, 'Charles Ivan', 'Colado', 'Monserate', '09464519088', 'cicmonserate.chmsu@gmail.com', '2025-01-15', '00:07:00', '#55 Gomez Extension', 'birthday', 'PACKAGE 3', 'LUMPIA, FISH SWEET AND SOUR, PORK BBQ, SOTANGHON GUISADO, BUFFALO WINGS, VALENCIANA', 2999.00, 'Downpayment 50%', 'uploads/receipts/678298a5d736f.png', 'pending', '2025-01-11 16:13:25', '2025-01-11 16:13:25');

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
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
