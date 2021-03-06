-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 05, 2020 at 01:11 PM
-- Server version: 5.7.31-0ubuntu0.16.04.1
-- PHP Version: 7.3.19-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cmd_hlh`
--

-- --------------------------------------------------------

--
-- Table structure for table `care_tz_drugsandservices_description`
--

DROP TABLE IF EXISTS `care_tz_drugsandservices_description`;
CREATE TABLE `care_tz_drugsandservices_description` (
  `ID` bigint(20) NOT NULL,
  `last_change` bigint(20) NOT NULL DEFAULT '0',
  `UID` varchar(255) DEFAULT '',
  `Fieldname` varchar(255) DEFAULT '',
  `ShowDescription` varchar(255) DEFAULT '',
  `company_id` int(11) NOT NULL DEFAULT '0',
  `FullDescription` varchar(255) DEFAULT '',
  `is_insurance_price` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `care_tz_drugsandservices_description`
--

INSERT INTO `care_tz_drugsandservices_description` (`ID`, `last_change`, `UID`, `Fieldname`, `ShowDescription`, `company_id`, `FullDescription`, `is_insurance_price`) VALUES
(1, 0, '', 'unit_price', 'CASH PRICE                                        ', 0, 'TSH (e.g. 1200,00 or 1200) - Standard price for item ', 1),
(2, 0, '', 'unit_price_1', 'NHIF  PRICE                    ', 12, 'TSH (e.g. 1200,00 or 1200) - price for company /insured people', 0),
(3, 0, '', 'unit_price_2', 'TANESCO PRICE', 7, 'TSH (e.g. 1200,00 or 1200) - price for self paying people', 0),
(5, 0, '', 'unit_price_4', 'iCHF PRIMARY ', 91, 'TSH (e.g. 1200,00 or 1200) - Standard price for item ', 0),
(6, 0, 'Admin', 'unit_price_5', 'iCHF REFERRAL', 91, 'TSH (e.g. 1200,00 or 1200) - Standard price for item ', 0),
(8, 0, '', 'unit_price_2', 'AAR PRICE', 3, 'TSH (e.g. 1200,00 or 1200) - price for self paying people', 0),
(9, 0, '', 'unit_price_2', 'MHI PRICE', 16, 'TSH (e.g. 1200,00 or 1200) - price for self paying people', 0),
(10, 0, '', 'unit_price_2', 'NSSF PRICE', 31, 'TSH (e.g. 1200,00 or 1200) - price for self paying people', 0),
(11, 0, '', 'unit_price_2', 'NSSF REFFERALL PRICE', 51, 'TSH (e.g. 1200,00 or 1200) - price for self paying people', 0),
(12, 0, '', 'unit_price_2', 'AAR LTD', 53, 'TSH (e.g. 1200,00 or 1200) - price for self paying people', 0),
(13, 0, '', 'unit_price_2', 'STRATEGIES PRICE', 58, 'TSH (e.g. 1200,00 or 1200) - price for self paying people', 0),
(14, 0, '', 'unit_price', 'Haydom Global Health Research                                      ', 82, 'TSH (e.g. 1200,00 or 1200) - Standard price for item ', 1),
(15, 0, '', 'unit_price', 'Poor Patient Fund                                      ', 83, 'TSH (e.g. 1200,00 or 1200) - Standard price for item ', 1),
(16, 0, '', 'unit_price', 'Diabetic Association', 85, 'TSH (e.g. 1200,00 or 1200) - price for self paying people', 0),
(17, 0, '', 'unit_price', 'Ndorobo Hadzabe Fund                                      ', 86, 'TSH (e.g. 1200,00 or 1200) - Standard price for item ', 1),
(18, 0, '', 'unit_price', 'Angel Disabled Children                                       ', 87, 'TSH (e.g. 1200,00 or 1200) - Standard price for item ', 1),
(19, 0, '', 'unit_price', 'Abscondee Registration                                       ', 88, 'TSH (e.g. 1200,00 or 1200) - Standard price for item ', 1),
(20, 0, '', 'unit_price', 'Humanity Direct                                     ', 92, 'TSH (e.g. 1200,00 or 1200) - Standard price for item ', 1),
(21, 0, '', 'unit_price', 'Hydrocephalus Fund                                   ', 93, 'TSH (e.g. 1200,00 or 1200) - Standard price for item ', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `care_tz_drugsandservices_description`
--
ALTER TABLE `care_tz_drugsandservices_description`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `care_tz_drugsandservices_description`
--
ALTER TABLE `care_tz_drugsandservices_description`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
