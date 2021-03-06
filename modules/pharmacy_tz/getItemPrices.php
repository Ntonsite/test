<?php

require_once('./roots.php');
include_once($root_path . 'include/inc_environment_global.php');
global $db;
require($root_path . 'include/care_api_classes/class_tz_pharmacy.php');

$product_obj = new Product();

$item_id = $_GET['item_id'];


$content = "<table class='table table-bordered table-hover'><thead><tbody>";

$itemPrices = $product_obj->getAllPricesColumns($item_id);

foreach ($itemPrices as $iKey => $itemPrice) {
    if (!is_int($iKey)) {
         preg_match("/[^_]+$/", $iKey, $jina);
        $last_word = (int)$jina[0];
        $content .= "<tr><td><input class='input' id='itempricevalue".$last_word .$item_id ."' onchange='updateDrugItemPrice(".$item_id.", ".$last_word.")' type='number' name='".$iKey."' value='".$itemPrice."'></td><td>".str_replace("_", " ", $iKey)."</td></tr>";
    }
}


$content .= "</tbody></thead></table>";

print_r($content);
