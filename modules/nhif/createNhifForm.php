<?php
ini_set("memory_limit", "-1");
set_time_limit(0);

require_once './roots.php';
include_once $root_path . 'include/inc_environment_global.php';
require_once $root_path . 'include/care_api_classes/class_encounter.php';
require_once $root_path . 'include/care_api_classes/class_tz_billing.php';
require_once $root_path . 'include/care_api_classes/class_nhif_claims.php';
require_once $root_path . 'include/care_api_classes/class_globalconfig.php';
require_once $root_path . 'tcpdf/tcpdf.php';
require_once $root_path . 'tcpdf/tcpdf_autoconfig.php';

$encounter_nr = $_GET['encounter_nr'];
$type = $_GET['type'];
global $db;

$enc_obj = new Encounter;
$claims_obj = new Nhif_claims;



//make tcpdf object
$pdf = new TCPDF('L','mm', 'A4');

//remove default header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


//add page
$pdf->AddPage();

/*
$pdf->SetFont('Helvetica','',14);
$pdf->Cell(190,10,"Ostech IT Engineering LTD",1,1,'C');

$pdf->SetFont('Helvetica','',8);
$pdf->Cell(190,5,"Employee list",1,1,'C');

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(30,5,"Class",1,1,'C');
$pdf->Cell(160,5,": Programming 101",1);
$pdf->Ln();

$pdf->Cell(30,5,"Teacher Name",1,'C');
$pdf->Cell(160,5,": Professor Smith",1);
$pdf->Ln();
*/



$doctor = $claims_obj->GetDignosisDocName($encounter_nr);
$docUser = $claims_obj->GetDocUser($doctor);
$qDetailsRow=$claims_obj->GetqualificationDetails($doctor);  
$doctorQualificationName=$qDetailsRow['sname'];


$login_id = $docUser['login_id'];


$image_file = '../../modules/nhif/images/' . 'NHIF_logo.jpg';
$pdf->Image($image_file,10,10,20);

$muhuri_file = '<img src="../../gui/img/common/default/'.'muhuri.png"  width="61" height="36">';

$doctorSignature = '<img src="../../modules/nhif/signatures/'.$login_id.'.png"  width="29" height="16">';
//$patientSignature = '<img src="../../modules/nhif/signatures/signature'.$encounter_nr.'.png"  width="726"   height="134" style="vertical-align:top" >';
$patientSignature = '<img src="../../modules/nhif/signatures/signature'.$encounter_nr.'.png"  width="223"   height="46" style="vertical-align:top;float:left" >';





//$pdf->writeHTML($doctorSignature, true, 0, true, 0);





$pdf->SetFont('Helvetica','B',14);
$pdf->Cell('','',"CONFIDENTIAL",'','','C');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell('','',"Form NHIF 2A",'','','R');
$pdf->Ln();
$pdf->SetFont('Helvetica','',8);
$pdf->Cell('','',"Regulation 18(1)",'','','R');

$pdf->Ln();
$pdf->SetFont('Helvetica','B',14);
$pdf->Cell('','',"THE NHIF - HEALTH PROVIDER IN/OUT PATIENT CLAIM FORM",'','','C');
$pdf->Ln();

$sqlEncounterDate="SELECT encounter_date FROM care_encounter WHERE encounter_nr=".$encounter_nr;

$resultEncounterDate=$db->Execute($sqlEncounterDate);
if ($rowEncounterDate=$resultEncounterDate->FetchRow()) {
    $claims_details=$rowEncounterDate;

}

$companyName = "";
$companyAddress = "";

$companySQL = "SELECT value FROM care_config_global WHERE type = 'main_info_name'";
$companyResult = $db->Execute($companySQL);
if (@$companyResult) {
    $company = $companyResult->FetchRow();
    $companyName = $company['value'];
}

$companySQL = "SELECT value FROM care_config_global WHERE type = 'main_info_address'";
$companyResult = $db->Execute($companySQL);
if (@$companyResult) {
    $company = $companyResult->FetchRow();
    $companyAddress = $company['value'];
}







 $claims_details_query = $claims_obj->ShowPendingClaimsDetails(array('in_outpatient' => $type, 'encounter_nr' => $encounter_nr)); 

if (!is_null($claims_details_query)) {
    $rowDetails=$claims_details_query->FetchRow();
    
}


