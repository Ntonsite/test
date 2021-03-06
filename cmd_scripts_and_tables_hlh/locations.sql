-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 23, 2020 at 11:11 AM
-- Server version: 5.7.31-0ubuntu0.16.04.1
-- PHP Version: 7.3.19-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `caremd`
--

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `locationname` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `deladd1` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `deladd2` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `deladd3` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `deladd4` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `deladd5` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `deladd6` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `tel` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `fax` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `email` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `contact` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `taxprovinceid` tinyint(4) NOT NULL DEFAULT '0',
  `cashsalecustomer` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `cashsalebranch` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `managed` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`loccode`, `locationname`, `deladd1`, `deladd2`, `deladd3`, `deladd4`, `deladd5`, `deladd6`, `tel`, `fax`, `email`, `contact`, `taxprovinceid`, `cashsalecustomer`, `cashsalebranch`, `managed`) VALUES
('PH01', 'IPD Pharmacy', '', '', '', '', '', '', '', '', '', '', 0, '', '', 0),
('PH02', 'Out Patient Pharmacy', '', '', '', '', '', '', '', '', '', '', 0, '', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`loccode`),
  ADD KEY `taxprovinceid` (`taxprovinceid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
