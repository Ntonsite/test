<?php

require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
global $db;

$batch = $_GET['batch_nr'];

$sql = "UPDATE care_test_request_radio SET is_deleted = 1,Deletion_History='".$_SESSION['sess_user_name'].' Deleted at:'.date('Y-m-d H:i:s')."' WHERE batch_nr = {$batch} ";
$db->Execute($sql);

$data['deleted'] = 1;

header('Content-type: application/json');
echo json_encode( $data );
