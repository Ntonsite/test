<?php

$sheetData = $spreadsheet->getActiveSheet();
$rows = $sheetData->toArray();

if (@$rows) {

	$length = (int) count($rows[0]);

	// File not edited
	if ($length <= 4) {

		foreach ($rows as $rowKey => $row) {
			if ($rowKey > 0) {
				$result = array();
				$uploaded = "No";

				$testResult = preg_split('/\s+/', $row[0]);

				if (count($testResult) > 2) {

					$unformattedDate = $testResult[7] . " " . $testResult[8];
					// $date = new DateTime($unformattedDate);
					$unformattedDate = explode("/", $testResult[7]);
					$year = @$unformattedDate[2] ? $unformattedDate[2] : date("Y");
					$month = @$unformattedDate[0] ? $unformattedDate[0] : date("M");
					$day = @$unformattedDate[1] ? $unformattedDate[1] : date("d");
					$month = str_pad($month, 4, '0', STR_PAD_LEFT);
					$day = str_pad($day, 4, '0', STR_PAD_LEFT);
					$result['delivery_date'] = $year . "-" . $month . "-" . $day;
					$result['delivery_time'] = $testResult[8];
					$result['first_name'] = $testResult[4];
					$result['last_name'] = $testResult[5];
					$result['gender'] = $testResult[6];

					if (count($testResult) >= 31) {
						$result['med_rec_no'] = $batch_nr = $testResult[22];
						$result['test_name'] = strtolower($testResult[12]);
						$result['test_value'] = strtolower($testResult[15]);
					} else {
						$result['med_rec_no'] = $batch_nr = $testResult[21];
						$result['test_name'] = strtolower($testResult[11]);
						$result['test_value'] = strtolower($testResult[14]);
					}

					$inserted = $labMachine->performAccent220sInsertion($result, $root_path);

					if ($inserted) {
						$uploaded = "Yes";
					}
					$result['uploaded'] = $uploaded;
					$importedResults[] = $result;

				}

			}

		}
	} else {
		// File Edited
		foreach ($rows as $rowKey => $row) {
			if ($rowKey > 0) {

				$result = [];

				$date = new \DateTime($row[6]);
				$result['delivery_date'] = $date->format('Y-m-d');
				$result['delivery_time'] = $date->format('H:i:s');
				$name = explode(" ", $row[4]);
				$result['first_name'] = @($name[0]) ? $name[0] : "";
				$result['last_name'] = @($name[1]) ? $name[1] : "";
				$result['gender'] = $row[5];

				$result['med_rec_no'] = $row[18];
				$result['test_name'] = strtolower($row[8]);
				$result['test_value'] = strtolower($row[11]);

				$inserted = $labMachine->performAccent220sInsertion($result, $root_path);
				$uploaded = "No";
				if ($inserted) {
					$uploaded = "Yes";
				}
				$result['uploaded'] = $uploaded;
				$importedResults[] = $result;

			}
		}

	}
}
?>


