<?php
if (!isset($GLOBAL_CONFIG)) {
	$GLOBAL_CONFIG = array();
}

include_once $root_path . 'include/care_api_classes/class_globalconfig.php';
include_once $root_path . 'language/en/lang_en_aufnahme.php';
$glob = new GlobalConfig($GLOBAL_CONFIG);
# Get all config items starting with "main_"
$glob->getConfig('main_%');



$addr[] = array($GLOBAL_CONFIG['main_info_address'],
	"$LDPhone:\n$LDFax:\n$LDEmail:",
	$GLOBAL_CONFIG['main_info_phone'] . "\n" . $GLOBAL_CONFIG['main_info_fax'] . "\n" . $GLOBAL_CONFIG['main_info_email'] . "\n",
);



$main_address = $GLOBAL_CONFIG['main_info_address'];

$addr_line = explode(",", $main_address);

function createDataBlock($param) {
	global $stored_findings, $edit;

	if ($edit) {
		echo '
    <textarea name="' . $param . '" cols=82 rows=10 wrap="physical">' . stripslashes($stored_findings[$param]) . '</textarea>';
	} else {
		echo '
                     <blockquote><font face="verdana,arial" color="#000000" size=5>' . nl2br(stripslashes($stored_findings[$param])) . '</font></blockquote>';
	}
}

function createInputBlock($param, $value) {
	global $stored_findings, $date_format, $edit, $lang;

	if ($edit) {
		/*
			      echo '&nbsp;<input type="text" name="'.$param.'"  value="'.$value.'" size=';
			      if ($param=='doctor_id') echo '35 maxlength=35>';
			      else echo '10 maxlength=10 onBlur="IsValidDate(this,\''.$date_format.'\')"   onKeyUp="setDate(this,\''.$date_format.'\',\''. $lang.'\')">';

		*/

		if ($param == 'doctor_id') {
			echo '&nbsp;<input type="text" name="' . $param . '" value="' . $value . '" size= 35 maxlength=35 >';
		} else {
			echo '&nbsp;<input type="text" name="' . $param . '" value="' . $value . '" size=10 maxlength=10  onBlur="IsValidDate(this,\'' . $date_format . '\')"   onKeyUp="setDate(this,\'' . $date_format . '\',\'' . $lang . '\')">';
		}

	} else {
		echo '&nbsp;
                     <font face="verdana,arial" color="#000000" size=2>' . $value . '</font><br>&nbsp;';
	}
}

$sql = "SELECT * FROM care_test_request_" . $db_request_table . " WHERE batch_nr='$batch_nr'";

$radtests = $db->Execute($sql);

$stored_tests = $radtests->FetchRow();

?>

