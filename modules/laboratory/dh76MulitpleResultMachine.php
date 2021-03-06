<?php

$sheetData = $spreadsheet->getActiveSheet();
$rows = $sheetData->toArray();

if (@$rows) {

	$length =  (int)count($rows[0]);
	// File not edited
	if ($length <= 7) {
		$columnNames = explode(",", $rows[0][0]);

		foreach ($rows as $rowKey => $row) {
			$result = [];
			if ($rowKey > 0) {

 				$columnValues = explode(",", $row[6]);
                if (count($columnValues) < 10) {
                	$columnValues = explode(",", $row[4]);
                }

				$patientDetails = explode(",", $row[0]);
				$batch_nr = $result['med_rec_no'] = @($patientDetails && @$patientDetails[1])?$patientDetails[1]:0;
				if(@$patientDetails[14]) {

					$date = str_replace("/", "-", $patientDetails[14]);
					$date .=":00:00";

					$dateFormat = explode(" ", $date)[0];

					$result['delivery_date'] = date('Y-m-d', strtotime($dateFormat));
					$result['delivery_time'] = explode(" ", $date)[1];

				}else {
 					$dateDetails = explode(",", $row[1]);
 					$date = str_replace("/", "-", $dateDetails[7]);
                    $dateFormat = explode(" ", $date)[0];
					$parts = explode('-',$dateFormat);
					$year = @$parts[2]?$parts[2]:date('Y');
					$month = @$parts[0]?$parts[0]:date('m');
					$day = @$parts[1]?$parts[1]:date('d');
					$result['delivery_date'] = $year . '-' . $month . '-' . $day;
					$datetime = @(explode(" ", $date)[1])?explode(" ", $date)[1]:"00:00:00";
                    $result['delivery_time'] = $result['delivery_date']." ". $datetime;
				}
				
				$result['first_name'] = $patientDetails[2];
				$result['last_name'] = $patientDetails[3];
				$result['gender'] = $patientDetails[4];


				foreach ($columnValues as $columnValueKey => $columnValue) {
					$columnKeyValue = $columnValueKey + 23;
					if (@$columnNames[$columnKeyValue]) {
						$column = str_replace("%", "1", strtolower($columnNames[$columnKeyValue]));
						$column = str_replace("#", "2", $column);
						$column = str_replace(" ", "_", $column);
						$column = str_replace(".", "", $column);
						$column = str_replace("-", "_", $column);

						if(@$column){
							if (array_key_exists('wbc', $result) && $column == 'wbc') {
		    					continue;
		    				}
							$result[$column] = $columnValue;	
						}
						$result['batch_nr'] = $batch_nr;
					}
				}

				// perfom Insertion

				if (@$result && $batch_nr > 0) {
					
					$uploaded = "No";
					$inserted = $labMachine->performMachineInsertion($batch_nr, $result, $root_path);
					if ($inserted) {
						$uploaded = "Yes";
					}
					$result['uploaded'] = $uploaded;
					$importedResults[] = $result;	
				}


			}
			
		}

		
	}else{
		// edited
		$columnNames = $rows[0];
		foreach ($rows as $rowKey => $row) {
			if ($rowKey > 0) {
				$result = [];
				foreach ($row as $key => $value) {
					$column = str_replace("%", "1", strtolower($columnNames[$key]));
					$column = str_replace("#", "2", $column);
					$column = str_replace(" ", "_", $column);
					$column = str_replace(".", "", $column);
					$column = str_replace("-", "_", $column);

					$result[$column] = $value;
					
				}

				// perform insertion

				if (@$result) {
					$batch_nr = $result['med_rec_no'];

					if (@$batch_nr) {
						$uploaded = "No";
						$inserted = $labMachine->performMachineInsertion($batch_nr, $result, $root_path);
						if ($inserted) {
							$uploaded = "Yes";
						}
						$result['uploaded'] = $uploaded;
						$importedResults[] = $result;	
					}
				}

				
			}
		}

	}
}
?>


