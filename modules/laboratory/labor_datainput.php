<?php
define("COL_MAX", 6); # define here the maximum number of rows for displaying the parameters
require './roots.php';
require $root_path . 'include/inc_environment_global.php';
require_once $root_path . 'include/care_api_classes/class_helper.php';
$helper = new Helper();

require_once './roots.php';

require_once $root_path . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
 * GNU General Public License
 * Copyright 2002,2003,2004,2005 Elpidio Latorilla
 * elpidio@care2x.org,
 *
 * See the file "copy_notice.txt" for the licence notice
 */
$lang_tables = array('chemlab_groups.php', 'chemlab_params.php');
define('LANG_FILE', 'lab.php');
$local_user = 'ck_lab_user';
require_once $root_path . 'include/inc_front_chain_lang.php';

$debug = FALSE;
($debug) ? $db->debug = true : $db->debug = FALSE;

if (!$encounter_nr) {
	header('Location:' . $root_path . 'language/' . $lang . '/lang_' . $lang . '_invalid-access-warning.php');
	exit();
}

if (!isset($user_origin) || empty($user_origin)) {
	$user_origin = 'lab';
}

# Create encounter object
require_once $root_path . 'include/care_api_classes/class_encounter.php';
$encounter = new Encounter($encounter_nr);
$enc_obj = new Encounter($encounter_nr);
if ($encounter = $enc_obj->getBasic4Data($encounter_nr)) {
	$patient = $encounter->FetchRow();
}

$enc_obj->loadEncounterData($encounter_nr);
$pid = $enc_obj->PID();

if ($debug) {
	echo "Parameterselect is set to:" . $parameterselect . "<br>";
	echo "mode is set to:" . $mode . "<br>";
	echo "allow update is set to:" . $allow_update . "<br>";
	echo "job id is set to:" . $job_id . "<br>";
	echo "encounter number is: " . $encounter_nr . "<br>";
}

$thisfile = 'labor_datainput.php';

# Create lab object
require_once $root_path . 'include/care_api_classes/class_lab.php';
$lab_obj = new Lab($encounter_nr);
$lab_obj_sub = new Lab($encounter_nr, true);

//to avoid reinserting already done analysis
//$pdata is an array [param_name => array [value => <value>,test_date => <test date>, test_time => <test_time>]
$parameterselect = $parameterselect ?? NULL;

if ($result = $lab_obj->getLabResult($job_id, $parameterselect)) {
	while ($row = $result->FetchRow()) {

		$batch_nr = $row['batch_nr'];
		$pdata[$row['paramater_name']]['value'] = $row['parameter_value'];
		$pdata[$row['paramater_name']]['test_date'] = $row['test_date'];
		$pdata[$row['paramater_name']]['test_time'] = $row['test_time'];
	}
}

if (!empty($pdata)) {
	$allow_update = TRUE;
} else {
	$allow_update = FALSE;
}

# Load the date formatter
include_once $root_path . 'include/inc_date_format_functions.php';

