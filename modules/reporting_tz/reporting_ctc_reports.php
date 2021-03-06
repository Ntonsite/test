<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');

$pageName = "Reporting";

define('LANG_FILE', 'reporting.php');
require($root_path . 'include/inc_front_chain_lang.php');

require_once($root_path . 'main_theme/head.inc.php');
require_once($root_path . 'main_theme/header.inc.php');
require_once($root_path . 'main_theme/topHeader.inc.php');

require_once('gui/gui_reporting_ctc_reports.php');

require_once($root_path . 'main_theme/footer.inc.php');

?>