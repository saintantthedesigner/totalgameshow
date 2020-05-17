-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 17, 2020 at 12:14 AM
-- Server version: 10.2.31-MariaDB-log
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `acepso5_fiverr`
--

-- --------------------------------------------------------

--
-- Table structure for table `Game_Questions`
--

CREATE TABLE `Game_Questions` (
  `id` int(11) NOT NULL,
  `id_game` int(11) NOT NULL,
  `question` text COLLATE latin1_general_ci NOT NULL,
  `answer1` text COLLATE latin1_general_ci NOT NULL,
  `answer2` text COLLATE latin1_general_ci NOT NULL,
  `answer3` text COLLATE latin1_general_ci NOT NULL,
  `answer4` text COLLATE latin1_general_ci NOT NULL,
  `answer` varchar(5) COLLATE latin1_general_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Game_Questions`
--
ALTER TABLE `Game_Questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_game` (`id_game`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Game_Questions`
--
ALTER TABLE `Game_Questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
