<?php


require('./roots.php');

require($root_path . 'include/inc_environment_global.php');

define('LANG_FILE', 'reporting.php');
require($root_path . 'include/inc_front_chain_lang.php');

define('NO_CHAIN', 1);
require_once($root_path . 'include/inc_front_chain_lang.php');
$_COOKIE['report_sub'] = "Meals Report";

require_once($root_path . 'main_theme/head.inc.php');
require_once($root_path . 'main_theme/header.inc.php');
require_once($root_path . 'main_theme/topHeader.inc.php');
require_once($root_path . 'include/care_api_classes/class_ward.php');
$ward_obj = new Ward;

$items = 'nr,name';
$TP_SELECT_BLOCK_IN = '';
$ward_info = $ward_obj->getAllWardsItemsObject($items);
$TP_SELECT_BLOCK_IN.='<select name="patient_department" class="custom-select input-sm col-md-12" size="1"><option value="all_ipd">all</option>';
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
$TP_SELECT_BLOCK = '<select name="patient_department" class="custom-select input-sm col-md-12" size="1"><option value="all_opd">all</option>';
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


require_once('gui/gui_meals_report.php');

require_once($root_path . 'main_theme/footer.inc.php');
require_once($root_path . 'js/care_md/auditor_corner_chart_js.php');

?>
<script src="<?php echo $root_path ?>/js/care_md/bootstrap-select.min.js"></script>

<script type="text/javascript">
  $(function () {
    $('.selectMultiple').selectpicker({
        includeSelectAllOption: true,
        noneSelectedText: 'All Meals Type'
    });

});
function popdepts() {
  var x = document.getElementById("admission_id").value;
  console.log(x)
  if (x == 1) {
      document.getElementById("dept").innerHTML =<?php echo json_encode($TP_SELECT_BLOCK_IN); ?>

  } else if (x == 2) {
      document.getElementById("dept").innerHTML =<?php echo json_encode($TP_SELECT_BLOCK); ?>
  } else if (x == "all_opd_ipd") {

      document.getElementById("dept").innerHTML = "<select class='custom-select input-sm col-md-12'><option>All Departments</select> ";
  }
}
</script>