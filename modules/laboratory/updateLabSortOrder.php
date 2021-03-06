<?php

require_once('./roots.php');
include_once($root_path . 'include/inc_environment_global.php');
global $db;

$nr = $_GET['nr'];
$sort_order = $_GET['sort_order'];

$sql = "UPDATE care_tz_laboratory_param SET sort_order ='$sort_order' WHERE nr = '$nr'";

$db->Execute($sql);


