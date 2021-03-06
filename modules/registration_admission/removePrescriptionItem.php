<?php

require_once('./roots.php');
include_once($root_path . 'include/inc_environment_global.php');

$currData = $_SESSION['item_array'];
$itemValue = $_POST['itemValue'];

$newItems = array();

foreach ($currData as $item) {
    if ($item != $itemValue) {
       array_push($newItems, $item);
    }
}

$_SESSION['item_array'] = $newItems;
