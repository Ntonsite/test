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
$type = $_GET['type'];
$insurance = $_GET['insurance'];
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

$totalPendingPaid = 0;
$totalPendingUnpaid = 0;
$totalServedPaid = 0;
$totalServedUnpaid = 0;

$colorHelper = new ColorHelper();

$servedPaidPatients = array();
$servedUnpaidPatients = array();
$pendingPaidPatients = array();
$pendingUnpaidPatients = array();

$pendingPaidPatientsRecords = array();
$pendingUnpaidPatientsRecords = array();
$servedPaidPatientsRecords = array();
$servedUnpaidPatientsRecords = array();

$totalPendingPaidPatients = 0;
$totalPendingUnpaidPatients = 0;
$totalServedPaidPatients = 0;
$totalServedUnpaidPatients = 0;

// RADIOLOGY PATIENTS
$patientsSQL = "SELECT cp.pid, cp.selian_pid, UPPER(name_last) as name_last, CONCAT(name_first, '  ', name_2) AS name_first, batch_nr, cp.insurance_ID, cp.sub_insurance_id, cd.unit_price, cd.unit_price_1 , cd.unit_price_2, cd.unit_price_3, cd.unit_price_4, cd.unit_price_5, cd.unit_price_6, cd.unit_price_7, cd.unit_price_8, cd.unit_price_9, cd.unit_price_10, cd.unit_price_11, cd.unit_price_12, cd.unit_price_13, cd.unit_price_14, cd.unit_price_15, cd.unit_price_16, crd.send_date, crd.bill_number, crd.status, crd.send_doctor, cd.item_description, crd.encounter_nr
	FROM care_test_request_radio crd
	INNER JOIN care_tz_drugsandservices cd
	ON crd.test_request = cd.item_id
	INNER JOIN care_encounter ce
	ON ce.encounter_nr = crd.encounter_nr
	INNER JOIN care_person cp
	ON cp.pid = ce.pid
	AND crd.send_date >= '$minimum_date'  AND crd.send_date <= '$maximum_date' ";

if (@$type) {
	if ($type == 'Inpatient') {
    	$patientsSQL .= ' AND ce.encounter_class_nr=1 ';
	}
	if ($type == 'Outpatient') {
        $patientsSQL .= ' AND ce.encounter_class_nr=2 ';
    }
}

if (!empty($insurance) || $insurance === "0") {
	$patientsSQL .= " AND cp.insurance_ID IN ($insurance) ";	
}

$patientsSQL .= " ORDER BY crd.send_date DESC";

$patientsRows = [];
$patientsResult = $db->Execute($patientsSQL);
if (@$patientsResult && $patientsResult->RecordCount()) {
	$patientsRows = $patientsResult->GetArray();
}

