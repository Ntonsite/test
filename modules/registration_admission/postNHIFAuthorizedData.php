<?php
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/inc_front_chain_lang.php');
global $db;



$nhif_full_name = $_POST['nhif_full_name'];
$nhif_card_status = $_POST['nhif_card_status'];
$nhif_authorization_status = $_POST['nhif_authorization_status'];
$nhif_authorization_number = $_POST['nhif_authorization_number'];
$nhif_latest_authorization = $_POST['nhif_latest_authorization'];
$nhif_scheme_id = $_POST['nhif_scheme_id'];
$nhif_remarks = $_POST['nhif_remarks'];
$encounter_nr = $_POST['encounter_nr'];

$sql = "UPDATE care_encounter SET nhif_full_name = '$nhif_full_name', nhif_card_status = '$nhif_card_status', nhif_authorization_status = '$nhif_authorization_status', nhif_authorization_number = '$nhif_authorization_number', nhif_latest_authorization = '$nhif_latest_authorization', nhif_remarks = '$nhif_remarks', nhif_scheme_id = '$nhif_scheme_id' where encounter_nr = '$encounter_nr'";

$db->Execute($sql);
