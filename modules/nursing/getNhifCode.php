<?php
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/inc_front_chain_lang.php');
global $db;

$radtest = $_POST['radtest'];

$sql = "SELECT 	nhif_item_code FROM care_tz_drugsandservices WHERE item_id = '$radtest'";

$result = $db->Execute($sql);
$nhifCode = [];
if(@$result && $result->RecordCount() > 0) {
	$nhifCode = $result->FetchRow();
}

$data['code'] = $nhifCode;

header('Content-type: application/json');
echo json_encode( $data );