$consultation_total_cost = 0;
    $consultations = $claims_obj->GetConsultations($encounter_nr,$rowDetails['PatientTypeCode']);
    foreach ($consultations as $cons) {
        $consultation_total_cost += $cons['row_amount'];
    }



switch ($rowDetails['PatientTypeCode']) {
    case 'OUT':
       
       //$ward_dept_name=$enc_obj->CurrentDeptName($encounter_nr);
       $sqlDeptNr="SELECT current_dept_nr FROM care_encounter WHERE encounter_nr=".$encounter_nr;
       $sqlDeptNrResult=$db->Execute($sqlDeptNr);
       if ($rowDept=$sqlDeptNrResult->FetchRow()) {
           $sqlDeptName="SELECT name_formal FROM care_department WHERE nr=".$rowDept['current_dept_nr'];
           $resultDeptName=$db->Execute($sqlDeptName);
           if ($ward_dept_name=$resultDeptName->FetchRow()) {
               $ward_dept_name=$ward_dept_name['name_formal'];
           }
       }        


        break;
    
    default:

    $sqlDeptNr="SELECT current_ward_nr FROM care_encounter WHERE encounter_nr=".$encounter_nr;
       $sqlDeptNrResult=$db->Execute($sqlDeptNr);
       if ($rowDept=$sqlDeptNrResult->FetchRow()) {
           $sqlDeptName="SELECT ward_id FROM care_ward WHERE nr=".$rowDept['current_ward_nr'];
           $resultDeptName=$db->Execute($sqlDeptName);
           if ($ward_dept_name=$resultDeptName->FetchRow()) {
               $ward_dept_name=$ward_dept_name['ward_id'];
           }

    //$ward_dept_name=$enc_obj->CurrentWardName($encounter_nr);
        
        break;
}
}



 

  


// $tpdf->MultiCell(12,15,'','L','C',1,0);   // Left border only 
// $tpdf->MultiCell(12,15,'','LR','C',1,0);  // Left and Right border only 
// $tpdf->MultiCell(12,15,'','LRB','C',1,0); // Left,Right and Bottom border only 
// $tpdf->MultiCell(12,15,'','LRBT','C',1,0);// Full border






//$dots='.............';


$number=$claims_obj->getSerialNumber($encounter_nr, $claims_details);
$serialNumber='Serial No: '.$number;


$preliminaryDX=$claims_obj->GetDignosisCodesByType($encounter_nr, 'preliminary');
$finalDX=$claims_obj->GetDignosisCodesByType($encounter_nr, 'final');







$fill=$pdf->SetFillColor(249,249,249); // Grey
//$this -> TCPDF -> Cell(95,$cellHigh,$data,'L',0,'L',$fill,'',0,false,'T','C');


$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(250,'',$serialNumber,'','','R');
//$pdf->Cell(100,'',"555555",'','','R');
//Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M') ⇒ Object

