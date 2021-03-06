<?php

require_once './roots.php';
require_once '../vendor/autoload.php';
require_once './config.php';

include_once $root_path . 'include/inc_environment_global.php';
global $db;

$sql = "SELECT value FROM care_config_global WHERE type = 'timeout_time' ";
$result = $db->Execute($sql);

$timeOut = 001000;

if (@$result && $result->RecordCount()) {
	$row = $result->FetchRow();
	$timeOut = $row['value'];
}

$hours = (int) substr($timeOut, 0, 2) * 60 * 60;
$minutes = (int) substr($timeOut, 2, 2) * 60;
$seconds = (int) substr($timeOut, 4, 4);

$totalSeconds = $hours + $minutes + $seconds;
$data['timeout'] = $totalSeconds * 1000;

$data['loginUrl'] = $root_path . "main/login.php";

header('Content-type: application/json');
echo json_encode($data);
