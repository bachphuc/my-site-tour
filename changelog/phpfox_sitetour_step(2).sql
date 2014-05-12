-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2014 at 10:03 PM
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
-- Table structure for table `phpfox_sitetour_step`
--

CREATE TABLE IF NOT EXISTS `phpfox_sitetour_step` (
  `step_id` int(10) NOT NULL AUTO_INCREMENT,
  `sitetour_id` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `element` varchar(300) NOT NULL,
  `content` varchar(500) NOT NULL,
  `placement` varchar(50) DEFAULT 'auto',
  `animation` varchar(10) DEFAULT 'true',
  `duration` varchar(10) NOT NULL DEFAULT '3000',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `time_stamp` int(10) NOT NULL,
  `ordering` int(10) DEFAULT '0',
  PRIMARY KEY (`step_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=77 ;

--
-- Dumping data for table `phpfox_sitetour_step`
--

INSERT INTO `phpfox_sitetour_step` (`step_id`, `sitetour_id`, `title`, `element`, `content`, `placement`, `animation`, `duration`, `is_active`, `time_stamp`, `ordering`) VALUES
(70, 19, 'sdf ', 'ul>li:nth-child(3)>a.has_drop_down.no_ajax_link:nth-child(1) ', 'dsafdsa dsdsf sdaf sdfa fsd ', 'auto', 'true', '', 1, 1399918404, 0),
(71, 19, 'sda faf dsa', 'div.user_display_name a', 'f asdf sdaf dsaf sad fasd asdf ', 'auto', 'true', '2000', 1, 1399918404, 0),
(72, 19, 'gsdf gdfsg dfg dfsg', 'li.first a.ajax_link', ' dfsgdfg sdf', 'auto', 'true', '3000', 1, 1399918404, 0),
(73, 19, 'ew gfsda ', 'a.no_text_input>div:nth-child(1) ', 'gdfsgsdfg dsfg dsfg ', 'auto', 'true', '', 1, 1399918404, 0),
(74, 19, ' asfd', 'div#js_block_border_log_login.block div.title', 'f asdasdfa fsdf sdfd ', 'auto', 'true', '', 1, 1399918404, 0),
(75, 19, 'sdf asd', 'div#js_block_border_shoutbox_display.block div.title', 'fdsaf dsaf sdf sdaf', 'auto', 'true', '3000', 1, 1399918404, 0),
(76, 19, 'sdf s', 'a#logo', 'dafasdfsdaf sdaf ', 'auto', 'true', '', 1, 1399918404, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
