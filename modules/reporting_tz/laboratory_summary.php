<?php
require './roots.php';
require $root_path . 'include/inc_environment_global.php';
$lang_tables[] = 'date_time.php';
$lang_tables[] = 'reporting.php';
require $root_path . 'include/inc_front_chain_lang.php';

#Load and create paginator object
require_once $root_path . 'include/care_api_classes/class_tz_reporting.php';
require_once $root_path . 'include/care_api_classes/class_tz_insurance.php';
$pageName = "Reporting";
/**
 * getting summary of OPD...
 */
$rep_obj = new selianreport();
$insurance_obj = new Insurance_tz;

require_once 'include/inc_timeframe.php';
/**
 * Getting the timeframe...
 */
$debug = FALSE;
$PRINTOUT = FALSE;
$category = 'drug_list';
if (empty($_GET['printout'])) {
	if (empty($_POST['month']) && empty($_POST['year'])) {
		if ($debug) {
			echo "no time value is set, we�re using now the current month<br>";
		}

		$month = date("n", time());
		$year = date("Y", time());
		$start_timeframe = mktime(0, 0, 0, $month, 1, $year);
		$end_timeframe = mktime(0, 0, 0, $month + 1, 0, $year); // Last day of requested month
		$admission = "0";
	} else {
		// month and year are given...
		if ($debug) {
			echo "Getting an new time range...<br>";
		}
//        echo $_POST['month'] . ' ' . $_POST['year'];
		$start_timeframe = mktime(0, 0, 0, $_POST['month'], 1, $_POST['year']);
//        echo $start_timeframe;
		$end_timeframe = mktime(0, 0, 0, $_POST['month'] + 1, 0, $_POST['year']);
		$admission = $_POST['admission_id'];
	} // end of if (empty($_POST['month']) && empty($_POST['year']))
} else {
	$PRINTOUT = TRUE;
} // end of if (empty($_GET['printout']))

require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';

require_once 'gui/gui_laboratory_summary.php';

require_once $root_path . 'main_theme/footer.inc.php';

?>
