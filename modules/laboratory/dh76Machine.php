<?php

$sheetData = $spreadsheet->getActiveSheet();
$rows = $sheetData->toArray();
$columnNames = $rows[0];
$columnValues = $rows[1];


$batch_nr = $rows[1][1];
$delivery = $rows[1][15];

$deliveryDate = date("Y-m-d", strtotime($delivery));
$deliveryTime = date("H:i:s", strtotime($delivery));

if (!is_numeric($batch_nr)) {
	$columnNames = explode(",", $rows[0][15]);
	$columnValues = explode(",", $rows[1][4]);
	if (count($columnValues) < 10) {
		$columnValues = explode(",", $rows[1][3]);	
	}
	$batch_nr = explode(",", $rows[1][0])[1];
	$delivery = explode(",", $rows[1][2]);
	$deliveryDate = date('Y-m-d', strtotime($delivery[4] . " " . $delivery[0]));
	$deliveryTime = date('H:i:s', strtotime($delivery[4] . " " . $delivery[0]));
}

$fileBatchNr= $batch_nr;

$testResults = [];

if (@$fileBatchNr) {

	foreach ($columnNames as $columnKey => $columnName) {
		foreach ($columnValues as $valueKey => $columnValue) {
			if ($columnKey === $valueKey) {
				$column = str_replace("%", "1", strtolower($columnName));
				$column = str_replace("#", "2", $column);
				$column = str_replace(" ", "_", $column);
				$column = str_replace(".", "", $column);
				$column = str_replace("-", "_", $column);
				if(@$column){
					if (array_key_exists('wbc', $testResults) && $column == 'wbc') {
    					continue;
    				}
					$testResults[$column] = $columnValue;	
				}
			}
		}
	}


	foreach ($testResults as $resultKey => $testResult) {
		foreach ($reqTests as $request) {

			$requestNames = explode("__", $request);
			$lastName = @($requestNames[1])?$requestNames[1]:"";

			if(@$lastName) {
				$resultName = "_" . $resultKey . "__".$lastName;

				if ($resultName == $request) {
					$labresult = array(
	                    'name' => $resultName,
	                    'amount' => $testResult,
	                    'description' => $resultName
	                );

	                $labResults[] = $labresult;
				}
				
			}
		}
	}

}

?>


