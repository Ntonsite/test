<?php

require_once $root_path . 'include/care_api_classes/class_core.php';
include_once $root_path . 'include/inc_environment_global.php';

class Reporting {

	function __construct() {

	}

	function getPatientInsurancePrice($insurance_id, $sub_insurance_id) {
		global $db;
		$priceRow = [];

		if ($sub_insurance_id > 0) {
			$result = $db->Execute("SELECT * FROM care_tz_drugsandservices_description WHERE ID = $sub_insurance_id LIMIT 1");
			$priceRow = $result->FetchRow();
		} else {
			$result = $db->Execute("SELECT * FROM care_tz_drugsandservices_description WHERE company_id = $insurance_id LIMIT 1 ");
			if (@$result && $result->RecordCount()) {
				$priceRow = $result->FetchRow();
			}
		}
		return $priceRow;
	}

	function getPatientFromEncounterNr($encounter_nr) {
		global $db;
		$pidresult = $db->Execute("SELECT pid FROM care_encounter WHERE encounter_nr = $encounter_nr LIMIT 1");
		$pidRow = $pidresult->FetchRow();
		$pid = $pidRow['pid'];

		$result = $db->Execute("SELECT insurance_ID, sub_insurance_id FROM care_person WHERE pid = $pid LIMIT 1");
		$patient = $result->FetchRow();
		return $patient;
	}

	function getTotalArrayAmountByColumn($records, $columnName, $columnValue, $columnAmount) {
		$total = 0;
		foreach ($records as $record) {
			if ($record[$columnName] == $columnValue) {
				$total += $record[$columnAmount];
			}
		}
		return $total;
	}

	function getTotalPatientAmountPaidInPrescription($prescriptions_nr) {
		global $db;
		$totalAmount = 0;
		$prescriptionSQL = "SELECT  SUM(amount*price) as total_amount FROM care_tz_billing_archive_elem WHERE prescriptions_nr = $prescriptions_nr LIMIT 1";
		$prescriptionResult = $db->Execute($prescriptionSQL);

		if ($prescriptionResult->RecordCount()) {
			while ($prescriptionRow = $prescriptionResult->FetchRow()) {
				$totalAmount += $prescriptionRow['total_amount'];
			}
		}
		return $totalAmount;
	}

	public function getPatientPendingPaidLabSubTests($startDate, $endDate, $price_column, $pid) {
		global $db;
		$rows = [];
		if (!empty($price_column)) {

			$labRequestsSQL = "SELECT ce.pid, ctr.send_date, ctrs.encounter_nr, ctrs.batch_nr, ctrs.item_id, ctr.bill_number, ctr.status
            FROM care_test_request_chemlabor_sub ctrs
            LEFT JOIN care_encounter ce
            ON ce.encounter_nr = ctrs.encounter_nr
            LEFT JOIN care_test_request_chemlabor ctr
            ON ctr.batch_nr = ctrs.batch_nr
            WHERE ctrs.encounter_nr IN (SELECT encounter_nr FROM care_test_request_chemlabor WHERE send_date BETWEEN '$startDate' AND '$endDate' )
            AND ctr.send_date BETWEEN '$startDate' AND '$endDate'
            AND ce.encounter_date BETWEEN '$startDate' AND '$endDate'
            AND ce.pid = '$pid'
            AND ctr.bill_number > 0
            AND ctrs.deleted = 0
            AND (ctr.status = 'pending' OR ctr.status = '')  GROUP BY ctr.batch_nr ";

			$labReqResult = $db->Execute($labRequestsSQL);

			if ($labReqResult->RecordCount()) {
				while ($lr = $labReqResult->FetchRow()) {
					$rows[] = $lr;
				}
			}
		}

		return $rows;
	}

