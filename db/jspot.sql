-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2018 at 06:37 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jspot`
--

-- --------------------------------------------------------

--
-- Table structure for table `apply`
--

CREATE TABLE `apply` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `subject` text NOT NULL,
  `body` text NOT NULL,
  `files` text NOT NULL,
  `created_at` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `timeFrom` time NOT NULL,
  `timeTo` time NOT NULL,
  `dateFrom` date NOT NULL,
  `dateTo` date NOT NULL,
  `location` text NOT NULL,
  `job_tags` text NOT NULL,
  `age_group` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `description`, `timeFrom`, `timeTo`, `dateFrom`, `dateTo`, `location`, `job_tags`, `age_group`, `created_by`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Some work', 'This work is about something lorem ipsum dolor sit amet.', '09:00:00', '18:00:00', '2018-01-01', '2018-08-31', '[\"Manila\"]', '[\"programming\"]', '{\"from\":25,\"to\":34}', 2, 1519091218, 1519234645, -1),
(2, 'Test', '', '00:01:00', '00:59:00', '2018-01-13', '2018-02-01', '[\"Mandaluyong\",\"Manila\"]', '[\"photography\",\"animation\"]', '{\"from\":18,\"to\":24}', 2, 1519094267, 1519237596, 2),
(3, 'Test', 'This work is about something lorem ipsum dolor sit amet.', '12:00:00', '23:00:00', '2018-01-01', '2018-02-11', '[\"Manila\",\"Quezon\"]', '[\"programming\"]', '{\"from\":18,\"to\":24}', 2, 1519095062, 1519107426, 1),
(4, 'Sample', 'Some lorem ipsum', '00:00:00', '12:00:00', '2018-01-01', '2018-03-20', '[\"Makati\",\"Manila\",\"Quezon\",\"Mandaluyong\"]', '[\"art\",\"programming\"]', '{\"from\":25,\"to\":34}', 2, 1519102595, 1519234560, 1),
(5, 'New job opening!', 'Wooo nice', '12:00:00', '18:30:00', '2018-01-01', '2018-03-02', '[\"Manila\",\"Quezon\"]', '[\"programming\",\"art\",\"animation\",\"photography\"]', '{\"from\":18,\"to\":24}', 2, 1519234849, 1519234849, 1);

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

CREATE TABLE `places` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `places`
--

INSERT INTO `places` (`id`, `name`, `status`) VALUES
(1, 'Manila', 1),
(2, 'Quezon', 1),
(3, 'Mandaluyong', 1),
(4, 'Makati', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `status`) VALUES
(1, 'programming', 1),
(2, 'animation', 1),
(3, 'photography', 1),
(4, 'art', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fname` varchar(128) NOT NULL,
  `lname` varchar(128) NOT NULL,
  `bio` text NOT NULL,
  `birthdate` date NOT NULL,
  `img_src` text NOT NULL,
  `job_tags` text NOT NULL,
  `places` text NOT NULL,
  `socials` text NOT NULL,
  `email` varchar(128) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `contact` varchar(64) NOT NULL,
  `verification_code` varchar(32) NOT NULL,
  `reset_code` varchar(32) NOT NULL,
  `reset_expiration` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `settings` text NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `password`, `fname`, `lname`, `bio`, `birthdate`, `img_src`, `job_tags`, `places`, `socials`, `email`, `type`, `contact`, `verification_code`, `reset_code`, `reset_expiration`, `created_at`, `updated_at`, `settings`, `status`) VALUES
(1, '$2y$10$d/46JkJivDqU9Bh7v0f6YOi4C5nXZDQEngMaE4OEl2AGm7ykViqHC', 'Charlyn', 'Ann', 'My short bio here.', '1998-09-26', '', '[\"programming\"]', '[\"Manila\"]', '[\"www.facebook.com\"]', 'test@email.com', 2, '09876543210', '', '', 0, 1518017533, 1518266763, '{\"dark\":\"false\"}', 1),
(2, '$2y$10$o92osqp/bG8JBOvlqVKj0.KaETjrxaiSWwXh7Luw6Gw6MIPQAfnV2', 'Jefferson', 'Rylee', '', '1999-03-13', 'rylee.jpg', '[\"programming\"]', '[\"Manila\"]', '[\"twitter.com\\/Arnesfield\"]', 'rylee@email.com', 3, '09876543210', '', '', 0, 1519017628, 1519041879, '\"\"', 1),
(3, '$2y$10$kzXRXhZLoDNYd1jottSyu.FLHW9OfuOVLnLLUSH3g8/4vprJlDbIW', 'Cayle', 'Anielle', 'Some bio', '1998-06-19', '', '[\"art\",\"photography\",\"animation\"]', '[\"Manila\"]', '[\"facebook.com\"]', 'cayle@email.com', 4, '09876543210', '', '', 0, 1519215119, 1519238897, '\"\"', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apply`
--
ALTER TABLE `apply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apply`
--
ALTER TABLE `apply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
