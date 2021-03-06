<?php

ini_set("memory_limit", "-1");
set_time_limit(0);

require_once('./roots.php');
include_once($root_path . 'include/inc_environment_global.php');

$data = $_REQUEST;
$dataBlob = json_encode($data);

$encounter_nr = $data['encounter_nr'];

$nhifsql = "UPDATE care_encounter set nhif_transfer_details = '$dataBlob' WHERE encounter_nr = '$encounter_nr'";
$db->Execute($nhifsql);

?>