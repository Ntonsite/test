<?php

require_once('./roots.php');
include_once($root_path . 'include/inc_environment_global.php');
global $db;

$columnValue = $_GET['columnValue'];
$columnName = $_GET['columnName'];
$columnId = $_GET['columnId'];

$sql = "UPDATE care_tz_drugsandservices SET $columnName = $columnValue WHERE item_id = '$columnId'";
echo $sql;

$db->Execute($sql);