<?php
ini_set("memory_limit", "-1");
set_time_limit(0);

require_once './roots.php';
include_once $root_path . 'include/inc_environment_global.php';
require_once $root_path . 'include/care_api_classes/class_nhif_claims.php';
require_once $root_path . 'tcpdf/tcpdf.php';
require_once $root_path . 'tcpdf/tcpdf_autoconfig.php';

$encounter_nr = $_GET['encounter_nr'];
$type = $_GET['type'];
global $db;

class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES . 'logo_example.jpg';

		global $db;
		$encounter_nr = $_GET['encounter_nr'];

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

		$authorization_number = "";
		$pid = 0;
		$patientName = "";
		$membership_nr = "";

		$encounterSQL = "SELECT pid, nhif_authorization_number FROM care_encounter WHERE encounter_nr = '$encounter_nr'";
		$encounterResult = $db->Execute($encounterSQL);
		if ($encounterResult && $encounterResult->RecordCount() > 0) {
			$row = $encounterResult->FetchRow();
			$pid = $row['pid'];
			$authorization_number = $row['nhif_authorization_number'];
		}

		$patientSQL = "SELECT name_first, name_2, name_last, date_birth, membership_nr FROM care_person WHERE pid = '$pid' ";
		$patientResult = $db->Execute($patientSQL);
		if (@$patientResult && $patientResult->RecordCount() > 0) {
			$row = $patientResult->FetchRow();
			$patientName = $row['name_first'] . " " . $row['name_2'] . " " . $row['name_last'];
			$membership_nr = $row['membership_nr'];
		}

		// $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('times', 'B', 16);
		// Title
		$this->Cell(0, 15, $companyName, 0, 1, 'C', 0, '', 1, false, 'M', 'M');
		$this->Cell(0, 15, $companyAddress, 0, 1, 'C', 0, '', 1, false, 'M', 'M');
		$this->SetFont('times', '', 12);

		$this->setX(15);
		$this->Cell(110, 10, "Patient Name: " . $patientName, 0, 0, 'L', 0, '', 0, false, 'M', 'M');
		$this->setX(110);
		$this->Cell(10, 10, "Authorization Number: " . $authorization_number, 0, 1, 'L', 0, '', 0, false, 'M', 'M');

		$this->setX(15);
		$this->Cell(110, 10, "Patient ID: " . $pid, 0, 0, 'L', 0, '', 0, false, 'M', 'M');
		$this->setX(110);
		$this->Cell(10, 10, "Visit No: " . $encounter_nr, 0, 0, 'L', 0, '', 0, false, 'M', 'M');
		$this->setX(150);
		$this->Cell(10, 10, "Card No: " . $membership_nr, 0, 1, 'L', 0, '', 0, false, 'M', 'M');

		$this->Cell(0, 1, '', "B", 1, 'C', 0, '', 0, false, 'M', 'M');

	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('times', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Rayton Kiwelu');
$pdf->SetTitle('Patient Encounter File');
$pdf->SetSubject('Patient Encounter File');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
	require_once dirname(__FILE__) . '/lang/eng.php';
	$pdf->setLanguageArray($l);
}

$pdf->SetFont('times', '', 12);

$pdf->AddPage();
$pdf->SetY(45);

$noteSQL = "SELECT short_notes, notes FROM care_encounter_notes WHERE encounter_nr = '$encounter_nr' ";
$noteResult = $db->Execute($noteSQL);
$notes = array();
if (@$noteResult && $noteResult->RecordCount() > 0) {
	$notes = $noteResult->GetArray();
}

if (@$notes) {
	$pdf->SetFont('times', 'B', 12);
	$pdf->Cell(0, 15, "Patient Case Notes", '', 1, 'L', 0, '', 0, false, 'M', 'M');
	$pdf->ln(5);

	foreach ($notes as $note) {
		$pdf->SetX(15);
		$pdf->SetFont('times', 'B', 11);
		$pdf->Cell(0, 5, $note['short_notes'], '', 1, 'L', 0, '', 0, false, 'T', 'T');
		$pdf->SetFont('times', '', 11);
		$pdf->SetX(20);
		$pdf->Cell(0, 10, $note['notes'], '', 1, 'L', 0, '', 0, false, 'T', 'T');
	}
	$pdf->ln(5);
}

