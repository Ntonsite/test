<?php

require_once('./roots.php');
require_once('./colorHelper.php');
require_once $root_path.'vendor/autoload.php';
require_once $root_path.'generated-conf/config.php';

use  CareMd\CareMd\CareTzBillingArchiveElemQuery;
use  CareMd\CareMd\CareTzDrugsandservicesQuery;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

$drugs = array();
$labels = array();
$drugData = array();
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


$drugCodes = CareTzDrugsandservicesQuery::create()
	->select(array('item_id', 'item_full_description'))
	->where('CareTzDrugsandservices.purchasing_class LIKE ?', '%drug_list%')
	->where("CareTzDrugsandservices.not_in_use	=?", 0)
	->distinct()
	->find()
	->toArray();


foreach ($drugCodes as $code) {
	$drug = CareTzBillingArchiveElemQuery::create()->filterByItemNumber($code['item_id'])
	->where("CareTzBillingArchiveElem.date_change >=?", strtotime($startDate))
	->where("CareTzBillingArchiveElem.date_change <=?", strtotime($endDate))
	->count();
	array_push($drugs, 
		array(
			'name' => $code['item_full_description'],
			'code' => $code['item_id'],
			'total'=> $drug,
		)
	);
}

usort($drugs, function($a, $b) {
    return $b['total'] - $a['total'];
});

$count = @$_GET['count']?$_GET['count']:10;

$topDiseases = array();

for ($i=0; $i < $count; $i++) { 
	array_push($topDiseases, $drugs[$i]);
}

foreach ($topDiseases as $topDisease) {
	if ( $topDisease['total'] > 0) {
		array_push($labels, $topDisease['name']);
		array_push($drugData, $topDisease['total']);
		array_push($background, $colorHelper->rand_color());
	}
	
}

if (empty($labels)) {
	$labels =  ['NO DATA'];
}

$data['labels'] = $labels;
$data['data'] = $drugData;
$data['background'] = $background;

header('Content-type: application/json');
echo json_encode( $data );

