<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3000);
require_once('./roots.php');
require_once($root_path.'modules/dashboard/colorHelper.php');
require_once $root_path.'vendor/autoload.php';
require_once $root_path.'generated-conf/config.php';

include_once($root_path . 'include/inc_environment_global.php');
global $db;
require_once($root_path . 'include/care_api_classes/class_reporting.php');

$reporting = new Reporting();
$period = $_GET['period'];
$insurance = $_GET['insurance'];
$type = $_GET['type'];
$category = @$_GET['category']?$_GET['category']:"";
use Carbon\CarbonPeriod;
use Carbon\Carbon;

$year = date('Y');
$lastYear = date('Y')-1;

$weekdays = array();
$weeklables = array();
$lastWeekDays = array();
$lastWeekLabels = array();
$thisMonthLabels = array();
$thisMonthDays = array();
$lastMonthLables = array();
$lastMonthDays = array();
$rangeDates = array();



$months = array(
	'01' => 'Jan', 
	'02' => 'Feb',
	'03' => 'Mar',
	'04' => 'Apr',
	'05' => 'May', 
	'06' => 'Jun', 
	'07' => 'Jul', 
	'08' => 'Aug',
	'09' => 'Sep',
	'10' => 'Oct', 
	'11' => 'Nov',
	'12' => 'Dec'
);

$lastMonths = array(
	'01' => 'Jan ' . $lastYear, 
	'02' => 'Feb ' . $lastYear,
	'03' => 'Mar ' . $lastYear,
	'04' => 'Apr ' . $lastYear,
	'05' => 'May ' . $lastYear, 
	'06' => 'Jun ' . $lastYear, 
	'07' => 'Jul ' . $lastYear, 
	'08' => 'Aug ' . $lastYear,
	'09' => 'Sep ' . $lastYear,
	'10' => 'Oct ' . $lastYear, 
	'11' => 'Nov ' . $lastYear,
	'12' => 'Dec ' . $lastYear
);

require_once('prepareLabels.php');


$legendNames = array();
$patientsStatus = array();

$servedPaidPatients = array();
$servedUnpaidPatients = array();
$pendingPaidPatients = array();
$pendingUnpaidPatients = array();

$servedPaidBills = array();
$servedUnpaidBills = array();
$pendingPaidBills = array();
$pendingUnpaidBills = array();

$totalServedPaid = 0;
$totalServedUnpaid = 0;
$totalPendingPaid = 0;
$totalPendingUnpaid = 0;

$pendingPaidPatientsRecords = array();
$pendingUnpaidPatientsRecords = array();
$servedPaidPatientsRecords = array();
$servedUnpaidPatientsRecords = array();

$colorHelper = new ColorHelper();

// Bills 

$prescriptionsSQL = "SELECT cp.pid, UPPER(name_last) as name_last, CONCAT(name_first,' ', name_2) AS name_first, cp.insurance_ID, cp.sub_insurance_id, cp.selian_pid, cep.encounter_nr, cep.prescribe_date, cep.bill_number, cep.status, cep.prescriber, cep.issuer,
	cd.unit_price,
	cd.unit_price_1,
	cd.unit_price_2,
	cd.unit_price_3,
	cd.unit_price_4,
	cd.unit_price_5,
	cd.unit_price_6,
	cd.unit_price_7,
	cd.unit_price_8,
	cd.unit_price_9,
	cd.unit_price_10,
	cd.unit_price_11,
	cd.unit_price_12,
	cd.unit_price_13,
	cd.unit_price_14,
	cd.unit_price_15 
	FROM care_encounter_prescription cep
    INNER JOIN care_encounter ce
       ON cep.encounter_nr = ce.encounter_nr
    INNER JOIN care_person cp
       ON ce.pid = cp.pid 
	INNER JOIN care_tz_drugsandservices cd
	   ON cep.article_item_number = cd.item_id 
	AND 
	   ( cd.purchasing_class = 'drug_list' 
	     OR cd.purchasing_class = 'drug_list_ctc' 
	     OR cd.purchasing_class = 'drug_list_nhif'
	     OR cd.purchasing_class = 'service'
	     OR cd.purchasing_class = 'minor_proc_op'
	     OR cd.purchasing_class = 'surgical_op'
	     OR cd.purchasing_class = 'dental'
	    )
	AND cep.status = 'done'
	AND cep.bill_number = 0
    AND cep.prescribe_date BETWEEN '$minimum_date' AND '$maximum_date' ";

if (!empty($insurance) || $insurance === "0") {
	$prescriptionsSQL .= " AND cp.insurance_ID IN ($insurance) ";	
}

