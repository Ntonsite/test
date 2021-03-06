<?php
require './roots.php';
require $root_path . 'include/inc_environment_global.php';
require_once $root_path . 'include/care_api_classes/class_globalconfig.php';
$GLOBAL_CONFIG = $GLOBAL_CONFIG ?? [];

/**
 * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
 * GNU General Public License
 * Copyright 2002,2003,2004,2005 Elpidio Latorilla
 * elpidio@care2x.org,
 *
 * See the file "copy_notice.txt" for the licence notice
 */
$lang_tables[] = 'prompt.php';
$lang_tables[] = 'departments.php';
define('LANG_FILE', 'nursing.php');
$local_user = 'ck_pflege_user';
require_once $root_path . 'include/inc_front_chain_lang.php';

require_once $root_path . 'include/care_api_classes/class_department.php';

require_once $root_path . 'include/care_api_classes/class_helper.php';
$helper = new Helper();

$_SESSION['backToPatientList'] = TRUE;

## Load all wards info
$dept_obj = new Department;
$allmed = $dept_obj->getAllMedical();
$dept_count = $dept_obj->LastRecordCount();
$glob_obj = new GlobalConfig($GLOBAL_CONFIG);



# Start Smarty templating here
/**
 * LOAD Smarty
 */
# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once $root_path . 'gui/smarty_template/smarty_care.class.php';
$smarty = new smarty_care('nursing');

# Title in toolbar
$smarty->assign('sToolbarTitle', $LDTransferPatient);

# hide back button
$smarty->assign('pbBack', FALSE);

# href for help button
$smarty->assign('pbHelp', "javascript:gethelp('outpatient_transfer.php','Outpatient Clinic :: Transfer')");

# href for close button
$smarty->assign('breakfile', "javascript:history.back();");

# OnLoad Javascript code
$smarty->assign('sOnLoadJs', 'onLoad="if (window.focus) window.focus();"');

# Window bar title
$smarty->assign('sWindowTitle', $LDTransferPatient);

# Hide Copyright footer
$smarty->assign('bHideCopyright', TRUE);

# Collect extra javascript code

ob_start();
?>
<?php
//echo 'pw: '.$_GET['dept_nr'];
//echo 'pat_station:'.$_GET['pat_station'];
//echo 'pn: '.$_GET['pn'];
//echo 'patnr: '.$_GET['patnr']; $patnr = $_GET['patnr'];
?>


<script language="javascript">
<!--
    var urlholder;

    function TransferDept(dn) {
<?php
$pyear = $pyear ?? date("Y");
$pmonth = $pmonth ?? date("m");
$pday = $pday ?? date("d");
$dept_nr = $dept_nr ?? "";
$station = $station ?? "";
$station = $station ?? "";
echo '
urlholder="amb_clinic_transfer_save.php?mode=transferdept&sid=' . $sid . '&lang=' . $lang . '&pyear=' . $pyear . '&pmonth=' . $pmonth . '&pday=' . $pday . '&pn=' . $pn . '&station=' . $station . '&dept_nr=' . $dept_nr . '&tgt_nr="+dn
';
?>
        window.location.replace(urlholder);
        window.close();
    }

// -->
</script>

<STYLE type=text/css>
    div.box { border: double; border-width: thin; width: 100%; border-color: black; }

    .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
      background-color: #CCCCCC;
    }
</style>

<?php
$sTemp = ob_get_contents();

ob_end_clean();

$smarty->append('JavaScript', $sTemp);

# Buffer page output

ob_start();
?>

<table border=0>
    <tr>
        <td><img <?php echo createMascot($root_path, 'mascot2_r.gif', '0'); ?>></td>
        <td><FONT class="warnprompt"><?php echo $LDWhereToTransfer; ?></td>
    </tr>
</table>

