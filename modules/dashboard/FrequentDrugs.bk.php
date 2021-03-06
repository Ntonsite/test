<?php

require_once('./roots.php');
require_once('./colorHelper.php');
require_once $root_path.'vendor/autoload.php';
require_once $root_path.'generated-conf/config.php';

// care_tz_billing_archive_elem
// care_tz_drugsandservices

use  CareMd\CareMd\CareEncounterPrescriptionQuery;

$drugs = array();
$labels = array();
$drugData = array();
$background = array();
$colorHelper = new ColorHelper();

$drugCodes = CareEncounterPrescriptionQuery::create()
				->select(array('article', 'article_item_number'))
				->where('CareEncounterPrescription.drug_class LIKE ?', '%drug_list%')
				->distinct()
				->find()
				->toArray();

foreach ($drugCodes as $code) {
	$drug = CareEncounterPrescriptionQuery::create()->filterByArticleItemNumber($code['article_item_number'])->count();
	array_push($drugs, 
		array(
			'name' => $code['article'],
			'code' => $code['article_item_number'],
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
	array_push($labels, $topDisease['name']);
	array_push($drugData, $topDisease['total']);
	array_push($background, $colorHelper->rand_color());
}

$data['labels'] = $labels;
$data['data'] = $drugData;
$data['background'] = $background;

header('Content-type: application/json');
echo json_encode( $data );

