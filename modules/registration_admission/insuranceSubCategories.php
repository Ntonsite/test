<?php
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/inc_front_chain_lang.php');
require_once($root_path . 'include/care_api_classes/class_lab.php');


$insuranceId = $_GET['insuranceId'];

$insuranceQuery = "SELECT ID, ShowDescription FROM care_tz_drugsandservices_description where company_id = {$insuranceId}";
$insuranceResult = $db->Execute($insuranceQuery);

$insuranceSubCategories = array();

$categories = [];
if (@$insuranceResult) {
	$categories = $insuranceResult->getArray();
}

foreach ($categories as $category) {
	$value = [
		'id' => $category['ID'],
		'name' => $category['ShowDescription'],
	];
	
	$insuranceSubCategories[] = $value;
	
}
$data['insuranceSubCategories'] = $insuranceSubCategories;
header('Content-type: application/json');
echo json_encode( $data );