	public function getPatientPendingUnpaidLabSubTests($startDate, $endDate, $price_column, $pid) {
		global $db;
		$rows = [];
		if (!empty($price_column)) {

			$labRequestsSQL = "SELECT ce.pid, ctr.send_date, ctrs.encounter_nr, ctrs.batch_nr,  ctrs.item_id, ctr.bill_number, ctr.status
            FROM care_test_request_chemlabor_sub ctrs
            LEFT JOIN care_encounter ce
            ON ce.encounter_nr = ctrs.encounter_nr
            LEFT JOIN care_test_request_chemlabor ctr
            ON ctr.batch_nr = ctrs.batch_nr
            WHERE ctrs.encounter_nr IN (SELECT encounter_nr FROM care_test_request_chemlabor WHERE send_date BETWEEN '$startDate' AND '$endDate' )
            AND ctr.send_date BETWEEN '$startDate' AND '$endDate'
            AND ce.encounter_date BETWEEN '$startDate' AND '$endDate'
            AND ce.pid = '$pid'
            AND ctr.bill_number = 0
            AND ctrs.deleted = 0
            AND (ctr.status = 'pending' OR ctr.status = '') GROUP BY ctr.batch_nr ";

			$labReqResult = $db->Execute($labRequestsSQL);

			if ($labReqResult->RecordCount()) {
				while ($lr = $labReqResult->FetchRow()) {
					$rows[] = $lr;
				}
			}
		}

		return $rows;
	}

	public function getPatientServedPaidLabSubTests($startDate, $endDate, $price_column, $pid) {
		global $db;
		$rows = [];
		if (!empty($price_column)) {

			$labRequestsSQL = "SELECT ce.pid, ctr.send_date, ctrs.encounter_nr, ctrs.batch_nr, ctrs.item_id, ctr.bill_number, ctr.status
            FROM care_test_request_chemlabor_sub ctrs
            LEFT JOIN care_encounter ce
            ON ce.encounter_nr = ctrs.encounter_nr
            LEFT JOIN care_test_request_chemlabor ctr
            ON ctr.batch_nr = ctrs.batch_nr
            WHERE ctrs.encounter_nr IN (SELECT encounter_nr FROM care_test_request_chemlabor WHERE send_date BETWEEN '$startDate' AND '$endDate' )
            AND ctr.send_date BETWEEN '$startDate' AND '$endDate'
            AND ce.encounter_date BETWEEN '$startDate' AND '$endDate'
            AND ce.pid = '$pid'
            AND ctr.bill_number > 0
            AND ctrs.deleted = 0
            AND ctr.status = 'done'  GROUP BY ctr.batch_nr ";

			$labReqResult = $db->Execute($labRequestsSQL);

			if ($labReqResult->RecordCount()) {
				while ($lr = $labReqResult->FetchRow()) {
					$rows[] = $lr;
				}
			}
		}

		return $rows;
	}

	public function getPatientServedUnpaidLabSubTests($startDate, $endDate, $price_column, $pid) {
		global $db;
		$rows = [];
		if (!empty($price_column)) {

			$labRequestsSQL = "SELECT ce.pid, ctr.send_date, ctrs.encounter_nr, ctrs.batch_nr, ctrs.item_id, ctr.bill_number, ctr.status
            FROM care_test_request_chemlabor_sub ctrs
            LEFT JOIN care_encounter ce
            ON ce.encounter_nr = ctrs.encounter_nr
            LEFT JOIN care_test_request_chemlabor ctr
            ON ctr.batch_nr = ctrs.batch_nr
            WHERE ctrs.encounter_nr IN (SELECT encounter_nr FROM care_test_request_chemlabor WHERE send_date BETWEEN '$startDate' AND '$endDate' )
            AND ctr.send_date BETWEEN '$startDate' AND '$endDate'
            AND ce.encounter_date BETWEEN '$startDate' AND '$endDate'
            AND ce.pid = '$pid'
            AND ctr.bill_number = 0
            AND ctrs.deleted = 0
            AND ctr.status = 'done'  GROUP BY ctr.batch_nr ";

			$labReqResult = $db->Execute($labRequestsSQL);

			if ($labReqResult->RecordCount()) {
				while ($lr = $labReqResult->FetchRow()) {
					$rows[] = $lr;
				}
			}
		}

		return $rows;
	}

