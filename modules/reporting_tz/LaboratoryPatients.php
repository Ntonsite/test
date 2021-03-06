<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', -1);
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
$category = @$_GET['category']?$_GET['category']:'';

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

$min_date = $minimum_date . " 00:00:00";
$max_date = $maximum_date . " 23:59:59";

// Lab PATIENTS
$patientsSQL = "SELECT cp.pid, cp.selian_pid, UPPER(name_last) as name_last, CONCAT(name_first, '  ', name_2) AS name_first, batch_nr, cp.insurance_ID, cp.sub_insurance_id, tr.encounter_nr, tr.send_date, tr.notes, dept_nr, room_nr, tr.bill_number, tr.status, tr.create_id
	FROM
		care_test_request_chemlabor tr,
		care_encounter ce,
	 	care_person cp
	WHERE tr.encounter_nr = ce.encounter_nr
		AND ce.pid = cp.pid
		AND tr.send_date >= '$min_date'  AND tr.send_date <= '$max_date' ";

if (!empty($insurance) || $insurance === "0") {
	$patientsSQL .= " AND cp.insurance_ID IN ($insurance) ";	
}

if (@$type) {
	if ($type == 'Inpatient') {
    	$patientsSQL .= ' AND ce.encounter_class_nr=1 ';
	}
	if ($type == 'Outpatient') {
        $patientsSQL .= ' AND ce.encounter_class_nr=2 ';
    }
}

$patientsSQL .= " ORDER BY tr.send_date DESC";
$patients = [];
$patientsResult = $db->Execute($patientsSQL);
if (@$patientsResult && $patientsResult->RecordCount()) {
	$pendingRequestsTotal = $patientsResult->RecordCount();
	$patients = $patientsResult->GetArray();
}

$legendNames = array();
$patientsStatus = array();

$totalPendingPaid = 0;
$totalPendingUnpaid = 0;
$totalAwaitingPaid = 0;
$totalAwaitingUnpaid = 0;
$totalServedPaid = 0;
$totalServedUnpaid = 0;

$colorHelper = new ColorHelper();

$servedPaidPatients = array();
$servedUnpaidPatients = array();
$pendingPaidPatients = array();
$pendingUnpaidPatients = array();

$servedPaidPatientsRecords = array();
$servedUnpaidPatientsRecords = array();
$pendingPaidPatientsRecords = array();
$pendingUnpaidPatientsRecords = array();

