<?php

require_once('./roots.php');
include_once($root_path . 'include/inc_environment_global.php');
global $db;

$scheme_value = $_GET['scheme_value'];
$scheme_id = $_GET['scheme_id'];

$sql = "INSERT INTO care_tz_drugsandservices_nhifschemes (item_id, scheme_id) VALUES('$scheme_id', '$scheme_value')";
$db->Execute($sql);

$inserted_scheme_id = 0;

$sql = "SELECT id FROM care_tz_drugsandservices_nhifschemes WHERE item_id = '$scheme_id' AND scheme_id = '$scheme_value' ";
$result = $db->Execute($sql);
if (@$result && $result->RecordCount() > 0) {
	$row = $result->FetchRow();
	$inserted_scheme_id = $row['id'];
}

$data['scheme_id'] = $inserted_scheme_id;

header('Content-type: application/json');
echo json_encode( $data );
