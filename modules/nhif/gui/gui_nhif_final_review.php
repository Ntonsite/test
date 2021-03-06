<!--<script type="text/javascript" src="<?php echo $root_path; ?>js/jquery.1.10.js"></script>-->
<script type="text/javascript">

    function approove_claim(encounter_nr) {
        ProgressCreate(10);
        $(document).ready(function () {
            $.ajax({
                url: "<?php echo $root_path; ?>modules/nhif/approve_claim.php",
                type: 'GET',
                data: {encounter_nr: encounter_nr},
                success: function (data) {
                    ProgressDestroy();
                    $("#approve_link").html(data);
                }
            });
        });
    }
</script>
<?php


$bat_nr = (isset($bat_nr) ? $bat_nr : null);
$claims_obj->Display_Header($LDNewQuotation, $enc_obj->ShowPID($bat_nr), '');

?>
<BODY bgcolor="#ffffff" link="#000066" alink="#cc0000" vlink="#000066">

    <?php $claims_obj->Display_Headline($LDFinalReview, '', '', 'Nhif_pending_claims.php', 'Claims :: Pending Claims');?>
    <!--Date starts here-->
    <script type="text/javascript">
        function CheckTarehe() {
            var date_from = document.getElementById("date_from").value;
            var date_to = document.getElementById("date_to").value;

            if (date_from == '') {
                alert("Date empty");
                return false;
            } else if (date_to == '') {
                alert("Date empty");
                return false;

            } else if (date_from > date_to) {
                alert("incorrect date");
                return false;

            }

        }

    </script>
    <script>
        window.setTimeout(function () {
            $(".alert-success").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
    </script>
    <link rel="stylesheet" href="<?php echo $root_path; ?>assets/bootstrap/css/bootstrap.min.css" >
    <script src="<?php echo $root_path; ?>assets/bootstrap/js/jquery-3.2.1.slim.min.js" ></script>
    <script src="<?php echo $root_path; ?>assets/bootstrap/js/popper.min.js" ></script>
    <script src="<?php echo $root_path; ?>assets/bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript"><?php require $root_path . 'include/inc_checkdate_lang.php';?>
    </script>
    <script language="javascript" src="<?php echo $root_path; ?>js/setdatetime.js"></script>
    <script language="javascript" src="<?php echo $root_path; ?>js/checkdate.js"></script>
    <script language="javascript" src="<?php echo $root_path; ?>js/dtpick_care2x.js"></script>


    <?php
//     Array
// (
//     [lang] => en
//     [sid] => iduv7e9qe648rgiogn20loc684
//     [target] => finalreview
//     [patient] => 2
//     [encounter_nr] => 30186
//     [page_action] => 
//     [date_from] => 01/11/2020
//     [date_to] => 30/11/2020
//     [checkintern] => 1
// )

    $encounter_nr = $_REQUEST['encounter_nr'];
    $in_outpatient = $_REQUEST['patient']; 



if ($page_action == 'approve') {

    
	echo $claims_obj->add_nhif_claim(array('encounter_nr' => $encounter_nr));
}

//echo "<pre>";print_r($_REQUEST);echo "</pre>";
  
 // $resultCep = $bill_obj->GetNewQuotation_Prescriptions($encounter_nr, $in_outpatient);
 // $result_lab = $bill_obj->GetNewQuotation_Laboratory($encounter_nr, $in_outpatient);
 // $result_rad = $bill_obj->GetNewQuotation_Radiology($encounter_nr, $in_outpatient);


//coming data
//Array ( [lang] => en [sid] => dk1kfsasrj3a1m7il49ist548u [target] => finalreview [patient] => 2 [encounter_nr] => 30340 [page_action] => [date_from] => 01/12/2020 [date_to] => 31/12/2020 [checkintern] => 1 )

$date_from = $_REQUEST['date_from'];      //format is 01/12/2020
$date_to   = $_REQUEST['date_to'];        //format is 01/12/2020
$target    = $_REQUEST['target'];         //Final Feview
$in_outpatient = $_REQUEST['patient'];    //
$encounter_nr = $_REQUEST['encounter_nr']; //patient


 

$claims_details_query = $claims_obj->ShowPendingClaimsDetails(array('in_outpatient' => $in_outpatient, 'encounter_nr' => $encounter_nr,'finalreview'=>$target));

//print_r($claims_details_query);die;


if (!is_null($claims_details_query)) {
	$claims_details = $claims_details_query->fields;

	// echo "<pre>"; print_r($claims_details);echo "</pre>";

	?>

        <style>
            .wrapper{
                line-height: 150%;
                /*width: 277mm;*/
                background-color: #59f7f2;
            }
            .center{
                text-align: center;
            }
            .left{text-align: left;}
            .right{
                text-align: right;
            }
            .logonhif{
                width: 24mm;
            }
            .title1{
                font-size: 20px;
                font-weight: bold;
            }
            .title2{
                font-size: 16px;
                font-weight: bold;
            }
            .undeline_sapn{
                border-bottom: 2px dotted;
                padding-right: 10px;
                padding-left: 10px;
                width: 100%;
            }
            .shade-light{
                height: 10mm;
                background-color:  lightgray;
                font-size: 16px;
            }

            .shade-light1{
                background-color:  lightgray;
                font-size: 16px;
            }

            table {
                table-layout: auto;
                border-collapse: collapse;
                width: 98%;
            }
            .table-lebel{
                padding-right: 10mm;
            }
            .table-lebel td{
                white-space: nowrap;  /** added **/
            }
            .table-lebel td:last-child{
                width:100%;
                padding-left: 5mm;
                border-bottom: 2px dotted;
            }

            .h-md {
                height: 25px;
            }
            .w-lg {
                width: 60%;
            }
            .text-right {
                padding-right: 10px;
            }

        </style>
        <?php
//echo "<pre>"; print_r($claims_details);echo "</pre>";
	//
	//        echo $encounter_nr;



	?>

        <div class="row">
             <div class="col-3">
                  <button class="btn btn-info btn-block" onclick="getinfo(<?php echo $encounter_nr ?>)" >CHART FOLDER</button>
            </div>
            <div class="col-4">
                <a href="../../modules/nhif/nhif_pass.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=BeforeBill&encounter_nr=<?=$encounter_nr?>&date_from=<?=$date_from?>&date_to=<?=$date_to?>" title="Back to List"><input type="submit" class="btn btn-info btn-block" name="show" value="Back to List"></a>
            </div>
            <div class="col-4">
                <?php
// $nhif_claims_query = $claims_obj->get_nhif_claimes_claimed(array('encounter_nr' => $encounter_nr));

    $sqlUpdateStatus = "SELECT nhif_approved FROM care_encounter WHERE encounter_nr='".$encounter_nr."'";
    $resultUpdateStatus = $db->Execute($sqlUpdateStatus); 
    $isUpdated = $resultUpdateStatus->FetchRow();
    
    if ($isUpdated['nhif_approved'] == '1') {
        $updated = true;
    }else{
        $updated = false;
    }





	if ($updated) {
		?>
                          <button class="btn btn-info btn-block" onclick="undoApproval('<?php echo $in_outpatient ?>', '<?php echo $encounter_nr ?>', '../../modules/nhif/nhif_final_approval.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=undo&encounter_nr=<?=$encounter_nr?>&type=<?=$in_outpatient?>&save=1')" >UN-DO</button>


                    <!-- <a href="../../modules/nhif/nhif_pass.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=send&encounter_nr=<?=$encounter_nr?>&date_from=<?=$date_from?>&date_to=<?=$date_to?>" title="Send Claim to NHIF"><input type="submit" class="btn btn-info btn-block" name="show" value="Submit Claim"></a> -->
                    <?php
} else {
		?>

                 <button class="btn btn-info btn-block" onclick="finalApproval('<?php echo $in_outpatient ?>', '<?php echo $encounter_nr ?>', '../../modules/nhif/nhif_final_approval.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=approve&page_action=approve&encounter_nr=<?=$encounter_nr?>&date_from=<?=$date_from?>&date_to=<?=$date_to?>')" >Approve</button>



                    <!-- <a href="../../modules/nhif/nhif_pass.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=claimsdetails&page_action=approve&encounter_nr=<?=$encounter_nr?>&date_from=<?=$date_from?>&date_to=<?=$date_to?>" title="Approve Claim"><input type="submit" class="btn btn-info btn-block" name="show" value="Approve"></a> -->
                    <?php
}
	?>

            </div>
        </div>
        
        <div class="row">
            <table border="0" width="100%" cellspacing="1" cellpadding="1" style="background-color: azure; margin-left: 15px;" >
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td rowspan="3" class="left logonhif" >
                                    <img class="logonhif" src="<?php echo $root_path; ?>modules/nhif/images/NHIF_logo.jpg" alt="NHIF Logo"/>
                                </td>
                                <td class="center title1">CONFIDENTIAL</td>
                                <td class="center" rowspan="2">Form NHIF 2A&B<br> Regulation 18(1)</td>
                            </tr>
                            <tr>
                                <td class="center title1">THE NHIF - HEALTH PROVIDER IN/OUT PATIENT CLAIM FORM</td>

                            </tr>
                            <tr>
                                <td class="right">Serial No. <?php echo $claims_obj->getSerialNumber($encounter_nr, $claims_details) ?></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th>A: PARTICULARS:</th>
                </tr>
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">

                            <tr>
                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>1. Name of Health Facility</td>
                                             <td><strong><?php echo $companyName ?></strong></td>
                                         </tr>
                                     </table>
                                </td>

                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>2. Address:</td>
                                            <td><strong><?php echo $companyAddress ?></strong></td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table class="table-lebel">

                                        <tr>
                                            <td>3. Consultation Fees:</td>
                                            <td>

                                                 <?php
$resultCep = $bill_obj->GetNewQuotation_Prescriptions($encounter_nr, $in_outpatient);                                                 


$consultation_total_cost = 0;
	//$consultations = $claims_obj->GetConsultations($encounter_nr);

if ($in_outpatient=='1') {

    foreach ($resultCep as $cons) {
        if (strtolower(substr($cons['item_number'], 0,4)) =='cons') {
            $sqlConsPrice = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE SchemeID='".$cons['nhif_scheme_id']."' AND ItemCode='".$cons['nhifItemCode']."' AND   ItemTypeID<>1";
            $consResult = $db->Execute($sqlConsPrice);
            $consRow = $consResult->FetchRow();
            $consultation_total_cost += $consRow['UnitPrice'];
        }

    }
    
}else{

    foreach ($resultCep as $cons) {
        if (strtolower(substr($cons['item_number'], 0,4)) =='cons') {
            $sqlConsPrice = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE SchemeID='".$cons['nhif_scheme_id']."' AND ItemCode='".$cons['nhifItemCode']."' ";
            $consResult = $db->Execute($sqlConsPrice);
            $consRow = $consResult->FetchRow();
            $consultation_total_cost += $consRow['UnitPrice'];
        }

    }

}
	
	?>
                                                <strong> <?php echo number_format($consultation_total_cost) ?></strong>

                                            </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>


                            <tr>
                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>4. Department:</td>
                                            <td><strong><?php echo $claims_obj->GetDepartmentName($claims_details['current_dept_nr']) ?></strong></td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>5. Date of Attendance:</td>
                                            <td><strong><?php echo date('d/m/Y', strtotime($claims_details['encounter_date'])) ?></strong></td>
                                        </tr>
                                    </table>

                                </td>

                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>6. Patient File No:</td>
                                            <td><strong><?php echo $claims_details['selian_pid'] ?></strong></td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">

                            <tr>
                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>7. Name of Patient:</td>
                                            <td>
                                                <strong><?=strtoupper($claims_details['name_first'])?> <?=strtoupper($claims_details['name_middle'])?> <?=strtoupper($claims_details['name_last'])?></strong>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>8. DOB:</td>
                                            <td>
                                                <strong><?php
echo date("d/m/Y", strtotime($claims_details['date_birth']))
	?></strong>
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table class="table-lebel" >
                                        <tr><td>9. Sex M/F:</td>
                                            <td><strong><?php echo strtoupper($claims_details['sex']) ?></strong></td>
                                        </tr>
                                    </table>
                                </td>

                            </tr>

                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table width="100%">
                            <tr>
                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>10. Vote:</td>
                                            <td><strong><?php echo $claims_details['employee_id'] ?></strong></td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>11. Patient Physical Address:</td>
                                            <td>
                                                <strong><?php echo $claims_obj->GetPatientPhysicalAddress($claims_details['ward'], $claims_details['district']) ?></strong>
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                                 <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>12. Card No:</td>
                                            <td>
                                                <strong><?php echo $claims_details['membership_nr'] ?></strong>

                                               

                                          

                                            </td>
                                        </tr>
                                    </table>
                                </td>

                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table width="100%">
                            <tr>
                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>13. Occupation:</td>
                                            <td><strong></strong></td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>14. Preliminary Diagnosis (Code):</td>
                                            <td>
                                              <strong><?php echo $claims_obj->GetDignosisCodesByType($encounter_nr, 'preliminary'); ?> </strong>
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                <table class="table-lebel" >
                                                    <tr>
                                                        <td>15. Final Diagnosis (Code):</td>
                                                        <td>
                                                           <strong><?php echo $claims_obj->GetDignosisCodesByType($encounter_nr, 'final') ?>  </strong>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <th>B: COST OF SERVICE</th>
                </tr>
                <tr>
                    <td>

                        <table cellpadding=0 cellspacing=0 border=1 height="200" width="100%">
                            <thead>
                                <tr class="shade-light">
                                    <th class="center">Description</th>
                                    <th class="center">Item Code</th>
                                    <th class="center">Qty</th>
                                    <th class="center">Unit Price</th>
                                    <th class="center">Amount</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if ($consultation_total_cost > 0): ?>
                                     <tr class="shade-light">
                                        <td  colspan="5">Consultations</td>
                                    </tr>

                                    <?php foreach ($resultCep as $cons): ?>
                                        <?php if (strtolower(substr($cons['item_number'], 0,4)) =='cons') { ?>
                                        <tr class="h-md">
                                            <td class="w-lg"><?php echo $cons['article'] ?></td>
                                            <td><?php echo $cons['nhifItemCode'] ?></td>
                                            <td class="text-right"><?php echo number_format($cons['total_dosage'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($consultation_total_cost, 2) ?></td>
                                            <td class="text-right"><?php echo number_format($consultation_total_cost*$cons['total_dosage'], 2) ?></td>
                                        </tr>
                                        <?php }?>
                                    <?php endforeach?>
                                    <tr class="h-md">
                                        <td colspan="4">SUB TOTAL</td>
                                        <td class="text-right shade-light1"><?php echo number_format($consultation_total_cost, 2) ?></td>
                                    </tr>
                                <?php endif?>

 

                                <?php
$labinvestigation_total_cost = 0;
$radinvestigation_total_cost = 0;                                
$investigation_total_cost = 0;
 $result_lab = $bill_obj->GetNewQuotation_Laboratory($encounter_nr, $in_outpatient);
 //$result_rad = $bill_obj->GetNewQuotation_Radiology($encounter_nr, $in_outpatient);

	//$investigations = $claims_obj->GetInvestigations($encounter_nr);

	foreach ($result_lab as $lab) {
		//$investigation_total_cost += $investigation['row_amount'];
        //echo "<pre>";print_r($lab);echo "</pre>";
        $sqlLabPrice = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE SchemeID='".$lab['nhif_scheme_id']."' AND ItemCode='".$lab['nhifItemCode']."'";
        $labResult = $db->Execute($sqlLabPrice);
        $labRow = $labResult->FetchRow();
        $labinvestigation_total_cost += $labRow['UnitPrice'];



	}

    $result_rad = $bill_obj->GetNewQuotation_Radiology($encounter_nr, $in_outpatient);

    foreach ($result_rad as $rad) {
        //$investigation_total_cost += $investigation['row_amount'];
        //echo "<pre>";print_r($lab);echo "</pre>";
        $sqlRadPrice = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE SchemeID='".$rad['nhif_scheme_id']."' AND ItemCode='".$rad['nhifItemCode']."'";
        $radResult = $db->Execute($sqlRadPrice);
        $radRow = $radResult->FetchRow();
        $radinvestigation_total_cost += $radRow['UnitPrice']*$rad['dosage'];

    }

    $investigation_total_cost = $radinvestigation_total_cost + $labinvestigation_total_cost;
	?>
                                <?php if ($investigation_total_cost > 0): ?>
                                     <tr class="shade-light">
                                        <td  colspan="5">INVESTIGATIONS</td>
                                    </tr>
                                    <?php foreach ($result_lab as $lab_items): ?>
                                        <?php //echo "<pre>"; print_r($lab_items); echo "</pre>";?>
                                    <?php    
 $sqlLabItemPrice = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE SchemeID='".$lab_items['nhif_scheme_id']."' AND ItemCode='".$lab_items['nhifItemCode']."'";
 $labItemResult = $db->Execute($sqlLabItemPrice);
 $labItemRow = $labItemResult->FetchRow();
                                     ?>    
    


                                        <tr class="h-md">
                                            <td class="w-lg"><?php echo $lab_items['item_description'] ?></td>
                                            <td><?php echo $lab_items['nhifItemCode'] ?></td>
                                            <td class="text-right"><?php echo number_format(1, 2) ?></td>
                                            <td class="text-right"><?php echo number_format($labItemRow['UnitPrice'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($labItemRow['UnitPrice']*1, 2) ?></td>
                                        </tr>
                                    <?php endforeach?>
                                <?php foreach ($result_rad as $rad_items): ?>
                                        <?php //echo "<pre>"; print_r($rad_items); echo "</pre>";?>
                                    <?php    
 $sqlRadItemPrice = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE SchemeID='".$rad_items['nhif_scheme_id']."' AND ItemCode='".$rad_items['nhifItemCode']."'";
 $radItemResult = $db->Execute($sqlRadItemPrice);
 $radItemRow = $radItemResult->FetchRow();
                                     ?>    
    


                                        <tr class="h-md">
                                            <td class="w-lg"><?php echo $rad_items['item_description'] ?></td>
                                            <td><?php echo $rad_items['nhifItemCode'] ?></td>
                                            <td class="text-right"><?php echo number_format($rad_items['dosage'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($radItemRow['UnitPrice'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($radItemRow['UnitPrice']*$rad_items['dosage'], 2) ?></td>
                                        </tr>
                                    <?php endforeach?>    

                                    <tr class="h-md">
                                        <td colspan="4">SUB TOTAL</td>
                                        <td class="text-right shade-light1"><?php echo number_format($investigation_total_cost, 2) ?></td>
                                    </tr>
                                <?php endif?>

                                <?php
$drugs_total_cost = 0;
	//$medicines = $claims_obj->GetMedicines($encounter_nr);


	foreach ($resultCep as $med) {
        //echo "<pre>";print_r($med); echo "</pre>";
        if (substr($med['purchasing_class'], 0,9)=='drug_list') {
            $sqlMedItemPrice = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE SchemeID='".$med['nhif_scheme_id']."' AND ItemCode='".$med['nhifItemCode']."'";
            $medItemResult = $db->Execute($sqlMedItemPrice);
           $medItemRow = $medItemResult->FetchRow();

           $drugs_total_cost += $medItemRow['UnitPrice']*$med['total_dosage']; 

            
        }

		
	}
	?>

                                <?php if ($drugs_total_cost > 0): ?>
                                    <tr class="shade-light">
                                        <td  colspan="5">MEDICINE</td>
                                    </tr>
                                    <?php foreach ($resultCep as $med_items): ?>
                                        <?php //echo "<pre>";print_r($medicine);echo "</pre>";?>
                                             <?php if(substr($med_items['purchasing_class'], 0,9)=='drug_list'){?>
                                                <?php 
                                                $sqlUnitPriceMed = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE SchemeID='".$med_items['nhif_scheme_id']."' AND ItemCode='".$med_items['nhifItemCode']."'";
                                                $resultUnitPriceMed = $db->Execute($sqlUnitPriceMed);
                                                $rowUnitPriceMed = $resultUnitPriceMed->FetchRow();
                                                ?>

                                        <tr class="h-md">
                                            <td class="w-lg"><?php echo $med_items['article'] ?></td>
                                            <td><?php echo $med_items['nhifItemCode'] ?></td>
                                            <td class="text-right"><?php echo number_format($med_items['total_dosage'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($rowUnitPriceMed['UnitPrice'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($med_items['total_dosage']*$rowUnitPriceMed['UnitPrice'], 2) ?></td>
                                        </tr>     
                                                  <?php }?> 
                                        
                                    <?php endforeach?>
                                     <tr class="h-md">
                                            <td colspan="4">SUB TOTAL</td>
                                            <td class="text-right shade-light1"><?php echo number_format($drugs_total_cost, 2) ?></td>
                                        </tr>
                                <?php endif?>

                                <?php
//start

    //$medicines = $claims_obj->GetMedicines($encounter_nr);

$procedure_total_cost = 0;
    foreach ($resultCep as $proc) {
        //echo "<pre>";print_r($proc); echo "</pre>";
        if (substr($proc['purchasing_class'], 0,6)=='dental' || substr($proc['purchasing_class'], 0,13)=='minor_proc_op' || substr($proc['purchasing_class'], 0,11)=='surgical_op' || substr($proc['purchasing_class'], 0,9)=='obgyne_op' || substr($proc['purchasing_class'], 0,8)=='ortho_op' ) {
            $sqlProcItemPrice = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE SchemeID='".$proc['nhif_scheme_id']."' AND ItemCode='".$proc['nhifItemCode']."'";
            $procItemResult = $db->Execute($sqlProcItemPrice);
           $procItemRow = $procItemResult->FetchRow();
           //echo "<pre>";print_r($procItemRow); echo "</pre>"; 


           $procedure_total_cost += $procItemRow['UnitPrice']*$proc['total_dosage']; 

            
        }

        
    }

//end                                
	?>

                                <?php if ($procedure_total_cost > 0): ?>
                                    <tr class="shade-light">
                                        <td  colspan="5">PROCEDURES</td>
                                    </tr>
                                    <?php foreach ($resultCep as $proc_items): ?>
<?php        if (substr($proc_items['purchasing_class'], 0,6)=='dental' || substr($proc_items['purchasing_class'], 0,13)=='minor_proc_op' || substr($proc_items['purchasing_class'], 0,11)=='surgical_op' || substr($proc_items['purchasing_class'], 0,9)=='obgyne_op' || substr($proc_items['purchasing_class'], 0,8)=='ortho_op' ) { ?>
<?php $sqlProcUnitPrice = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE SchemeID='".$proc_items['nhif_scheme_id']."' AND ItemCode='".$proc_items['nhifItemCode']."'";
            $procUnitPriceResult = $db->Execute($sqlProcUnitPrice);
           $procUnitPriceRow = $procUnitPriceResult->FetchRow(); ?>
                                        <tr class="h-md">
                                            <td class="w-lg"><?php echo $proc_items['article'] ?></td>
                                            <td><?php echo $proc_items['nhifItemCode'] ?></td>
                                            <td class="text-right"><?php echo number_format($proc_items['total_dosage'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($procUnitPriceRow['UnitPrice'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($proc_items['total_dosage']*$procUnitPriceRow['UnitPrice'], 2) ?></td>
                                        </tr>
                                        <?php }?>
                                    <?php endforeach?>
                                     <tr class="h-md">
                                            <td colspan="4">SUB TOTAL</td>
                                            <td class="text-right shade-light1"><?php echo number_format($procedure_total_cost, 2) ?></td>
                                        </tr>
                                <?php endif?>

                                 <?php
$supplies_total_cost = 0;
	foreach ($resultCep as $sup) {
        
        if (substr($sup['purchasing_class'], 0,7)=='service' || substr($sup['purchasing_class'], 0,8)=='supplies'  ) {
             if (strtolower(substr($sup['item_number'], 0,4))!=='cons') {
                $sqlSupPrice = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE SchemeID='".$sup['nhif_scheme_id']."' AND ItemCode='".$sup['nhifItemCode']."'";
                $supPriceResult = $db->Execute($sqlSupPrice);
                $supPriceItemRow = $supPriceResult->FetchRow();
                $supplies_total_cost += $supPriceItemRow['UnitPrice']*$sup['total_dosage'];
                
                
             }
             

            
        }

        
    }
	?>

                                <?php if ($supplies_total_cost > 0): ?>
                                    <tr class="shade-light">
                                        <td  colspan="5">SUPPLIES/SERVICES</td>
                                    </tr>
                                    <?php foreach ($resultCep as $sup_items): ?>
          <?php  if (substr($sup_items['purchasing_class'], 0,7)=='service' || substr($sup_items['purchasing_class'], 0,8)=='supplies'  ) {?>
                                         <?php if(strtolower(substr($sup_items['item_number'], 0,4))!=='cons'):?>
                                            <?php 
                                            $sqlUnitPriceSup = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE ItemCode='".$sup_items['nhifItemCode']."' AND SchemeID='".$sup_items['nhif_scheme_id']."'";
                                            $ResultUnitPriceSup = $db->Execute($sqlUnitPriceSup);
                                            $unitPriceSupRow = $ResultUnitPriceSup->FetchRow();
                                            ?>
                                         
                                        <tr class="h-md">
                                            <td class="w-lg"><?php echo $sup_items['article'] ?></td>
                                            <td><?php echo $sup_items['nhifItemCode'] ?></td>
                                            <td class="text-right"><?php echo number_format($sup_items['total_dosage'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($unitPriceSupRow['UnitPrice'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($sup_items['total_dosage']*$unitPriceSupRow['UnitPrice'], 2) ?></td>
                                        </tr>
                                    <?php endif?>
                                        <?php }?>
                                    <?php endforeach?>
                                     <tr class="h-md">
                                            <td colspan="4">SUB TOTAL</td>
                                            <td class="text-right shade-light1"><?php echo number_format($supplies_total_cost, 2) ?></td>
                                        </tr>
                                <?php endif?>

                                <tr class="">
                                    <td colspan="4">
                                        GRAND TOTAL
                                    </td>
                                    <td class="text-right shade-light">
                                        <?=number_format($consultation_total_cost + $investigation_total_cost + $drugs_total_cost + $procedure_total_cost + $supplies_total_cost, 2)?>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>


                <?php if (empty($claims_obj->GetDignosisCodes($encounter_nr))): ?>
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <th>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>C: Name of attending Clinician:
                                            </td>
                                            <td></td>
                                        </tr>
                                    </table>
                                </th>

                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>Qualification:</td>
                                            <td></td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>Signature:</td>
                                            <td></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>
                <tr>
                    <th>D: Patient Certification</th>
                </tr>
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>I certify that I received the above named services. Name:</td>
                                            <td></td>
                                        </tr>
                                    </table>
                                </td>
                                <td><table class="table-lebel" ><tr><td>Signature:</td> <td></td></tr></table></td>
                                <td><table class="table-lebel" ><tr><td>Tel. No:</td> <td></td></tr></table></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th><table class="table-lebel" ><tr><td>E: Description of Out/In-patient Management/any Other additional information (a separate sheet can be used):</td> <td></td></tr></table></th>
                </tr>
                <tr>
                    <th>
                        F: Claimant Certification:
                    </th>
                </tr>

                <?php else: ?>
                <?php
$doctor = $claims_obj->GetDignosisDocName($encounter_nr);
$docUser = $claims_obj->GetDocUser($doctor);
$qDetailsRow=$claims_obj->GetqualificationDetails($doctor);  
$doctorQualificationName=$qDetailsRow['sname'];

	// $patientId = $claims_obj->GetPersonel($personelNumber);
	// $person = $claims_obj->GetPerson($patientId);
	?>
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <th>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>C: Name of attending Clinician:</td>
                                            <td><strong><?php echo ucfirst($doctor) ?></strong></td>
                                        </tr>
                                    </table>
                                </th>

                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>Qualification:</td>
                                            <td><strong><?php echo $doctorQualificationName; ?></strong></td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                        <?php
                        $login_id = $docUser['login_id'];
                      $doctorSignature = '<img src="../../modules/nhif/signatures/'.$login_id.'.png"  width="29" height="16">';
                      ?>
              
                                    <table class="" >
                                        <tr>
                                            <td>Signature:</td>
                                            <td><?php echo $doctorSignature;?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>
                <tr>
                    <th>D: Patient Certification</th>
                </tr>
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <table class="table-lebel" >
                                        <tr>
                                            <td>I certify that I received the above named services. Name:</td>
                                             <td>
                                                <strong><?=strtoupper($claims_details['name_first'])?> <?=strtoupper($claims_details['name_middle'])?> <?=strtoupper($claims_details['name_last'])?></strong>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <?php

                                $patientSignature = '<img src="../../modules/nhif/signatures/signature'.$encounter_nr.'.png"  width="223"   height="46" style="vertical-align:top;float:left" >';
                                ?>

                                <td><table class="" ><tr><td>Signature:</td><td><?php echo $patientSignature;?> 
                                    
                                </td></tr></table></td>
                                <td><table class="table-lebel" ><tr><td>Tel. No:</td> <td><strong><?=($docUser) ? $docUser['tel_no'] : ""?></strong></td></tr></table></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th><table class="table-lebel" ><tr><td>E: Description of Out/In-patient Management/any Other additional information (a separate sheet can be used):</td> <td></td></tr></table></th>
                </tr>
                <tr>
                    <th>
                        F: Claimant Certification:
                    </th>
                </tr>

                <?php endif?>
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><table class="table-lebel" ><tr><td>I certify that I provided the above service.  Name:</td> <td><?=ucfirst($doctor)?></td></tr></table></td>
                                <td><table class="" ><tr><td>Signature:</td>
                                 <td><?php echo $doctorSignature;?></td></tr></table></td>
                                 <?php
                                 $muhuri_file = '<img src="../../gui/img/common/default/'.'muhuri.png"  width="61" height="36">';
                                 ?>
                                <td><table ><tr><td>Official Stamp:</td> <td><?php echo $muhuri_file; ?></td></tr></table></td>
                            </tr>
                        </table>

                    </td>
                </tr>

                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <th>NB:</th>
                                <th>Fill in Triplicate and please submit the original form on monthly basis, and the claim be attached with Monthly Report.<br>Any falsified information may subject you to prosecution in accordance with NHIF Act No. 8 of 1999.</th>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <?php 
        $contents='<table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <th>NB:</th>
                                <th>Fill in Triplicate and please submit the original form on monthly basis, and the claim be attached with Monthly Report.<br>Any falsified information may subject you to prosecution in accordance with NHIF Act No. 8 of 1999.</th>
                            </tr>
                        </table>';
        //echo $contents;


        
        ?>


        <div class="row">
            <div class="col-3">
                  <button class="btn btn-info btn-block" onclick="getinfo(<?php echo $encounter_nr ?>)" >CHART FOLDER</button>
            </div>

           <div class="col-4">
                <a href="../../modules/nhif/nhif_pass.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=BeforeBill&encounter_nr=<?=$encounter_nr?>&date_from=<?=$date_from?>&date_to=<?=$date_to?>" title="Back to List"><input type="submit" class="btn btn-info btn-block" name="show" value="Back to List"></a>
            </div>

            
                <?php


	if ($updated) {
		?>
                    <div class="col-4">

                      <button class="btn btn-info btn-block" onclick="undoApproval('<?php echo $in_outpatient ?>', '<?php echo $encounter_nr ?>', '../../modules/nhif/nhif_final_approval.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=undo&encounter_nr=<?=$encounter_nr?>&type=<?=$in_outpatient?>&save=1')" >UN-DO</button>
                      </div>




                    <!-- <a href="" title="Send Claim to NHIF"><input type="submit" class="btn btn-info btn-block" name="show" value="Submit"></a> -->
                    
                    <?php
} else {
		?>
                    <div class="col-4">
                    <button class="btn btn-info btn-block" onclick="finalApproval('<?php echo $in_outpatient ?>', '<?php echo $encounter_nr ?>', '../../modules/nhif/nhif_final_approval.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=approve&page_action=approve&encounter_nr=<?=$encounter_nr?>&date_from=<?=$date_from?>&date_to=<?=$date_to?>')" >Approve</button>

                        <?php

}
	?>

                    </div>
            </div>
        </div>
        <?php
}
?>


    <?php $claims_obj->Display_Footer($LDCreatenewquotation, '', '', 'billing_create_2.php', 'Billing :: Create Quotation');?>

    <?php $claims_obj->Display_Credits();?>
