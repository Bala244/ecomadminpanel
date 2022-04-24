-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2022 at 01:05 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `packurs`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `parent_id`, `level`, `name`, `description`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(4, 0, 0, 'Parent 1', 'sdsad', 1, '2022-04-23 11:10:16', NULL, '2022-04-23 11:10:16', NULL),
(5, 0, 0, 'Parent 2', 'sdasd', 1, '2022-04-23 11:12:22', NULL, '2022-04-23 11:12:22', NULL),
(7, 4, 1, 'SubParent 1', 'dsfds', 1, '2022-04-23 11:13:10', NULL, '2022-04-23 11:13:10', NULL),
(10, 4, 1, 'Parent 1', '', 1, '2022-04-23 11:19:33', NULL, '2022-04-23 11:19:33', NULL),
(11, 7, 2, 'Parent 1', '', 1, '2022-04-23 12:36:11', NULL, '2022-04-23 12:36:11', NULL),
(12, 4, 1, 'BNK\'s Portfolio', 'sdaasda', 1, '2022-04-23 12:52:46', NULL, '2022-04-23 12:52:46', NULL),
(13, 11, 2, 'sdfdf', '', 1, '2022-04-23 12:53:41', NULL, '2022-04-23 12:53:41', NULL),
(14, 12, 2, 'sdfdf', '', 1, '2022-04-23 12:53:41', NULL, '2022-04-23 12:53:41', NULL),
(15, 7, 2, 'Parent 2', '', 1, '2022-04-23 12:36:11', NULL, '2022-04-23 12:36:11', NULL),
(16, 7, 1, 'new parent', 'sdsfsdf', 1, '2022-04-23 16:23:07', NULL, '2022-04-23 16:23:07', NULL),
(17, 5, 0, 'ne cate', 'asdsadsa', 1, '2022-04-23 16:29:39', NULL, '2022-04-23 16:29:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `admin_type` varchar(20) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_image` varchar(250) DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `admin_type`, `mobile_no`, `gender`, `address`, `profile_image`, `is_active`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'superadmin', 'admin@gmail.com', '$2y$10$LH3fec6f9a8peZW/Q24EFuevmv2y9W3Q0an2KDM4.fATMNF8QlKNK', NULL, NULL, 'male', 'Chennai', NULL, 1, '2022-04-17 11:16:33', 1, '2022-04-17 11:16:33', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
