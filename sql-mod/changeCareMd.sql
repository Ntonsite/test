ALTER TABLE `caredb_aicc`.`care_encounter` 

  ADD COLUMN referral_no varchar(15) NULL AFTER referrer_institution, 

ALTER TABLE `caredb_aicc`.`care_encounter_prescription`  
  ADD COLUMN practitioner_nr varchar(20), 

ALTER TABLE `caredb_aicc`.`care_role_person` 
  ADD COLUMN sname varchar(50) NOT NULL AFTER role, 

ALTER TABLE `caredb_aicc`.`care_tz_billing_elem_advance` 
  ADD COLUMN signed_by_follower tinyint(1) NOT NULL DEFAULT '0' AFTER User_Id, 
  ADD COLUMN is_transmit2ERP tinyint(4) NOT NULL DEFAULT '1', 

ALTER TABLE `caredb_aicc`.`care_tz_company` 
  ADD COLUMN company_code varchar(50) NULL AFTER name, 
 
ALTER TABLE `caredb_aicc`.`care_tz_diagnosis` 
  ADD COLUMN diagnosis_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER type, 
  ADD COLUMN practitioner_nr varchar(20) NULL AFTER doctor_name, 

ALTER TABLE `caredb_aicc`.`care_tz_drugsandservices` 
  ADD COLUMN nhif_item_code varchar(20) NULL AFTER partcode, 


ALTER TABLE `caredb_aicc`.`care_tz_stock_item_properties` 
  ADD COLUMN Stock_place_id bigint(20) NOT NULL DEFAULT '0' AFTER Drugsandservices_id;

ALTER TABLE `care_encounter` ADD `referrer_number` VARCHAR(255) NOT NULL AFTER `medical_service`;

ALTER TABLE `care_tz_company` ADD `company_code` VARCHAR(255) NOT NULL AFTER `enable_member_expiry`;
UPDATE `care_tz_company` SET `company_code` = 'NHIF' WHERE `care_tz_company`.`name` = "NHIF";
TRUNCATE care_nhif_claims;
ALTER TABLE `care_tz_drugsandservices` ADD `nhif_item_code` VARCHAR(20) NULL DEFAULT NULL AFTER `unit_cost`;


ALTER TABLE `care_person` ADD `national_id` VARCHAR(255) NULL DEFAULT NULL AFTER `insurance_ceiling_for_families`, ADD `employee_Id` VARCHAR(255) NULL DEFAULT NULL AFTER `national_id`;


INSERT INTO `care_config_global` (`type`, `value`, `notes`, `status`, `history`, `modify_id`, `modify_time`, `create_id`, `create_time`) VALUES ('nhif_acreditation', NULL, NULL, '', '', '', CURRENT_TIMESTAMP, '', '0000-00-00 00:00:00.000000');

ALTER TABLE `care_tz_diagnosis` ADD `diagnosis_type` ENUM('final','preliminary') NOT NULL DEFAULT 'final' AFTER `doctor_name`;


ALTER TABLE `care_tz_diagnosis` ADD INDEX(`encounter_nr`);
ALTER TABLE `care_tz_diagnosis` ADD INDEX(`type`);



-- 05/11

ALTER TABLE `care_users` ADD `occupation` VARCHAR(255) NULL DEFAULT NULL AFTER `theme_name`, ADD `tel_no` VARCHAR(255) NULL DEFAULT NULL AFTER `occupation`;



--27/11

ALTER TABLE care_person ENGINE=InnoDB;
ALTER TABLE care_encounter ENGINE=InnoDB
ALTER TABLE `care_encounter` CHANGE `pid` `pid` INT(11) UNSIGNED NOT NULL DEFAULT '0';


--13/12

ALTER TABLE `care_encounter` ADD `nhif_card_status` VARCHAR(255)  NULL AFTER `referrer_number`, ADD `nhif_authorization_status` VARCHAR(255)  NULL AFTER `nhif_card_status`, ADD `nhif_authorization_number` VARCHAR(255)  NULL AFTER `nhif_authorization_status`, ADD `nhif_latest_authorization` VARCHAR(255)  NULL AFTER `nhif_authorization_number`, ADD `nhif_visit_type` VARCHAR(255)  NULL AFTER `nhif_latest_authorization`;



