-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 16, 2021 at 06:00 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jideola`
--

-- --------------------------------------------------------

--
-- Table structure for table `jx1_sessions`
--

CREATE TABLE `jx1_sessions` (
  `s_id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `session_start_time` varchar(20) NOT NULL,
  `session_last_access` timestamp NOT NULL DEFAULT current_timestamp(),
  `session_expires` int(11) NOT NULL,
  `session_user_id` int(11) NOT NULL,
  `session_ip` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jx1_sessions`
--

INSERT INTO `jx1_sessions` (`s_id`, `session_id`, `session_start_time`, `session_last_access`, `session_expires`, `session_user_id`, `session_ip`) VALUES
(1, '170d943c616fc60292d385c56d4fbcc0', '2021-04-16 16:46:14', '2021-04-16 15:46:14', 1618602974, 1, '::1'),
(2, '4f6c7155b49a55c39a0e36a537f64d84', '2021-04-16 16:46:54', '2021-04-16 15:46:54', 1618603014, 1, '::1');

-- --------------------------------------------------------

--
-- Table structure for table `jx1_users`
--

CREATE TABLE `jx1_users` (
  `id` int(11) NOT NULL,
  `username` varchar(10) NOT NULL,
  `names` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` longtext NOT NULL,
  `address` varchar(100) NOT NULL,
  `latest_login` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `login_count` int(11) NOT NULL DEFAULT 0,
  `blocked_till` int(11) NOT NULL DEFAULT 0,
  `login_fail` int(11) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jx1_users`
--

INSERT INTO `jx1_users` (`id`, `username`, `names`, `email`, `phone`, `password`, `address`, `latest_login`, `last_login`, `login_count`, `blocked_till`, `login_fail`, `date_created`) VALUES
(1, 'Davido', 'Olaleye Olajide Sunday', 'davido@yahoo.com', '+2348085100961', '$2y$10$P7FAQldDR3duG.tot7XgBuImHGchcXJnYKaJUb4Z8TEsClob6CXJy', '33A Abba Johnson, Akora Villa', '2021-04-16 16:46:54', '2021-04-16 16:46:14', 6, 1618589548, 0, '2021-04-16 16:41:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jx1_sessions`
--
ALTER TABLE `jx1_sessions`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `jx1_users`
--
ALTER TABLE `jx1_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jx1_sessions`
--
ALTER TABLE `jx1_sessions`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jx1_users`
--
ALTER TABLE `jx1_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
