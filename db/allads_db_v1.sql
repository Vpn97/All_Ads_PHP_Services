-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2020 at 09:11 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `allads`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_mst`
--

CREATE TABLE `admin_mst` (
  `admin_id` int(8) NOT NULL,
  `uid` int(11) NOT NULL,
  `can_edit_prices` tinyint(1) NOT NULL DEFAULT 0,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `can_post_ads` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ads_mst`
--

CREATE TABLE `ads_mst` (
  `ad_id` int(8) NOT NULL,
  `uid` int(8) NOT NULL,
  `cat_id` int(8) NOT NULL,
  `sub_cat_id` int(8) NOT NULL,
  `ad_text` varchar(5000) NOT NULL,
  `img_url` varchar(512) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `location` varchar(256) NOT NULL,
  `ad_price` double(8,2) NOT NULL,
  `oder_id` int(8) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` int(11) DEFAULT NULL,
  `end_time_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cat_id` int(8) NOT NULL,
  `cat_name` varchar(256) NOT NULL,
  `user_type_id` int(8) NOT NULL,
  `description` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_mst`
--

CREATE TABLE `order_mst` (
  `order_id` int(15) NOT NULL,
  `ad_id` int(8) NOT NULL,
  `uid` int(8) NOT NULL,
  `bank_name` varchar(256) NOT NULL,
  `bank_txn_id` varchar(256) NOT NULL,
  `checksum_hash` varchar(1024) NOT NULL,
  `currency` varchar(16) NOT NULL DEFAULT 'INR',
  `gateway_name` varchar(256) NOT NULL,
  `mid` varchar(512) NOT NULL,
  `payment_mode` varchar(256) NOT NULL,
  `resp_code` varchar(3) NOT NULL,
  `resp_msg` varchar(256) NOT NULL,
  `status` varchar(256) NOT NULL,
  `txn_amount` double(8,2) NOT NULL,
  `txn_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `txn_id` varchar(512) NOT NULL,
  `native_sdk_msg` varchar(512) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_pmt_success` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category` (
  `sub_cat_id` int(8) NOT NULL,
  `sub_cate_name` varchar(512) NOT NULL,
  `description` varchar(512) NOT NULL,
  `cat_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_address_mst`
--

CREATE TABLE `user_address_mst` (
  `address_id` int(8) NOT NULL,
  `uid` int(8) NOT NULL,
  `add_1` varchar(125) NOT NULL,
  `add_2` varchar(125) DEFAULT NULL,
  `city` varchar(125) NOT NULL,
  `state` varchar(125) NOT NULL,
  `country` varchar(125) NOT NULL DEFAULT 'India',
  `pincode` int(6) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_mst`
--

CREATE TABLE `user_mst` (
  `uid` int(8) NOT NULL,
  `mob_no` varchar(12) NOT NULL,
  `user_name` varchar(512) DEFAULT NULL,
  `password_hash` varchar(512) NOT NULL,
  `address_id` int(8) NOT NULL,
  `user_type_id` int(8) NOT NULL,
  `email` varchar(512) DEFAULT NULL,
  `device_id` varchar(512) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `update_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `user_type_id` int(8) NOT NULL,
  `type_name` varchar(256) NOT NULL,
  `description` varchar(512) DEFAULT NULL,
  `type_code` varchar(10) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_mst`
--
ALTER TABLE `admin_mst`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `user_index` (`uid`);

--
-- Indexes for table `ads_mst`
--
ALTER TABLE `ads_mst`
  ADD PRIMARY KEY (`ad_id`),
  ADD KEY `fk_ads_mst_uid` (`uid`),
  ADD KEY `fk_ads_mst_cat_id` (`cat_id`),
  ADD KEY `fk_ads_mst_sub_cat_id` (`sub_cat_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `fk_user_type_id` (`user_type_id`);

--
-- Indexes for table `order_mst`
--
ALTER TABLE `order_mst`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_order_mst_ad_mst_id` (`ad_id`),
  ADD KEY `fk_order_mst_user_mst_uid` (`uid`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`sub_cat_id`),
  ADD KEY `fk_sub_cat_id_cat_id` (`cat_id`);

--
-- Indexes for table `user_address_mst`
--
ALTER TABLE `user_address_mst`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `fk_address_uid_user_mst_uid` (`uid`);

--
-- Indexes for table `user_mst`
--
ALTER TABLE `user_mst`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `mob_no` (`mob_no`),
  ADD KEY `fk_user_mst_address_id_adress_mst_id` (`address_id`),
  ADD KEY `fk_user_mst_user_type_id` (`user_type_id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`user_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_mst`
--
ALTER TABLE `admin_mst`
  MODIFY `admin_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_mst`
--
ALTER TABLE `ads_mst`
  MODIFY `ad_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cat_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_mst`
--
ALTER TABLE `user_mst`
  MODIFY `uid` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `user_type_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_mst`
--
ALTER TABLE `admin_mst`
  ADD CONSTRAINT `fk_admin_mst_uid_user_mst_uid` FOREIGN KEY (`uid`) REFERENCES `user_mst` (`uid`);

--
-- Constraints for table `ads_mst`
--
ALTER TABLE `ads_mst`
  ADD CONSTRAINT `fk_ads_mst_cat_id` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`),
  ADD CONSTRAINT `fk_ads_mst_sub_cat_id` FOREIGN KEY (`sub_cat_id`) REFERENCES `sub_category` (`sub_cat_id`),
  ADD CONSTRAINT `fk_ads_mst_uid` FOREIGN KEY (`uid`) REFERENCES `user_mst` (`uid`);

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `fk_user_type_id` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`user_type_id`);

--
-- Constraints for table `order_mst`
--
ALTER TABLE `order_mst`
  ADD CONSTRAINT `fk_order_mst_ad_mst_id` FOREIGN KEY (`ad_id`) REFERENCES `ads_mst` (`ad_id`),
  ADD CONSTRAINT `fk_order_mst_user_mst_uid` FOREIGN KEY (`uid`) REFERENCES `user_mst` (`uid`);

--
-- Constraints for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD CONSTRAINT `fk_sub_cat_id_cat_id` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`);

--
-- Constraints for table `user_address_mst`
--
ALTER TABLE `user_address_mst`
  ADD CONSTRAINT `fk_address_uid_user_mst_uid` FOREIGN KEY (`uid`) REFERENCES `user_mst` (`uid`);

--
-- Constraints for table `user_mst`
--
ALTER TABLE `user_mst`
  ADD CONSTRAINT `fk_user_mst_address_id_adress_mst_id` FOREIGN KEY (`address_id`) REFERENCES `user_address_mst` (`address_id`),
  ADD CONSTRAINT `fk_user_mst_user_type_id` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`user_type_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