<table border=0 cellpadding=2 cellspacing=1 width=100% class="table table-condensed table-hover table-stripped table-bordered">
    <tr bgcolor="#f6f6f6">
        <td colspan=2>&nbsp;<FONT class="prompt"><?php echo $LDTransferClinic; ?></td>
    </tr>

<?php
# Generate the rows of departments and transfer links
# Note: the $allmed is an array

while (list($x, $v) = $helper->CustomEach($allmed)) {
	if ($v['nr'] == $dept_nr) {
		continue;
	}

	echo '<tr bgcolor="#f6f6f6"><td>&nbsp;';
	if (isset($v['LD_var']) && !empty($v['LD_var'])) {
		echo $v['LD_var'];
	} else {
		echo $v['name_formal'];
	}

	echo '
	 </td>
	 <td><a href="javascript:TransferDept(\'' . $v['nr'] . '\')"><img ' . createLDImgSrc($root_path, 'transfer_sm.gif', '0') . ' alt="' . $LDClkTransfer . '"></a></td>
	 </tr>';
}
?>

<?php
$hide = false;
$sqlRestriction = "SELECT type,value FROM care_config_global WHERE type='restrict_transfer_nhif_patients'";
$resultRestriction = $db->Execute($sqlRestriction);

if (@$resultRestriction && $resultRestriction->RecordCount()>0 ) {
    if ($rowRestriction = $resultRestriction->FetchRow() ) {
         if ($rowRestriction['value'] == 1 && stripos($_GET['insurance_name'], 'NHIF')!==false) {
            $hide = true;
             
         }else{
            $hide = false;
         }
     } 
    
}


?>

</table>
<br>


<table border=0 cellpadding=2 cellspacing=1 width=100%>

<?php if($hide == false):?>
    <tr bgcolor="#f6f6f6">
        <td colspan=2>&nbsp;<FONT class="prompt"><?php echo $LDTransferWard; ?></td>
    </tr>

<?php endif ?>


<?php
//	require_once($root_path.'include/care_api_classes/class_ward.php');
//	## Load all wards info
//	$ward_obj=new Ward;
//	$items='nr,ward_id,name';
//	$ward_info=&$ward_obj->getAllWardsItemsObject($items);
//	$ward_count=$ward_obj->LastRecordCount();
//
//	while($ward=$ward_info->FetchRow()){
//	if($ward['nr']==$ward_nr) continue;
//	echo '<tr bgcolor="#f6f6f6"><td>'.$ward['ward_id'].'</td>
//	 <td>'.$ward['name'].'</td>
//	 <td><a href="javascript:TransferWard(\''.$ward['nr'].'\')"><img '.createLDImgSrc($root_path,'transfer_sm.gif','0').'></a></td></tr>';
//}
//
//
//
?>

    <?php
$station = "'.$root_path.'modules/registration_admission/aufnahme_start.php?sid='.$sid.'&ntid=false&lang='.$lang.'&pid=10000058&origin=patreg_reg&encounter_class_nr=1";

$pathInpatient = $root_path . 'modules/registration_admission/aufnahme_start.php?sid=' . $sid . '&ntid=false&lang=' . $lang . '&pid=' . $patnr . '&origin=patreg_reg&encounter_class_nr=1&transFromOutp=yes&pn=' . $pn;
?>




    <?php if($hide == false):?>

    <tr><td><a href="<?php echo $root_path . 'modules/registration_admission/aufnahme_start.php?sid=' . $sid . '&ntid=false&lang=' . $lang . '&pid=' . $patnr . '&origin=patreg_reg&encounter_class_nr=1&transFromOutp=yes&pn=' . $pn ?>">click here</a></td></tr>

<?php endif?>


</table>
</form>



<?php
$sTemp = ob_get_contents();
ob_end_clean();

# Assign the page output to the mainframe center block

$smarty->assign('sMainFrameBlockData', $sTemp);

/**
 * show Template
 */

require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';

$smarty->display('common/mainframe.tpl');
?>
<?php require_once $root_path . 'main_theme/footer.inc.php';?>
