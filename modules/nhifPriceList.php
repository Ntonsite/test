<?php

ini_set("memory_limit", "-1");
set_time_limit(0);
ini_set("post_max_size", "400M");
ini_set("upload_max_filesize", "400M");
ini_set("max_input_vars", "1000");

$root_path = "../";
include_once('../include/inc_environment_global.php');

global $db;

require  $root_path.'vendor/autoload.php';

$nhifPrices =  json_decode($_POST['formdata']);


$sql = "TRUNCATE TABLE care_tz_drugsandservices_nhifschemes";
$db->Execute($sql);

$nhifPricesArray = [];
foreach ($nhifPrices as $nhifPrice) {
	array_push($nhifPricesArray, (array)$nhifPrice);
	
	$ItemCode = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->ItemCode));
		$PriceCode = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->PriceCode));
		$LevelPriceCode = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->LevelPriceCode));
		$OldItemCode = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->OldItemCode));
		$ItemName = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->ItemName));
		$Strength = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->Strength));
		$PackageID = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->PackageID));
		$SchemeID = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->SchemeID));
		$FacilityLevelCode = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->FacilityLevelCode));
		$UnitPrice = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->UnitPrice));
		$IsRestricted = (str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->IsRestricted))?1:0;
		$Dosage = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->Dosage));
		$ItemTypeID = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->ItemTypeID));
		$MaximumQuantity = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->MaximumQuantity));
		$AvailableInLevels = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->AvailableInLevels));
		$PractitionerQualifications = addslashes(str_replace(array(':', '-', '/', '*', '®'), ' ', $nhifPrice->PractitionerQualifications));
		$IsActive = ($nhifPrice->IsActive)?1:0;
	
	$schemeAddSQL = "INSERT INTO care_tz_drugsandservices_nhifschemes (ItemCode, PriceCode, LevelPriceCode, OldItemCode, ItemName, Strength, PackageID, SchemeID, FacilityLevelCode, UnitPrice, IsRestricted, Dosage, ItemTypeID, MaximumQuantity, AvailableInLevels, PractitionerQualifications, IsActive) VALUES('$ItemCode','$PriceCode', '$LevelPriceCode', '$OldItemCode', '$ItemName', '$Strength', '$PackageID', '$SchemeID', '$FacilityLevelCode', '$UnitPrice', '$IsRestricted', '$Dosage', '$ItemTypeID', '$MaximumQuantity', '$AvailableInLevels', '$PractitionerQualifications', '$IsActive')";
			$db->Execute($schemeAddSQL);
}


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();



$spreadsheet->getActiveSheet()
    ->fromArray(
        array_keys($nhifPricesArray[0]),  // The data to set
        NULL,        // Array values with this value will not be set
        'A1'         // Top left coordinate of the worksheet range where
                     //    we want to set these values (default is A1)
    );
$spreadsheet->getActiveSheet()
    ->fromArray(
        $nhifPricesArray,  // The data to set
        NULL,        // Array values with this value will not be set
        'A2'         // Top left coordinate of the worksheet range where
                     //    we want to set these values (default is A1)
    );

$writer = new Xlsx($spreadsheet);
$writer->save('nhifprices.xlsx');



$data['success'] = 1;

header('Content-type: application/json');
echo json_encode( $data );