//pull out test date/time from form input; if not set default to current dates
if (isset($_POST['test_date']) && $_POST['test_date'] != DBF_NODATE && (!empty($_POST['test_date']))) {
	$exam_date = formatDate2STD($_POST['test_date'], $date_format);
} else {
	$exam_date = date('Y-m-d');
}
if (isset($_POST['test_time']) && (!empty($_POST['test_time']))) {
	$exam_time = $_POST['test_time'];
} else {
	$exam_time = date('H:i:s');
}
if ($mode == 'save') {
	$nbuf = array();
	$nbuf_old = array();

	//Prepare parameter values
	while (list($z, $y) = $helper->CustomEach($_POST)) {

		//echo "<pre>";print_r($_POST);echo "</pre>";die;


		


		if ($result_tests = $lab_obj->GetTestsToDo($job_id, $encounter_nr)) {
			while ($row_tests = $result_tests->FetchRow()) {
				if ($z == $row_tests['paramater_name']) {
					$nbuf[$z]['amount'] = $y;
					$nbuf[$z]['sort_order'] = $row_tests['sort_order'];
				}
				if ($z == $row_tests['paramater_name'] . '_old') {
					$nbuf_old[$z] = $y;
				}
			}
		}

	}

	


	$dbuf['job_id'] = $job_id;
	$dbuf['encounter_nr'] = $encounter_nr;
	if ($allow_update == TRUE) {
		//this is a save of test that already has some data entered
		//in this case, delete old values and re-insert new ones
		$dbuf['modify_id'] = $_SESSION['sess_user_name'];
		$dbuf['modify_time'] = date('YmdHis');
		$lab_obj_sub->deleteOldValues($batch_nr, $encounter_nr);
//print_r($nbuf_old['__brucella_test__screening__serology_old']);



		foreach ($nbuf as $key => $value) {
			if (isset($value) && !empty($value['amount'])) {

				




				//get the old value from array
				$parsedParamList['old_param_value'] = $nbuf_old[$key . '_old'];
				$parsedParamList['is_updated'] = 1; //Set updated status to 1
				$parsedParamList['test_date'] = $exam_date;
				$parsedParamList['test_time'] = $exam_time;
				$parsedParamList['batch_nr'] = $batch_nr;
				$parsedParamList['job_id'] = $job_id;
				$parsedParamList['encounter_nr'] = $encounter_nr;
				$parsedParamList['paramater_name'] = $key;
				$parsedParamList['parameter_value'] = addslashes($value['amount']);
				$parsedParamList['sort_order'] = addslashes($value['sort_order']);
				$parsedParamList['history'] = "Modified: " . date('Y-m-d H:i:s') . " " . $_SESSION['sess_user_name'] . "\n";
				$parsedParamList['create_id'] = $_SESSION['sess_user_name'];
				$parsedParamList['create_time'] = date('YmdHis');


				$lab_obj_sub->setDataArray($parsedParamList);
				if ($lab_obj_sub->insertDataFromInternalArray()) {
					$saved = TRUE;

					//the line below was added by israel

					if (@$_POST['labcomment']) {						

						$labcommentSQL="UPDATE care_test_findings_chemlab SET lab_comment='".$_POST['labcomment']."' WHERE batch_nr=".$batch_nr." AND encounter_nr=".$encounter_nr;					

						$db->Execute($labcommentSQL);
						
					}

					//end added by israel

					

					//$lab_obj->getLastQuery();
				} else {
					echo "<p>" . $lab_obj->getLastQuery() . "$LDDbNoSave";
				}
			}
		}



		# If save successful, jump to display values
		if ($saved) {
			include_once $root_path . 'include/inc_visual_signalling_fx.php';
			# Set the visual signal
			setEventSignalColor($encounter_nr, SIGNAL_COLOR_DIAGNOSTICS_REPORT);
			//header("location:$thisfile?sid=$sid&lang=$lang&saved=1&batch_nr=$batch_nr&encounter_nr=$encounter_nr&job_id=$job_id&parameterselect=$parameterselect&allow_update=1&user_origin=$user_origin&tickerror=$tickerror");

			if (!empty($_POST['submit']) && $_POST['submit'] === "senddone") {
				$doneurl = "labor_test_request_admin_chemlabor.php" . URL_APPEND . "&edit=0&mode=done&target=admin&subtarget=chemlabor&batch_nr=" . $_SESSION['batch_nr'] . "&pn=" . $_SESSION['labPn'] . "&formtitle=" . $_SESSION['labFormTitle'] . "&user_origin=" . $thisfile;
				header("Location:" . $doneurl . " ");
			} else {
				$redirect = 'labor_datalist_noedit.php' . URL_APPEND . '&encounter_nr=' . $encounter_nr . '&noexpand=1&from=input&job_id=' . $job_id . '&parameterselect=' . $parameterselect . '&allow_update=' . $allow_update . '&nostat=1&user_origin=' . $user_origin;
				header("Location:$redirect");
			}
			exit;
		}
	} else {
		//this is first time test values are being entered for this test request (i.e. do INSERTs)

		$dbuf['test_date'] = $exam_date;
		$dbuf['test_time'] = $exam_time;
		$dbuf['history'] = "Create " . date('Y-m-d H:i:s') . " " . $_SESSION['sess_user_name'] . "\n";
		$dbuf['create_id'] = $_SESSION['sess_user_name'];
		$dbuf['create_time'] = date('YmdHis');
		$dbuf['modify_id'] = $dbuf['create_id'];
		$dbuf['modify_time'] = $dbuf['create_time'];
		$dbuf['lab_comment'] = $_POST['labcomment'];


		//Insert new lab record
		$lab_obj->setDataArray($dbuf);
		$insertedID = $lab_obj->insertDataFromInternalArray();
		if ($insertedID) {
			$pk_nr = $db->Insert_ID();
			$batch_nr = $lab_obj->LastInsertPK('batch_nr', $pk_nr);
			$batch_nr = $insertedID[0];

			//set the timestamps and modified dates for sub table
			$parsedParamList['create_id'] = $_SESSION['sess_user_name'];
			$parsedParamList['create_time'] = date('YmdHis');
			$parsedParamList['test_date'] = $exam_date;
			$parsedParamList['test_time'] = $exam_time;
			foreach ($nbuf as $key => $value) {

				if (isset($value) && !empty($value['amount'])) {

					$parsedParamList['batch_nr'] = $batch_nr;
					$parsedParamList['encounter_nr'] = $encounter_nr;
					$parsedParamList['job_id'] = $job_id;
					$parsedParamList['paramater_name'] = $key;
					$parsedParamList['parameter_value'] = $value['amount'];
					$parsedParamList['sort_order'] = $value['sort_order'];
					//now save it via sub object

					$lab_obj_sub->setDataArray($parsedParamList);
					$lab_obj_sub->insertDataFromInternalArray();
				}
			}
			$saved = true;
		} else {
			//save failed
			echo "<p>" . $lab_obj->getLastQuery() . "$LDDbNoSave";
		}
	}
	# If save successful, jump to display values
	if ($saved) {
		include_once $root_path . 'include/inc_visual_signalling_fx.php';
		# Set the visual signal
		setEventSignalColor($encounter_nr, SIGNAL_COLOR_DIAGNOSTICS_REPORT);
		header_remove();
		if (!empty($_POST['submit']) && $_POST['submit'] === "senddone") {
			$_POST['submit'] = "";
			$doneurl = "labor_test_request_admin_chemlabor.php" . URL_APPEND . "&edit=0&mode=done&target=admin&subtarget=chemlabor&batch_nr=" . $_SESSION['batch_nr'] . "&pn=" . $_SESSION['labPn'] . "&formtitle=" . $_SESSION['labFormTitle'] . "&user_origin=" . $thisfile;
			header("Location:" . $doneurl . " ");
		} else {
			header("location:labor_test_request_admin_chemlabor.php?sid=$sid");
		}
		exit;

	}
# end of if(mode==save)
} else {
	//If mode is not "save" then pull previously saved data and present form for editing

	if ($debug) {
		echo "mode is not save then get the basic personal data<br>";
	}

	# If previously saved, get the values

	if (@$saved) {
		if ($result = $lab_obj->getBatchResult($batch_nr)) {
			while ($row = $result->FetchRow()) {
				$pdata[$row['paramater_name']]['value'] = $row['parameter_value'];
				$pdata[$row['paramater_name']]['test_date'] = $row['test_date'];
				$pdata[$row['paramater_name']]['test_time'] = $row['test_time'];
			}
		}
	} else {
		if ($result = $lab_obj->getLabResult($job_id, $parameterselect)) {
			while ($row = $result->FetchRow()) {
				$pdata[$row['paramater_name']]['value'] = $row['parameter_value'];
				$pdata[$row['paramater_name']]['test_date'] = $row['test_date'];
				$pdata[$row['paramater_name']]['test_time'] = $row['test_time'];
			}
		} else {
			# disallow update if group does not exist yet
			$allow_update = false;
		}
	}

	//TODO: for now just use the last element for display of exam date/time
	//set date/time vars for display if present; else use the defaults already in place
	if (@$pdata && (!empty($pdata[key($pdata)]['test_date'])) && $pdata[key($pdata)]['test_date'] != DBF_NODATE) {
		$exam_date = $pdata[key($pdata)]['test_date'];
	}
	if (@$pdata && !empty($pdata[key($pdata)]['test_time'])) {
		$exam_time = $pdata[key($pdata)]['test_time'];
	}
	;

	//echo $lab_obj->getLastQuery();
	# Get the test test groups
	$tgroups = $lab_obj->TestActiveGroups();
	# Get the test parameter values
	//gjergji : take all the params for this group...
	$tparams = $lab_obj->TestParams();

	# Set the return file
	if (isset($job_id) && $job_id) {
		switch ($user_origin) {
		case 'lab_mgmt':
			$breakfile = "labor_test_request_admin_chemlabor.php" . URL_APPEND . "&pn=$encounter_nr&batch_nr=$job_id&user_origin=lab";
			break;
		default:
			//$breakfile="labor_data_check_arch.php".URL_APPEND."&versand=1&encounter_nr=$encounter_nr";
			$breakfile = "labor.php";
		}
	} else {
		$breakfile = "labor_data_patient_such.php" . URL_APPEND . "&mode=edit";
	}
}

