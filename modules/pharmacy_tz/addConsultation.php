<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');

require($root_path . 'include/inc_environment_global.php');
require($root_path . 'include/care_api_classes/class_tz_pharmacy.php');
define('NO_2LEVEL_CHK', 1);
require($root_path . 'include/inc_front_chain_lang.php');
$debug = FALSE;
// Endable db-debugging if variable debug is true
($debug) ? $db->debug = TRUE : $db->debug = FALSE;

$product_obj = new Product();
$product_obj->usePriceDescriptionTable();

$_COOKIE['PageName'] = 'AddConsultation';

require_once($root_path . 'main_theme/head.inc.php');
require_once($root_path . 'main_theme/header.inc.php');
require_once($root_path . 'main_theme/topHeader.inc.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Consultation</title>

</head>
<body>
	<b>Work in progress</b>
	<?php 
	//$qualifications=$product_obj->doctorQualification();


	?>
	
	<?php


	?>
	


</body>
</html>

	



