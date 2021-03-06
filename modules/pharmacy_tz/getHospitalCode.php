<?php

require('./roots.php');

require($root_path . 'include/inc_environment_global.php');
global $db;

$hospital_code = 0;
$hsql="SELECT value FROM  care_config_global WHERE type = 'main_info_facility_code' ";
$hospQuery = $db->Execute($hsql);

while ($hospital_datail=$hospQuery->FetchRow()) {
$hospital_code = $hospital_datail['value'];
}

$data['hospital_code'] = $hospital_code;

header('Content-Type: application/json');
echo json_encode($data);
