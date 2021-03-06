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

    <?php $claims_obj->Display_Headline($LDPendingClaims, '', '', 'Nhif_pending_claims.php', 'Claims :: Pending Claims');?>
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

if ($page_action == 'approve') {

    
	echo $claims_obj->add_nhif_claim(array('encounter_nr' => $encounter_nr));
}

$claims_details_query = $claims_obj->ShowPendingClaimsDetails(array('in_outpatient' => $in_outpatient, 'encounter_nr' => $encounter_nr));

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
// echo "<pre>"; print_r($claims_details);echo "</pre>";
	//
	//        echo $encounter_nr;

	?>

        <div class="row">
            <div class="col-4">
                <a href="../../modules/nhif/nhif_pass.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=claimsdetails&page_action=resfresh&encounter_nr=<?=$encounter_nr?>&date_from=<?=$date_from?>&date_to=<?=$date_to?>&type=<?=$in_outpatient?>&save=1" title="Refresh Details"><input type="submit" class="btn btn-info btn-block" name="show" value="Refresh"></a>
            </div>
            <div class="col-4">
                <a href="../../modules/nhif/nhif_pass.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=review&encounter_nr=<?=$encounter_nr?>&date_from=<?=$date_from?>&date_to=<?=$date_to?>" title="Back to List"><input type="submit" class="btn btn-info btn-block" name="show" value="Back to List"></a>
            </div>
            <div class="col-4">
                <?php
