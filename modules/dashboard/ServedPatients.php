<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300);
require_once('./roots.php');
require_once('./colorHelper.php');
require_once $root_path.'vendor/autoload.php';
require_once $root_path.'generated-conf/config.php';

$period = @$_GET['period']?$_GET['period']:"ThisWeek";


use  CareMd\CareMd\CareEncounterQuery;
use  CareMd\CareMd\CareEncounterEventSignallerQuery;

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

if ($period == "LastYear") {
	$data['labels'] = array_values($lastMonths);
}

if ($period == "ThisYear") {
	$data['labels'] = array_values($months);	
}

if ($period == "ThisWeek") {
		
	$startWeekDate = Carbon::now()->startOfWeek();
	$endWeekDate =  Carbon::now()->endOfWeek();
	$weekPeriod = CarbonPeriod::create($startWeekDate, $endWeekDate);

	foreach ($weekPeriod as $key => $date) {

		$thisday = array(
			'date' => $date->format('Y-m-d'),
			'name' => $date->format('D'),
		);
		array_push($weeklables, $date->format('D'));
		array_push($weekdays, $thisday);
	}
	$data['labels'] = $weeklables;

}

if ($period == "ThisMonth") {
	
	$start_date = date('Y-m-01');
	$end_date = date('Y-m-t');

	$thisMonthPeriod = CarbonPeriod::create($start_date, $end_date);


	foreach ($thisMonthPeriod as $key => $date) {

		$thisday = array(
			'date' => $date->format('Y-m-d'),
			'name' => $date->format('D'),
		);
		array_push($thisMonthLabels, $date->format('D'). " ". $date->format('d/m/Y'));
		array_push($thisMonthDays, $thisday);
	}
	$data['labels'] = $thisMonthLabels;

}

if ($period == "LastMonth") {
	
	$lastmonth = date('m', strtotime("last month"));
	$start_date = date('Y-'.$lastmonth.'-01');
	$end_date = date('Y-'.$lastmonth.'-t');

	$lastMonthPeriod = CarbonPeriod::create($start_date, $end_date);


	foreach ($lastMonthPeriod as $key => $date) {

		$lastmonth = array(
			'date' => $date->format('Y-m-d'),
			'name' => $date->format('D'),
		);
		array_push($lastMonthLables, $date->format('D'). " ". $date->format('d/m/Y'));
		array_push($lastMonthDays, $lastmonth);
	}
	$data['labels'] = $lastMonthLables;

}

if ($period == "LastWeek") {
	
	$previous_week = strtotime("-1 week +1 day");
	$start_week = strtotime("last monday midnight",$previous_week);
	$end_week = strtotime("next sunday",$start_week);

	$start_week = date("Y-m-d",$start_week);
	$end_week = date("Y-m-d",$end_week);

	$lastWeekPeriod = CarbonPeriod::create($start_week, $end_week);


	foreach ($lastWeekPeriod as $key => $date) {

		$thisday = array(
			'date' => $date->format('Y-m-d'),
			'name' => $date->format('D'),
		);
		array_push($lastWeekLabels, $date->format('D'). " ".$date->format('d/m/Y'));
		array_push($lastWeekDays, $thisday);
	}
	$data['labels'] = $lastWeekLabels;

}


$legendNames = array();
$patientsStatus = array();
$servedPatients = array();
$unservedPatients = array();

$colorHelper = new ColorHelper();

if ($period == "ThisWeek") {

	foreach ($weekdays as $keyday => $weekday) {
		$patients = CareEncounterQuery::create()
		->filterByEncounterDate(array("min" => $weekday['date']." 00:00:00", "max" => $weekday['date']." 23:59:59"))
		->orderBy('CareEncounter.EncounterDate', 'desc')
		->limit(600)
		->find()
		->toArray();

		$patientsNumbers = array();
		foreach ($patients as $patient) {
			array_push($patientsNumbers, $patient['EncounterNr']);
		}

		$totalServedPatients = CareEncounterEventSignallerQuery::create()
		->filterByEncounterNr($patientsNumbers)
		->find()
		->count();
		
		$totalPatients = count($patients);
		$totalUnservedPatients = $totalPatients - $totalServedPatients;

		array_push($servedPatients, $totalServedPatients);
		array_push($unservedPatients, $totalUnservedPatients);
	}	
}

if ($period == "LastWeek") {

	foreach ($lastWeekDays as $keyday => $weekday) {
		$patients = CareEncounterQuery::create()
		->filterByEncounterDate(array("min" => $weekday['date']." 00:00:00", "max" => $weekday['date']." 23:59:59"))
		->orderBy('CareEncounter.EncounterDate', 'desc')
		->limit(600)
		->find()
		->toArray();

		$patientsNumbers = array();
		foreach ($patients as $patient) {
			array_push($patientsNumbers, $patient['EncounterNr']);
		}
		$totalServedPatients = CareEncounterEventSignallerQuery::create()
		->filterByEncounterNr($patientsNumbers)
		->find()
		->count();
		$totalPatients = count($patients);
		$totalUnservedPatients = $totalPatients - $totalServedPatients;

		array_push($servedPatients, $totalServedPatients);
		array_push($unservedPatients, $totalUnservedPatients);
	}	
}

