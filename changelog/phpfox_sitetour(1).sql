-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2014 at 10:02 PM
-- Server version: 5.6.11
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sitetour`
--

-- --------------------------------------------------------

--
-- Table structure for table `phpfox_sitetour`
--

CREATE TABLE IF NOT EXISTS `phpfox_sitetour` (
  `sitetour_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_group_id` int(10) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL,
  `url` varchar(500) NOT NULL,
  `is_autorun` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `time_stamp` int(10) NOT NULL,
  PRIMARY KEY (`sitetour_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `phpfox_sitetour`
--

INSERT INTO `phpfox_sitetour` (`sitetour_id`, `user_group_id`, `title`, `url`, `is_autorun`, `is_active`, `time_stamp`) VALUES
(19, 0, 'test', 'http://localhost/sitetour/index.php?do=/', 1, 1, 1399918404);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
