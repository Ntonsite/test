<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
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
define('LANG_FILE', 'billing.php');
require($root_path . 'include/inc_front_chain_lang.php');
require($root_path . 'language/en/lang_en_date_time.php');
require($root_path . 'language/en/lang_en_billing.php');
require($root_path . 'include/inc_date_format_functions.php');

$txtsearch = $_REQUEST['txtsearch'];

require_once($root_path . 'include/care_api_classes/class_encounter.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
$billing_tz = new Bill();
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
$insurance_tz = new Insurance_tz();
require_once($root_path . 'include/care_api_classes/class_tz_insurance_reports.php');
$insurance_tz_report = new Insurance_Reports_tz();


require_once('include/inc_timeframe.php');
/**
 * Getting the timeframe...
 */
if (empty($print)) {
    if ($debug)
        echo "no time value is set, we're using now the current month<br>";
    $month = date("n", time());
    $year = date("Y", time());
    $start_timeframe = mktime(0, 0, 0, $month, 1, $year);
    $end_timeframe = mktime(0, 0, 0, $month + 1, 0, $year); // Last day of requested month
}
else {

    if ($debug)
        echo "Getting an new time range...<br>";
    $start_timeframe = mktime(0, 0, 0, $_REQUEST['month'], 1, $_REQUEST['year']);
    $end_timeframe = mktime(0, 0, 0, $_REQUEST['month'] + 1, 0, $_REQUEST['year']);
}
$enc_obj = new Encounter;
$bill_obj = new Bill;
$insurance_tz = new Insurance_tz;
$debug = false;

$glob_obj = new GlobalConfig($GLOBAL_CONFIG);
$glob_obj->getConfig('main_info%');

$view = "month";

($debug) ? $db->debug = TRUE : $db->debug = FALSE;

// require($root_path.'modules/registration_admission/aufnahme_daten_such.php');

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


require ("gui/gui_billing_tz_archive.php");

    require_once($root_path . 'main_theme/footer.inc.php');

?>
