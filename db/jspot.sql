-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2018 at 02:22 PM
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
  `interview_date` int(11) NOT NULL,
  `interview_time` time NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `apply`
--

INSERT INTO `apply` (`id`, `user_id`, `job_id`, `subject`, `body`, `files`, `interview_date`, `interview_time`, `created_at`, `updated_at`, `status`) VALUES
(1, 3, 5, 'test', 'test', '[]', 1522166400, '23:00:00', 1520244018, 1520244018, 1),
(2, 3, 3, 'Some application', 'Hello I would like to apply for this job lorem ipsum dolor sit amet for testing.', '[\"F_1520416460.pdf\"]', 1520956800, '19:00:00', 1520416460, 1520420218, 2),
(3, 3, 7, 'Yehey apply', 'Nice', '[]', 0, '00:00:00', 1521132556, 1521132556, 1);

-- --------------------------------------------------------

--
-- Table structure for table `boosts`
--

CREATE TABLE `boosts` (
  `id` int(11) NOT NULL,
  `ref_id` int(11) NOT NULL,
  `tbl_name` varchar(16) NOT NULL,
  `created_at` int(11) NOT NULL,
  `ends_at` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `boosts`
--

INSERT INTO `boosts` (`id`, `ref_id`, `tbl_name`, `created_at`, `ends_at`, `status`) VALUES
(1, 2, 'users', 1520940911, 1521027311, 1),
(2, 6, 'jobs', 1520940915, 1521027315, 1),
(3, 2, 'users', 1521045937, 1521132337, 1),
(4, 7, 'users', 1521129288, 1521215688, 1),
(5, 7, 'jobs', 1521132494, 1521218894, 1),
(6, 7, 'jobs', 1521132498, 1521218898, 1);

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
  `payment` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `description`, `timeFrom`, `timeTo`, `dateFrom`, `dateTo`, `location`, `job_tags`, `age_group`, `payment`, `created_by`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Some work', 'This work is about something lorem ipsum dolor sit amet.', '09:00:00', '18:00:00', '2018-01-01', '2018-08-31', '[\"Manila\"]', '[\"programming\"]', '{\"from\":25,\"to\":34}', 'P200/hr', 2, 1519091218, 1519234645, -1),
(2, 'Test', '', '00:01:00', '00:59:00', '2018-01-13', '2018-02-01', '[\"Mandaluyong\",\"Manila\"]', '[\"photography\",\"animation\"]', '{\"from\":18,\"to\":24}', 'P200/hr', 2, 1519094267, 1519237596, 1),
(3, 'Test', 'This work is about something lorem ipsum dolor sit amet.', '12:00:00', '23:00:00', '2018-01-01', '2018-02-11', '[\"Manila\",\"Quezon\"]', '[\"programming\"]', '{\"from\":18,\"to\":24}', 'P500-1000/hr', 2, 1519095062, 1519107426, 1),
(4, 'Sample', 'Some lorem ipsum', '00:00:00', '12:00:00', '2018-01-01', '2018-03-20', '[\"Makati\",\"Manila\",\"Quezon\",\"Mandaluyong\"]', '[\"art\",\"programming\"]', '{\"from\":25,\"to\":34}', 'P200/hr', 2, 1519102595, 1519234560, 1),
(5, 'New job opening!', 'Wooo nice', '12:00:00', '18:30:00', '2018-01-01', '2018-03-02', '[\"Manila\",\"Quezon\"]', '[\"programming\",\"art\",\"animation\",\"photography\"]', '{\"from\":18,\"to\":24}', 'P100/hr', 2, 1519234849, 1520517112, 1),
(6, 'Waw job', 'Naks', '09:30:00', '18:30:00', '2018-04-01', '2018-03-31', '[\"Zamboanga\"]', '[\"music\"]', '{\"from\":25,\"to\":34}', 'PHP500/hr', 2, 1520933974, 1520933974, 1),
(7, 'Nice job', 'Yehey', '09:45:00', '18:30:00', '2018-05-31', '2018-06-10', '[\"Laguna\"]', '[\"babysitting\",\"care\"]', '{\"from\":18,\"to\":24}', 'P100/hr', 7, 1521132476, 1521132476, 1);

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
(4, 'Makati', 1),
(5, 'Zamboanga', 1),
(6, 'Pasig', 1),
(7, 'Laguna', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `created_at` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `reviewer_id`, `body`, `rating`, `created_at`, `status`) VALUES
(1, 3, 2, 'New message here', 3, 1520951429, 1),
(2, 3, 2, 'Messageee', 3, 1520961429, 1),
(3, 2, 3, 'Messagee hehe', 4, 1520955410, 1),
(4, 2, 3, 'New messagee lol', 5, 1521012687, 1),
(5, 2, 3, 'Test', 4, 1521017583, 1),
(6, 2, 7, 'huwaw', 3, 1521128523, 1),
(7, 7, 2, 'Nakow', 5, 1521129331, 1),
(8, 3, 7, 'Huwaw', 5, 1521130977, 0),
(9, 3, 7, 'Naks namern', 5, 1521131047, 1),
(10, 7, 3, 'weh', 1, 1521131070, 0),
(11, 7, 3, 'weh kay sherry ka na lang ge', 1, 1521132248, 1);

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
(4, 'art', 1),
(5, 'music', 1),
(6, 'singing', 1),
(7, 'babysitting', 1),
(8, 'care', 1);

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
(2, '$2y$10$E30rXz/U.Ze/J/.goVOuouNXSTz5KVT6mpxjzSJpOSjpC1o8xW/a6', 'Jefferson', 'Rylee', 'Some bio heree lol', '1999-03-13', 'F_1521100100.jpeg', '[\"programming\",\"music\"]', '[\"Manila\"]', '[\"twitter.com/Arnesfield\"]', 'rylee@email.com', 3, '09876543211', '', '', 0, 1519017628, 1521201402, '{}', 1),
(3, '$2y$10$kzXRXhZLoDNYd1jottSyu.FLHW9OfuOVLnLLUSH3g8/4vprJlDbIW', 'Cayle', 'Anielle', 'Some bio', '1998-06-19', '', '[\"art\",\"photography\",\"animation\",\"babysitting\"]', '[\"Manila\"]', '[\"facebook.com\"]', 'cayle@email.com', 4, '09876543210', '', '', 0, 1519215119, 1521132534, '\"\"', 1),
(7, '$2y$10$SRv7zBfeLhw8ziDJZVbvT.7YWMI4x4fXW5Altny0N7oxcTIB5WHJ6', 'Charlyn', 'Ann', 'lol', '1998-09-26', 'F_1521128443.JPG', '[\"music\",\"singing\"]', '[\"Manila\",\"Laguna\"]', '[\"facebook.com/Arnesfield\"]', 'charlyn@email.com', 3, '098764321', '', '', 0, 1521127350, 1521132365, '{}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `views`
--

CREATE TABLE `views` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `viewer_id` int(11) NOT NULL,
  `viewed_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `views`
--

INSERT INTO `views` (`id`, `user_id`, `job_id`, `viewer_id`, `viewed_at`) VALUES
(1, 3, 0, 2, 1520843261),
(2, 3, 0, 2, 1520846744),
(3, 3, 0, 2, 1520852746),
(4, 3, 0, 2, 1520852750),
(5, 2, 0, 3, 1520865745),
(6, 3, 0, 2, 1520866579),
(7, 3, 0, 2, 1520870202),
(8, 0, 5, 3, 1520870324),
(9, 0, 2, 3, 1520872556),
(10, 0, 3, 3, 1520872560),
(11, 2, 0, 3, 1520878189),
(12, 3, 0, 2, 1520879219),
(13, 0, 3, 3, 1520881199),
(14, 2, 0, 3, 1520881249),
(15, 0, 5, 3, 1520881261),
(16, 0, 2, 3, 1520881280),
(17, 3, 0, 2, 1520881592),
(18, 0, 6, 3, 1520934007),
(19, 3, 0, 2, 1520938542),
(20, 0, 5, 3, 1520938572),
(21, 2, 0, 3, 1520938580),
(22, 2, 0, 3, 1520938619),
(23, 0, 2, 3, 1520938630),
(24, 0, 6, 3, 1520938634),
(25, 3, 0, 2, 1520943483),
(26, 3, 0, 2, 1520943595),
(27, 3, 0, 2, 1520943628),
(28, 3, 0, 2, 1520945049),
(29, 3, 0, 2, 1520945092),
(30, 3, 0, 2, 1520945098),
(31, 3, 0, 2, 1520945159),
(32, 3, 0, 2, 1520945377),
(33, 0, 5, 3, 1520946457),
(34, 2, 0, 3, 1520946471),
(35, 2, 0, 3, 1520946962),
(36, 2, 0, 3, 1520947005),
(37, 2, 0, 3, 1520947069),
(38, 0, 5, 3, 1520947430),
(39, 3, 0, 2, 1520947698),
(40, 3, 0, 2, 1520947911),
(41, 3, 0, 2, 1520948430),
(42, 3, 0, 2, 1520948437),
(43, 3, 0, 2, 1520951332),
(44, 3, 0, 2, 1520951651),
(45, 3, 0, 2, 1520951829),
(46, 3, 0, 2, 1520951833),
(47, 0, 5, 3, 1521004049),
(48, 0, 5, 3, 1521004064),
(49, 0, 5, 3, 1521004092),
(50, 0, 3, 3, 1521004135),
(51, 0, 5, 3, 1521004190),
(52, 0, 3, 3, 1521004211),
(53, 2, 0, 3, 1521006894),
(54, 2, 0, 3, 1521007238),
(55, 2, 0, 3, 1521007444),
(56, 2, 0, 3, 1521009743),
(57, 2, 0, 3, 1521010175),
(58, 2, 0, 3, 1521010297),
(59, 2, 0, 3, 1521011007),
(60, 2, 0, 3, 1521011062),
(61, 2, 0, 3, 1521011112),
(62, 2, 0, 3, 1521011118),
(63, 2, 0, 3, 1521012262),
(64, 2, 0, 3, 1521012292),
(65, 2, 0, 3, 1521012424),
(66, 2, 0, 3, 1521012516),
(67, 2, 0, 3, 1521013222),
(68, 2, 0, 3, 1521013408),
(69, 2, 0, 3, 1521013449),
(70, 2, 0, 3, 1521013505),
(71, 2, 0, 3, 1521013538),
(72, 2, 0, 3, 1521013545),
(73, 2, 0, 3, 1521013568),
(74, 2, 0, 3, 1521013604),
(75, 2, 0, 3, 1521013646),
(76, 2, 0, 3, 1521013665),
(77, 2, 0, 3, 1521014038),
(78, 2, 0, 3, 1521014057),
(79, 2, 0, 3, 1521014060),
(80, 2, 0, 3, 1521014107),
(81, 2, 0, 3, 1521014109),
(82, 2, 0, 3, 1521014126),
(83, 2, 0, 3, 1521014131),
(84, 2, 0, 3, 1521014212),
(85, 2, 0, 3, 1521014302),
(86, 2, 0, 3, 1521014317),
(87, 2, 0, 3, 1521014358),
(88, 2, 0, 3, 1521014943),
(89, 2, 0, 3, 1521014984),
(90, 2, 0, 3, 1521014987),
(91, 2, 0, 3, 1521015095),
(92, 2, 0, 3, 1521015335),
(93, 2, 0, 3, 1521015373),
(94, 2, 0, 3, 1521015413),
(95, 2, 0, 3, 1521015746),
(96, 2, 0, 3, 1521015875),
(97, 2, 0, 3, 1521016013),
(98, 2, 0, 3, 1521023681),
(99, 2, 0, 3, 1521023703),
(100, 2, 0, 3, 1521023742),
(101, 2, 0, 3, 1521023821),
(102, 2, 0, 3, 1521023885),
(103, 2, 0, 3, 1521023947),
(104, 2, 0, 3, 1521024014),
(105, 2, 0, 3, 1521024027),
(106, 2, 0, 3, 1521024054),
(107, 2, 0, 3, 1521024060),
(108, 2, 0, 3, 1521039896),
(109, 2, 0, 3, 1521040570),
(110, 2, 0, 3, 1521041365),
(111, 2, 0, 3, 1521041757),
(112, 2, 0, 3, 1521042186),
(113, 2, 0, 3, 1521043167),
(114, 2, 0, 3, 1521043404),
(115, 2, 0, 3, 1521044001),
(116, 2, 0, 3, 1521046502),
(117, 2, 0, 3, 1521046763),
(118, 0, 3, 3, 1521047234),
(119, 3, 0, 2, 1521047303),
(120, 0, 3, 3, 1521049935),
(121, 0, 5, 3, 1521049941),
(122, 2, 0, 7, 1521128512),
(123, 2, 0, 7, 1521128564),
(124, 2, 0, 7, 1521128634),
(125, 3, 0, 7, 1521128641),
(126, 2, 0, 7, 1521128902),
(127, 2, 0, 7, 1521129036),
(128, 2, 0, 7, 1521129079),
(129, 2, 0, 7, 1521129153),
(130, 0, 6, 7, 1521129174),
(131, 0, 6, 7, 1521129182),
(132, 2, 0, 7, 1521129259),
(133, 0, 6, 7, 1521129260),
(134, 0, 6, 3, 1521129269),
(135, 2, 0, 3, 1521129623),
(136, 3, 0, 7, 1521130970),
(137, 7, 0, 3, 1521131099),
(138, 0, 6, 3, 1521131109),
(139, 0, 5, 3, 1521131110),
(140, 0, 3, 3, 1521131112),
(141, 7, 0, 3, 1521132345),
(142, 0, 7, 3, 1521132539),
(143, 0, 7, 3, 1521132559),
(144, 7, 0, 3, 1521132566),
(145, 3, 0, 7, 1521132595);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apply`
--
ALTER TABLE `apply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boosts`
--
ALTER TABLE `boosts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `jobs` ADD FULLTEXT KEY `FULLTEXT_INDEX_JOBTAGS` (`job_tags`);
ALTER TABLE `jobs` ADD FULLTEXT KEY `FULLTEXT_INDEX_LOCATION` (`location`);
ALTER TABLE `jobs` ADD FULLTEXT KEY `FULLTEXT_INDEX_DESCRIPTION` (`description`);
ALTER TABLE `jobs` ADD FULLTEXT KEY `FULLTEXT_INDEX_TITLE` (`title`);

--
-- Indexes for table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

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
ALTER TABLE `users` ADD FULLTEXT KEY `FULLTEXT_INDEX_JOBTAGS` (`job_tags`);
ALTER TABLE `users` ADD FULLTEXT KEY `FULLTEXT_INDEX_PLACES` (`places`);
ALTER TABLE `users` ADD FULLTEXT KEY `FULLTEXT_INDEX_FNAME` (`fname`);
ALTER TABLE `users` ADD FULLTEXT KEY `FULLTEXT_INDEX_LNAME` (`lname`);

--
-- Indexes for table `views`
--
ALTER TABLE `views`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apply`
--
ALTER TABLE `apply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `boosts`
--
ALTER TABLE `boosts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `views`
--
ALTER TABLE `views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