$pdf->Ln();
$pdf->SetFont('Helvetica','B',6);
$pdf->Cell(160,'','A:   PARTICULARS:','','','L');
$pdf->Ln();
$pdf->SetFont('Helvetica','B',6);
$pdf->Cell(160,'','A1: Health Facility Particulars','','','L');
$pdf->SetFont('Helvetica','',8);
$pdf->Ln();
$pdf->Cell(40,5,'        1. Name of Health Facility','','','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(50,5,$companyName,'B','C','');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(14,5,'2.Address','','','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(58,5,$companyAddress,'B','','');
//$pdf->Cell(25,5,'Morogoro','','','',$fill);

// $pdf->SetFont('Helvetica','',8);
// $pdf->Cell(21,5,'3.Consultation','','','');
// $pdf->SetFont('Helvetica','B',8);
// $pdf->Cell(20,5,number_format($consultation_total_cost),'','','');

//$pdf->Ln();
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(25,5,'        3.Department','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(50,5,$ward_dept_name,'B','C');

$pdf->SetFont('Helvetica','',8);
$pdf->Cell(30,5,'4.Date Of Attendance','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(18,5,date('d.m.Y',strtotime($rowDetails['encounter_date'])),'B','C');
$pdf->Ln();
$pdf->SetFont('Helvetica','B',6);
$pdf->Cell(160,'','A2: Patient Particulars','','','L');
$pdf->Ln();

$pdf->SetFont('Helvetica','',8);
$pdf->Cell(33,5,'        1.Name of Patient','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(50,5,$rowDetails['name_first'].' '.$rowDetails['name_last'],'B','C');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(10,5,'2.DOB:','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(18,5,DATE('d-m-Y',strtotime($rowDetails['date_birth'])),'B','C');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(10,5,'3.Sex:','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(5,5,strtoupper($rowDetails['sex']),'B','C');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(20,5,'4.Occupation:','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(20,5,'','B','C','1');
//$pdf->Ln();
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(30,5,'        5.Patient File No.:','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(50,5,$rowDetails['selian_pid'],'B','C');
$pdf->Ln();
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(33,5,'6.Physical Address','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(50,5,$claims_obj->GetPatientPhysicalAddress($rowDetails['ward'], $rowDetails['district']),'B','C');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(20,5,'7.Card Number:','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(38,5,$rowDetails['membership_nr'],'B','C');
//$pdf->Ln();
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(38,5,'        8.Authorization No:','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(38,5,$rowDetails['nhif_authorization_number'],'B','C');
$pdf->SetFont('Helvetica','',8);
$pdf->Ln();
$pdf->Cell(15,5,'9.Vote:','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(18,5,$rowDetails['employee_id'],'B','C');
$pdf->SetFont('Helvetica','',8);
//$pdf->Ln();
$pdf->Cell(50,5,'       10.Preliminary Diagnosis (Code):','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(55,5,$preliminaryDX,'B');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(45,5,'11.Final Diagnosis (Code):','');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(55,5,$finalDX,'B','');















$pdf->Ln();



$pdf->Ln();
$pdf->Cell(33,5,'B: Details / Cost of services','C');
$pdf->Ln();
$pdf->SetFont('Helvetica','B',12);
$pdf->Cell(185,5,"Description",1,'C','',$fill);
$pdf->Cell(30,5,"Item Code",1,'','',$fill);
$pdf->Cell(15,5,"Qty",1,'','',$fill);
$pdf->Cell(25,5,"Unit Price",1,'','',$fill);
$pdf->Cell(25,5,"Amount",1,'','',$fill);
$pdf->Ln();


//consultation
if ($consultation_total_cost > 0){

     $pdf->SetFont('Helvetica','',12);
     $pdf->Cell(280,5,"CONSULTATIONS",1,'C','',$fill);
     $pdf->Ln();

    foreach ($consultations as $consultation){

      $pdf->Cell(185,5,$consultation['description'],1,'C','');
      
      $pdf->Cell(30,5,$consultation['nhif_item_code'],1,'','');
      $pdf->Cell(15,5,number_format($consultation['amount']),1,'','');
      $pdf->Cell(25,5,number_format($consultation['price']),1,'','');
      $pdf->Cell(25,5,number_format($consultation['row_amount']),1,'','');
      $pdf->Ln(); 


    }
    $pdf->Cell(255,5,'SUB TOTAL',1,'C','');
    $pdf->Cell(25,5,number_format($consultation_total_cost),1,'','');  

}
$pdf->Ln(); 

//Investigation
$investigation_total_cost = 0;

$investigations = $claims_obj->GetInvestigations($encounter_nr);
foreach ($investigations as $investigation) {
    $investigation_total_cost += $investigation['row_amount'];
  }

  if ($investigation_total_cost > 0){
     $pdf->SetFont('Helvetica','',12);
     $pdf->Cell(280,5,"INVESTIGATIONS",1,'C','',$fill);
     $pdf->Ln();
    foreach ($investigations as $investigation){

      $pdf->Cell(185,5,$investigation['description'],1,'C','');
      $pdf->Cell(30,5,$investigation['nhif_item_code'],1,'','');
      $pdf->Cell(15,5,number_format($investigation['amount']),1,'','');
      $pdf->Cell(25,5,number_format($investigation['price']),1,'','');
      $pdf->Cell(25,5,number_format($investigation['row_amount']),1,'','');
      $pdf->Ln();

    }

    $pdf->Cell(255,5,'SUB TOTAL',1,'C','');
    $pdf->Cell(25,5,number_format($investigation_total_cost),1,'','');  

  }

   $pdf->Ln();


  $drugs_total_cost = 0;
  $medicines = $claims_obj->GetMedicines($encounter_nr);
  
  foreach ($medicines as $medicine) {
    $drugs_total_cost += $medicine['row_amount'];
  }

     


     if ($drugs_total_cost > 0){
     $pdf->SetFont('Helvetica','',12);
     $pdf->Cell(280,5,"MEDICINES",1,'C','',$fill);
     $pdf->Ln();
     

     foreach ($medicines as $medicine){

      $pdf->Cell(185,5,$medicine['description'],1,'C','');
      
      $pdf->Cell(30,5,$medicine['nhif_item_code'],1,'','');
      $pdf->Cell(15,5,number_format($medicine['amount']),1,'','');
      $pdf->Cell(25,5,number_format($medicine['price']),1,'','');
      $pdf->Cell(25,5,number_format($medicine['row_amount']),1,'','');
      $pdf->Ln(); 


    }

    $pdf->Cell(255,5,'SUB TOTAL',1,'C','');
    $pdf->Cell(25,5,number_format($drugs_total_cost),1,'','');


  }

  $pdf->Ln();


  $procedure_total_cost = 0;
  $procedures = $claims_obj->GetProcedures($encounter_nr);
  foreach ($procedures as $procedure) {
    $procedure_total_cost += $procedure['row_amount'];
  }


  if ($procedure_total_cost > 0){
     $pdf->SetFont('Helvetica','',12);
     $pdf->Cell(280,5,"PROCEDURES",1,'C','',$fill);
     $pdf->Ln();

     foreach ($procedures as $procedure){
      $pdf->Cell(185,5,$procedure['description'],1,'C','');
      
      $pdf->Cell(30,5,$procedure['nhif_item_code'],1,'','');
      $pdf->Cell(15,5,number_format($procedure['amount']),1,'','');
      $pdf->Cell(25,5,number_format($procedure['price']),1,'','');
      $pdf->Cell(25,5,number_format($procedure['row_amount']),1,'','');
      $pdf->Ln();

     }

     $pdf->Cell(255,5,'SUB TOTAL',1,'C','');
     $pdf->Cell(25,5,number_format($procedure_total_cost),1,'','');




  }
$pdf->Ln();

  $supplies_total_cost = 0;
  $supplies = $claims_obj->GetSupplies($encounter_nr);
  foreach ($supplies as $supply) {
    $supplies_total_cost += $supply['row_amount'];
  }

  if ($supplies_total_cost > 0){

    $pdf->SetFont('Helvetica','',12);
     $pdf->Cell(280,5,"SUPPLIES/SERVICES",1,'C','',$fill);
     $pdf->Ln();


     foreach ($supplies as $supply){

      $pdf->Cell(185,5,$supply['description'],1,'C','');      
      $pdf->Cell(30,5,$supply['nhif_item_code'],1,'','');
      $pdf->Cell(15,5,number_format($supply['amount']),1,'','');
      $pdf->Cell(25,5,number_format($supply['price']),1,'','');
      $pdf->Cell(25,5,number_format($supply['row_amount']),1,'','');
      $pdf->Ln();


     }

     $pdf->Cell(255,5,'SUB TOTAL',1,'C','');
     $pdf->Cell(25,5,number_format($supplies_total_cost),1,'','');

  }


  $pdf->Ln();


 $grandtotal = number_format($consultation_total_cost + $investigation_total_cost + $drugs_total_cost + $procedure_total_cost + $supplies_total_cost);



     $pdf->Cell(255,5,'GRAND TOTAL',1,'C','');
     $pdf->Cell(25,5,$grandtotal,1,'','');
     $pdf->Ln();

     //echo $encounter_nr; die;

  

//function writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true)

// function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')




//****************************************************************  

$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(43,10,'C: Name of attending clinician:','','','');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(34,5,$doctor,'B','C','');

//****************************************************************



//****************************************************************
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(24,10,'Qualifications:','','','');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(40,5,$doctorQualificationName,'B','C','');
//****************************************************************

//****************************************************************
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(21,10,'MCT Reg. No:','','','');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(28,10,$docUser['practitioner_nr'],'B','C','');

//****************************************************************



$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(15,10,'Mob. No:','','','');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(25,10,$docUser[' tel_no'],'','C','');
$pdf->Ln();


//****************************************************************
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(18,10,'Signature:','','','');
$pdf->writeHTMLCell('',18,'','',$doctorSignature,'');
//****************************************************************
  $pdf->Ln();
  $pdf->Ln();
  

//****************************************************************

$pdf->SetFont('Helvetica','B',10);
$pdf->Cell(280,10,'D: Uthibitisho wa mgonjwa/Patient Certification:','','','L');
$pdf->Ln();
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(280,5,'Nathibitisha kuwa nimepokea huduma zilizoanishwa hapo juu na natambua kwamba ni kosa kisheria kukiri kupata matibabu ambayo hayajatolewa.','','','L');
$pdf->Ln();

$pdf->Cell(280,5,'I certify that I received the above mentioned services as witnessed by my signature hereunder and I understand that it is illegal to provide false testimony.','','','L');

$pdf->Ln();
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(24,10,'Jina/Name:','','','');
//$pdf->Cell(35,10,$rowDetails['name_first'].' '.$rowDetails['name_last'],'B','C');
$pdf->Cell(35,10,$rowDetails['name_first'].' '.$rowDetails['name_last'],'B','C');

$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(24,10,'Tarehe(Date)','','','');
$pdf->Cell(35,10,date('d-m-Y'),'B','C');
$pdf->Cell(40,10,'Namba ya Simu(Mobile No.)','','','');
$pdf->Cell(28,10,$rowDetails['phone_1_nr'],'B','C');

$pdf->Ln();


$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(18,10,'Signature:','','','');
$pdf->writeHTMLCell('',18,'','',$patientSignature,'');
$pdf->Ln();


$pdf->Cell(280,5,'Hakikisha unasaini fomu baada ya kupatiwa huduma na kupatiwa nakala ya fomu hii iliyojazwa huduma ulizopatiwa.','','','L');
$pdf->Ln();
$pdf->Cell(280,5,'Make sure you receive a copy of the form you signed.','','','L');

$pdf->Ln();
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(280,5,'E: Description of In/Out-patient Management/any other additional Information(a separate sheet of paper can be used):.','','','L');
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(175,5,'','B','','L');
$pdf->Ln();
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(18,5,'F: Claimant Certification:','','','');
$pdf->Ln();
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(75,5,'I Certify that I provided the above services.','','','L');
$pdf->Ln();


$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(10,5,'Name:','','','');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(40,5,$docUser['name'],'','C','');

$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(15,5,'Signature:','','','');
$pdf->writeHTMLCell('',18,'','',$doctorSignature,'');

$pdf->Ln();

$pdf->Cell(20,20,'Official Stamp:','','','');

$pdf->writeHTMLCell('','','','',$muhuri_file);

$pdf->Ln();
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(90,5,'Patient should sign the form after completion of service.','','','L');
$pdf->Ln();
$pdf->Cell(160,5,'Before reffering the patient to another facility. The prescriber should be satisfied for the missing item and its alternative within the facility.','','','L');
$pdf->Ln();
$pdf->Cell(160,5,'Any falsified information may subject you to prosecution in accordance with NHIF Act Cap 395.','','','L');
$pdf->Ln();
$pdf->SetFont('Helvetica','I',8);
$pdf->Cell(160,5,'.Original form to be submitted to NHIF Offices by the treating Health Facility(Yellow). 1st Copy to be retained by the treating Facility (Pink). ','','','L');
$pdf->Ln();

$pdf->Cell(160,5,'2nd Copy to be given to NHIF beneficiary (Blue) ','','','L');








$save = @$_GET['save'] ? $_GET['save'] : "";

if (@$save) {
	$pdf->Output(__DIR__ . '/uploads/nhifForm' . $encounter_nr . '.pdf', 'F');
  //$pdf->Output('./uploads/nhifForm'.$encounter_nr.'pdf', 'D');

   //check file existance
   $filePath='./uploads/nhifForm' . $encounter_nr . '.pdf';


   $file = file($filePath);
   $endfile= trim($file[count($file) - 1]);
   $n="%%EOF";


   if ($endfile === $n) {
     $status="good";
   } else {
     $status="corrupted";
   }



   if (file_exists($filePath) && $status === "good") {
     echo "file created";
   }else{
    echo "no file";
   }

   





} else {
	$pdf->Output('nhifFrom.pdf', 'I');
}






?>