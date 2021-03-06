<?php

use Carbon\CarbonPeriod;
use Carbon\Carbon;

if ($period == "LastYear") {
	$data['labels'] = array_values($lastMonths);
	$minimum_date =  $lastYear.'-01-01';
	$maximum_date =  $lastYear.'-12-31';
	foreach ($lastMonths as $keyMonth => $month) {
		$minDate = $lastYear."-".$keyMonth.'-01';
		$maxDate = date('Y-m-t', strtotime($minDate));
		$lastyear = array(
			'min' => $minDate,
			'max' => $maxDate,
		);	
		array_push($rangeDates, $lastyear);
	}
	
}

if ($period == "ThisYear") {
	$minimum_date =  $year.'-01-01';
	$maximum_date =  $year.'-12-31';
	$data['labels'] = array_values($months);
	foreach ($months as $keyMonth => $month) {
		$minDate = $year."-".$keyMonth.'-01';
		$maxDate = date('Y-m-t', strtotime($minDate));

		$thisyear = array(
			'min' => $minDate,
			'max' => $maxDate,
		);	
		array_push($rangeDates, $thisyear);
	}

}

if ($period == "ThisWeek") {
		
	$startWeekDate = Carbon::now()->startOfWeek();
	$endWeekDate =  Carbon::now()->endOfWeek();
	$minimum_date =  $startWeekDate->format('Y-m-d');
	$maximum_date =  $endWeekDate->format('Y-m-d');
	$weekPeriod = CarbonPeriod::create($startWeekDate, $endWeekDate);

	foreach ($weekPeriod as $key => $date) {

		$thisday = array(
			'date' => $date->format('Y-m-d'),
			'min' => $date->format('Y-m-d') ,
			'max' => $date->format('Y-m-d') ,
			'name' => $date->format('D'),
		);
		array_push($weeklables, $date->format('D'));
		array_push($rangeDates, $thisday);
	}
	$data['labels'] = $weeklables;

}

if ($period == "ThisMonth") {
	
	$start_date = $minimum_date = date('Y-m-01');
	$end_date = $maximum_date = date('Y-m-t');

	$thisMonthPeriod = CarbonPeriod::create($start_date, $end_date);


	foreach ($thisMonthPeriod as $key => $date) {

		$thisday = array(
			'date' => $date->format('Y-m-d'),
			'min' => $date->format('Y-m-d'),
			'max' => $date->format('Y-m-d'),
			'name' => $date->format('D'),
		);
		array_push($thisMonthLabels, $date->format('D'). " ". $date->format('d/m/Y'));
		array_push($rangeDates, $thisday);
	}
	$data['labels'] = $thisMonthLabels;

}

if ($period == "LastMonth") {
	
	$lastmonth = date('m', strtotime("last month"));
	$start_date = $minimum_date = date('Y-'.$lastmonth.'-01');
	$end_date = $maximum_date = date('Y-'.$lastmonth.'-t');

	$lastMonthPeriod = CarbonPeriod::create($start_date, $end_date);


	foreach ($lastMonthPeriod as $key => $date) {

		$lastmonth = array(
			'date' => $date->format('Y-m-d'),
			'min' => $date->format('Y-m-d'),
			'max' => $date->format('Y-m-d'),
			'name' => $date->format('D'),
		);
		array_push($lastMonthLables, $date->format('D'). " ". $date->format('d/m/Y'));
		array_push($rangeDates, $lastmonth);
	}
	$data['labels'] = $lastMonthLables;

}

if ($period == "LastWeek") {
	
	$previous_week = strtotime("-1 week +1 day");
	$start_week = strtotime("last monday midnight",$previous_week);
	$end_week = strtotime("next sunday",$start_week);

	$start_week = $minimum_date = date("Y-m-d",$start_week);
	$end_week = $maximum_date = date("Y-m-d",$end_week);

	$lastWeekPeriod = CarbonPeriod::create($start_week, $end_week);


	foreach ($lastWeekPeriod as $key => $date) {

		$thisday = array(
			'date' => $date->format('Y-m-d'),
			'min' => $date->format('Y-m-d') ,
			'max' => $date->format('Y-m-d') ,
			'name' => $date->format('D'),
		);
		array_push($lastWeekLabels, $date->format('D'). " ".$date->format('d/m/Y'));
		array_push($rangeDates, $thisday);
	}
	$data['labels'] = $lastWeekLabels;

}

if ($period == "custom" || $period == "") {
	$startdate = @($_GET['start_date'] && $_GET['start_date'] != "undefined")?date('Y-m-d', strtotime($_GET['start_date'])):date('Y-m-01');
	$enddate = @($_GET['end_date'] && $_GET['end_date'] != "undefined")?date('Y-m-d', strtotime($_GET['end_date'])):date('Y-m-d');
	$start_date = strtotime($startdate);
	$end_date = strtotime($enddate);
	
	$minimum_date = date("Y-m-d", $start_date);
	$maximum_date = date("Y-m-d", $end_date);

	$customPeriod = CarbonPeriod::create($minimum_date, $maximum_date);


	foreach ($customPeriod as $key => $date) {

		$thisday = array(
			'date' => $date->format('Y-m-d'),
			'min' => $date->format('Y-m-d') ,
			'max' => $date->format('Y-m-d') ,
			'name' => $date->format('D'),
		);
		array_push($lastWeekLabels, $date->format('D'). " ".$date->format('d/m/Y'));
		array_push($rangeDates, $thisday);
	}
	$data['labels'] = $lastWeekLabels;

}

$data['start_date'] =  date("d/m/Y", strtotime($minimum_date));
$data['end_date'] =  date("d/m/Y", strtotime($maximum_date));
$data['StartDate'] =  date("Y-m-d", strtotime($minimum_date));
$data['EndDate'] =  date("Y-m-d", strtotime($maximum_date));
?>