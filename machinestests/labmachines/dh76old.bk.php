#!/usr/local/bin/php

<?php
proc_nice(-10);

error_reporting(E_ALL);
ini_set('display_errors', 1);

$procLimit = 100000;

$root_path = '/var/www/html/CareMD/';

require($root_path . 'include/inc_init_main.php');
require_once $root_path . 'vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\IOFactory;

// directory to watch
$dirWatch = $root_path . 'machinestests/labmachines/dh76/';

// Open an inotify instance
$inoInst = inotify_init();

// this is needed so inotify_read while operate in non blocking mode
stream_set_blocking($inoInst, 0);

// watch if a file is created or deleted in our directory to watch
// $watch_id = inotify_add_watch($inoInst, $dirWatch, IN_MOVED_TO | IN_CREATE | IN_DELETE_SELF);
$watch_id = inotify_add_watch($inoInst, $dirWatch, IN_ALL_EVENTS);


// not the best way but sufficient for this example :-)
while (true) {

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

        foreach ($files as $evt => $file) {

 			switch (true) {
                // File was modified
                case $file['mask'] & IN_MODIFY:
                    // Stop watching $file for changes
                    inotify_rm_watch($inoInst, $watch_id);
                }
            }

            fclose($inoInst);

            if(@$file['name']){
            $fileName = $file['name'];
            $timestamp = time();
            $filePath = $dirWatch . $fileName;
            // chmod($filePath, 0777);
            $directoryPath = $dirWatch . "SampleExport_graphic";
            // $filePath = $dirWatch . 'patientfile' . $timestamp . '.csv';
            // if ($fileName === 'SampleExport.csv' && file_exists($originalFilePath)) {
            //     rename($originalFilePath, $filePath);
            // }

            $encounter_nr = 0;

            if (file_exists($filePath)) {


                // Create connection
                $conn = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $inputFileType = IOFactory::identify($filePath);;

                $reader = IOFactory::createReader($inputFileType);
                $spreadsheet = $reader->load($filePath);

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
                    $batch_nr = explode(",", $rows[1][0])[1];
                    $delivery = explode(",", $rows[1][2]);
                    $deliveryDate = date('Y-m-d', strtotime($delivery[4] . " " . $delivery[0]));
                    $deliveryTime = date('H:i:s', strtotime($delivery[4] . " " . $delivery[0]));
                }

                $batch_nr = trim($batch_nr);
                $batch_nr = (int) $batch_nr;


                $testResults = [];

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
                                if (array_key_exists('wbc', $testResults) && $column == 'wbc') {
                                    continue;
                                }
                                $testResults[$column] = $columnValue;
                            }
                        }
                    }
                }

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

                if (@$requests) {
                    $encounter_nr = $requests[0]['encounter_nr'];
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
                                        'amount' => $testResult
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

                    if (@$ergebnis  && $ergebnis->num_rows > 0) {
                        $row = $ergebnis->fetch_array();
                        if ($row[1] != $status)
                            $sql = "UPDATE $event_table SET $color ='$status' WHERE encounter_nr=$encounter_nr";

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
                    if (is_dir($directoryPath)) {
                        $objects = scandir($directoryPath);
                        foreach ($objects as $object) {
                            if ($object != "." && $object != "..") {
                                if (filetype($directoryPath . "/" . $object) == "dir") rrmdir($directoryPath . "/" . $object);
                                else unlink($directoryPath . "/" . $object);
                            }
                        }
                        reset($objects);
                        rmdir($directoryPath);
                    }
                    // done
                    $sql = "UPDATE care_test_request_chemlabor
						SET status = 'done'
						WHERE batch_nr = '" . $batch_nr . "'";
                    $stmt = $conn->prepare($sql);
                    $stmt->Execute();
                    $stmt->close();
                    if (file_exists($filePath) && $encounter_nr > 0 && @$testResults && @$requests) {
                        fclose($filePath);
                        unlink($filePath);

                        // shell_exec('rm -f -r ' . $filePath . '/* 2>&1');
                    }
                }

                unset($spreadsheet);
                unset($reader);
                $conn->close();
            }
        }
    }
}

// stop watching our directory
// inotify_rm_watch($inoInst, $watch_id);

// close our inotify instance
// fclose($inoInst);
// usleep(1000);
?>