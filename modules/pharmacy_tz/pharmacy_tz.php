<?php
//error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require './roots.php';
$pageName = "Pharmacy";

require $root_path . 'include/inc_environment_global.php';
/**
 * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
 * GNU General Public License
 * Copyright 2005 Robert Meggle based on the development of Elpidio Latorilla (2002,2003,2004,2005)
 * elpidio@care2x.org, meggle@merotech.de
 *
 * See the file "copy_notice.txt" for the licence notice
 */
$lang_tables[] = 'pharmacy.php';
//define('NO_2LEVEL_CHK',1);
require $root_path . 'include/inc_front_chain_lang.php';

//$_SESSION['substore'] = $store_id; //try
$subStore = @$_GET['substore'] ? $_GET['substore'] : "";
$sql_locstock = "SELECT locationname,loccode
 FROM locations
 WHERE loccode='" . $subStore . "'";
$locstock_result = $db->Execute($sql_locstock);
if ($locstock_row = $locstock_result->FetchRow()) {
	$locname = $locstock_row['locationname'];
	$loccode = $locstock_row['loccode'];
}
require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';

require "gui/gui_pharmacy_tz.php";
?>
<?php require_once $root_path . 'main_theme/footer.inc.php';?>
