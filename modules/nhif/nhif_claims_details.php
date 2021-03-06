<?php



require './roots.php';
require $root_path . 'include/inc_environment_global.php';

/**
 * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
 * GNU General Public License
 * Copyright 2005 Robert Meggle based on the development of Elpidio Latorilla (2002,2003,2004,2005)
 * elpidio@care2x.org, meggle@merotech.de
 *
 * See the file "copy_notice.txt" for the licence notice
 */
require_once $root_path . 'include/care_api_classes/class_encounter.php';
require_once $root_path . 'include/care_api_classes/class_tz_billing.php';
require_once $root_path . 'include/care_api_classes/class_nhif_claims.php';
require_once $root_path . 'tcpdf/tcpdf.php';
require_once $root_path . 'tcpdf/tcpdf_autoconfig.php';
//require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
//$insurance_tz = New Insurance_tz;



//add page
//$pdf->AddPage();
$enc_obj = new Encounter;
$claims_obj = new Nhif_claims;

global $db;

require_once $root_path . 'include/care_api_classes/class_tz_drugsandservices.php';
$drg_obj = new DrugsAndServices;

$in_outpatient = $_REQUEST['patient'];
$encounter_nr = $_REQUEST['encounter_nr'];
$page_action = $_REQUEST['page_action'];
$date_from = $_REQUEST['date_from'];
$date_to = $_REQUEST['date_to'];

define('LANG_FILE', 'nhif.php');
require $root_path . 'include/inc_front_chain_lang.php';

require_once $root_path . 'vendor/autoload.php';
require_once $root_path . 'generated-conf/config.php';
$companyName = "";
$companyAddress = "";

$companySQL = "SELECT value FROM care_config_global WHERE type = 'main_info_name'";
$companyResult = $db->Execute($companySQL);
if (@$companyResult) {
	$company = $companyResult->FetchRow();
	$companyName = $company['value'];
}

$companySQL = "SELECT value FROM care_config_global WHERE type = 'main_info_address'";
$companyResult = $db->Execute($companySQL);
if (@$companyResult) {
	$company = $companyResult->FetchRow();
	$companyAddress = $company['value'];
}

require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';

require 'gui/gui_nhif_claims_details.php';

require_once $root_path . 'main_theme/footer.inc.php';

?>
