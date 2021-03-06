<?php
$sheetData = $spreadsheet->getActiveSheet();
$sheetData->getCell('A1')->setValue('');
$rows = $sheetData->toArray();
$values = $rows[1][0];

//echo "<pre>";print_r($rows);echo "</pre>";die;


if ($rows[10][0] > 0) {

    $fileBatchNr = $rows[10][0];
    $tests = array_flatten($rows);
   
}else{
    $values = str_replace("", '', $values);
    $values = str_replace(PHP_EOL, ',', $values);
    $tests = $reqTest = explode(',', $values);
    $fileBatchNr = (int)$reqTest[9];

}



if (@$fileBatchNr) {

	
	foreach ($reqTests as $reqTest) {
	    $requestTypes = explode('__', $reqTest);
	    $reqestType = @($requestTypes[1])?$requestTypes[1]:"";
	    str_replace("_", "", $reqestType);

	    if (@$reqestType) {
	        $reqtest = substr($reqTest, 0, strpos($reqTest, "__"));
	        $reqtest = str_replace("_", "", $reqtest);
	        foreach ($tests as $test) {
	            $testResult = preg_replace("!\s+!", ",", $test);
	            $testResult = explode(',', $testResult);
	            if (@$testResult[1]) {

	                $tempName = strtolower(str_replace(".", "", $testResult[0]));
	                $testName = "_" .$tempName . "__" . $reqestType;  
	                if ($testName == $reqTest) {
	                    $labresult = array(
	                        'name' => $testResult[0],
	                        'amount' => $testResult[1],
	                        'description' => $reqTest
	                    );
	                }
	                $labResults[] = $labresult;
	                //line below was added by israel pascal, file batch number was not picked
                    $filebnr['filebatchnr']=$fileBatchNr;  

	                
	            }
	        }
	    }
	}

}

?>