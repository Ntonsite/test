<?php

class LabMachine {

	function __construct() {

	}

	function performMachineInsertion($batch_nr, $testResults, $root_path) {
		// Create connection
		$inserted = 0;

		require $root_path . 'include/inc_init_main.php';
		$conn = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$encounter_nr = 0;
		$deliveryDate = date('Y-m-d', strtotime($testResults['delivery_time']));
		$deliveryTime = date('H:i:s', strtotime($testResults['delivery_time']));

		$rTSql = "SELECT * FROM care_test_request_chemlabor_sub
			    		WHERE batch_nr='$batch_nr'
			    		AND care_test_request_chemlabor_sub.deleted = 0
			    		ORDER BY sort_order";
		$stmt = $conn->prepare($rTSql);
		$stmt->execute();
		$reqResult = $stmt->get_result();
		$requests = array();
		while ($row = $reqResult->fetch_array()) {
			$requests[] = $row;
		}
		if (!empty($requests)) {
			$encounter_nr = $requests[0]['encounter_nr'];
			$inserted = 1;
		}
		$stmt->close();

		if (@$testResults && $encounter_nr > 0) {

			$batchEnSQL = "SELECT batch_nr FROM care_test_findings_chemlab WHERE encounter_nr = '$encounter_nr' AND job_id = '$batch_nr' ";
			$stmt = $conn->prepare($batchEnSQL);
			$stmt->execute();
			$batchResult = $stmt->get_result();
			$stmt->close();

			if (@$batchResult && $batchResult->num_rows > 0) {
				$batchResult = $batchResult->fetch_array();
				$preBatchNr = $batchResult['batch_nr'];
			} else {
				$insBatchSQL = " INSERT INTO care_test_findings_chemlab  (encounter_nr, test_date ,test_time, job_id) VALUES('$encounter_nr', '$deliveryDate', '$deliveryTime', '$batch_nr')";
				$stmt = $conn->prepare($insBatchSQL);
				$stmt->Execute();
				$stmt->close();

				$batchEnSQL = "SELECT batch_nr FROM care_test_findings_chemlab WHERE encounter_nr = '$encounter_nr' AND job_id = '$batch_nr' ";
				$stmt = $conn->prepare($batchEnSQL);
				$stmt->Execute();
				$batchResult = $stmt->get_result();
				$stmt->close();

				if (@$batchResult && $batchResult->num_rows > 0) {
					$batchResult = $batchResult->fetch_array();
					$preBatchNr = $batchResult['batch_nr'];
				}
			}

			foreach ($testResults as $resultKey => $testResult) {
				foreach ($requests as $request) {
					$requestName = $request['paramater_name'];

					$requestNames = explode("__", $request['paramater_name']);
					$lastName = @($requestNames[1]) ? $requestNames[1] : "";

					if (@$lastName) {
						$resultName = "_" . $resultKey . "__" . $lastName;
						if ($resultName == $requestName) {
							$sort_order = $request['sort_order'];
							$labresult = array(
								'name' => $resultName,
								'amount' => $testResult,
							);

							$deleteSQL = "DELETE FROM care_test_findings_chemlabor_sub WHERE job_id = '$batch_nr' AND encounter_nr = '$encounter_nr' AND paramater_name = '$resultName' ";

							$stmt = $conn->prepare($deleteSQL);
							$stmt->Execute();
							$stmt->close();

							$insBatchSQL = " INSERT INTO care_test_findings_chemlabor_sub (batch_nr, job_id, encounter_nr, paramater_name ,parameter_value, test_date, test_time, create_id, sort_order) VALUES('$preBatchNr', '$batch_nr', '$encounter_nr', '$resultName', '$testResult', '$deliveryDate', '$deliveryTime', 'dh76 Machine', '$sort_order')";

							$stmt = $conn->prepare($insBatchSQL);
							$saved = $stmt->Execute();
							$stmt->close();
						}

					}
				}
			}

			// set Signal Event Color

			$color = 'brown';
			$event_table = 'care_encounter_event_signaller';
			$status = 2;
			$sql = "SELECT encounter_nr, $color FROM $event_table WHERE encounter_nr=$encounter_nr";
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$ergebnis = $stmt->get_result();
			$stmt->close();

			$nogo = false;

			if (@$ergebnis && $ergebnis->num_rows > 0) {
				$row = $ergebnis->fetch_array();
				if ($row[1] != $status) {
					$sql = "UPDATE $event_table SET $color ='$status' WHERE encounter_nr=$encounter_nr";
				}

				$stmt = $conn->prepare($sql);
				$stmt->Execute();
				if (!$stmt->affected_rows > 0) {
					$nogo = true;
				}
				$stmt->close();

			} else {
				$nogo = true;
			}

			if ($nogo) {
				$sql = "INSERT INTO " . $event_table . " ( encounter_nr, " . $color . ") VALUES ( " . $encounter_nr . ", " . $status . ")";
				$stmt = $conn->prepare($sql);
				$stmt->Execute();
				$stmt->close();
			}

			// done
			$sql = "UPDATE care_test_request_chemlabor
				SET status = 'done'
				WHERE batch_nr = '" . $batch_nr . "'";
			$stmt = $conn->prepare($sql);
			$stmt->Execute();
			$stmt->close();
		}
		$conn->close();
		return $inserted;
	}

