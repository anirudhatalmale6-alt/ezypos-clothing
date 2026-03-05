-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 16, 2019 at 04:05 AM
-- Server version: 5.6.45-cll-lve
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ezy_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_bank`
--

CREATE TABLE `ezy_pos_bank` (
  `bank_id` int(11) NOT NULL,
  `bank_name` varchar(250) NOT NULL,
  `bank_branch` varchar(250) NOT NULL,
  `bank_accname` varchar(250) NOT NULL,
  `bank_accnumber` varchar(250) NOT NULL,
  `bank_createdby` int(11) NOT NULL,
  `bank_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_bank`
--

INSERT INTO `ezy_pos_bank` (`bank_id`, `bank_name`, `bank_branch`, `bank_accname`, `bank_accnumber`, `bank_createdby`, `bank_status`) VALUES
(1, 'BOC', 'Pettah', 'Jone', '1234566878', 89, 1),
(2, 'Sampath', 'Pettah', 'Smith', '1234566878', 89, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_categories`
--

CREATE TABLE `ezy_pos_categories` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(250) NOT NULL,
  `cat_status` int(1) NOT NULL DEFAULT '1',
  `cat_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_categories`
--

INSERT INTO `ezy_pos_categories` (`cat_id`, `cat_name`, `cat_status`, `cat_createdat`) VALUES
(1, 'Sonny', 1, '2018-09-05 06:25:00'),
(2, 'Sumsung', 1, '2018-09-05 06:25:09'),
(3, 'CAT 1', 1, '2019-10-04 05:12:41'),
(4, 'Category', 1, '2019-10-11 04:53:21'),
(5, 'CATEGORY wish2', 0, '2019-10-11 05:09:32'),
(6, 'Aaaa', 0, '2019-10-14 10:04:35'),
(7, 'Aaaa', 0, '2019-10-14 10:05:01'),
(8, 'Aaaa', 1, '2019-10-14 10:59:19');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_company`
--

CREATE TABLE `ezy_pos_company` (
  `com_id` int(11) NOT NULL,
  `com_name` varchar(250) NOT NULL,
  `com_address` varchar(250) DEFAULT NULL,
  `com_telephone` varchar(17) DEFAULT NULL,
  `com_mobile` varchar(17) DEFAULT NULL,
  `com_email` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_config2`
--

