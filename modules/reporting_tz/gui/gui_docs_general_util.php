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

.selection{
    font-size: 2px;
    border-color:red;

}

</STYLE>
</head>';
    ?>

    <?php
//        echo 'lklklkl';
//    echo $_GET['dept_nr'];
   // $rep_obj->Detailed_Revenue($_GET['start'], $_GET['end'], $_GET['company'], $_GET['in_out_patient'], $_GET['dept_nr'], $_GET['insurance'], 1);
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
            function printOut(admission_id, dept, insurance)
            {
                urlholder = "./DetailedRevenue.php?printout=TRUE&start=<?php echo $selected_date_from; ?>&end=<?php echo $selected_date_to; ?>&company=<?php echo $company; ?>&in_out_patient=" + admission_id + "&dept_nr=" + dept + "&insurance=" + insurance;
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
        <script type="text/javascript">
        function popcomp(){
            var compvalue=document.getElementById("comp").value;
            if(compvalue=="1"){
                document.getElementById("comp_show").innerHTML="";

            }else if(compvalue=="2"){
                document.getElementById("comp_show").innerHTML=<?php echo json_encode($insurance_obj->ShowAllInsurancesForQuotatuion()); ?>
            }
        }
            
        </script>    
        <script language="JavaScript">
            
            function validate() {
                var datefrom=document.getElementById("date_from").value;
                var dateto=document.getElementById("date_to").value;
                var healthfund=document.getElementById("insurance").value;
                var comp=document.getElementById("comp").value;

                
                if(datefrom==""){
                    alert("INCORRECT DATE");
                    return false;
                }


            }

        </script>
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
                                    &nbsp;&nbsp;<font color="#330066"><?php echo "General Monthly Report"; ?></font></td>
                                <td width="408" align=right bgcolor="#99ccff">
                                    <a href="javascript: history.back();"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)" ></a>
                                    <a href="javascript:gethelp('reporting_overview.php','Reporting :: Overview')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>
                                    <a href="<?php echo $root_path; ?>modules/reporting_tz/reporting_main_menu.php" ><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>  
                                </td>
                            </tr>
                        </table>

                              <?php
                              $msg=(isset($msg) ? $msg : null);
                              if($msg!=""){
                                ?>
                                <div style="color:red; font-size: 16px; font-weight: bold; "><?php echo $msg;?></div>
                             <?php
                              }
                              ?>
                        <form name="form1" method="post" action="" onSubmit="return validate();">


                           <table width="59%" border="0" align="center">
    <tr>
        <td><?php 
        $LDDateFrom=(isset($LDDateFrom) ? $LDDateFrom : null);
        $_POST['date_from']=(isset($_POST['date_from']) ? $_POST['date_from'] : null);
        echo $LDDateFrom; 
        ?><input name="date_from" id="date_from" type="text" size=10 maxlength=10 value="<?php echo $_POST['date_from'] ?>">
            <a href="javascript:show_calendar('form1.date_from','<?php echo $date_format ?>')">
                <img <?php echo createComIcon($root_path, 'show-calendar.gif', '0', 'absmiddle'); ?>></a>
            <?php 
            echo $LDDateTo;
            $_POST['date_to']=(isset($_POST['date_to']) ? $_POST['date_to'] : null); 
            ?>
            <input name="date_to" id="date_to" type="text" size=10 maxlength=10 value="<?php echo $_POST['date_to'] ?>" >
            <a href="javascript:show_calendar('form1.date_to','<?php echo $date_format ?>')">
                <img <?php echo createComIcon($root_path, 'show-calendar.gif', '0', 'absmiddle'); ?>></a>

            <font size=1>[<?php
            $dfbuffer = "LD_" . strtr($date_format, ".-/", "phs");
            echo $$dfbuffer;
            ?>]
        </td>
    </tr>
    <tr>
        <td>
            <label>Select Doctor</label>
            <select name="dr_name" id="doctor_name">
                <option value="">Select Doctor</option>
                <?php
                $dr_n = "SELECT DISTINCT `prescriber`FROM care_encounter_prescription";
                $dr_n_result = $db->Execute($dr_n);
                while ($dr_n_row = $dr_n_result ->FetchRow()) {
                 echo "<option value='" . $dr_n_row['prescriber'] ."'>" . $dr_n_row['prescriber'] ."</option>";


                }
                ?>
            </select>
           
            <input type="submit" name="show"  value="<?php echo $LDShow; ?>">
        </td>

    </tr>