# Prepare title
if ($update) {
	$sTitle = "$LDLabReport - $LDEdit";
} else {
	$sTitle = "$LDNew $LDLabReport";
}

# Start Smarty templating here
/**
 * LOAD Smarty
 */
# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once $root_path . 'gui/smarty_template/smarty_care.class.php';
$smarty = new smarty_care('common');

# Title in toolbar
$smarty->assign('sToolbarTitle', $sTitle);

# href for help button
$smarty->assign('pbHelp', "javascript:gethelp('lab_report_edit.php','Laboratories :: Lab Report Edit','main','$job_id')");

# hide return  button
$smarty->assign('pbBack', FALSE);

# href for close button
$smarty->assign('breakfile', $breakfile);

# Window bar title
$smarty->assign('sWindowTitle', $sTitle);

# collect extra javascript code
ob_start();
?>

<style type="text/css" name="1">
.va12_n {
    font-family: verdana, arial;
    font-size: 12;
    color: #000099
}

.a10_b {
    font-family: arial;
    font-size: 10;
    color: #000000
}

.a10_n {
    font-family: arial;
    font-size: 10;
    color: #000099
}
</style>

<script language="javascript" name="j1">
<!--
function pruf(d) {
    if (!d.job_id.value) {
        alert("<?php echo $LDAlertJobId ?>");
        d.job_id.focus();
        return false;
    } else {
        if (d.test_date) {
            if (!d.test_date.value) {
                alert("<?php echo $LDAlertTestDate ?>");
                d.test_date.focus();
                return false;
            } else
                return true;
        }
    }
}

