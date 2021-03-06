<?php

require_once('./roots.php');
require_once('./colorHelper.php');
require_once $root_path.'vendor/autoload.php';
require_once $root_path.'generated-conf/config.php';

use  CareMd\CareMd\CareTzDiagnosisQuery;
use  CareMd\CareMd\CareTzDiagnosis;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

$diseases = array();
$labels = array();
$diseaseData = array();
$background = array();
$colorHelper = new ColorHelper();

$period = @$_GET['period']?$_GET['period']:"ThisWeek";

if ($period == "ThisWeek") {
	$startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
	$endDate =  Carbon::now()->endOfWeek()->format('Y-m-d');

}

if ($period == "ThisMonth") {
	$startDate = date('Y-m-01');
	$endDate = date('Y-m-t');
}

if ($period == "ThisYear") {
	$year = date('Y');
	$startDate = $year . "-01-01";
	$endDate = $year . "-12-31";
}

if ($period == "LastYear") {
	$year = date('Y')-1;
	$startDate = $year . "-01-01";
	$endDate = $year . "-12-31";	
}

if ($period == "LastMonth") {
	$lastmonth = date('m', strtotime("last month"));
	$startDate = date('Y-'.$lastmonth.'-01');
	$endDate = date('Y-'.$lastmonth.'-t');
}

if ($period == "LastWeek") {
	$previous_week = strtotime("-1 week +1 day");
	$start_week = strtotime("last monday midnight",$previous_week);
	$end_week = strtotime("next sunday",$start_week);

	$startDate = date("Y-m-d",$start_week);
	$endDate = date("Y-m-d",$end_week);
}


$diseaseCodes = CareTzDiagnosisQuery::create()
				->select(array('ICD_10_code', 'ICD_10_description'))
				->distinct()
				->find()
				->toArray();

foreach ($diseaseCodes as $code) {
	$disease = CareTzDiagnosisQuery::create()
	->filterByIcd10Code($code['ICD_10_code'])
	->where("CareTzDiagnosis.timestamp >=?", strtotime($startDate))
	->where("CareTzDiagnosis.timestamp <=?", strtotime($endDate))
	->count();

	array_push($diseases, 
		array(
			'name' => $code['ICD_10_description'],
			'code' => $code['ICD_10_code'],
			'total'=> $disease,
		)
	);
}

usort($diseases, function($a, $b) {
    return $b['total'] - $a['total'];
});

$count = @$_GET['count']?$_GET['count']:10;


$topDiseases = array();
if (@$diseases) {
	for ($i=0; $i < $count; $i++) { 
		if (@$diseases[$i]) {
			array_push($topDiseases, $diseases[$i]);
		}
	}
}

foreach ($topDiseases as $topDisease) {
	if ($topDisease['total'] > 0) {
		array_push($labels, $topDisease['name']);
		array_push($diseaseData, $topDisease['total']);
		array_push($background, $colorHelper->rand_color());	
	}
}

if (empty($labels)) {
	$labels =  ['NO DATA'];
}

$data['labels'] = $labels;
$data['data'] = $diseaseData;
$data['background'] = $background;

header('Content-type: application/json');
echo json_encode( $data );

