
<script type="text/javascript">
    
    function addInput(value,unit,readonly,defaultValue,readonlyQty){


        

    var user="<?php echo $_SESSION['sess_user_name']?>";      

    $("#"+value).after("<tr id='row"+value+"'><td><input type=date id='tarehe'"+value+"  name='tarehe[]'  class='datepicker' required></td><td><input type='text' name='time[]' placeholder='Enter Time' size='10' required></td><td><input type='text' size='8' name='dose[]' "+readonly+"  placeholder="+unit+" required></td><td><input type='text' size='4' name='qty[]' "+readonlyQty+"   placeholder='qty' value='"+defaultValue+"'  required></td><td><input type='text' name='comment[]'  placeholder='comment' required><input type='hidden'  name='nr[]' value='"+value+"' ><input type='hidden'  name='user[]' value='"+user+"' ></td><td><input type='button' value='DELETE' onclick=delete_row('row"+value+"')></td></td><td><input type='submit' value='save' onclick=confirm_chart() name='save_"+value+"'  ></td></tr>");
}





function delete_row(value)
{
 $('#'+value).remove();
}  

</script>

<script type="text/javascript">
    function confirm_chart() {
       window.confirm("Are you sure you want to save?");
    }

</script>


<?php




//we were here

 // print_r($_SESSION);
 // die();
$sqlweberp="SELECT value FROM care_config_global WHERE type='transmit_to_weberp_enabled'";
$resultweberp=$db->Execute($sqlweberp);
$valueweberp=$resultweberp->FetchRow();



$sqlnurse="SELECT value FROM care_config_global WHERE type='nurse_chart_deduct_stock'";
$resultnurse=$db->Execute($sqlnurse);
$valuenurse=$resultnurse->FetchRow();

if ($valueweberp['value']=='1'&& $valuenurse['value']=='1') {
  $apiEnabled=TRUE;
  require_once $root_path . 'include/care_api_classes/class_weberp_c2x.php';
    if (!isset($weberp_obj)) {

      $weberp_obj = new weberp();

    }
}else{
  $apiEnabled=FALSE;

}

?>


<form method="POST" name="presc"   action="./nurse_chart.php" >
<table border=0 id="presc" cellpadding=4 cellspacing=1 width=100% class="">

    <tr bgcolor="lightgrey" valign="top">

        <th><FONT SIZE=-1  FACE="Arial">Date/Admission No.</th>
        <th><FONT SIZE=-1  FACE="Arial"><?php
            if ($prescrServ == "serv" || $prescrServ == "proc") {
                echo "Procedure / Details";
            } else {
                echo "Drug /Prescription";
            }
            ?></th>

        <th><FONT SIZE=-1  FACE="Arial"><?php
            if ($prescrServ == "serv" || $prescrServ == "proc") {
                echo "";
            } else {
                echo "Single Dose";
            }
            ?></th>
