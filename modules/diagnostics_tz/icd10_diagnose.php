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
//define('NO_2LEVEL_CHK',1);
//require($root_path.'include/inc_front_chain_lang.php');

$lang_tables[] = 'diagnoses_ICD10.php';
require $root_path . 'include/inc_front_chain_lang.php';

//Load the diagnstics-class:
require_once $root_path . 'include/care_api_classes/class_tz_diagnostics.php';

$diagnostic_obj = new Diagnostics;
/*

print_r ( $_SESSION );
echo "<br>";
echo "das will ich sehen: ".$_SESSION['sess_full_en'];
 */

foreach ($_POST as $postKey => $post) {
	$_POST[$postKey] = addslashes($post);
}

if ($todo == 'submit') {
	// Load the visual signalling functions
	include_once $root_path . 'include/inc_visual_signalling_fx.php';
	// Set the visual signal
	setEventSignalColor($_SESSION['sess_en'], SIGNAL_COLOR_QUERY_DOCTOR);

	$diagnostic_obj->EnterNewCase($_POST);
}
require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';

require "gui/gui_icd10_diagnose.php";

require_once $root_path . 'main_theme/footer.inc.php';

?>