$nhif_claims_query = $claims_obj->get_nhif_claimes_claimed(array('encounter_nr' => $encounter_nr));

	if (!is_null($nhif_claims_query)) {
		?>
                          <button class="btn btn-info btn-block" onclick="submitNHIFClaim('<?php echo $in_outpatient ?>', '<?php echo $encounter_nr ?>', '../../modules/nhif/nhif_pass.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=send&encounter_nr=<?=$encounter_nr?>&type=<?=$in_outpatient?>&save=1')" >Submit</button>


                    <!-- <a href="../../modules/nhif/nhif_pass.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=send&encounter_nr=<?=$encounter_nr?>&date_from=<?=$date_from?>&date_to=<?=$date_to?>" title="Send Claim to NHIF"><input type="submit" class="btn btn-info btn-block" name="show" value="Submit Claim"></a> -->
                    <?php
} else {
		?>

                 <button class="btn btn-info btn-block" onclick="CreateNhifForm('<?php echo $in_outpatient ?>', '<?php echo $encounter_nr ?>', '../../modules/nhif/nhif_pass.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=claimsdetails&page_action=approve&encounter_nr=<?=$encounter_nr?>&date_from=<?=$date_from?>&date_to=<?=$date_to?>')" >Create NHIF Form</button>



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
$consultation_total_cost = 0;
	$consultations = $claims_obj->GetConsultations($encounter_nr);
	foreach ($consultations as $cons) {
		$consultation_total_cost += $cons['row_amount'];
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
                                    <?php foreach ($consultations as $consultation): ?>
                                        <tr class="h-md">
                                            <td class="w-lg"><?php echo $consultation['description'] ?></td>
                                            <td><?php echo $consultation['nhif_item_code'] ?></td>
                                            <td class="text-right"><?php echo number_format($consultation['amount'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($consultation['price'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($consultation['row_amount'], 2) ?></td>
                                        </tr>
                                    <?php endforeach?>
                                    <tr class="h-md">
                                        <td colspan="4">SUB TOTAL</td>
                                        <td class="text-right shade-light1"><?php echo number_format($consultation_total_cost, 2) ?></td>
                                    </tr>
                                <?php endif?>

                                <?php
$investigation_total_cost = 0;

	$investigations = $claims_obj->GetInvestigations($encounter_nr);

	foreach ($investigations as $investigation) {
		$investigation_total_cost += $investigation['row_amount'];
	}
	?>
                                <?php if ($investigation_total_cost > 0): ?>
                                     <tr class="shade-light">
                                        <td  colspan="5">INVESTIGATIONS</td>
                                    </tr>
                                    <?php foreach ($investigations as $investigation): ?>
                                        <tr class="h-md">
                                            <td class="w-lg"><?php echo $investigation['description'] ?></td>
                                            <td><?php echo $investigation['nhif_item_code'] ?></td>
                                            <td class="text-right"><?php echo number_format($investigation['amount'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($investigation['price'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($investigation['row_amount'], 2) ?></td>
                                        </tr>
                                    <?php endforeach?>
                                    <tr class="h-md">
                                        <td colspan="4">SUB TOTAL</td>
                                        <td class="text-right shade-light1"><?php echo number_format($investigation_total_cost, 2) ?></td>
                                    </tr>
                                <?php endif?>

                                <?php
$drugs_total_cost = 0;
	$medicines = $claims_obj->GetMedicines($encounter_nr);

	foreach ($medicines as $medicine) {
		$drugs_total_cost += $medicine['row_amount'];
	}
	?>

                                <?php if ($drugs_total_cost > 0): ?>
                                    <tr class="shade-light">
                                        <td  colspan="5">MEDICINE</td>
                                    </tr>
                                    <?php foreach ($medicines as $medicine): ?>
                                        <tr class="h-md">
                                            <td class="w-lg"><?php echo $medicine['description'] ?></td>
                                            <td><?php echo $medicine['nhif_item_code'] ?></td>
                                            <td class="text-right"><?php echo number_format($medicine['amount'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($medicine['price'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($medicine['row_amount'], 2) ?></td>
                                        </tr>
                                    <?php endforeach?>
                                     <tr class="h-md">
                                            <td colspan="4">SUB TOTAL</td>
                                            <td class="text-right shade-light1"><?php echo number_format($drugs_total_cost, 2) ?></td>
                                        </tr>
                                <?php endif?>

                                <?php
$procedure_total_cost = 0;
	$procedures = $claims_obj->GetProcedures($encounter_nr);
	foreach ($procedures as $procedure) {
		$procedure_total_cost += $procedure['row_amount'];
	}
	?>

                                <?php if ($procedure_total_cost > 0): ?>
                                    <tr class="shade-light">
                                        <td  colspan="5">PROCEDURES</td>
                                    </tr>
                                    <?php foreach ($procedures as $procedure): ?>
                                        <tr class="h-md">
                                            <td class="w-lg"><?php echo $procedure['description'] ?></td>
                                            <td><?php echo $procedure['nhif_item_code'] ?></td>
                                            <td class="text-right"><?php echo number_format($procedure['amount'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($procedure['price'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($procedure['row_amount'], 2) ?></td>
                                        </tr>
                                    <?php endforeach?>
                                     <tr class="h-md">
                                            <td colspan="4">SUB TOTAL</td>
                                            <td class="text-right shade-light1"><?php echo number_format($procedure_total_cost, 2) ?></td>
                                        </tr>
                                <?php endif?>

                                 <?php
$supplies_total_cost = 0;
	$supplies = $claims_obj->GetSupplies($encounter_nr);    
	foreach ($supplies as $supply) {
		$supplies_total_cost += $supply['row_amount'];
	}
	?>

                                <?php if ($supplies_total_cost > 0): ?>
                                    <tr class="shade-light">
                                        <td  colspan="5">SUPPLIES/SERVICES</td>
                                    </tr>
                                    <?php foreach ($supplies as $supply): ?>
                                        <tr class="h-md">
                                            <td class="w-lg"><?php echo $supply['description'] ?></td>
                                            <td><?php echo $supply['nhif_item_code'] ?></td>
                                            <td class="text-right"><?php echo number_format($supply['amount'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($supply['price'], 2) ?></td>
                                            <td class="text-right"><?php echo number_format($supply['row_amount'], 2) ?></td>
                                        </tr>
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
                                            <td><strong><?php echo ($docUser) ? ucfirst($docUser['occupation']) : "" ?></strong></td>
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
                                            <td><strong><?php echo ucfirst($doctor) ?></strong></td>
                                        </tr>
                                    </table>
                                </td>
                                <td><table class="table-lebel" ><tr><td>Signature:</td> <td></td></tr></table></td>
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
                                <td><table class="table-lebel" ><tr><td>Signature:</td> <td</td></tr></table></td>
                                <td><table class="table-lebel" ><tr><td>Official Stamp:</td> <td></td></tr></table></td>
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
                  <a target="_blank" href="../../modules/nhif/printPatientFile.php?type=<?=$in_outpatient?>&encounter_nr=<?=$encounter_nr?>" title="Print Patient File"><input type="submit" class="btn btn-info btn-block" name="show" value="Print Patient File"></a>
            </div>

            <div class="col-3">
                <form name="form1" action="" method="POST" onSubmit=" return CheckTarehe();">
                    <input type="submit" class="btn btn-info btn-block" name="show" value="Refresh">
                </form>
            </div>

            <div class="col-6">
                <?php
$nhif_claims_query = $claims_obj->get_nhif_claimes_claimed($filter_data = array('encounter_nr' => $encounter_nr));

	if (!is_null($nhif_claims_query)) {
		?>
                    <!-- <a href="" title="Send Claim to NHIF"><input type="submit" class="btn btn-info btn-block" name="show" value="Submit"></a> -->
                    <button class="btn btn-info btn-block" onclick="submitNHIFClaim('<?php echo $in_outpatient ?>', '<?php echo $encounter_nr ?>', '../../modules/nhif/nhif_pass.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=send&encounter_nr=<?=$encounter_nr?>&type=<?=$in_outpatient?>&save=1')" >Submit</button>
                    <?php
} else {
		?>
                    
                    <button class="btn btn-info btn-block" onclick="CreateNhifForm('<?php echo $in_outpatient ?>', '<?php echo $encounter_nr ?>', '../../modules/nhif/nhif_pass.php<?=URL_APPEND?> &patient=<?=$in_outpatient?> &lang=en&target=claimsdetails&page_action=approve&encounter_nr=<?=$encounter_nr?>&date_from=<?=$date_from?>&date_to=<?=$date_to?>')" >Create NHIF Form</button>
                        <?php
}
	?>

            </div>
        </div>
        <?php
}
?>


    <?php $claims_obj->Display_Footer($LDCreatenewquotation, '', '', 'billing_create_2.php', 'Billing :: Create Quotation');?>

    <?php $claims_obj->Display_Credits();?>
