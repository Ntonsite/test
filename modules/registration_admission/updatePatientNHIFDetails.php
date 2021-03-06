<?php
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/inc_front_chain_lang.php');
global $db;


$pid = $_POST['pid'];
$_POST = str_replace("'", '', $_POST);
$authorization_details = json_encode($_POST);



$sql = "UPDATE care_person SET nhif_authorization_details = '$authorization_details' where pid = '$pid'";



$db->Execute($sql);

if (@$_POST['encounter_nr'] && $_POST['encounter_nr'] > 0 ) {

	$nhif_full_name = $_POST['FullName'];
	$nhif_card_status = $_POST['CardStatus'];
	$nhif_authorization_status = $_POST['AuthorizationStatus'];
	$nhif_authorization_number = $_POST['AuthorizationNo'];
	$nhif_latest_authorization = $_POST['LatestAuthorization'];
	$nhif_remarks = $_POST['Remarks'];
	$encounter_nr = $_POST['encounter_nr'];
	$nhif_scheme_id = $_POST['SchemeID'];

	$sql = "UPDATE care_encounter SET nhif_full_name = '$nhif_full_name', nhif_card_status = '$nhif_card_status', nhif_authorization_status = '$nhif_authorization_status', nhif_authorization_number = '$nhif_authorization_number', nhif_latest_authorization = '$nhif_latest_authorization', nhif_remarks = '$nhif_remarks', nhif_scheme_id = '$nhif_scheme_id' where encounter_nr = '$encounter_nr'";

	$db->Execute($sql);
}