$radiologySQL="SELECT rr.clinical_info,fr.findings, ds.item_description, fr.doctor_id, fr.findings_date,rr.send_doctor, rr.send_date FROM care_test_request_radio rr LEFT JOIN care_test_findings_radio fr ON rr.batch_nr=fr.batch_nr INNER JOIN care_tz_drugsandservices ds ON ds.item_id=rr.item_id WHERE rr.encounter_nr = '$encounter_nr'";


$radioResult = $db->Execute($radiologySQL);
$radios = array();
if (@$radioResult && $radioResult->RecordCount() > 0) {
	$radios = $radioResult->GetArray();
}

if (@$radios) {
	$pdf->SetFont('times', 'B', 12);

	$pdf->Cell(0, 15, "Clinical Radiology Summary", '', 1, 'L', 0, '', 0, false, 'M', 'M');

	$tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="0.00001">
 	<tr style="font-weight: bold;">
        <td>Date</td>
        <td>requested test</td>
        <td>Clinical Information</td>
        <td>Findings</td>
        <td>Doctor</td>
    </tr>
EOD;

	$pdf->SetFont('times', '', 11);

	foreach ($radios as $radio) {
		$date = date('d/m/Y', strtotime($radio['send_date']));
		$doctor = $radio['send_doctor'];
		$test = $radio['item_description'];
		$findings = $radio['findings'];
		$clinical_info = $radio['clinical_info'];
		$tbl .= '<tr height="20" style="line-height: 20px;" >
        <td>' . $date . '</td>
        <td ><br>' . $test . '</td>
        <td><br>' . $clinical_info . '</td>
        <td><br>' . $findings . '</td>
        <td><br>' . $doctor . '</td>
    </tr>';
	}
	$tbl .= "</table>";

	$pdf->writeHTML($tbl, true, false, false, false, '');
	$pdf->ln(10);
}

$labSQL = "SELECT cp.name, cp.median, cp.msr_unit, cp.nr, ct.parameter_value FROM care_test_findings_chemlab cf
INNER JOIN care_test_findings_chemlabor_sub ct
	ON (cf.job_id = ct.job_id AND cf.encounter_nr = ct.encounter_nr)
INNER JOIN care_tz_laboratory_param cp
	ON (cp.id = ct.paramater_name)
WHERE  cf.status NOT IN ('deleted','hidden','inactive','void')
AND cf.encounter_nr='$encounter_nr'
ORDER BY cp.name, ct.sort_order, ct.test_date";
$labResult = $db->Execute($labSQL);
$labTests = array();
if (@$labResult && $labResult->RecordCount() > 0) {
	$labTests = $labResult->GetArray();
}

if (@$labTests) {

	$pdf->SetFont('times', 'B', 12);
	$pdf->Cell(0, 15, "Clinical Laboratory Result", '', 1, 'L', 0, '', 0, false, 'M', 'M');

	$pdf->SetFont('times', '', 11);

	$tbl2 = <<<EOD
<table cellspacing="0" cellpadding="1" border="1">
 	<tr style="font-weight: bold;">
        <td>Parameter</td>
        <td>Normal Range</td>
        <td>Msr Unit</td>
        <td>Results</td>
    </tr>
EOD;

	foreach ($labTests as $lab) {
		$parameter = $lab['name'];
		$normal_range = $lab['median'];
		$unit = $lab['msr_unit'];
		$result = $lab['parameter_value'];
		$tbl2 .= '<tr height="20" style="line-height: 20px;" >
        <td>' . $parameter . '</td>
        <td ><br>' . $normal_range . '</td>
        <td><br>' . $unit . '</td>
        <td><br>' . $result . '</td>
    </tr>';
	}
	$tbl2 .= "</table>";
	$pdf->writeHTML($tbl2, true, false, false, false, '');

}

//Close and output PDF document



$save = @$_GET['save'] ? $_GET['save'] : "";

if (@$save) {
	$pdf->Output(__DIR__ . '/uploads/patientFile' . $encounter_nr . '.pdf', 'F');

} else {
	$pdf->Output('patientFile.pdf', 'I');
}


 $filePath='./uploads/patientFile' . $encounter_nr . '.pdf';




    $file = file($filePath);




    $endfile= trim($file[count($file) - 1]);

    $n="%%EOF";


    if ($endfile === $n) {
      $status="good";
    } else {
      $status="corrupted";
   }





    if (file_exists($filePath) && $status === "good") {
      echo "file_created";
      
   }else{
     echo "file_not_created";
    }