function posneg(f) {
    //if(d."<?php echo $adddata[$tp['id']] ?>[0].checked || d."<?php echo $adddata[$tp['id']] ?>"[1].checked)
    //{
    // alert(<?php
echo $_POST['_add' . $x . '_']; ?> );
//return false;
//}
//else return true;

}

function limitedInput(inputId, range) {
    var inputElement = document.getElementById(inputId);
    var rangeArray = range.split("-");
    if (inputElement.value != '') {
        if (Number(inputElement.value).toFixed(6) < Number(rangeArray[0]).toFixed(6) ||
            Number(inputElement.value).toFixed(6) > Number(rangeArray[1]).toFixed(6)) {
            alert('Value must be between ranges : ' + rangeArray[0] + ' and ' + rangeArray[1] + '!');
            inputElement.value = '';
        }
    }

}


function chkselect(d) {
    if (d.parameterselect.value == "<?php echo $parameterselect ?>") {
        return false;
    }
}

function labReport() {
    window.location.replace("<?php
echo 'labor_datalist_noedit.php' . URL_REDIRECT_APPEND .
	'&encounter_nr=' . $encounter_nr .
	'&noexpand=1&from=input&job_id=' . $job_id .
	'&parameterselect=' . $parameterselect .
	'&allow_update=' . $allow_update .
	'&nostat=1&user_origin=lab'; ?> ");
    }
    <?php
require $root_path . 'include/inc_checkdate_lang.php';
?>
    // -->
</script>
<script language="javascript" src="<?php echo $root_path ?>js/checkdate.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $root_path ?>js/setdatetime.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/dtpick_care2x.js"></script>

<?php

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript', $sTemp);

# Assign patient basic elements
$smarty->assign('LDCaseNr', $LDPatientID);
$smarty->assign('LDLastName', $LDLastName);
$smarty->assign('LDName', $LDName);
$smarty->assign('LDBday', $LDBday);
$smarty->assign('LDJobIdNr', $LDJobIdNr);
$smarty->assign('LDExamDate', $LDExamDate);
$smarty->assign('LDExamDate', $LDExamDate);
//$smarty->assign('LDSampleTime', $LDSampleTime);
# Assign patient basic data
//$smarty->assign ( 'encounter_nr', $encounter_nr );
$smarty->assign('sPID', $pid);
$smarty->assign('sLastName', $patient['name_last']);
$smarty->assign('sName', ucwords($patient['name_first']));
$smarty->assign('sBday', formatDate2Local($patient['date_birth'], $date_format));
$smarty->assign('sJobIdNr', $job_id . '<input type=hidden name=job_id value="' . $job_id . '">');
$batchNumber = $job_id;
$smarty->assign('sExamDate', '<input name="test_date" type="text" size="14" value="' . formatDate2Local($exam_date, $date_format) . '" onBlur="IsValidDate(this,\'' . $date_format . '\')")  onKeyUp="setDate(this,\'' . $date_format . '\',\'' . $lang . '\')">');
$smarty->assign('sExamTime', '<input name="test_time" type="text" size="5" value="' . convertTimeToLocal($exam_time) . '" ">');
//$smarty->assign('sSampleTime', '<input name="test_time" type="text" size="5" value="' . convertTimeToLocal($sample_time) . '" ">');
$smarty->assign('sMiniCalendar', "<a href=\"javascript:show_calendar('datain.test_date','$date_format')\"><img " . createComIcon($root_path, 'show-calendar.gif', '0', 'absmiddle') . "></a>");

$smarty->assign('pbShowReport', '<a href="labor_datalist_noedit.php' . URL_APPEND . '&encounter_nr=' . $encounter_nr . '&noexpand=1&from=input&job_id=' . $job_id . '&parameterselect=' . $parameterselect . '&allow_update=' . $allow_update . '&nostat=1&user_origin=' . $user_origin . '"><img ' . createLDImgSrc($root_path, 'showreport.gif', '0', 'absmiddle') . ' alt="' . $LDClk2See . '"></a>');

if (@$saved || $update) {
	$sCancelBut = '<img ' . createLDImgSrc($root_path, 'close2.gif', '0', 'absmiddle') . '>';
} else {
	$sCancelBut = '<img  ' . createLDImgSrc($root_path, 'cancel.gif', '0', 'absmiddle') . '>';
}

//$smarty->assign('pbCancel',"<a href=\"$breakfile\">$sCancelBut</a>");
$smarty->assign('pbCancel', "<a href=\"$breakfile\">$sCancelBut</a>");

$smarty->assign('sAskIcon', "<img " . createComIcon($root_path, 'small_help.gif', '0') . ">");

$smarty->assign('sFormAction', $thisfile);

# Buffer parameter items generation

ob_start();
?>

<?php
if (@$tickerror > 0):
?>
<tr bgcolor=#ffffee>
    <td colspan=4>
        <center>
            <font face=arial color=#7700ff size=4> An error occured! Please
                be sure to insert valid values and also fill out the text boxes right
                beside the checkboxes!
        </center>
    </td>
</tr>

<?php
endif;

//* Get the global config for billing item*/
include_once $root_path . 'include/care_api_classes/class_globalconfig.php';
$glob_obj = new GlobalConfig($GLOBAL_CONFIG);
$parameters = @$parameters ?? [];
$paramnum = sizeof($parameters);
//$pcols = ceil ( $paramnum / ROW_MAX );

echo '<tr>';
$pcols = @$pcols ?? 1;

for ($j = 0; $j < $pcols; $j++) {
	echo '
        <td class="a10_n">&nbsp;' . $LDParameter . '</td>
        <td  class="a10_n">&nbsp;' . $LDValue . '</td>';
}

echo '
    </tr>';
//order the params according to groups
$rowlimit = 0;
$requestData = array();
if ($result_tests = $lab_obj->GetTestsToDo($job_id, $encounter_nr)) {
	//TODO - gjergji : why do i have to do it like this ?!
	do {
		if (isset($result_tests->fields['paramater_name'])) {
			$ext = substr(stristr($result_tests->fields['paramater_name'], '__'), 2);

			preg_match("/[^_]+$/", $result_tests->fields['paramater_name'], $jina);
			$last_word = $jina[0];

			// $requestData[str_replace("_", "", $ext)][$result_tests->fields['paramater_name']] = $result_tests->fields['parameter_value'];
			$requestData[$last_word][$result_tests->fields['paramater_name']] = $result_tests->fields['parameter_value'];
		}
	} while ($result_tests->MoveNext());
}
$pcols = COL_MAX / 2;
reset($requestData);
//print_r($requestData);
$collimit = 0;

$testMeasures = [];
$data = $requestData;
foreach ($data as $testArray) {
	foreach ($testArray as $key => $value) {
		array_push($testMeasures, $key);
	}
}

$_COOKIE['testMeasures'] = $testMeasures;

function array_flatten($array) {
	if (!is_array($array)) {
		return FALSE;
	}
	$result = array();
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$result = array_merge($result, array_flatten($value));
		} else {
			$result[$key] = $value;
		}
	}
	return $result;
}

