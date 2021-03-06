<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');

$lang_tables[] = 'date_time.php';
$lang_tables[] = 'reporting.php';
require($root_path . 'include/inc_front_chain_lang.php');
#Load and create paginator object
require_once($root_path . 'include/care_api_classes/class_tz_selianreporting.php');

$pageName = "Reporting";

/**
 * getting summary of OPD...
 */
$rep_obj = new selianreport();

require_once('include/inc_timeframe.php');
/**
 * Getting the timeframe...
 */
$debug = false;
$PRINTOUT = FALSE;
if (empty($_GET['printout'])) {
    if (empty($_POST['month']) && empty($_POST['year'])) {
        if ($debug)
            echo "no time value is set, we're using now the current month<br>";
        $month = date("n", time());
        $year = date("Y", time());
        $start_timeframe = mktime(0, 0, 0, $month, 1, $year);
        $end_timeframe = mktime(0, 0, 0, $month + 1, 0, $year); // Last day of requested month
    } else { // month and year are given...
        if ($debug)
            echo "Getting an new time range...<br>";
        $start_timeframe = mktime(0, 0, 0, $_POST['month'], 1, $_POST['year']);
        $end_timeframe = mktime(0, 0, 0, $_POST['month'] + 1, 0, $_POST['year']);
    } // end of if (empty($_POST['month']) && empty($_POST['year']))
} else {
    $PRINTOUT = TRUE;
} // end of if (empty($_GET['printout']))


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

$showFinancialReport = false;

foreach ($userPermissions as $permission) {

    if ($permission == "financialreportingread" || $permission == "allreportingread" ) {
        $showFinancialReport = true;
    }
}


if ($userPermissions[0] == "System_Admin" || $userPermissions[0] == "_a_0_all " || $userPermissions[0] == "_a_0_all")  {
    $showFinancialReport = true;
}


require_once('gui/gui_tracking_summary.php');

require_once($root_path . 'main_theme/footer.inc.php');

?>