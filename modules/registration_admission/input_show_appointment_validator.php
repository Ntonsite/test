<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require './roots.php';

require $root_path . 'include/inc_environment_global.php';
include_once $root_path . 'include/care_api_classes/class_multi.php';

$multi = new multi;

require_once $root_path . 'include/care_api_classes/class_appointment.php';
$obj = new Appointment();

extract($_GET);
$dz = explode('/', $dt);
$dt2 = $dz[2] . '-' . $dz[1] . '-' . $dz[0];
//echo $dt2.'>'.$dpt.'<';
if (($tm != '') && ($dt != '') && ($dpt != '')) {
	$vk = $db->Execute(' SELECT * FROM care_appointment WHERE date="' . $dt2 . '"  AND to_dept_nr="' . $dpt . '" ORDER BY nr DESC LIMIT 1');

	//Get minutes from time and chech if is withing 15 minutes
	$gt = explode(':', $tm);
	$gt2 = $gt[0] . ':' . $gt[1];
	$gth = $gt[0];
	$gtm = $gt[1];

	$chimbotime = $vk->FetchRow();
	$chimbotime = $chimbotime['time'];
	$cgt = explode(':', $chimbotime);
	$cgth = $cgt[0];
	$cgtm = $cgt[1];
	$cgt2 = $cgt[0] . ':' . $cgt[1];
	$dakikagape = $gtm - $cgtm;
	if ($vk->RecordCount() > 0 & $gth == $cgth & $dakikagape <= 15) {

		//print $chimbotime.'Diff'.$gtm.'--'.$dakikagape;

		print 'There is another appointment on this Time <input type="hidden" id="hd" name="hd" value="1">';
	} else {
		print '<input type="hidden" id="hd" name="hd" value="0">';
		print $db->errorMsg();
	}
	//Check That the allowed number of appointments is not exceeded

	if ($obj->appointment_count($dt2, '_DEPT', $dpt)) {
		$sql = $db->Execute("SELECT max_appointments FROM care_department
                                                WHERE nr = $dpt");
		$app = $sql->FetchRow();
		$app = $app['max_appointments'];
		print 'You have reached the maximum number of ' . $app . ' appointments for ' . $dt . ' on this department'
			. '<input type="hidden" id="max_app" name="max_app" value="1">';
	}
} else {
	print ' ... <input type="hidden" id="hd" name="hd" value="0">';
}

?>