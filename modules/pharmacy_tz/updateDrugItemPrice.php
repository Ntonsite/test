<?php

require_once('./roots.php');
include_once($root_path . 'include/inc_environment_global.php');
global $db;

$item_id = $_GET['item_id'];
$priceIndex = $_GET['priceIndex'];
$itemValue = $_GET['itemValue'];

if ($priceIndex == 0) {
	$columnName = "unit_price";
}else {
	$columnName = "unit_price_". $priceIndex;
}

$sql = "UPDATE care_tz_drugsandservices SET $columnName = $itemValue WHERE item_id = '$item_id'";
// echo $sql;

$db->Execute($sql);

$data['amount'] = $itemValue;
$data['success'] = 1;

header('Content-type: application/json');
echo json_encode( $data );
