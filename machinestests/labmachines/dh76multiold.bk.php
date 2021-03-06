#!/usr/local/bin/php

<?php
proc_nice(-10);

error_reporting(E_ALL);
ini_set('display_errors', 1);

$procLimit = 100000;

$root_path = '/var/www/html/CareMD/';

require ($root_path . 'include/inc_init_main.php');
require_once $root_path.'vendor/autoload.php';
require($root_path . 'include/care_api_classes/class_labmachine.php');
$labMachine = new labMachine;


use PhpOffice\PhpSpreadsheet\IOFactory;

// directory to watch
$dirWatch = $root_path . 'machinestests/labmachines/dh76multi/';

// Open an inotify instance
$inoInst = inotify_init();

// this is needed so inotify_read while operate in non blocking mode
stream_set_blocking($inoInst, 0);

// watch if a file is created or deleted in our directory to watch
$watch_id = inotify_add_watch($inoInst, $dirWatch, IN_MOVED_TO | IN_CREATE);


// not the best way but sufficient for this example :-)
while(true){

	$load = sys_getloadavg();

    if ($load[0] > 0.95 || memory_get_usage() > $procLimit) {
        sleep(3);
    }

  	// read events (
  	// which is non blocking because of our use of stream_set_blocking
 	$files = inotify_read($inoInst);	
  	
//SampleExport_graphic
  	// output data

 	if (@$files) {

 		foreach ($files as $file) {

	 		$fileName = $file['name'];
			$timestamp = time();
	 		$originalFilePath = $dirWatch . $fileName;
			$directoryPath = $dirWatch. "SampleExport_graphic";
			$filePath = $dirWatch.'patientfile'.$timestamp.'.csv';
			if($fileName === 'SampleExport.csv'){
				// rename($originalFilePath,$filePath );
			}
			$filePath = $originalFilePath;

	 		$encounter_nr = 0;
	 		
			if (file_exists($filePath)) {


		 		$inputFileType = IOFactory::identify($filePath);;
		        
			    $reader = IOFactory::createReader($inputFileType);
			    $spreadsheet = $reader->load($filePath);
			    
			    $sheetData = $spreadsheet->getActiveSheet();
			    $rows = $sheetData->toArray();

				if (@$rows) {

					$length =  (int)count($rows[0]);
					// File not edited
					if ($length <= 7) {
					$columnNames = explode(",", $rows[0][0]);

					foreach ($rows as $rowKey => $row) {
						if ($rowKey > 0) {

							$columnValues = explode(",", $row[4]);
							$patientDetails = explode(",", $row[0]);
							$batch_nr = $result['med_rec_no'] = @($patientDetails && @$patientDetails[1])?$patientDetails[1]:0;
							$date = str_replace("/", "-", $patientDetails[14]);
							$date .=":00:00";

							$dateFormat = explode(" ", $date)[0];

							$result['delivery_date'] = date('Y-m-d', strtotime($dateFormat));
							$result['delivery_time'] = explode(" ", $date)[1];
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
										$labMachine->performMachineInsertion($batch_nr, $result, $root_path);
									}
								}

								
							}
						}

					}
				}
			}

			if (file_exists($filePath) && @$rows) {
	 			$old = getcwd(); // Save the current directory
				chdir($dirWatch);
				unlink($filePath);
				chdir($old); // Restore the old working directory   
				clearstatcache();
				
			}
	 	}
 	}

}

// stop watching our directory
inotify_rm_watch($inoInst, $watch_id);

// close our inotify instance
fclose($inoInst);
usleep(1000);
?>