if (@($_FILES)) {
	$excelErrors = array();
	$file_name = $_FILES['resultfile']['name'];
	$file_size = $_FILES['resultfile']['size'];
	$file_tmp = $_FILES['resultfile']['tmp_name'];
	$file_type = $_FILES['resultfile']['type'];
	$file_ext = strtolower(end(explode('.', $file_name)));

	$excelExtensions = array("xls", "xlsx", "csv", 'xlsm', 'xlsb', 'xltx', 'xltm', 'xlt', 'xml', 'xlam', 'xla', 'xlw', 'xlr');

	if (in_array($file_ext, $excelExtensions) === false) {
		$excelErrors[] = "The Selected file isn't an excel file. Please select excel file.";
	}



	if (empty($excelErrors) == true) {

		$uploadDirectory = "uploads/";
		if (!file_exists($uploadDirectory)) {
			$oldmask = umask(0);
			mkdir($uploadDirectory, 0777, true);
			umask($oldmask);
		}

		$filePath = $uploadDirectory . $file_name;


		move_uploaded_file($file_tmp, $filePath);


		$inputFileType = IOFactory::identify($filePath);

		$reader = IOFactory::createReader($inputFileType);
		$spreadsheet = $reader->load($filePath);


		$labResults = [];
		$reqTests = $_COOKIE['testMeasures'];

		require_once $root_path . "modules/laboratory/ms4Machine.php";
		require_once $root_path . "modules/laboratory/mythic22.php";
		require_once $root_path . "modules/laboratory/dh76Machine.php";


		  //print_r($labResults);



		if (file_exists($filePath)) {
			unlink($filePath);
		}
	} else {





		// upload file and save it's path
		if (@$file_ext) {

			$uploadDirectory = "uploads/labfiles/";
			if (!file_exists($uploadDirectory)) {
				$oldmask = umask(0);
				mkdir($uploadDirectory, 0777, true);
				umask($oldmask);
			}
			$filePath = $uploadDirectory . $file_name;
           
			move_uploaded_file($file_tmp, $filePath);
             
			$update_file = $_POST['update_file'];
			if (empty($update_file)) {
				$dbuf['test_date'] = $exam_date;
				$dbuf['test_time'] = $exam_time;
				$dbuf['history'] = "Create " . date('Y-m-d H:i:s') . " " . $_SESSION['sess_user_name'] . "\n";
				$dbuf['create_id'] = $_SESSION['sess_user_name'];
				$dbuf['create_time'] = date('YmdHis');
				$dbuf['modify_id'] = $dbuf['create_id'];
				$dbuf['modify_time'] = $dbuf['create_time'];
				$dbuf['encounter_nr'] = $encounter_nr;
				$dbuf['job_id'] = $job_id;

				//Insert new lab record
				$lab_obj->setDataArray($dbuf);
				$lab_obj->insertDataFromInternalArray();
			}

			$batchNrSQL = "SELECT max(batch_nr) as batch_nr FROM care_test_findings_chemlab WHERE job_id = {$job_id} AND encounter_nr = {$encounter_nr}";

			$batchQuery = $db->Execute($batchNrSQL);
			$batchRow = $batchQuery->FetchRow();
			$new_batch_nr = @$batchRow['batch_nr'] ? $batchRow['batch_nr'] : 0;

			$updateSQL = "UPDATE care_test_findings_chemlab SET file_path = '{$filePath}' WHERE job_id = {$job_id} AND encounter_nr = {$encounter_nr} AND batch_nr = {$new_batch_nr}";
			$db->Execute($updateSQL);
			$smarty->assign('uploadedFile', "uploadedFile");

		}

	}



}