	public function getPatientPrescriptions($start_date, $end_date, $priceColumn, $pid, $status) {
		$startDate = $start_date . " 00:00:00";
		$endDate = $end_date . " 23:59:59";

		global $db;
		$totalAmount = 0;
		$singleRows = [];
		$prescrRows = "<table class='table datatable table-condensed table-bordered' ><tr><td>Encounter</td><td>Date</td><td>Drug Name</td><td>Unit Price</td><td>Total Dosage</td><td>Prescriber</td><td>Issuer</td><td>Amount</td></tr>";
		$prescriptionsSQL = "
            SELECT  encounter_nr, article, article_item_number, total_dosage, prescribe_date, prescriber, taken, issuer, bill_number, cd.purchasing_class
            FROM care_encounter_prescription cp
            LEFT JOIN care_tz_drugsandservices cd
            ON cp.article_item_number = cd.item_id
            WHERE (
                cd.purchasing_class != 'labtest'
                AND cd.purchasing_class != 'xray'
            )
            AND cp.encounter_nr IN (SELECT encounter_nr FROM care_encounter WHERE pid = '$pid') AND cp.prescribe_date BETWEEN '$startDate' AND  '$endDate' ";
		if ($status == "pending") {
			$prescriptionsSQL .= " AND (cp.status = '' OR cp.status='pending')";
		} else {

			$prescriptionsSQL .= " AND cp.status = 'done'";
		}

		$prescriptionsResult = $db->Execute($prescriptionsSQL);

		if ($prescriptionsResult->RecordCount()) {
			while ($pr = $prescriptionsResult->FetchRow()) {
				$drugPrice = $this->getDrugPrice($priceColumn, $pr['article_item_number']);
				$amount = $drugPrice * $pr['total_dosage'];
				$prescrRows .= "<tr><td>" . $pr['encounter_nr'] . "</td><td>" . date('d/m/Y', strtotime($pr['prescribe_date'])) . "</td><td>" . $pr['article'] . "</td><td>" . number_format($drugPrice, 2) . "</td><td>" . $pr['total_dosage'] . "</td><td>" . $pr['prescriber'] . "</td><td>" . $pr['issuer'] . "</td><td>" . number_format($amount) . "</td></tr>";

				$totalAmount += $amount;
				$pr['row_amount'] = $amount;
				$pr['drug_price'] = $drugPrice;
				if ($amount > 0) {
					$singleRows[] = $pr;
				}
			}
		}
		$prescrRows .= "</table>";
		$data['rows'] = $prescrRows;
		$data['singleRows'] = $singleRows;
		$data['amount'] = $totalAmount;
		return $data;
	}

	public function getDrugPrice($priceColumn, $item_id) {
		global $db;
		$amount = 0;
		$drugSQL = "SELECT  $priceColumn FROM care_tz_drugsandservices WHERE item_id = '$item_id' LIMIT 1";
		$drugResult = $db->Execute($drugSQL);
		if (@$drugResult && $drugResult->RecordCount()) {
			$drugRow = $drugResult->FetchRow();
			$amount = @($drugRow[$priceColumn]) ? $drugRow[$priceColumn] : 0;
		}
		return $amount;
	}

	public function getDrug($priceColumn, $item_id) {
		global $db;
		$amount = 0;
		$name = "";
		$drugSQL = "SELECT  $priceColumn, item_description FROM care_tz_drugsandservices WHERE item_id = '$item_id' LIMIT 1";
		$drugResult = $db->Execute($drugSQL);
		if (@$drugResult && $drugResult->RecordCount()) {
			$drugRow = $drugResult->FetchRow();
			$amount = @($drugRow[$priceColumn]) ? $drugRow[$priceColumn] : 0;
			$name = @($drugRow['item_description']) ? $drugRow['item_description'] : '';
		}
		$data['amount'] = $amount;
		$data['name'] = $name;
		return $data;
	}

	function array_unique_deep($array, $key) {
		$values = array();
		foreach ($array as $k1 => $row) {
			foreach ($row as $k2 => $v) {
				if ($k2 == $key) {
					$values[$k1] = $v;
					continue;
				}
			}
		}
		return array_unique($values);
	}

}

?>