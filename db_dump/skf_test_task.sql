-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 03, 2022 at 10:52 PM
-- Server version: 5.7.29
-- PHP Version: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skf_test_task`
--

-- --------------------------------------------------------

--
-- Table structure for table `skf_api_ip_limits`
--

CREATE TABLE `skf_api_ip_limits` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(225) DEFAULT NULL,
  `allowance` int(11) NOT NULL DEFAULT '3',
  `timestamp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `skf_api_ip_limits`
--

INSERT INTO `skf_api_ip_limits` (`id`, `user_id`, `action`, `allowance`, `timestamp`) VALUES
(1, 1, 'construct', 9, 1649015288);

-- --------------------------------------------------------

--
-- Table structure for table `skf_api_v1_log`
--

CREATE TABLE `skf_api_v1_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `function` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - ошибка, 1 - успешный запрос',
  `ip` varchar(225) DEFAULT NULL,
  `params` json DEFAULT NULL,
  `response` json DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `skf_api_v1_log`
--

INSERT INTO `skf_api_v1_log` (`id`, `user_id`, `function`, `status`, `ip`, `params`, `response`, `timestamp`) VALUES
(1, 1, 'v1/food/construct', 0, '127.0.0.1', '\"{\\\"code\\\":\\\"dccii4i\\\"}\"', '\"\\\"{\\\\\\\"code\\\\\\\":[\\\\\\\"\\\\\\\\u041a\\\\\\\\u043e\\\\\\\\u0434 4 \\\\\\\\u0432 \\\\\\\\u0442\\\\\\\\u0430\\\\\\\\u0431\\\\\\\\u043b\\\\\\\\u0438\\\\\\\\u0446\\\\\\\\u0435 \\\\\\\\u043e\\\\\\\\u0442\\\\\\\\u0441\\\\\\\\u0443\\\\\\\\u0442\\\\\\\\u0432\\\\\\\\u0443\\\\\\\\u0435\\\\\\\\u0442.\\\\\\\"]}\\\"\"', '2022-04-03 19:31:35');

-- --------------------------------------------------------

--
-- Table structure for table `skf_api_v1_user`
--

CREATE TABLE `skf_api_v1_user` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `key` varchar(32) NOT NULL,
  `rate_limit` smallint(5) UNSIGNED NOT NULL DEFAULT '10',
  `login` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `skf_api_v1_user`
--

INSERT INTO `skf_api_v1_user` (`id`, `status`, `key`, `rate_limit`, `login`, `timestamp`, `timestamp_update`) VALUES
(1, 1, 'FOJShnewogf743fhdscvn3w4cs', 10, 'adduser', '2022-02-21 04:53:27', '2022-02-21 04:53:27');

-- --------------------------------------------------------

--
-- Table structure for table `skf_ingredient`
--

CREATE TABLE `skf_ingredient` (
  `id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(19,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `skf_ingredient`
--

INSERT INTO `skf_ingredient` (`id`, `type_id`, `title`, `price`) VALUES
(1, 1, 'Тонкое тесто', '100.00'),
(2, 1, 'Пышное тесто', '110.00'),
(3, 1, 'Ржаное тесто', '150.00'),
(4, 2, 'Моцарелла', '50.00'),
(5, 2, 'Рикотта', '70.00'),
(6, 3, 'Колбаса', '30.00'),
(7, 3, 'Ветчина', '35.00'),
(8, 3, 'Грибы', '50.00'),
(9, 3, 'Томаты', '10.00');

-- --------------------------------------------------------

--
-- Table structure for table `skf_ingredient_type`
--

CREATE TABLE `skf_ingredient_type` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `code` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `skf_ingredient_type`
--

INSERT INTO `skf_ingredient_type` (`id`, `title`, `code`) VALUES
(1, 'Тесто', 'd'),
(2, 'Сыр', 'c'),
(3, 'Начинка', 'i');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `skf_api_ip_limits`
--
ALTER TABLE `skf_api_ip_limits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `skf_api_v1_log`
--
ALTER TABLE `skf_api_v1_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `function` (`function`),
  ADD KEY `status` (`status`),
  ADD KEY `timestamp` (`timestamp`),
  ADD KEY `ip` (`ip`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `skf_api_v1_user`
--
ALTER TABLE `skf_api_v1_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`key`(15)) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `request_limit_per_second` (`rate_limit`);

--
-- Indexes for table `skf_ingredient`
--
ALTER TABLE `skf_ingredient`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_ingredient_type_id` (`type_id`);

--
-- Indexes for table `skf_ingredient_type`
--
ALTER TABLE `skf_ingredient_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `skf_api_ip_limits`
--
ALTER TABLE `skf_api_ip_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `skf_api_v1_log`
--
ALTER TABLE `skf_api_v1_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `skf_api_v1_user`
--
ALTER TABLE `skf_api_v1_user`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `skf_ingredient`
--
ALTER TABLE `skf_ingredient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `skf_ingredient_type`
--
ALTER TABLE `skf_ingredient_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `skf_ingredient`
--
ALTER TABLE `skf_ingredient`
  ADD CONSTRAINT `FK_ingredient_type_id` FOREIGN KEY (`type_id`) REFERENCES `skf_ingredient_type` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
