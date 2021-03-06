<?php

require './roots.php';
require $root_path . 'include/inc_environment_global.php';
require($root_path . 'language/en/lang_en_reporting.php');
require($root_path . 'language/en/lang_en_date_time.php');
require($root_path . 'include/inc_date_format_functions.php');
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
require_once($root_path . 'include/care_api_classes/class_ward.php');
//require($root_path . 'include/inc_checkdate_lang.php');

//define('LANG_FILE', 'reporting.php');
//require $root_path . 'include/inc_front_chain_lang.php';

define('NO_CHAIN', 1);
//require_once $root_path . 'include/inc_front_chain_lang.php';
$_COOKIE['report_sub'] = "Number of Radiology done";
$ward_obj = new Ward;
$items = 'nr,name';
$TP_SELECT_BLOCK_IN = '';
$ward_info = $ward_obj->getAllWardsItemsObject($items);
$TP_SELECT_BLOCK_IN.='<select name="current_ward_nr" size="1"><option value="all_ipd">all</option>';
if (!empty($ward_info) && $ward_info->RecordCount()) {
    while ($station = $ward_info->FetchRow()) {
        $TP_SELECT_BLOCK_IN.='
								<option value="' . $station['nr'] . '" ';
        if (isset($current_ward_nr) && ($current_ward_nr == $station['nr']))
            $TP_SELECT_BLOCK.='selected';
        $TP_SELECT_BLOCK_IN.='>' . $station['name'] . '</option>';
    }
}
$TP_SELECT_BLOCK_IN.='</select>';

require_once($root_path . 'include/care_api_classes/class_department.php');
$dept_obj = new Department;
$medical_depts = $dept_obj->getAllMedical();
$TP_SELECT_BLOCK = '<select name="dept_nr" size="1"><option value="all_opd">all</option>';
$later_depts = $medical_depts;

while (list($x, $v) = each($medical_depts)) {
    $TP_SELECT_BLOCK.='
	<option value="' . $v['nr'] . '">';
    $buffer = $v['LD_var'];
    if (isset($$buffer) && !empty($$buffer))
        $TP_SELECT_BLOCK.=$$buffer;
    else
        $TP_SELECT_BLOCK.=$v['name_formal'];
    $TP_SELECT_BLOCK.='</option>';
}
$TP_SELECT_BLOCK.='</select>';


$insurance_obj = new Insurance_tz;

if (isset($_POST['show'])) {
	if ($_POST['date_from']!==""&&$_POST["date_to"]!=="") {
       $dateFrom=explode("/", $_POST['date_from']);
       $dateTo=explode("/", $_POST["date_to"]);
       $dateFromSql=$dateFrom[2].'-'.$dateFrom[1].'-'.$dateFrom[0].' 00:00:00';
       $dateToSql=$dateTo[2].'-'.$dateTo[1].'-'.$dateTo[0].' 23:59:59';

       //print_r($_POST);

       switch ($_POST['admission_id']) {
       	case 'all_opd_ipd':
       		$currentDeptWard="";
          
       		break;
       	case '2':
       		if ($_POST['dept_nr']=='all_opd') {
            $currentDeptWard='AND encounter_class_nr='.$_POST['admission_id'];
                              
          }else{
            $currentDeptWard='AND current_dept_nr='.$_POST['dept_nr'];
            
            
          }
       		break;

        case '1':
          if ($_POST['current_ward_nr']=='all_ipd') {
            $currentDeptWard='AND encounter_class_nr='.$_POST['admission_id'];  
             
                  
          }else{
            $currentDeptWard='AND current_ward_nr='.$_POST['current_ward_nr'];
          }
          break;  	
       	
       	default:
       		# code...
       		break;
       }     

        
	  		
	}

//print_r($_POST);
  if ($_POST['insurance']<0) {
    $insurance="";
  }else{
    $insurance="AND insurance_ID=".$_POST['insurance'];

  }


}












require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';

require_once 'gui/gui_radiologyTests.php';

require_once $root_path . 'main_theme/footer.inc.php';
//require_once $root_path . 'js/care_md/auditor_corner_chart_js.php';

?>
<script src="<?php echo $root_path ?>/js/care_md/bootstrap-select.min.js"></script>
