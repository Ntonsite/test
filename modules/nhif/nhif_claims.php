<?php
require './roots.php';

require $root_path . 'include/inc_environment_global.php';
require $root_path . 'language/en/lang_en_billing.php';
/**
 * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
 * GNU General Public License
 * Copyright 2005 Robert Meggle based on the development of Elpidio Latorilla (2002,2003,2004,2005)
 * elpidio@care2x.org, meggle@merotech.de
 *
 * See the file "copy_notice.txt" for the licence notice
 */
//define('NO_2LEVEL_CHK',1);
define('LANG_FILE', 'nhif.php');
require $root_path . 'include/inc_front_chain_lang.php';

//require($root_path.'include/inc_page_functions.php');
//$page_funct= new page_funct();
//require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
//$bill_obj = new Bill();
require_once $root_path . 'include/care_api_classes/class_tz_insurance.php';
//$insurance_tz = new Insurance_tz();
//require_once($root_path.'include/care_api_classes/class_tz_insurance_reports.php');
//$insurance_tz_report = new Insurance_Reports_tz();

require_once $root_path . 'include/care_api_classes/class_nhif_claims.php';

$claims_obj = new Nhif_claims;

require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';

require "gui/gui_nhif_claims.php";

require_once $root_path . 'main_theme/footer.inc.php';

?>