if ($period == "ThisMonth") {

	foreach ($thisMonthDays as $keyday => $monthday) {
		$patients = CareEncounterQuery::create()
		->filterByEncounterDate(array("min" => $monthday['date']." 00:00:00", "max" => $monthday['date']." 23:59:59"))
		->orderBy('CareEncounter.EncounterDate', 'desc')
		->limit(600)
		->find()
		->toArray();

		$patientsNumbers = array();
		foreach ($patients as $patient) {
			array_push($patientsNumbers, $patient['EncounterNr']);
		}
		$totalServedPatients = CareEncounterEventSignallerQuery::create()
		->filterByEncounterNr($patientsNumbers)
		->find()
		->count();
		$totalPatients = count($patients);
		$totalUnservedPatients = $totalPatients - $totalServedPatients;

		array_push($servedPatients, $totalServedPatients);
		array_push($unservedPatients, $totalUnservedPatients);
	}	
}

if ($period == "LastMonth") {

	foreach ($lastMonthDays as $keyday => $monthday) {
		$patients = CareEncounterQuery::create()
		->filterByEncounterDate(array("min" => $monthday['date']." 00:00:00", "max" => $monthday['date']." 23:59:59"))
		->orderBy('CareEncounter.EncounterDate', 'desc')
		->limit(600)
		->find()
		->toArray();

		$patientsNumbers = array();
		foreach ($patients as $patient) {
			array_push($patientsNumbers, $patient['EncounterNr']);
		}
		$totalServedPatients = CareEncounterEventSignallerQuery::create()
		->filterByEncounterNr($patientsNumbers)
		->find()
		->count();
		$totalPatients = count($patients);
		$totalUnservedPatients = $totalPatients - $totalServedPatients;

		array_push($servedPatients, $totalServedPatients);
		array_push($unservedPatients, $totalUnservedPatients);
	}	
}


if ($period == "ThisYear") {

	foreach ($months as $keyMonth => $month) {
		$minDate = $year."-".$keyMonth.'-01';
		$maxDate = $year."-".$keyMonth.'-31';
		$patients = CareEncounterQuery::create()
		->filterByEncounterDate(array("min" => $minDate." 00:00:00", "max" => $maxDate." 23:59:59"))
		->orderBy('CareEncounter.EncounterDate', 'desc')
		->limit(10000)
		->find()
		->toArray();

		$patientsNumbers = array();
		foreach ($patients as $patient) {
			array_push($patientsNumbers, $patient['EncounterNr']);
		}
		$totalServedPatients = CareEncounterEventSignallerQuery::create()
		->filterByEncounterNr($patientsNumbers)
		->find()
		->count();
		$totalPatients = count($patients);
		$totalUnservedPatients = $totalPatients - $totalServedPatients;

		array_push($servedPatients, $totalServedPatients);
		array_push($unservedPatients, $totalUnservedPatients);
	}	
}


if ($period == "LastYear") {
	
	foreach ($lastMonths as $keyMonth => $month) {
		$minDate = $lastYear."-".$keyMonth.'-01';
		$maxDate = $lastYear."-".$keyMonth.'-31';
		$patients = CareEncounterQuery::create()
		->filterByEncounterDate(array("min" => $minDate." 00:00:00", "max" => $maxDate." 23:59:59"))
		->orderBy('CareEncounter.EncounterDate', 'desc')
		->limit(10000)
		->find()
		->toArray();

		$patientsNumbers = array();
		foreach ($patients as $patient) {
			array_push($patientsNumbers, $patient['EncounterNr']);
		}
		$totalServedPatients = CareEncounterEventSignallerQuery::create()
		->filterByEncounterNr($patientsNumbers)
		->find()
		->count();
		$totalPatients = count($patients);
		$totalUnservedPatients = $totalPatients - $totalServedPatients;

		array_push($servedPatients, $totalServedPatients);
		array_push($unservedPatients, $totalUnservedPatients);
	}	
}



$data['datasets'] = array(
	array(
		'label' => 'Served Patients',
		'backgroundColor' => $colorHelper->rand_color(),
		'data' => $servedPatients,
	),
	array(
		'label' => 'Unserved Patients',
		'backgroundColor' => $colorHelper->rand_color(),
		'data' => $unservedPatients
	)
);


header('Content-type: application/json');
echo json_encode( $data );


