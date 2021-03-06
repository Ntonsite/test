<?php

ini_set("memory_limit", "-1");
set_time_limit(0);

require_once('./roots.php');
include_once($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_nhif_claims.php');

$encounter_nr = $_GET['encounter_nr'];
$type = $_GET['type'];
$claims_obj = new Nhif_claims;

$data['encounter_nr'] = $encounter_nr;
$data['in_outpatient'] = trim($type);

$claims = $claims_obj->claims_json($data);

header('Content-type: application/json');
echo  $claims;
