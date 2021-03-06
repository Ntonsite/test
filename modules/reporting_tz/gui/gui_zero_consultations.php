<?php
if ($PRINTOUT) {
    echo '<head>
 <script language="javascript"> this.window.print(); </script>
<title>' . $LDReportingModule . '</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<STYLE TYPE="text/css">
.report{
font-size: 10px;
border-collapse:collapse;
}

</STYLE>

</head>';
    ?>

    <?php
    //BETWEEN '$startDate' AND '$endDate' $insuranceID $ward_dept
    $startDate = $_GET['date_from'];
    $endDate = $_GET['date_to'];
    $insuranceID = $_GET['insurance'];
    $ward_dept = $_GET['ward_dept'];
    $displayStartDate = $_GET['displayDFrom'];
    $displayEndDate = $_GET['displayTo'];
    $displayfundName = $_GET['displayfundName']; 
    $displayward_dept_name = $_GET['displayward_dept_name'];






 //WHERE ce.encounter_date BETWEEN '$startDate' AND '$endDate' $insuranceID $ward_dept 

    $styles = array('even','odd');

     $sqlReview="SELECT  cp.date_reg, 
                       cp.selian_pid as fileNr,
                       cp.name_first,
                       cp.name_last,
                       ce.encounter_date as visit_date,
                       care_encounter_notes.notes,
                       care_encounter_notes.create_id, 
                       care_encounter_notes.create_time, 
                       care_tz_drugsandservices.item_number, 
                       care_tz_billing_archive_elem.description,
                       care_tz_billing_archive_elem.User_Id,
                       care_tz_billing_archive_elem.amount*care_tz_billing_archive_elem.price as amt
                  FROM care_person cp 
                  INNER JOIN care_encounter ce ON ce.pid= cp.pid 
                  INNER JOIN care_tz_billing_archive ON care_tz_billing_archive.encounter_nr=ce.encounter_nr 
                  INNER JOIN  care_tz_billing_archive_elem ON care_tz_billing_archive_elem.nr = care_tz_billing_archive.nr INNER JOIN care_tz_drugsandservices ON care_tz_drugsandservices.item_id=care_tz_billing_archive_elem.item_number 
                  LEFT JOIN care_encounter_notes ON care_encounter_notes.encounter_nr=ce.encounter_nr  INNER JOIN care_users ON care_users.name = care_encounter_notes.create_id


                  WHERE care_users.nhif_qualification_id IN('1','2','3','4','5','6') AND care_tz_drugsandservices.item_number LIKE 'CONS%' AND care_tz_billing_archive_elem.price<1 AND FROM_UNIXTIME(care_tz_billing_archive_elem.date_change) BETWEEN  '$startDate' AND '$endDate' $insuranceID $ward_dept  GROUP BY fileNr,ce.encounter_nr ORDER BY ce.encounter_nr ";



          $resultReview=$db->Execute($sqlReview);

                      


    


        ?>
        <h1 align="center">
    <img src="../../gui/img/common/default/<?php echo $hospital_logo;?>" alt="" />
</h1>
        <?php

        $sqlAddress="SELECT value FROM care_config_global WHERE type='main_info_address'";
$result=$db->Execute($sqlAddress);
if ($result->RecordCount()) {
    $address=$result->FetchRow();

    echo '<div align="center"><b>'.$address['value'].'</b></div>';

}
?>
<table border="1" align="center">
<tr>
  <th id="header1" colspan="9" ><?php echo  date('d.m.Y',strtotime($startDate)).' To '.date('d.m.Y',strtotime($endDate)).';  ' .$fundName.'; '.$ward_dept_name.'; ';  ?></th>
  </tr>
  </tr>
  <tr id="header2">
    <th>Reg.Date</th><th>Visit Date</th><th>Patient Names</th><th>File Number</th><th>Description</th><th>Amt</th><th>CashierName</th><th>Doctor</th><th>Dr.Signature</th>
      
  </tr>
  <?php
  while ($row=$resultReview->FetchRow()) {
    echo '<tr>
    <td>'.date('d.m.Y',strtotime($row['date_reg'])).'</td>
    <td>'.date('d.m.Y',strtotime($row['visit_date'])).'</td>
    <td>'.$row['name_first'].' '.$row['name_last'].'</td>
    <td>'.$row['fileNr'].'</td>
    <td>'.$row['description'].'</td>
    <td>'.$row['amt'].'</td>
    <td>'.$row['User_Id'].'</td>
    
    <td>'.$row['create_id'].'</td>
    <td><b>Signature.................</b></td>   
    
    
    </tr>';

  
      
  }



  ?>
  
  
  </table>
  <p>
<p> 
  <table>
      <tr align="center">
    <td></td><td></td><td></td><td>Approved By:..........................................</td>
    <td>Designation:..........................................</td><td></td>
    <td>Signature:..........................................</td>

  </tr>
  </table>

<?php

    








    exit();
}
?>



