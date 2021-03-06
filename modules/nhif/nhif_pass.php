<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
/**
 * CARE2X Integrated Hospital Information System beta 2.0.1 - 2004-07-04
 * GNU General Public License
 * Copyright 2002,2003,2004,2005 Elpidio Latorilla
 * elpidio@care2x.org, 
 *
 * See the file "copy_notice.txt" for the licence notice
 */
$lang_tables = array('nhif.php', 'actions.php');
define('LANG_FILE', 'stdpass.php');
define('NO_2LEVEL_CHK', 1);
require_once($root_path . 'include/inc_front_chain_lang.php');

require_once($root_path . 'global_conf/areas_allow.php');

//$allowedarea = &$allow_area['bill'];
$append = URL_REDIRECT_APPEND;

if (!isset($target) || $target == '') {
    $target = 'read';
}



$patient = $_REQUEST['patient'];
$encounter_nr = $_REQUEST['encounter_nr'];
$page_action = $_REQUEST['page_action'];
$date_from = $_REQUEST['date_from'];
$date_to = $_REQUEST['date_to'];

if (empty($date_from) || empty($date_to)) {
    $date_from = date('Y-m-01');
    $date_to = date('Y-m-t');
    $date_from = date('d/m/Y', strtotime(str_replace('-', '/', $date_from)));
    $date_to = date('d/m/Y', strtotime(str_replace('-', '/', $date_to)));    
    
}else{
    $date_from = $date_from;
    $date_to   = $date_to; 
}



//print_r($_REQUEST);die;
$sorttype = isset($_REQUEST['sorttyp'])? $_REQUEST['sorttyp'] : '';
$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : '';









//      echo "<pre>"; print_r($target);echo "</pre>";
// die();
switch ($target) {
    case 'review':
        $allowedarea = $allow_area['nhif_read'];
        $fileforward = "nhif_claims_review.php" . $append . "&sid=$sid&target=review&lang=$lang&patient=$patient&date_from=$date_from&date_to=$date_to";
        $lognow = 'NHIF login ok';
        break;
    case 'BeforeBill':
        $allowedarea = $allow_area['nhif_read'];
        $fileforward = "nhif_claims_review_Before_Bill.php" . $append . "&sid=$sid&target=BeforeBill&lang=$lang&patient=$patient&date_from=$date_from&date_to=$date_to";
        $lognow = 'NHIF login ok';
        break;    
    case 'read':
        $allowedarea = $allow_area['nhif_read'];
        $fileforward = "nhif_claims.php" . $append . "&sid=$sid&target=read&lang=$lang";
        $lognow = 'NHIF login ok';
        break;
    case 'send':
        $allowedarea = $allow_area['nhif_read'];
        $fileforward = "nhif_submit_claims.php" . $append . "&sid=$sid&target=send&lang=$lang&patient=$patient&encounter_nr=$encounter_nr&date_from=$date_from&date_to=$date_to";
        $lognow = 'NHIF login ok';
        break;
    case 'claimsdetails':
        $allowedarea = $allow_area['nhif_read'];
        $fileforward = "nhif_claims_details.php" . $append . "&sid=$sid&target=claimsdetails&patient=$patient&lang=$lang&encounter_nr=$encounter_nr&page_action=$page_action&date_from=$date_from&date_to=$date_to";
        $lognow = 'NHIF login ok';
        break;
    case 'claimsreview':
        $allowedarea = $allow_area['nhif_read'];
        $fileforward = "nhif_final_review.php" . $append . "&sid=$sid&target=finalreview&patient=$patient&lang=$lang&encounter_nr=$encounter_nr&page_action=$page_action&date_from=$date_from&date_to=$date_to";
        $lognow = 'NHIF login ok';
        break;    
     case 'report':
        $allowedarea = $allow_area['nhif_read'];
        $fileforward = "nhif_claims_report.php" . $append . "&sid=$sid&target=claimsdetails&patient=$patient&lang=$lang&encounter_nr=$encounter_nr&page_action=$page_action&date_from=$date_from&date_to=$date_to";
        $lognow = 'NHIF login ok';
        break;

    default:
        $allowedarea = $allow_area['nhif_read'];
        $fileforward = 'nhif_claims.php' . $append;
        $lognow = 'NHIF login ok';
        break;
}

$thisfile = basename($_SERVER['PHP_SELF']);

$userck = 'aufnahme_user';
//reset cookie;
// reset all 2nd level lock cookies
setcookie($userck . $sid, '', 0, '/');
require($root_path . 'include/inc_2level_reset.php');
setcookie(ck_2level_sid . $sid, '', 0, '/');

require($root_path . 'include/inc_passcheck_internchk.php');
if ($pass == 'check')
    include($root_path . 'include/inc_passcheck.php');

$errbuf = $LDAdmission;

require($root_path . 'include/inc_passcheck_head.php');
?>

<BODY  onLoad="document.passwindow.userid.focus();" bgcolor=<?php echo $cfg['body_bgcolor']; ?>
<?php
if (!$cfg['dhtml']) {
    echo ' link=' . $cfg['idx_txtcolor'] . ' alink=' . $cfg['body_alink'] . ' vlink=' . $cfg['idx_txtcolor'];
}
?>>

    <FONT    SIZE=-1  FACE="Arial">

    <P>
        <?php
        $buf = $LDNhif . ' :: ' . $LDLogin;
        echo '
<script language=javascript>
<!--
 if (window.screen.width) 
 { if((window.screen.width)>1000) document.write(\'<img ' . createComIcon($root_path, 'smiley.gif', '0', 'top') . '><FONT  COLOR="' . $cfg['top_txtcolor'] . '"  SIZE=6  FACE="verdana"> <b>' . $buf . '</b></font>\');}
 //-->
 </script>';
        ?>


    <table width=100% border=0 cellpadding="0" cellspacing="0"> 
        <?php
        $maskBorderColor = '#66ee66';
        require($root_path . 'include/inc_passcheck_mask.php')
        ?>  

        <p>
            <?php
            require($root_path . 'include/inc_load_copyrite.php');
            ?>
            </FONT>
            </BODY>
            </HTML>
