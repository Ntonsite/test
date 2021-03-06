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

//column = nhif_approved
$encounter_nr = $_REQUEST['encounter_nr'];
$target = $_REQUEST['target'];

//echo $encounter_nr;



if ($target == 'approve') {	

$sqlUpdateEncounter = "UPDATE care_encounter SET nhif_approved='1' WHERE encounter_nr='".$encounter_nr."'";
$db->Execute($sqlUpdateEncounter);
}else{
	$sqlUpdateEncounter = "UPDATE care_encounter SET nhif_approved='0' WHERE encounter_nr='".$encounter_nr."'";
$db->Execute($sqlUpdateEncounter);
}


header('Location: ' . $_SERVER["HTTP_REFERER"] );
exit;











require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';


require_once $root_path . 'main_theme/footer.inc.php';

?>
