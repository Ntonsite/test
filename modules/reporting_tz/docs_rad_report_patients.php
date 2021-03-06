<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
//require($root_path . 'include/inc_front_chain_lang.php');
//require($root_path . 'language/en/lang_en_reporting.php');
require($root_path . 'language/en/lang_en_date_time.php');
require($root_path . 'include/inc_date_format_functions.php');

#Load and create paginator object
require_once($root_path . 'include/care_api_classes/class_tz_reporting.php');
/**
 * getting summary of OPD...
 */
$rep_obj = new selianreport();

$lang_tables[] = 'date_time.php';
$lang_tables[] = 'reporting.php';
require($root_path . 'include/inc_front_chain_lang.php');
require_once('include/inc_timeframe.php');
$month = array_search(1, $ARR_SELECT_MONTH);
$year = array_search(1, $ARR_SELECT_YEAR);

if ($printout) {
   $doctor=$_GET['doctor'];
   $start=$_GET['start'];
   $end=$_GET['end'];
   $currentDeptWard=$_GET['currentDeptWard'];
   $insurance_ID=$_GET['insurance_ID'];
} else {
    $startdate = $_GET['start'];
    $enddate = $_GET['end'];

//    $startdate = @formatDate2STD($_POST['date_from'], "dd/mm/yyyy");
//    (!isset($_POST['date_to']) || $_POST['date_to'] == '') ? $enddate = @formatDate2STD(date('Y-m-d'), "yyyy-mm-dd") : $enddate = @formatDate2STD($_POST['date_to'], "dd/mm/yyyy");
}

$debug = FALSE;
($debug) ? $db->debug = TRUE : $db->debug = FALSE;

$startdate=$_GET['start'];
$enddate=$_GET['end'];
$dr=$_GET['doctor'];
$currentDeptWard=$_GET['currentDeptWard'];
$insurance_ID=$_GET['insurance_ID'];






require_once('gui/gui_docs_rad_patients.php');
?>
