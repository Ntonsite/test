<?php
ini_set('memory_limit', '-1');
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');

require_once('./roots.php');
global $db;
$_SESSION['icd_updated'] = 0;

require_once $root_path . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$file_name = $_FILES['fileToUpload']['name'];
$file_size = $_FILES['fileToUpload']['size'];
$file_tmp = $_FILES['fileToUpload']['tmp_name'];
$file_type = $_FILES['fileToUpload']['type'];
//$file_ext=strtolower(end(explode('.', $file_name)));
$file_ext1 = explode('.', $file_name);
$file_ext = end($file_ext1);
$file_ext = strtolower($file_ext);



$uploadDirectory = "uploads/";
if (!file_exists($uploadDirectory)) {
	$oldmask = umask(0);
	mkdir($uploadDirectory, 0777, true);
	umask($oldmask);
}

$filePath = $uploadDirectory . $file_name;
move_uploaded_file($file_tmp, $filePath);

$inputFileType = IOFactory::identify($filePath);
$reader = IOFactory::createReader($inputFileType);

$spreadsheet = new Spreadsheet();
$spreadsheet = $reader->load($filePath);

$sheetData = $spreadsheet->getActiveSheet();
$rows = $sheetData->toArray();
foreach ($rows as  $row) {
	$icdCode = $row[0];
	$icdDiagnosis = $row[1];
	$opdSerial = $row[2];
	$opdName = $row[3];
	$ipdSerial = $row[4];
	$ipdName = $row[5];

	$icdSQL = "UPDATE care_icd10_en SET opd_series = '" . $opdSerial . "' , opd_name = '" . $opdName . "' , ipd_series ='" . $ipdSerial . "' , ipd_name = '" . $ipdName . "' WHERE diagnosis_code = '" . $icdCode . "'";
	$db->Execute($icdSQL);

	$diagSQL = "UPDATE care_tz_diagnosis SET opd_series = '" . $opdSerial . "' , opd_name = '" . $opdName . "' , ipd_series ='" . $ipdSerial . "' , ipd_name = '" . $ipdName . "' WHERE ICD_10_code = '" . $icdCode . "'";
	$db->Execute($diagSQL);
}

$_SESSION['icd_updated'] = 1;

unlink($filePath);
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
