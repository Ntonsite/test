<?php

require './roots.php';
require $root_path . 'include/inc_environment_global.php';

define('LANG_FILE', 'reporting.php');
require $root_path . 'include/inc_front_chain_lang.php';

require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';

require_once $root_path . 'vendor/autoload.php';
require_once $root_path . 'generated-conf/config.php';

require_once $root_path . 'include/care_api_classes/class_tz_reporting.php';

require_once $root_path . 'include/care_api_classes/class_tz_insurance.php';
require_once $root_path . 'include/care_api_classes/class_ward.php';

$ward_obj = new Ward;
$rep_obj = new selianreport();
$insurance_obj = new Insurance_tz;

$sql = "SELECT ID, ShowDescription, company_id, Fieldname FROM care_tz_drugsandservices_description";
$insurance_info = $db->Execute($sql);

if (!empty($insurance_info) && $insurance_info->RecordCount()) {
	$insurances = $insurance_info->GetArray();
}

$lastMonth = date('Y-m-d', strtotime('first day of last month'));

$datefrom = $_GET['from_date'] ? date('Y-m-d', strtotime($_GET['from_date'])) : $lastMonth;
$dateto = $_GET['to_date'] ? date('Y-m-d', strtotime($_GET['to_date'])) : "";
$company = $_GET['company'];
$doctor = $_GET['doctor'] ? $_GET['doctor'] : "";

$sqlQuery = "SELECT p.article, d.item_id, p.encounter_nr, p.article_item_number, d.unit_price, d.unit_price_1, d.unit_price_2, d.unit_price_3, d.unit_price_4, d.unit_price_5, d.unit_price_6, d.unit_price_7, d.unit_price_8, d.unit_price_9, d.unit_price_10, d.unit_price_11, p.modify_time, p.prescriber, p.prescribe_date, p.disable_id, p.comment FROM care_encounter_prescription p LEFT JOIN care_tz_drugsandservices d ON d.item_id = p.article_item_number WHERE p.status = 'deleted'";

if (@$datefrom) {
	$sqlQuery .= " AND p.modify_time >= '$datefrom' ";
}

if (@$dateto) {
	$sqlQuery .= " AND p.modify_time <= '$dateto' ";
}

if (@$doctor) {
	$sqlQuery .= " AND p.disable_id LIKE '%$doctor%' ";
}

$sqlQuery .= " ORDER BY modify_time DESC";

$prescriptions = array();
$prescription_info = $db->Execute($sqlQuery);

if (!empty($prescription_info) && $prescription_info->RecordCount()) {
	$prescriptions = $prescription_info->GetArray();
}

foreach ($prescriptions as $key => $prescription) {

	$insurance_id = 0;
	$careEncounterSQL = "SELECT pid from care_encounter WHERE encounter_nr = {$prescription['encounter_nr']} LIMIT 1";
	$careEncounterRow = $db->Execute($careEncounterSQL);
	if (!empty($careEncounterRow) && $careEncounterRow->RecordCount()) {
		$patient = $careEncounterRow->FetchRow();
		$prescriptions[$key]['pid'] = $patient['pid'];
		$patientId = $patient['pid'];
	}
	if (@$patientId) {
		$carePersonSQL = "SELECT insurance_ID, name_first, name_2, name_last, sub_insurance_id from care_person WHERE pid = {$patientId} LIMIT 1";
		$carePersonRow = $db->Execute($carePersonSQL);

		if (!empty($carePersonRow) && $carePersonRow->RecordCount()) {
			$carePerson = $carePersonRow->FetchRow();
			$prescriptions[$key]['patient_name'] = $carePerson['name_first'] . " " . $carePerson['name_2'] . " " . $carePerson['name_last'];
			$prescriptions[$key]['sub_insurance_id'] = $carePerson['sub_insurance_id'];
			$insurance_id = $carePerson['insurance_ID'];
		}
	}
	$prescriptions[$key]['insurance_id'] = $insurance_id;

	foreach ($insurances as $insurance) {
		if ($prescriptions[$key]['sub_insurance_id'] > 0) {
			if ($insurance['ID'] == $prescriptions[$key]['sub_insurance_id']) {
				$priceColumn = $insurance['Fieldname'];
				$prescriptions[$key]['unit_price'] = $prescription[$priceColumn];
				$prescriptions[$key]['insurance_name'] = $insurance['ShowDescription'];
				$prescriptions[$key]['unit_price'] = $prescription[$priceColumn];
			}

		} else {
			if ($insurance['company_id'] == $insurance_id) {
				$priceColumn = $insurance['Fieldname'];
				$prescriptions[$key]['unit_price'] = $prescription[$priceColumn];
				$prescriptions[$key]['insurance_name'] = $insurance['ShowDescription'];
				$prescriptions[$key]['unit_price'] = $prescription[$priceColumn];
			}
		}

	}
}

use CareMd\CareMd\CareUserRolesQuery;
use CareMd\CareMd\CareUsersQuery;

$userId = $_SESSION['sess_login_userid'];

$user = CareUsersQuery::create()->filterByLoginId($userId)->findOne()->toArray();
$roleId = $user['RoleId'];

