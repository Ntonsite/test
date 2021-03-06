<?php

//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
//error_reporting(0);
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
require_once($root_path . 'include/care_api_classes/class_encounter.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
$insurance_tz = New Insurance_tz;
$enc_obj = new Encounter;
$bill_obj = new Bill;

require_once($root_path . 'include/care_api_classes/class_tz_drugsandservices.php');
$drg_obj = new DrugsAndServices;

$in_outpatient = @$_REQUEST['patient']?$_REQUEST['patient']:"";




define('LANG_FILE', 'billing.php');
require($root_path . 'include/inc_front_chain_lang.php');

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
// $roleId = 13;

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
}



require ('gui/gui_billing_tz_quotation.php');
require_once($root_path . 'main_theme/footer.inc.php');

?>