//print_r($labResults);


//check existance of file batch number, added by israel
if (empty($fileBatchNr)) {
	if (@$filebnr) {
		$fileBatchNr=$filebnr['filebatchnr'];
	}
}

if (@$fileBatchNr && $fileBatchNr == $batchNumber) {
	//print_r($pdata);

	foreach ($pdata as $pdataKey => $value) {

		foreach ($labResults as $lab_result) {

			if ($lab_result['description'] == $pdataKey) {
				$pdata[$pdataKey]['value'] = $lab_result['amount'];
			}

			

		}

	}

}



$rejected = isset($_GET['rejected']) ? $_GET['rejected'] : null;

if (@$rejected && $rejected == 1) {

	foreach ($pdata as $pdataKey => $value) {
		$pdata[$pdataKey]['value'] = "";

	}
}

// MINOR cHANGES

while (list($group, $pm) = $helper->customEach($requestData)) {

	if ($glob_obj->getConfigValue("restrict_unbilled_items") === "1" && $enc_obj->EncounterClass() === "2" && $patient['insurance_ID'] === '0') {

		$gName = $lab_obj->getGroupName($group);

		if ($lab_obj->getPatientLabBillNoByBatch($job_id) > 0) {
			echo '<tr>';
			echo '<td colspan="' . COL_MAX . '" bgcolor="#ffffee" class="a10_a"><b>';
			echo $gName->fields['name'];
			echo '</b></td></tr>';
		}
	} else {
		$gName = $lab_obj->getGroupName($group);
		echo '<tr>';
		echo '<td colspan="' . COL_MAX . '" bgcolor="#ffffee" class="a10_a"><b>';
		echo $gName->fields['name'];
		echo '</b></td></tr>';
	}

	echo '<tr>';

	while (list($pId, $not) = $helper->CustomEach($pm)) {

		//Check if item is billed
		if ($glob_obj->getConfigValue("restrict_unbilled_items") === "1" && $enc_obj->EncounterClass() === "2" && $patient['insurance_ID'] === '0') {
			//Check the restriction status
			if ($lab_obj->getLabBillNoByBatch($job_id, $pId) > 0) {

				// echo '<tr>';
				// echo '<td colspan="' . COL_MAX . '" bgcolor="#ffffee" class="a10_a"><b>';
				// echo $gName->fields['name'];
				// echo '</b></td></tr>';

				$pName = $lab_obj->TestParamsDetails($pId);
				echo '<td bgcolor="#ffffee" class="a10_b"><b>';
				echo $pName->fields['name'] . '</b></td>';
				echo '<td>';
				//it's a dropdown
				if ($pName->fields['field_type'] == 'drop_down') {
					$inputValue = '<select name="' . $pId . '" size="1">';
					do {
						$inputValue .= '<option value=' . $pDropDown['input_value'];
						if ($pDropDown['input_value'] == $pdata[$pId]['value']) {
							$inputValue .= ' selected="selected" ';
						}

						$inputValue .= '>' . $pDropDown['input_value'] . '</option>';
					} while ($pDropDown = $pName->FetchRow());
					$inputValue .= '</select>';
					//it has margins in the set of values so activate js
				} elseif ($pName->fields['field_type'] == 'limited') {
					$inputValue = '<input name="' . $pId . '" type="text" size="8" value="' . $pdata[$pId]['value'] . '" id="' . $pId . '" ';
					$inputValue .= 'onBlur="javascript:limitedInput(\'' . $pId . '\',\'' . $pName->fields['input_value'] . '\')">';
					//standard input box
				} else {

					$labTestResult = $pdata[$pId]['value'];

					if ($fileBatchNr == $batchNumber) {
						foreach ($labResults as $lab_result) {
							if ($lab_result['description'] == $pName->fields[5]) {
								$labTestResult = $lab_result['amount'];



							}
						}

					}

					$inputValue = '<input name="' . $pId . '" type="text" size="8" value="' . $labTestResult . '">';
				}

				echo $inputValue;
				echo '<input name="' . $pId . '_old" type="hidden" size="8" value="' . $pdata[$pId]['value'] . '">';
				echo '&nbsp;';
			}

		} else {

			$pName = $lab_obj->TestParamsDetails($pId);
			echo '<td bgcolor="#ffffee" class="a10_b"><b>';
			echo $pName->fields['name'] . '</b></td>';
			echo '<td>';
			//it's a dropdown
			if ($pName->fields['field_type'] == 'drop_down') {
				$inputValue = '<select name="' . $pId . '" size="1">';
				do {
					$inputValue .= '<option value=' . $pDropDown['input_value'];
					if ($pDropDown['input_value'] == $pdata[$pId]['value']) {
						$inputValue .= ' selected="selected" ';
					}

					$inputValue .= '>' . $pDropDown['input_value'] . '</option>';
				} while ($pDropDown = $pName->FetchRow());
				$inputValue .= '</select>';
				//it has margins in the set of values so activate js
			} elseif ($pName->fields['field_type'] == 'limited') {
				$inputValue = '<input name="' . $pId . '" type="text" size="8" value="' . $pdata[$pId]['value'] . '" id="' . $pId . '" ';
				$inputValue .= 'onBlur="javascript:limitedInput(\'' . $pId . '\',\'' . $pName->fields['input_value'] . '\')">';
				//standard input box
			} else {

				$labTestResult = $pdata[$pId]['value'];
				if ($fileBatchNr == $batchNumber) {
					foreach ($labResults as $lab_result) {

						if ($lab_result['description'] == $pName->fields[5]) {
							$labTestResult = $lab_result['amount'];

						}
					}

				}

				$inputValue = '<input name="' . $pId . '" type="text" size="8" value="' . $labTestResult . '">';
			}
			echo $inputValue;
			//Hidden input value for edit mode
			echo '<input name="' . $pId . '_old" type="hidden" size="8" value="' . $pdata[$pId]['value'] . '">';
			echo '&nbsp;';
		}

		$collimit++;
		if ($collimit == (COL_MAX / 2)) {
			echo '
            </tr>';

			$collimit = 0;
		}
	}
}