	function performAccent220sInsertion($testResult, $root_path) {
		// Create connection
		$batch_nr = $testResult['med_rec_no'];

		require $root_path . 'include/inc_init_main.php';
		$conn = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$encounter_nr = 0;

		$rTSql = "SELECT * FROM caredb_tohs.care_test_request_chemlabor_sub
			    		WHERE batch_nr='$batch_nr'
			    		AND care_test_request_chemlabor_sub.deleted = 0
			    		ORDER BY sort_order";
		$stmt = $conn->prepare($rTSql);
		$stmt->execute();
		$reqResult = $stmt->get_result();
		$requests = array();
		while ($row = $reqResult->fetch_array()) {
			$requests[] = $row;
		}
		if (!empty($requests)) {
			$encounter_nr = $requests[0]['encounter_nr'];
		}
		$stmt->close();

		$testValue = $testResult['test_value'];
		$deliveryDate = trim($testResult['delivery_date']);
		$deliveryTime = trim($testResult['delivery_time']);
		$inserted = 0;
		if ($encounter_nr > 0) {

			$batchEnSQL = "SELECT batch_nr FROM care_test_findings_chemlab WHERE encounter_nr = '$encounter_nr' AND job_id = '$batch_nr' ";
			$stmt = $conn->prepare($batchEnSQL);
			$stmt->execute();
			$batchResult = $stmt->get_result();
			$stmt->close();

			if (@$batchResult && $batchResult->num_rows > 0) {
				$batchResult = $batchResult->fetch_array();
				$preBatchNr = $batchResult['batch_nr'];
			} else {
				$insBatchSQL = " INSERT INTO care_test_findings_chemlab  (encounter_nr, test_date ,test_time, job_id) VALUES('$encounter_nr', '$deliveryDate', '$deliveryTime', '$batch_nr')";
				$stmt = $conn->prepare($insBatchSQL);
				$stmt->Execute();
				$stmt->close();

				$batchEnSQL = "SELECT batch_nr FROM care_test_findings_chemlab WHERE encounter_nr = '$encounter_nr' AND job_id = '$batch_nr' ";
				$stmt = $conn->prepare($batchEnSQL);
				$stmt->Execute();
				$batchResult = $stmt->get_result();
				$stmt->close();

				if (@$batchResult && $batchResult->num_rows > 0) {
					$batchResult = $batchResult->fetch_array();
					$preBatchNr = $batchResult['batch_nr'];
				}
			}
			// print_r($preBatchNr);die;

			foreach ($requests as $request) {
				$requestName = $request['paramater_name'];
				$sort_order = $request['sort_order'];
				$requestNames = explode("__", $request['paramater_name']);
				$lastName = @($requestNames[1]) ? $requestNames[1] : "";

				if (@$lastName) {

					$testName = str_replace(" ", "_", $testResult['test_name']);
					$testName = str_replace(".", "", $testName);
					$testName = str_replace("-", "_", $testName);
					$resultName = "_" . $testName . "__" . $lastName;

					if ($requestName === $resultName) {

						$inserted = 1;
						$deleteSQL = "DELETE FROM care_test_findings_chemlabor_sub WHERE job_id = '$batch_nr' AND encounter_nr = '$encounter_nr' AND paramater_name = '$resultName' ";

						$stmt = $conn->prepare($deleteSQL);
						$stmt->Execute();
						$stmt->close();

						$insBatchSQL = " INSERT INTO care_test_findings_chemlabor_sub (batch_nr, job_id, encounter_nr, paramater_name ,parameter_value, test_date, test_time, create_id, sort_order) VALUES('$preBatchNr', '$batch_nr', '$encounter_nr', '$resultName', '$testValue', '$deliveryDate', '$deliveryTime', 'Accent 220s Machine', '$sort_order')";

						$stmt = $conn->prepare($insBatchSQL);
						$saved = $stmt->Execute();
						$stmt->close();
					}

				}
			}

			// set Signal Event Color

			$color = 'brown';
			$event_table = 'care_encounter_event_signaller';
			$status = 2;
			$sql = "SELECT encounter_nr, $color FROM $event_table WHERE encounter_nr=$encounter_nr";
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$ergebnis = $stmt->get_result();
			$stmt->close();

			$nogo = false;

			if (@$ergebnis && $ergebnis->num_rows > 0) {
				$row = $ergebnis->fetch_array();
				if ($row[1] != $status) {
					$sql = "UPDATE $event_table SET $color ='$status' WHERE encounter_nr=$encounter_nr";
				}

				$stmt = $conn->prepare($sql);
				$stmt->Execute();
				if (!$stmt->affected_rows > 0) {
					$nogo = true;
				}
				$stmt->close();

			} else {
				$nogo = true;
			}

			if ($nogo) {
				$sql = "INSERT INTO " . $event_table . " ( encounter_nr, " . $color . ") VALUES ( " . $encounter_nr . ", " . $status . ")";
				$stmt = $conn->prepare($sql);
				$stmt->Execute();
				$stmt->close();
			}

			// done
			$sql = "UPDATE care_test_request_chemlabor
				SET status = 'done'
				WHERE batch_nr = '" . $batch_nr . "'";
			$stmt = $conn->prepare($sql);
			$stmt->Execute();
			$stmt->close();

		}

		$conn->close();
		return $inserted;
	}
}