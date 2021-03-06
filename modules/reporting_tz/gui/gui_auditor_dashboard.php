<?php
require './roots.php';

$insurances = array();
$insuranceSQL = "SELECT DISTINCT (company_id) AS company_id, name
FROM care_tz_insurance
INNER JOIN care_tz_company ON care_tz_company.id = care_tz_insurance.company_id
WHERE parent = -1
AND (
  (care_tz_insurance.start_date <= UNIX_TIMESTAMP())
  AND (care_tz_insurance.end_date >= UNIX_TIMESTAMP())
)
AND cancel_flag ='0' AND name !='CASH-PATIENT'
ORDER BY `care_tz_insurance`.`company_id` ASC ";

$insuranceResult = $db->Execute($insuranceSQL);
while ($r = $insuranceResult->FetchRow()) {
	$insurances[] = $r;
}
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<HTML>
<HEAD>
 <TITLE>Auditor's Corner</TITLE>
 <meta name="Description" content="Hospital and Healthcare Integrated Information System - CAREMD">
 <meta name="Author" content="Rayton Kiwelu">
 <meta name="Generator" content="various: Quanta, AceHTML 4 Freeware, NuSphere, PHP Coder">
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="<?php echo $root_path ?>css/themes/care_md/bootstrap-select.css" rel="stylesheet"/>

 <style>

   .filter-option {
    color: #000;
    background: #fff;
   }

   @media (min-width: 992px)
    .modal-lg {
        max-width: 1000px !important;
    }

 </style>

  	<script language="javascript" >
<!--
function gethelp(x,s,x1,x2,x3,x4)
{
	if (!x) x="";
	urlholder="../../main/help-router.php?sid=<?php echo SID; ?>&lang=$lang&helpidx="+x+"&src="+s+"&x1="+x1+"&x2="+x2+"&x3="+x3+"&x4="+x4;
	helpwin=window.open(urlholder,"helpwin","width=790,height=540,menubar=no,resizable=yes,scrollbars=yes");
	window.helpwin.moveTo(0,0);
}
// -->

</script>
<link rel="stylesheet" href="../../css/themes/default/default.css" type="text/css">
<script language="javascript" src="../../js/hilitebu.js"></script>

