<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
require($root_path . 'include/care_api_classes/class_tz_pharmacy.php');
$pageName = "Pharmacy";


/**
 * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
 * GNU General Public License
 * Copyright 2005 Robert Meggle based on the development of Elpidio Latorilla (2002,2003,2004,2005)
 * elpidio@care2x.org, meggle@merotech.de
 *
 * See the file "copy_notice.txt" for the licence notice
 */
$lang_tables[] = 'pharmacy.php';
define('NO_2LEVEL_CHK', 1);
require($root_path . 'include/inc_front_chain_lang.php');

require_once($root_path . 'include/care_api_classes/class_tz_pharmacy.php');
$debug = FALSE;

$product_obj = new Product();

if ($debug) {

    function print_debug_info($name, $value) {
        if (isset($value))
            echo $name . " is set to value: " . $value . "<br>";
    }

    print_debug_info("Search pattern", $keyword);
    print_debug_info("Category", $category);
}

// prepare the category-select box:
$classfication_options = '';
$all_cassifications_array = $product_obj->get_all_categories();
while ($classification_array = $all_cassifications_array->FetchRow()) {
    if ($category == $classification_array[0]) {

        $classfication_options = $classfication_options . "<option selected>" . $classification_array[0] . "</option>\n";
    } else {
        $classfication_options = $classfication_options . "<option>" . $classification_array[0] . "</option>\n";
    }
}

