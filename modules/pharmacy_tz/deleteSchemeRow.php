<?php

require_once('./roots.php');
include_once($root_path . 'include/inc_environment_global.php');
global $db;

$scheme_id = @($_GET['scheme_id'])?$_GET['scheme_id']:0;
$sql = "DELETE FROM care_tz_drugsandservices_nhifschemes WHERE id='$scheme_id'";
$db->Execute($sql);