if (@$fileBatchNr && $fileBatchNr != $batchNumber && !empty($_FILES) && empty($excelErrors)) {
	$smarty->assign('batchMismatch', "Mismatch");
}

# Assign parameter output
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$sTemp = ob_get_contents();
ob_end_clean();

$textarea='<div class="form-group shadow-textarea">
    <textarea class="form-control z-depth-1" name="labcomment" id="labcomment" rows="3" placeholder="Write Comment Here"></textarea>
</div>';

$smarty->assign('sParameters', $sTemp);

$uploadURL = $actual_link . "&rejected=0";

$formUpload = '<form action="' . $uploadURL . '" method="post"  enctype="multipart/form-data">
                    <table>
                        <tbody>
                           <tr>
                            <h5 style="width: 300px; text-align: center;">Supported Machines: MS4, DH 76 </h5> <hr>
                           </tr>
                            <tr>
                               <div style="width: 200px">
                                    <input type="checkbox" name="update_file" value="update_file">Check to update last patient\'s uploaded file (ie pdf, image) result <br><br>
                                    <input type="file" required name="resultfile">
                                    <button class="btn btn-primary btn-sm" type="submit">Upload</button>
                                  </div>
                            </tr>
                        </tbody>
                    </table>
                </form>';



$enableUpload = "no";

