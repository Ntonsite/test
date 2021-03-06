
<?php

$bat_nr = (isset($bat_nr) ? $bat_nr : null);
$claims_obj->Display_Header($LDNewQuotation, $enc_obj->ShowPID($bat_nr), '');
?>
<style>
    /*###Desktops, big landscape tablets and laptops(Large, Extra large)####*/
    @media screen and (min-width: 1024px){
        /*Style*/
        .container{
            max-width: 90vw;
        }
    }

    /*###Tablet(medium)###*/
    @media screen and (min-width : 768px) and (max-width : 1023px){
        /*Style*/
        .container{
            max-width: 100vw;
        }
    }

    /*### Smartphones (portrait and landscape)(small)### */
    @media screen and (min-width : 0px) and (max-width : 767px){
        /*Style*/
        .container{
            max-width: 100vw;
        }
    }

</style>
<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>
<BODY bgcolor="#ffffff" link="#000066" alink="#cc0000" vlink="#000066">

    <?php $claims_obj->Display_Headline($LDPendingClaims . '(' . ($in_outpatient == 1 ? 'INPATIENT' : "OUTPATIENT") . ')', '', '', 'Nhif_pending_claims.php', 'Claims :: Pending Claims'); ?>
    <!--Date starts here-->

    <script type="text/javascript"><?php require($root_path . 'include/inc_checkdate_lang.php'); ?>
    </script>
    <script language="javascript" src="<?php echo $root_path; ?>js/setdatetime.js"></script>
    <script language="javascript" src="<?php echo $root_path; ?>js/checkdate.js"></script>
    <script language="javascript" src="<?php echo $root_path; ?>js/dtpick_care2x.js"></script>
    <?php require_once($root_path . 'main_theme/footer.inc.php'); ?>

    <style type="text/css">
    .container {
        width: 100%;
        padding-bottom: 100px;
    }
    </style>
    
    <div class="container">        
        <div class="row">
            <div class="col-md-12">
                <form name="form1" class="form-inline"  action="" method="POST" onSubmit=" return CheckTarehe();">
                    <table class="table" width="100%">   
                        <tr>  
                            <th> 
                                <div class="alert1 alert1-info">
                                    <span>ONE MONTH PENDING CLAIMS IS SELECTED BY DEFAULT, TO SEARCH MORE CLAIMS CHOOSE DATE BELOW.</span>
                                </div>

                            </th>
                        </tr>
                        <tr>
                            <td>
                                <?php

                                
                                $_POST['date_from'] = (isset($_POST['date_from']) ? $_POST['date_from'] : isset($_REQUEST['date_from']) ? $_REQUEST['date_from'] : NULL);
                                $in_outpatient = (isset($_POST['in_outpatient']) ? $_POST['in_outpatient'] : $in_outpatient);
                                ?>
                                <input type="hidden" name="in_outpatient"  value="<?php echo $in_outpatient; ?>" >
                                <div class="bootstrap-iso">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-12">

                                                <!-- Form code begins -->

                                                <div class="form-group"> <!-- Date input -->
                                                    <label class="control-label" for="date_from">From Date</label>
                                                    <input class="form-control" id="datepicker" name="date_from" placeholder="MM/DD/YYY" type="text" value="<?php echo $_POST['date_from']; ?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group">
                                                    <label class="control-label" for="date">To</label>
                                                    <input class="form-control" id="datepicker1" name="date_to" placeholder="MM/DD/YYY" type="text" value="<?php echo $date_to; ?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group"> <!-- Submit button -->
                                                    <button class="btn btn-primary " name="submit" type="submit">Show</button>
                                                </div>
                                            </div>



                                        </div>    
                                    </div>
                                </div>


                            </td>

                        </tr>
                    </table>

                </form>
            </div>
        </div>


        <div class="row">
            <!--Date ends here-->

            <?php if (!isset($mode)) $mode = ''; ?>
            
            <?php $target = isset($target)? $target : 'BeforeBill'; ?>

            <div style="" class="col-lg-12 col-12">
                <table width="100%"  cellspacing=0  class="table datatable table-striped table-bordered table-hover  nowrap" cellspacing="0">
                    <thead class="thead-light">
                        <tr>

                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong><?php echo $LDPatientFileNo; ?></strong></a></div></th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong><?php echo $LDCardno; ?></strong></a></div></th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong><?php echo $LDFirstName; ?></strong></a>
                                </div>
                            </th>

                            </th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong><?php echo $LDLastName; ?></strong></a>
                                </div>
                            </th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong><?php echo $LDGender; ?></strong></a>
                                </div>
                            </th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong><?php echo $LDDateOfBirth; ?></strong></a>
                                </div>
                            </th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong><?php echo $LDTelephoneNo; ?></strong></a>
                                </div>
                            </th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong><?php echo $AuthorizationNo; ?></strong></a>
                                </div>
                            </th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong><?php echo $LDAttendanceDate; ?></strong></a>
                                </div>
                            </th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong>Registration</strong></a>
                                </div>
                            </th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong>Investigation</strong></a>
                                </div>
                            </th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong>Outpatient <br>Charges</strong></a>
                                </div>
                            </th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong>Surgery</strong></a>
                                </div>
                            </th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong>Days <br>Admitted</strong></a>
                                </div>
                            </th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong>Inpatient <br>Charges</strong></a>
                                </div>
                            </th>
                            <th><div align="center"><a href="nhif_pass.php?target=<?php echo $target ?>&patient=<?php
                                    echo $_REQUEST['patient'] . '&sort=care_encounter.encounter_date&date_from=' . $_POST['date_from'] . '&date_to=' . $date_to . '&sorttyp=';
                                    if (!$_REQUEST['sorttyp'])
                                        echo 'asc';
                                    if ($_REQUEST['sorttyp'] == 'asc')
                                        echo 'desc';
                                    if ($_REQUEST['sorttyp'] == 'desc')
                                        echo 'asc';
                                    ?> "><strong>Total</strong></a>
                                </div>
                            </th>
                            <th><div align="center"><strong><?php echo $LDInfo; ?></strong></div></th>

                        </tr>
                    </thead>

                    <TBODY>

                        <?php
                        require_once($root_path . 'include/inc_date_format_functions.php');



                        if (!isset($_POST['date_from']) || is_null($_POST['date_from']) || $_POST['date_from'] == '' || empty($_POST['date_from'])) {
                            $_POST['date_from'] = date('Y-m') . '-01';
                        } else {
                            list($month, $day, $year) = explode('/', $_POST['date_from']);
//                    $_POST['date_from'] = $year . '-' . $month . '-' . $day;
                        }
                        if (!isset($_POST['date_to']) || is_null($_POST['date_to']) || $_POST['date_to'] == '' || empty($_POST['date_to'])) {
                            $_POST['date_to'] = date('Y-m-d');
                        } else {
                            list($month, $day, $year) = explode('/', $_POST['date_to']);
//                    $_POST['date_to'] = $year . '-' . $month . '-' . $day;
                        }
                        $total_registration = 0;
                        $total_investigation = 0;
                        $total_outpatient_charges = 0;
                        $total_registration = 0;
                        $total_surgery = 0;
                        $total_registration = 0;
                        $total_days_admitted = 0;
                        $total_inpatient_charges = 0;
                        $grant_total = 0;

                        $pending_claims_query = $claims_obj->GetPendingClaims(array('in_outpatient' => $in_outpatient, 'sid' => $sid, 'date_from' => $_POST['date_from'], 'date_to' => $_POST['date_to'],'is_discharged'=>'0'));

                        // $pending_claims_query = $bill_obj->ShowNewQuotations($in_outpatient, $sid, $_POST['date_from'], $_POST['date_to']);

                        //print_r($pending_claims_query);die;


                        if (!is_null($pending_claims_query)) {
                            while ($row = $pending_claims_query->FetchRow()) {
                                //echo "<pre>";print_r($row); echo "</pre>";
                                if ($row['sex'] == 'm'or $row['sex'] == 'M') {
                                    $row['sex'] = 'Male';
                                } elseif ($row['sex'] == 'f' OR $row['sex'] == 'F') {
                                    $row['sex'] = 'Female';
                                }
                         

                                $result_cep = $bill_obj->GetNewQuotation_Prescriptions($row['visit_no'], $in_outpatient);
                                
                                $row_total_cep = 0;
                                 
                                 if ($in_outpatient == '1') {

                                     while($cep_row = $result_cep->FetchRow()){

                                        
                                      //Get unit price from NHIF table
                                        $sqlNhifUnitPriceCep = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE ItemCode = '".$cep_row['nhifItemCode']."' AND SchemeID = '".$cep_row['nhif_scheme_id']."' AND ItemTypeID<>'1' ";

                                        

                                        
                                        $NhifUnitPriceResultCep = $db->Execute($sqlNhifUnitPriceCep);
                                        $NhifUnitPriceCep = $NhifUnitPriceResultCep->FetchRow();
                                        $NhifUnitPriceCep = $NhifUnitPriceCep['UnitPrice'];

                                    //Get total Price
                                        $TotalPriceCep = $NhifUnitPriceCep * $cep_row['total_dosage'];
                                        $row_total_cep += $TotalPriceCep;

                                       }
  

                                      
                                  }else{

                                while($cep_row = $result_cep->FetchRow()){
                                      //Get unit price from NHIF table
                                    $sqlNhifUnitPriceCep = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE ItemCode = '".$cep_row['nhifItemCode']."' AND SchemeID = '".$cep_row['nhif_scheme_id']."' ";
                                    $NhifUnitPriceResultCep = $db->Execute($sqlNhifUnitPriceCep);
                                    $NhifUnitPriceCep = $NhifUnitPriceResultCep->FetchRow();
                                    $NhifUnitPriceCep = $NhifUnitPriceCep['UnitPrice'];

                                    //Get total Price
                                    $TotalPriceCep = $NhifUnitPriceCep * $cep_row['total_dosage'];
                                    $row_total_cep += $TotalPriceCep;







                                  }
                                }

                                

                 $result_lab = $bill_obj->GetNewQuotation_Laboratory($row['visit_no'], $in_outpatient);
                 $row_total_lab = 0;
                                while($lab_row = $result_lab->FetchRow()){
                                      //Get unit price from NHIF table
                                    $sqlNhifUnitPriceLab = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE ItemCode = '".$lab_row['nhifItemCode']."' AND SchemeID = '".$lab_row['nhif_scheme_id']."' ";
                                    $NhifUnitPriceResultLab = $db->Execute($sqlNhifUnitPriceLab);
                                    $NhifUnitPriceLab = $NhifUnitPriceResultLab->FetchRow();
                                    $NhifUnitPriceLab = $NhifUnitPriceLab['UnitPrice'];

                                    //Get total Price
                                    $TotalPriceLab = $NhifUnitPriceLab * 1;
                                    $row_total_lab += $TotalPriceLab;

                                }

                 $result_rad = $bill_obj->GetNewQuotation_Radiology($row['visit_no'], $in_outpatient);
                 $row_total_rad = 0;
                                while($rad_row = $result_rad->FetchRow()){
                                      //Get unit price from NHIF table
                                    $sqlNhifUnitPriceRad = "SELECT UnitPrice FROM care_tz_drugsandservices_nhifschemes WHERE ItemCode = '".$rad_row['nhifItemCode']."' AND SchemeID = '".$rad_row['nhif_scheme_id']."' ";
                                    $NhifUnitPriceResultRad = $db->Execute($sqlNhifUnitPriceRad);
                                    $NhifUnitPriceRad = $NhifUnitPriceResultRad->FetchRow();
                                    $NhifUnitPriceRad = $NhifUnitPriceRad['UnitPrice'];

                                    //Get total Price
                                    $TotalPriceRad = $NhifUnitPriceRad * $rad_row['dosage'];
                                    $row_total_rad += $TotalPriceRad;

                                }                
                

                


                                $row_total = 0;

                                $row_total = $row_total_cep + $row_total_lab + $row_total_rad;
                               

                                ?>
                                <tr>
                                    <td ><?= $row['selian_pid'] ?></td>
                                    <td ><?= $row['membership_nr'] ?></td>
                                    <td ><?= ucfirst(strtolower($row['name_first'])) ?></td>
                                    <td ><?= ucfirst(strtolower($row['name_last'])) ?></td>
                                    <td ><?= $row['sex'] ?></td> 
                                    <td ><?= $row['date_birth'] ?></td>
                                    <td ><?= $row['cellphone_1_nr'] ?></td>
                                    <td ><?= $row['nhif_authorization_number'] ?></td>
                                    <td ><?= $row['encounter_date'] ?></td>
                                    <td style="text-align: right"><?= $registration_charges ?></td>
                                    <td style="text-align: right"><?= $investigation_charges ?></td>
                                    <td style="text-align: right"><?= $outpatient_charges ?></td>
                                    <td style="text-align: right"><?= $surgery_charges ?></td>
                                    <td style="text-align: right"><?= $in_outpatient == 1 ? $days_admitted->days : '' ?></td>
                                    <td style="text-align: right"><?= $inpatient_charges ?></td>
                                    <td style="text-align: right"><?= number_format($row_total,2) ?></td>
                                    <td ><div align="center">
                                            <a href="../../modules/nhif/nhif_pass.php<?= URL_APPEND ?>&patient=<?= $in_outpatient ?>&lang=en&target=claimsreview&encounter_nr=<?= $row['visit_no'] ?>&date_from=<?= $date_from ?>&date_to=<?= $date_to ?>" title="Visit Details : Click to show data"><button type="button">>></button></a>
                                        </div>
                                    </td>

                                </tr>

                                <?php
                            }
                        }
//                $claims_obj->ShowPendingClaims($in_outpatient, $sid, $_POST['date_from'], $_POST['date_to']);
                        ?>
                    </TBODY>
                </table>
            </div>

        </div>
    </div>


         

    <?php $claims_obj->Display_Footer($LDCreatenewquotation, '', '', 'billing_create_2.php', 'Billing :: Create Quotation'); ?>

    <?php $claims_obj->Display_Credits(); ?>