foreach ($rangeDates as $keyrange => $rangeDate) {

	$minDate = $rangeDate['min'] . " 00:00:00";
	$maxDate = $rangeDate['max'] . " 23:59:59";

	$rangePendingUnpaidPatients = 0;
	$rangePendingPaidPatients = 0;
	$rangeServedUnpaidPatients = 0;
	$rangeServedPaidPatients = 0;

	$servedPaidRequests = array();
	$servedUnpaidRequests = array();
	$pendingPaidRequests = array();
	$pendingUnpaidRequests = array();

	foreach ($patients as $pk => $patient) {
		if(strtotime($patient['send_date']) >= strtotime($minDate) 
			&& strtotime($patient['send_date']) <= strtotime($maxDate)
		){
			// Pending Patients
			if ($patient['status'] == "pending" || $patient['status'] == '') {
				$priceRow = $reporting->getPatientInsurancePrice($patient['insurance_ID'], $patient['sub_insurance_id']);
				$priceColumn = @$priceRow['Fieldname']?$priceRow['Fieldname']:"";

				// Pending Paid
				$pPPRequests = $reporting->getPatientPendingPaidLabSubTests($minDate, $maxDate, $priceColumn, $patient['pid']);

				if (count($pPPRequests) > 0) {
					foreach ($pPPRequests as $ppr) {
						$drug = $reporting->getDrug($priceColumn, $ppr['item_id']);
						$drugAmount = $drug['amount'];
						$drugName = $drug['name'];
						$pendingPaidPatientsRecords[] = [
					    	$patient['pid'],
					    	$patient['encounter_nr'],
					    	$patient['batch_nr'],
					    	$patient['name_last'] . " " . $patient['name_first'],
					    	date('d/m/Y', strtotime($patient['send_date'])),
					    	$patient['create_id'],
					    	$ppr['status'],
					    	$priceRow['ShowDescription'],
					    	$drugName,
					    	number_format($drugAmount, 2)
					    ];
					}
					$pCount = count($reporting->array_unique_deep($pPPRequests, 'batch_nr'));
					$rangePendingPaidPatients += $pPPRequests;
				}

				// Pending Unpaid
				$pPURequests = $reporting->getPatientPendingUnpaidLabSubTests($minDate, $maxDate, $priceColumn, $patient['pid']);

				if (count($pPURequests) > 0) {
					foreach ($pPURequests as $pur) {
						$drug = $reporting->getDrug($priceColumn, $pur['item_id']);
						$drugAmount = $drug['amount'];
						$drugName = $drug['name'];
						$pendingUnpaidPatientsRecords[] = [
					    	$patient['pid'],
					    	$patient['encounter_nr'],
					    	$patient['batch_nr'],
					    	$patient['name_last'] . " " . $patient['name_first'],
					    	date('d/m/Y', strtotime($patient['send_date'])),
					    	$patient['create_id'],
					    	$pur['status'],
					    	$priceRow['ShowDescription'],
					    	$drugName,
					    	number_format($drugAmount, 2)
					    ];
					    $totalPendingUnpaid += $drugAmount;
					}
					$pCount = count($reporting->array_unique_deep($pPURequests, 'batch_nr'));
					$rangePendingUnpaidPatients += $pCount;
				}

				// Served Paid
				$sPPRequests = $reporting->getPatientServedPaidLabSubTests($minDate, $maxDate, $priceColumn, $patient['pid']);

				if (count($sPPRequests) > 0) {
					foreach ($sPPRequests as $spr) {
						$drug = $reporting->getDrug($priceColumn, $spr['item_id']);
						$drugAmount = $drug['amount'];
						$drugName = $drug['name'];
						$servedPaidPatientsRecords[] = [
					    	$patient['pid'],
					    	$patient['encounter_nr'],
					    	$patient['batch_nr'],
					    	$patient['name_last'] . " " . $patient['name_first'],
					    	date('d/m/Y', strtotime($patient['send_date'])),
					    	$patient['create_id'],
					    	$spr['status'],
					    	$priceRow['ShowDescription'],
					    	$drugName,
					    	number_format($drugAmount, 2)
					    ];
					}
					$pCount = count($reporting->array_unique_deep($sPPRequests, 'batch_nr'));
					$rangeServedPaidPatients += $pCount;
				}

				// Served Unpaid
				$sUPRequests = $reporting->getPatientServedUnpaidLabSubTests($minDate, $maxDate, $priceColumn, $patient['pid']);

				if (count($sUPRequests) > 0) {
					foreach ($sUPRequests as $sur) {
						$drug = $reporting->getDrug($priceColumn, $sur['item_id']);
						$drugAmount = $drug['amount'];
						$drugName = $drug['name'];
						$servedUnpaidPatientsRecords[] = [
					    	$patient['pid'],
					    	$patient['encounter_nr'],
					    	$patient['batch_nr'],
					    	$patient['name_last'] . " " . $patient['name_first'],
					    	date('d/m/Y', strtotime($patient['send_date'])),
					    	$patient['create_id'],
					    	$sur['status'],
					    	$priceRow['ShowDescription'],
					    	$drugName,
					    	number_format($drugAmount, 2)
					    ];
						$totalServedUnpaid += $drugAmount;
					}
					$pCount = count($reporting->array_unique_deep($sUPRequests, 'batch_nr'));
					$rangeServedUnpaidPatients += $pCount;
				}
			
				
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

$totalPaidSQL = "SELECT sum(amount*price) as total_sales FROM care_tz_billing_archive_elem bare INNER JOIN care_tz_drugsandservices pricelist ON bare.item_number=pricelist.item_id WHERE from_unixtime(date_change,'%Y-%m-%d') BETWEEN '$minimum_date' AND '$maximum_date' AND pricelist.purchasing_class like 'labtest%' ";
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
	'served_unpaid' => $totalServedUnpaid,
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