--18/12
ALTER TABLE `care_tz_drugsandservices` ADD `is_restricted` BOOLEAN NULL DEFAULT NULL AFTER `nhif_item_code`, ADD `maximum_quantity` INT NULL DEFAULT NULL AFTER `is_restricted`;
ALTER TABLE `care_encounter_prescription` ADD `reason` TEXT NULL DEFAULT NULL AFTER `practitioner_nr`;

--19/12
ALTER TABLE `care_tz_drugsandservices` ADD `nhif_item_type_id` INT NOT NULL DEFAULT '0' AFTER `maximum_quantity`;

--03/01
ALTER TABLE `care_encounter_prescription` CHANGE `reason` `comment` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `care_tz_drugsandservices_description` ADD `company_id` INT NOT NULL DEFAULT '0' AFTER `ShowDescription`;


--07-01
ALTER TABLE `care_person` CHANGE `allergy` `allergy` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

--08/01
ALTER TABLE `care_person` ADD `allergic` TINYINT NOT NULL DEFAULT '0' AFTER `history`;

--17-01
ALTER TABLE `care_tz_diagnosis` ADD INDEX(`timestamp`);
ALTER TABLE `care_tz_laboratory_param` ADD `block_selection` ENUM('yes','no') NOT NULL DEFAULT 'yes' AFTER `price_1`, ADD `enable_upload` ENUM('yes','no') NOT NULL DEFAULT 'yes' AFTER `block_selection`;


--21-01
composer require phpoffice/phpspreadsheet:1.1.0


--28-01
ALTER TABLE `care_tz_laboratory_param` ADD `sort_order` INT NOT NULL DEFAULT '0' AFTER `enable_upload`;
ALTER TABLE `care_test_request_chemlabor_sub` ADD `sort_order` INT NOT NULL DEFAULT '0' AFTER `history`;

--30-01
ALTER TABLE `care_test_findings_chemlabor_sub` ADD `sort_order` INT NOT NULL DEFAULT '0' AFTER `create_time`;

--01-02
ALTER TABLE `care_person` ADD `is_foreigner` TINYINT NOT NULL DEFAULT '0' AFTER `allergic`;
ALTER TABLE `care_user_roles` CHANGE `permission` `permission` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

--04-02
ALTER TABLE `care_test_request_chemlabor_sub` ADD `deleted` TINYINT NOT NULL DEFAULT '0' AFTER `sort_order`;

--12-02
ALTER TABLE `care_person` ADD `sub_insurance_id` INT NOT NULL DEFAULT '0' AFTER `insurance_ID`;
ALTER TABLE `care_person` DROP `is_foreigner`;


--11-03
ALTER TABLE `care_tz_diagnosis` ADD INDEX(`ICD_10_description`);

--30-04
ALTER TABLE `care_test_findings_chemlab` ADD `file_path` VARCHAR(500) NOT NULL AFTER `create_time`;



--08-06
UPDATE `care_config_global` SET `value` = '1|1|1|0|1|2|0|0|2|1|0|0|0' WHERE `care_config_global`.`type` = 'hospital_numbers_to_display';
ALTER TABLE `care_person` ADD `prescribe_without_diagnosis` INT NOT NULL DEFAULT '0' AFTER `national_id`;


--10-06
ALTER TABLE `care_tz_billing_archive_elem` ADD INDEX(`amount`);
ALTER TABLE `care_tz_billing_archive_elem` ADD INDEX(`price`);


ALTER TABLE `care_test_request_chemlabor_sub` ADD INDEX(`bill_number`);
ALTER TABLE `care_test_request_chemlabor` ADD INDEX(`send_date`);


--17-06
ALTER TABLE `care_test_request_radio` ADD `hint` TEXT NULL DEFAULT NULL AFTER `process_time`;

