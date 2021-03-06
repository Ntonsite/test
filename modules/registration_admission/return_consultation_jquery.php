<?php

require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/inc_environment_global.php');
include_once($root_path . 'include/care_api_classes/class_prescription.php');

//$_SESSION['item_array']=NULL;
if (isset($_POST['id'])) {
	if ($_POST['patientid']) {
		$sqlRegDate="SELECT DATE_FORMAT(date_reg, '%Y-%m-%d') AS date_reg FROM care_person WHERE pid=".$_POST['patientid'];

		$resultDateReg=$db->Execute($sqlRegDate);
		$row=$resultDateReg->FetchRow();
		if($row['date_reg']==date('Y-m-d')){
			$new=true;
			$insuranceID=$row['insurance_ID'];
			$subInsuranceID=$row['sub_insurance_id'];
		 }else{
			$new=false;
			$insuranceID=$row['insurance_ID'];
			$subInsuranceID=$row['sub_insurance_id'];
		 }

		 $cons=null;
		 $cons_other=null;

		 if($new){
		 	$cons='cons0'.$_POST['id']; 
            $cons_other='cons10';

		 	$sqlConsNew="SELECT item_description FROM care_tz_drugsandservices WHERE (item_number='".$cons."' OR  item_number LIKE '".$cons_other."%') AND purchasing_class='service'";

		 	$consNewResult=$db->Execute($sqlConsNew);
		 	while ($rows=$consNewResult->FetchRow()) {
		 		$description=$rows['item_description'];	
		 		 $cTemp = $cTemp . '<option value="' . $description . '" ';
		         $cTemp = $cTemp . '>';
		         $cTemp = $cTemp . $description;
		         $cTemp = $cTemp . '</option>';


		 	}

			
		   }else{
		   	$cons='cons0'.$_POST['id'];
		   	$cons_other='cons10';
		   	$sqlConsReturn="SELECT item_description FROM care_tz_drugsandservices WHERE (item_number LIKE '".$cons.'%'."' OR  item_number LIKE '".$cons_other."%') AND purchasing_class='service'";
		 	$consReturnResult=$db->Execute($sqlConsReturn);
		 	while ($rows=$consReturnResult->FetchRow()) {
		 		$description=$rows['item_description'];
		 		$cTemp = $cTemp . '<option value="' . $description . '" ';
		        $cTemp = $cTemp . '>';
		        $cTemp = $cTemp . $description;
		        $cTemp = $cTemp . '</option>';	
		 		 		
		 	}

		 	//echo ($cTemp);
		   							
		  }	

		  echo ($cTemp);
		  //echo $sqlConsNew;

		
		}

	}

?>

