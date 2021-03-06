<?php

require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/inc_front_chain_lang.php');
global $db;

$cardNo = $_GET['cardno'];

$sql = "SELECT pid FROM care_person WHERE membership_nr = '$cardNo'";

$result = $db->Execute($sql);
$patient = [];
if(@$result && $result->RecordCount() > 0) {
	$patient = $result->FetchRow();
}

$data['patient'] = $patient;

header('Content-type: application/json');
echo json_encode( $data );
