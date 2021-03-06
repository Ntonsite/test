<?php
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/inc_front_chain_lang.php');
require_once($root_path . 'include/care_api_classes/class_lab.php');


$group_id = strtolower(str_replace(" ", "_", $_GET['group_id']));
$lab_obj = new Lab();
$allTestGroups = &$lab_obj->AllActiveTestGroups();

$relatedGroups = array();
$groups = [];
if (@$allTestGroups->FetchRow()) {
	$groups = $allTestGroups->getArray();
}

foreach ($groups as $group) {
	$value = [
		'nr' => $group['nr'],
		'name' => $group['name'],
		'sort_order' => $group['sort_order']
	];
	if ($group['group_id'] == $group_id || $group['nr'] == -1) {
		$relatedGroups[] = $value;
	}
	
}

$data['relatedGroups'] = $relatedGroups;
header('Content-type: application/json');
echo json_encode( $data );




?>