--22-07
ALTER TABLE `care_icd10_en` ADD `opd_series` INT NOT NULL AFTER `series`, ADD `opd_name` TEXT NOT NULL AFTER `opd_series`, ADD `ipd_series` INT NOT NULL AFTER `opd_name`, ADD `ipd_name` TEXT NOT NULL AFTER `ipd_series`;

ALTER TABLE `care_tz_diagnosis` ADD `opd_series` INT NOT NULL DEFAULT '0' AFTER `series`, ADD `opd_name` TEXT NULL DEFAULT NULL AFTER `opd_series`, ADD `ipd_series` INT NOT NULL DEFAULT '0' AFTER `opd_name`, ADD `ipd_name` TEXT NULL DEFAULT NULL AFTER `ipd_series`;

--30-07
ALTER TABLE `care_encounter` ADD `nhif_full_name` VARCHAR(255) NULL DEFAULT NULL AFTER `nhif_visit_type`;
ALTER TABLE `care_encounter` ADD `nhif_remarks` TEXT NULL DEFAULT NULL AFTER `nhif_full_name`;

--31-07
ALTER TABLE `care_role_person` ADD `nhif_qualification_id` INT NOT NULL DEFAULT '0' AFTER `create_time`;
ALTER TABLE `care_users` ADD `nhif_qualification_id` INT NOT NULL DEFAULT '0' AFTER `tel_no`;
ALTER TABLE `care_encounter` ADD `nhif_transfer_details` JSON NULL DEFAULT NULL AFTER `nhif_remarks`;

ALTER TABLE `care_role_person` CHANGE `role` `role` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '';
ALTER TABLE `care_role_person` CHANGE `name` `name` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '';


INSERT INTO `care_role_person` (`nr`, `group_nr`, `role`, `sname`, `name`, `LD_var`, `status`, `modify_id`, `modify_time`, `create_id`, `create_time`, `nhif_qualification_id`) VALUES (NULL, '0', 'Super Specialist', '', 'Super Specialist', '', '', '', CURRENT_TIMESTAMP, '', '0000-00-00 00:00:00.000000', '1'), (NULL, '0', 'Specialist', '', 'Specialist', '', '', '', CURRENT_TIMESTAMP, '', '0000-00-00 00:00:00.000000', '2');
INSERT INTO `care_role_person` (`nr`, `group_nr`, `role`, `sname`, `name`, `LD_var`, `status`, `modify_id`, `modify_time`, `create_id`, `create_time`, `nhif_qualification_id`) VALUES (NULL, '0', 'Medical Officer(MD)/Dental Surgeon(DDS)', '', 'Medical Officer(MD)/Dental Surgeon(DDS)', '', '', '', CURRENT_TIMESTAMP, '', '0000-00-00 00:00:00.000000', '3'), (NULL, '0', 'Assistant Medical Officer(AMO)/Assistant Dental Officer(ADO)', '', 'Assistant Medical Officer(AMO)/Assistant Dental Officer(ADO)', '', '', '', CURRENT_TIMESTAMP, '', '0000-00-00 00:00:00.000000', '4');
INSERT INTO `care_role_person` (`nr`, `group_nr`, `role`, `sname`, `name`, `LD_var`, `status`, `modify_id`, `modify_time`, `create_id`, `create_time`, `nhif_qualification_id`) VALUES (NULL, '0', 'Clinical Officer/Dental Assistant', '', 'Clinical Officer/Dental Assistant', '', '', '', CURRENT_TIMESTAMP, '', '0000-00-00 00:00:00.000000', '5'), (NULL, '0', 'Assistant Clinical Officer', '', 'Assistant Clinical Officer', '', '', '', CURRENT_TIMESTAMP, '', '0000-00-00 00:00:00.000000', '6');
INSERT INTO `care_role_person` (`nr`, `group_nr`, `role`, `sname`, `name`, `LD_var`, `status`, `modify_id`, `modify_time`, `create_id`, `create_time`, `nhif_qualification_id`) VALUES (NULL, '0', 'Others', '', 'Others', '', '', '', CURRENT_TIMESTAMP, '', '0000-00-00 00:00:00.000000', '7');


