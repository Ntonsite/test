<?php

require './roots.php';
require $root_path . 'include/inc_environment_global.php';
require $root_path . 'include/inc_front_chain_lang.php';
require $root_path . 'include/inc_date_format_functions.php';
require_once $root_path . 'include/care_api_classes/class_tz_arv_report_cohort.php';
//------------------------------------------------------------------------------------------------------
$debug = false;
$curr_month = date("m", time());
$curr_year = date("Y", time());

$month = @($_GET['month']) ? $_GET['month'] : date("m");
$year = @($_GET['year']) ? $_GET['year'] : date("Y");

$cohort_rpt = new Cohort_report($month, $year, 6);
$arr_facility = $cohort_rpt->getFacilityInfo();

if (@$printout) {
//    $cohort_rpt->calc_timeframe();
	$arr_coh = $cohort_rpt->display_2yrs_cohort_report();
} else if (isset($_GET['submit'])) {
	#Please select Timeframe
	$start_month_timestamp = mktime(0, 0, 0, $_GET['month'], 1, $_GET['year']);
	$end_month_timestamp = mktime(0, 0, 0, $_GET['month'] + 6, 1, $_GET['year']) - 86400; //86400 are seconds in a day
	$arr_coh = $cohort_rpt->display_2yrs_cohort_report();
//    print_r($arr_coh['periods']);
}

//------------------------------------------------------------------------------------------------------

require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';

require "gui/gui_arv_reporting_cohort_2yrs.php";

require_once $root_path . 'main_theme/footer.inc.php';

?>