$userRole = CareUserRolesQuery::create()->filterByRoleId($roleId)->findOne()->toArray();
$themeName = $user['ThemeName'];

$userPermissions = explode(" ", $userRole['Permission']);

$userPermissions = str_replace('_a_1_', '', $userPermissions);
$userPermissions = str_replace('_a_2_', '', $userPermissions);
$userPermissions = str_replace('_a_3_', '', $userPermissions);
$userPermissions = str_replace('_a_4_', '', $userPermissions);

$showFinancialReport = false;

foreach ($userPermissions as $permission) {

	if ($permission == "financialreportingread" || $permission == "allreportingread") {
		$showFinancialReport = true;
	}
}

if ($userPermissions[0] == "System_Admin" || $userPermissions[0] == "_a_0_all " || $userPermissions[0] == "_a_0_all") {
	$showFinancialReport = true;
}

?>

 <table cellspacing="0"  class="titlebar" border=0 width="100%">
    <tr valign=top  class="titlebar" >
        <td width="202" bgcolor="#99ccff" >
            &nbsp;&nbsp;<font color="#330066"><?php echo $LDConsumption; ?></font></td>
        <td width="408" align=right bgcolor="#99ccff">
            <a href="javascript: history.back();"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)" ></a>
            <a href="javascript:gethelp('reporting_overview.php','Reporting :: Overview')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>
            <a href="<?php echo $root_path; ?>modules/reporting_tz/reporting_main_menu.php" ><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>
        </td>
    </tr>
</table>
<?php require_once $root_path . 'main_theme/reportingNav.inc.php';?>

<style>
    .form-control:disabled, .form-control[readonly] {
        background-color: transparent;
    }
    .container {
        background-color: #fff;
        padding-top: 20px;
    }

    .dataTables_filter {
       width: 500%;
       float: right;
       text-align: right;
       padding-right: 10px;
    }

    .btn-right {
       float: right;
       text-align: right;
       padding-right: 10px;
    }
</style>

<div class="container" style="">
    <div class="row">
        <div class="col-md-12">
            <form class="form-inline">

                <div class="row">
                    <div class="col">
                      <div class="form-group bmd-form-group">
                        <label class="bmd-label-floating">From</label>
                        <input type="text"  name="from_date" value="<?php echo $datefrom ?>" class="form-control" id="datepicker" />
                      </div>
                    </div>

                    <div class="col">
                      <div class="form-group bmd-form-group">
                        <label class="bmd-label-floating">To</label>
                        <input type="text" name="to_date" value="<?php echo $dateto ?>" class="form-control" id="datepicker1" />
                      </div>
                    </div>

                    <div class="col">
                      <div class="form-group bmd-form-group">
                        <select name="company" class="form-control" id="company">
                            <option value="All">Company</option>
                            <?php foreach ($insurances as $insurance): ?>
                                <option <?php if ($insurance['company_id'] == $company) {echo 'selected';}?> value="<?php echo $insurance['company_id'] ?>"><?php echo $insurance['ShowDescription'] ?></option>
                            <?php endforeach?>
                        </select>
                      </div>
                    </div>

                    <div class="col">
                      <div class="form-group bmd-form-group">
                        <label class="bmd-label-floating">Doctor's Name</label>
                        <input type="text" name="doctor" value="<?php echo $doctor ?>" class="form-control" />
                      </div>
                    </div>
                </div>

              <button type="submit" class="btn btn-primary mb-2 mx-auto">Search</button>
            </form>

        </div>
    </div>

    <div class="row">
       <div class="col-md-12" width="100%">
            <table class="table table-striped table-bordered table-condensed datatable2 ">
            <thead>

                <tr>
                    <th>SN</th>
                    <th>Patient</th>
                    <th>Prescription Name</th>
                    <th>Prescriber</th>
                    <th>Insurance</th>
                    <th>Price</th>
                    <th>Prescribe Date</th>
                    <th>Deleted By</th>
                    <th>Comment</th>
                    <th>Deleted Date</th>
                 </tr>

            </thead>
            <tbody>
                <?php foreach ($prescriptions as $key => $prescription): ?>
                    <tr>
                        <td><?php echo ++$key ?></td>
                        <td><?php echo $prescription['patient_name'] ?></td>
                        <td><?php echo $prescription['article'] ?></td>
                        <td><?php echo $prescription['prescriber'] ?></td>
                        <td><?php echo $prescription['insurance_name'] ?></td>
                        <td class="text-right"><?php echo number_format($prescription['unit_price'], 2) ?></td>
                        <td><?php echo date('d/m/Y', strtotime($prescription['prescribe_date'])) ?></td>
                        <td><?php echo $prescription['disable_id'] ?></td>
                        <td><?php echo $prescription['comment'] ?></td>
                        <td><?php echo date('d/m/Y', strtotime($prescription['modify_time'])) ?></td>
                    </tr>
                <?php endforeach?>
            </tbody>
        </table>
       </div>
    </div>
</div>

<?php

require_once $root_path . 'main_theme/footer.inc.php';
?>











