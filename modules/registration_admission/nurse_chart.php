<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');

require($root_path . 'include/inc_environment_global.php');
//require($root_path . 'include/care_api_classes/class_tz_pharmacy.php');

define('NO_2LEVEL_CHK', 1);
require($root_path . 'include/inc_front_chain_lang.php');






//echo "<pre>"; print_r($_POST['tarehe']);echo "</pre>";
$rows=count($_POST['tarehe']);
for ($i=0; $i <$rows ; $i++) { 
  $date=date("Y-m-d H:i:s");
  $sql_sum="SELECT SUM(qty) as chartTotal FROM care_tz_nursing_chart WHERE nr=".$_POST['nr'][$i];
   $resultSum=$db->Execute($sql_sum);
   if ($rowsCharted=$resultSum->FetchRow()) {
      $chartedTotal=$rowsCharted['chartTotal'];
      
      $sqlPresc="SELECT total_dosage,nr FROM care_encounter_prescription WHERE nr=".$_POST['nr'][$i];
      $resultPresc=$db->Execute($sqlPresc);
      if ($rowPresc=$resultPresc->FetchRow()) {
        if ($_POST['nr'][$i]==$rowPresc['nr']) {
          $nowChartTotal=+$_POST['qty'][$i];
          $chartedTotal=$chartedTotal+$nowChartTotal;
          if ($chartedTotal<=$rowPresc['total_dosage']) {
                $sql_chart="INSERT INTO care_tz_nursing_chart (nr,userdate,usertime,systemdate,qty,comment,user,dose) values('".$_POST['nr'][$i]."','".$_POST['tarehe'][$i]."','".$_POST['time'][$i]."','".$date."','".$_POST['qty'][$i]."','".$_POST['comment'][$i]."','".$_POST['user'][$i]."','".$_POST['dose'][$i]."')";
                
                  $db->Execute($sql_chart);
            
          }
        
        }    

        
      }

      
    } 

}//for loop





    

    
    
   
if(isset($_REQUEST["destination"])){
	header("Location: {$_REQUEST["destination"]}");
}else if (isset($_SERVER["HTTP_REFERER"])) {
	header("Location: {$_SERVER["HTTP_REFERER"]}");	
}

?>