if (@$type) {
	if ($type == 'Inpatient') {
    	$prescriptionsSQL .= ' AND ce.encounter_class_nr=1 ';
	}
	if ($type == 'Outpatient') {
        $prescriptionsSQL .= ' AND ce.encounter_class_nr=2 ';
    }
}
$prescriptionsSQL .= " GROUP by cep.prescribe_date, cep.encounter_nr, cp.pid, cp.selian_pid, name_first, name_last ORDER BY cep.prescribe_date DESC";
$prescriptionsRows = [];
$prescriptionsResult = $db->Execute($prescriptionsSQL);
if (@$prescriptionsResult && $prescriptionsResult->RecordCount()) {
	$prescriptionsRows = $prescriptionsResult->GetArray();
}

foreach ($rangeDates as $keyrange => $rangeDate) {

	$minDate = $rangeDate['min'];
	$maxDate = $rangeDate['max'];

	$paidBillTotal = 0;
	$unpaidBillTotal = 0;

	$rangePendingUnpaidPatients = 0;
	$rangePendingPaidPatients = 0;
	$rangeServedUnpaidPatients = 0;
	$rangeServedPaidPatients = 0;

	$pendingUnpaidRows = [];
	$pendingPaidRows = [];
	$pendingunpaidnumber = 0;
	$pendingpaidnumber = 0;
	$servedunpaidnumber = 0;
	$servedpaidnumber = 0;

	foreach ($prescriptionsRows as $pbKey => $pb) {
		
		if(strtotime($pb['prescribe_date']) >= strtotime($rangeDate['min']) && strtotime($pb['prescribe_date']) <= strtotime($rangeDate['max'])){
			
			// Pending Unpaid Patients
			if ($pb['bill_number'] == 0 && ($pb['status'] == "pending" || $pb['status'] == '')) {

				$priceRow = $reporting->getPatientInsurancePrice($pb['insurance_ID'], $pb['sub_insurance_id']);

		        $priceColumn = @$priceRow['Fieldname']?$priceRow['Fieldname']:'';
		        $patientPrescription = $reporting->getPatientPrescriptions($rangeDate['min'], $rangeDate['max'], $priceColumn, $pb['pid'], 'pending');
		        $patientRows = $patientPrescription['rows'];
		        $singleRows = $patientPrescription['singleRows'];

				if (count($singleRows) > 0) {
					foreach ($singleRows as $singleRow) {

						if ($singleRow['purchasing_class'] == "service" || $singleRow['purchasing_class'] == "minor_proc_op" || $singleRow['purchasing_class'] == "surgical_op" || $singleRow['purchasing_class'] == "dental") {
							if ($singleRow['bill_number'] == 0) {
		        				$unpaidAmount =  $singleRow['row_amount'];
								$pendingUnpaidPatientsRecords[] = [
						        	// ++ $pendingunpaidnumber,
						        	$pb['pid'],
						        	$pb['selian_pid'],
						        	$pb['name_last'] . " " . $pb['name_first'],
						        	$pb['encounter_nr'],
						        	date('d/m/Y', strtotime($pb['prescribe_date'])),
						        	$singleRow['article'],
						        	$singleRow['prescriber'],
						        	$singleRow['issuer'],
						        	$pb['status'],
						        	@$priceRow['ShowDescription']?$priceRow['ShowDescription']:"",
						        	number_format($singleRow['drug_price'], 2),
						        	$singleRow['total_dosage'],
						        	number_format($singleRow['row_amount'], 2)
						        ];
								$totalPendingUnpaid += $unpaidAmount;

							}
						}else {
							if ($singleRow['bill_number'] == 0) {
		        				$unpaidAmount =  $singleRow['row_amount'];
								$pendingUnpaidPatientsRecords[] = [
						        	// ++ $pendingunpaidnumber,
						        	$pb['pid'],
						        	$pb['selian_pid'],
						        	$pb['name_last'] . " " . $pb['name_first'],
						        	$pb['encounter_nr'],
						        	date('d/m/Y', strtotime($pb['prescribe_date'])),
						        	$singleRow['article'],
						        	$singleRow['prescriber'],
						        	$singleRow['issuer'],
						        	$pb['status'],
						        	@$priceRow['ShowDescription']?$priceRow['ShowDescription']:"",
						        	number_format($singleRow['drug_price'], 2),
						        	$singleRow['total_dosage'],
						        	number_format($singleRow['row_amount'], 2)
						        ];
								$totalPendingUnpaid += $unpaidAmount;
							}
						}
						
					}
				}
						        
		        $rangePendingUnpaidPatients ++;
			}

			// Served Unpaid Patients
			if ($pb['bill_number'] == 0 && $pb['status'] == "done") {

				$priceRow = $reporting->getPatientInsurancePrice($pb['insurance_ID'], $pb['sub_insurance_id']);
		        $priceColumn = @$priceRow['Fieldname']?$priceRow['Fieldname']:'';

		        $patientPrescription = $reporting->getPatientPrescriptions($rangeDate['min'], $rangeDate['max'], $priceColumn, $pb['pid'], 'done');
		        $patientRows = $patientPrescription['rows'];
				$singleRows = $patientPrescription['singleRows'];

				if (count($singleRows) > 0) {
					foreach ($singleRows as $singleRow) {

						if ($singleRow['purchasing_class'] == "service" || $singleRow['purchasing_class'] == "minor_proc_op" || $singleRow['purchasing_class'] == "surgical_op" || $singleRow['purchasing_class'] == "dental") {
							
							if ($singleRow['bill_number'] == 0) {
		        				$unpaidAmount =  $singleRow['row_amount'];
								$servedUnpaidPatientsRecords[] = [
						        	// ++ $pendingunpaidnumber,
						        	$pb['pid'],
						        	$pb['selian_pid'],
						        	$pb['name_last'] . " " . $pb['name_first'],
						        	$pb['encounter_nr'],
						        	date('d/m/Y', strtotime($pb['prescribe_date'])),
						        	$singleRow['article'],
						        	$singleRow['prescriber'],
						        	$singleRow['issuer'],
						        	$pb['status'],
						        	@$priceRow['ShowDescription']?$priceRow['ShowDescription']:"",
						        	number_format($singleRow['drug_price'], 2),
						        	$singleRow['total_dosage'],
						        	number_format($singleRow['row_amount'], 2)
						        ];
								$totalServedUnpaid += $unpaidAmount;

							}
						}else {
							if ($singleRow['bill_number'] == 0) {
		        				$unpaidAmount =  $singleRow['row_amount'];

								$servedUnpaidPatientsRecords[] = [
						        	// ++ $pendingunpaidnumber,
						        	$pb['pid'],
						        	$pb['selian_pid'],
						        	$pb['name_last'] . " " . $pb['name_first'],
						        	$pb['encounter_nr'],
						        	date('d/m/Y', strtotime($pb['prescribe_date'])),
						        	$singleRow['article'],
						        	$singleRow['prescriber'],
						        	$singleRow['issuer'],
						        	$pb['status'],
						        	@$priceRow['ShowDescription']?$priceRow['ShowDescription']:"",
						        	number_format($singleRow['drug_price'], 2),
						        	$singleRow['total_dosage'],
						        	number_format($singleRow['row_amount'], 2)
						        ];
								$totalServedUnpaid += $unpaidAmount;
							}


						}
						
					}
				}

		        $rangeServedUnpaidPatients ++;
		
			}

			// Pending Paid Patients
			if ($pb['bill_number'] > 0 && ($pb['status'] == "pending" || $pb['status'] == '')) {

				$priceRow = $reporting->getPatientInsurancePrice($pb['insurance_ID'], $pb['sub_insurance_id']);
		        $paidAmount = $reporting->getTotalPatientAmountPaidInPrescription($pb['prescriptions_nr']);

				$totalPendingPaid += $paidAmount;
		        $pendingPaidPatientsRecords[] = [
		        	// ++ $pendingpaidnumber,
		        	$pb['pid'],
		        	$pb['selian_pid'],
		        	// $pb['prescription_name'],
		        	$pb['name_last'] . " " . $pb['name_first'],
		        	date('d/m/Y', strtotime($pb['prescribe_date'])),
		        	$pb['status'],
		        	$pb['prescriber'],
		        	$pb['issuer'],
		        	@$priceRow['ShowDescription']?$priceRow['ShowDescription']:"",
		        	number_format($paidAmount, 2)
		        ];
		        $rangePendingPaidPatients ++;
			}

			// Served Paid Patients
			if ($pb['bill_number'] > 0 && ($pb['status'] == "done")) {

				$priceRow = $reporting->getPatientInsurancePrice($pb['insurance_ID'], $pb['sub_insurance_id']);
		        // $paidAmount = $reporting->getTotalPatientAmountPaidInPrescription($pb['prescriptions_nr']);
		        $paidAmount = 0;

				$totalServedPaid += $paidAmount;
		        $servedPaidPatientsRecords[] = [
		        	// ++ $servedPaidPatients,
		        	$pb['pid'],
		        	$pb['selian_pid'],
		        	$pb['name_last'] . " " . $pb['name_first'],
		        	date('d/m/Y', strtotime($pb['prescribe_date'])),
		        	$pb['status'],
		        	$pb['prescriber'],
		        	$pb['issuer'],
		        	@$priceRow['ShowDescription']?$priceRow['ShowDescription']:"",
		        	number_format($paidAmount, 2)
		        ];
		        $rangeServedPaidPatients ++;
			}
		}
	}
	array_push($pendingUnpaidPatients, $rangePendingUnpaidPatients);
	array_push($pendingPaidPatients, $rangePendingPaidPatients);
	array_push($servedUnpaidPatients, $rangeServedUnpaidPatients);
	array_push($servedPaidPatients, $rangeServedPaidPatients);

}

