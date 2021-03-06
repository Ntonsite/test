<?php

ini_set("memory_limit", "-1");
set_time_limit(0);
require_once('./roots.php');
include_once($root_path . 'include/inc_environment_global.php');

$encounter_nr = $_GET['encounter_nr'];

$CardNo = "";
$AuthorizationStatus = "";
$AuthorizationNo = "";
$PatientFullName = "";
$PhysicianMobileNo = "";
$Gender = "";
$PhysicianName = "";
$PhysicianQualificationID = 0;
$ServiceIssuingFacilityCode = "";
$ReferringDiagnosis = "";
$Remarks = "";
$CardStatus = "";
$DoB = "";


$pprSQL = "SELECT cp.membership_nr, ce.nhif_authorization_number, cp.name_last, CONCAT(cp.name_first,' ', cp.name_2) AS name_first, cp.sex, ce.create_id, ce.nhif_authorization_status, ce.nhif_remarks, ce.nhif_card_status, cp.date_birth
		FROM care_encounter ce
		INNER JOIN care_person cp
			ON ce.pid = cp.pid
		WHERE ce.encounter_nr = ".$encounter_nr." ";

$ppr = $db->Execute($pprSQL);
if (@$ppr && $ppr->RecordCount()) {
	$patient = $ppr->FetchRow();
	$CardNo = $patient['membership_nr'];
	$CardStatus = $patient['nhif_card_status'];
	$AuthorizationStatus = $patient['nhif_authorization_status'];
	$AuthorizationNo = $patient['nhif_authorization_number'];
	$Remarks = $patient['nhif_remarks'];
	$DoB = date("M d, Y", strtotime($patient['date_birth']));
	$PatientFullName = $patient['name_first'] . " " . $patient['name_last'];
	if ($patient['sex'] == "m") {
		$Gender = 'Male';
	}else {
		$Gender = 'Female';
	}
	$PhysicianName = $patient['create_id'];
}

$diagSQL = "SELECT ICD_10_code FROM care_tz_diagnosis where encounter_nr = '$encounter_nr' AND diagnosis_type = 'final'";
$diagnosises = [];
$diagResult = $db->Execute($diagSQL);
if (@$diagResult && $diagResult->RecordCount()) {
	$diagnosises = $diagResult->GetArray();
}

foreach ($diagnosises as $key => $diagnosis) {
	if ($key == 0) {
		$ReferringDiagnosis .= $diagnosis['ICD_10_code'];
	}else {
		$diag = ",". $diagnosis['ICD_10_code'];
		$ReferringDiagnosis .= $diag;	
	}
}


$PhysicianQualificationID = 0;
$PhysicianMobileNo = "";

$docSQL="SELECT nhif_qualification_id, tel_no FROM  care_users WHERE login_id = '$PhysicianName' LIMIT 1 ";
$docResult = $db->Execute($docSQL);

if (@$docResult && $docResult->RecordCount() > 0) {
	$doc =$docResult->FetchRow();
  	$PhysicianQualificationID = $doc['nhif_qualification_id'];
  	$PhysicianMobileNo = $doc['tel_no'];
}

$data['CardNo'] = $CardNo;
$data['CardStatus'] = $CardStatus;
$data['AuthorizationStatus'] = $AuthorizationStatus;
$data['AuthorizationNo'] = $AuthorizationNo;
$data['PatientFullName'] = $PatientFullName;
$data['PhysicianMobileNo'] = $PhysicianMobileNo;
$data['Gender'] = $Gender;
$data['Remarks'] = $Remarks;
$data['DoB'] = $DoB;

$data['PhysicianName'] = $PhysicianName;
$data['PhysicianQualificationID'] = $PhysicianQualificationID;
$data['ServiceIssuingFacilityCode'] = $ServiceIssuingFacilityCode;
$data['ReferringDiagnosis'] = $ReferringDiagnosis;


header('Content-type: application/json');
echo json_encode( $data );


