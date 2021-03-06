<?php
$mythicData = $spreadsheet->getActiveSheet();
$mythicData->getCell('A1')->setValue('');
$mythicrows = $sheetData->toArray();

$deviceName=$mythicrows[1][0];
if ($deviceName == 'MYTHIC 1') {
	//echo "<pre>";print_r($mythicrows);echo "</pre>";
	$pid=$mythicrows[8][1];
	$pid=(int)$pid;
	if ($pid>0) {
		$fileBatchNr = $pid;
		$tests = $mythicrows;

		foreach ($reqTests as $reqTest) {
			$requestTypes = explode('__', $reqTest);
			$reqestType = @($requestTypes[1])?$requestTypes[1]:"";
	        str_replace("_", "", $reqestType);
	        //echo "<pre>";print_r($reqestType);echo "</pre
	        if (@$reqestType) {
	        	$reqtest = substr($reqTest, 0, strpos($reqTest, "__"));
	            $reqtest = str_replace("_", "", $reqtest);
	            foreach ($tests as $test) {	            	
	                if (@$test[1]) {

	                	switch ($test[0]) {
	                		case 'MON':
	                			$test[0]='MON2';
	                			break;

	                		case 'MON%':
	                			$test[0]='MON1';
	                			break;

	                		case 'NEU':
	                			$test[0]='NEU2';
	                			break;

	                		case 'NEU%':
	                			$test[0]='NEU1';
	                			break;

	                		case 'LYM':
	                			$test[0]='LYM2';
	                			break;	

	                		case 'LYM%':
	                			$test[0]='LYM1';
	                			break;

	                		case 'EOS':
	                			$test[0]='EOS2';
	                			break;

	                		case 'EOS%':
	                			$test[0]='EOS1';
	                			break;

	                		case 'BAS':
	                			$test[0]='BAS2';
	                			break;	

	                		case 'BAS%':
	                			$test[0]='BAS1';
	                			break;							
	                		
	                		default:
	                			$test[0];
	                			break;
	                	}

	                	



	                	if (is_numeric($test[1])) {
	                		//echo "<pre>";echo $test[0];echo "</pre>";
	                		$tempName = strtolower($test[0]);
	                		$testName = "_" .$tempName . "__" . $reqestType;
	                		//echo $testName.'======== '.$reqTest.'<br>';
	                		  if ($testName == $reqTest) {
	                                $labresult = array(
	                                'name' => $test[0],
	                                'amount' => $test[1],
	                                'description' => $reqTest
	                                );
	                            }

	                            $labResults[] = $labresult;
	                            $filebnr['filebatchnr']=$fileBatchNr;

	                	    
	                	}
	                	
	                	 
	                }


	            }




	        }



			



		}





	}
 	
 } 
//$mythicvalues = $mythicrows[1][0];







?>