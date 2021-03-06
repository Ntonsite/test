<?php
require './roots.php';
require $root_path . 'include/inc_environment_global.php';
require_once $root_path . 'include/care_api_classes/class_globalconfig.php';
require_once $root_path . 'include/inc_init_xmlrpc.php';
require_once $root_path . 'include/care_api_classes/class_tz_billing.php';

/**
 * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
 * GNU General Public License
 * Copyright 2005 Robert Meggle based on the development of Elpidio Latorilla (2002,2003,2004,2005)
 * elpidio@care2x.org, meggle@merotech.de
 *
 * See the file "copy_notice.txt" for the licence notice
 */
require_once $root_path . 'include/care_api_classes/class_encounter.php';
$transmit_to_weberp_enabled = $glob_obj->getConfigValue('transmit_to_weberp_enabled');
if ($transmit_to_weberp_enabled == 1) {
	require_once $root_path . 'include/care_api_classes/class_weberp_c2x.php';
	$weberp_obj = new weberp();
}

$enc_obj = new Encounter;

if (!isset($glob_obj)) {
	$glob_obj = new GlobalConfig($GLOBAL_CONFIG);

}
$bill_obj = new Bill;

$_GET['prescrServ'] = (isset($_GET['prescrServ']) ? $_GET['prescrServ'] : null);
$_GET['admission'] = (isset($_GET['admission']) ? $_GET['admission'] : null);

$prescrServ = $_GET['prescrServ'];
$admission = $_GET['admission'];

//$locationcode = $_GET['loccode'];
if (isset($_GET['loccode'])) {
	$_SESSION['loccode'] = $_GET['loccode'];
	$locationcode = $_SESSION['loccode'];
	$_SESSION['locname'] = $_GET['locname'];
	$locname = $_SESSION['locname'];
} else {
	$locationcode = $_SESSION['loccode'];
	$locname = $_SESSION['locname'];
}

$and_admission_class = '';

if ($admission == 'inpatient') {
	$and_admission_class = 'AND care_encounter.encounter_class_nr=1';
} else {
	if ($admission == 'outpatient') {
		$and_admission_class = 'AND care_encounter.encounter_class_nr=2';
	} else {
		$and_admission_class = '';
	}
}

$debug = false;

if ($debug) {
	echo $pn . "<br>";
	echo $prescription_date . "<br>";
	echo "comming from " . $comming_from . "<br>";
	echo "back path:" . $back_path . "<br>";
	echo "prescrServ: " . $prescrServ . "<br>";
	echo "admission: " . $admission . "<br>";
	//echo "loccode: ".$loccode."<br>";
}

if (empty($back_path)) {
	$RETURN_PATH = $root_path . "modules/pharmacy_tz/pharmacy_tz_substore.php";
	// $RETURN_PATH = $root_path . "modules/pharmacy_tz/pharmacy_tz.php?ntid=false&lang=$lang";
} else {
	if ($back_path == "billing") {
		$RETURN_PATH = $root_path . "modules/billing_tz/billing_tz.php";
	}

	if ($back_path == "laboratory") {
		$RETURN_PATH = $root_path . "modules/laboratory/labor.php";
	}

}

