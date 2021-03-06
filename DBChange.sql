-- 13/06-2020
CREATE TABLE `care_tz_drugsandservices_nhifschemes` (
  `id` int(11) NOT NULL,
  `ItemCode` varchar(255) DEFAULT NULL,
  `PriceCode` varchar(255) DEFAULT NULL,
  `LevelPriceCode` varchar(255) DEFAULT NULL,
  `OldItemCode` varchar(255) DEFAULT NULL,
  `ItemName` varchar(255) DEFAULT NULL,
  `Strength` varchar(255) DEFAULT NULL,
  `PackageID` varchar(255) DEFAULT NULL,
  `SchemeID` varchar(255) DEFAULT NULL,
  `FacilityLevelCode` varchar(255) DEFAULT NULL,
  `UnitPrice` decimal(15,2) DEFAULT NULL,
  `IsRestricted` int(11) DEFAULT NULL,
  `Dosage` varchar(255) DEFAULT NULL,
  `ItemTypeID` varchar(255) DEFAULT NULL,
  `MaximumQuantity` int(11) DEFAULT NULL,
  `AvailableInLevels` int(11) DEFAULT NULL,
  `PractitionerQualifications` varchar(255) DEFAULT NULL,
  `IsActive` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `care_tz_drugsandservices_nhifschemes`
--
ALTER TABLE `care_tz_drugsandservices_nhifschemes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `care_tz_drugsandservices_nhifschemes`
--
ALTER TABLE `care_tz_drugsandservices_nhifschemes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
