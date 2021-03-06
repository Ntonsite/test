<?php

require_once('./roots.php');
require_once $root_path.'vendor/autoload.php';
require_once $root_path.'generated-conf/config.php';

use  CareMd\CareMd\CareEncounterQuery;
use  CareMd\CareMd\CareEncounterLocationQuery;

$encounterNr = $_GET['encounterNr'];

$in_ward = 0;
$in_dept = 0;

$patientRow = CareEncounterQuery::create()->filterByEncounterNr($encounterNr)->findOne()->toArray();

if ($patientRow['EncounterClassNr'] == 1) {
	$in_ward = 0;
}else{
	$in_dept = 1;
}

$patient = CareEncounterQuery::create()->findOneByEncounterNr($encounterNr);
$patient->setIsDischarged(0);
$patient->setDischargeDate("");
$patient->setDischargeTime("");
$patient->setStatus("");
$patient->setInWard($in_ward);
$patient->setInDept($in_dept);
$patient->save();

 
$_SESSION['sess_login_userid']=isset($_SESSION['sess_login_userid'])? $_SESSION['sess_login_userid'] : '';
$history = "Create: " . date('Y-m-d H:i:s') . $_SESSION['sess_login_userid'];
$patient = CareEncounterLocationQuery::create()->findOneByEncounterNr($encounterNr);
$patient->setDateTo("0000-00-00");
$patient->setTimeTo("");
$patient->setStatus("");
$patient->setHistory($history);
$patient->save();


$data['updated'] = 1;
header('Content-type: application/json');
echo json_encode( $data );


 ?>