--05-08
ALTER TABLE `care_encounter` ADD `nhif_serial_number` INT NOT NULL DEFAULT '0' AFTER `nhif_transfer_details`, ADD `nhif_serial_date` DATE NULL DEFAULT NULL AFTER `nhif_serial_number`;
ALTER TABLE `care_person` ADD `nhif_authorization_details` JSON NULL DEFAULT NULL;
ALTER TABLE `care_encounter` ADD `nhif_dob` DATE NULL DEFAULT NULL AFTER `nhif_serial_date`;

ALTER TABLE `care_users` ADD `practitioner_nr` INT NULL DEFAULT '0' AFTER `nhif_qualification_id`;


ALTER TABLE `care_nhif_claims` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);


--14-08
ALTER TABLE `care_tz_drugsandservices` ADD `nhif_is_active` INT NOT NULL AFTER `nhif_item_type_id`, ADD `nhif_is_restricted` INT NOT NULL AFTER `nhif_is_active`, ADD `nhif_package_id` INT NOT NULL AFTER `nhif_is_restricted`, ADD `nhif_price_code` VARCHAR(50) NOT NULL AFTER `nhif_package_id`;
ALTER TABLE `care_tz_drugsandservices` ADD `nhif_scheme_id` INT NOT NULL DEFAULT '0' AFTER `nhif_price_code`;

ALTER TABLE `care_encounter_prescription` ADD `nhif_item_code` INT NOT NULL DEFAULT '0' AFTER `comment`;
ALTER TABLE `care_tz_billing_archive_elem` ADD `nhif_item_code` INT NOT NULL DEFAULT '0' AFTER `encounter_class_nr`;

ALTER TABLE `care_encounter_prescription` ADD `nhif_approval_no` VARCHAR(255) NULL DEFAULT NULL AFTER `nhif_item_code`;
ALTER TABLE `care_tz_billing_archive_elem` ADD `nhif_approval_no` VARCHAR(255) NULL DEFAULT NULL AFTER `nhif_item_code`;


--inc_init main.php

$nhif_base = 'https://verification.nhif.or.tz/NHIFService';
$nhif_test_base = 'https://verification.nhif.or.tz/test/nhifservice';

$nhif_claim_server = 'https://verification.nhif.or.tz/claimsserver';
$nhif_claim_url = 'https://verification.nhif.or.tz/claimsserver/api/v1/Packages/GetPricePackage';

$claims_token_url = 'https://verification.nhif.or.tz/ClaimsServer/Token';
$claims_api_url = 'https://verification.nhif.or.tz/ClaimsServer/api/v1/Claims/SubmitFolios';

AICC Facility code  04635

--26-08
ALTER TABLE `care_encounter` ADD `nhif_scheme_id` VARCHAR(50) NULL;
ALTER TABLE `care_test_request_chemlabor_sub` ADD `nhif_item_code` INT NOT NULL DEFAULT '0' AFTER `deleted`;
ALTER TABLE `care_test_request_radio` ADD `nhif_item_code` INT NOT NULL DEFAULT '0' AFTER `hint`;


--04-09
CREATE TABLE `caredb_aicc`.`care_tz_drugsandservices_nhifschemes` ( `id` INT NOT NULL AUTO_INCREMENT , `item_id` INT NOT NULL , `scheme_id` INT NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `care_tz_drugsandservices_nhifschemes` ADD `unit_price` DECIMAL(15,2) NOT NULL AFTER `scheme_id`;
ALTER TABLE `care_tz_drugsandservices_nhifschemes` ADD `updated_at` TIMESTAMP NULL DEFAULT NULL AFTER `created_at`;
ALTER TABLE `care_tz_drugsandservices` CHANGE `nhif_item_code` `nhif_item_code` INT(20) NULL DEFAULT NULL;

ALTER TABLE `care_encounter_prescription` ADD `meal_type` VARCHAR(255) NOT NULL AFTER `nhif_approval_no`;
ALTER TABLE `care_tz_billing_archive_elem` ADD `meal_type` VARCHAR(255) NULL DEFAULT NULL AFTER `nhif_approval_no`;

--06-09
ALTER TABLE `care_encounter_prescription` ADD INDEX(`meal_type`);
ALTER TABLE `care_tz_drugsandservices_nhifschemes` ADD INDEX(`item_id`);


--26-09

-- Linux Commands (Installing I Notify)
pecl channel-update pecl.php.net
  sudo apt-get install php5.6-dev
sudo pecl install inotify-0.1.6

--then enable inotify
sudo vim /etc/php/5.6/mods-available/inotify.ini

--  Add below line to the file
extension=inotify.so

--Then Enable inotify
sudo a2enmod inotify

--Restart Apache
sudo service apache2 restart

-- initiate the php file
nohup php  /var/www/html/careMd/machinestests/labmachines/dh76.php </dev/null &>/dev/null &

-- dh76 Machine Tests
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`status`,`history`,`modify_id`,`sort_nr`,`block_selection`,`enable_upload`) VALUES ('-1','dh76','dh76',' ', CONCAT(history,'Created 2019-09-29 10:06:43 rayton '),'rayton','58','yes','yes');

INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','WBC','_wbc__dh76','10^3/uL','9.50','3.50','9.50','3.50','input_box',' ', CONCAT(history,'Created 2019-09-29 10:12:44 rayton '),'rayton','1');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','Neu%','_neu1__dh76','%','75.0','40.0','75.0','40.0','input_box',' ', CONCAT(history,'Created 2019-09-29 10:15:37 rayton '),'rayton','2');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','Lym%','_lym1__dh76','%','50.0','20','50.0','20.0','input_box',' ', CONCAT(history,'Created 2019-09-29 10:17:57 rayton '),'rayton','3');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','Mon%','_mon1__dh76','%','10.0','3.0','10.0','3.0','input_box',' ', CONCAT(history,'Created 2019-09-29 10:27:50 rayton '),'rayton','4');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','Eos%','_eos1__dh76','%','8.0','0.4','8.0','0.4','input_box',' ', CONCAT(history,'Created 2019-09-29 10:29:56 rayton '),'rayton','5');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','Bas%','_bas1__dh76','%','1.0','0.0','1.0','0.0','input_box',' ', CONCAT(history,'Created 2019-09-29 10:40:05 rayton '),'rayton','6');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','Neu#','_neu2__dh76','10^3/uL','6.30','1.80','6.30','1.80','input_box',' ', CONCAT(history,'Created 2019-09-29 10:45:36 rayton '),'rayton','7');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','Lym#','_lym2__dh76','10^3/uL','3.20','1.10','3.20','1.10','input_box',' ', CONCAT(history,'Created 2019-09-29 10:48:32 rayton '),'rayton','8');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','Mon#','_mon2__dh76','10^3/uL','0.60','0.10','0.60','0.10','input_box',' ', CONCAT(history,'Created 2019-09-29 10:53:10 rayton '),'rayton','9');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','Eos#','_eos2__dh76','10^3/uL','0.52','0.02','0.52','0.02','input_box',' ', CONCAT(history,'Created 2019-09-29 10:55:21 rayton '),'rayton','10');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','Bas%','_bas2__dh76','10^3/uL','0.06','0.00','0.06','0.00','input_box',' ', CONCAT(history,'Created 2019-09-29 10:58:51 rayton '),'rayton','11');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','RBC','_rbc__dh76','10^6/uL','5.8','3.8','5.8','3.8','input_box',' ', CONCAT(history,'Created 2019-09-29 11:01:56 rayton '),'rayton','12');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','HGB','_hgb__dh76','g/dl','17.5','11.5','17.5','11.5','input_box',' ', CONCAT(history,'Created 2019-09-29 11:06:04 rayton '),'rayton','13');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','HCT','_hct__dh76','%','50.0','35.0','50.0','35.0','input_box',' ', CONCAT(history,'Created 2019-09-29 11:07:33 rayton '),'rayton','14');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','MCV','_mcv__dh76','fL','100.0','82.0','100.0','82.0','input_box',' ', CONCAT(history,'Created 2019-09-29 11:10:15 rayton '),'rayton','15');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','MCH','_mch__dh76','pg','34.0','27.0','34.0','27.0','input_box',' ', CONCAT(history,'Created 2019-09-29 11:12:18 rayton '),'rayton','16');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','MCHC','_mchc__dh76','g/dL','35.4','31.6','35.4','31.6','input_box',' ', CONCAT(history,'Created 2019-09-29 11:14:29 rayton '),'rayton','17');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','RDW-CV','_rdw_cv__dh76','%','16.0','11.0','16.0','11.0','input_box',' ', CONCAT(history,'Created 2019-09-29 11:16:19 rayton '),'rayton','18');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','RDW-SD','_rdw_sd__dh76','fL','56.0','35.0','56.0','35.0','input_box',' ', CONCAT(history,'Created 2019-09-29 11:18:11 rayton '),'rayton','19');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','PLT','_plt__dh76','10^3/uL','350','125','350','125','input_box',' ', CONCAT(history,'Created 2019-09-29 11:19:46 rayton '),'rayton','20');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','MPV','_mpv__dh76','fL','12.0','6.5','12.0','6.5','input_box',' ', CONCAT(history,'Created 2019-09-29 11:28:54 rayton '),'rayton','21');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','PDW','_pdw__dh76','fL','17.0','9.0','17.0','9.0','input_box',' ', CONCAT(history,'Created 2019-09-29 11:30:50 rayton '),'rayton','22');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','PCT','_pct__dh76','%','0.282','0.108','0.282','0.108','input_box',' ', CONCAT(history,'Created 2019-09-29 11:34:45 rayton '),'rayton','23');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','P-LCR','_p_lcr__dh76','%','45.0','11.0','45.0','11.0','input_box',' ', CONCAT(history,'Created 2019-09-29 11:36:50 rayton '),'rayton','24');
INSERT INTO care_tz_laboratory_param (`group_id`,`name`,`id`,`msr_unit`,`hi_bound`,`lo_bound`,`hi_bound_f`,`lo_bound_f`,`field_type`,`status`,`history`,`modify_id`,`sort_order`) VALUES ('dh76','P-LCC','_p_lcc__dh76','10^9/uL','90','30','90','30','input_box',' ', CONCAT(history,'Created 2019-09-29 11:38:55 rayton '),'rayton','25');




