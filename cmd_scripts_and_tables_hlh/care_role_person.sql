-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 06, 2020 at 04:19 PM
-- Server version: 5.7.31-0ubuntu0.16.04.1
-- PHP Version: 7.3.19-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `c2x_hlh`
--

-- --------------------------------------------------------

--
-- Table structure for table `care_role_person`
--

DROP TABLE IF EXISTS `care_role_person`;
CREATE TABLE `care_role_person` (
  `nr` smallint(5) UNSIGNED NOT NULL,
  `group_nr` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `role` varchar(255) DEFAULT '',
  `sname` varchar(255) DEFAULT '',
  `name` varchar(255) DEFAULT '',
  `LD_var` varchar(255) DEFAULT '',
  `status` varchar(255) DEFAULT '',
  `modify_id` varchar(255) DEFAULT '',
  `modify_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `create_id` varchar(255) DEFAULT '',
  `create_time` timestamp NULL DEFAULT NULL,
  `nhif_qualification_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `care_role_person`
--

INSERT INTO `care_role_person` (`nr`, `group_nr`, `role`, `sname`, `name`, `LD_var`, `status`, `modify_id`, `modify_time`, `create_id`, `create_time`, `nhif_qualification_id`) VALUES
(1, 1, 'Super Specialist', 'Super Specialist', 'Super Specialist', 'LDSuperSpecialist', 'active', '', NULL, '', NULL, 1),
(2, 2, 'Specialist', 'Specialist', 'Specialist', 'LDSpecialist', 'active', '', NULL, '', NULL, 2),
(3, 3, 'Medical Officer(MD)', 'Medical Officer(MD)', 'Medical Officer', 'LDMedicalOfficer', 'active', '', '2020-06-21 07:23:36', '', NULL, 3),
(4, 3, 'Dental Surgeon(DDS)', 'Dental Surgeon(DDS)', 'Dental Surgeon(DDS)', 'LDDentalSurgeon', 'active', '', NULL, '', NULL, 3),
(5, 4, 'Assistant Medical Officer(AMO)', 'Assistant Medical Officer(AMO)', 'Assistant Medical Officer(AMO)', 'LDAssistantMedicalOfficer', 'active', '', NULL, NULL, NULL, 4),
(6, 4, 'Assistant Dental\r\nOfficer(ADO)', 'Assistant Dental\r\nOfficer(ADO)', 'Assistant Dental\r\nOfficer(ADO)', 'LDAssistantDentalOfficer', 'active', '', NULL, '', NULL, 4),
(7, 5, 'Clinical Officer', 'Clinical Officer', 'Clinical Officer', 'LDClinicalOfficer', 'active', '', NULL, '', NULL, 5),
(8, 5, 'Dental\r\nAssistant', 'Dental\r\nAssistant', 'Dental\r\nAssistant', 'LDDentalAssistant', 'active', '', NULL, '', NULL, 5),
(9, 6, 'Assistant Clinical Officer', 'Assistant Clinical Officer', 'Assistant Clinical Officer', 'LDAssistantClinicalOfficer', 'Active', '', NULL, '', NULL, 6),
(10, 7, 'Others', 'Others', 'Others', 'LDOthers', 'Active', '', NULL, '', NULL, 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `care_role_person`
--
ALTER TABLE `care_role_person`
  ADD PRIMARY KEY (`nr`,`group_nr`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `care_role_person`
--
ALTER TABLE `care_role_person`
  MODIFY `nr` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
