<?php

require_once('./roots.php');
require($root_path . 'include/inc_environment_global.php');

require_once $root_path.'vendor/autoload.php';
require_once $root_path.'generated-conf/config.php';
global $db;


$encounterNr = $_GET['encounterNr'];

$in_ward = 0;
$in_dept = 1;

$patientRow = [];
$sql = "SELECT encounter_class_nr FROM care_encounter WHERE encounter_nr = '$encounterNr'";
$result = $db->Execute($sql);

if (@$result && $result->RecordCount() > 0) {
	$patientRow = $result->FetchRow();
}
if (@$patientRow) {
	if ($patientRow['encounter_class_nr'] == 1) {
		$in_ward = 0;
	}else{
		$in_dept = 1;
	}
}

$sql = "UPDATE care_encounter SET is_discharged = 0, discharge_date = '', discharge_time = '', status = '', in_ward = '$in_ward', in_dept = '$in_dept' WHERE encounter_nr = '$encounterNr' ";
$db->Execute($sql);

 
$_SESSION['sess_login_userid']=isset($_SESSION['sess_login_userid'])? $_SESSION['sess_login_userid'] : '';
$history = "Create: " . date('Y-m-d H:i:s') . $_SESSION['sess_login_userid'];

$sql = "UPDATE care_encounter_location SET date_to = '0000-00-00', time_to = '',  status = '', history = '$history' WHERE encounter_nr = '$encounterNr' ";
$db->Execute($sql);

$sql = "DELETE FROM care_nhif_claims WHERE encounter_nr = '$encounterNr'";
$db->Execute($sql);


$data['updated'] = 1;
header('Content-type: application/json');
echo json_encode( $data );


 ?>