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
-- Table structure for table `entrytbf`
--

CREATE TABLE `entrytbf` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TW` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kec_id` char(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `entrytbf`
--

INSERT INTO `entrytbf` (`id`, `TW`, `tahun`, `kec_id`, `status`, `created_at`, `updated_at`) VALUES
('3f29065b-7893-44e9-b5cd-dc60b43c5e21', '04', '2023', '1674012', 6, NULL, '2024-03-25 02:45:07'),
('3f29065b-7893-44e9-b5cd-dc60b43c5e22', '03', '2023', '1674012', 6, NULL, NULL),
('4e028e62-b477-4a1c-91c2-8100a67b0a61', '04', '2023', '1674042', 6, NULL, NULL),
('4e028e62-b477-4a1c-91c2-8100a67b0a62', '03', '2023', '1674042', 6, NULL, NULL),
('8b4fb108-19b7-45f7-b25b-9e71a5f348a1', '04', '2023', '1674041', 6, NULL, NULL),
('8b4fb108-19b7-45f7-b25b-9e71a5f348a2', '03', '2023', '1674041', 6, NULL, NULL),
('8e372d8e-2aef-4cd7-bd22-0e1f83f1e0f1', '04', '2023', '1674021', 6, NULL, NULL),
('8e372d8e-2aef-4cd7-bd22-0e1f83f1e0f2', '03', '2023', '1674021', 6, NULL, NULL),
('9977b6ab-5e0f-4e06-b8e2-41c6a2c947f1', '04', '2023', '1674031', 6, NULL, NULL),
('9977b6ab-5e0f-4e06-b8e2-41c6a2c947f2', '03', '2023', '1674031', 6, NULL, NULL),
('c4a7ae40-d161-4c4d-aa35-6e5e3a5d1db1', '04', '2023', '1674011', 6, NULL, NULL),
('c4a7ae40-d161-4c4d-aa35-6e5e3a5d1db2', '03', '2023', '1674011', 6, NULL, NULL),
('e1a08f64-40e3-4888-9da2-74cfb4f62321', '04', '2023', '1674022', 6, NULL, NULL),
('e1a08f64-40e3-4888-9da2-74cfb4f62322', '03', '2023', '1674022', 6, NULL, NULL),
('f9732dd7-0b90-4970-ba82-0dfc9c7f87e1', '04', '2023', '1674032', 6, NULL, NULL),
('f9732dd7-0b90-4970-ba82-0dfc9c7f87e2', '03', '2023', '1674032', 6, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `entrytbf`
--
ALTER TABLE `entrytbf`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entrytbf_kec_id_foreign` (`kec_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `entrytbf`
--
ALTER TABLE `entrytbf`
  ADD CONSTRAINT `entrytbf_kec_id_foreign` FOREIGN KEY (`kec_id`) REFERENCES `kecamatan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
