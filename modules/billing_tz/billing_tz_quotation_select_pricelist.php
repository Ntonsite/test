<?php

require './roots.php';
require $root_path . 'include/inc_environment_global.php';

$lang_tables[] = 'billing.php';
$lang_tables[] = 'aufnahme.php';
require $root_path . 'include/inc_front_chain_lang.php';
require_once $root_path . 'include/care_api_classes/class_tz_billing.php';
$billing_tz = new Bill();
require_once $root_path . 'include/care_api_classes/class_tz_insurance.php';
$insurance_tz = new Insurance_tz();
require_once $root_path . 'include/care_api_classes/class_tz_insurance_reports.php';
$insurance_tz_report = new Insurance_Reports_tz();

$enc_obj = new Encounter;
$bill_obj = new Bill;
require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';

include 'gui/gui_billing_tz_select_pricelist.php';
require_once $root_path . 'main_theme/footer.inc.php';

?>
