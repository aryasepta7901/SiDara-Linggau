-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2024 at 04:47 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lv9_sidara`
--

-- --------------------------------------------------------

--
-- Table structure for table `th`
--

CREATE TABLE `th` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `r3` double(8,2) DEFAULT NULL,
  `r4` double(8,2) DEFAULT NULL,
  `r5` double(8,2) DEFAULT NULL,
  `r6` double(8,2) DEFAULT NULL,
  `r7` double(8,2) DEFAULT NULL,
  `r8` double(8,2) DEFAULT NULL,
  `r9` double(8,2) DEFAULT NULL,
  `r10` double(8,2) DEFAULT NULL,
  `r11` double DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` char(18) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanaman_id` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan_dinas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_BPS` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `status_tanaman` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `th`
--

INSERT INTO `th` (`id`, `r3`, `r4`, `r5`, `r6`, `r7`, `r8`, `r9`, `r10`, `r11`, `note`, `user_id`, `tanaman_id`, `entry_id`, `catatan_dinas`, `catatan_BPS`, `status`, `status_tanaman`, `created_at`, `updated_at`) VALUES
(1, 20.00, 5.00, 0.00, 0.00, 5.00, 20.00, 10.00, 0.00, 125000, '', '199811052021041001', '196201001', 'e1a08f64-40e3-4888-9da2-74cfb4f62322', '', '', 6, 0, NULL, NULL),
(2, 20.00, 0.00, 0.00, 0.00, 0.00, 20.00, 0.00, 0.00, 0, '', '199811052021041001', '196201001', 'e1a08f64-40e3-4888-9da2-74cfb4f62321', '', '', 6, 0, NULL, NULL),
(32, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, '', '198408242008012009', '196201025', 'c4a7ae40-d161-4c4d-aa35-6e5e3a5d1db1', NULL, NULL, 6, 2, '2024-03-29 14:54:13', '2024-03-29 14:54:13'),
(33, 0.00, 0.00, 0.00, 0.00, 12.54, 12.54, 0.00, 0.00, 0, '', '198408242008012009', '196201025', 'c4a7ae40-d161-4c4d-aa35-6e5e3a5d1db2', NULL, NULL, 6, 0, '2024-03-29 14:54:13', '2024-03-29 14:54:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `th`
--
ALTER TABLE `th`
  ADD PRIMARY KEY (`id`),
  ADD KEY `th_entry_id_foreign` (`entry_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `th`
--
ALTER TABLE `th`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `th`
--
ALTER TABLE `th`
  ADD CONSTRAINT `th_entry_id_foreign` FOREIGN KEY (`entry_id`) REFERENCES `entryth` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