<style TYPE="text/css">
A:link  {color: #000066;}
A:hover {color: #cc0033;}
A:active {color: #cc0000;}
A:visited {color: #000066;}
A:visited:active {color: #cc0000;}
A:visited:hover {color: #cc0033;}
.font-lg {
  font-size: 16px;
}
tr {
  cursor: pointer;
}

.font-xs {
  font-size: 12px !important;
}

.dropdown-menu .show {
   position: relative;
  max-height: 300px
}


</style>

</HEAD>
<BODY bgcolor=#ffffff link=#000066 alink=#cc0000 vlink=#000066 style="background-color: #fff;"  >


<table width=100% border=0 cellspacing=0 height=100%>
	<tr>
		<td  valign="top" align="middle" height="35">
			 <table cellspacing="0"  class="titlebar" border=0 >
 				<tr valign=top  class="titlebar" >
  					<td width="202" bgcolor="#99ccff" >
    					&nbsp;&nbsp;<font color="#330066">Reporting: Auditor Corner</font></td>
						  <td width="408" align=right bgcolor="#99ccff">
						   <a href="javascript: history.back();"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)" ></a>
						   <a href="javascript:gethelp('reporting_overview.php','Reporting :: Overview')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>
						   <a href="javascript:history.back()" ><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>
						  </td>
  					 </tr>
 </table>

 <?php require_once $root_path . 'main_theme/reportingNav.inc.php';?>

<div class="row" style="margin-top: -30px;">

  <div class="col-md-12">
    <div class="card card-chart" style="margin-bottom: -10px;">
      <div class="card-header " style="border-bottom: 1px dashed #eee;">
         <div class="row">
          <div class="col-md-4">
            <h4 class="card-title d-flex justify-content-start">Medicines, Procedures and Services</h4>
          </div>
          <div class="col-md-8">
              <div class="row justify-content-end">
                <div class="col-md-4">
                 <select class="selectMulti form-control" id="pharmacypatientinsurance" name="insurances[]" multiple data-live-search="true">
                    <option value="0">(Cash Patients)</option>
                    <?php foreach ($insurances as $insurance): ?>
                      <option value="<?php echo $insurance['company_id'] ?>"><?php echo $insurance['name'] ?></option>
                    <?php endforeach?>
                  </select>
                </div>
                <div class="col-md-3">
                  <select class="custom-select input-sm col-md-12" id="pharmacypatienttype">
                    <option  value="">All Patients</option>
                    <option  value="Inpatient">Inpatients</option>
                    <option  value="Outpatient" selected>Outpatients</option>
                  </select>
                </div>
                <div class="col-md-3">

                  <select class="custom-select input-sm col-md-12" onchange="openPharmacyPopover()" id="pharmacypatientperiod">
                    <option value="">Select View</option>
                    <option value="ThisWeek" >This Week</option>
                    <option value="ThisMonth">This Month</option>
                    <option value="ThisYear">This Year</option>
                    <option value="LastWeek">Last Week</option>
                    <option value="LastMonth">Last Month</option>
                    <option value="LastYear">Last Year</option>
                    <option value="custom">Custom</option>
                  </select>
                   <div>

                     <a href="#"  data-placement="bottom" class="pharmacypopover" data-content="<label for='start_date'>Start Date</label><input type='date' name='start_date' id='pharmacy_start_date' class='input-sm custom-select' style='background:none;'><br><label for='end_date'>End Date</label><input type='date' name='end_date' class='input-sm custom-select' id='pharmacy_end_date' style='background:none;'>"></a>
                   </div>
                </div>
                <div class="col-md-2">
                  <button onclick="showPharmacyPatients()" class="btn btn-success pharmacy-btn btn-primary btn-sm">Show</button>
                </div>
              </div>
          </div>
        </div>

      </div>
      <div class="card-body" style="min-height: 350px;">

        <div class="row justify-content-start align-items-center">
          <div class="col-md-8 pharmacypatients">
           <canvas class="" id="pharmacypatients" style="max-height: 400px;min-height: 350px;"></canvas>


          </div>
          <div class="col-md-4" style="text-align: left;">
           <table class="table table-condensed table-hover">
             <thead>
               <th></th>
               <th></th>
             </thead>
             <tbody>
              <tr id="pharmacyrangedatesdiv" style="display: none;">
                <td colspan="2" style="text-align: center; font-size: 18px;" id="pharmacyrangedates"></td>
              </tr>
               <tr class="text-success revenue" url="" title="View Detailed Revenue" onclick="goToRevenueURL()">
                 <td>Total Paid:</td>
                 <td id="totalServedPaid" class="text-right font-lg"></td>
               </tr>
               <tr class="text-danger" title="View Dispensed Unpaid Patients" onclick="viewPharmacyRecords('servedUnpaid')">
                 <td>Total Dispensed Unpaid:</td>
                 <td id="totalServedUnPaid"  class="text-right font-lg"></td>
               </tr>

               <tr class="text-danger" title="View Pending Unpaid Patients" onclick="viewPharmacyRecords('pendingUnpaid')">
                 <td>Total Pending Unpaid:</td>
                 <td id="totalPendingUnpaid" class="text-right font-lg"></td>
               </tr>

                <tr class="text-danger" title="View All Unpaid Patients" onclick="viewPharmacyRecords('uncollected')">
                  <td>Total Unpaid:</td>
                  <td id="totalUnCollected" class="text-right font-lg"></td>
                </tr>

                 <tr class="text-primary">
                  <td>Unpaid Percent Ratio:</td>
                  <td id="percentUncollected" class="text-right font-lg"></td>
                </tr>

             </tbody>
           </table>
          </div>
        </div>

      </div>

    </div>
  </div>

 <div class="col-md-12">
    <div class="card card-chart" style="margin-bottom: -10px;">
      <div class="card-header " style="border-bottom: 1px dashed #eee;">
         <div class="row">
          <div class="col-md-4">
            <h4 class="card-title d-flex justify-content-start">Laboratory</h4>
          </div>
          <div class="col-md-8">
              <div class="row justify-content-end">
                <div class="col-md-4">
                  <select class="selectMulti form-control" id="laboratorypatientinsurance" name="insurances[]" multiple data-live-search="true">
                    <option value="0">(Cash Patients)</option>
                    <?php foreach ($insurances as $insurance): ?>
                      <option value="<?php echo $insurance['company_id'] ?>"><?php echo $insurance['name'] ?></option>
                    <?php endforeach?>
                  </select>
                </div>

                <div class="col-md-3">
                  <select class="custom-select input-sm col-md-12" id="laboratorypatienttype">
                    <option  value="">All Patients</option>
                    <option  value="Inpatient">Inpatients</option>
                    <option  value="Outpatient" selected>Outpatients</option>
                  </select>
                </div>

                <div class="col-md-3">
                  <select class="custom-select input-sm col-md-12" onchange="openLaboratoryPopover()" id="laboratorypatientperiod">
                    <option value="">Select View</option>
                    <option selected value="ThisWeek">This Week</option>
                    <option value="ThisMonth">This Month</option>
                    <option value="ThisYear">This Year</option>
                    <option  value="LastWeek">Last Week</option>
                    <option value="LastMonth">Last Month</option>
                    <option value="LastYear">Last Year</option>
                    <option value="custom">Custom</option>
                  </select>
                    <div>

                      <a href="#"  data-placement="bottom" class="laboratorypopover" data-content="<label for='start_date'>Start Date</label><input type='date' name='start_date' id='laboratory_start_date' class='input-sm custom-select' style='background:none;'><br><label for='end_date'>End Date</label><input type='date' name='end_date' class='input-sm custom-select' id='laboratory_end_date' style='background:none;'>"> </a>
                    </div>
                </div>
                <div class="col-md-2">
                  <button onclick="showLaboratoryPatients()" class="btn btn-success lab-btn btn-primary btn-sm">Show</button>
                </div>
              </div>
          </div>
        </div>

      </div>
      <div class="card-body" style="min-height: 300px;">

        <div class="row justify-content-start align-items-center">
          <div class="col-md-8 laboratorypatients">
           <canvas class="laboratorypatients" id="laboratorypatients" style="max-height: 400px;min-height: 300px;"></canvas>


          </div>

          <div class="col-md-4" style="text-align: left;">
           <table class="table table-condensed table-hover">
             <thead>
               <th></th>
               <th></th>
             </thead>
             <tbody>
                <tr id="laboratoryrangedatesdiv" style="display: none;">
                  <td colspan="2" style="text-align: center; font-size: 18px;" id="laboratoryrangedates"></td>
                </tr>

              <tr class="text-success">
                 <td>Total Paid:</td>
                 <td id="labtotalCollected" class="text-right font-lg"></td>
               </tr>

               <tr class="text-danger" title="View All Pending Unpaid Patients" onclick="viewLaboratoryRecords('pendingUnpaid')">
                 <td>Total Pending Unpaid:</td>
                 <td id="labtotalPendingUnPaid" class="text-right font-lg"></td>
               </tr>

                <tr class="text-danger" title="View All Served Unpaid Patients" onclick="viewLaboratoryRecords('servedUnpaid')">
                 <td>Total Dispensed Unpaid:</td>
                 <td id="labtotalServedUnPaid" class="text-right font-lg"></td>
               </tr>

                <tr class="text-danger" title="View All Unpaid Patients" onclick="viewLaboratoryRecords('uncollected')">
                  <td>Total Unpaid:</td>
                  <td id="labtotalUnCollected" class="text-right font-lg"></td>
                </tr>

                 <tr class="text-primary">
                  <td>Unpaid Percent Ratio:</td>
                  <td id="labpercentUncollected" class="text-right font-lg"></td>
                </tr>

             </tbody>
           </table>
          </div>
          </div>
        </div>

      </div>

    </div>


<div class="col-md-12">
    <div class="card card-chart" style="margin-bottom: -10px;">
      <div class="card-header " style="border-bottom: 1px dashed #eee;">
         <div class="row">
          <div class="col-md-4">
            <h4 class="card-title d-flex justify-content-start">Radiology</h4>
          </div>
          <div class="col-md-8">
              <div class="row justify-content-end">

                <div class="col-md-4">
                  <select class="selectMulti form-control" id="radiologypatientinsurance" name="insurances[]" multiple data-live-search="true">
                    <option value="0">(Cash Patients)</option>
                    <?php foreach ($insurances as $insurance): ?>
                      <option value="<?php echo $insurance['company_id'] ?>"><?php echo $insurance['name'] ?></option>
                    <?php endforeach?>
                  </select>
                </div>

                <div class="col-md-3">
                  <select class="custom-select input-sm col-md-12" id="radiologypatienttype">
                    <option  value="">All Patients</option>
                    <option  value="Inpatient">Inpatients</option>
                    <option  value="Outpatient" selected>Outpatients</option>
                  </select>
                </div>

                <div class="col-md-3">
                  <select class="custom-select input-sm col-md-12" onchange="openRadiologyPopover()" id="radiologypatientperiod">
                    <option value="">Select View</option>
                    <option selected value="ThisWeek">This Week</option>
                    <option value="ThisMonth">This Month</option>
                    <option value="ThisYear">This Year</option>
                    <option  value="LastWeek">Last Week</option>
                    <option value="LastMonth">Last Month</option>
                    <option value="LastYear">Last Year</option>
                    <option value="custom">Custom</option>
                  </select>
                  <div>

                    <a href="#"  data-placement="bottom" class="radiologypopover" data-content="<label for='start_date'>Start Date</label><input type='date' name='start_date' id='radiology_start_date' class='input-sm custom-select' style='background:none;'><br><label for='end_date'>End Date</label><input type='date' name='end_date' class='input-sm custom-select' id='radiology_end_date' style='background:none;'>"> </a>
                  </div>
                </div>
                <div class="col-md-2">
                  <button onclick="showRadiologyPatients()" class="btn btn-success radiology-btn btn-primary btn-sm">Show</button>
                </div>
              </div>
          </div>
        </div>

      </div>
      <div class="card-body" style="min-height: 300px;">

        <div class="row justify-content-start align-items-center">
          <div class="col-md-8 radiologypatients">
           <canvas class="radiologypatients" id="radiologypatients" style="max-height: 400px;min-height: 300px;"></canvas>


          </div>

          <div class="col-md-4" style="text-align: left;">
           <table class="table table-condensed table-hover">
             <thead>
               <th></th>
               <th></th>
             </thead>
             <tbody>
              <tr id="radiologyrangedatesdiv" style="display: none;">
                <td colspan="2" style="text-align: center; font-size: 18px;" id="radiologyrangedates"></td>
              </tr>

                <tr class="text-success">
                 <td>Total Paid:</td>
                 <td id="radiologytotalCollected" class="text-right font-lg"></td>
               </tr>

               <tr class="text-danger" title="View Pending Unpaid Patients" onclick="viewRadiologyRecords('pendingUnpaid')">
                 <td>Total Pending Unpaid:</td>
                 <td id="radiologytotalPendingUnpaid" class="text-right font-lg"></td>
               </tr>

               <tr class="text-danger" title="View Dispensed Unpaid Patients" onclick="viewRadiologyRecords('servedUnpaid')">
                 <td>Total Dispensed Unpaid:</td>
                 <td id="radiologytotalServedUnpaid" class="text-right font-lg"></td>
               </tr>

                <tr class="text-danger" title="View All Unpaid Patients" onclick="viewRadiologyRecords('uncollected')">
                  <td>Total Unpaid:</td>
                  <td id="radiologytotalUnCollected" class="text-right font-lg"></td>
                </tr>

                 <tr class="text-primary">
                  <td>Unpaid Percent Ratio:</td>
                  <td id="radiologypercentUncollected" class="text-right font-lg"></td>
                </tr>

             </tbody>
           </table>
          </div>
          </div>
        </div>

      </div>

    </div>



  </div>


</div>

</BODY>
</HTML>

<!-- Radiology Modal -->
<div class="modal" id="radiologyModal">
  <div class="modal-dialog modal-lg" style="max-width: 90%;">
    <div class="modal-content">

      <!-- Modal header -->
      <div class="modal-header">
        <h4 class="modal-title">Radiology Records</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
         <div class="table-responsive">
            <table id="radiologydatatable" class="table table-hover table-stripped table-condensed table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>PID</th>
                  <th>File No</th>
                  <th>Encounter No</th>
                  <th class="text-center" style="min-width: 100px;">Name</th>
                  <th class="text-center">Date</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Doctor</th>
                  <th class="text-center">Test</th>
                  <th class="text-center">Insurance</th>
                  <th class="text-center">Amount</th>
                </tr>
              </thead>
            </table>
          </div>
      </div>

    </div>
  </div>
</div>

<!-- Laboratory Modal -->
<div class="modal" id="laboratoryModal">
  <div class="modal-dialog modal-lg" style="max-width: 90%;">
    <div class="modal-content">

      <!-- Modal header -->
      <div class="modal-header">
        <h4 class="modal-title">Laboratory Records</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body labtableoverlay">
         <div class="table-responsive">
            <table id="laboratorydatatable" class="table table-hover table-stripped table-condensed table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>PID</th>
                  <th>Encounter No</th>
                  <th>Batch No</th>
                  <th class="text-center" style="min-width: 100px;">Name</th>
                  <th class="text-center">Date</th>
                  <th class="text-center">Doctor</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Insurance</th>
                  <th class="text-center">Test Name</th>
                  <th class="text-center">Amount</th>
                </tr>
              </thead>
            </table>
          </div>
      </div>

    </div>
  </div>
</div>

<!-- Pharmacy Modal -->
<div class="modal" id="pharmacyModal">
  <div class="modal-dialog modal-lg" style="max-width: 90%;">
    <div class="modal-content">

      <!-- Modal header -->
      <div class="modal-header">
        <h4 class="modal-title">Pharmacy Records</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
         <div class="table-responsive">
            <table id="pharmacydatatable" class="table table-hover table-stripped table-condensed table-bordered" cellspacing="0" width="100%" style="max-width: 100%">
              <thead>
                <tr>
                  <!-- <th class="font-xs">ID</th> -->
                  <th class="font-xs">PID</th>
                  <th class="font-xs">File No</th>
                  <th class="text-center font-xs" style="min-width: 100px;">Name</th>
                  <th class="text-center font-xs">Encounter</th>
                  <th class="text-center font-xs">Date</th>
                  <th class="text-center font-xs">Drug Name</th>
                  <th class="text-center font-xs">Prescriber</th>
                  <th class="text-center font-xs">Issuer</th>
                  <th class="text-center font-xs">Status</th>
                  <th class="text-center font-xs">Insurance</th>
                  <th class="text-center font-xs">Unit Price</th>
                  <th class="text-center font-xs">Total Dosage</th>
                  <th class="text-center font-xs">Amount</th>
                </tr>
              </thead>
            </table>
          </div>
      </div>

    </div>
  </div>
</div>
