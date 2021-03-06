--
-- Schema Sync 0.9.4 Patch Script
-- Created: Fri, Sep 04, 2020
-- Server Version: 5.7.31-0ubuntu0.16.04.1
-- Apply To: localhost/caremd
--

USE `caremd`;
SET FOREIGN_KEY_CHECKS = 0;
ALTER DATABASE `caremd` CHARACTER SET=latin1 COLLATE=latin1_swedish_ci;
CREATE TABLE `care_mtuha_mapping` ( `ICD10CODE` varchar(255) DEFAULT '', `DIAGNOSIS` varchar(255) DEFAULT '', `OPDSerial` int(10) NOT NULL DEFAULT '0', `OPDName` varchar(255) DEFAULT '', `IPDSerial` int(10) NOT NULL DEFAULT '0', `IPDName` varchar(255) DEFAULT '') ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `care_nhif_claims` ( `id` int(11) NOT NULL AUTO_INCREMENT, `FolioID` varchar(255) DEFAULT '', `ClaimYear` year(4) DEFAULT NULL, `ClaimMonth` int(2) DEFAULT '0', `FolioNo` int(50) DEFAULT '0', `SerialNo` varchar(255) DEFAULT '', `CardNo` varchar(255) DEFAULT '', `Age` int(3) DEFAULT '0', `TelephoneNo` varchar(255) DEFAULT '', `encounter_nr` bigint(11) DEFAULT '0', `claim_status` varchar(255) DEFAULT '', `CreatedBy` varchar(255) DEFAULT '', `DateCreated` timestamp NULL DEFAULT NULL, `LastModifiedBy` varchar(255) DEFAULT '', `LastModified` varchar(255) DEFAULT '', PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `care_tz_drugsandservices_nhifschemes` ( `id` int(11) NOT NULL AUTO_INCREMENT, `ItemCode` varchar(255) DEFAULT NULL, `PriceCode` varchar(255) DEFAULT NULL, `LevelPriceCode` varchar(255) DEFAULT NULL, `OldItemCode` varchar(255) DEFAULT NULL, `ItemName` varchar(255) DEFAULT NULL, `Strength` varchar(255) DEFAULT NULL, `PackageID` varchar(255) DEFAULT NULL, `SchemeID` varchar(255) DEFAULT NULL, `FacilityLevelCode` varchar(255) DEFAULT NULL, `UnitPrice` decimal(15,2) DEFAULT NULL, `IsRestricted` int(11) DEFAULT NULL, `Dosage` varchar(255) DEFAULT NULL, `ItemTypeID` varchar(255) DEFAULT NULL, `MaximumQuantity` int(11) DEFAULT NULL, `AvailableInLevels` int(11) DEFAULT NULL, `PractitionerQualifications` varchar(255) DEFAULT NULL, `IsActive` int(11) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `care_tz_drugsandservices_nhifschemes_temp` ( `id` int(11) NOT NULL AUTO_INCREMENT, `ItemCode` varchar(255) DEFAULT NULL, `PriceCode` varchar(255) DEFAULT NULL, `LevelPriceCode` varchar(255) DEFAULT NULL, `OldItemCode` varchar(255) DEFAULT NULL, `ItemName` varchar(255) DEFAULT NULL, `Strength` varchar(255) DEFAULT NULL, `PackageID` varchar(255) DEFAULT NULL, `SchemeID` varchar(255) DEFAULT NULL, `FacilityLevelCode` varchar(255) DEFAULT NULL, `UnitPrice` decimal(15,2) DEFAULT NULL, `IsRestricted` int(11) DEFAULT NULL, `Dosage` varchar(255) DEFAULT NULL, `ItemTypeID` varchar(255) DEFAULT NULL, `MaximumQuantity` int(11) DEFAULT NULL, `AvailableInLevels` int(11) DEFAULT NULL, `PractitionerQualifications` varchar(255) DEFAULT NULL, `IsActive` int(11) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `care_tz_nursing_chart` ( `id` int(11) NOT NULL AUTO_INCREMENT, `nr` int(11) NOT NULL DEFAULT '0', `userdate` timestamp NULL DEFAULT NULL, `usertime` time DEFAULT NULL, `systemdate` timestamp NULL DEFAULT NULL, `qty` int(11) NOT NULL DEFAULT '0', `comment` longtext, `is_stopped` tinyint(4) NOT NULL DEFAULT '0', `sub_class` varchar(255) DEFAULT '', `user` varchar(255) DEFAULT '', `stoppedBy` varchar(255) DEFAULT '', `stopReason` longtext NOT NULL, `stopDate` date DEFAULT '1980-01-01', `dose` varchar(255) DEFAULT '', PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `care_tz_ward_dispensed` ( `id` int(11) NOT NULL AUTO_INCREMENT, `wardNr` int(11) NOT NULL DEFAULT '0', `wardName` varchar(255) DEFAULT '', `prescriptionNr` int(11) DEFAULT '0', `qtyIssued` int(11) DEFAULT '0', `dateIssued` date DEFAULT '1980-01-01', `is_issued` int(11) NOT NULL DEFAULT '0', `issuer` varchar(255) DEFAULT '', PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;
DROP TABLE `care_billing_archive`;
DROP TABLE `care_billing_bill`;
DROP TABLE `care_billing_bill_item`;
DROP TABLE `care_billing_final`;
DROP TABLE `care_billing_item`;
DROP TABLE `care_billing_payment`;
DROP TABLE `care_tz_billing_archieve_special`;
DROP TABLE `care_tz_billing_special`;
DROP TABLE `care_tz_dhis_element`;
DROP TABLE `care_tz_dhis_period`;
DROP TABLE `care_tz_drugsandservices_backup`;
DROP TABLE `care_tz_grn_detail`;
DROP TABLE `care_tz_grn_master`;
DROP TABLE `care_tz_item_transfer`;
DROP TABLE `care_tz_item_transfer_detail`;
DROP TABLE `care_tz_location`;
DROP TABLE `care_tz_purchase_order`;
DROP TABLE `care_tz_purchase_order_detail`;
DROP TABLE `care_tz_regions`;
DROP TABLE `care_tz_stock_in_hand`;
DROP TABLE `care_tz_stock_item_amount`;
DROP TABLE `care_tz_stock_item_properties`;
DROP TABLE `care_tz_stock_place`;
DROP TABLE `care_tz_store_info`;
DROP TABLE `care_users_old`;
DROP TABLE `mems_drug_list`;
DROP TABLE `mems_special_other`;
DROP TABLE `mems_supplies`;
DROP TABLE `mems_supplies_labor`;
DROP TABLE `temp_list`;
DROP TABLE `userrole_permission`;
DROP TABLE `care_tz_billing_elem_advance`;
ALTER TABLE `care_department` ADD COLUMN `max_appointments` int(10) NOT NULL DEFAULT 0 AFTER `consult_hours`;
ALTER TABLE `care_encounter` ADD COLUMN `referral_no` varchar(255) NULL AFTER `referrer_institution`, ADD COLUMN `pharmacy` varchar(255) NULL AFTER `current_dept_nr`, ADD COLUMN `referrer_number` varchar(255) NULL AFTER `medical_service`, ADD COLUMN `nhif_card_status` varchar(255) NULL AFTER `referrer_number`, ADD COLUMN `nhif_authorization_status` varchar(255) NULL AFTER `nhif_card_status`, ADD COLUMN `nhif_authorization_number` varchar(255) NULL AFTER `nhif_authorization_status`, ADD COLUMN `nhif_latest_authorization` varchar(255) NULL AFTER `nhif_authorization_number`, ADD COLUMN `nhif_visit_type` varchar(255) NULL AFTER `nhif_latest_authorization`, ADD COLUMN `nhif_full_name` varchar(255) NULL AFTER `nhif_visit_type`, ADD COLUMN `nhif_remarks` text NULL AFTER `nhif_full_name`, ADD COLUMN `nhif_transfer_details` longtext NULL AFTER `nhif_remarks`, ADD COLUMN `nhif_serial_number` int(11) NOT NULL DEFAULT 0 AFTER `nhif_transfer_details`, ADD COLUMN `nhif_serial_date` date NULL DEFAULT '1980-01-01' AFTER `nhif_serial_number`, ADD COLUMN `nhif_dob` date NULL DEFAULT '1980-01-01' AFTER `nhif_serial_date`, ADD COLUMN `nhif_scheme_id` varchar(255) NULL AFTER `nhif_dob`, MODIFY COLUMN `pid` int(11) unsigned NOT NULL DEFAULT 0 AFTER `encounter_nr_prev`, ADD INDEX `encounter` (`is_discharged`, `in_dept`, `status`) USING BTREE, ADD INDEX `current_ward_nr` (`current_ward_nr`) USING BTREE;
ALTER TABLE `care_encounter_diagnostics_report` ADD INDEX `encounter_nr` (`encounter_nr`) USING BTREE;
ALTER TABLE `care_encounter_event_signaller` row_format=DYNAMIC;
ALTER TABLE `care_encounter_notes` ADD INDEX `date` (`date`) USING BTREE, ADD INDEX `personell_name` (`personell_name`) USING BTREE;
ALTER TABLE `care_encounter_prescription` ADD COLUMN `partcode` varchar(255) NULL AFTER `article_item_number`, ADD COLUMN `mark_os` smallint(2) NOT NULL DEFAULT 0 AFTER `partcode`, ADD COLUMN `materialcost` varchar(255) NULL AFTER `mark_os`, ADD COLUMN `issue_date` timestamp NULL AFTER `issuer`, ADD COLUMN `is_printed` int(11) NOT NULL DEFAULT 0 AFTER `bill_status`, ADD COLUMN `in_weberp` int(3) NOT NULL DEFAULT 0 AFTER `sub_store`, ADD COLUMN `practitioner_nr` varchar(255) NULL AFTER `in_weberp`, ADD COLUMN `comment` text NULL AFTER `practitioner_nr`, ADD COLUMN `nhif_item_code` int(11) NOT NULL DEFAULT 0 AFTER `comment`, ADD COLUMN `nhif_approval_no` varchar(255) NULL AFTER `nhif_item_code`, ADD COLUMN `meal_type` varchar(255) NULL AFTER `nhif_approval_no`, MODIFY COLUMN `total_dosage` double NOT NULL AFTER `days`, ADD INDEX `article` (`article`) USING BTREE, ADD INDEX `status` (`status`) USING BTREE, ADD INDEX `bill_number` (`bill_number`) USING BTREE, ADD INDEX `mark_os` (`mark_os`) USING BTREE;
ALTER TABLE `care_icd10_en` ADD COLUMN `series` int(5) NOT NULL DEFAULT 0 AFTER `extra_subclass`, ADD COLUMN `opd_series` int(11) NOT NULL DEFAULT 0 AFTER `series`, ADD COLUMN `opd_name` text NULL AFTER `opd_series`, ADD COLUMN `ipd_series` int(11) NOT NULL DEFAULT 0 AFTER `opd_name`, ADD COLUMN `ipd_name` text NULL AFTER `ipd_series`, MODIFY COLUMN `diagnosis_code` varchar(255) NOT NULL FIRST;
ALTER TABLE `care_op_med_doc` ADD COLUMN `anasthetist` varchar(255) NULL AFTER `op_end`, ADD COLUMN `anaesthesia_type` varchar(255) NULL AFTER `assistant`, ADD COLUMN `postorder` text NULL AFTER `anaesthesia_type`, ADD COLUMN `indication` text NULL AFTER `postorder`, ADD COLUMN `procedure_or` text NULL AFTER `indication`, DROP COLUMN `class_m`;
ALTER TABLE `care_person` ADD COLUMN `allergic` tinyint(4) NOT NULL DEFAULT 0 AFTER `history`, ADD COLUMN `sub_insurance_id` int(11) NOT NULL DEFAULT 0 AFTER `insurance_ID`, ADD COLUMN `employee_id` varchar(255) NULL AFTER `insurance_ceiling_for_families`, ADD COLUMN `national_id` varchar(255) NULL AFTER `employee_id`, ADD COLUMN `prescribe_without_diagnosis` int(11) NOT NULL DEFAULT 0 AFTER `national_id`, ADD COLUMN `nhif_authorization_details` longtext NULL AFTER `prescribe_without_diagnosis`, MODIFY COLUMN `selian_pid` bigint(20) NOT NULL DEFAULT 0 AFTER `pid`, MODIFY COLUMN `allergy` text NULL AFTER `allergic`, ADD INDEX `date_reg` (`date_reg`) USING BTREE, ADD INDEX `name_2` (`name_2`) USING BTREE, ADD INDEX `name_first_2` (`name_first`, `name_last`) USING BTREE, ADD INDEX `insurance_ID` (`insurance_ID`) USING BTREE, ADD INDEX `sex` (`sex`) USING BTREE, ADD INDEX `create_time` (`create_time`) USING BTREE;
ALTER TABLE `care_role_person` ADD COLUMN `sname` varchar(255) NULL AFTER `role`, ADD COLUMN `nhif_qualification_id` int(11) NOT NULL DEFAULT 0 AFTER `create_time`;
ALTER TABLE `care_test_findings_chemlab` ADD COLUMN `file_path` varchar(255) NULL AFTER `create_time`, ADD COLUMN `lab_comment` longtext NOT NULL AFTER `file_path`, MODIFY COLUMN `job_id` varchar(25) NOT NULL AFTER `encounter_nr`;
ALTER TABLE `care_test_findings_chemlabor_sub` ADD COLUMN `sort_order` int(11) NOT NULL DEFAULT 0 AFTER `create_time`, MODIFY COLUMN `parameter_value` varchar(510) NULL AFTER `paramater_name`, ADD INDEX `batch_nr` (`batch_nr`, `job_id`, `encounter_nr`) USING BTREE, ADD INDEX `create_time` (`create_time`) USING BTREE;
ALTER TABLE `care_test_findings_radio` ADD INDEX `batch_nr` (`batch_nr`) USING BTREE, ADD INDEX `encounter_nr` (`encounter_nr`) USING BTREE, ADD INDEX `status` (`status`) USING BTREE;
ALTER TABLE `care_test_param` ROW_FORMAT=Compact row_format=COMPACT;
ALTER TABLE `care_test_request_chemlabor` ADD COLUMN `is_printed` int(11) NOT NULL DEFAULT 0 AFTER `bill_number`, ADD INDEX `status` (`status`) USING BTREE, ADD INDEX `item_id` (`item_id`) USING BTREE, ADD INDEX `specimen_collected` (`specimen_collected`) USING BTREE;
ALTER TABLE `care_test_request_chemlabor_sub` ADD COLUMN `is_printed` int(11) NOT NULL DEFAULT 0 AFTER `bill_status`, ADD COLUMN `sort_order` int(11) NOT NULL DEFAULT 0 AFTER `history`, ADD COLUMN `deleted` tinyint(4) NOT NULL DEFAULT 0 AFTER `sort_order`, ADD COLUMN `nhif_item_code` int(11) NOT NULL DEFAULT 0 AFTER `deleted`, ADD COLUMN `nhif_approval_number` varchar(255) NOT NULL AFTER `nhif_item_code`, ADD INDEX `bill_number` (`bill_number`) USING BTREE, ADD INDEX `item_id` (`item_id`) USING BTREE, ADD INDEX `batch_nr` (`batch_nr`) USING BTREE, ADD INDEX `status` (`status`) USING BTREE;
ALTER TABLE `care_test_request_radio` ADD COLUMN `hint` text NULL AFTER `process_time`, ADD COLUMN `nhif_item_code` int(11) NOT NULL DEFAULT 0 AFTER `hint`, ADD COLUMN `nhif_approval_no` varchar(255) NULL AFTER `nhif_item_code`, MODIFY COLUMN `send_date` date NULL AFTER `number_of_tests`, MODIFY COLUMN `xray_date` date NULL AFTER `mtr`, ADD INDEX `encounter_nr` (`encounter_nr`) USING BTREE, ADD INDEX `status` (`status`) USING BTREE;
ALTER TABLE `care_tz_arv_co_medi` row_format=DYNAMIC;
ALTER TABLE `care_tz_arv_eligible_reason` row_format=DYNAMIC;
ALTER TABLE `care_tz_arv_events` row_format=DYNAMIC;

CREATE TEMPORARY TABLE TMPT(SELECT id, COUNT(`nr`),`nr` FROM `care_tz_billing_archive` GROUP BY `nr` HAVING COUNT(`nr`)>1);
DELETE FROM care_tz_billing_archive WHERE id IN( SELECT id FROM TMPT );

ALTER TABLE `care_tz_billing_archive` ADD COLUMN `is_printed` int(11) NOT NULL DEFAULT 0 AFTER `create_id`, DROP COLUMN `id`, MODIFY COLUMN `nr` bigint(20) NOT NULL auto_increment FIRST, ADD INDEX `nr` (`nr`) USING BTREE, ADD INDEX `nr_2` (`nr`, `encounter_nr`) USING BTREE, DROP PRIMARY KEY, ADD PRIMARY KEY (`nr`) USING BTREE;
UPDATE `care_tz_billing_archive` SET is_printed=1;
ALTER TABLE `care_tz_billing_archive_elem` ADD COLUMN `amount_doc` bigint(20) NOT NULL DEFAULT 0 AFTER `amount`, ADD COLUMN `notes` varchar(255) NULL AFTER `description`, ADD COLUMN `close_deposit` tinyint(2) NOT NULL DEFAULT 0 AFTER `current_ward_nr`, ADD COLUMN `nhif_item_code` int(11) NOT NULL DEFAULT 0 AFTER `encounter_class_nr`, ADD COLUMN `nhif_approval_no` varchar(255) NULL AFTER `nhif_item_code`, ADD COLUMN `meal_type` varchar(255) NULL AFTER `nhif_approval_no`, MODIFY COLUMN `bank_ref` varchar(255) NULL AFTER `sub_store`, MODIFY COLUMN `is_deposit_item` tinyint(1) NOT NULL DEFAULT 0 AFTER `is_transmit2ERP`, MODIFY COLUMN `current_dept_nr` smallint(3) NOT NULL AFTER `bank_ref`, MODIFY COLUMN `current_ward_nr` smallint(3) NOT NULL AFTER `current_dept_nr`, ADD INDEX `insurance_id` (`insurance_id`) USING BTREE, ADD INDEX `item_number` (`item_number`) USING BTREE, ADD INDEX `current_dept_nr` (`current_dept_nr`) USING BTREE, ADD INDEX `current_ward_nr` (`current_ward_nr`) USING BTREE, ADD INDEX `encounter_class_nr` (`encounter_class_nr`) USING BTREE, ADD INDEX `amount` (`amount`) USING BTREE, ADD INDEX `price` (`price`) USING BTREE;
ALTER TABLE `care_tz_billing_elem` MODIFY COLUMN `materialcost` double(10,2) NULL AFTER `price`, MODIFY COLUMN `bank_ref` bigint(20) NULL DEFAULT 0 AFTER `materialcost`, MODIFY COLUMN `is_deposit_item` smallint(5) NOT NULL AFTER `sub_store`, ADD INDEX `prescriptions_nr` (`prescriptions_nr`) USING BTREE;
ALTER TABLE `care_tz_company` ADD COLUMN `company_code` varchar(255) NULL AFTER `name`, ADD COLUMN `enable_member_expiry` tinyint(2) NOT NULL DEFAULT 0 AFTER `modify_id`;
ALTER TABLE `care_tz_diagnosis` ADD COLUMN `diagnosis_type` enum('final','preliminary') NOT NULL DEFAULT 'final' AFTER `doctor_name`, ADD COLUMN `series` int(20) NOT NULL DEFAULT 0 AFTER `diagnosis_type`, ADD COLUMN `opd_series` int(11) NOT NULL DEFAULT 0 AFTER `series`, ADD COLUMN `opd_name` text NULL AFTER `opd_series`, ADD COLUMN `ipd_series` int(11) NOT NULL DEFAULT 0 AFTER `opd_name`, ADD COLUMN `ipd_name` text NULL AFTER `ipd_series`, ADD INDEX `parent_case_nr` (`parent_case_nr`, `PID`, `encounter_nr`, `timestamp`) USING BTREE, ADD INDEX `ICD_10_code` (`ICD_10_code`) USING BTREE, ADD INDEX `type` (`type`) USING BTREE, ADD INDEX `encounter_nr_3` (`encounter_nr`) USING BTREE, ADD INDEX `type_2` (`type`) USING BTREE, ADD INDEX `encounter_nr_4` (`encounter_nr`) USING BTREE, ADD INDEX `type_3` (`type`) USING BTREE, ADD INDEX `timestamp` (`timestamp`) USING BTREE, ADD INDEX `timestamp_2` (`timestamp`) USING BTREE, ADD INDEX `ICD_10_description` (`ICD_10_description`) USING BTREE;
ALTER TABLE `care_tz_district` MODIFY COLUMN `is_additional` int(11) NOT NULL DEFAULT 0 AFTER `district_name`;
ALTER TABLE `care_tz_drugs_reordering_level` row_format=DYNAMIC;
ALTER TABLE `care_tz_drugsandservices` ADD COLUMN `min_level` int(20) NOT NULL DEFAULT 0 AFTER `not_in_use`, ADD COLUMN `unit_price_7` double(10,2) NOT NULL DEFAULT 0.00 AFTER `unit_price_6`, ADD COLUMN `unit_price_8` double(10,2) NOT NULL DEFAULT 0.00 AFTER `unit_price_7`, ADD COLUMN `unit_price_9` double(10,2) NOT NULL DEFAULT 0.00 AFTER `unit_price_8`, ADD COLUMN `unit_price_10` double(10,2) NOT NULL DEFAULT 0.00 AFTER `unit_price_9`, ADD COLUMN `unit_price_11` double(10,2) NOT NULL DEFAULT 0.00 AFTER `unit_price_10`, ADD COLUMN `unit_price_12` double(10,2) NOT NULL DEFAULT 0.00 AFTER `unit_price_11`, ADD COLUMN `unit_price_13` double(10,2) NOT NULL DEFAULT 0.00 AFTER `unit_price_12`, ADD COLUMN `unit_price_14` double(10,2) NOT NULL DEFAULT 0.00 AFTER `unit_price_13`, ADD COLUMN `unit_price_15` double(10,2) NOT NULL DEFAULT 0.00 AFTER `unit_price_14`, ADD COLUMN `unit_price_16` double(10,2) NOT NULL DEFAULT 0.00 AFTER `unit_price_15`, ADD COLUMN `unit_price_17` double(10,2) NOT NULL DEFAULT 0.00 AFTER `unit_price_16`, ADD COLUMN `unit_price_18` double(10,2) NOT NULL DEFAULT 0.00 AFTER `unit_price_17`, ADD COLUMN `unit_price_19` double(10,2) NOT NULL DEFAULT 0.00 AFTER `unit_price_18`, ADD COLUMN `unit_price_20` double(10,2) NOT NULL DEFAULT 0.00 AFTER `unit_price_19`, ADD COLUMN `nhif_item_code` int(20) NULL DEFAULT 0 AFTER `unit_cost`, ADD COLUMN `is_restricted` tinyint(1) NULL DEFAULT 0 AFTER `nhif_item_code`, ADD COLUMN `maximum_quantity` int(11) NULL DEFAULT 0 AFTER `is_restricted`, ADD COLUMN `nhif_item_type_id` int(11) NOT NULL DEFAULT 0 AFTER `maximum_quantity`, ADD COLUMN `nhif_is_active` int(11) NOT NULL DEFAULT 0 AFTER `nhif_item_type_id`, ADD COLUMN `nhif_is_restricted` int(11) NOT NULL DEFAULT 0 AFTER `nhif_is_active`, ADD COLUMN `nhif_package_id` int(11) NOT NULL DEFAULT 0 AFTER `nhif_is_restricted`, ADD COLUMN `nhif_price_code` varchar(255) NULL AFTER `nhif_package_id`, ADD COLUMN `nhif_scheme_id` int(11) NOT NULL DEFAULT 0 AFTER `nhif_price_code`, MODIFY COLUMN `unit_price` double(10,2) NULL DEFAULT 0.00 AFTER `item_full_description`, MODIFY COLUMN `unit_price_1` double(10,2) NULL DEFAULT 0.00 AFTER `unit_price`, MODIFY COLUMN `unit_price_2` double(10,2) NULL DEFAULT 0.00 AFTER `unit_price_1`, MODIFY COLUMN `unit_price_3` double(10,2) NULL DEFAULT 0.00 AFTER `unit_price_2`, MODIFY COLUMN `unit_price_4` double(10,2) NULL DEFAULT 0.00 AFTER `min_level`, MODIFY COLUMN `unit_price_5` double(10,2) NULL DEFAULT 0.00 AFTER `unit_price_4`, MODIFY COLUMN `unit_price_6` double(10,2) NULL DEFAULT 0.00 AFTER `unit_price_5`, ADD INDEX `purchasing_class` (`purchasing_class`) USING BTREE, ADD INDEX `unit_price_5` (`unit_price_5`) USING BTREE;
ALTER TABLE `care_tz_drugsandservices_description` ADD COLUMN `company_id` int(11) NOT NULL DEFAULT 0 AFTER `ShowDescription`;
ALTER TABLE `care_tz_hospital_doctor_history` MODIFY COLUMN `patients` longtext NULL AFTER `attend`;
ALTER TABLE `care_tz_insurance` ADD INDEX `company_id` (`company_id`) USING BTREE;
ALTER TABLE `care_tz_laboratory` row_format=DYNAMIC;
ALTER TABLE `care_tz_laboratory_param` ADD COLUMN `block_selection` enum('yes','no') NOT NULL DEFAULT 'no' AFTER `price_1`, ADD COLUMN `enable_upload` enum('yes','no') NOT NULL DEFAULT 'yes' AFTER `block_selection`, ADD COLUMN `sort_order` int(11) NOT NULL DEFAULT 0 AFTER `enable_upload`, ADD COLUMN `price_4` double(10,2) NOT NULL DEFAULT 0.00 AFTER `sort_order`, ADD INDEX `nr` (`nr`) USING BTREE, ADD INDEX `id` (`id`) USING BTREE;
ALTER TABLE `care_tz_laboratory_tests` ADD INDEX `parent` (`parent`) USING BTREE;
ALTER TABLE `care_tz_person_insurance` row_format=DYNAMIC;
ALTER TABLE `care_tz_region` MODIFY COLUMN `is_additional` int(11) NOT NULL DEFAULT 0 AFTER `region_name`;
ALTER TABLE `care_tz_stock_transfer` row_format=DYNAMIC;
ALTER TABLE `care_tz_ward` MODIFY COLUMN `is_additional` int(11) NOT NULL DEFAULT 0 AFTER `ward_name`;
ALTER TABLE `care_encounter`  ADD `qualification` INT(11) NULL DEFAULT NULL  AFTER `nhif_scheme_id`;
ALTER TABLE `care_encounter` CHANGE `qualification` `qualification` INT(11) NULL DEFAULT '0';
UPDATE `care_person` SET insurance_ID='0' WHERE insurance_ID='';
UPDATE `care_tz_laboratory_param` SET `field_type`='';
DELETE FROM `care_encounter` WHERE care_encounter.pid NOT IN(SELECT care_person.pid FROM care_person);
DELETE FROM `care_encounter_prescription` WHERE encounter_nr NOT IN(SELECT encounter_nr FROM care_encounter); 
DELETE FROM `care_tz_insurance` WHERE `care_tz_insurance`.`id` = 183;
DELETE FROM `care_tz_company` WHERE id=84;
UPDATE `care_person` SET insurance_ID='0' WHERE insurance_ID='84';
UPDATE `care_test_request_chemlabor` SET status='done', specimen_collected='1' WHERE send_date < '2020-09-01';
UPDATE `care_config_global` SET value=1 WHERE type='restrict_unbilled_items';
UPDATE `care_test_request_radio` SET status='done' WHERE send_date < '2020-09-01';
ALTER TABLE `care_encounter`  ADD `nhif_scheme_name` VARCHAR(255) NOT NULL  AFTER `nhif_scheme_id`;
ALTER TABLE `care_encounter`  ADD `nhif_product_code` VARCHAR(255) NOT NULL  AFTER `nhif_scheme_name`;
ALTER TABLE `care_encounter`  ADD `nhif_product_name` VARCHAR(255) NOT NULL  AFTER `nhif_product_code`;
DROP VIEW `care_dhis_view`, `care_dhis_view_above5`, `care_dhis_view_under5`;


--care_user_roles has error please check
ALTER TABLE `care_user_roles` MODIFY COLUMN `permission` text NULL AFTER `description`, ADD INDEX `login_id` (`role_id`) USING BTREE, ROW_FORMAT=Compact row_format=COMPACT;
ALTER TABLE `care_users` ADD COLUMN `theme_name` varchar(255) NULL AFTER `create_time`, ADD COLUMN `occupation` varchar(255) NULL AFTER `theme_name`, ADD COLUMN `tel_no` varchar(255) NULL AFTER `occupation`, ADD COLUMN `nhif_qualification_id` int(11) NOT NULL DEFAULT 0 AFTER `tel_no`, ADD COLUMN `practitioner_nr` varchar(255) NULL AFTER `nhif_qualification_id`, ADD INDEX `password` (`password`) USING BTREE, ADD INDEX `name` (`name`) USING BTREE;
SET FOREIGN_KEY_CHECKS = 1;

--Make sure consultation according to doctor qualification are loaded
--Make update price list from original care2x, some items shows 99999999
--pharmacy is slow, we need to check
--enable copy patient notes to radiology 
--put logo for haydom
--patient transfer should not show qualification