$collectedPatientRecords = array_merge($pendingPaidPatientsRecords, $servedPaidPatientsRecords);
$uncollectedPatientRecords = array_merge($pendingUnpaidPatientsRecords, $servedUnpaidPatientsRecords);
$allPatientRecords = array_merge($collectedPatientRecords, $uncollectedPatientRecords);

$totalPaidSQL = "SELECT sum(amount*price) as total_sales FROM care_tz_billing_archive_elem bare INNER JOIN care_tz_drugsandservices pricelist ON bare.item_number=pricelist.item_id WHERE from_unixtime(date_change,'%Y-%m-%d') BETWEEN '$minimum_date' AND '$maximum_date' AND pricelist.purchasing_class != 'labtest'
                AND pricelist.purchasing_class != 'xray'  ";

if (@$type) {
	if ($type == 'Inpatient') {
    	$totalPaidSQL .= ' AND encounter_class_nr=1 ';
	}
	if ($type == 'Outpatient') {
        $totalPaidSQL .= ' AND encounter_class_nr=2 ';
    }
}
if (!empty($insurance) || $insurance === "0") {
	 $totalPaidSQL .= " AND bare.insurance_id IN ($insurance) ";
}
$totalPaidResult = $db->Execute($totalPaidSQL);
if (@$totalPaidResult && $totalPaidResult->RecordCount()) {
	$totalPaidRow = $totalPaidResult->FetchRow();
}
$totalPaid = @($totalPaidRow['total_sales'])?round($totalPaidRow['total_sales']):0;
 