CREATE TABLE `ezy_pos_config2` (
  `config_id` int(3) NOT NULL,
  `config_key` varchar(250) NOT NULL,
  `config_value` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_config2`
--

INSERT INTO `ezy_pos_config2` (`config_id`, `config_key`, `config_value`) VALUES
(7, 'name', 'Company Name'),
(8, 'addressLine1', '12/34,Bankshall Street'),
(9, 'addressLine2', 'Colombo 11'),
(10, 'tel', '011234567'),
(11, 'mob', '0771234567'),
(12, 'footer', 'Thank You , Come Again');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_currentqtywithgrn`
--

CREATE TABLE `ezy_pos_currentqtywithgrn` (
  `cur_id` int(11) NOT NULL,
  `cur_grnID` int(11) NOT NULL,
  `cur_itmID` int(11) NOT NULL,
  `cur_grnQty` double(250,2) NOT NULL,
  `cur_grnPrice` double(30,2) NOT NULL,
  `cur_grnTotal` double(30,2) NOT NULL,
  `cur_currentQTY` double(250,2) NOT NULL,
  `cur_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_currentqtywithgrn`
--

INSERT INTO `ezy_pos_currentqtywithgrn` (`cur_id`, `cur_grnID`, `cur_itmID`, `cur_grnQty`, `cur_grnPrice`, `cur_grnTotal`, `cur_currentQTY`, `cur_status`) VALUES
(1, 1, 21, 200.00, 500.00, 100000.00, 0.00, 1),
(2, 2, 21, 200.00, 500.00, 100000.00, 0.00, 1),
(3, 2, 22, 600.00, 700.00, 420000.00, 563.00, 1),
(4, 3, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(5, 4, 22, 200.00, 700.00, 140000.00, 200.00, 1),
(6, 5, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(7, 6, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(8, 7, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(9, 8, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(10, 9, 22, 200.00, 700.00, 126000.00, 200.00, 1),
(11, 10, 22, 10.00, 550.00, 5500.00, 10.00, 1),
(12, 11, 21, 100.00, 600.00, 54000.00, 100.00, 1),
(13, 12, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(14, 13, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(15, 14, 21, 30.00, 500.00, 15000.00, 30.00, 1),
(16, 15, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(17, 16, 21, 20.00, 500.00, 10000.00, 20.00, 1),
(18, 17, 21, 1.00, 500.00, 500.00, 1.00, 1),
(19, 18, 21, 30.00, 500.00, 15000.00, 30.00, 1),
(20, 19, 21, 100.00, 500.00, 50000.00, 100.00, 1),
(21, 20, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(22, 21, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(23, 22, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(24, 23, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(25, 24, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(26, 25, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(27, 26, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(28, 27, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(29, 28, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(30, 1, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(31, 2, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(32, 2, 22, 200.00, 500.00, 100000.00, 200.00, 1),
(33, 1, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(34, 2, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(35, 2, 22, 200.00, 500.00, 100000.00, 200.00, 1),
(36, 3, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(37, 4, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(38, 5, 21, 30.00, 500.00, 15000.00, 30.00, 1),
(39, 6, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(40, 7, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(41, 8, 21, 600.00, 500.00, 300000.00, 600.00, 1),
(42, 9, 21, 400.00, 500.00, 200000.00, 400.00, 1),
(43, 10, 21, 400.00, 500.00, 200000.00, 400.00, 1),
(44, 11, 21, 400.00, 500.00, 200000.00, 400.00, 1),
(45, 12, 21, 400.00, 500.00, 200000.00, 400.00, 1),
(46, 13, 21, 400.00, 500.00, 200000.00, 400.00, 1),
(47, 14, 21, 400.00, 500.00, 200000.00, 400.00, 1),
(48, 15, 21, 200.00, 500.00, 100000.00, 200.00, 1),
(49, 16, 21, 10.00, 500.00, 5000.00, 10.00, 1),
(50, 17, 21, 10.00, 500.00, 5000.00, 10.00, 1),
(51, 18, 21, 10.00, 500.00, 5000.00, 10.00, 1),
(52, 19, 21, 10.00, 500.00, 5000.00, 10.00, 1),
(53, 20, 21, 10.00, 500.00, 5000.00, 10.00, 1),
(54, 21, 21, 10.00, 500.00, 5000.00, 10.00, 1),
(55, 22, 23, 1000.00, 500.00, 500000.00, 990.00, 1),
(56, 23, 23, 200.00, 500.00, 100000.00, 200.00, 1),
(57, 24, 23, 600.00, 500.00, 300000.00, 600.00, 1),
(58, 25, 21, 2.00, 1000.00, 2000.00, 2.00, 1),
(59, 26, 21, 3.00, 1000.00, 3000.00, 3.00, 1),
(60, 27, 21, 3.00, 1000.00, 3000.00, 3.00, 1),
(61, 28, 26, 1000.00, 70.00, 70000.00, 998.00, 1),
(62, 29, 21, 200.00, 1000.00, 200000.00, 200.00, 1),
(63, 30, 21, 1000.00, 1000.00, 1000000.00, 1000.00, 1),
(64, 31, 21, 1.00, 1000.00, 1000.00, 1.00, 1),
(65, 32, 21, 200.00, 1000.00, 200000.00, 200.00, 1),
(66, 33, 21, 200.00, 1000.00, 200000.00, 200.00, 1),
(67, 34, 21, 200.00, 1000.00, 200000.00, 200.00, 1),
(68, 35, 21, 1.00, 1000.00, 1000.00, 1.00, 1),
(69, 36, 21, 1000.00, 1000.00, 1000000.00, 1000.00, 1),
(70, 37, 21, 200.00, 1000.00, 200000.00, 200.00, 1),
(71, 38, 21, 200.00, 1000.00, 200000.00, 200.00, 1),
(72, 39, 21, 1.00, 1000.00, 1000.00, 1.00, 1),
(73, 1, 21, 200.00, 1000.00, 200000.00, 200.00, 1),
(74, 2, 21, 200.00, 1000.00, 200000.00, 200.00, 1),
(75, 3, 21, 200.00, 1000.00, 200000.00, 200.00, 1),
(76, 4, 21, 200.00, 1000.00, 200000.00, 200.00, 1),
(77, 5, 21, 200.00, 1000.00, 200000.00, 200.00, 1),
(78, 6, 21, 200.00, 1000.00, 200000.00, 200.00, 1),
(79, 1, 21, 200.00, 1000.00, 200000.00, 200.00, 1),
(80, 1, 21, 200.00, 1000.00, 200000.00, 200.00, 1),
(81, 2, 21, 1.00, 1000.00, 1000.00, 1.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_cur_grn_vs_sale`
--

CREATE TABLE `ezy_pos_cur_grn_vs_sale` (
  `grnvssale_id` int(11) NOT NULL,
  `grnvssale_curQtyWithGrnID` int(11) NOT NULL,
  `grnvssale_itmID` int(11) NOT NULL,
  `grnvssale_saleID` int(11) NOT NULL,
  `grnvssale_status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_cur_grn_vs_sale`
--

INSERT INTO `ezy_pos_cur_grn_vs_sale` (`grnvssale_id`, `grnvssale_curQtyWithGrnID`, `grnvssale_itmID`, `grnvssale_saleID`, `grnvssale_status`) VALUES
(1, 1, 21, 1, 1),
(2, 3, 22, 2, 1),
(3, 1, 21, 2, 1),
(4, 1, 21, 1, 1),
(5, 3, 22, 2, 1),
(6, 1, 21, 2, 1),
(7, 1, 21, 3, 1),
(8, 1, 21, 4, 1),
(9, 1, 21, 5, 1),
(10, 1, 21, 6, 1),
(11, 1, 21, 7, 1),
(12, 1, 21, 8, 1),
(13, 1, 21, 9, 1),
(14, 1, 21, 10, 1),
(15, 1, 21, 11, 1),
(16, 1, 21, 12, 1),
(17, 1, 21, 13, 1),
(18, 1, 21, 14, 1),
(19, 1, 21, 15, 1),
(20, 1, 21, 16, 1),
(21, 1, 21, 17, 1),
(22, 1, 21, 18, 1),
(23, 1, 21, 19, 1),
(24, 1, 21, 20, 1),
(25, 1, 21, 21, 1),
(26, 1, 21, 22, 1),
(27, 1, 21, 23, 1),
(28, 1, 21, 24, 1),
(29, 1, 21, 25, 1),
(30, 1, 21, 26, 1),
(31, 1, 21, 27, 1),
(32, 1, 21, 28, 1),
(33, 1, 21, 29, 1),
(34, 1, 21, 30, 1),
(35, 1, 21, 31, 1),
(36, 1, 21, 32, 1),
(37, 1, 21, 33, 1),
(38, 1, 21, 34, 1),
(39, 1, 21, 35, 1),
(40, 1, 21, 36, 1),
(41, 1, 21, 37, 1),
(42, 1, 21, 38, 1),
(43, 1, 21, 39, 1),
(44, 1, 21, 40, 1),
(45, 1, 21, 41, 1),
(46, 1, 21, 42, 1),
(47, 1, 21, 43, 1),
(48, 1, 21, 44, 1),
(49, 1, 21, 45, 1),
(50, 1, 21, 46, 1),
(51, 1, 21, 47, 1),
(52, 1, 21, 48, 1),
(53, 1, 21, 49, 1),
(54, 1, 21, 50, 1),
(55, 1, 21, 51, 1),
(56, 1, 21, 52, 1),
(57, 1, 21, 53, 1),
(58, 1, 21, 54, 1),
(59, 1, 21, 55, 1),
(60, 1, 21, 56, 1),
(61, 1, 21, 57, 1),
(62, 1, 21, 58, 1),
(63, 1, 21, 59, 1),
(64, 1, 21, 60, 1),
(65, 1, 21, 61, 1),
(66, 1, 21, 62, 1),
(67, 1, 21, 63, 1),
(68, 1, 21, 64, 1),
(69, 1, 21, 65, 1),
(70, 1, 21, 66, 1),
(71, 1, 21, 67, 1),
(72, 1, 21, 68, 1),
(73, 1, 21, 69, 1),
(74, 1, 21, 70, 1),
(75, 1, 21, 71, 1),
(76, 1, 21, 72, 1),
(77, 1, 21, 73, 1),
(78, 1, 21, 74, 1),
(79, 1, 21, 75, 1),
(80, 1, 21, 76, 1),
(81, 1, 21, 77, 1),
(82, 1, 21, 78, 1),
(83, 1, 21, 79, 1),
(84, 1, 21, 80, 1),
(85, 1, 21, 81, 1),
(86, 1, 21, 82, 1),
(87, 1, 21, 83, 1),
(88, 1, 21, 84, 1),
(89, 1, 21, 85, 1),
(90, 1, 21, 86, 1),
(91, 2, 21, 87, 1),
(92, 2, 21, 88, 1),
(93, 2, 21, 89, 1),
(94, 2, 21, 90, 1),
(95, 2, 21, 91, 1),
(96, 2, 21, 92, 1),
(97, 2, 21, 93, 1),
(98, 2, 21, 94, 1),
(99, 2, 21, 95, 1),
(100, 2, 21, 96, 1),
(101, 2, 21, 97, 1),
(102, 2, 21, 98, 1),
(103, 2, 21, 99, 1),
(104, 2, 21, 100, 1),
(105, 2, 21, 101, 1),
(106, 2, 21, 102, 1),
(107, 2, 21, 103, 1),
(108, 2, 21, 104, 1),
(109, 2, 21, 105, 1),
(110, 2, 21, 106, 1),
(111, 2, 21, 107, 1),
(112, 2, 21, 108, 1),
(113, 2, 21, 109, 1),
(114, 2, 21, 110, 1),
(115, 2, 21, 111, 1),
(116, 2, 21, 112, 1),
(117, 2, 21, 113, 1),
(118, 2, 21, 114, 1),
(119, 2, 21, 115, 1),
(120, 2, 21, 116, 1),
(121, 2, 21, 117, 1),
(122, 2, 21, 118, 1),
(123, 2, 21, 119, 1),
(124, 2, 21, 120, 1),
(125, 2, 21, 121, 1),
(126, 2, 21, 122, 1),
(127, 2, 21, 123, 1),
(128, 2, 21, 124, 1),
(129, 2, 21, 125, 1),
(130, 2, 21, 126, 1),
(131, 2, 21, 127, 1),
(132, 2, 21, 128, 1),
(133, 2, 21, 129, 1),
(134, 2, 21, 130, 1),
(135, 2, 21, 131, 1),
(136, 2, 21, 132, 1),
(137, 2, 21, 133, 1),
(138, 2, 21, 134, 1),
(139, 2, 21, 135, 1),
(140, 2, 21, 136, 1),
(141, 2, 21, 137, 1),
(142, 2, 21, 138, 1),
(143, 2, 21, 139, 1),
(144, 2, 21, 140, 1),
(145, 2, 21, 141, 1),
(146, 2, 21, 142, 1),
(147, 2, 21, 143, 1),
(148, 2, 21, 144, 1),
(149, 2, 21, 145, 1),
(150, 2, 21, 146, 1),
(151, 2, 21, 147, 1),
(152, 2, 21, 148, 1),
(153, 2, 21, 149, 1),
(154, 2, 21, 150, 1),
(155, 2, 21, 151, 1),
(156, 2, 21, 152, 1),
(157, 2, 21, 153, 1),
(158, 2, 21, 154, 1),
(159, 2, 21, 155, 1),
(160, 2, 21, 156, 1),
(161, 2, 21, 157, 1),
(162, 2, 21, 158, 1),
(163, 2, 21, 159, 1),
(164, 2, 21, 160, 1),
(165, 2, 21, 161, 1),
(166, 2, 21, 162, 1),
(167, 2, 21, 163, 1),
(168, 2, 21, 164, 1),
(169, 2, 21, 165, 1),
(170, 2, 21, 166, 1),
(171, 2, 21, 167, 1),
(172, 2, 21, 168, 1),
(173, 3, 22, 168, 1),
(174, 2, 21, 169, 1),
(175, 3, 22, 169, 1),
(176, 2, 21, 170, 1),
(177, 3, 22, 170, 1),
(178, 2, 21, 171, 1),
(179, 3, 22, 171, 1),
(180, 55, 23, 171, 1),
(181, 2, 21, 172, 1),
(182, 3, 22, 172, 1),
(183, 55, 23, 172, 1),
(184, 2, 21, 173, 1),
(185, 3, 22, 173, 1),
(186, 55, 23, 173, 1),
(187, 2, 21, 174, 1),
(188, 3, 22, 174, 1),
(189, 55, 23, 174, 1),
(190, 2, 21, 175, 1),
(191, 2, 21, 176, 1),
(192, 2, 21, 177, 1),
(193, 2, 21, 178, 1),
(194, 2, 21, 179, 1),
(195, 2, 21, 180, 1),
(196, 2, 21, 181, 1),
(197, 2, 21, 182, 1),
(198, 2, 21, 183, 1),
(199, 2, 21, 184, 1),
(200, 2, 21, 185, 1),
(201, 2, 21, 186, 1),
(202, 2, 21, 187, 1),
(203, 2, 21, 188, 1),
(204, 2, 21, 189, 1),
(205, 2, 21, 190, 1),
(206, 2, 21, 0, 1),
(207, 2, 21, 191, 1),
(208, 2, 21, 192, 1),
(209, 2, 21, 193, 1),
(210, 3, 22, 193, 1),
(211, 2, 21, 194, 1),
(212, 2, 21, 195, 1),
(213, 2, 21, 196, 1),
(214, 2, 21, 197, 1),
(215, 3, 22, 197, 1),
(216, 55, 23, 197, 1),
(217, 2, 21, 198, 1),
(218, 2, 21, 199, 1),
(219, 3, 22, 199, 1),
(220, 55, 23, 199, 1),
(221, 2, 21, 200, 1),
(222, 3, 22, 200, 1),
(223, 2, 21, 201, 1),
(224, 3, 22, 201, 1),
(225, 55, 23, 201, 1),
(226, 55, 23, 202, 1),
(227, 2, 21, 203, 1),
(228, 2, 21, 204, 1),
(229, 2, 21, 205, 1),
(230, 3, 22, 205, 1),
(231, 55, 23, 205, 1),
(232, 2, 21, 206, 1),
(233, 3, 22, 206, 1),
(234, 55, 23, 206, 1),
(235, 2, 21, 207, 1),
(236, 2, 21, 208, 1),
(237, 2, 21, 209, 1),
(238, 2, 21, 210, 1),
(239, 2, 21, 211, 1),
(240, 2, 21, 212, 1),
(241, 2, 21, 213, 1),
(242, 2, 21, 214, 1),
(243, 2, 21, 215, 1),
(244, 2, 21, 216, 1),
(245, 2, 21, 217, 1),
(246, 2, 21, 218, 1),
(247, 2, 21, 220, 1),
(248, 2, 21, 221, 1),
(249, 2, 21, 222, 1),
(250, 2, 21, 223, 1),
(251, 2, 21, 1, 1),
(252, 2, 21, 2, 1),
(253, 2, 21, 3, 1),
(254, 3, 22, 4, 1),
(255, 2, 21, 5, 1),
(256, 2, 21, 6, 1),
(257, 2, 21, 7, 1),
(258, 2, 21, 8, 1),
(259, 2, 21, 10, 1),
(260, 2, 21, 11, 1),
(261, 2, 21, 12, 1),
(262, 2, 21, 13, 1),
(263, 2, 21, 14, 1),
(264, 2, 21, 1, 1),
(265, 2, 21, 2, 1),
(266, 2, 21, 3, 1),
(267, 2, 21, 1, 1),
(268, 2, 21, 2, 1),
(269, 2, 21, 3, 1),
(270, 2, 21, 4, 1),
(271, 61, 26, 5, 1),
(272, 61, 26, 6, 1),
(273, 2, 21, 7, 1),
(274, 2, 21, 8, 1),
(275, 2, 21, 9, 1),
(276, 2, 21, 10, 1),
(277, 2, 21, 11, 1),
(278, 2, 21, 1, 1),
(279, 2, 21, 2, 1),
(280, 2, 21, 3, 1),
(281, 2, 21, 4, 1),
(282, 2, 21, 5, 1),
(283, 2, 21, 6, 1),
(284, 2, 21, 7, 1),
(285, 2, 21, 8, 1),
(286, 2, 21, 1, 1),
(287, 3, 22, 2, 1),
(288, 2, 21, 3, 1),
(289, 2, 21, 4, 1),
(290, 2, 21, 5, 1),
(291, 2, 21, 6, 1),
(292, 2, 21, 7, 1),
(293, 2, 21, 8, 1),
(294, 2, 21, 9, 1),
(295, 2, 21, 10, 1),
(296, 2, 21, 11, 1),
(297, 2, 21, 12, 1),
(298, 2, 21, 13, 1),
(299, 2, 21, 2, 1),
(300, 2, 21, 3, 1),
(301, 2, 21, 1, 1),
(302, 2, 21, 2, 1),
(303, 2, 21, 3, 1),
(304, 2, 21, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_customers`
--

CREATE TABLE `ezy_pos_customers` (
  `cus_id` int(11) NOT NULL,
  `cus_name` varchar(200) NOT NULL,
  `cus_address` varchar(250) NOT NULL,
  `cus_contact` varchar(20) NOT NULL,
  `cus_balance` decimal(30,2) NOT NULL,
  `cus_creditlimit` double(30,2) NOT NULL,
  `cus_status` int(1) NOT NULL DEFAULT '1',
  `cus_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_customers`
--

INSERT INTO `ezy_pos_customers` (`cus_id`, `cus_name`, `cus_address`, `cus_contact`, `cus_balance`, `cus_creditlimit`, `cus_status`, `cus_createdat`) VALUES
(2, 'Ezytest', 'Colombo', '0771234567', '0.00', 1000.00, 1, '2018-09-05 06:26:03'),
(3, 'Ezy Buyer ', 'Colombo', '0771234567', '0.00', 10000.00, 1, '2018-09-05 06:26:34'),
(4, 'Customer1', 'Colombo', '0771234567', '3000.00', 1000.00, 1, '2018-09-05 18:39:55'),
(6, 'Cust1', 'Colombo', '0771234567', '2000.00', 1000.00, 1, '2018-09-20 09:12:12'),
(7, 'Ezytest11', 'Colombo', '0771234567', '5000.00', 1000.00, 1, '2018-09-20 11:01:04'),
(11, 'Cus Wish', '125/25 church lane,', '0768453735', '1000.00', 2500.00, 1, '2019-10-11 04:40:45'),
(12, 'Cus Wish2', '125/26 church lane,', '0768453735', '1500.00', 1000.00, 0, '2019-10-11 04:51:20'),
(13, 'as', 'ss', 'cxvcxvcxvcx', '123.00', 250.33, 0, '2019-10-14 11:06:08'),
(14, 'Cust2', 'cxvxcv2', 'cvxcvxcvcxvxcvcxvcxv', '34534534.22', 454.22, 1, '2019-10-14 11:08:26');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_cus_balnce`
--

CREATE TABLE `ezy_pos_cus_balnce` (
  `bal_id` int(11) NOT NULL,
  `bal_cusid` int(11) NOT NULL,
  `bal_amount` double(50,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_cus_balnce`
--

INSERT INTO `ezy_pos_cus_balnce` (`bal_id`, `bal_cusid`, `bal_amount`) VALUES
(1, 2, -1900.00),
(2, 3, 4000.00),
(3, 4, 0.00),
(4, 6, 0.00),
(5, 7, 0.00),
(6, 11, 0.00),
(7, 12, 0.00),
(8, 13, 0.00),
(9, 14, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_cus_cheque`
--

CREATE TABLE `ezy_pos_cus_cheque` (
  `cus_cheque_id` int(11) NOT NULL,
  `cus_cheque_saleid` int(11) NOT NULL,
  `cus_cheque_amount` double(50,2) NOT NULL,
  `cus_cheque_bank` varchar(250) NOT NULL,
  `cus_cheque_num` varchar(250) NOT NULL,
  `cus_cheque_date` date NOT NULL,
  `cus_cheque_givendate` date NOT NULL,
  `cus_cheque_status` tinyint(1) NOT NULL,
  `cus_cheque_transferred` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_cus_cheque`
--

INSERT INTO `ezy_pos_cus_cheque` (`cus_cheque_id`, `cus_cheque_saleid`, `cus_cheque_amount`, `cus_cheque_bank`, `cus_cheque_num`, `cus_cheque_date`, `cus_cheque_givendate`, `cus_cheque_status`, `cus_cheque_transferred`) VALUES
(1, 2, 1000.00, 'sampath', '124234', '2018-09-18', '2018-10-03', 1, 0),
(2, 3, 11000.00, 'sampath', '124234', '2018-09-18', '2018-10-03', 1, 1),
(3, 4, 10.00, 'sampath', '124234', '2018-10-31', '2018-10-03', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_cus_chequelog`
--

CREATE TABLE `ezy_pos_cus_chequelog` (
  `cheqlog_id` int(11) NOT NULL,
  `cheqlog_chequeid` int(11) NOT NULL,
  `cheqlog_saleid` int(11) NOT NULL,
  `cheqlog_amount` double(50,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_cus_payment`
--

CREATE TABLE `ezy_pos_cus_payment` (
  `cus_pay_id` int(11) NOT NULL,
  `cus_pay_saleid` int(11) NOT NULL,
  `cus_pay_cash` double(50,2) NOT NULL,
  `cus_pay_credit` double(50,2) NOT NULL,
  `cus_pay_paiddate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_cus_payment`
--

INSERT INTO `ezy_pos_cus_payment` (`cus_pay_id`, `cus_pay_saleid`, `cus_pay_cash`, `cus_pay_credit`, `cus_pay_paiddate`) VALUES
(1, 1, 0.00, 1000.00, '2018-10-03'),
(2, 2, 0.00, 0.00, '2018-10-03'),
(3, 3, 0.00, -1000.00, '2018-10-03'),
(4, 4, 0.00, 900.00, '2018-10-03');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_cus_paymnt_log`
--

CREATE TABLE `ezy_pos_cus_paymnt_log` (
  `pymntlog_id` int(11) NOT NULL,
  `pymntlog_saleid` int(11) NOT NULL,
  `pymntlog_amount` double(50,2) NOT NULL,
  `pymntlog_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_cus_paymnt_log`
--

INSERT INTO `ezy_pos_cus_paymnt_log` (`pymntlog_id`, `pymntlog_saleid`, `pymntlog_amount`, `pymntlog_date`) VALUES
(1, 1, 0.00, '2018-10-03'),
(2, 2, 0.00, '2018-10-03'),
(3, 3, 0.00, '2018-10-03'),
(4, 4, 0.00, '2018-10-03');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_cus_return`
--

CREATE TABLE `ezy_pos_cus_return` (
  `cusrtrn_id` int(11) NOT NULL,
  `cusrtrn_cusID` int(11) NOT NULL,
  `cusrtrn_totalRtrn` double(20,2) NOT NULL,
  `cusrtrn_createdby` int(11) NOT NULL,
  `cusrtrn_status` tinyint(1) NOT NULL,
  `cusrtrn_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_cus_return`
--

INSERT INTO `ezy_pos_cus_return` (`cusrtrn_id`, `cusrtrn_cusID`, `cusrtrn_totalRtrn`, `cusrtrn_createdby`, `cusrtrn_status`, `cusrtrn_createdat`) VALUES
(3, 2, 0.00, 89, 1, '2019-10-03 04:41:36'),
(4, 2, 0.00, 89, 1, '2019-10-03 04:41:38'),
(5, 2, 0.00, 89, 1, '2019-10-03 04:41:40');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_cus_return_item`
--

CREATE TABLE `ezy_pos_cus_return_item` (
  `retrn_itm_id` int(11) NOT NULL,
  `retrn_itm_retrnID` int(11) NOT NULL,
  `retrn_itm_itmID` int(11) NOT NULL,
  `retrn_itm_rQty` double(250,2) NOT NULL,
  `retrn_itm_rAmount` double(30,2) NOT NULL,
  `retrn_itm_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_employe_salary`
--

CREATE TABLE `ezy_pos_employe_salary` (
  `empsalary_id` int(11) NOT NULL,
  `empsalary_staffid` int(11) NOT NULL,
  `empsalary_month` tinyint(2) NOT NULL,
  `empsalary_year` smallint(5) NOT NULL,
  `empsalary_balance` double(30,2) NOT NULL,
  `empsalary_basicsalary` double(30,2) NOT NULL,
  `empsalary_food` double(30,2) NOT NULL,
  `empsalary_travel` double(30,2) NOT NULL,
  `empsalary_other` double(30,2) NOT NULL,
  `empsalary_ot` double(30,2) NOT NULL,
  `empsalary_bonus` double(30,2) NOT NULL,
  `empsalary_total` double(30,2) NOT NULL,
  `empsalary_createdby` int(11) NOT NULL,
  `empsalary_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_employe_salary`
--

INSERT INTO `ezy_pos_employe_salary` (`empsalary_id`, `empsalary_staffid`, `empsalary_month`, `empsalary_year`, `empsalary_balance`, `empsalary_basicsalary`, `empsalary_food`, `empsalary_travel`, `empsalary_other`, `empsalary_ot`, `empsalary_bonus`, `empsalary_total`, `empsalary_createdby`, `empsalary_status`) VALUES
(1, 3, 1, 2018, 0.00, 31.00, 5.00, 5.00, 20.00, 4.00, 10.00, 75.00, 89, 1),
(2, 2, 1, 2019, 0.00, 25.00, 0.00, 80.20, 0.00, 0.00, 0.00, 105.20, 89, 1),
(3, 1, 1, 2019, 0.00, 150000.00, 5000.00, 3000.00, 51000.00, 15750.00, 0.00, 224750.00, 89, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_employe_salary_log`
--

CREATE TABLE `ezy_pos_employe_salary_log` (
  `emp_slry_log_expense_id` int(11) NOT NULL,
  `emp_slry_log_expense_tblid` int(11) NOT NULL,
  `emp_slry_log_empsalry_tblid` int(11) NOT NULL,
  `emp_slry_log_empid` int(11) NOT NULL,
  `emp_slry_log_month` tinyint(2) NOT NULL,
  `emp_slry_log_year` smallint(5) NOT NULL,
  `emp_slry_log_amount` double(50,2) NOT NULL,
  `emp_slry_log_balanceofmonth` double(50,2) NOT NULL,
  `emp_slry_log_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_employe_salary_log`
--

INSERT INTO `ezy_pos_employe_salary_log` (`emp_slry_log_expense_id`, `emp_slry_log_expense_tblid`, `emp_slry_log_empsalry_tblid`, `emp_slry_log_empid`, `emp_slry_log_month`, `emp_slry_log_year`, `emp_slry_log_amount`, `emp_slry_log_balanceofmonth`, `emp_slry_log_status`) VALUES
(1, 6, 1, 3, 1, 2018, 20.00, 0.00, 1),
(2, 7, 2, 2, 1, 2019, 100.00, 0.00, 1),
(3, 8, 2, 2, 1, 2019, 5.20, 0.00, 1),
(4, 9, 3, 1, 1, 2019, 174750.00, 0.00, 1),
(5, 10, 3, 1, 1, 2019, 5000.00, 0.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_expense`
--

CREATE TABLE `ezy_pos_expense` (
  `expen_id` int(11) NOT NULL,
  `expen_expencat_id` int(11) NOT NULL,
  `expen_description` text NOT NULL,
  `expen_amount` double(30,2) NOT NULL,
  `expen_date` date NOT NULL,
  `expen_createdby` int(11) NOT NULL,
  `expen_status` tinyint(1) NOT NULL DEFAULT '1',
  `expen_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_expense`
--

INSERT INTO `ezy_pos_expense` (`expen_id`, `expen_expencat_id`, `expen_description`, `expen_amount`, `expen_date`, `expen_createdby`, `expen_status`, `expen_createdat`) VALUES
(1, 3, 'Monthly waterbill', 501.00, '2018-09-11', 89, 1, '2018-09-10 12:23:58'),
(2, 3, 'Monthly waterbill', 20.00, '2018-09-11', 89, 1, '2018-09-10 12:24:29'),
(3, 2, 'Monthly rent', 50000.00, '2018-09-11', 89, 1, '2018-09-10 13:14:29'),
(4, 2, 'Monthly waterbill', 500.00, '2018-09-25', 89, 1, '2018-09-10 13:15:13'),
(5, 3, 'bill paymnt electy', 20.00, '2018-09-10', 89, 1, '2018-09-10 13:16:40'),
(6, 1, 'Monthly Salary', 20.00, '2018-09-10', 89, 1, '2018-09-10 13:22:20'),
(7, 1, 'wishwa', 100.00, '2019-10-16', 89, 1, '2019-10-16 03:53:54'),
(8, 1, 'wishwa2', 5.20, '2019-10-16', 89, 1, '2019-10-16 03:55:12'),
(9, 1, 'wishwa', 174750.00, '2019-10-31', 89, 1, '2019-10-16 03:56:46'),
(10, 1, 'add any description to here.', 5000.00, '2019-10-16', 89, 1, '2019-10-16 08:21:29'),
(11, 1, 'add any description to here.', 5000.00, '2019-10-16', 89, 1, '2019-10-16 08:23:45'),
(12, 2, 'add any description to here.', 5000.00, '2019-10-31', 89, 1, '2019-10-16 08:24:49');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_expense_cat`
--

CREATE TABLE `ezy_pos_expense_cat` (
  `expencat_id` int(11) NOT NULL,
  `expencat_catname` varchar(250) NOT NULL,
  `expencat_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_expense_cat`
--

INSERT INTO `ezy_pos_expense_cat` (`expencat_id`, `expencat_catname`, `expencat_status`) VALUES
(1, 'Employee Salary', 1),
(2, 'Rent', 1),
(3, 'waterBill', 1),
(4, 'Electricity Bill', 1),
(5, 'Rent', 1),
(6, 'Other', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_expen_cheque`
--

CREATE TABLE `ezy_pos_expen_cheque` (
  `expen_chq_id` int(11) NOT NULL,
  `expen_chq_expntblid` int(11) NOT NULL,
  `expen_chq_amount` double(50,2) NOT NULL,
  `expen_chq_bank` varchar(250) NOT NULL,
  `expen_chq_chq_no` varchar(20) NOT NULL,
  `expen_chq_date` date NOT NULL,
  `expen_chq_status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_expen_cheque`
--

INSERT INTO `ezy_pos_expen_cheque` (`expen_chq_id`, `expen_chq_expntblid`, `expen_chq_amount`, `expen_chq_bank`, `expen_chq_chq_no`, `expen_chq_date`, `expen_chq_status`) VALUES
(1, 5, 20.00, '2', '45345', '2018-09-19', 1),
(2, 6, 20.00, '2', '345', '2018-09-18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_grns`
--

CREATE TABLE `ezy_pos_grns` (
  `grn_id` int(11) NOT NULL,
  `grn_code` varchar(50) NOT NULL,
  `grn_supplier_id` int(11) NOT NULL,
  `grn_grandtotal` double(50,2) NOT NULL,
  `grn_subtotal` double(50,2) NOT NULL,
  `grn_discount` double(5,2) NOT NULL,
  `grn_less` double(10,2) NOT NULL,
  `grn_createdby` int(11) NOT NULL,
  `grn_date` date NOT NULL,
  `grn_status` tinyint(1) NOT NULL,
  `grn_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_grns`
--

INSERT INTO `ezy_pos_grns` (`grn_id`, `grn_code`, `grn_supplier_id`, `grn_grandtotal`, `grn_subtotal`, `grn_discount`, `grn_less`, `grn_createdby`, `grn_date`, `grn_status`, `grn_createdat`) VALUES
(1, 'grncode1', 4, 200000.00, 200000.00, 0.00, 0.00, 89, '2018-10-02', 1, '2018-10-03 04:22:43'),
(2, 'grncode2', 4, 1000.00, 1000.00, 0.00, 0.00, 89, '2018-10-02', 1, '2018-10-03 06:41:24');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_grn_item`
--

CREATE TABLE `ezy_pos_grn_item` (
  `grnitm_id` int(11) NOT NULL,
  `grnitm_grn_id` int(11) NOT NULL,
  `grnitm_itemid` int(11) NOT NULL,
  `grnitm_price` double(50,2) NOT NULL,
  `grnitm_quantity` double(250,2) NOT NULL,
  `grnitm_total` double(50,2) NOT NULL,
  `grnitm_discount` double(5,2) NOT NULL,
  `grnitm_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_grn_item`
--

INSERT INTO `ezy_pos_grn_item` (`grnitm_id`, `grnitm_grn_id`, `grnitm_itemid`, `grnitm_price`, `grnitm_quantity`, `grnitm_total`, `grnitm_discount`, `grnitm_createdat`) VALUES
(1, 1, 21, 1000.00, 200.00, 200000.00, 0.00, '2018-10-03 04:22:43'),
(2, 2, 21, 1000.00, 1.00, 1000.00, 0.00, '2018-10-03 06:41:24');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_insuffistocksale`
--

CREATE TABLE `ezy_pos_insuffistocksale` (
  `insuffi_id` int(11) NOT NULL,
  `insuffi_saleid` int(11) NOT NULL,
  `insuffi_itmid` int(11) NOT NULL,
  `insuffi_qty` double(250,2) NOT NULL,
  `insuffi_newqty` double(250,2) NOT NULL,
  `insuffi_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `insuffi_grnstatus` tinyint(1) NOT NULL DEFAULT '0',
  `insuffi_salestatus` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_insuffistocksale`
--

INSERT INTO `ezy_pos_insuffistocksale` (`insuffi_id`, `insuffi_saleid`, `insuffi_itmid`, `insuffi_qty`, `insuffi_newqty`, `insuffi_createdat`, `insuffi_grnstatus`, `insuffi_salestatus`) VALUES
(1, 206, 24, 1.00, 1.00, '2018-09-13 04:10:49', 0, 0),
(2, 206, 25, 1.00, 1.00, '2018-09-13 04:10:50', 0, 0),
(3, 219, 24, 1.00, 1.00, '2018-09-13 05:10:50', 0, 0),
(4, 9, 24, 1.00, 1.00, '2018-09-14 08:30:51', 0, 0),
(5, 1, 0, 1.00, 1.00, '2018-10-03 06:56:01', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_items`
--

CREATE TABLE `ezy_pos_items` (
  `itm_id` int(11) NOT NULL,
  `itm_code` varchar(250) NOT NULL,
  `itm_name` varchar(250) NOT NULL,
  `itm_category` int(11) NOT NULL,
  `itm_subcategory` int(11) NOT NULL,
  `itm_brand` varchar(250) NOT NULL,
  `itm_manufacture` varchar(250) NOT NULL,
  `itm_description` text NOT NULL,
  `itm_sellingprice` double(30,2) NOT NULL,
  `itm_quantity` double(250,2) NOT NULL,
  `itm_reorderlevel` double(250,2) NOT NULL,
  `itm_uom` varchar(250) NOT NULL,
  `itm_status` int(1) NOT NULL DEFAULT '1',
  `itm_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_items`
--

INSERT INTO `ezy_pos_items` (`itm_id`, `itm_code`, `itm_name`, `itm_category`, `itm_subcategory`, `itm_brand`, `itm_manufacture`, `itm_description`, `itm_sellingprice`, `itm_quantity`, `itm_reorderlevel`, `itm_uom`, `itm_status`, `itm_createdat`) VALUES
(21, 'ITEM0001', 'Table Lamp', 1, 25, 'sonny', 'sonny', 'japan', 1000.00, 0.00, 100.00, 'pcs', 1, '2018-09-05 06:28:44'),
(22, 'ITEM0002', 'Garden Lamp', 2, 26, 'Sumsung', 'sumsung', 'japan', 2000.00, 0.00, 100.00, 'pcs', 1, '2018-09-05 06:29:27'),
(23, 'ITEM00012', 'Night Bulb', 1, 25, 'sonny', 'sonny', 'sdf', 1000.00, 0.00, 100.00, 'pcs', 1, '2018-09-12 04:39:00'),
(24, 'ITEM000134', 'White Wall Light ', 1, 0, 'sonny', 'sonny', 'sdf', 1000.00, 0.00, 100.00, 'pcs', 1, '2018-09-13 04:08:53'),
(25, 'ITEM000154', 'Orange Wall Light ', 1, 25, 'sonny', 'sonny', 'sdf', 1000.00, 0.00, 100.00, 'pcs', 1, '2018-09-13 04:09:40'),
(26, 'ASR0000153', 'TestItem', 1, 25, 'sonny', 'sonny', 'sf', 100.00, 0.00, 100.00, 'pcs', 1, '2018-09-19 10:22:29'),
(27, 'OFF100', 'ITEM 100', 3, 27, 'Brand Cat', 'MAN CAT', 'Description copy here,', 1500.00, 0.00, 10.00, 'Kg', 1, '2019-10-04 05:15:58'),
(28, 'ITEM w11', 'ITEM W11', 3, 27, 'Brand Wish1', 'MAN Wish1', 'Item Description comes here, type by wish1. ', 150.00, 0.00, 10.00, 'Item', 1, '2019-10-11 05:48:37'),
(29, 'ITEM w2 ', 'ITEM Name w2 ', 0, 0, 'Dddd', 'Ssss', 'Test Sssss', 1500.00, 0.00, 10.00, 'Kg', 1, '2019-10-11 05:53:43'),
(30, 'Wwww2', 'Books & Magazines', 8, 35, 'Wwww', 'Www ww', 'Www ww', 25.23, 0.00, 10.00, 'Kg', 1, '2019-10-14 11:32:43');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_privileges`
--

CREATE TABLE `ezy_pos_privileges` (
  `priv_id` int(11) NOT NULL,
  `priv_userid` int(11) NOT NULL,
  `priv_item` tinyint(1) NOT NULL DEFAULT '0',
  `priv_category` tinyint(1) NOT NULL DEFAULT '0',
  `priv_customer` tinyint(1) NOT NULL DEFAULT '0',
  `priv_supplier` tinyint(1) NOT NULL DEFAULT '0',
  `priv_store` tinyint(1) NOT NULL DEFAULT '0',
  `priv_staff` tinyint(1) NOT NULL DEFAULT '0',
  `priv_tax` tinyint(1) NOT NULL DEFAULT '0',
  `priv_register` tinyint(1) NOT NULL DEFAULT '0',
  `priv_expense_cat` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_privileges`
--

INSERT INTO `ezy_pos_privileges` (`priv_id`, `priv_userid`, `priv_item`, `priv_category`, `priv_customer`, `priv_supplier`, `priv_store`, `priv_staff`, `priv_tax`, `priv_register`, `priv_expense_cat`) VALUES
(51, 89, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(52, 90, 1, 1, 1, 1, 1, 1, 1, 0, 0),
(53, 91, 1, 1, 1, 1, 1, 1, 1, 0, 0),
(54, 92, 1, 1, 1, 1, 1, 1, 1, 0, 0),
(55, 93, 1, 1, 1, 1, 1, 1, 1, 0, 0),
(56, 94, 1, 1, 1, 1, 1, 1, 1, 0, 0),
(57, 95, 1, 1, 1, 1, 1, 1, 1, 0, 0),
(58, 96, 1, 1, 1, 1, 1, 1, 1, 0, 0),
(59, 97, 1, 1, 1, 1, 1, 1, 1, 0, 0),
(60, 98, 1, 1, 1, 1, 1, 1, 1, 0, 0),
(61, 99, 1, 1, 1, 1, 1, 1, 1, 0, 0),
(62, 100, 1, 1, 1, 1, 1, 1, 1, 0, 0),
(63, 101, 1, 1, 1, 1, 1, 1, 1, 0, 0),
(64, 102, 1, 1, 1, 1, 1, 1, 1, 0, 0),
(65, 103, 1, 1, 1, 1, 0, 0, 0, 0, 0),
(66, 104, 1, 1, 1, 1, 1, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_sale`
--

CREATE TABLE `ezy_pos_sale` (
  `sale_id` int(11) NOT NULL,
  `sale_cus_id` int(11) NOT NULL,
  `sale_grandtotal` double(50,2) NOT NULL,
  `sale_subtotal` double(50,2) NOT NULL,
  `sale_discount` double(5,2) NOT NULL,
  `sale_less` double(10,2) NOT NULL,
  `sale_createdby` int(11) NOT NULL,
  `sale_date` date NOT NULL,
  `sale_location` int(11) NOT NULL,
  `sale_status` tinyint(4) NOT NULL DEFAULT '1',
  `sale_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_sale`
--

INSERT INTO `ezy_pos_sale` (`sale_id`, `sale_cus_id`, `sale_grandtotal`, `sale_subtotal`, `sale_discount`, `sale_less`, `sale_createdby`, `sale_date`, `sale_location`, `sale_status`, `sale_createdat`) VALUES
(1, 2, 1000.00, 1000.00, 0.00, 0.00, 89, '2018-10-03', 5, 1, '2018-10-03 07:02:40'),
(2, 2, 1000.00, 1000.00, 0.00, 0.00, 89, '2018-10-03', 5, 1, '2018-10-03 07:05:01'),
(3, 2, 10000.00, 10000.00, 0.00, 0.00, 89, '2018-10-03', 5, 1, '2018-10-03 07:06:13'),
(4, 2, 1000.00, 1000.00, 0.00, 0.00, 89, '2018-10-03', 5, 1, '2018-10-03 07:19:36'),
(5, 4, 22062.50, 44125.00, 50.00, 0.00, 89, '2019-10-04', 4, 1, '2019-10-04 05:46:13'),
(6, 6, 9450.00, 9450.00, 0.00, 0.00, 89, '2019-10-15', 6, 1, '2019-10-15 06:49:20'),
(7, 11, 12825.00, 14250.00, 10.00, 0.00, 89, '2019-10-31', 6, 1, '2019-10-16 03:48:17');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_sale_item`
--

CREATE TABLE `ezy_pos_sale_item` (
  `saleitem_id` int(11) NOT NULL,
  `saleitem_sale_id` int(11) NOT NULL,
  `saleitem_item_id` int(11) NOT NULL,
  `saleitem_price` double(50,2) NOT NULL,
  `saleitem_quantity` double(250,2) NOT NULL,
  `saleitem_total` double(50,2) NOT NULL,
  `saleitem_discount` double(5,2) NOT NULL,
  `saleitem_ctreatedat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_sale_item`
--

INSERT INTO `ezy_pos_sale_item` (`saleitem_id`, `saleitem_sale_id`, `saleitem_item_id`, `saleitem_price`, `saleitem_quantity`, `saleitem_total`, `saleitem_discount`, `saleitem_ctreatedat`) VALUES
(1, 1, 21, 1000.00, 1.00, 1000.00, 0.00, '2018-10-03 07:02:41'),
(2, 2, 21, 1000.00, 1.00, 1000.00, 0.00, '2018-10-03 07:05:02'),
(3, 3, 21, 1000.00, 10.00, 10000.00, 0.00, '2018-10-03 07:06:14'),
(4, 4, 21, 1000.00, 1.00, 1000.00, 0.00, '2018-10-03 07:19:36'),
(5, 5, 27, 1500.00, 20.00, 27000.00, 10.00, '2019-10-04 05:46:27'),
(6, 5, 23, 1000.00, 10.00, 10000.00, 0.00, '2019-10-04 05:46:30'),
(7, 5, 27, 1500.00, 5.00, 7125.00, 5.00, '2019-10-04 05:46:34'),
(8, 6, 27, 1500.00, 2.00, 2700.00, 10.00, '2019-10-15 06:49:42'),
(9, 6, 28, 150.00, 50.00, 6750.00, 10.00, '2019-10-15 06:49:44'),
(10, 7, 29, 1500.00, 10.00, 13500.00, 10.00, '2019-10-16 03:48:32'),
(11, 7, 28, 150.00, 5.00, 750.00, 0.00, '2019-10-16 03:48:34');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_staffs`
--

CREATE TABLE `ezy_pos_staffs` (
  `staff_id` int(11) NOT NULL,
  `staff_name` varchar(250) NOT NULL,
  `staff_address` varchar(250) NOT NULL,
  `staff_contact` varchar(20) NOT NULL,
  `staff_basicsalary` double(50,2) NOT NULL,
  `staff_food` double(50,2) NOT NULL,
  `staff_travel` double(50,2) NOT NULL,
  `staff_other` double(50,2) NOT NULL,
  `staff_otperhour` double(50,2) NOT NULL,
  `staff_status` int(1) NOT NULL DEFAULT '1',
  `staff_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_staffs`
--

INSERT INTO `ezy_pos_staffs` (`staff_id`, `staff_name`, `staff_address`, `staff_contact`, `staff_basicsalary`, `staff_food`, `staff_travel`, `staff_other`, `staff_otperhour`, `staff_status`, `staff_createdat`) VALUES
(1, 'STAFF Wish', '125/25 church lane,', '0768453735', 50000.00, 5000.00, 3000.00, 1000.00, 450.00, 1, '2019-10-11 06:04:33'),
(2, 'Susee', 'col', 'wewewewewewwwwscs', 25.00, 0.00, 80.20, 0.00, 100.00, 1, '2019-10-14 11:47:19'),
(3, 'wqw', 'qwqw', 'qwqwqdsfdsfsfsdfsdfs', 343.23, 343.23, 343.23, 343.23, 343.23, 0, '2019-10-14 11:52:21');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_stock`
--

CREATE TABLE `ezy_pos_stock` (
  `stock_id` int(11) NOT NULL,
  `stock_itm_id` int(11) NOT NULL,
  `stock_qty` double(30,2) NOT NULL,
  `stock_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_stock`
--

INSERT INTO `ezy_pos_stock` (`stock_id`, `stock_itm_id`, `stock_qty`, `stock_status`) VALUES
(1, 21, 186.00, 1),
(2, 23, -3.00, 1),
(3, 0, 0.00, 1),
(4, 0, 0.00, 1),
(5, 27, 3.00, 1),
(6, 0, 0.00, 1),
(7, 0, 0.00, 1),
(8, 0, 0.00, 1),
(9, 28, -55.00, 1),
(10, 29, -10.00, 1),
(11, 30, 0.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_stock_log`
--

CREATE TABLE `ezy_pos_stock_log` (
  `stocklog_id` int(11) NOT NULL,
  `stocklog_itmid` int(11) NOT NULL,
  `stocklog_qty` double(250,2) NOT NULL,
  `stocklog_grnID` int(11) NOT NULL,
  `stocklog_saleID` int(11) NOT NULL,
  `stocklog_return_sup_retrnID` int(11) NOT NULL,
  `stocklog_return_supID` int(11) NOT NULL DEFAULT '0',
  `stocklog_return_cus_retrnID` int(11) NOT NULL,
  `stocklog_return_cusID` int(11) NOT NULL DEFAULT '0',
  `stocklog_status` tinyint(1) NOT NULL,
  `stocklog_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_stock_log`
--

INSERT INTO `ezy_pos_stock_log` (`stocklog_id`, `stocklog_itmid`, `stocklog_qty`, `stocklog_grnID`, `stocklog_saleID`, `stocklog_return_sup_retrnID`, `stocklog_return_supID`, `stocklog_return_cus_retrnID`, `stocklog_return_cusID`, `stocklog_status`, `stocklog_createdat`) VALUES
(1, 21, 200.00, 1, 0, 0, 0, 0, 0, 1, '2018-09-05 08:01:31'),
(2, 21, 200.00, 2, 0, 0, 0, 0, 0, 1, '2018-09-05 08:04:06'),
(3, 22, 600.00, 2, 0, 0, 0, 0, 0, 1, '2018-09-05 08:04:06'),
(4, 21, 200.00, 3, 0, 0, 0, 0, 0, 1, '2018-09-05 08:12:12'),
(5, 22, 200.00, 4, 0, 0, 0, 0, 0, 1, '2018-09-05 08:15:58'),
(6, 21, 200.00, 5, 0, 0, 0, 0, 0, 1, '2018-09-05 08:28:11'),
(7, 21, 200.00, 6, 0, 0, 0, 0, 0, 1, '2018-09-05 08:35:59'),
(8, 21, 200.00, 7, 0, 0, 0, 0, 0, 1, '2018-09-05 08:38:49'),
(9, 21, 200.00, 8, 0, 0, 0, 0, 0, 1, '2018-09-05 08:40:16'),
(10, 22, 200.00, 9, 0, 0, 0, 0, 0, 1, '2018-09-05 11:23:30'),
(11, 22, 10.00, 10, 0, 0, 0, 0, 0, 1, '2018-09-05 11:39:05'),
(12, 21, 100.00, 11, 0, 0, 0, 0, 0, 1, '2018-09-05 11:52:09'),
(13, 21, 10.00, 0, 1, 0, 0, 0, 0, 1, '2018-09-05 18:41:55'),
(14, 22, 10.00, 0, 2, 0, 0, 0, 0, 1, '2018-09-05 21:17:02'),
(15, 21, 5.00, 0, 2, 0, 0, 0, 0, 1, '2018-09-05 21:17:02'),
(16, 21, 200.00, 12, 0, 0, 0, 0, 0, 1, '2018-09-07 06:20:45'),
(17, 21, 200.00, 13, 0, 0, 0, 0, 0, 1, '2018-09-07 06:22:32'),
(18, 21, 30.00, 14, 0, 0, 0, 0, 0, 1, '2018-09-07 09:12:55'),
(19, 21, 200.00, 15, 0, 0, 0, 0, 0, 1, '2018-09-07 09:16:01'),
(20, 21, 20.00, 16, 0, 0, 0, 0, 0, 1, '2018-09-07 09:20:55'),
(21, 21, 1.00, 17, 0, 0, 0, 0, 0, 1, '2018-09-07 09:38:16'),
(22, 21, 30.00, 18, 0, 0, 0, 0, 0, 1, '2018-09-07 09:41:19'),
(23, 21, 100.00, 19, 0, 0, 0, 0, 0, 1, '2018-09-07 09:44:34'),
(24, 21, 200.00, 20, 0, 0, 0, 0, 0, 1, '2018-09-07 09:46:40'),
(25, 21, 200.00, 21, 0, 0, 0, 0, 0, 1, '2018-09-07 09:48:29'),
(26, 21, 200.00, 22, 0, 0, 0, 0, 0, 1, '2018-09-07 09:55:52'),
(27, 21, 200.00, 23, 0, 0, 0, 0, 0, 1, '2018-09-07 10:00:19'),
(28, 21, 200.00, 24, 0, 0, 0, 0, 0, 1, '2018-09-07 10:01:59'),
(29, 21, 200.00, 25, 0, 0, 0, 0, 0, 1, '2018-09-07 10:04:05'),
(30, 21, 200.00, 26, 0, 0, 0, 0, 0, 1, '2018-09-07 10:08:24'),
(31, 21, 200.00, 27, 0, 0, 0, 0, 0, 1, '2018-09-07 10:13:59'),
(32, 21, 200.00, 28, 0, 0, 0, 0, 0, 1, '2018-09-07 11:03:59'),
(33, 21, 200.00, 1, 0, 0, 0, 0, 0, 1, '2018-09-07 11:15:51'),
(34, 21, 200.00, 2, 0, 0, 0, 0, 0, 1, '2018-09-07 11:20:31'),
(35, 22, 200.00, 2, 0, 0, 0, 0, 0, 1, '2018-09-07 11:20:31'),
(36, 21, 200.00, 1, 0, 0, 0, 0, 0, 1, '2018-09-07 11:31:49'),
(37, 21, 200.00, 2, 0, 0, 0, 0, 0, 1, '2018-09-07 11:32:33'),
(38, 22, 200.00, 2, 0, 0, 0, 0, 0, 1, '2018-09-07 11:32:34'),
(39, 21, 10.00, 0, 1, 0, 0, 0, 0, 1, '2018-09-07 11:33:17'),
(40, 22, 10.00, 0, 2, 0, 0, 0, 0, 1, '2018-09-07 11:34:59'),
(41, 21, 10.00, 0, 2, 0, 0, 0, 0, 1, '2018-09-07 11:34:59'),
(42, 21, 200.00, 3, 0, 0, 0, 0, 0, 1, '2018-09-07 11:45:43'),
(43, 21, 200.00, 4, 0, 0, 0, 0, 0, 1, '2018-09-07 11:50:53'),
(44, 21, 30.00, 5, 0, 0, 0, 0, 0, 1, '2018-09-07 12:07:32'),
(45, 21, 200.00, 6, 0, 0, 0, 0, 0, 1, '2018-09-11 04:49:41'),
(46, 21, 200.00, 7, 0, 0, 0, 0, 0, 1, '2018-09-11 04:51:35'),
(47, 21, 600.00, 8, 0, 0, 0, 0, 0, 1, '2018-09-11 04:52:44'),
(48, 21, 400.00, 9, 0, 0, 0, 0, 0, 1, '2018-09-11 04:53:51'),
(49, 21, 400.00, 10, 0, 0, 0, 0, 0, 1, '2018-09-11 04:54:00'),
(50, 21, 400.00, 11, 0, 0, 0, 0, 0, 1, '2018-09-11 04:54:16'),
(51, 21, 400.00, 12, 0, 0, 0, 0, 0, 1, '2018-09-11 04:59:24'),
(52, 21, 400.00, 13, 0, 0, 0, 0, 0, 1, '2018-09-11 04:59:38'),
(53, 21, 400.00, 14, 0, 0, 0, 0, 0, 1, '2018-09-11 04:59:52'),
(54, 21, 200.00, 15, 0, 0, 0, 0, 0, 1, '2018-09-11 05:02:05'),
(55, 21, 10.00, 16, 0, 0, 0, 0, 0, 1, '2018-09-11 05:08:48'),
(56, 21, 10.00, 17, 0, 0, 0, 0, 0, 1, '2018-09-11 05:08:52'),
(57, 21, 10.00, 18, 0, 0, 0, 0, 0, 1, '2018-09-11 05:08:58'),
(58, 21, 10.00, 19, 0, 0, 0, 0, 0, 1, '2018-09-11 05:10:33'),
(59, 21, 10.00, 20, 0, 0, 0, 0, 0, 1, '2018-09-11 05:10:41'),
(60, 21, 10.00, 21, 0, 0, 0, 0, 0, 1, '2018-09-11 05:21:58'),
(61, 21, 10.00, 0, 3, 0, 0, 0, 0, 1, '2018-09-11 05:56:15'),
(62, 21, 10.00, 0, 4, 0, 0, 0, 0, 1, '2018-09-11 06:04:54'),
(63, 21, 10.00, 0, 5, 0, 0, 0, 0, 1, '2018-09-11 06:15:01'),
(64, 21, 10.00, 0, 6, 0, 0, 0, 0, 1, '2018-09-11 06:18:37'),
(65, 21, 10.00, 0, 7, 0, 0, 0, 0, 1, '2018-09-11 06:22:30'),
(66, 21, 10.00, 0, 8, 0, 0, 0, 0, 1, '2018-09-11 06:36:36'),
(67, 21, 10.00, 0, 9, 0, 0, 0, 0, 1, '2018-09-11 06:37:01'),
(68, 21, 10.00, 0, 10, 0, 0, 0, 0, 1, '2018-09-11 06:39:49'),
(69, 21, 1.00, 0, 11, 0, 0, 0, 0, 1, '2018-09-11 06:40:43'),
(70, 21, 1.00, 0, 12, 0, 0, 0, 0, 1, '2018-09-11 06:44:44'),
(71, 21, 1.00, 0, 13, 0, 0, 0, 0, 1, '2018-09-11 06:45:42'),
(72, 21, 1.00, 0, 14, 0, 0, 0, 0, 1, '2018-09-11 06:45:51'),
(73, 21, 1.00, 0, 15, 0, 0, 0, 0, 1, '2018-09-11 06:48:35'),
(74, 21, 1.00, 0, 16, 0, 0, 0, 0, 1, '2018-09-11 06:57:55'),
(75, 21, 1.00, 0, 17, 0, 0, 0, 0, 1, '2018-09-11 06:59:40'),
(76, 21, 1.00, 0, 18, 0, 0, 0, 0, 1, '2018-09-11 07:06:21'),
(77, 21, 1.00, 0, 19, 0, 0, 0, 0, 1, '2018-09-11 07:06:32'),
(78, 21, 10.00, 0, 20, 0, 0, 0, 0, 1, '2018-09-11 07:33:01'),
(79, 21, 1.00, 0, 21, 0, 0, 0, 0, 1, '2018-09-11 07:33:48'),
(80, 21, 1.00, 0, 22, 0, 0, 0, 0, 1, '2018-09-11 07:34:56'),
(81, 21, 1.00, 0, 23, 0, 0, 0, 0, 1, '2018-09-11 07:36:14'),
(82, 21, 1.00, 0, 24, 0, 0, 0, 0, 1, '2018-09-11 07:38:16'),
(83, 21, 1.00, 0, 25, 0, 0, 0, 0, 1, '2018-09-11 07:39:26'),
(84, 21, 1.00, 0, 26, 0, 0, 0, 0, 1, '2018-09-11 07:47:09'),
(85, 21, 1.00, 0, 27, 0, 0, 0, 0, 1, '2018-09-11 07:54:08'),
(86, 21, 1.00, 0, 28, 0, 0, 0, 0, 1, '2018-09-11 07:55:09'),
(87, 21, 1.00, 0, 29, 0, 0, 0, 0, 1, '2018-09-11 08:47:17'),
(88, 21, 1.00, 0, 30, 0, 0, 0, 0, 1, '2018-09-11 08:47:59'),
(89, 21, 1.00, 0, 31, 0, 0, 0, 0, 1, '2018-09-11 08:49:04'),
(90, 21, 1.00, 0, 32, 0, 0, 0, 0, 1, '2018-09-11 08:50:14'),
(91, 21, 1.00, 0, 33, 0, 0, 0, 0, 1, '2018-09-11 08:55:09'),
(92, 21, 1.00, 0, 34, 0, 0, 0, 0, 1, '2018-09-11 08:56:40'),
(93, 21, 1.00, 0, 35, 0, 0, 0, 0, 1, '2018-09-11 09:13:31'),
(94, 21, 1.00, 0, 36, 0, 0, 0, 0, 1, '2018-09-11 09:14:47'),
(95, 21, 1.00, 0, 37, 0, 0, 0, 0, 1, '2018-09-11 09:14:56'),
(96, 21, 1.00, 0, 38, 0, 0, 0, 0, 1, '2018-09-11 09:15:13'),
(97, 21, 1.00, 0, 39, 0, 0, 0, 0, 1, '2018-09-11 09:18:09'),
(98, 21, 1.00, 0, 40, 0, 0, 0, 0, 1, '2018-09-11 09:18:26'),
(99, 21, 1.00, 0, 41, 0, 0, 0, 0, 1, '2018-09-11 09:21:48'),
(100, 21, 1.00, 0, 42, 0, 0, 0, 0, 1, '2018-09-11 09:22:00'),
(101, 21, 1.00, 0, 43, 0, 0, 0, 0, 1, '2018-09-11 09:22:50'),
(102, 21, 1.00, 0, 44, 0, 0, 0, 0, 1, '2018-09-11 09:23:23'),
(103, 21, 1.00, 0, 45, 0, 0, 0, 0, 1, '2018-09-11 09:30:47'),
(104, 21, 1.00, 0, 46, 0, 0, 0, 0, 1, '2018-09-11 09:32:59'),
(105, 21, 1.00, 0, 47, 0, 0, 0, 0, 1, '2018-09-11 09:33:14'),
(106, 21, 1.00, 0, 48, 0, 0, 0, 0, 1, '2018-09-11 09:37:50'),
(107, 21, 1.00, 0, 49, 0, 0, 0, 0, 1, '2018-09-11 09:42:53'),
(108, 21, 1.00, 0, 50, 0, 0, 0, 0, 1, '2018-09-11 09:43:11'),
(109, 21, 1.00, 0, 51, 0, 0, 0, 0, 1, '2018-09-11 09:43:48'),
(110, 21, 1.00, 0, 52, 0, 0, 0, 0, 1, '2018-09-11 09:44:28'),
(111, 21, 1.00, 0, 53, 0, 0, 0, 0, 1, '2018-09-11 09:45:34'),
(112, 21, 1.00, 0, 54, 0, 0, 0, 0, 1, '2018-09-11 09:52:01'),
(113, 21, 1.00, 0, 55, 0, 0, 0, 0, 1, '2018-09-11 09:52:24'),
(114, 21, 1.00, 0, 56, 0, 0, 0, 0, 1, '2018-09-11 09:53:30'),
(115, 21, 1.00, 0, 57, 0, 0, 0, 0, 1, '2018-09-11 09:55:01'),
(116, 21, 1.00, 0, 58, 0, 0, 0, 0, 1, '2018-09-11 09:56:19'),
(117, 21, 1.00, 0, 59, 0, 0, 0, 0, 1, '2018-09-11 09:56:52'),
(118, 21, 1.00, 0, 60, 0, 0, 0, 0, 1, '2018-09-11 09:58:01'),
(119, 21, 1.00, 0, 61, 0, 0, 0, 0, 1, '2018-09-11 09:59:49'),
(120, 21, 1.00, 0, 62, 0, 0, 0, 0, 1, '2018-09-11 10:01:56'),
(121, 21, 1.00, 0, 63, 0, 0, 0, 0, 1, '2018-09-11 10:03:08'),
(122, 21, 1.00, 0, 64, 0, 0, 0, 0, 1, '2018-09-11 10:09:56'),
(123, 21, 1.00, 0, 65, 0, 0, 0, 0, 1, '2018-09-11 10:10:28'),
(124, 21, 1.00, 0, 66, 0, 0, 0, 0, 1, '2018-09-11 10:11:09'),
(125, 21, 1.00, 0, 67, 0, 0, 0, 0, 1, '2018-09-11 10:11:31'),
(126, 21, 1.00, 0, 68, 0, 0, 0, 0, 1, '2018-09-11 10:19:08'),
(127, 21, 1.00, 0, 69, 0, 0, 0, 0, 1, '2018-09-11 10:22:55'),
(128, 21, 1.00, 0, 70, 0, 0, 0, 0, 1, '2018-09-11 10:23:14'),
(129, 21, 1.00, 0, 71, 0, 0, 0, 0, 1, '2018-09-11 10:24:28'),
(130, 21, 1.00, 0, 72, 0, 0, 0, 0, 1, '2018-09-11 10:27:41'),
(131, 21, 1.00, 0, 73, 0, 0, 0, 0, 1, '2018-09-11 10:30:34'),
(132, 21, 1.00, 0, 74, 0, 0, 0, 0, 1, '2018-09-11 10:31:38'),
(133, 21, 1.00, 0, 75, 0, 0, 0, 0, 1, '2018-09-11 10:32:35'),
(134, 21, 1.00, 0, 76, 0, 0, 0, 0, 1, '2018-09-11 10:33:31'),
(135, 21, 1.00, 0, 77, 0, 0, 0, 0, 1, '2018-09-11 10:38:12'),
(136, 21, 1.00, 0, 78, 0, 0, 0, 0, 1, '2018-09-11 10:38:40'),
(137, 21, 1.00, 0, 79, 0, 0, 0, 0, 1, '2018-09-11 10:39:37'),
(138, 21, 1.00, 0, 80, 0, 0, 0, 0, 1, '2018-09-11 10:43:24'),
(139, 21, 1.00, 0, 81, 0, 0, 0, 0, 1, '2018-09-11 10:59:08'),
(140, 21, 1.00, 0, 82, 0, 0, 0, 0, 1, '2018-09-11 10:59:49'),
(141, 21, 1.00, 0, 83, 0, 0, 0, 0, 1, '2018-09-11 11:01:45'),
(142, 21, 1.00, 0, 84, 0, 0, 0, 0, 1, '2018-09-11 11:02:10'),
(143, 21, 1.00, 0, 85, 0, 0, 0, 0, 1, '2018-09-11 11:04:32'),
(144, 21, 1.00, 0, 86, 0, 0, 0, 0, 1, '2018-09-11 11:05:02'),
(145, 21, 1.00, 0, 87, 0, 0, 0, 0, 1, '2018-09-11 11:06:21'),
(146, 21, 1.00, 0, 88, 0, 0, 0, 0, 1, '2018-09-11 11:06:56'),
(147, 21, 1.00, 0, 89, 0, 0, 0, 0, 1, '2018-09-11 11:07:28'),
(148, 21, 1.00, 0, 90, 0, 0, 0, 0, 1, '2018-09-11 11:07:49'),
(149, 21, 1.00, 0, 91, 0, 0, 0, 0, 1, '2018-09-11 11:08:02'),
(150, 21, 1.00, 0, 92, 0, 0, 0, 0, 1, '2018-09-11 11:23:07'),
(151, 21, 1.00, 0, 93, 0, 0, 0, 0, 1, '2018-09-11 11:23:38'),
(152, 21, 1.00, 0, 94, 0, 0, 0, 0, 1, '2018-09-11 11:23:52'),
(153, 21, 1.00, 0, 95, 0, 0, 0, 0, 1, '2018-09-11 11:29:03'),
(154, 21, 1.00, 0, 96, 0, 0, 0, 0, 1, '2018-09-11 11:31:49'),
(155, 21, 1.00, 0, 97, 0, 0, 0, 0, 1, '2018-09-11 11:32:58'),
(156, 21, 1.00, 0, 98, 0, 0, 0, 0, 1, '2018-09-11 11:37:56'),
(157, 21, 1.00, 0, 99, 0, 0, 0, 0, 1, '2018-09-11 11:38:51'),
(158, 21, 1.00, 0, 100, 0, 0, 0, 0, 1, '2018-09-11 11:40:17'),
(159, 21, 1.00, 0, 101, 0, 0, 0, 0, 1, '2018-09-11 11:40:47'),
(160, 21, 1.00, 0, 102, 0, 0, 0, 0, 1, '2018-09-11 11:41:47'),
(161, 21, 1.00, 0, 103, 0, 0, 0, 0, 1, '2018-09-11 11:43:33'),
(162, 21, 1.00, 0, 104, 0, 0, 0, 0, 1, '2018-09-11 11:43:55'),
(163, 21, 1.00, 0, 105, 0, 0, 0, 0, 1, '2018-09-11 11:44:36'),
(164, 21, 1.00, 0, 106, 0, 0, 0, 0, 1, '2018-09-11 11:45:26'),
(165, 21, 1.00, 0, 107, 0, 0, 0, 0, 1, '2018-09-11 12:00:01'),
(166, 21, 1.00, 0, 108, 0, 0, 0, 0, 1, '2018-09-11 12:00:43'),
(167, 21, 1.00, 0, 109, 0, 0, 0, 0, 1, '2018-09-11 12:05:01'),
(168, 21, 1.00, 0, 110, 0, 0, 0, 0, 1, '2018-09-11 12:05:19'),
(169, 21, 1.00, 0, 111, 0, 0, 0, 0, 1, '2018-09-11 12:08:05'),
(170, 21, 1.00, 0, 112, 0, 0, 0, 0, 1, '2018-09-11 12:20:54'),
(171, 21, 1.00, 0, 113, 0, 0, 0, 0, 1, '2018-09-11 12:23:46'),
(172, 21, 1.00, 0, 114, 0, 0, 0, 0, 1, '2018-09-11 12:26:39'),
(173, 21, 1.00, 0, 115, 0, 0, 0, 0, 1, '2018-09-11 12:28:15'),
(174, 21, 1.00, 0, 116, 0, 0, 0, 0, 1, '2018-09-11 12:29:19'),
(175, 21, 1.00, 0, 117, 0, 0, 0, 0, 1, '2018-09-11 12:35:44'),
(176, 21, 1.00, 0, 118, 0, 0, 0, 0, 1, '2018-09-11 12:39:19'),
(177, 21, 1.00, 0, 119, 0, 0, 0, 0, 1, '2018-09-11 12:39:50'),
(178, 21, 1.00, 0, 120, 0, 0, 0, 0, 1, '2018-09-11 12:43:09'),
(179, 21, 1.00, 0, 121, 0, 0, 0, 0, 1, '2018-09-11 12:47:19'),
(180, 21, 1.00, 0, 122, 0, 0, 0, 0, 1, '2018-09-11 12:47:52'),
(181, 21, 1.00, 0, 123, 0, 0, 0, 0, 1, '2018-09-11 12:54:00'),
(182, 21, 1.00, 0, 124, 0, 0, 0, 0, 1, '2018-09-11 12:54:21'),
(183, 21, 1.00, 0, 125, 0, 0, 0, 0, 1, '2018-09-11 12:54:35'),
(184, 21, 1.00, 0, 126, 0, 0, 0, 0, 1, '2018-09-11 12:54:38'),
(185, 21, 1.00, 0, 127, 0, 0, 0, 0, 1, '2018-09-11 12:54:40'),
(186, 21, 1.00, 0, 128, 0, 0, 0, 0, 1, '2018-09-11 12:54:43'),
(187, 21, 1.00, 0, 129, 0, 0, 0, 0, 1, '2018-09-11 12:54:46'),
(188, 21, 1.00, 0, 130, 0, 0, 0, 0, 1, '2018-09-11 12:54:50'),
(189, 21, 1.00, 0, 131, 0, 0, 0, 0, 1, '2018-09-11 12:54:52'),
(190, 21, 1.00, 0, 132, 0, 0, 0, 0, 1, '2018-09-11 12:54:54'),
(191, 21, 1.00, 0, 133, 0, 0, 0, 0, 1, '2018-09-11 12:54:55'),
(192, 21, 1.00, 0, 134, 0, 0, 0, 0, 1, '2018-09-11 12:54:57'),
(193, 21, 1.00, 0, 135, 0, 0, 0, 0, 1, '2018-09-11 12:54:58'),
(194, 21, 1.00, 0, 136, 0, 0, 0, 0, 1, '2018-09-11 12:54:59'),
(195, 21, 1.00, 0, 137, 0, 0, 0, 0, 1, '2018-09-11 12:55:01'),
(196, 21, 1.00, 0, 138, 0, 0, 0, 0, 1, '2018-09-11 12:55:02'),
(197, 21, 1.00, 0, 139, 0, 0, 0, 0, 1, '2018-09-11 12:55:03'),
(198, 21, 1.00, 0, 140, 0, 0, 0, 0, 1, '2018-09-11 12:55:05'),
(199, 21, 1.00, 0, 141, 0, 0, 0, 0, 1, '2018-09-11 13:02:11'),
(200, 21, 1.00, 0, 142, 0, 0, 0, 0, 1, '2018-09-11 13:02:13'),
(201, 21, 1.00, 0, 143, 0, 0, 0, 0, 1, '2018-09-11 13:02:14'),
(202, 21, 1.00, 0, 144, 0, 0, 0, 0, 1, '2018-09-11 13:02:24'),
(203, 21, 1.00, 0, 145, 0, 0, 0, 0, 1, '2018-09-11 13:02:25'),
(204, 21, 1.00, 0, 146, 0, 0, 0, 0, 1, '2018-09-11 13:02:25'),
(205, 21, 1.00, 0, 147, 0, 0, 0, 0, 1, '2018-09-11 13:02:26'),
(206, 21, 1.00, 0, 148, 0, 0, 0, 0, 1, '2018-09-11 13:02:27'),
(207, 21, 1.00, 0, 149, 0, 0, 0, 0, 1, '2018-09-11 13:02:28'),
(208, 21, 1.00, 0, 150, 0, 0, 0, 0, 1, '2018-09-11 13:02:29'),
(209, 21, 1.00, 0, 151, 0, 0, 0, 0, 1, '2018-09-11 13:02:31'),
(210, 21, 1.00, 0, 152, 0, 0, 0, 0, 1, '2018-09-11 13:02:43'),
(211, 21, 1.00, 0, 153, 0, 0, 0, 0, 1, '2018-09-11 13:03:16'),
(212, 21, 1.00, 0, 154, 0, 0, 0, 0, 1, '2018-09-11 13:03:22'),
(213, 21, 1.00, 0, 155, 0, 0, 0, 0, 1, '2018-09-11 13:03:25'),
(214, 21, 1.00, 0, 156, 0, 0, 0, 0, 1, '2018-09-11 13:03:29'),
(215, 21, 1.00, 0, 157, 0, 0, 0, 0, 1, '2018-09-11 13:03:33'),
(216, 21, 1.00, 0, 158, 0, 0, 0, 0, 1, '2018-09-11 13:03:36'),
(217, 21, 1.00, 0, 159, 0, 0, 0, 0, 1, '2018-09-11 13:03:39'),
(218, 21, 1.00, 0, 160, 0, 0, 0, 0, 1, '2018-09-11 13:03:42'),
(219, 21, 1.00, 0, 161, 0, 0, 0, 0, 1, '2018-09-11 13:03:45'),
(220, 21, 1.00, 0, 162, 0, 0, 0, 0, 1, '2018-09-11 13:03:49'),
(221, 21, 1.00, 0, 163, 0, 0, 0, 0, 1, '2018-09-11 13:03:53'),
(222, 21, 1.00, 0, 164, 0, 0, 0, 0, 1, '2018-09-11 13:04:03'),
(223, 21, 1.00, 0, 165, 0, 0, 0, 0, 1, '2018-09-11 13:04:13'),
(224, 21, 1.00, 0, 166, 0, 0, 0, 0, 1, '2018-09-11 13:04:22'),
(225, 21, 1.00, 0, 167, 0, 0, 0, 0, 1, '2018-09-11 13:04:28'),
(226, 21, 1.00, 0, 168, 0, 0, 0, 0, 1, '2018-09-12 04:34:24'),
(227, 22, 1.00, 0, 168, 0, 0, 0, 0, 1, '2018-09-12 04:34:25'),
(228, 21, 1.00, 0, 169, 0, 0, 0, 0, 1, '2018-09-12 04:35:21'),
(229, 22, 1.00, 0, 169, 0, 0, 0, 0, 1, '2018-09-12 04:35:21'),
(230, 21, 1.00, 0, 170, 0, 0, 0, 0, 1, '2018-09-12 04:35:45'),
(231, 22, 1.00, 0, 170, 0, 0, 0, 0, 1, '2018-09-12 04:35:46'),
(232, 23, 1000.00, 22, 0, 0, 0, 0, 0, 1, '2018-09-12 04:40:01'),
(233, 21, 1.00, 0, 171, 0, 0, 0, 0, 1, '2018-09-12 04:41:08'),
(234, 22, 1.00, 0, 171, 0, 0, 0, 0, 1, '2018-09-12 04:41:08'),
(235, 23, 1.00, 0, 171, 0, 0, 0, 0, 1, '2018-09-12 04:41:09'),
(236, 21, 1.00, 0, 172, 0, 0, 0, 0, 1, '2018-09-12 04:43:27'),
(237, 22, 1.00, 0, 172, 0, 0, 0, 0, 1, '2018-09-12 04:43:27'),
(238, 23, 1.00, 0, 172, 0, 0, 0, 0, 1, '2018-09-12 04:43:28'),
(239, 21, 1.00, 0, 173, 0, 0, 0, 0, 1, '2018-09-12 04:51:03'),
(240, 22, 1.00, 0, 173, 0, 0, 0, 0, 1, '2018-09-12 04:51:03'),
(241, 23, 1.00, 0, 173, 0, 0, 0, 0, 1, '2018-09-12 04:51:03'),
(242, 21, 1.00, 0, 174, 0, 0, 0, 0, 1, '2018-09-12 04:51:39'),
(243, 22, 1.00, 0, 174, 0, 0, 0, 0, 1, '2018-09-12 04:51:39'),
(244, 23, 1.00, 0, 174, 0, 0, 0, 0, 1, '2018-09-12 04:51:40'),
(245, 21, 3.00, 0, 175, 0, 0, 0, 0, 1, '2018-09-12 07:42:41'),
(246, 23, 200.00, 23, 0, 0, 0, 0, 0, 1, '2018-09-12 07:53:18'),
(247, 23, 600.00, 24, 0, 0, 0, 0, 0, 1, '2018-09-12 07:54:11'),
(248, 21, 1.00, 0, 176, 0, 0, 0, 0, 1, '2018-09-12 08:33:11'),
(249, 21, 1.00, 0, 177, 0, 0, 0, 0, 1, '2018-09-12 08:36:50'),
(250, 21, 1.00, 0, 178, 0, 0, 0, 0, 1, '2018-09-12 08:37:29'),
(251, 21, 1.00, 0, 179, 0, 0, 0, 0, 1, '2018-09-12 08:37:44'),
(252, 21, 1.00, 0, 180, 0, 0, 0, 0, 1, '2018-09-12 08:40:28'),
(253, 21, 1.00, 0, 181, 0, 0, 0, 0, 1, '2018-09-12 08:48:29'),
(254, 21, 1.00, 0, 182, 0, 0, 0, 0, 1, '2018-09-12 08:49:49'),
(255, 21, 1.00, 0, 183, 0, 0, 0, 0, 1, '2018-09-12 08:52:39'),
(256, 21, 1.00, 0, 184, 0, 0, 0, 0, 1, '2018-09-12 09:10:50'),
(257, 21, 2.00, 0, 185, 0, 0, 0, 0, 1, '2018-09-12 09:12:22'),
(258, 21, 1.00, 0, 186, 0, 0, 0, 0, 1, '2018-09-12 09:14:22'),
(259, 21, 1.00, 0, 187, 0, 0, 0, 0, 1, '2018-09-12 09:14:33'),
(260, 21, 1.00, 0, 188, 0, 0, 0, 0, 1, '2018-09-12 09:15:38'),
(261, 21, 2.00, 25, 0, 0, 0, 0, 0, 1, '2018-09-12 09:31:29'),
(262, 21, 3.00, 26, 0, 0, 0, 0, 0, 1, '2018-09-12 10:07:26'),
(263, 21, 3.00, 27, 0, 0, 0, 0, 0, 1, '2018-09-12 10:08:07'),
(264, 21, 1.00, 0, 189, 0, 0, 0, 0, 1, '2018-09-12 11:09:06'),
(265, 21, 1.00, 0, 190, 0, 0, 0, 0, 1, '2018-09-12 11:18:57'),
(266, 21, 1.00, 0, 0, 0, 0, 0, 0, 1, '2018-09-12 11:30:13'),
(267, 21, 1.00, 0, 191, 0, 0, 0, 0, 1, '2018-09-12 11:31:47'),
(268, 21, 1.00, 0, 192, 0, 0, 0, 0, 1, '2018-09-12 11:33:35'),
(269, 21, 2.00, 0, 193, 0, 0, 0, 0, 1, '2018-09-12 11:41:02'),
(270, 22, 1.00, 0, 193, 0, 0, 0, 0, 1, '2018-09-12 11:41:02'),
(271, 21, 1.00, 0, 194, 0, 0, 0, 0, 1, '2018-09-12 13:26:31'),
(272, 21, 1.00, 0, 195, 0, 0, 0, 0, 1, '2018-09-12 13:29:00'),
(273, 21, 1.00, 0, 196, 0, 0, 0, 0, 1, '2018-09-12 13:32:35'),
(274, 21, 1.00, 0, 197, 0, 0, 0, 0, 1, '2018-09-12 13:34:33'),
(275, 22, 1.00, 0, 197, 0, 0, 0, 0, 1, '2018-09-12 13:34:33'),
(276, 23, 1.00, 0, 197, 0, 0, 0, 0, 1, '2018-09-12 13:34:33'),
(277, 21, 1.00, 0, 198, 0, 0, 0, 0, 1, '2018-09-12 13:35:10'),
(278, 21, 1.00, 0, 199, 0, 0, 0, 0, 1, '2018-09-12 13:35:36'),
(279, 22, 1.00, 0, 199, 0, 0, 0, 0, 1, '2018-09-12 13:35:36'),
(280, 23, 1.00, 0, 199, 0, 0, 0, 0, 1, '2018-09-12 13:35:37'),
(281, 21, 1.00, 0, 200, 0, 0, 0, 0, 1, '2018-09-12 13:36:34'),
(282, 22, 1.00, 0, 200, 0, 0, 0, 0, 1, '2018-09-12 13:36:35'),
(283, 21, 1.00, 0, 201, 0, 0, 0, 0, 1, '2018-09-12 13:37:12'),
(284, 22, 1.00, 0, 201, 0, 0, 0, 0, 1, '2018-09-12 13:37:12'),
(285, 23, 1.00, 0, 201, 0, 0, 0, 0, 1, '2018-09-12 13:37:12'),
(286, 23, 1.00, 0, 202, 0, 0, 0, 0, 1, '2018-09-12 13:37:44'),
(287, 21, 2.00, 0, 203, 0, 0, 0, 0, 1, '2018-09-13 04:06:26'),
(288, 21, 1.00, 0, 204, 0, 0, 0, 0, 1, '2018-09-13 04:07:09'),
(289, 21, 1.00, 0, 205, 0, 0, 0, 0, 1, '2018-09-13 04:07:29'),
(290, 22, 1.00, 0, 205, 0, 0, 0, 0, 1, '2018-09-13 04:07:29'),
(291, 23, 1.00, 0, 205, 0, 0, 0, 0, 1, '2018-09-13 04:07:30'),
(292, 24, 1.00, 0, 206, 0, 0, 0, 0, 1, '2018-09-13 04:10:49'),
(293, 25, 1.00, 0, 206, 0, 0, 0, 0, 1, '2018-09-13 04:10:50'),
(294, 21, 1.00, 0, 206, 0, 0, 0, 0, 1, '2018-09-13 04:10:50'),
(295, 22, 2.00, 0, 206, 0, 0, 0, 0, 1, '2018-09-13 04:10:50'),
(296, 23, 1.00, 0, 206, 0, 0, 0, 0, 1, '2018-09-13 04:10:50'),
(297, 21, 1.00, 0, 207, 0, 0, 0, 0, 1, '2018-09-13 04:17:51'),
(298, 21, 1.00, 0, 208, 0, 0, 0, 0, 1, '2018-09-13 04:18:29'),
(299, 21, 1.00, 0, 209, 0, 0, 0, 0, 1, '2018-09-13 04:27:07'),
(300, 21, 1.00, 0, 210, 0, 0, 0, 0, 1, '2018-09-13 04:28:06'),
(301, 21, 1.00, 0, 211, 0, 0, 0, 0, 1, '2018-09-13 04:43:14'),
(302, 21, 1.00, 0, 212, 0, 0, 0, 0, 1, '2018-09-13 04:43:45'),
(303, 21, 1.00, 0, 213, 0, 0, 0, 0, 1, '2018-09-13 04:44:41'),
(304, 21, 1.00, 0, 214, 0, 0, 0, 0, 1, '2018-09-13 04:46:09'),
(305, 21, 1.00, 0, 215, 0, 0, 0, 0, 1, '2018-09-13 04:49:44'),
(306, 21, 1.00, 0, 216, 0, 0, 0, 0, 1, '2018-09-13 04:55:46'),
(307, 21, 1.00, 0, 217, 0, 0, 0, 0, 1, '2018-09-13 04:56:53'),
(308, 21, 1.00, 0, 218, 0, 0, 0, 0, 1, '2018-09-13 05:10:17'),
(309, 24, 1.00, 0, 219, 0, 0, 0, 0, 1, '2018-09-13 05:10:51'),
(310, 21, 1.00, 0, 220, 0, 0, 0, 0, 1, '2018-09-13 05:35:55'),
(311, 21, 1.00, 0, 221, 0, 0, 0, 0, 1, '2018-09-13 07:01:15'),
(312, 21, 1.00, 0, 222, 0, 0, 0, 0, 1, '2018-09-14 05:52:55'),
(313, 21, 1.00, 0, 223, 0, 0, 0, 0, 1, '2018-09-14 05:54:59'),
(314, 21, 1.00, 0, 1, 0, 0, 0, 0, 1, '2018-09-14 06:02:20'),
(315, 21, 1.00, 0, 2, 0, 0, 0, 0, 1, '2018-09-14 06:02:54'),
(316, 21, 1.00, 0, 3, 0, 0, 0, 0, 1, '2018-09-14 06:04:20'),
(317, 22, 1.00, 0, 4, 0, 0, 0, 0, 1, '2018-09-14 06:07:59'),
(318, 21, 1.00, 0, 5, 0, 0, 0, 0, 1, '2018-09-14 06:12:35'),
(319, 21, 1.00, 0, 6, 0, 0, 0, 0, 1, '2018-09-14 06:24:16'),
(320, 21, 1.00, 0, 7, 0, 0, 0, 0, 1, '2018-09-14 06:26:01'),
(321, 21, 1.00, 0, 8, 0, 0, 0, 0, 1, '2018-09-14 06:46:25'),
(322, 24, 1.00, 0, 9, 0, 0, 0, 0, 1, '2018-09-14 08:30:51'),
(323, 21, 1.00, 0, 10, 0, 0, 0, 0, 1, '2018-09-14 08:49:57'),
(324, 21, 1.00, 0, 11, 0, 0, 0, 0, 1, '2018-09-17 06:02:52'),
(325, 21, 1.00, 0, 12, 0, 0, 0, 0, 1, '2018-09-17 06:03:32'),
(326, 21, 1.00, 0, 13, 0, 0, 0, 0, 1, '2018-09-17 10:05:27'),
(327, 21, 1.00, 0, 14, 0, 0, 0, 0, 1, '2018-09-17 12:41:55'),
(328, 21, 1.00, 0, 1, 0, 0, 0, 0, 1, '2018-09-18 05:37:37'),
(329, 21, 1.00, 0, 2, 0, 0, 0, 0, 1, '2018-09-18 05:45:51'),
(330, 21, 1.00, 0, 3, 0, 0, 0, 0, 1, '2018-09-18 08:53:38'),
(331, 21, 1.00, 0, 1, 0, 0, 0, 0, 1, '2018-09-18 09:02:14'),
(332, 21, 1.00, 0, 2, 0, 0, 0, 0, 1, '2018-09-18 09:17:58'),
(333, 21, 1.00, 0, 3, 0, 0, 0, 0, 1, '2018-09-19 07:08:33'),
(334, 21, 1.00, 0, 4, 0, 0, 0, 0, 1, '2018-09-19 07:16:31'),
(335, 26, 1000.00, 28, 0, 0, 0, 0, 0, 1, '2018-09-19 10:23:10'),
(336, 26, 1.00, 0, 5, 0, 0, 0, 0, 1, '2018-09-19 10:24:06'),
(337, 26, 1.00, 0, 6, 0, 0, 0, 0, 1, '2018-09-19 10:24:41'),
(338, 21, 1.00, 0, 7, 0, 0, 0, 0, 1, '2018-09-20 08:00:43'),
(339, 21, 1.00, 0, 8, 0, 0, 0, 0, 1, '2018-09-20 08:04:10'),
(340, 21, 1.00, 0, 9, 0, 0, 0, 0, 1, '2018-09-20 08:04:30'),
(341, 21, 1.00, 0, 10, 0, 0, 0, 0, 1, '2018-09-20 08:06:59'),
(342, 21, 1.00, 0, 11, 0, 0, 0, 0, 1, '2018-09-20 09:12:50'),
(343, 21, 1.00, 0, 1, 0, 0, 0, 0, 1, '2018-09-21 08:05:32'),
(344, 21, 1.00, 0, 2, 0, 0, 0, 0, 1, '2018-09-21 08:05:55'),
(345, 21, 1.00, 0, 3, 0, 0, 0, 0, 1, '2018-09-21 08:06:03'),
(346, 21, 1.00, 0, 4, 0, 0, 0, 0, 1, '2018-09-21 09:26:29'),
(347, 21, 1.00, 0, 5, 0, 0, 0, 0, 1, '2018-09-21 09:28:49'),
(348, 21, 1.00, 0, 6, 0, 0, 0, 0, 1, '2018-09-25 12:08:05'),
(349, 21, 1.00, 0, 7, 0, 0, 0, 0, 1, '2018-09-25 12:27:53'),
(350, 21, 1.00, 0, 8, 0, 0, 0, 0, 1, '2018-09-26 09:23:03'),
(351, 21, 1.00, 0, 1, 0, 0, 0, 0, 1, '2018-09-26 09:25:28'),
(352, 22, 1.00, 0, 2, 0, 0, 0, 0, 1, '2018-09-26 09:25:43'),
(353, 21, 200.00, 29, 0, 0, 0, 0, 0, 1, '2018-09-27 05:33:18'),
(354, 21, 1.00, 0, 3, 0, 0, 0, 0, 1, '2018-09-27 05:38:30'),
(355, 21, 1.00, 0, 4, 0, 0, 0, 0, 1, '2018-09-27 05:52:50'),
(356, 21, 1.00, 0, 5, 0, 0, 0, 0, 1, '2018-09-27 06:01:59'),
(357, 21, 1000.00, 30, 0, 0, 0, 0, 0, 1, '2018-09-27 06:08:04'),
(358, 21, 1.00, 0, 6, 0, 0, 0, 0, 1, '2018-09-27 06:14:59'),
(359, 21, 1.00, 0, 7, 0, 0, 0, 0, 1, '2018-09-27 06:17:43'),
(360, 21, 1.00, 0, 8, 0, 0, 0, 0, 1, '2018-09-27 06:20:12'),
(361, 21, 1.00, 0, 9, 0, 0, 0, 0, 1, '2018-09-27 07:37:58'),
(362, 21, 1.00, 31, 0, 0, 0, 0, 0, 1, '2018-09-27 07:39:28'),
(363, 21, 200.00, 32, 0, 0, 0, 0, 0, 1, '2018-09-27 08:00:02'),
(364, 21, 200.00, 33, 0, 0, 0, 0, 0, 1, '2018-09-27 08:11:07'),
(365, 21, 200.00, 34, 0, 0, 0, 0, 0, 1, '2018-09-27 08:12:10'),
(366, 21, 1.00, 35, 0, 0, 0, 0, 0, 1, '2018-09-27 09:05:17'),
(367, 21, 1.00, 0, 10, 0, 0, 0, 0, 1, '2018-09-27 09:13:50'),
(368, 21, 1.00, 0, 11, 0, 0, 0, 0, 1, '2018-09-27 09:30:14'),
(369, 21, 1.00, 0, 12, 0, 0, 0, 0, 1, '2018-09-27 09:32:26'),
(370, 21, 1.00, 0, 13, 0, 0, 0, 0, 1, '2018-09-27 10:05:01'),
(371, 21, 1000.00, 36, 0, 0, 0, 0, 0, 1, '2018-10-01 05:50:01'),
(372, 21, 200.00, 37, 0, 0, 0, 0, 0, 1, '2018-10-01 05:51:19'),
(373, 21, 200.00, 38, 0, 0, 0, 0, 0, 1, '2018-10-01 05:52:15'),
(374, 21, 1.00, 39, 0, 0, 0, 0, 0, 1, '2018-10-01 05:53:25'),
(375, 21, 200.00, 1, 0, 0, 0, 0, 0, 1, '2018-10-01 06:13:29'),
(376, 21, 200.00, 2, 0, 0, 0, 0, 0, 1, '2018-10-01 06:36:48'),
(377, 21, 200.00, 3, 0, 0, 0, 0, 0, 1, '2018-10-01 06:40:03'),
(378, 21, 200.00, 4, 0, 0, 0, 0, 0, 1, '2018-10-01 06:56:13'),
(379, 21, 200.00, 5, 0, 0, 0, 0, 0, 1, '2018-10-01 06:57:20'),
(380, 21, 200.00, 6, 0, 0, 0, 0, 0, 1, '2018-10-01 07:17:52'),
(381, 21, 200.00, 1, 0, 0, 0, 0, 0, 1, '2018-10-03 04:14:38'),
(382, 21, 200.00, 1, 0, 0, 0, 0, 0, 1, '2018-10-03 04:22:43'),
(383, 21, 1.00, 2, 0, 0, 0, 0, 0, 1, '2018-10-03 06:41:24'),
(384, 0, 1.00, 0, 1, 0, 0, 0, 0, 1, '2018-10-03 06:56:02'),
(385, 21, 1.00, 0, 2, 0, 0, 0, 0, 1, '2018-10-03 06:59:32'),
(386, 21, 1.00, 0, 3, 0, 0, 0, 0, 1, '2018-10-03 07:00:09'),
(387, 21, 1.00, 0, 1, 0, 0, 0, 0, 1, '2018-10-03 07:02:41'),
(388, 21, 1.00, 0, 2, 0, 0, 0, 0, 1, '2018-10-03 07:05:02'),
(389, 21, 10.00, 0, 3, 0, 0, 0, 0, 1, '2018-10-03 07:06:14'),
(390, 21, 1.00, 0, 4, 0, 0, 0, 0, 1, '2018-10-03 07:19:36'),
(391, 23, 7.00, 0, 0, 0, 0, 0, 0, 1, '2019-10-03 04:51:47'),
(392, 0, 0.00, 0, 0, 0, 0, 0, 0, 1, '2019-10-03 04:51:48'),
(393, 0, 0.00, 0, 0, 0, 0, 0, 0, 1, '2019-10-03 04:51:50'),
(394, 27, 10.00, 0, 0, 0, 0, 0, 0, 1, '2019-10-04 05:32:10'),
(395, 0, 0.00, 0, 0, 0, 0, 0, 0, 1, '2019-10-04 05:32:12'),
(396, 27, 10.00, 0, 0, 0, 0, 0, 0, 1, '2019-10-04 05:33:33'),
(397, 0, 0.00, 0, 0, 0, 0, 0, 0, 1, '2019-10-04 05:33:35'),
(398, 27, 10.00, 0, 0, 0, 0, 0, 0, 1, '2019-10-04 05:36:20'),
(399, 0, 0.00, 0, 0, 0, 0, 0, 0, 1, '2019-10-04 05:36:22'),
(400, 27, 20.00, 0, 5, 0, 0, 0, 0, 1, '2019-10-04 05:46:30'),
(401, 23, 10.00, 0, 5, 0, 0, 0, 0, 1, '2019-10-04 05:46:33'),
(402, 27, 5.00, 0, 5, 0, 0, 0, 0, 1, '2019-10-04 05:46:36'),
(403, 27, 2.00, 0, 6, 0, 0, 0, 0, 1, '2019-10-15 06:49:43'),
(404, 28, 50.00, 0, 6, 0, 0, 0, 0, 1, '2019-10-15 06:49:45'),
(405, 29, 10.00, 0, 7, 0, 0, 0, 0, 1, '2019-10-16 03:48:34'),
(406, 28, 5.00, 0, 7, 0, 0, 0, 0, 1, '2019-10-16 03:48:37');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_stores`
--

CREATE TABLE `ezy_pos_stores` (
  `store_id` int(11) NOT NULL,
  `store_name` varchar(200) NOT NULL,
  `store_address` varchar(250) NOT NULL,
  `store_address2` varchar(250) NOT NULL,
  `store_tel` varchar(15) NOT NULL,
  `store_mobile` varchar(15) NOT NULL,
  `store_mobile2` varchar(15) NOT NULL,
  `store_fax` varchar(15) NOT NULL,
  `store_email` varchar(200) NOT NULL,
  `store_status` int(1) NOT NULL DEFAULT '1',
  `store_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_stores`
--

INSERT INTO `ezy_pos_stores` (`store_id`, `store_name`, `store_address`, `store_address2`, `store_tel`, `store_mobile`, `store_mobile2`, `store_fax`, `store_email`, `store_status`, `store_createdat`) VALUES
(4, 'Head Office', 'No 23/65', '2nd cross street, Colombo 11', '0111234567', '0771234567', '0771234567', '0771234567', 'example@gmail.com', 1, '2018-09-12 10:47:49'),
(5, 'Branch', 'No 14/34', '1st cross street', '0111234567', '0771234567', '0771234567', '0771234567', 'example@yahoo.com', 1, '2018-09-12 10:48:01'),
(6, 'Store  wish1', '125/25 church lane1,', 'Colombo1', '0755236901w', '0755236902w', '0755236903w', '0755236904w', 'wishwabuddhi@gmailcom', 1, '2019-10-11 05:58:35'),
(7, '11', '11', 'Colombo', '0755236901', '0755236902', '0755236903', '0755236904', 'wishwabuddhi@gmail.com', 0, '2019-10-11 06:00:41'),
(8, 'cxvxcvcxvcxvxc', 'xcvxcvxcvcxv', 'xcvxcvxcv', 'xcvxcvxcv', 'vxcvxcvxcvxcv', '', '', '', 0, '2019-10-14 11:57:44');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_subcategories`
--

CREATE TABLE `ezy_pos_subcategories` (
  `sub_cat_id` int(11) NOT NULL,
  `parent_cat_id` int(11) NOT NULL,
  `sub_cat_name` varchar(250) NOT NULL,
  `sub_cat_status` int(1) NOT NULL DEFAULT '1',
  `sub_cat_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_subcategories`
--

INSERT INTO `ezy_pos_subcategories` (`sub_cat_id`, `parent_cat_id`, `sub_cat_name`, `sub_cat_status`, `sub_cat_createdat`) VALUES
(25, 1, 'sub of sonny', 1, '2018-09-05 06:25:25'),
(26, 2, 'sub of sumsung', 1, '2018-09-05 06:25:36'),
(27, 3, 'Sub CAT 1', 1, '2019-10-04 05:13:10'),
(28, 4, 'Sub category wish1', 0, '2019-10-11 04:54:56'),
(29, 4, 'Sub category wish 2', 1, '2019-10-11 04:55:20'),
(30, 4, 'Sub category wish3', 0, '2019-10-11 05:09:10'),
(31, 5, 'Sub category2 wish1', 1, '2019-10-11 05:09:46'),
(32, 7, '', 1, '2019-10-14 10:12:48'),
(33, 7, 'Cccc', 1, '2019-10-14 10:13:15'),
(34, 8, 'Wwwww', 1, '2019-10-14 11:03:03'),
(35, 8, 'Qqqq', 0, '2019-10-14 11:03:10');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_suppliers`
--

CREATE TABLE `ezy_pos_suppliers` (
  `sup_id` int(11) NOT NULL,
  `sup_name` varchar(200) NOT NULL,
  `sup_address` varchar(250) NOT NULL,
  `sup_contact` int(20) NOT NULL,
  `sup_balance` double(50,2) NOT NULL,
  `sup_status` int(1) NOT NULL DEFAULT '1',
  `sup_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_suppliers`
--

INSERT INTO `ezy_pos_suppliers` (`sup_id`, `sup_name`, `sup_address`, `sup_contact`, `sup_balance`, `sup_status`, `sup_createdat`) VALUES
(4, 'Ezycodec', 'Colomboc', 21474, 0.00, 1, '2018-09-05 06:24:08'),
(5, 'EzySupplier', 'Colombo', 2147483647, 0.00, 1, '2018-09-05 06:24:39'),
(6, 'EzyNew', 'Colombo', 771234567, 500.00, 0, '2018-10-01 05:49:32'),
(7, 'ezyCashOnly', 'Colombo', 771234567, 0.00, 0, '2018-10-01 05:50:51'),
(8, 'ezyCashCredit', 'Colombo', 771234567, 500.00, 0, '2018-10-01 05:51:52'),
(9, 'ezyChqOnly', 'Colombo', 771234567, 500.00, 0, '2018-10-01 05:52:46'),
(10, 'cashChqCredit', 'Colombo', 2147483647, 0.00, 1, '2018-10-01 06:35:44'),
(11, 'EzyCashChq', 'Colombo', 771234567, 500.00, 0, '2018-10-01 06:39:17'),
(12, 'ezyChqCredit', 'Colombo', 2147483647, 0.00, 1, '2018-10-01 06:53:41'),
(13, 'EzyCashCredit', 'Colombo', 2147483647, 0.00, 1, '2018-10-01 06:53:57'),
(14, 'wish', '125/25 church lane,dehiwala', 768453733, 0.00, 1, '2019-10-03 04:46:59'),
(15, 'SUPPLIER wish', '125/25 church lane,', 768453735, 0.00, 0, '2019-10-11 05:14:47'),
(16, 'SUPPLIER wish1', '125/25 church lane1,', 1768453735, 0.00, 0, '2019-10-11 05:24:12'),
(17, 'SUPPLIER wish', '125/25 church lane,', 768453735, 0.00, 1, '2019-10-11 05:35:25'),
(18, 'www', 'www@gam.com', 123456789, 0.00, 0, '2019-10-11 05:47:29');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_sup_balnce`
--

CREATE TABLE `ezy_pos_sup_balnce` (
  `supbal_id` int(11) NOT NULL,
  `supbal_supid` int(11) NOT NULL,
  `supbal_amount` double(50,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_sup_cheque`
--

CREATE TABLE `ezy_pos_sup_cheque` (
  `sup_cheque_id` int(11) NOT NULL,
  `sup_cheque_grnid` int(11) NOT NULL,
  `sup_cheque_amount` double(50,2) NOT NULL,
  `sup_cheque_bank` varchar(250) NOT NULL,
  `sup_cheque_num` varchar(250) NOT NULL,
  `sup_cheque_our0_cuscheqtblid` tinyint(1) NOT NULL,
  `sup_cheque_date` date NOT NULL,
  `sup_cheque_givendate` date NOT NULL,
  `sup_cheque_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_sup_cheque`
--

INSERT INTO `ezy_pos_sup_cheque` (`sup_cheque_id`, `sup_cheque_grnid`, `sup_cheque_amount`, `sup_cheque_bank`, `sup_cheque_num`, `sup_cheque_our0_cuscheqtblid`, `sup_cheque_date`, `sup_cheque_givendate`, `sup_cheque_status`) VALUES
(1, 1, 20.00, '2', 'erfew', 0, '2018-10-02', '2018-10-02', 1),
(2, 1, 20.00, '2', 'erfew', 0, '2018-10-02', '2018-10-02', 1),
(3, 1, 12.00, '2', 'erfew', 0, '2018-10-02', '2018-10-02', 1),
(4, 1, 17.00, '2', 'erfew', 0, '2018-10-02', '2018-10-02', 1),
(5, 1, 1.00, '2', 'erfew', 0, '2018-10-02', '2018-10-02', 1),
(12, 2, 10.00, 'sampath', '124234', 0, '2018-10-31', '2018-10-03', 1),
(13, 2, 12.00, '2', 'erfew', 0, '2018-10-03', '2018-10-03', 1),
(14, 2, 10.00, 'sampath', '124234', 0, '2018-10-31', '2018-10-03', 1),
(15, 2, 20.00, '2', 'erfew', 0, '2018-10-03', '2018-10-03', 1),
(16, 2, 20.00, '1', 'erfew', 0, '2018-10-03', '2018-10-03', 1),
(17, 2, 20.00, '1', 'erfew', 0, '2018-10-03', '2018-10-03', 1),
(18, 2, 10.00, 'sampath', '124234', 0, '2018-10-31', '2018-10-03', 1),
(19, 2, 1.00, '1', 'erfew', 0, '2018-10-03', '2018-10-03', 1),
(20, 2, 730.00, 'sampath', '124234', 0, '2018-09-18', '2018-10-03', 1),
(21, 0, 17.00, '2', 'erfew', 0, '2018-10-04', '2018-10-04', 1),
(22, 0, 2.00, '2', 'erfew', 0, '2018-10-05', '2018-10-05', 1),
(23, 0, 21.00, '2', 'erfew', 0, '2018-10-05', '2018-10-05', 1),
(24, 0, 1.00, '2', 'erfew', 0, '2018-10-05', '2018-10-05', 1),
(25, 0, 1.00, '2', 'erfew', 0, '2018-10-05', '2018-10-05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_sup_chequelog`
--

CREATE TABLE `ezy_pos_sup_chequelog` (
  `supchqlog_id` int(11) NOT NULL,
  `supchqlog_chqid` int(11) NOT NULL,
  `supchqlog_grnid` int(11) NOT NULL,
  `supchqlog_amnt` double(50,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_sup_chequelog`
--

INSERT INTO `ezy_pos_sup_chequelog` (`supchqlog_id`, `supchqlog_chqid`, `supchqlog_grnid`, `supchqlog_amnt`) VALUES
(1, 21, 1, 17.00),
(2, 22, 1, 2.00),
(3, 23, 1, 21.00),
(4, 24, 1, 1.00),
(5, 25, 1, 1.00);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_sup_payment`
--

CREATE TABLE `ezy_pos_sup_payment` (
  `sup_pay_id` int(11) NOT NULL,
  `sup_pay_grnid` int(11) NOT NULL,
  `sup_pay_cash` double(50,2) NOT NULL,
  `sup_pay_credit` double(50,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_sup_payment`
--

INSERT INTO `ezy_pos_sup_payment` (`sup_pay_id`, `sup_pay_grnid`, `sup_pay_cash`, `sup_pay_credit`) VALUES
(1, 1, 1274.00, 198654.00),
(2, 2, 167.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_sup_paymentcashlog`
--

CREATE TABLE `ezy_pos_sup_paymentcashlog` (
  `paycashlog_id` int(11) NOT NULL,
  `paycashlog_grnid` int(11) NOT NULL,
  `paycashlog_amount` double(50,2) NOT NULL,
  `paycashlog_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_sup_paymentcashlog`
--

INSERT INTO `ezy_pos_sup_paymentcashlog` (`paycashlog_id`, `paycashlog_grnid`, `paycashlog_amount`, `paycashlog_date`) VALUES
(1, 1, 0.00, '2018-10-02'),
(2, 1, 1000.00, '2018-10-02'),
(3, 1, 100.00, '2018-10-02'),
(4, 1, 11.00, '2018-10-02'),
(5, 1, 11.00, '2018-10-02'),
(6, 1, 16.00, '2018-10-02'),
(7, 1, 2.00, '2018-10-02'),
(8, 2, 0.00, '2018-10-02'),
(11, 2, 10.00, '2018-10-03'),
(12, 2, 10.00, '2018-10-03'),
(13, 2, 10.00, '2018-10-03'),
(14, 2, 10.00, '2018-10-03'),
(15, 2, 121.00, '2018-10-03'),
(16, 2, 1.00, '2018-10-03'),
(17, 2, 5.00, '2018-10-03'),
(18, 1, 112.00, '2018-10-04'),
(19, 1, 1.00, '2018-10-04'),
(20, 1, 1.00, '2018-10-04'),
(21, 1, 17.00, '2018-10-04'),
(22, 1, 1.00, '2018-10-05'),
(23, 1, 2.00, '2018-10-05');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_sup_return`
--

CREATE TABLE `ezy_pos_sup_return` (
  `suprtrn_id` int(11) NOT NULL,
  `suprtrn_supID` int(11) NOT NULL,
  `suprtrn_totalRtrn` double(30,2) NOT NULL,
  `suprtrn_createdby` int(11) NOT NULL,
  `suprtrn_status` tinyint(1) NOT NULL DEFAULT '1',
  `suprtrn_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_sup_return_item`
--

CREATE TABLE `ezy_pos_sup_return_item` (
  `supRtrn_itm_id` int(11) NOT NULL,
  `supRtrn_itm_supRtrnID` int(11) NOT NULL,
  `supRtrn_itm_itmID` int(11) NOT NULL,
  `supRtrn_itm_rQty` double(250,2) NOT NULL,
  `supRtrn_itm_rAmount` double(30,2) NOT NULL,
  `supRtrn_itm_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_taxs`
--

CREATE TABLE `ezy_pos_taxs` (
  `tax_id` int(11) NOT NULL,
  `tax_name` varchar(200) NOT NULL,
  `tax_value` double(50,2) NOT NULL,
  `tax_status` int(1) NOT NULL DEFAULT '1',
  `tax_createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_taxs`
--

INSERT INTO `ezy_pos_taxs` (`tax_id`, `tax_name`, `tax_value`, `tax_status`, `tax_createdat`) VALUES
(6, 'Tax 1', 1000.00, 1, '2019-10-04 05:20:04'),
(7, 'Tax 1', 1500.50, 1, '2019-10-04 05:21:29'),
(8, 'Tax Wish 11', 15000.00, 1, '2019-10-11 05:55:21'),
(9, 'Tax 1', 1000.00, 0, '2019-10-11 05:55:56'),
(10, 'tax3', 767670.00, 1, '2019-10-14 11:13:24'),
(11, '22', 10.21, 0, '2019-10-14 11:13:46');

-- --------------------------------------------------------

--
-- Table structure for table `ezy_pos_users`
--

CREATE TABLE `ezy_pos_users` (
  `user_id` int(11) NOT NULL,
  `user_username` varchar(250) NOT NULL,
  `user_name` varchar(250) NOT NULL,
  `user_password` varchar(250) NOT NULL,
  `user_role` int(1) NOT NULL,
  `user_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezy_pos_users`
--

INSERT INTO `ezy_pos_users` (`user_id`, `user_username`, `user_name`, `user_password`, `user_role`, `user_status`) VALUES
(89, 'admin', 'Admin', '21232f297a57a5a743894a0e4a801fc3', 1, 1),
(99, 'admin5', 'Ezyadmin5', 'admin123', 1, 1),
(98, 'admin4', 'Ezyadmin4', 'admin123', 1, 1),
(97, 'admin3', 'Ezyadmin3', 'admin123', 1, 1),
(96, 'admin2', 'Ezyadmin2', 'admin123', 1, 1),
(95, 'admin1', 'Ezyadmin1', 'admin123', 1, 1),
(100, 'admin6', 'Ezyadmin6', 'admin', 1, 1),
(101, 'admin7', 'Ezyadmin7', '123', 1, 1),
(102, 'wish', 'Wishwa Buddhi', '202cb962ac59075b964b07152d234b70', 1, 0),
(103, 'User Wish', 'Don Buddhi', '202cb962ac59075b964b07152d234b70', 0, 0),
(104, 'user wishwa1', 'Don Buddhi', '202cb962ac59075b964b07152d234b70', 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ezy_pos_bank`
--
ALTER TABLE `ezy_pos_bank`
  ADD PRIMARY KEY (`bank_id`);

--
-- Indexes for table `ezy_pos_categories`
--
ALTER TABLE `ezy_pos_categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `ezy_pos_config2`
--
ALTER TABLE `ezy_pos_config2`
  ADD PRIMARY KEY (`config_id`);

--
-- Indexes for table `ezy_pos_currentqtywithgrn`
--
ALTER TABLE `ezy_pos_currentqtywithgrn`
  ADD PRIMARY KEY (`cur_id`);

--
-- Indexes for table `ezy_pos_cur_grn_vs_sale`
--
ALTER TABLE `ezy_pos_cur_grn_vs_sale`
  ADD PRIMARY KEY (`grnvssale_id`);

--
-- Indexes for table `ezy_pos_customers`
--
ALTER TABLE `ezy_pos_customers`
  ADD PRIMARY KEY (`cus_id`);

--
-- Indexes for table `ezy_pos_cus_balnce`
--
ALTER TABLE `ezy_pos_cus_balnce`
  ADD PRIMARY KEY (`bal_id`);

--
-- Indexes for table `ezy_pos_cus_cheque`
--
ALTER TABLE `ezy_pos_cus_cheque`
  ADD PRIMARY KEY (`cus_cheque_id`);

--
-- Indexes for table `ezy_pos_cus_chequelog`
--
ALTER TABLE `ezy_pos_cus_chequelog`
  ADD PRIMARY KEY (`cheqlog_id`);

--
-- Indexes for table `ezy_pos_cus_payment`
--
ALTER TABLE `ezy_pos_cus_payment`
  ADD PRIMARY KEY (`cus_pay_id`);

--
-- Indexes for table `ezy_pos_cus_paymnt_log`
--
ALTER TABLE `ezy_pos_cus_paymnt_log`
  ADD PRIMARY KEY (`pymntlog_id`);

--
-- Indexes for table `ezy_pos_cus_return`
--
ALTER TABLE `ezy_pos_cus_return`
  ADD PRIMARY KEY (`cusrtrn_id`);

--
-- Indexes for table `ezy_pos_cus_return_item`
--
ALTER TABLE `ezy_pos_cus_return_item`
  ADD PRIMARY KEY (`retrn_itm_id`);

--
-- Indexes for table `ezy_pos_employe_salary`
--
ALTER TABLE `ezy_pos_employe_salary`
  ADD PRIMARY KEY (`empsalary_id`);

--
-- Indexes for table `ezy_pos_employe_salary_log`
--
ALTER TABLE `ezy_pos_employe_salary_log`
  ADD PRIMARY KEY (`emp_slry_log_expense_id`);

--
-- Indexes for table `ezy_pos_expense`
--
ALTER TABLE `ezy_pos_expense`
  ADD PRIMARY KEY (`expen_id`);

--
-- Indexes for table `ezy_pos_expense_cat`
--
ALTER TABLE `ezy_pos_expense_cat`
  ADD PRIMARY KEY (`expencat_id`);

--
-- Indexes for table `ezy_pos_expen_cheque`
--
ALTER TABLE `ezy_pos_expen_cheque`
  ADD PRIMARY KEY (`expen_chq_id`);

--
-- Indexes for table `ezy_pos_grns`
--
ALTER TABLE `ezy_pos_grns`
  ADD PRIMARY KEY (`grn_id`);

--
-- Indexes for table `ezy_pos_grn_item`
--
ALTER TABLE `ezy_pos_grn_item`
  ADD PRIMARY KEY (`grnitm_id`);

--
-- Indexes for table `ezy_pos_insuffistocksale`
--
ALTER TABLE `ezy_pos_insuffistocksale`
  ADD PRIMARY KEY (`insuffi_id`);

--
-- Indexes for table `ezy_pos_items`
--
ALTER TABLE `ezy_pos_items`
  ADD PRIMARY KEY (`itm_id`);

--
-- Indexes for table `ezy_pos_privileges`
--
ALTER TABLE `ezy_pos_privileges`
  ADD PRIMARY KEY (`priv_id`);

--
-- Indexes for table `ezy_pos_sale`
--
ALTER TABLE `ezy_pos_sale`
  ADD PRIMARY KEY (`sale_id`);

--
-- Indexes for table `ezy_pos_sale_item`
--
ALTER TABLE `ezy_pos_sale_item`
  ADD PRIMARY KEY (`saleitem_id`);

--
-- Indexes for table `ezy_pos_staffs`
--
ALTER TABLE `ezy_pos_staffs`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `ezy_pos_stock`
--
ALTER TABLE `ezy_pos_stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `ezy_pos_stock_log`
--
ALTER TABLE `ezy_pos_stock_log`
  ADD PRIMARY KEY (`stocklog_id`);

--
-- Indexes for table `ezy_pos_stores`
--
ALTER TABLE `ezy_pos_stores`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `ezy_pos_subcategories`
--
ALTER TABLE `ezy_pos_subcategories`
  ADD PRIMARY KEY (`sub_cat_id`);

--
-- Indexes for table `ezy_pos_suppliers`
--
ALTER TABLE `ezy_pos_suppliers`
  ADD PRIMARY KEY (`sup_id`);

--
-- Indexes for table `ezy_pos_sup_balnce`
--
ALTER TABLE `ezy_pos_sup_balnce`
  ADD PRIMARY KEY (`supbal_id`);

--
-- Indexes for table `ezy_pos_sup_cheque`
--
ALTER TABLE `ezy_pos_sup_cheque`
  ADD PRIMARY KEY (`sup_cheque_id`);

--
-- Indexes for table `ezy_pos_sup_chequelog`
--
ALTER TABLE `ezy_pos_sup_chequelog`
  ADD PRIMARY KEY (`supchqlog_id`);

--
-- Indexes for table `ezy_pos_sup_payment`
--
ALTER TABLE `ezy_pos_sup_payment`
  ADD PRIMARY KEY (`sup_pay_id`);

--
-- Indexes for table `ezy_pos_sup_paymentcashlog`
--
ALTER TABLE `ezy_pos_sup_paymentcashlog`
  ADD PRIMARY KEY (`paycashlog_id`);

--
-- Indexes for table `ezy_pos_sup_return`
--
ALTER TABLE `ezy_pos_sup_return`
  ADD PRIMARY KEY (`suprtrn_id`);

--
-- Indexes for table `ezy_pos_sup_return_item`
--
ALTER TABLE `ezy_pos_sup_return_item`
  ADD PRIMARY KEY (`supRtrn_itm_id`);

--
-- Indexes for table `ezy_pos_taxs`
--
ALTER TABLE `ezy_pos_taxs`
  ADD PRIMARY KEY (`tax_id`);

--
-- Indexes for table `ezy_pos_users`
--
ALTER TABLE `ezy_pos_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ezy_pos_bank`
--
ALTER TABLE `ezy_pos_bank`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ezy_pos_categories`
--
ALTER TABLE `ezy_pos_categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ezy_pos_config2`
--
ALTER TABLE `ezy_pos_config2`
  MODIFY `config_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ezy_pos_currentqtywithgrn`
--
ALTER TABLE `ezy_pos_currentqtywithgrn`
  MODIFY `cur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `ezy_pos_cur_grn_vs_sale`
--
ALTER TABLE `ezy_pos_cur_grn_vs_sale`
  MODIFY `grnvssale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=305;

--
-- AUTO_INCREMENT for table `ezy_pos_customers`
--
ALTER TABLE `ezy_pos_customers`
  MODIFY `cus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ezy_pos_cus_balnce`
--
ALTER TABLE `ezy_pos_cus_balnce`
  MODIFY `bal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ezy_pos_cus_cheque`
--
ALTER TABLE `ezy_pos_cus_cheque`
  MODIFY `cus_cheque_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ezy_pos_cus_chequelog`
--
ALTER TABLE `ezy_pos_cus_chequelog`
  MODIFY `cheqlog_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ezy_pos_cus_payment`
--
ALTER TABLE `ezy_pos_cus_payment`
  MODIFY `cus_pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ezy_pos_cus_paymnt_log`
--
ALTER TABLE `ezy_pos_cus_paymnt_log`
  MODIFY `pymntlog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ezy_pos_cus_return`
--
ALTER TABLE `ezy_pos_cus_return`
  MODIFY `cusrtrn_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ezy_pos_cus_return_item`
--
ALTER TABLE `ezy_pos_cus_return_item`
  MODIFY `retrn_itm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ezy_pos_employe_salary`
--
ALTER TABLE `ezy_pos_employe_salary`
  MODIFY `empsalary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ezy_pos_employe_salary_log`
--
ALTER TABLE `ezy_pos_employe_salary_log`
  MODIFY `emp_slry_log_expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ezy_pos_expense`
--
ALTER TABLE `ezy_pos_expense`
  MODIFY `expen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ezy_pos_expense_cat`
--
ALTER TABLE `ezy_pos_expense_cat`
  MODIFY `expencat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ezy_pos_expen_cheque`
--
ALTER TABLE `ezy_pos_expen_cheque`
  MODIFY `expen_chq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ezy_pos_grns`
--
ALTER TABLE `ezy_pos_grns`
  MODIFY `grn_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ezy_pos_grn_item`
--
ALTER TABLE `ezy_pos_grn_item`
  MODIFY `grnitm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ezy_pos_insuffistocksale`
--
ALTER TABLE `ezy_pos_insuffistocksale`
  MODIFY `insuffi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ezy_pos_items`
--
ALTER TABLE `ezy_pos_items`
  MODIFY `itm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `ezy_pos_privileges`
--
ALTER TABLE `ezy_pos_privileges`
  MODIFY `priv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `ezy_pos_sale`
--
ALTER TABLE `ezy_pos_sale`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ezy_pos_sale_item`
--
ALTER TABLE `ezy_pos_sale_item`
  MODIFY `saleitem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ezy_pos_staffs`
--
ALTER TABLE `ezy_pos_staffs`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ezy_pos_stock`
--
ALTER TABLE `ezy_pos_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ezy_pos_stock_log`
--
ALTER TABLE `ezy_pos_stock_log`
  MODIFY `stocklog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=407;

--
-- AUTO_INCREMENT for table `ezy_pos_stores`
--
ALTER TABLE `ezy_pos_stores`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ezy_pos_subcategories`
--
ALTER TABLE `ezy_pos_subcategories`
  MODIFY `sub_cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `ezy_pos_suppliers`
--
ALTER TABLE `ezy_pos_suppliers`
  MODIFY `sup_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `ezy_pos_sup_balnce`
--
ALTER TABLE `ezy_pos_sup_balnce`
  MODIFY `supbal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ezy_pos_sup_cheque`
--
ALTER TABLE `ezy_pos_sup_cheque`
  MODIFY `sup_cheque_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `ezy_pos_sup_chequelog`
--
ALTER TABLE `ezy_pos_sup_chequelog`
  MODIFY `supchqlog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ezy_pos_sup_payment`
--
ALTER TABLE `ezy_pos_sup_payment`
  MODIFY `sup_pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ezy_pos_sup_paymentcashlog`
--
ALTER TABLE `ezy_pos_sup_paymentcashlog`
  MODIFY `paycashlog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `ezy_pos_sup_return`
--
ALTER TABLE `ezy_pos_sup_return`
  MODIFY `suprtrn_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ezy_pos_sup_return_item`
--
ALTER TABLE `ezy_pos_sup_return_item`
  MODIFY `supRtrn_itm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ezy_pos_taxs`
--
ALTER TABLE `ezy_pos_taxs`
  MODIFY `tax_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ezy_pos_users`
--
ALTER TABLE `ezy_pos_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
