<?php

ini_set("memory_limit", "-1");
set_time_limit(0);

require_once('./roots.php');
include_once($root_path . 'include/inc_environment_global.php');

$sql="UPDATE care_tz_diagnosis SET is_deleted='1',history='".$_SESSION['sess_user_name'].' Deleted at:'.date('Y-m-d H:i:s')."', modify='".$_SESSION['sess_user_name']."'  WHERE case_nr='".$_GET['caseId']."' ";
$db->Execute($sql);


$data['updated'] = 1;


header('Content-type: application/json');
echo json_encode( $data );