$testId = @$pName->fields['group_id'] ? $pName->fields['group_id'] : 0;
$testSQL = "SELECT enable_upload FROM care_tz_laboratory_param WHERE id = '$testId' AND group_id = -1";





$testQuery = $db->Execute($testSQL);

if (@$testQuery && $testQuery->RecordCount()) {
	$enableUpoadRow = $testQuery->FetchRow();
	$enableUpload = $enableUpoadRow['enable_upload'];
}



if ($enableUpload == 'yes') {

    $smarty->assign('pbSave', '<button class="btn sendBtn btn-primary btn-sm" type="submit" name="submit" value="send">Send</button>');
	$smarty->assign('pbSaveDone', '<button class="btn sendBtn btn-info btn-sm" type="submit" name="submit"  value="senddone">Send & Done</button>');
    $smarty->assign('resultFormUpload', $formUpload);
	
	
	// $smarty->assign('pbSave', '<input class="sendBtn" style="display: none"  type="image" ' . createLDImgSrc($root_path, 'send.gif', '0') . ' >');

	/*
	accept submit form, that is why we commented 
	$smarty->assign('pbSave', '<button style="display: none" type="submit" name="submit" class="btn sendBtn btn-primary btn-sm"  value="send">Send</button>');
	$smarty->assign('pbSaveDone', '<button style="display: none" type="submit" name="submit"  class="btn sendBtn btn-info btn-sm"  value="senddone">Send & Done</button>');
	$smarty->assign('pbAccept', '<button class="btn acceptBtn btn-success btn-sm">Accept</button>');
	$smarty->assign('pbReject', '<button href="' . $actual_link . '" type="reset" class="btn rejectBtn btn-danger btn-sm">Reject</button>');
	$smarty->assign('resultFormUpload', $formUpload);
} else {
	$smarty->assign('pbSave', '<button class="btn sendBtn btn-primary btn-sm" type="submit" name="submit" value="send">Send</button>');
	$smarty->assign('pbSaveDone', '<button class="btn sendBtn btn-info btn-sm" type="submit" name="submit"  value="senddone">Send & Done</button>');
	*/
}

$smarty->assign('comment',$textarea);





# Collect hidden inputs for the parameters form

ob_start();
?>
<input type=hidden name="parameterselect" value=<?php echo $parameterselect; ?>>
<input type=hidden name="encounter_nr" value="<?php echo $encounter_nr; ?>">
<input type=hidden name="sid" value="<?php echo $sid; ?>">
<input type=hidden name="lang" value="<?php echo $lang; ?>">
<input type=hidden name="update" value="<?php echo $update; ?>">
<input type=hidden name="allow_update" value="<?php if (isset($allow_update)) {
	echo $allow_update;
}
?>">
<input type=hidden name="batch_nr" value="<?php if (isset($row['batch_nr'])) {
	echo $row['batch_nr'];
}
?>">
<input type=hidden name="newid" value="<?php echo $newid; ?>">
<input type=hidden name="user_origin" value="<?php echo $user_origin; ?>">
<input type=hidden name="mode" value="save">
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->assign('sSaveParamHiddenInputs', $sTemp);

# Assign help items
$smarty->assign('LDParamNoSee', "<a href=\"Javascript:gethelp('lab.php','input','param')\">$LDParamNoSee</a>");
$smarty->assign('LDOnlyPair', "<a href=\"Javascript:gethelp('lab.php','input','few')\">$LDOnlyPair</a>");
$smarty->assign('LDHow2Save', "<a href=\"Javascript:gethelp('lab.php','input','save')\">$LDHow2Save</a>");
$smarty->assign('LDWrongValueHow', "<a href=\"Javascript:gethelp('lab.php','input','correct')\">$LDWrongValueHow</a>");
$smarty->assign('LDVal2Note', "<a href=\"Javascript:gethelp('lab.php','input','note')\">$LDVal2Note</a>");
$smarty->assign('LDImDone', "<a href=\"Javascript:gethelp('lab.php','input','done')\">$LDImDone</a>");

# Assign the include file to mainframe

$smarty->assign('sMainBlockIncludeFile', 'laboratory/chemlab_data_results.tpl');

/**
 * show Template
 */

require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';
$smarty->display('common/mainframe.tpl');

require_once $root_path . 'main_theme/footer.inc.php';
?>