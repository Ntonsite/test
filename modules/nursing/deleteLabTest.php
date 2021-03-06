<?php

require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
global $db;

$sub_id = $_GET['sub_id'];

$sql = "UPDATE care_test_request_chemlabor_sub SET DELETED = 1 WHERE sub_id = {$sub_id} ";
$db->Execute($sql);

$data['deleted'] = 1;

header('Content-type: application/json');
echo json_encode( $data );