Show global status like 'opened_files';

--my.conf open_files_limit

sysctl fs.inotify;
sysctl fs.inotify.max_user_instances=2560  1048576
fs.file-max = 2097152
sudo vim /etc/sysctl.conf

echo fs.inotify.max_user_watches=524288 | sudo tee -a /etc/sysctl.conf && sudo sysctl -p


-- what process consuming inotify
find /proc/*/fd -lname anon_inode:inotify |
   cut -d/ -f3 |
   xargs -I '{}' -- ps --no-headers -o '%p %U %c' -p '{}' |
   uniq -c |
   sort -nr
--number of inotify
sudo lsof | grep -i inotify | wc -l
ps aux | grep php
*/

--14/11
--update db visit /updateDB.php 


sudo apt install php7.3-zip
sudo apt install php7.3-xml
sudo apt install php7.3-gd
sudo apt install php7.3-mbstring
sudo apt install php-mysql
sudo apt-get install php-dev
sudo pecl install inotify
sudo vim /etc/php/7.3/mods-available/inotify.ini
sudo vim /etc/php/7.1/mods-available/inotify.ini

--  Add below line to the file
extension=inotify.so

--Then Enable inotify
sudo phpenmod inotify


-- 28-11
ALTER TABLE `care_test_findings_chemlab` CHANGE `group_id` `group_id` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '0';
--16-feb-2020
ALTER TABLE `care_test_findings_chemlab`  ADD `labcomment` LONGTEXT NOT NULL  AFTER `file_path`;

--nursing treatment sheet 13.apr.2020
CREATE TABLE `care_tz_nursing_chart` ( `id` INT NOT NULL AUTO_INCREMENT , `userdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `usertime` VARCHAR(11) NULL DEFAULT NULL , `systemdate` INT NOT NULL , `systemtime` INT NOT NULL , `qty` INT NOT NULL , `comment` LONGTEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
