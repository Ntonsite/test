<?php
if ($printout) {
    echo '<head>
<script language="javascript"> this.window.print(); </script>
<title>' . $LDDocsUtilReport . '</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>';
    echo '<html><body>';
    ?>
    <DIV align="center">
        <h2><?php echo $LDDocsUtilReport; ?><?php echo ' as From: ' . @formatDate2Local($startdate, "dd/mm/yyyy"). ' To: ' . @formatDate2Local($enddate, "dd/mm/yyyy"); ?></h1>
            <p><?php echo $LDCreationTime; ?><?php
                echo date("F j, Y, g:i a");
                ?></p>
    </DIV>
    <table border="1" cellspacing="0" cellpadding="0" align="center" bgcolor=#ffffdd>
        <tr> 
            <?php
             echo '<td align="center"><b>' . 'Date' . '</b></td>';
             echo '<td align="center"><b>' . $LDDoctor . '</b></td>';
            echo '<td align="center"><b>' . 'Patients' . '</b></td>'; 
                                    
            ?>
        </tr>
        <?php
         $sql_list_drs="";
        $sql_list_drs="SELECT cen.personell_name FROM care_encounter_notes cen INNER JOIN care_encounter ce ON ce.encounter_nr=cen.encounter_nr INNER JOIN care_person cp ON cp.pid=ce.pid INNER JOIN care_tz_diagnosis dx ON dx.encounter_nr=ce.encounter_nr WHERE dx.doctor_name=cen.personell_name AND dx.diagnosis_type='final' AND date  BETWEEN  '".$startdate."' AND '".$enddate."'  AND FROM_UNIXTIME(dx.timestamp,'%Y-%m-%d')=cen.date GROUP BY personell_name ";
            $result_list_drs=$db->Execute($sql_list_drs);



              $total_all=0;
                                 $row_list_drs='';
                                 $sql_count_patients='';
                                while ($row_list_drs=$result_list_drs->FetchRow()) {
                                    $sql_count_patients='';
                                    $sql_count_patients="SELECT cp.name_first,cp.name_last,cp.insurance_ID, cen.nr,cen.notes,dx.ICD_10_description,dx.ICD_10_code,dx.doctor_name, cen.personell_name,cen.date as tarehe, count(DISTINCT(cen.encounter_nr)) total_p FROM care_encounter_notes cen INNER JOIN care_encounter ce ON ce.encounter_nr=cen.encounter_nr INNER JOIN care_person cp ON cp.pid=ce.pid INNER JOIN care_tz_diagnosis dx ON dx.encounter_nr=ce.encounter_nr WHERE cen.personell_name='".$row_list_drs['personell_name']."' AND dx.doctor_name=cen.personell_name AND dx.diagnosis_type='final' AND date BETWEEN '".$startdate."' AND '".$enddate."' AND FROM_UNIXTIME(dx.timestamp,'%Y-%m-%d')=cen.date GROUP BY date ORDER BY personell_name,date";                        

                                    $sql_result_count_patients=$db->Execute($sql_count_patients);



                                    $total=0;

                                    
                                    //COUNT EACH DOCTOR
                                    while ($row_drs=$sql_result_count_patients->fetchRow()) {
                                        
                                        echo '<tr><td>'.$row_drs['tarehe'].'</td><td>'.$row_drs['personell_name'].'</td><td>'.$row_drs['total_p'].'</td></tr>';

                                        $total+=$row_drs['total_p'];
                                        $total_all+=$row_drs['total_p'];
                                    }
                                    echo "<tr bgcolor='lightgrey'><td colspan='2' ><b>TOTAL:</b></td><td><b><a href=\"javascript:patientsList('".$row_list_drs['personell_name']."');\"'>".$total."</a></b></td></tr>";

                                                                    
                                }

        ?>
         <tr> 
                                    <td bgcolor="#ffffaa" colspan="2"><b><?php echo'Grand ' . $LDtotal; ?></td>
                                    <?php

                                    echo '<td align="center"><b>'.number_format($total_all).'</b></td>';

                                    

                                    
                                    ?>


                                </tr>  
    </table>
    <?php
    exit();
}
//end print
?>