$mode = isset($mode) ? $mode : "";
if ($mode == "done" && isset($pn) && isset($prescription_date)) {
	require_once $root_path . 'include/care_api_classes/class_tz_insurance.php';

	$sql_pid = "SELECT pid FROM care_encounter WHERE encounter_nr=" . $pn;
	$rs = $db->Execute($sql_pid);
	if ($row = $rs->FetchRow()) {
		$pid = $row['pid'];

	}

//     $ins_obj= new Insurance_tz;

	//  $ins_name=$ins_obj->GetName_insurance_from_pid($pid);

	// if ($ins_name) {
	//   $healthfund=$ins_name;
	//}else{

	// $healthfund='CASH';

	//}

	$sql_healthf = "SELECT insurance_ID, name_first, name_2, name_last, selian_pid FROM care_person WHERE pid=" . $pid;

	$result_hf = $db->Execute($sql_healthf);
	$row = $result_hf->FetchRow();
	if ($row['insurance_ID'] == "0") {
		$insname = 'CASH';
	} else {
		$insname = 'CREDIT';
	}

	if (@$row) {
		$description = $row['selian_pid'] . " " . $row['name_last'] . " " . $row['name_2'] . " " . $row['name_first'];
	}

	//xmlrpc API is On
	if ($transmit_to_weberp_enabled === "1") {

		if ($glob_obj->getConfigValue("restrict_unbilled_items") === "1" && $insname === "CASH") {
			$sql = "SELECT pr.*, e.encounter_class_nr FROM care_encounter AS e, care_person AS p, care_encounter_prescription AS pr, care_tz_drugsandservices
          WHERE p.pid=" . $_SESSION['sess_pid'] . " AND p.pid=e.pid AND e.encounter_nr=pr.encounter_nr
                      AND pr.article_item_number=care_tz_drugsandservices.item_id
                      AND (pr.bill_number > '0'  OR e.encounter_class_nr = '1')
                      AND ( purchasing_class = 'drug_list' OR purchasing_class='supplies')  AND  (pr.status='pending' OR pr.status='') AND pr.encounter_nr='" . $pn . "'  AND pr.prescribe_date='" . $prescription_date . "'
          ORDER BY pr.modify_time DESC";
			//echo $sql;
		} else {
			$sql = "SELECT pr.*, e.encounter_class_nr FROM care_encounter AS e, care_person AS p, care_encounter_prescription AS pr, care_tz_drugsandservices
          WHERE p.pid=" . $_SESSION['sess_pid'] . " AND p.pid=e.pid AND e.encounter_nr=pr.encounter_nr
                      AND pr.article_item_number=care_tz_drugsandservices.item_id AND ( purchasing_class = 'drug_list' OR purchasing_class='supplies')  AND (pr.status='pending' OR pr.status='') AND pr.encounter_nr='" . $pn . "' AND pr.prescribe_date='" . $prescription_date . "'
          ORDER BY pr.modify_time DESC";
		}

		//stock_adjustment_in_webERP($StockID, $Location, $Quantity, $TranDate)
		//get_stock_balance_webERP($stockID)
		$result_api = $db->Execute($sql);
		$item = '';
		while ($row_api = $result_api->FetchRow()) {
			//let us done dental items, I dont know why they are included here
			//done dental items
			$sql_done_supplies = "SELECT article_item_number FROM care_encounter_prescription INNER JOIN care_tz_drugsandservices ON care_encounter_prescription.article_item_number=care_tz_drugsandservices.item_id WHERE care_tz_drugsandservices.purchasing_class ='supplies' AND care_encounter_prescription.article_item_number=" . $row_api['article_item_number'];
			$result_done_supplies = $db->Execute($sql_done_supplies);
			while ($row_done_supplies = $result_done_supplies->FetchRow()) {
				$sql_update = "UPDATE care_encounter_prescription
                                           SET status = 'done',taken='1',sub_store='" . $locationcode . "',issuer='" . $_SESSION['sess_user_name'] . "'
                                          WHERE encounter_nr = " . $pn . " AND prescribe_date = '" . $prescription_date . "' AND article_item_number='" . $row_api['article_item_number'] . "'";

				$db->Execute($sql_update);

			}
			//end done dental items
			if ($row_api['total_dosage'] > 0) {
				if (isset($row_api['partcode'])) {
					$item = $weberp_obj->get_stock_item_from_webERP($row_api['partcode']);
					if ($row_api['partcode'] == $item['stockid']) {
						$Bal = $weberp_obj->get_stock_balance_webERP($row_api['partcode']);
						for ($i = 0; $i < sizeof($Bal); $i++) {
							if ($Bal[$i]['loccode'] == $locationcode) {
								$StockBal = $Bal[$i]['quantity'];
								if ($StockBal > 0) {
									if ($StockBal >= $row_api['total_dosage']) {
										$TranDate = date('Y-m-d');
										$zero = 0.00;
										$total_dosage = number_format($row_api['total_dosage'], 2);
										$Quantity = $zero - $total_dosage;
										$TranDate .= "_" . $description;

										$adjust = $weberp_obj->stock_adjustment_in_webERP($row_api['partcode'], $_SESSION['loccode'], $Quantity, $TranDate);

										$adjusted_qty = 0;
										$adjusted_qty = $weberp_obj->get_stock_balance_webERP($row_api['partcode']);

										//echo 'adjusted'.$adjusted_qty[$i]['quantity'] .'<br>';
										//echo 'initial balance'.$StockBal;
										if ($StockBal > $adjusted_qty[$i]['quantity']) {

											/*
												                                          There was a bug in stock deduction, the bug was caused by
												                                          relying on encounter_nr and prescription date, the problems comes when one prescripton contain different encounter nr and prescription date, the bug was fixed on 5.July.2019.

												                                          Lets flag done based on prescription number
											*/

											$sql = "UPDATE
                care_encounter_prescription
          SET status = 'done',taken='1',issue_date='" . date('Y-m-d H:i:s') . "',materialcost='" . number_format($item['materialcost'], '2', '.', '') . "',sub_store='" . $locationcode . "',issuer='" . $_SESSION['sess_user_name'] . "',in_weberp='1'
          WHERE nr='" . $row_api['nr'] . "' AND status IN('','pending')";

											$sql4 = "UPDATE care_tz_billing_archive_elem SET sub_store='" . $locationcode . "', materialcost='" . number_format($item['materialcost'], '2', '.', '') . "' WHERE prescriptions_nr='" . $row_api['nr'];
											$db->Execute($sql4);

											($debug) ? $db->debug = TRUE : $db->debug = FALSE;
											$db->Execute($sql);

										}

//start

//end
										//$weberp_obj->destroy_weberp($obj_weberp);

									} //end  if($StockBal>=$row_api['total_dosage'])
								} //end if($StockBal>0)
							} //end if($Bal[$i]['loccode']==$locationcode)
						} //end for loop sizeof($Bal)
					} //end  if($row_api['partcode']==$item['stockid'])
				} //end isset($row_api['partcode']

			} //end $row_api['total_dosage']>0

		} //end while row_api

	} else {

//The lines below were changed by Israel Pascal to include subtores in pharmacy
		// Update the datbase: Set this prescription as "done"

		$sql = "UPDATE
                care_encounter_prescription
          SET status = 'done',taken='1',issue_date='" . date('Y-m-d H:i:s') . "',sub_store='" . $locationcode . "',issuer='" . $_SESSION['sess_user_name'] . "'
          WHERE encounter_nr = " . $pn . "
                AND prescribe_date = '" . $prescription_date . "'";

		//echo $sql;

		$sql3 = "SELECT nr FROM care_encounter_prescription WHERE encounter_nr=" . $pn . " AND prescribe_date = '" . $prescription_date . "'";
		($debug) ? $db->debug = TRUE : $db->debug = FALSE;
		$result_nr = $db->Execute($sql3);
		$data = array();
		while ($store_rows = $result_nr->FetchRow()) {
			$data['nr'][] = $store_rows;
		}
		while (list($x, $v) = each($data['nr'])) {
			$sql4 = "UPDATE care_tz_billing_archive_elem SET sub_store='" . $locationcode . "' WHERE prescriptions_nr='" . $v['nr'] . "'";
			$db->Execute($sql4);
		}

//echo $sql;
		($debug) ? $db->debug = TRUE : $db->debug = FALSE;
		$db->Execute($sql);
	}

	//deduct stock from weberp

	//stock_adjustment_in_webERP($StockID, $Location, $Quantity, $TranDate)
	//end deduct stock from weberp

	if (isset($discharge)) {
		header('Location: ../ambulatory/amb_clinic_discharge.php' . URL_REDIRECT_APPEND . '&pn=' . $encounter . '&pyear=' . date("Y") . '&pmonth=' . date("n") . '&pday=' . date(j) . '&tb=' . str_replace("#", "", $cfg['top_bgcolor']) . '&tt=' . str_replace("#", "", $cfg['top_txtcolor']) . '&bb=' . str_replace("#", "", $cfg['body_bgcolor']) . '&d=' . $cfg['dhtml'] . '&station=' . $station . '&backpath=' . urlencode('../pharmacy_tz/pharmacy_tz_pending_prescriptions.php') . '&dept_nr=' . $dept_nr);
	}

	// Clear the status:
	$mode = "";
	$pn = "";
	$prescription_date = "";
} else {
	// Fall back, either mode is not done or batch number is missing
	// => make a usual form here
	$mode = "";
}
if (empty($mode)) /* Get the pending test requests */ {

	// $sql = "SELECT care_person.pid, " .
	// 	"care_person.selian_pid, " .
	// 	"UPPER(name_last) as name_last, CONCAT(name_first,'  ', name_2) AS name_first, " .
	// 	"care_encounter_prescription.encounter_nr, " .
	// 	"care_encounter_prescription.prescribe_date, " .
	// 	"care_person.pid as batch_nr " .
	// 	"FROM care_encounter_prescription " .
	// 	"inner join care_encounter on care_encounter_prescription.encounter_nr = care_encounter.encounter_nr " .
	// 	"and care_encounter_prescription.mark_os='0' AND (care_encounter_prescription.status='pending' OR care_encounter_prescription.status='' and care_encounter.pharmacy='$locationcode' " . " $and_admission_class) " .
	// 	"inner join care_person on care_encounter.pid = care_person.pid " .
	// 	"inner join care_tz_drugsandservices on care_encounter_prescription.article_item_number=care_tz_drugsandservices.item_id " .
	// 	"and ( care_tz_drugsandservices.purchasing_class = 'drug_list' OR care_tz_drugsandservices.purchasing_class ='supplies' )" .
	// 	"GROUP by care_encounter_prescription.prescribe_date, care_encounter_prescription.encounter_nr, care_person.pid, care_person.selian_pid, name_first, name_last " .
	// 	"ORDER BY care_encounter_prescription.prescribe_date DESC";

		$sql="SELECT care_person.pid, care_person.selian_pid, UPPER(name_last) as name_last, CONCAT(name_first,' ', name_2) AS name_first, care_encounter_prescription.encounter_nr, care_encounter_prescription.prescribe_date, care_person.pid as batch_nr 
		FROM care_encounter_prescription 
		INNER JOIN care_encounter ON care_encounter_prescription.encounter_nr = care_encounter.encounter_nr  
		INNER JOIN care_person ON care_encounter.pid = care_person.pid 
		INNER JOIN care_tz_drugsandservices ON care_encounter_prescription.article_item_number=care_tz_drugsandservices.item_id  
		WHERE  care_encounter_prescription.mark_os='0' AND (care_encounter_prescription.status='pending' OR care_encounter_prescription.status='') and care_encounter.pharmacy='$locationcode' $and_admission_class and ( care_tz_drugsandservices.purchasing_class = 'drug_list' OR care_tz_drugsandservices.purchasing_class ='supplies' ) 
		GROUP by care_encounter_prescription.prescribe_date, care_encounter_prescription.encounter_nr, care_person.pid, care_person.selian_pid, name_first, name_last ORDER BY care_encounter_prescription.prescribe_date DESC";
		

	 

	if ($requests = $db->Execute($sql)) {
		//print_r($requests);

		if ($requests->RecordCount() > 0) {

			/* If request is available, load the date format functions */

			if ($debug) {
				echo $requests;
			}

			require_once $root_path . 'include/inc_date_format_functions.php';

			$batchrows = $requests->RecordCount();
			//if($batchrows && (!isset($batch_nr) || !$batch_nr)) {
			if ($batchrows) {

				if ($debug) {
					echo "<br>got rows...";
				}

				$test_request = $requests->FetchRow();
				/* Check for the patietn number = $pn. If available get the patients data */
				$requests->MoveFirst();
				/*
					                  while($test_request=$requests->FetchRow())
					                  echo $test_request['encounter_nr']."<br>";
				*/
				if (empty($pn)) {
					$pn = $test_request['encounter_nr'];
				}

				if (empty($prescription_date)) {
					$prescription_date = $test_request['prescribe_date'];
				}

				if (empty($batch_nr)) {
					$batch_nr = $test_request['batch_nr'];
				}

				if ($debug) {
					echo $batch_nr . "<br>" . $prescription_date . "<br>";
				}

			}
		} else {
			$NO_PENDING_PRESCRIPTIONS = TRUE;
		}
	} else {
		echo "<p>$sql<p>$LDDbNoRead";
		exit;
	}
	$mode = "show";
}

$lang_tables[] = 'billing.php';

require $root_path . 'include/inc_front_chain_lang.php';

require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader2.inc.php';

require "gui/gui_pharmacy_tz_pending_prescriptions.php";

require_once $root_path . 'main_theme/footer.inc.php';

?>
