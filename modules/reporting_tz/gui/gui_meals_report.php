
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
  urlholder="../../main/help-router.php?sid=<?php echo sid;?>&lang=$lang&helpidx="+x+"&src="+s+"&x1="+x1+"&x2="+x2+"&x3="+x3+"&x4="+x4;
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

 <?php require_once($root_path . 'main_theme/reportingNav.inc.php'); ?>
<form method="post" accept="">
<div class="row" style="margin-top: -30px;">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header " style="border-bottom: 1px dashed #eee;">
         <div class="row">
          <div class="col-md-2">
            <h4 class="card-title d-flex justify-content-start">Meals Report</h4> 
          </div>
          <div class="col-md-10">
              <div class="row justify-content-end">

                <div class="col-md-3">
                  <select name="meal_type[]" class="selectMultiple form-control" multiple data-live-search="true">
                    <option value="">--Select Meal Type--</option>
                    <option value="breakfast">Breakfast</option>
                    <option value="lunch">Lunch</option>
                    <option value="supper">Supper </option>
                    <option value="dinner">Dinner </option>
                    <option value="tea">Tea </option>
                    <option value="brunch">Brunch </option>
                    <option value="Beverage">beverage </option>
                    <option value="elevenses">Elevenses </option>
                  </select>
                </div>

                <div class="col-md-2">
                  <input placeholder="Start Date" style="background: none;" type="text" class="custom-select input-sm col-md-12" id="datepicker" value="<?php if(@$_POST['start_date']){echo $_POST['start_date']; } ?>" name="start_date"  >
                </div>

                <div class="col-md-2">
                  <input placeholder="End Date" style="background: none;" type="text" class="custom-select input-sm col-md-12" id="datepicker1" value="<?php if(@$_POST['end_date']){echo $_POST['end_date']; } ?>" name="end_date"  >
                </div>
              
                <div class="col-md-2">
                  <select class="custom-select input-sm col-md-12" name="patient_type" onchange="javascript:popdepts()" id="admission_id">
                    <option  value="all_opd_ipd">All Patients</option>
                    <option  value="1" <?php if($_POST['patient_type'] == 1){echo "selected"; } ?>>Inpatients</option>
                    <option  value="2" <?php if($_POST['patient_type'] == 2){echo "selected"; } ?>>Outpatients</option>
                  </select>
                </div>
                <div class="col-md-2" id="dept">
                  <select name="patient_department" class="custom-select input-sm col-md-12">
                    <option>All Departments</option>
                  </select>
                </div>
                <div class="col-md-1">
                  <button type="submit"  class="btn btn-success pharmacy-btn btn-primary btn-sm">Show</button>
                </div>
              </div>
          </div>
        </div>

      </div>

      <div class="card-body" style="min-height: 100vh; margin-top: 30px; " >

        <div class="row">
          <div class="col-md-12">
            
            <?php
            if (@$_POST) {
              $start_date = @$_POST['start_date']?date('Y-m-d', strtotime(str_replace("/", '-', $_POST['start_date']))): date('Y-m-d');
              $_POST['start_date'] = date('d/m/Y', strtotime($start_date));
              $end_date = @$_POST['end_date']?date('Y-m-d', strtotime(str_replace("/", '-', $_POST['end_date']))):"";
              $patient_type = $_POST['patient_type'];
              $patient_department = $_POST['patient_department'];
              $meal_type = $_POST['meal_type'];
             
              $sql = "SELECT ep.*, cp.name_first, cp.name_middle, cp.name_last FROM care_encounter_prescription ep
              LEFT JOIN care_encounter ce ON ce.encounter_nr = ep.encounter_nr
              LEFT JOIN care_person cp ON ce.pid = cp.pid
               WHERE ep.meal_type <> '' ";

              if (@$meal_type) {
                $meal = "";
                foreach ($meal_type as $key => $mealValue) {
                  if ($key == 0) {
                    $value = "'".$mealValue ."'";  
                  }else {
                    $value = ",'".$mealValue ."'"; 
                  }
                  $meal .= $value;
                }
                $sql .= " AND meal_type IN ($meal)";
              }
              if (@$start_date) {
                $sql .= " AND ep.prescribe_date >= '$start_date' ";
              }

              if (@$end_date) {
                $sql .= " AND ep.prescribe_date <= '$end_date' ";
              }
              if (is_numeric($patient_type)) {
                $sql .= " AND ce.encounter_class_nr = $patient_type ";
                
                if ($patient_type == 2) {
                  if (is_numeric($patient_department)) {
                    $sql .= " AND ce.current_dept_nr = $patient_department ";
                  }
                }else {
                  if (is_numeric($patient_department)) {
                    $sql .= " AND ce.current_ward_nr = $patient_department ";
                  }
                }
                
              }
              $meals = array();              
              $result = $db->Execute($sql);
              if (@$result && $result->RecordCount() > 0) {
                $meals = $result->GetArray();
              }
              $number = 1;
            }
            ?>

            <table class="table datatable4 table-striped table-condensed table-hover">
              <thead>
                <th>SN</th>
                <th>Patient Name</th>
                <th>Meal</th>
                <th>Date</th>
                <th class="sum">Unit Price</th>
                <th class="sum">Qty</th>
                <th class="sum">Amount</th>
              </thead>
              <tbody>
                <?php foreach ($meals as $meal): ?>
                  <tr>
                    <td><?php echo $number++ ?></td>
                    <td><?php echo $meal['name_first'] . " " . $meal['name_middle'] . " " . $meal['name_last'] ?></td>
                    <td><?php echo $meal['article'] ?></td>
                    <td><?php echo date('d/m/Y', strtotime($meal['prescribe_date'])) ?></td>
                    <td class="text-right"><?php echo number_format($meal['price'], 2); ?></td>
                    <td class="text-right"><?php echo number_format($meal['total_dosage']) ?></td>
                    <td class="text-right"><?php echo number_format($meal['price'] * $meal['total_dosage'], 2) ?></td>
                  </tr>
                <?php endforeach ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3" style="text-align:right">Total:</th>
                  <th class="text-right"></th>
                  <th class="text-right"></th>
                  <th class="text-right"></th>
                  <th class="text-right"></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

      </div>
      
     
    </div>
  </div>


  </div>
</form>


</div>

</BODY>
</HTML>