<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<HTML>
    <HEAD>
        <TITLE><?php echo $LDReportingModule; ?></TITLE>
        <meta name="Description" content="Hospital and Healthcare Integrated Information System - CARE2x">
        <meta name="Author" content="Israel Pascal">
        <meta name="Generator" content="various: Quanta, AceHTML 4 Freeware, NuSphere, PHP Coder">
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

        <script language="javascript" >

            function gethelp(x, s, x1, x2, x3, x4)
            {
                if (!x)
                    x = "";
                urlholder = "../../main/help-router.php?sid=<?php echo sid; ?>&lang=$lang&helpidx=" + x + "&src=" + s + "&x1=" + x1 + "&x2=" + x2 + "&x3=" + x3 + "&x4=" + x4;
                helpwin = window.open(urlholder, "helpwin", "width=790,height=540,menubar=no,resizable=yes,scrollbars=yes");
                window.helpwin.moveTo(0, 0);
            }
            function printOut()
            {
                urlholder = "./docs_presc_report.php?printout=TRUE&start=<?php echo $startdate; ?>&end=<?php echo $enddate; ?>&amount_per_person=<?php echo $amount_per_person; ?>";
                testprintout = window.open(urlholder, "printout", "width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
                window.testprintout.moveTo(0, 0);
            }
            function patientsList(doctor) {
                //alert(doctor);
                urlholder = "./docs_presc_report_patients.php?doctor=" + doctor + "&start=<?php echo $startdate; ?>&end=<?php echo $enddate; ?>";
                patientswin = window.open(urlholder, "patientslist", "width=1020,height=600,menubar=yes,resizable=yes,scrollbars=yes");
                window.patientswin.moveTo(0, 0);
            }
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
        </style>
        <script language="JavaScript">

            function popPic(pid, nm) {

                if (pid != "")
                    regpicwindow = window.open("../../main/pop_reg_pic.php?sid=<?php echo sid; ?>&lang=$lang&pid=" + pid + "&nm=" + nm, "regpicwin", "toolbar=no,scrollbars,width=180,height=250");

            }


        </script> 
    </HEAD>

    <BODY bgcolor=#ffffff link=#000066 alink=#cc0000 vlink=#000066  >

        <!-- START HEAD OF HTML CONTENT -->


        <table width=100% border=0 cellspacing=0 height=100%>
            <tbody class="main">

                <tr>

                    <td  valign="top" align="middle" height="35">
                        <table cellspacing="0"  class="titlebar" border=0>
                            <tr valign=top  class="titlebar" >
                                <td width="402" bgcolor="#99ccff" > &nbsp;&nbsp;<font color="#330066"><?php echo $LDDocsUtilReport; ?></font></td>
                                <td width="408" align=right bgcolor="#99ccff">
                                    <a href="javascript: history.back();"><img src="../../gui/img/control/blue_aqua/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)" ></a>
                                    <a href="javascript:gethelp('reporting_overview.php','Reporting :: Overview')"><img src="../../gui/img/control/blue_aqua/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>
                                    <a href="<?php echo $root_path; ?>modules/reporting_tz/reporting_main_menu.php" ><img src="../../gui/img/control/blue_aqua/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>  
                                </td>
                            </tr>
                        </table>	
 <?php require_once($root_path . 'main_theme/reportingNav.inc.php'); ?>

                        <!-- END HEAD OF HTML CONTENT -->

                        <form name="form1" method="post" action=""></p>
                            <?php require_once($root_path . $top_dir . 'include/inc_gui_timeframe_date_docs_util.php'); ?>
                            <p><br>

                                <br>
                            </p>


                            <div align="center">
                                <h2><?php echo $LDDocsUtilReport; ?><?php echo 'From: ' . $_POST['date_from'] . ' ' . '00:00:00 ' . 'To: ' . @formatDate2Local($enddate, "dd/mm/yyyy") . ' ' . '23:59:59'; ?></h1>
                                    <p><?php echo $LDCreationTime; ?><?php
                                        echo date("F j, Y, g:i a");
                                        ?></p>
                            </div>
                            <table border="1" cellspacing="0" cellpadding="2" align="center" bgcolor=#ffffdd>
                                <tr> 
                                    <?php
                                    

                                    echo '<td align="center"><b>' . 'Date' . '</b></td>';
                                    echo '<td align="center"><b>' . $LDDoctor . '</b></td>';
                                    echo '<td align="center"><b>' . 'Patients' . '</b></td>'; 
                                    

                                    
                                    ?>
                                </tr>
                                <?php
                                //echo $tabler;
                                $serial_start=1;
                                ?>
                            
                                <?php
                                


                                  


                               // while ($row_doctor=$count_patients_result->FetchRow()) {
                                   // $serial_start+1;

                                
                                $sql_list_drs="";
                                $sql_list_drs="SELECT cen.personell_name FROM care_encounter_notes cen INNER JOIN care_encounter ce ON ce.encounter_nr=cen.encounter_nr INNER JOIN care_person cp ON cp.pid=ce.pid INNER JOIN care_tz_diagnosis dx ON dx.encounter_nr=ce.encounter_nr WHERE dx.doctor_name=cen.personell_name AND dx.diagnosis_type='final' AND date  BETWEEN  '".$startdate."' AND '".$enddate."'  AND FROM_UNIXTIME(dx.timestamp,'%Y-%m-%d')=cen.date GROUP BY personell_name ";






                                $result_list_drs=$db->Execute($sql_list_drs);
                                


                                 $total_all=0;
                                 $row_list_drs='';
                                 $sql_count_patients='';
                                while ($row_list_drs=$result_list_drs->FetchRow()) {
                                    $sql_count_patients='';
                                    $sql_count_patients="SELECT cp.name_first,cp.name_last,cp.insurance_ID, cen.nr,cen.notes,dx.ICD_10_description,dx.ICD_10_code,dx.doctor_name, cen.personell_name,cen.date as tarehe, count(DISTINCT(cen.encounter_nr)) total_p FROM care_encounter_notes cen INNER JOIN care_encounter ce ON ce.encounter_nr=cen.encounter_nr INNER JOIN care_person cp ON cp.pid=ce.pid INNER JOIN care_tz_diagnosis dx ON dx.encounter_nr=ce.encounter_nr WHERE cen.personell_name='".$row_list_drs['personell_name']."' AND dx.doctor_name=cen.personell_name AND dx.diagnosis_type='final' AND date BETWEEN '".$startdate."' AND '".$enddate."' AND FROM_UNIXTIME(dx.timestamp,'%Y-%m-%d')=cen.date GROUP BY date ORDER BY personell_name,date";                        

                                    $sql_result_count_patients=$db->Execute($sql_count_patients);



                                    $total=0;

                                    
                                    //COUNT EACH DOCTOR
                                    while ($row_drs=$sql_result_count_patients->fetchRow()) {
                                        
                                        echo '<tr><td>'.date('d/m/Y',strtotime($row_drs['tarehe'])).'</td><td>'.$row_drs['personell_name'].'</td><td>'.$row_drs['total_p'].'</td></tr>';

                                        $total+=$row_drs['total_p'];
                                        $total_all+=$row_drs['total_p'];
                                    }
                                    echo "<tr bgcolor='lightgrey'><td colspan='2' ><b>TOTAL:</b></td><td><b><a href=\"javascript:patientsList('".$row_list_drs['personell_name']."');\"'>".$total."</a></b></td></tr>";

                                                                    
                                }


                                   





                                    
                                    

                                                                                                      
                          //      }end while
                                
                                ?>
                                
                                <tr> 
                                    <td bgcolor="#ffffaa" colspan="2"><b><?php echo'Grand ' . $LDtotal; ?></td>
                                    <?php

                                    echo '<td align="center"><b>'.number_format($total_all).'</b></td>';                                    

                                    
                                    ?>


                                </tr>  
                            </table>
                            <p>&nbsp; </p>

                        </form>			  
                        <a href="javascript:printOut()"><img border=0 src=<?php echo $root_path; ?>/gui/img/common/default/billing_print_out.gif></a><br>									  
                        <br><br><br>  <br><br><br>						  


                        <!-- START BOTTIOM OF HTML CONTENT --->
                        <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#cfcfcf">
                            <tr>
                                <td align="center">
                                    <table width="100%" bgcolor="#ffffff" cellspacing=0 cellpadding=5>
                                        <tr>
                                            <td>
                                                <div class="copyright">
                                                    <script language="JavaScript">
                                                        <!-- Script Begin
                                                    function openCreditsWindow() {

                                                            urlholder = "../../language/$lang/$lang_credits.php?lang=$lang";
                                                            creditswin = window.open(urlholder, "creditswin", "width=500,height=600,menubar=no,resizable=yes,scrollbars=yes");

                                                        }
                                                        //  Script End -->
                                                    </script>


                                                    <a href="http://www.care2x.org" target=_new>CARE2X 3rd Generation pre-deployment 3.3</a> :: <a href="../../legal_gnu_gpl.htm" target=_new> License</a> ::
                                                    <a href=mailto:care2x@care2x.org>Contact</a>  :: <a href="../../language/en/en_privacy.htm" target="pp"> Our Privacy Policy </a> ::
                                                    <a href="../../docs/show_legal.php?lang=$lang" target="lgl"> Legal </a> ::
                                                    <a href="javascript:openCreditsWindow()"> Credits </a> ::.<br>

                                                </div>
                                            </td>
                                        <tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!-- START BOTTIOM OF HTML CONTENT --->

                        </BODY>