if (!empty($keyword) || !empty($category)) {
    // We have work...


    if (empty($keyword))
        $keyword = "*";


    $search_results = $product_obj->get_array_search_results($keyword, $category);

    $number_of_search_results = $search_results->RecordCount();

    $bg_color_change = 1;

    while ($search_element = $search_results->FetchRow()) {
        if ($bg_color_change) {
            $http_buffer.="<tr bgcolor='#ffffaa' id='hovv'>";
            $bg_color_change = 0;
        } else {
            $http_buffer.="<tr bgcolor='#ffffdd' id='hovv'>";
            $bg_color_change = 1;
        }

       // print_r($search_element);

        $item_id = $search_element['item_id'];
        $part_code = $search_element['partcode'];
        $item_plausibility = $search_element['plausibility'];
        $item_description = $product_obj->get_description($item_id);
        $item_number = $product_obj->get_itemnumber($item_id);
        $item_classification = $product_obj->get_item_classification($item_id);
        $item_unit_price = $product_obj->get_all_prices($item_id);
        // $itemPrices = $product_obj->getAllPricesColumns($item_id);

        $inuse = $search_element['not_in_use'];
        $min_level=$search_element['min_level'];
        $check = ($inuse == 1) ? 'checked=\"checked\"' : '';
        $nhif_item_code=$search_element['nhif_item_code'];
        $nhif_is_restricted = $search_element['nhif_is_restricted'];
        $restrict_over_dose = $search_element['restrict_over_dose'];




        // $schemes = $product_obj->getProductNHIFSchemes($item_id);

        // $schemeRow = "<div id='schemediv".$item_id."'>";

        // $schemeRow .= "<span id='schemerow".$item_id."'><i class='material-icons ica' onclick='addNHIFScheme(".$item_id.")'>add_circle</i></span><a href='#'  data-placement='bottom' data-html='true' id='schemepopover".$item_id."' data-content=\"".$content."\"> </a><br>";
        // foreach ($schemes as $scheme) {
        //     $schemeRow .= "<span id='scheme".$scheme['id']."' >".$scheme['scheme_id']."<i class='material-icons ic' onclick='deleteNHIFScheme(".$scheme['id'].")'>delete</i></span><br>";
        // }

        // $schemeRow .= "</div>";

        $notRestricted = $isRestricted = "";
        if ($nhif_is_restricted == 1) {
            $isRestricted = " selected ";
        }else {
            $notRestricted = " selected ";
        }
        $isNHIFRestrictedRow = "<select style='width: 50px;' id='nhif_is_restricted".$item_id."' onchange='updateDrugRow(".$item_id.",\"nhif_is_restricted\", \"nhif_is_restricted\")' ><option ".$notRestricted."value='0'>No</option><option ".$isRestricted." value='1'>Yes</option></select>";

        $notRestrictedOverDose = $isRestrictedOverDose = "";
        if ($restrict_over_dose == 1) {
            $isRestrictedOverDose = " selected ";
        }else {
            $notRestrictedOverDose = " selected ";
        }
        $isRestrictedOverDoseRow = "<select style='width: 50px;' id='restrict_over_dose".$item_id."' onchange='updateDrugRow(".$item_id.",\"restrict_over_dose\", \"restrict_over_dose\")' ><option ".$notRestrictedOverDose."value='0'>No</option><option ".$isRestrictedOverDose." value='1'>Yes</option></select>";

        $http_buffer.=" <td class=\"b r\">" . $item_number . "</td>
                    <td class=\"b r\">" . $part_code . "&nbsp;</td>

                    <td class=\"b r\"  id=\"nav" . $item_id . "\" class=\"redd\">
                        <input style='width: 55px;' type=\"text\" id=\"nhif_item_code" . $item_id . "\" size=\"3\" name=\"nhif_item_code".$item_id."\" value=\"".$nhif_item_code."\"  onChange=\"updateDrugRow(" .$item_id . ",'nhif_item_code', 'nhif_item_code')\" >
                    </td>

                    <td class=\"b r\">
                        ".$isNHIFRestrictedRow."
                    </td>
                    <td class=\"b r\">
                        ".$isRestrictedOverDoseRow."
                    </td>

                    <td class=\"b r\">" . str_replace(strtolower(trim($keyword)), "<b>" . trim($keyword) . "</b>", strtolower($item_description)) . "</td>
		   			<td class=\"b r\">" . $item_classification . "&nbsp;</td>
                    <td class=\"b r\" align=\"right\">" . $item_unit_price . "&nbsp;</td>
					<td class=\"b r\" align=\"right\">" . 'WIP' . "&nbsp;</td>
                    <td class=\"b r\" align=\"right\">" . $item_plausibility . "&nbsp;</td>
                    <td class=\"b r\"><div align=\"center\"><a href='#'  data-placement='left' data-html='true' data-title=\"".$item_description." Prices\" onclick=\"showPricesPopover(".$item_id.")\"  id='pricespopover".$item_id."' ><img src=\"" . $root_path . "gui/img/common/default/common_infoicon.gif\" width=\"16\" height=\"16\" border=\"0\"></a></td>
                    <td class=\"b r\"><div align=\"center\"><a href=\"pharmacy_tz_new_product.php?mode=edit&item_id=" . $item_id . "&GOBACKTOSEARCH=TRUE&category=" . $category . "&keyword=" . $keyword . "\"><img src=\"" . $root_path . "gui/img/common/default/hammer.gif\" width=\"16\" height=\"16\" border=\"0\"></a></td>
                    <td class=\"b r\"><div align=\"center\"><a href=\"pharmacy_tz_new_product.php?mode=erase&item_id=" . $item_id . "&keyword=" . $keyword . "\"><img src=\"" . $root_path . "gui/img/common/default/delete.gif\" width=\"16\" height=\"16\" border=\"0\"></a></td>
                    <td class=\"b\" nowrap id=\"nav" . $item_id . "\" class=\"redd\"><input type=\"checkbox\" id=\"itemx" . $item_id . "\"  value=\"itemx" . $item_id . "\" " . $check . " onclick=\"sendQest(" . $item_id . ")\" ></td>
                     <td class=\"b\" nowrap id=\"nav" . $item_id . "\" class=\"redd\"><input type=\"text\" id=\"jina" . $item_id . "\" size=\"3\" name=\"jina".$item_id."\" value=\"".$min_level."\"  onChange=\"insertValue(" .$item_id . ")\" ></td><td id=\"txtHint\"></td>";
                    
                   
        $http_buffer.="</tr>";
    }
}
require_once($root_path . 'main_theme/head.inc.php');
require_once($root_path . 'main_theme/header.inc.php');
require_once($root_path . 'main_theme/topHeader.inc.php');

require ("gui/gui_pharmacy_tz_search.php");
require_once($root_path . 'main_theme/footer.inc.php');

?>