<input type='hidden' name='destination' value="<?php echo $_SERVER['REQUEST_URI'];?>" />
        <th><FONT SIZE=-1  FACE="Arial"><?php
            if ($prescrServ == "serv" || $prescrServ == "proc") {
                echo "";
            } else {
                echo "Times Per Day";
            }
            ?></th>

        <th><FONT SIZE=-1  FACE="Arial"><?php
            if ($prescrServ == "serv" || $prescrServ == "proc") {
                echo "";
            } else {
                echo "Days";
            }
            ?></th>

        <th colspan="3"><FONT SIZE=-1  FACE="Arial"><?php
            if ($prescrServ == "serv" || $prescrServ == "proc") {
                echo "Total Tests /Items ";
            } else {
                echo "Total Dose";
            }
            ?></th>

            <th></th>

    </tr>

    <?php
    
    $toggle = 0;

    include_once($root_path . 'include/care_api_classes/class_tz_billing.php');
      while ($row = $result->FetchRow()) {

      $sql_class="SELECT purchasing_class,sub_class FROM care_tz_drugsandservices WHERE item_id=".$row['article_item_number'];
      $result_class=$db->Execute($sql_class);

      if ($classRow=$result_class->FetchRow()) {
          if ($classRow['purchasing_class']=='drug_list') {
              $isDrug=$classRow['purchasing_class'];
              $sub_class=$classRow['sub_class'];            
          }
      }

      





         //nursing chart display
        $sql_chart_dispay=" SELECT * FROM care_tz_nursing_chart WHERE nr=".$row['nr']; 
        //echo $sql_chart_dispay;
        $result_chart=$db->Execute($sql_chart_dispay);

        if ($result_chart->RecordCount()>0) {
            $charted=TRUE;
        }else{
            $charted=FALSE;
        }

        


    

        if ($toggle)
            $bgc = '#f3f3f3';
        else
            $bgc = '#fefefe';
        $toggle = !$toggle;

        if ($row['encounter_class_nr'] == 1)
            $full_en = $row['encounter_nr'] + $GLOBAL_CONFIG['patient_inpatient_nr_adder']; // inpatient admission
        else
            $full_en = $row['encounter_nr'] + $GLOBAL_CONFIG['patient_outpatient_nr_adder']; // outpatient admission
        $amount = 0;
        $notbilledyet = false;
        if ($row['bill_number'] > 0) {

            if (!isset($bill_obj))
                $bill_obj = new Bill;
            $billresult = $bill_obj->GetElemsOfBillByPrescriptionNr($row['nr']);
            if ($billrow = $billresult->FetchRow()) {
                if ($billrow['amount'] != $row['dosage'])
                    $amount = $billrow['amount'];
            }
            if (!$amount > 0) {
                $billresult = $bill_obj->GetElemsOfBillByPrescriptionNrArchive($row['nr']);
                if ($billrow = $billresult->FetchRow()) {
                    if ($billrow['amount'] != $row['dosage'])
                        $amount = $billrow['amount'];
                }
            }
        } {
            $notbilledyet = true;
        }

        if ($row['mark_os']=='1') {
            $warn_os='->Out of Stock';
        }else{
            $warn_os='';
        }
        ?>

        <?php 

        if ($row['is_disabled']) {
            $bgc = "yellow";
        }

         ?>

        <tr bgcolor="<?php echo $bgc; ?>" valign="top">
            <td><FONT SIZE=-1  FACE="Arial"><?php echo @formatDate2Local($row['prescribe_date'], $date_format); ?></td>

            <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['article'].'<font style="font-size: 15px; color: red; font-style: italic; font-style: bold;">'.$warn_os.'</font>'; ?></td>
            <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['dosage']; ?></td>
            <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['times_per_day']; ?></td>
            <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['days']; ?></td>
            <td colspan="4"><FONT SIZE=-1  FACE="Arial">

                <?php
                echo $row['total_dosage'];

                // if ($amount > 0) {
                //     echo '<s>' . $row['total_dosage'] . '</s> ' . $amount;
                // } else {
                //     echo $row['total_dosage'];
                // }
                ?>
            </td>
            <td>
              <!-- Start to show stock balance in webERP -->
              <?php

              $ItemClass='';
              if ($apiEnabled==TRUE) {
                //check item class
                if ($ItemClass=$result_class->FetchRow()) {
                  //all item should be drug_list
                  if ($ItemClass['purchasing_class']=='drug_list') {
                    //Let us get stock location and partcode
                    $loccode=$row_ipd['pharmacy'];
                    $partcode=$row['partcode'];

                    $StockBalance = $weberp_obj->get_stock_balance_webERP($partcode);

                    for ($i = 0; $i < sizeof($StockBalance); $i++) {
                      if ($StockBalance[$i]['loccode']==$loccode) {
                        $Balance = $StockBalance[$i]['quantity'];
                        echo '<b>Bal:'.$Balance.'</b>';                       
                      }//end $StockBalance[$i]['loccode']==$loccode
                      

                    }//end for($i = 0; $i < sizeof($StockBalance); $i++)




                  
                  }//end $ItemClass['purchasing_class']
                  
                }//end ItemClass

                
              }//end $apiEnabled

              ?>
            </td>
        </tr>
        
        <tr bgcolor="<?php echo $bgc; ?>" valign="top">
            <td><FONT SIZE=-1  FACE="Arial">
                | <?php
                if ($row['is_disabled'] || $row['bill_number'] > 0 || $row['issuer']) {
                    echo '<font color="#D4D4D4">Edit</font>';
                } else
                    echo '<a href="' . $thisfile . URL_APPEND . '&mode=edit&nr=' . $row['nr'] . '&show=insert&backpath=' . urlencode($backpath) . '&prescrServ=' . $_GET['prescrServ'] . '&externalcall=' . $externalcall . '&disablebuttons=' . $disablebuttons . '">' . $LDEdit . '</a>';
                ?> | 
                <?php
                if ($row['is_disabled'] || $row['bill_number'] > 0 || $row['issuer']) {
                    echo '<font color="#D4D4D4">' . $LDdelete . '</font>';
                } else{
                    $deleteUrl = $thisfile . URL_APPEND . '&mode=delete&nr=' . $row['nr'] . '&show=insert&backpath=' . urlencode($backpath) . '&prescrServ=' . $_GET['prescrServ'] . '&externalcall=' . $externalcall . '&disablebuttons=' . $disablebuttons;
                ?>
                <a href="#" onClick="deletePrescription('<?php echo $deleteUrl ?>')"><?php echo $LDdelete ?></a>
                <?php 
                }
                ?>
                

            </td>
            <td colspan=""><FONT SIZE=-1  FACE="Arial">
                <?php
                if ($row['is_disabled']) {
                    echo '<br><br><img src="../../gui/img/common/default/warn.gif" border=0 height="15" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"> <font color=red>' . $LDDisabled . '</font>';
                } elseif ($row['bill_number'] > 0) {
                    echo '<br><br><img src="../../gui/img/common/default/warn.gif" border=0 height="15" alt="" style="filter:alpha(opacity=70)"> <font color=green>' . $LDAlreadyBilled . ' ' . $row['bill_number'] . '</font>';
                    // if ($billrow['amount'] != $row['total_dosage'])
                    //     echo '<br><img src="../../gui/img/common/default/warn.gif" border=0 height="15" alt="" style="filter:alpha(opacity=70)"> <font color="red">' . $LDTheDrugDosagehDESChanged . '</font>';
                }
                elseif ($notbilledyet) {
                    echo '<br><br><img src="../../gui/img/common/default/warn.gif" border=0 height="15" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"> <font color=red>' . $LDPrescriptionNotBilled . '</font>';
                }
                ?>    </td>
            <th><FONT SIZE=-1  FACE="Arial">Notes:</th><td colspan=""><FONT SIZE=-1  FACE="Arial"><?php echo $row['notes']; ?></td>
<td><FONT SIZE=-1  FACE="Arial">Prescriber: <?php echo $row['prescriber']; ?> </td><th>
                <?php
                if (!$row['is_disabled']) {
                    echo 'Issuer:';
                }else {
                    echo "Deletion Reason:";
                }
                ?>
            </th>
            <td colspan="">
                <FONT SIZE=-1 FACE="Arial">
        <?php
        if (!$row['is_disabled']) {
            if($row['issuer']){
                echo $row['issuer'];
            }
            
        }else {
            echo $row['comment'];
        }
        ?>
            </td>  
            <th>
                Modified by:
            </th>

             <td colspan="">
                <?php
                if($row['modify_id']){
                    echo $row['modify_id'];
                }


                ?>
            </td>
            
        </tr>
       

        
       
        <?php      
                
        

        $sql_ipd="SELECT encounter_class_nr,pharmacy FROM care_encounter WHERE encounter_nr=".$_SESSION['sess_en'];

        $resut_ipd=$db->Execute($sql_ipd);
        $row_ipd=$resut_ipd->FetchRow();


           

        if ($row_ipd['encounter_class_nr']=="1"&&$isDrug&& !$row['is_disabled']&&$charted) {

            ?>           

        <tr  valign="top" bgcolor="<?php echo $bgc; ?>">
            <th>SysDate</th>
            <th>Date Entered By User</th>
            <th>QtyTaken</th>
            <th>Comment</th>
            <th>Is Stopped</th>
            <th>StoppedBy</th>
            <th>StopReason</th>
            <th>StopDate</th>
            <th>Personell</th>   
        
        </tr>
            <?php
            


            //$is_stopped=isset($is_stopped) ? $is_stopped : "NO";

              //loop throug charted items
            $stop=array();
            $total=0;
while ($rowchart=$result_chart->FetchRow()) {         
    
            if ($rowchart['is_stopped']==1) {
                $is_stopped='<b><font color="red">YES</font></b>';
                $nr=$rowchart['nr'];
                $stop[$nr]=$rowchart['nr'];                
            }else{
              $is_stopped='NO';
            }


             
       
        ?>         
        <tr bgcolor="<?php echo $bgc; ?>" valign="top">

             <td>
              <?php echo date('d-m-Y H:i:s', strtotime($rowchart['systemdate'])) ?>             
             </td>
             <td colspan="">
              <?php echo date('d-m-Y',strtotime($rowchart['userdate'])).' '.$rowchart['usertime']; ?>                
             </td>             
            <td>  <?php  echo   $rowchart['qty'];      ?>  </td>
            <td>  <?php  echo   $rowchart['comment'].' '.$rowchart['dose'];   ?>  </td>
            <td>  <?php  echo   $is_stopped;            ?>  </td>
            <td>  <?php  echo   $rowchart['stoppedBy']; ?>  </td>
            <td>  <?php  echo   $rowchart['stopReason'];?>  </td>
            <td>  <?php  echo   $rowchart['stopDate'];  ?>  </td>
            <td>  <?php  echo   $rowchart['user'];      ?>  </td>
             
        </tr>  
        
          <?php $total+=$rowchart['qty'];?>
          
        





<?php

}
echo '<tr bgcolor="'.$bgc.'"><td></td><td><strong>TOTAL DOSE GIVEN</strong></td><td>'.$total.'</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';


// echo $stop[$row['nr']];


}
        echo "<tr  bgcolor='$bgc'   class='field_wrapper' id='".$row['nr']."'>";

        echo "<td colspan=''>
            <div >
         <div>";           

         
             if ($isDrug=='drug_list') {               

                   $drug_class=TRUE;
                  switch ($sub_class) {
                      case 'syrup':
                          $unit='Enter_mls(syrup)';
                          $readonly='';

                          //Enter one(1) if first time
                          $sqlDefaultValue="SELECT * FROM care_tz_nursing_chart
                          WHERE nr=".$row['nr'];
                          $resultDefaultValue=$db->Execute($sqlDefaultValue);
                          if ($resultDefaultValue->RecordCount()>0) {
                            $defaultValue='0';                              
                          }else{
                            $defaultValue='1';

                          }
                          $readonlyQty='readonly';
                          break;

                      case 'injections':
                          $unit='enter_mg';
                          $readonly='';
                          $defaultValue='';
                          $readonlyQty='';
                          break;

                      case 'tabs':
                          $unit='tablets';
                          $readonly='readonly';
                          $readonlyQty='';
                          $defaultValue='';
                          break;  

                      case 'tablet':
                          $unit='tablets';
                          $readonly='readonly';
                          $readonlyQty='';
                          $defaultValue='';
                          break; 
                      
                      case 'tablets':
                          $unit='tablets';
                          $readonly='readonly';
                          $readonlyQty='';
                          $defaultValue='';
                          break;

                      case 'caps':
                          $unit='capsule';
                          $readonly='readonly';
                          $readonlyQty='';
                          $defaultValue='';
                          break; 

                      case 'capsule':
                          $unit='capsule';
                          $readonly='readonly';
                          $readonlyQty='';
                          $defaultValue='';
                          break;
                          
                      case 'cap':
                          $unit='capsule';
                          $readonly='readonly';
                          $readonlyQty='';
                          $defaultValue='';
                          break;                            
                      
                      default:
                          $unit='other';
                          $readonly='readonly';
                          $readonlyQty='';
                          $defaultValue='';
                          break;
                  }
               }else{
                $drug_class=FALSE;

               }  


               // echo $total.'<br>';
               // echo $row['total_dosage'];

                    

        //here we show button to add rows
        if ($row_ipd['encounter_class_nr']=="1"&&!$row['is_disabled']&&$isDrug=='drug_list'&&$stop[$row['nr']]!=$row['nr']&&$row['bill_number']<1) {



       echo  "<a href=\"javascript:addInput('".$row['nr']."','".$unit."','".$readonly."','".$defaultValue."','".$readonlyQty."');\" class='btn btn-info' role='button'  title='Chart this drug'>Nurse Chart</a>

       </div>
      </div>
      

      </td>";

      $stopUrl = $thisfile . URL_APPEND . '&mode=stop&nr=' . $row['nr'] . '&show=insert&backpath=' . urlencode($backpath) . '&prescrServ=' . $_GET['prescrServ'] . '&externalcall=' . $externalcall . '&disablebuttons=' . $disablebuttons;

    



      ?>

      <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['article'].'<font style="font-size: 15px; color: red; font-style: italic; font-style: bold;">'.$warn_os.'</font>'; ?></td>
      <?php
      if ($charted) {
          # code...
      
      ?>
      
      <td colspan="7"><a href="#" onClick="stopPrescription('<?php echo $stopUrl ?>')"><img src="<?php echo $root_path; ?>gui/img/control/default/en/en_stop.gif"  /></a></td>
      <?php
      }else{
        echo '<td colspan="7"></td>';
      }
      ?>
      <?php

            } 

     echo  "</tr>";
    ?>  

     <!--  <?php

      // echo "<tr bgcolor='lightgrey'><td colspan='2' ><b>TOTAL:</b></td><td><b><a href=\"javascript:patientsList('".$row_list_drs['personell_name']."');\"'>".$total."</a></b></td></tr>";

      ?> -->


        
        <?php
      }
    ?>

    


    <!-- Nursing treatment sheet will be appended here -->
    
</table>
</form>

<?php
if ($parent_admit && !$is_discharged) {
    ?>
    <p>
        <img <?php echo createComIcon($root_path, 'bul_arrowgrnlrg.gif', '0', 'absmiddle'); ?>>
        <a href="<?php echo $thisfile . URL_APPEND . '&pid=' . $_SESSION['sess_pid'] . '&target=' . $target . '&mode=new'; ?>">
            <?php echo $LDEnterNewRecord; ?>
        </a>
        <?php
    }
    ?>