$total_collected = $totalPaid;
$total_uncollected = $totalPendingUnpaid + $totalServedUnpaid;
$total = $total_collected + $total_uncollected;
$percentage_uncollected = ($total > 0)?number_format($total_uncollected/$total*100, 2):0;

$data['total'] = [
	'served_paid' => $totalPaid,
	'served_unpaid' => $totalServedUnpaid,
	'pending_paid' => $totalPendingPaid,
	'pending_unpaid' => $totalPendingUnpaid,
	'total_collected' => $total_collected,
	'total_uncollected' => $total_uncollected,
	'percentage_uncollected' => $percentage_uncollected
];


$data['datasets'] = array(
	array(
		'label' => 'Dispensed Paid',
		'backgroundColor' => $colorHelper->rand_color(),
		'stack' => 'Stack 0',
		'data' => $servedPaidPatients,
	),
	array(
		'label' => 'Dispensed Unpaid',
		'backgroundColor' => $colorHelper->rand_color(),
		'stack' => 'Stack 0',
		'data' => $servedUnpaidPatients,
	),
	array(
		'label' => 'Pending Paid',
		'backgroundColor' => $colorHelper->rand_color(),
		'stack' => 'Stack 1',
		'data' => $pendingPaidPatients
	),
	array(
		'label' => 'Pending Unpaid',
		'backgroundColor' => $colorHelper->rand_color(),
		'stack' => 'Stack 1',
		'data' => $pendingUnpaidPatients
	)
);


if ($category == 'pendingPaid') {
	$data['recordsTotal'] = count($pendingPaidPatientsRecords);
	$data['recordsFiltered'] = count($pendingPaidPatientsRecords);
	$data['data'] = $pendingPaidPatientsRecords;
}

if ($category == 'servedPaid') {
	$data['recordsTotal'] = count($servedPaidPatientsRecords);
	$data['recordsFiltered'] = count($servedPaidPatientsRecords);
	$data['data'] = $servedPaidPatientsRecords;
}

if ($category == 'pendingUnpaid') {
	$data['recordsTotal'] = count($pendingUnpaidPatientsRecords);
	$data['recordsFiltered'] = count($pendingUnpaidPatientsRecords);
	$data['data'] = $pendingUnpaidPatientsRecords;
}

if ($category == 'servedUnpaid') {

	$data['recordsTotal'] = count($servedUnpaidPatientsRecords);
	$data['recordsFiltered'] = count($servedUnpaidPatientsRecords);
	$data['data'] = $servedUnpaidPatientsRecords;
}

if ($category == 'collected') {
	$data['recordsTotal'] = count($collectedPatientRecords);
	$data['recordsFiltered'] = count($collectedPatientRecords);
	$data['data'] = $collectedPatientRecords;
}

if ($category == 'uncollected') {
	$data['recordsTotal'] = count($uncollectedPatientRecords);
	$data['recordsFiltered'] = count($uncollectedPatientRecords);
	$data['data'] = $uncollectedPatientRecords;
}

if ($category == 'all') {
	$data['recordsTotal'] = count($allPatientRecords);
	$data['recordsFiltered'] = count($allPatientRecords);
	$data['data'] = $allPatientRecords;
}


header('Content-type: application/json');
echo json_encode( $data );


