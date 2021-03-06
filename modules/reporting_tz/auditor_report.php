<?php

require './roots.php';
require $root_path . 'include/inc_environment_global.php';

define('LANG_FILE', 'reporting.php');
require $root_path . 'include/inc_front_chain_lang.php';

define('NO_CHAIN', 1);
require_once $root_path . 'include/inc_front_chain_lang.php';
$_COOKIE['report_sub'] = "Auditor Report";

require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';

require_once 'gui/gui_auditor_dashboard.php';

require_once $root_path . 'main_theme/footer.inc.php';
require_once $root_path . 'js/care_md/auditor_corner_chart_js.php';

?>
<script src="<?php echo $root_path ?>/js/care_md/bootstrap-select.min.js"></script>
