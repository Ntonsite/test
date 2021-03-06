<?php

require('./roots.php');
require($root_path . 'include/inc_environment_global.php');

$last_room = $_POST['last_room'];
$ward_nr = $_POST['ward_nr'];
$user = $_POST['sid'];
$ward_id = $_POST['ward_id'];
$num_rooms = $_POST['num_rooms'];

global $db;

$date = date('Y-m-d');
$now =  date("Y-m-d H:i:s");


for ($i=1; $i <= $num_rooms; $i++) {
	$roomNumber = $last_room + $i;
	$sql = "INSERT INTO care_room (type_nr, date_create, room_nr, ward_nr, create_id, create_time) VALUES ('1', '$date', '$roomNumber',' $ward_nr', '$user', '$now')";
	$db->Execute($sql);
}

$current_rooms = $last_room + $num_rooms;
$updateSQL = "UPDATE care_ward SET room_nr_end = $current_rooms WHERE nr = $ward_nr ";
$db->Execute($updateSQL);

$data['updated'] = 1;

header('Content-type: application/json');
echo json_encode( $data );