<style type="text/css" name="1">
    .va12_n{font-family:verdana,arial; font-size:12; border: 1px solid; color:#000099 }
    .a10_b{font-family:arial; font-size:10; border: 1px solid; color:#000000}
    .a10_n{font-family:arial; font-size:10; border: 1px solid; color:#000099}
    .a12_b{font-family:arial; font-size:12; border: 1px solid; color:#000000}
    .j{font-family:verdana; font-size:12; border: 1px solid; color:#000000}
    .report_tbl {
        border: 1px solid;
        border-collapse: collapse;
        width: 730px;
        margin: auto;
    }
    .report_data{
        border: 1px solid;
    }

    .report_tbl_header{
        width: 725px;
        border: none;
    }
    .no_border_right{
        border: none;
        border-right: none;
    }
    .no_border_left{
        border: none;
        border-left: none;
    }
</style>
<table border=0 class="report_tbl" cellpadding=1 cellspacing=0 bgcolor="#000000">
    <tr>
        <td>


            <table border=0 class="report_tbl"  cellpadding=0 cellspacing=0 bgcolor="#ffffff">
                <tr>
                    <td>

                        <table border=0 class="report_tbl" cellpadding="0" cellspacing=1 >

                            <tr bgcolor="<?php echo $bgc1 ?>">
                                

                                        <?php
echo $addr_line[0] ?? "" . '<br>';
echo $addr_line[1] ?? "";
echo $addr_line[2] ?? "";
?>

                                

                               
                               

                              
                                       <?php

$sql_address="SELECT type,value FROM care_config_global WHERE type like 'main_%'";

$result_addr=$db->Execute($sql_address);










$cache='';

$logodir = 'gui/img/common/default/';
$logo = $root_path . $logodir . $hospital_logo;

$cache .= '<td width="auto" align="left" valign="top">';

$cache .= '<img src="' . $logo . '"  width="150">';
$cache .= '</td>';                                  

$cache .= '<p><td valign="top">';
while ($values=$result_addr->FetchRow()) {

    

    switch ($values['type']) {
        case 'main_info_facility_name':
            $main_facility_name=$values['value'];
            //$cache .= '<font size="4">'.$main_facility_name.'</font>'.'<br>';
            break;

        case 'main_info_address':
            $main_address=$values['value'];
            //$cache .= '<font size="4">'.$main_address.'</font>'.'<br>';
            break; 

        case 'main_info_phone':
            $main_phone=$values['value'];
            //$cache .= '<font size="4">'.$main_phone.'</font>'.'<br>';
            break; 

        case 'main_info_email':
            $main_email=$values['value'];
            //$cache .= '<font size="4">'.$main_phone.'</font>'.'<br>';
            break;           
        
        default:
            # code...
            break;
    }

    
 

}


$cache .= '<font size="4">'.$main_facility_name.'</font>'.'<br>';
$cache .= '<font size="4">'.$main_address.'</font>'.'<br>';
$cache .= '<font size="4">'.$main_email.'</font>'.'<br>';
$cache .= '<font size="4">Phone:'.$main_phone.'</font>';






$cache .= '</td>';                                  

                                


                                    
$cache .= '<td width="auto" align="right" class="no_border_left" rowspan="2">';
$cache .= '<img src="' . $root_path . 'main/imgcreator/barcode_label_single_large.php?sid=' . $sid . '&lang=' . $lang . '&fen=' . $full_en . '&en=' . $full_en . '" width=282 height=178">';
$cache .= '</td>';

echo $cache;
                                    ?>
                            


                            </tr>

                            <tr bgcolor="<?php echo $bgc1 ?>">

                                <td valign="top" colspan=3 align="center">
                                    <div class=fva0_ml10>

                                </td>
                            </tr>

                            <tr bgcolor="<?php echo $bgc1 ?>">

                                <td valign="top" colspan=3 align="center">
                                    <div class=fva0_ml10>

                                        <p>

                                </td>
                            </tr>


                            <tr bgcolor="<?php echo $bgc1 ?>">

                                <td valign="top" colspan=3 align="center">
                                    <div class=fva0_ml10>
                                        <font color="#000099">
                                            <font size=3 color="#000099" face="verdana,arial">
                                                <b><?php echo $formtitle ?></b></font><br>
                                            <hr />

                                </td>
                            </tr>

                            <tr bgcolor="<?php echo $bgc1 ?>">

                                <td valign="top" colspan=3 align="left">
                                    <div class=fva0_ml10>
                                        <font color="#000099" size=2 face="verdana,arial">

                                            <?php echo $LDRequestDate ?>
                                            <u><?php echo $stored_tests['send_date'] ?><p>

                                </td>
                            </tr>

                            <tr bgcolor="<?php echo $bgc1 ?>">

                                <td valign="top" colspan=3 align="left">
                                    <div class=fva0_ml10>
                                        <font color="#000099" size=2 face="verdana,arial">

                                            <?php echo $LDRequestingDoc ?>
                                            <u><?php echo $stored_tests['send_doctor'] ?><p>

                                </td>
                            </tr>

                            <tr bgcolor="<?php echo $bgc1 ?>">

                                <td valign="top" colspan=3 align="left">
                                    <div class=fva0_ml10>
                                        <font color="#000099" size=2 face="verdana,arial">

                                            <?php echo $LDClinicalSum ?>
                                            <u><?php echo $stored_tests['clinical_info'] ?><p>

                                </td>
                            </tr>

                            <tr bgcolor="<?php echo $bgc1 ?>">

                                <td valign="top" colspan=3 align="left">
                                    <div class=fva0_ml10>
                                        <font color="#000099" size=2 face="verdana,arial">

                                            <?php echo $LDDiagnosticTest ?>

                                            <?php
$sql = 'select item_description from care_tz_drugsandservices where item_id=' . $stored_tests['test_request'];
$requests = $db->Execute($sql);
if ($requests) {
	$test_request = $requests->FetchRow();
}

?>
                                            <u><?php echo $test_request[0] ?><p>

                                    </div>
                                </td>
                            </tr>

                            <tr bgcolor="<?php echo $bgc1 ?>">

                            <tr bgcolor="<?php echo $bgc1 ?>">
                                <td valign="top" colspan=3>
                                    <div class=fva0_ml10>
                                        <font color="#000099" size=2 face="verdana,arial">
                                            <?php echo $LDTestFindings ?><br>
                                            <?php createDataBlock('findings')?>
                                </td>
                            </tr>

                            <tr bgcolor="<?php echo $bgc1 ?>">
                                <td valign="top" colspan=3>
                                    <div class=fva0_ml10>
                                        <font color="#000099" size=2 face="verdana,arial">
                                            <?php echo $LDDiagnosis ?><br>
                                            <?php createDataBlock('diagnosis')?>
                                    </div>
                                </td>

                            </tr>

                            <tr bgcolor="<?php echo $bgc1 ?>">
                                <td>
                                    <div class=fva2_ml10>
                                        <font color="#000099">
                                            <?php echo $LDDate ?>:
                                            <?php
/*                 if($mode=="edit") $fdate=formatDate2Local($stored_findings['findings_date'],$date_format);
else $fdate=formatDate2Local(date("Y-m-d"),$date_format);
 */
/*
if($mode=='edit' && $stored_findings['findings_date']) $fdate=formatDate2Local($stored_findings['findings_date'],$date_format);
else $fdate=formatDate2Local(date('Y-m-d'),$date_format);
 */
if ($stored_findings['findings_date']) {
	$fdate = formatDate2Local($stored_findings['findings_date'], $date_format);
} else {
	$fdate = formatDate2Local(date('Y-m-d'), $date_format);
}

createInputBlock('findings_date', $fdate);

if ($edit) {
	?>
                                            <a
                                                href="javascript:show_calendar('form_test_request.findings_date','<?php echo $date_format ?>')">
                                                <img
                                                    <?php echo createComIcon($root_path, 'show-calendar.gif', '0', 'absmiddle'); ?>></a>
                                            <?php
}
?>
                                    </div>
                                </td>
                                <td align="right" colspan=3>
                                    <div class=fva2_ml10>
                                        <font color="#000099">



                                            <?php echo $LDReportingRad ?>:</font>
                                        <font color="#000000">

                                            <?php
if ($stored_findings['doctor_id']) {
	$doctor_id = $stored_findings['doctor_id'];
} else {
	$doctor_id = $_SESSION['sess_user_name'];
}

createInputBlock('doctor_id', $doctor_id);
?>

                                            <!-- <?php createInputBlock('doctor_id', $stored_findings['doctor_id']);?>  -->

                                            <!-- <input type="text" name="doctor_id" size=30 maxlength=30 value="<?php echo $_SESSION['sess_user_name']; ?>" readonly>  -->
                                            &nbsp;&nbsp;
                                    </div>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>