<?php

ini_set("memory_limit", "-1");
set_time_limit(0);

require './roots.php';

require $root_path . 'include/inc_init_main.php';
$conn = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

if ($conn) {
	echo 'success';
}else{
	echo 'failed';
}