</table>
                            <?php
                            //echo $MysqlDateFrom.$MysqlDateTo;
                            ?>
                            <table  border="1" cellspacing="3" cellpadding="3" bgcolor=#ffffdd>
                            <tr>
                              <td ><b><?php echo "Date"; ?></b></td>
                              <td ><b><?php echo "File Number"; ?></b></td>
                              <td ><b><?php echo "Patient Name"; ?></b></td>
                              <td ><b><?php echo "Insurance/Cash"; ?></b></td>
                              <td ><b><?php echo "Bill Number"; ?></b></td>
                              <td ><b><?php echo "Service Name"; ?></b></td>
                              <td ><b><?php echo "Price"; ?></b></td>

                            </tr>
                            <?php
                            if (isset($_POST['show'])) {           
                                if($error==FALSE){
                                     $general = "SELECT care_person.selian_pid AS file_nr,
 care_person.name_first,
 care_person.name_last,
 (CASE WHEN ISNULL(care_tz_company.name)=1 THEN 'CASH' ELSE care_tz_company.name END) AS ins_name,
 care_encounter_prescription.bill_number,
 care_encounter_prescription.bill_status,
 care_encounter_prescription.status,
 care_encounter_prescription.prescriber,care_encounter_prescription.is_disabled,care_encounter_prescription.disable_id,
 (CASE WHEN care_person.insurance_ID=0 THEN care_tz_drugsandservices.unit_price WHEN care_person.insurance_ID=12 THEN care_tz_drugsandservices.unit_price_3 WHEN care_person.insurance_ID=14 THEN care_tz_drugsandservices.unit_price_2 WHEN care_person.insurance_ID=21 THEN care_tz_drugsandservices.unit_price_4 ELSE care_tz_drugsandservices.unit_price_1 END) AS unit_price,
 care_encounter_prescription.article,care_encounter_prescription.prescriber, care_encounter_prescription.prescribe_date,care_encounter_prescription.encounter_nr as visit_nr,
 care_encounter_prescription.partcode,care_encounter_prescription.total_dosage as qty,
 care_tz_drugsandservices.purchasing_class 
 FROM care_person
 LEFT JOIN care_encounter ON care_person.pid=care_encounter.pid 
 LEFT JOIN care_encounter_prescription ON care_encounter_prescription.encounter_nr=care_encounter.encounter_nr 
 LEFT JOIN care_tz_drugsandservices ON care_tz_drugsandservices.item_id=care_encounter_prescription.article_item_number 
 LEFT JOIN care_tz_company ON care_tz_company.id=care_person.insurance_ID  WHERE care_encounter_prescription.bill_number>0  AND care_encounter_prescription.prescriber='$yakutuma' AND care_encounter_prescription.prescribe_date
 BETWEEN '$MysqlDateFrom' AND '$MysqlDateTo' AND care_tz_drugsandservices.purchasing_class IN ('dental','Eye-glass','minor_proc_op','obgyne_op','ortho_op','surgical_op','service','xray')";

               $general_result = $db->Execute($general);
                while ($general_rows = $general_result->FetchRow()) {
                        $file_nr = $general_rows['file_nr'];
                        $name = $general_rows['name_first'].' '.$general_rows['name_last'];
                        $insurance = $general_rows['ins_name'];
                        $bill_number = $general_rows['bill_number'];
                        $article = $general_rows['article'];
                        $date = $general_rows['prescribe_date'];
                        $price = $general_rows['unit_price'];
                
                            echo "
                            <tr>
                             <td > $date </td>
                              <td > $file_nr </td>
                              <td > $name </td>
                              <td > $insurance </td>
                              <td > $bill_number</td>
                              <td > $article </td>
                              <td > $price </td>
                            </tr>";                
                          } 

                          echo " <tr>
                            <th colspan='7'>Summary</th>"; 

  $instotal = "SELECT  (CASE WHEN ISNULL(care_tz_company.name)=1 THEN 'CASH' ELSE care_tz_company.name END) AS ins_name,count(care_person.insurance_ID) as instotal,
 care_encounter_prescription.bill_number,
 care_encounter_prescription.prescriber,care_encounter_prescription.is_disabled,care_encounter_prescription.disable_id,
 (CASE WHEN care_person.insurance_ID=0 THEN care_tz_drugsandservices.unit_price WHEN care_person.insurance_ID=12 THEN care_tz_drugsandservices.unit_price_3 WHEN care_person.insurance_ID=14 THEN care_tz_drugsandservices.unit_price_2 WHEN care_person.insurance_ID=21 THEN care_tz_drugsandservices.unit_price_4 ELSE care_tz_drugsandservices.unit_price_1 END) AS unit_price,
 care_encounter_prescription.article,care_encounter_prescription.prescriber, care_encounter_prescription.prescribe_date,care_encounter_prescription.encounter_nr as visit_nr,
 care_encounter_prescription.partcode,care_encounter_prescription.total_dosage as qty,
 care_tz_drugsandservices.purchasing_class 
 FROM care_person
 LEFT JOIN care_encounter ON care_person.pid=care_encounter.pid 
 LEFT JOIN care_encounter_prescription ON care_encounter_prescription.encounter_nr=care_encounter.encounter_nr 
 LEFT JOIN care_tz_drugsandservices ON care_tz_drugsandservices.item_id=care_encounter_prescription.article_item_number 
 LEFT JOIN care_tz_company ON care_tz_company.id=care_person.insurance_ID  WHERE care_encounter_prescription.bill_number>0  AND care_encounter_prescription.prescriber='$yakutuma' AND care_encounter_prescription.prescribe_date
 BETWEEN '$MysqlDateFrom' AND '$MysqlDateTo' AND care_tz_drugsandservices.purchasing_class IN ('dental','Eye-glass','minor_proc_op','obgyne_op','ortho_op','surgical_op','service','xray') GROUP BY care_person.insurance_ID";

               $instotal_result = $db->Execute($instotal);
                while ($instotal_rows = $instotal_result->FetchRow()) {
                        $ins_name = $instotal_rows['ins_name'];
                        $ins_total = $instotal_rows['instotal'];
                                     
                            echo "
                            <tr>
                            <td></td>
                             <td colspan='4'> $ins_name </td>
                              <td colspan='4'> $ins_total </td>
                              </tr>";                
                          } 
                                
                                
                          echo "  </tr>
                        </TABLE>";



             
                            }
                                                    }
                            ?>
                            <!-- &nbsp;&nbsp;
                             <a href="./gui/downloads/detailed_revenue.csv"><img border=0 src=<?php //echo $root_path;                 ?>/gui/img/common/default/savedisk.gif></a>-->
                        </form>
                       
                </TR>
            </TBODY>
        </TABLE>
    </body>
</html>