<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<HTML>
    <HEAD>
        <TITLE><?php echo $LDReportingModule; ?></TITLE>
        <meta name="Description" content="Hospital and Healthcare Integrated Information System - CARE2x">
        <meta name="Author" content="Robert Meggle">
        <meta name="Generator" content="various: Quanta, AceHTML 4 Freeware, NuSphere, PHP Coder">
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

        <script language="javascript" >
<!-- 
            function gethelp(x, s, x1, x2, x3, x4)
            {
                if (!x)
                    x = "";
                urlholder = "../../main/help-router.php?sid=<?php echo sid; ?>&lang=$lang&helpidx=" + x + "&src=" + s + "&x1=" + x1 + "&x2=" + x2 + "&x3=" + x3 + "&x4=" + x4;
                helpwin = window.open(urlholder, "helpwin", "width=790,height=540,menubar=no,resizable=yes,scrollbars=yes");
                window.helpwin.moveTo(0, 0);
            }

            function printOut(date_from,date_to,insurance,ward_dept)
            {
                var date_from = $("#dfrom").val();
                var date_to = $("#dto").val();
                var insurance = $("#insuranceID").val();
                var ward_dept = $("#ward_dept").val();
                var displayDFrom = $("#displayDFrom").val();
                var displayTo = $("#displayTo").val();
                var displayfundName = $("#displayfundName").val();
                var displayward_dept_name = $("#displayward_dept_name").val();

                urlholder = "./zero_consultations.php?printout=TRUE&date_from="+date_from+"&date_to="+date_to+"&insurance="+insurance+"&ward_dept="+ward_dept+"&displayDFrom="+displayDFrom+"&displayTo="+displayTo+"&displayfundName="+displayfundName+"&displayward_dept_name="+displayward_dept_name;
                testprintout = window.open(urlholder, "printout", "width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
                window.testprintout.moveTo(0, 0);
            }



//-->
<?php require($root_path . 'include/inc_checkdate_lang.php'); ?>

        </script> 

        <script language="javascript" src="<?php echo $root_path; ?>js/setdatetime.js"></script>
        <script language="javascript" src="<?php echo $root_path; ?>js/checkdate.js"></script>
        <script language="javascript" src="<?php echo $root_path; ?>js/dtpick_care2x.js"></script>
        <script src="<?php print $root_path; ?>/include/_jquery.js" language="javascript"></script> 

        <link rel="stylesheet" href="../../css/themes/default/default.css" type="text/css">
        <script language="javascript" src="../../js/hilitebu.js"></script>

        <STYLE TYPE="text/css">
            A:link  {color: #000066;}
            A:hover {color: #cc0033;}
            A:active {color: #cc0000;}
            A:visited {color: #000066;}
            A:visited:active {color: #cc0000;}
            A:visited:hover {color: #cc0033;}

            .report{
                font-size: 10px;
                border-collapse:collapse;
            }


        </style>
        <script language="JavaScript">
            <!--
        function popPic(pid, nm) {

                if (pid != "")
                    regpicwindow = window.open("../../main/pop_reg_pic.php?sid=<?php echo sid; ?>&lang=$lang&pid=" + pid + "&nm=" + nm, "regpicwin", "toolbar=no,scrollbars,width=180,height=250");

            }
            // -->
        </script>

        <script language="JavaScript">
            function popdepts() {
                var x = document.getElementById("admission_id").value;
                if (x == 1) {
                    document.getElementById("dept").innerHTML =<?php echo json_encode($TP_SELECT_BLOCK_IN); ?>

                } else if (x == 2) {
                    document.getElementById("dept").innerHTML =<?php echo json_encode($TP_SELECT_BLOCK); ?>
                } else if (x == "all_opd_ipd") {

                    document.getElementById("dept").innerHTML = "all_opd_ipd";
                }
            }
        </script>    
        <script language="JavaScript">
//var u = true;
            // function validate() {
            //     if (document.getElementById('date_from').value == '') {
            //         alert('Date from is needed');
            //         document.getElementById('date_from').focus();
            //         u = false;
            //     } else if (document.getElementById('date_to').value == "") {
            //         alert('Date to is needed');
            //         document.getElementById('date_to').focus();
            //         u = false;
            //     } else {
            //         return true;
            //     }

            // }

        </script>


   <style>
#admissions {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#header1 {
  border: 1px solid #dddddd;
  text-align: center;
  padding: 8px;
  background-color: lightyellow;




}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>     




    </HEAD>

    <BODY bgcolor=#ffffff link=#000066 alink=#cc0000 vlink=#000066  >

        <!-- START HEAD OF HTML CONTENT -->

        <table width=100% border=0 cellspacing=0>
            <tbody class="main">

                <tr>
                    <td  valign="top" align="middle" height="35">
                        <table cellspacing="0"  class="titlebar" border=0>
                            <tr valign=top  class="titlebar" >
                                <td width="202" bgcolor="#99ccff" >                                
                                    &nbsp;&nbsp;<font color="#330066"><?php
                                    $LDServicesTotal=(isset($LDServicesTotal) ? $LDServicesTotal : null);
                                     echo $LDServicesTotal; ?></font></td>
                                <td width="408" align=right bgcolor="#99ccff">
                                    <a href="javascript: history.back();"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)" ></a>
                                    <a href="javascript:gethelp('reporting_overview.php','Reporting :: Overview')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>
                                    <a href="<?php echo $root_path; ?>modules/reporting_tz/reporting_main_menu.php" ><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>  
                                </td>
                            </tr>
                        </table>
 <?php

  require_once($root_path . 'main_theme/reportingNav.inc.php');

  

 ?>


                        <form name="form1" method="post" action="">        

                            <?php require_once($root_path . $top_dir . 'include/inc_gui_timeframe_cash_credit.php'); ?>
                            <?php
                            if (isset($_POST['show']) || empty($_POST['show'])) {

                                if (empty($_POST['show'])) {
                                   //Default date is one month
                                $toDay = date('Y-m-d');
                                $startDate = date("Y-m-01 00:00:00", strtotime($toDay));
                                $endDate = date("Y-m-t 23:59:59", strtotime($toDay));
                                $insuranceID=""; 
                                $ward_dept="";

                                                    
                                

                                 

                                    

                                }else{
                                     $date_from = $_POST['date_from'];
                                     $date_to = $_POST['date_to'];
                                     $dateArrFrom=explode("/", $date_from);
                                     $dateArrTo=explode("/", $date_to);

                                         
                                    

                                     $dateFromSql=$dateArrFrom[2].'-'.$dateArrFrom[1].'-'.$dateArrFrom[0];
                                     $dateToSql=$dateArrTo[2].'-'.$dateArrTo[1].'-'.$dateArrTo[0];

                                     
                                     $startDate = $dateFromSql.' 00:00:00';
                                     $endDate = $dateToSql.' 23:59:59';

                                     

                                    

                                    //insurance
                                switch ($_POST['insurance']) {
                                    case '-2':
                                        $insuranceID="";
                                        $fundName='ALL INSURANCE';

                                        break;                                   
                                    
                                    default:
                                    $id=$_POST['insurance'];
                                    $insuranceID=" AND cp.insurance_ID=".$id;
                                    //insurance name
                                    $sqlInsuranceName="SELECT name FROM care_tz_company WHERE id=".$id;
                                    $resultFundName=$db->Execute($sqlInsuranceName);
                                    if ($resultFundName->RecordCount()) {
                                        $fundName=$resultFundName->FetchRow();
                                        $fundName=$fundName['name'];
                                    }else{
                                        $fundName='CASH';

                                    }




                                        
                                        break;
                                }


                                //print_r($_POST);

                               // inpatient and out patient
                                switch ($_POST['admission_id']) {
                                    case 'all_opd_ipd':
                                    $ward_dept="";
                                    $ward_dept_name='all_opd_ipd';
                                        
                                        break;

                                    case '2':
                                    if ($_POST['dept_nr']=='all_opd') {
                                        $ward_dept="AND ce.encounter_class_nr='2'";
                                        $ward_dept_name='all_opd';
                                    }else{
                                        $ward_dept="AND ce.current_dept_nr=".$_POST['dept_nr'];
                                        $sqlDeptName="SELECT name_formal FROM care_department WHERE nr=".$_POST['dept_nr'];
                                        $resultDeptName=$db->Execute($sqlDeptName);
                                        $row_ward_dept_name=$resultDeptName->FetchRow();

                                        $ward_dept_name=$row_ward_dept_name['name_formal'];

                                    }

                                        break; 

                                    
                                    case '1':

                                    if ($_POST['current_ward_nr']=='all_ipd') {
                                        $ward_dept="AND ce.encounter_class_nr='1'";
                                        $ward_dept_name='all_ipd';
                                    }else{
                                        $ward_dept="AND ce.current_ward_nr=".$_POST['current_ward_nr'];

                                        $sqlWardName="SELECT name FROM care_ward WHERE nr=".$_POST['current_ward_nr'];

                                        $resultWardName=$db->Execute($sqlWardName);

                                        $row_ward_name=$resultWardName->FetchRow();

                                        $ward_dept_name=$row_ward_name['name'];



                                    }
                                        
                                        break;       
                                    
                                    default:
                                        # code...
                                        break;
                                }


                            }

                                    // echo $startDate.'<br>'; 
                                    // echo $endDate.'<br>';
                                    // echo $insuranceID.'<br>';
                                    // echo $ward_dept;


                           

                            

    $styles = array('even','odd');
    $sqlReview="SELECT  cp.date_reg, 
                       cp.selian_pid as fileNr,
                       cp.name_first,
                       cp.name_last,
                       ce.encounter_date as visit_date,
                       care_encounter_notes.notes,
                       care_encounter_notes.create_id, 
                       care_encounter_notes.create_time, 
                       care_tz_drugsandservices.item_number, 
                       care_tz_billing_archive_elem.description,
                       care_tz_billing_archive_elem.User_Id,
                       care_tz_billing_archive_elem.amount*care_tz_billing_archive_elem.price as amt
                  FROM care_person cp 
                  INNER JOIN care_encounter ce ON ce.pid= cp.pid 
                  INNER JOIN care_tz_billing_archive ON care_tz_billing_archive.encounter_nr=ce.encounter_nr 
                  INNER JOIN  care_tz_billing_archive_elem ON care_tz_billing_archive_elem.nr = care_tz_billing_archive.nr INNER JOIN care_tz_drugsandservices ON care_tz_drugsandservices.item_id=care_tz_billing_archive_elem.item_number 
                  LEFT JOIN care_encounter_notes ON care_encounter_notes.encounter_nr=ce.encounter_nr  INNER JOIN care_users ON care_users.name = care_encounter_notes.create_id


                  WHERE care_users.nhif_qualification_id IN('1','2','3','4','5','6') AND care_tz_drugsandservices.item_number LIKE 'CONS%' AND care_tz_billing_archive_elem.price<1 AND FROM_UNIXTIME(care_tz_billing_archive_elem.date_change) BETWEEN  '$startDate' AND '$endDate' $insuranceID $ward_dept  GROUP BY fileNr,ce.encounter_nr ORDER BY ce.encounter_nr ";



          $resultReview=$db->Execute($sqlReview);
       
                      

        

         ?>         
<h1>
    <img src="../../gui/img/common/default/<?php echo $hospital_logo;?>" alt="" />
</h1>



<?php
$sqlAddress="SELECT value FROM care_config_global WHERE type='main_info_address'";
$result=$db->Execute($sqlAddress);
if ($result->RecordCount()) {
    $address=$result->FetchRow();

    echo '<b>'.$address['value'].'</b>';
}

?>
<p>
    
</p>
<input type="hidden" id="dfrom" name="dfrom" value="<?php echo $startDate;?>">
<input type="hidden" id="dto" name="dto" value="<?php echo $endDate;?>">
<input type="hidden" id="insuranceID" name="insuranceID" value="<?php echo $insuranceID;?>">
<input type="hidden" id="ward_dept" name="ward_dept" value="<?php echo $ward_dept;?>">
<input type="hidden" id="displayDFrom" name="displayDFrom" value="<?php echo $startDate;?>">
<input type="hidden" id="displayTo" name="displayTo" value="<?php echo $endDate;?>">
<input type="hidden" id="displayfundName" name="displayfundName" value="<?php echo $fundName;?>">
<input type="hidden" id="displayward_dept_name" name="displayward_dept_name" value="<?php echo $ward_dept_name;?>">


<table id="admissions">
   
   
    <a href="javascript:printOut()"><img border=0 src=<?php echo $root_path; ?>/gui/img/common/default/printer.gif></a>

    
    <tr>
  <th id="header1" colspan="9" ><?php echo  date('d.m.Y',strtotime($startDate)).' To '.date('d.m.Y',strtotime($endDate)).';  ' .$fundName.'; '.$ward_dept_name.'; ';  ?></th>
  </tr>
  </tr>
  <tr id="header2">
    <th>Registration Date</th><th>Visit Date</th><th>Patient Names</th><th>File Number</th><th>Description</th><th>Amt</th><th>CashierName</th><th>Doctor</th><th>Dr.Signature</th>
      
  </tr>
  <?php
  while ($row=$resultReview->FetchRow()) {
    echo '<tr>
    <td>'.date('d.m.Y',strtotime($row['date_reg'])).'</td>
    <td>'.date('d.m.Y',strtotime($row['visit_date'])).'</td>
    <td>'.$row['name_first'].' '.$row['name_last'].'</td>
    <td>'.$row['fileNr'].'</td>
    <td>'.$row['description'].'</td>
    <td>'.$row['amt'].'</td>
    <td>'.$row['User_Id'].'</td>
    
    <td>'.$row['create_id'].'</td>
    <td><b>Signature.................</b></td>   
    
    
    </tr>';

  
      
  }



  ?>
<p>
  <tr>
    <td>Approved By:..........................................</td><td></td><td></td>
    <td>Designation:..........................................</td><td></td>
    <td>Signature:..........................................</td>

  </tr>

  
</table>


         

        
        <?php   










}

                                
                                
                                 
                               
                            
                            ?>
                                
                           
                        </form>
                       
                </TR>
            </TBODY>
        </TABLE>
    </body>
</html>









