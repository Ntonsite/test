<?php

require('./roots.php');

require($root_path . 'include/inc_environment_global.php');
$pageName = "Billing";

/**
 * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
 * GNU General Public License
 * Copyright 2005 Robert Meggle based on the development of Elpidio Latorilla (2002,2003,2004,2005)
 * elpidio@care2x.org, meggle@merotech.de
 *
 * See the file "copy_notice.txt" for the licence notice
 */
//define('NO_2LEVEL_CHK',1);
define('LANG_FILE', 'billing.php');
require($root_path . 'include/inc_front_chain_lang.php');
//require($root_path.'include/inc_page_functions.php');
//$page_funct= new page_funct();
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
$bill_obj = new Bill();
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
//$insurance_tz = new Insurance_tz();
//require_once($root_path.'include/care_api_classes/class_tz_insurance_reports.php');
//$insurance_tz_report = new Insurance_Reports_tz();
require_once($root_path . 'main_theme/head.inc.php');
require_once($root_path . 'main_theme/header.inc.php');
require_once($root_path . 'main_theme/topHeader.inc.php');


require('./roots.php');
require_once $root_path.'vendor/autoload.php';
require_once $root_path.'generated-conf/config.php';

use  CareMd\CareMd\CareUsersQuery;
use  CareMd\CareMd\CareUserRolesQuery;

$userId = $_SESSION['sess_login_userid'];

$user = CareUsersQuery::create()->filterByLoginId($userId)->findOne()->toArray();
$roleId = $user['RoleId'];
// $roleId = 12;

$userRole = CareUserRolesQuery::create()->filterByRoleId($roleId)->findOne()->toArray();
$themeName = $user['ThemeName'];

$userPermissions = explode(" ", $userRole['Permission']);


$userPermissions = str_replace('_a_1_', '', $userPermissions);
$userPermissions = str_replace('_a_2_', '', $userPermissions);
$userPermissions = str_replace('_a_3_', '', $userPermissions);
$userPermissions = str_replace('_a_4_', '', $userPermissions);

$createOutpatientQuotation = false;
$createInpatientQuotation = false;
$createARTQuotation = false;
$billingReport = false;
$nhifClaimsPermission = false;

//nhif_claims

foreach ($userPermissions as $permission) {

	if ($permission == "billquotationsoutp") {
		$createOutpatientQuotation = true;
	}

	if ($permission == "billquotationsinp") {
		$createInpatientQuotation = true;
	}

	if ($permission == "billquotationsden") {
		$createARTQuotation = true;
	}

	if ($permission == "billreports") {
		$billingReport = true;
	}

	if ($permission == "billquotations") {
		$createOutpatientQuotation = true;
		$createInpatientQuotation = true;
		$createARTQuotation = true;
	}

	if ($permission == 'nhif_claims') {
		$nhifClaimsPermission = true;
	}

	if ($permission == "billallwrite" || $permission == "billallread" ) {
		$createOutpatientQuotation = true;
		$createInpatientQuotation = true;
		$createARTQuotation = true;
		$billingReport = true;
	}
}




if ($userPermissions[0] == "System_Admin" || $userPermissions[0] == "_a_0_all " || $userPermissions[0] == "_a_0_all")  {
	$createOutpatientQuotation = true;
	$createInpatientQuotation = true;
	$createARTQuotation = true;
	$billingReport = true;
	$nhifClaimsPermission = true;
}

require ("gui/gui_billing_tz.php");

require_once($root_path . 'main_theme/footer.inc.php');


?>
