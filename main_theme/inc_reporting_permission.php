<?php

require_once $root_path . 'vendor/autoload.php';
require_once $root_path . 'generated-conf/config.php';

global $db;

$userId = $_SESSION['sess_login_userid'];

$roleId = 0;
$usrSQL = "SELECT role_id FROM care_users WHERE login_id = '$userId'";
$usrResult = $db->Execute($usrSQL);
if (@$usrResult && $usrResult->RecordCount()) {
	$usrRow = $usrResult->FetchRow();
	$roleId = $usrRow['role_id'];
}
// $roleId = 3;

$themeName = "";
$themeSQL = "SELECT theme_name FROM care_user_roles WHERE role_id = '$roleId'";
$themeResult = $db->Execute($themeSQL);
if (@$themeResult && $themeResult->RecordCount()) {
	$theme = $themeResult->FetchRow();
	$themeName = $theme['theme_name'];
}

$userRole = [];
$rolesSQL = "SELECT permission FROM care_user_roles WHERE role_id = '$roleId'";
$rolesResult = $db->Execute($rolesSQL);
if (@$rolesResult && $rolesResult->RecordCount()) {
	$userRole = $rolesResult->FetchRow();
}

$userPermissions=isset($userPermissions) ? $userPermissions : '';

$userPermissions = explode(" ", $userRole['permission']);

$userPermissions = str_replace('_a_1_', '', $userPermissions);
$userPermissions = str_replace('_a_2_', '', $userPermissions);
$userPermissions = str_replace('_a_3_', '', $userPermissions);
$userPermissions = str_replace('_a_4_', '', $userPermissions);

$showFinancialReport = false;
$showAuditorCorner = false;

$showMealsTab = false;
$showMealsReport = false;
$showLabReport = false;
$showctcReport = false;
$showPharmacyReport = false;
$showClinicalReport = false;
$showSystemReport = false;
$showProductCatalog = false;

foreach ($userPermissions as $permission) {

	if ($permission == "financialreportingread" || $permission == "allreportingread") {
		$showFinancialReport = true;
	}

	if ($permission == "auditorcorner" || $permission == "allreportingread") {
		$showFinancialReport = true;
		$showAuditorCorner = true;
	}

	if ($permission == 'meals') {
		$showMealsTab = true;
	}

	if ($permission == 'mealsreport') {
		$showMealsReport = true;
	}

	if ($permission == 'labreport') {
		$showLabReport = true;
	}

	if ($permission == 'ctcreport') {
		$showctcReport = true;
	}

	if ($permission == 'pharmacyreport') {
		$showPharmacyReport = true;
	}

	if ($permission == 'clinicreportingread') {
		$showClinicalReport = true;
	}

	if ($permission == 'systemreportingread') {
		$showSystemReport = true;
	}

	if ($permission == 'productcatalog') {
		$showProductCatalog = true;
	}

}

if ($userPermissions[0] == "System_Admin" || $userPermissions[0] == "_a_0_all " || $userPermissions[0] == "_a_0_all") {
	$showFinancialReport = true;
	$showAuditorCorner = true;
	$showMealsTab = true;
	$showMealsReport = true;
	$showLabReport = true;
	$showctcReport = true;
	$showPharmacyReport = true;
	$showClinicalReport = true;
	$showSystemReport = true;
	$showProductCatalog = true;
}

?>
