<?php

proc_nice(-10);

$procLimit = 100000;
$load = sys_getloadavg();

if ($load[0] > 0.70 || memory_get_usage() > $procLimit) {
	sleep(3);
}

$root_path = '/home/israel/htdocs/CareMD/';
$root_path = '/var/www/html/CareMD/';

require_once $root_path . 'vendor/autoload.php';

use Concerto\DirectoryMonitor\RecursiveMonitor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use React\EventLoop\Factory as EventLoopFactory;

$dirWatch = $root_path . 'machinestests/labmachines/dh76/';

$loop = EventLoopFactory::create();
$monitor = new RecursiveMonitor($loop, $dirWatch);

$monitor->on('create', function ($path, $root) {

	$root_path = '/var/www/html/CareMD/';
	$dirWatch = $root_path . 'machinestests/labmachines/dh76/';

	require_once ($root_path . 'include/inc_init_main.php');

	require_once ($root_path . 'include/care_api_classes/class_labmachine.php');
	$labMachine = new labMachine;

	$filePath = $root . "/" . $path;
	$directoryPath = $dirWatch . "SampleExport_graphic";

	if (file_exists($filePath)) {

		$inputFileType = IOFactory::identify($filePath);;

		$reader = IOFactory::createReader($inputFileType);
		$spreadsheet = $reader->load($filePath);

		$sheetData = $spreadsheet->getActiveSheet();
		$rows = $sheetData->toArray();

		// Single Results
		if (count($rows) <= 2) {

			$encounter_nr = 0;
			$columnNames = $rows[0];
			$columnValues = $rows[1];
			$batch_nr = $rows[1][1];
			$delivery = $rows[1][15];
			$deliveryDate = date("Y-m-d", strtotime($delivery));
			$deliveryTime = date("H:i:s", strtotime($delivery));
			$first_name = $last_name = $gender = "";

			if (!is_numeric($batch_nr)) {
				$columnNames = explode(",", $rows[0][15]);
				$columnValues = explode(",", $rows[1][4]);
				if (count($columnValues) < 10) {
					$columnValues = explode(",", $rows[1][3]);
				}
				$batch_nr = explode(",", $rows[1][0])[1];
				$delivery = explode(",", $rows[1][2]);
				$name = explode(",", $rows[1][0]);
				$first_name = $name[2];
				$last_name = $name[3];
				$deliveryDate = date('Y-m-d', strtotime($delivery[4] . " " . $delivery[0]));
				$deliveryTime = date('H:i:s', strtotime($delivery[4] . " " . $delivery[0]));
			}

			$batch_nr = trim($batch_nr);
			$batch_nr = (int) $batch_nr;

			$result = [];

			foreach ($columnNames as $columnKey => $columnName) {
				foreach ($columnValues as $valueKey => $columnValue) {
					if ($columnKey === $valueKey) {
						$column = strtolower($columnName);
						$column = str_replace("%", "1", $column);
						$column = str_replace("#", "2", $column);
						$column = str_replace(" ", "_", $column);
						$column = str_replace(".", "", $column);
						$column = str_replace("-", "_", $column);
						if (@$column) {
							if (array_key_exists('wbc', $result) && $column == 'wbc') {
								continue;
							}
							$result[$column] = $columnValue;
						}
					}
				}
			}

			$result['delivery_date'] = $deliveryDate;
			$result['delivery_time'] = $deliveryTime;
			$result['first_name'] = $first_name;
			$result['last_name'] = $last_name;
			$result['gender'] = $gender;

			$inserted = $labMachine->performMachineInsertion($batch_nr, $result, $root_path);
			// print_r($inserted);
			// delete single file result
			if (file_exists($filePath) && $inserted) {
				unlink($filePath);
			}
		} else {

			// Multi Results

			$length = (int) count($rows[0]);
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
						$dateDetails = explode(",", $row[1]);
						$batch_nr = $result['med_rec_no'] = @($patientDetails && @$patientDetails[1]) ? $patientDetails[1] : 0;

						if (@$patientDetails[14]) {

							$date = str_replace("/", "-", $patientDetails[14]);
							$date .= ":00:00";

							$dateFormat = explode(" ", $date)[0];

							$result['delivery_date'] = date('Y-m-d', strtotime($dateFormat));
							$result['delivery_time'] = explode(" ", $date)[1];

						} else {
							$dateDetails = explode(",", $row[1]);
							$date = str_replace("/", "-", $dateDetails[7]);
							$dateFormat = explode(" ", $date)[0];
							$parts = explode('-', $dateFormat);
							$result['delivery_date'] = "";
							$jinadate = explode(" ", $date)[1];
							$result['delivery_time'] = $result['delivery_date'] . " " . $jinadate;
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

								if (@$column) {
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
			} else {
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
			// relete multiple file results
			if (@$importedResults) {
				unlink($filePath);
			}
		}
	}
});

// $monitor->on('delete', function($path, $root) {
// 	echo "Deleted: {$path} in {$root}\n";
// });

// $monitor->on('modify', function($path, $root) {
// 	echo "Modified: {$path} in {$root}\n";
// });

// $monitor->on('write', function($path, $root) {
// 	echo "Wrote: {$path} in {$root}\n";
// });

$loop->run();