foreach ($rangeDates as $keyrange => $rangeDate) {

	$minDate = $rangeDate['min'] . " 00:00:00";
	$maxDate = $rangeDate['max'] . " 23:59:59";

	$rangePendingUnpaidPatients =0;
	$rangePendingPaidPatients = 0;
	$rangeServedUnpaidPatients = 0;
	$rangeServedPaidPatients = 0;

	foreach ($patientsRows as $pupk => $pr) {

		if(strtotime($pr['send_date']) >= strtotime($rangeDate['min']) 
			&& strtotime($pr['send_date']) <= strtotime($rangeDate['max'])
		){

			// Pending Unpaid Patients
			if ($pr['bill_number'] == 0 && ($pr['status'] == "pending" || $pr['status'] == '')) {

				$priceRow = $reporting->getPatientInsurancePrice($pr['insurance_ID'], $pr['sub_insurance_id']);
		        $priceColumn = @($priceRow['Fieldname'])?$priceRow['Fieldname']:"";
		        $unpaidAmount = @$pr[$priceColumn]?$pr[$priceColumn]:0;
		        $totalPendingUnpaid += $unpaidAmount;
		        $rangePendingUnpaidPatients ++;

		        $pendingUnpaidPatientsRecords[] = [
		        	++$pupk,
		        	$pr['pid'],
		        	$pr['selian_pid'],
		        	$pr['encounter_nr'],
		        	$pr['name_last'] . " " . $pr['name_first'],
		        	date('d/m/Y', strtotime($pr['send_date'])),
		        	$pr['status'],
		        	$pr['send_doctor'],
		        	$pr['item_description'],
		        	@($priceRow['ShowDescription'])?$priceRow['ShowDescription']:"",
		        	number_format($unpaidAmount, 2)
		        ];

			}

			// Pending Paid Patients
			if ($pr['bill_number'] > 0 && ($pr['status'] == "pending" || $pr['status'] == '')) {

				$priceRow = $reporting->getPatientInsurancePrice($pr['insurance_ID'], $pr['sub_insurance_id']);
		        $priceColumn = @($priceRow['Fieldname'])?$priceRow['Fieldname']:"";
		        $unpaidAmount = 0;
		        $rangePendingPaidPatients +=1;

		        $pendingPaidPatientsRecords[] = [
		        	++$pupk,
		        	$pr['pid'],
		        	$pr['selian_pid'],
		        	$pr['encounter_nr'],
		        	$pr['name_last'] . " " . $pr['name_first'],
		        	date('d/m/Y', strtotime($pr['send_date'])),
		        	$pr['status'],
		        	$pr['send_doctor'],
		        	@($priceRow['ShowDescription'])?$priceRow['ShowDescription']:"",
		        	number_format($unpaidAmount, 2)
		        ];

			}

			// Served Unpaid Patients
			if ($pr['bill_number'] == 0 && ($pr['status'] == "done" )) {

				$priceRow = $reporting->getPatientInsurancePrice($pr['insurance_ID'], $pr['sub_insurance_id']);
		        $priceColumn = @($priceRow['Fieldname'])?$priceRow['Fieldname']:"";
		        $unpaidAmount = @$pr[$priceColumn]?$pr[$priceColumn]:0;
		        $totalServedUnpaid += $unpaidAmount;
		        $rangeServedUnpaidPatients ++;

		        $servedUnpaidPatientsRecords[] = [
		        	++$pupk,
		        	$pr['pid'],
		        	$pr['selian_pid'],
		        	$pr['encounter_nr'],
		        	$pr['name_last'] . " " . $pr['name_first'],
		        	date('d/m/Y', strtotime($pr['send_date'])),
		        	$pr['status'],
		        	$pr['send_doctor'],
		        	$pr['item_description'],
		        	@($priceRow['ShowDescription'])?$priceRow['ShowDescription']:"",
		        	number_format($unpaidAmount, 2)
		        ];

			}

			// Served Paid Patients
			if ($pr['bill_number'] > 0 && ($pr['status'] == "done" )) {

				$priceRow = $reporting->getPatientInsurancePrice($pr['insurance_ID'], $pr['sub_insurance_id']);
		        $priceColumn = @($priceRow['Fieldname'])?$priceRow['Fieldname']:"";
		        $unpaidAmount = 0;
		        $totalServedPaid += $unpaidAmount;
		        $rangeServedPaidPatients ++;

		        $servedPaidPatientsRecords[] = [
		        	++$pupk,
		        	$pr['pid'],
		        	$pr['selian_pid'],
		        	$pr['encounter_nr'],
		        	$pr['name_last'] . " " . $pr['name_first'],
		        	date('d/m/Y', strtotime($pr['send_date'])),
		        	$pr['status'],
		        	$pr['send_doctor'],
		        	$pr['item_description'],
		        	@($priceRow['ShowDescription'])?$priceRow['ShowDescription']:"",
		        	number_format($unpaidAmount, 2)
		        ];

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

$totalPaidSQL = "SELECT sum(amount*price) as total_sales FROM care_tz_billing_archive_elem bare INNER JOIN care_tz_drugsandservices pricelist ON bare.item_number=pricelist.item_id WHERE from_unixtime(date_change,'%Y-%m-%d') BETWEEN '$minimum_date' AND '$maximum_date' AND pricelist.purchasing_class like 'xray%' ";

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
	'served_paid' => $totalServedPaid,
	'served_unpaid' => $totalServedUnpaid,
	'pending_paid' => $totalPendingPaid,
	'pending_unpaid' => $totalPendingUnpaid,
	'total_collected' => $total_collected,
	'total_uncollected' => $total_uncollected,
	'percentage_uncollected' => $percentage_uncollected
];


$data['datasets'] = array(
	array(
		'label' => 'Pending Paid',
		'backgroundColor' => $colorHelper->rand_color(),
		'stack' => 'Stack 0',
		'data' => $pendingPaidPatients,
	),
	array(
		'label' => 'Pending Unpaid',
		'backgroundColor' => $colorHelper->rand_color(),
		'stack' => 'Stack 0',
		'data' => $pendingUnpaidPatients,
	),
	array(
		'label' => 'Dispensed Paid',
		'backgroundColor' => $colorHelper->rand_color(),
		'stack' => 'Stack 1',
		'data' => $servedPaidPatients
	),
	array(
		'label' => 'Dispensed Unpaid',
		'backgroundColor' => $colorHelper->rand_color(),
		'stack' => 'Stack 1',
		'data' => $servedUnpaidPatients
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


