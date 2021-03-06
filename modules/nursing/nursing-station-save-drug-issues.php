<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php
require('./roots.php');

require($root_path . 'include/inc_environment_global.php');
//require($root_path . 'include/care_api_classes/class_tz_pharmacy.php');

define('NO_2LEVEL_CHK', 1);
require($root_path . 'include/inc_front_chain_lang.php');

 //echo "<pre>";print_r($_POST);echo "</pre>";die;


$presc_nr=array();
if (isset($_POST['issue'])) {//issue button is pressed
	foreach ($_POST as $key => $value) {
		if (substr($key, 0,7)=='checked') {
			$presc_nr['nr']=substr($key, 8);
      $todayDose=$_POST['todayDose_'.$presc_nr['nr']];
      $_POST['nr_'.$presc_nr['nr']]=isset($_POST['nr_'.$presc_nr['nr']]) ? $_POST['nr_'.$presc_nr['nr']] : null;
      $prescriptionNr=$_POST['nr_'.$presc_nr['nr']];
      $today=date('Y-m-d');      
      $article_item_number=$_POST['article_'.$presc_nr['nr']];
      $_POST['supply_'.$presc_nr['nr']]=isset($_POST['supply_'.$presc_nr['nr']])? $_POST['supply_'.$presc_nr['nr']] : null;
      $supply=$_POST['supply_'.$presc_nr['nr']];
      if ($supply>0) {
          $supHasValue='ELSE '.$supply;
          }



          

                         
            


            
            /*
            PATIENT WILL BE GIVEN  DOSAGE FOR SINGLE DAY ONLY. IF THE DRUG IS ALREADY GIVEN TODAY IT WILL BE CLOSED UNTIL TOMORROW.
            */

            $supHasValue=isset($supHasValue)? $supHasValue : null;

            $sqlPresExpanded="SELECT cep.nr,cep.article_item_number,ce.current_ward_nr,cw.name,(CASE WHEN ds.sub_class='syrup' THEN '1' WHEN ds.sub_class='suspension' THEN '1' WHEN ds.sub_class='bottle' THEN '1' WHEN ds.sub_class='tabs' THEN dosage*times_per_day WHEN ds.sub_class='tablet' THEN dosage*times_per_day WHEN ds.sub_class='tablets' THEN dosage*times_per_day WHEN ds.sub_class='caps' THEN dosage*times_per_day WHEN ds.sub_class='capsule' THEN dosage*times_per_day WHEN ds.sub_class='capsules'THEN dosage*times_per_day WHEN ds.sub_class='injections' THEN dosage*times_per_day WHEN ds.sub_class='injection' THEN dosage*times_per_day $supHasValue END ) as qtyIssued FROM care_encounter_prescription cep INNER JOIN care_encounter ce ON cep.encounter_nr=ce.encounter_nr INNER JOIN care_tz_drugsandservices ds ON ds.item_id=cep.article_item_number INNER JOIN care_ward cw ON cw.nr=ce.current_ward_nr  WHERE ce.current_ward_nr='".$_POST['ward_nr']."' AND cep.article_item_number='".$article_item_number."' AND ce.is_discharged='0' AND cep.is_disabled<>1 AND ds.purchasing_class IN('drug_list','supplies')";  

            //echo $sqlPresExpanded;        

           $resultExpanded=$db->Execute($sqlPresExpanded);
           $_SESSION['today']=$today;
           $_SESSION['user']=$_POST['user'];


          //This is last dose, it should be given to prescription with the   highest total dose
           
           //we get prescription number from checked(checked =on) 

           
           $arrayPrNr=explode("_", $key);
           $_POST['lastDose_'.$arrayPrNr[1]]=isset($_POST['lastDose_'.$arrayPrNr[1]])? $_POST['lastDose_'.$arrayPrNr[1]] : null;
           if ($_POST['lastDose_'.$arrayPrNr[1]]) {
               

            if ($rowsExapanded=$resultExpanded->FetchRow()) {
                 
                  


            $sqlLastTrans="SELECT dis.prescriptionNr, max(cep.total_dosage) as maxDose FROM care_tz_ward_dispensed as dis INNER JOIN care_encounter_prescription cep ON cep.nr=dis.prescriptionNr INNER JOIN care_encounter ce ON ce.encounter_nr=cep.encounter_nr INNER JOIN care_tz_drugsandservices as ds ON ds.item_id=cep.article_item_number

             WHERE ce.current_ward_nr='".$_POST['ward_nr']."' AND cep.article_item_number='".$article_item_number."' AND ce.is_discharged='0' AND cep.is_disabled<>1 AND ds.purchasing_class IN('drug_list','supplies')";

             $resultLastTrans=$db->Execute($sqlLastTrans);
             if ($finalRow=$resultLastTrans->FetchRow()) {
              $rowsExapanded['nr']=$finalRow['prescriptionNr'];
              $rowsExapanded['qtyIssued']=$_POST['lastDose_'.$arrayPrNr[1]];


                            
             } 
             //echo $rowsExapanded['nr'].'<br>'.$rowsExapanded['qtyIssued'];

             $sqlCheckGiven="SELECT disp.prescriptionNr,disp.dateIssued FROM care_tz_ward_dispensed as disp  WHERE dateIssued='".$_SESSION['today']."' AND prescriptionNr='".$rowsExapanded['nr']."' AND wardNr='".$rowsExapanded['current_ward_nr']."' ";
            $resultCheckGiven=$db->Execute($sqlCheckGiven);



            if ($resultCheckGiven->RecordCount()<1 ) {
              // if ($_SESSION['supplyQTY']>0) {
              //   $rowsExapanded['qtyIssued']=$_SESSION['supplyQTY'];
              // }

                //lastDose_

             

              if ($rowsExapanded['qtyIssued']>0) {       
              
              $sqlInsert="INSERT INTO care_tz_ward_dispensed(wardNr,wardName,prescriptionNr,qtyIssued,dateIssued,is_issued,issuer)values('".$rowsExapanded['current_ward_nr']."','".$rowsExapanded['name']."','".$rowsExapanded['nr']."','".$rowsExapanded['qtyIssued']."','".$_SESSION['today']."','1','".$_SESSION['user']."') ";


              //echo $sqlInsert;

              
              $db->Execute($sqlInsert); 

                //echo "<pre>".print_r($rowsExapanded);echo "</pre>";
              
              }             
            }


          }
             
           }else{      



           
          
           while ($rowsExapanded=$resultExpanded->FetchRow()) { // print_r($_POST);     

                     

            $sqlCheckGiven="SELECT disp.prescriptionNr,disp.dateIssued FROM care_tz_ward_dispensed as disp  WHERE dateIssued='".$_SESSION['today']."' AND prescriptionNr=".$rowsExapanded['nr']." AND wardNr='".$rowsExapanded['current_ward_nr']."' ";


            $resultCheckGiven=$db->Execute($sqlCheckGiven);



            if ($resultCheckGiven->RecordCount()<1 ) {
              // if ($_SESSION['supplyQTY']>0) {
              //   $rowsExapanded['qtyIssued']=$_SESSION['supplyQTY'];
              // }

                //lastDose_

             

              if ($rowsExapanded['qtyIssued']>0) {       
              
            	$sqlInsert="INSERT INTO care_tz_ward_dispensed(wardNr,wardName,prescriptionNr,qtyIssued,dateIssued,is_issued,issuer)values('".$rowsExapanded['current_ward_nr']."','".$rowsExapanded['name']."','".$rowsExapanded['nr']."','".$rowsExapanded['qtyIssued']."','".$_SESSION['today']."','1','".$_SESSION['user']."') ";

              
              $db->Execute($sqlInsert); 

                //echo "<pre>".print_r($rowsExapanded);echo "</pre>";
            	
              }           	
            }

           }

         }  
           





		}

	}

  

  
	
}












if(isset($_REQUEST["destination"])){
	header("Location: {$_REQUEST["destination"]}");
}else if (isset($_SERVER["HTTP_REFERER"])) {
	header("Location: {$_SERVER["HTTP_REFERER"]}");	
}

require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';



	?>







</body>
</html>