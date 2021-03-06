<?php
if ($printout) {
    echo '<head>';
    ?>
    <script language="javascript"> this.window.print();</script>
    <title>Radiologist Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <style type="text/css">
        .printout{
            font-size: 13px;
        }
    </style>
    </head>
    <html><body>

            <DIV align="center">

                <h2><?php echo $doctor . '\'s'; ?>&nbsp;Patients Attendance List<?php echo ' From: ' . @formatDate2Local($start, "dd/mm/yyyy") . ' To: ' . @formatDate2Local($end, "dd/mm/yyyy"); ?></h1>
                    <p><?php echo $LDCreationTime; ?><?php
                        echo date("F j, Y, g:i a");
                        ?></p>
            </DIV>
            <table class="printout" border="1" cellspacing="0" cellpadding="0" align="center" bgcolor=#ffffdd>

                 <tr> 
                                        <?php
                                        echo '<td><b>' . 'Serial No:' . '</td>';
                                        echo '<td><b>' . 'Visit Date' . '</td>';
                                        echo '<td><b>' . $LDPatientName . '</td>';
                                        echo '<td><b> Sex</b></td>';
                                        echo '<td><b> Health Fund</b></td>';
                                        echo '<td><b>' . 'FileNr' . '</td>';
                                        echo '<td><b>Findings</td>';
                                       
                                        ?>
                                    </tr>
                
                <?php
// $startdate=$_GET['start'];
// $enddate=$_GET['end'];
// $dr=$_GET['doctor'];
// $currentDeptWard=$_GET['currentDeptWard'];
// $insurance_ID=$_GET['insurance_ID']; 

                             

                                    
                                    
                                     // echo $doctor.'<br>';
                                     // echo $start.'<br>';
                                     // echo $end.'<br>';

                
                




                                     $sql_count_patients="SELECT  fr.encounter_nr,ce.encounter_date as visit_date,cp.selian_pid, cp.name_first,cp.name_last,cp.sex,cp.insurance_ID, fr.batch_nr,fr.findings,ds.item_description,fr.create_id,fr.findings_date as tarehe FROM care_test_findings_radio fr INNER JOIN care_encounter ce ON ce.encounter_nr=fr.encounter_nr INNER JOIN care_person cp ON cp.pid=ce.pid INNER JOIN care_test_request_radio rr ON rr.batch_nr=fr.batch_nr INNER JOIN care_tz_drugsandservices ds ON ds.item_id=rr.test_request WHERE fr.create_id='".$doctor."' AND fr.findings_date BETWEEN '".$start."' AND '".$end."' $currentDeptWard $insurance_ID  GROUP BY fr.encounter_nr ORDER BY fr.findings_date";


                                     $result_count_patient=$db->Execute($sql_count_patients);

                                     

                                     $sql_hf="";
                                     $serial=1;
                                     while ($rowp=$result_count_patient->FetchRow()) {

                                        //Health Fund                                        
                                        $sql_hf="SELECT name,id FROM care_tz_company WHERE id=".$rowp['insurance_ID'];


                                        $result_hf=$db->Execute($sql_hf);
                                        if ($hfname=$result_hf->FetchRow()) {
                                            $insurance=$hfname['name'];
                                            $CountHf[]=$insurance;
                                        }else{
                                            $insurance="CASH";
                                            $CountHf[]=$insurance;
                                        }



                                        echo '<tr><td>'.$serial.'</td><td>'.date('d/m/Y',strtotime($rowp['visit_date'])).'</td><td>'.$rowp['name_first'].' '.$rowp['name_last'].'</td><td>'.$rowp['sex'].'</td><td>'.$insurance.'</td><td>'.$rowp['selian_pid'].'</td><td>'.$rowp['item_description'].'('.$rowp['findings'].')'.'</td></tr>';

                                        $serial++;
                                         
                                     }


                                          $count_hf_values=array_count_values($CountHf);
                                        echo '<tr bgcolor="lightgrey"><td colspan="8">';

                                          foreach ($count_hf_values as $key => $value) {

                                            echo $key."=>"."<b>".$value."</b>"." ,";
                                            
                                              
                                          }

                                          echo '</td></tr>';


                ?>
               
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
            <meta name="Author" content="Mark Patrick">
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
                    urlholder = "./docs_rad_report_patients.php?doctor=<?php echo $doctor; ?>&printout=TRUE&start=<?php echo $startdate; ?>&end=<?php echo $enddate; ?>&currentDeptWard=<?php echo $currentDeptWard;?>&insurance_ID=<?php echo $insurance_ID;?>";
                    testprintout = window.open(urlholder, "printout", "width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
                    window.testprintout.moveTo(0, 0);
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

                function get_ICD_descr(code) {
                    urlholder = "<?php echo $root_path; ?>modules/reporting_tz/icd_code_descr.php?icd_code=" + code;
                    var Request = new XMLHttpRequest();

                    Request.open("GET", urlholder, false);
                    Request.send();

                    if (Request.status === 200) {
                        alert(Request.responseText);
                    } else {
                        alert("Error!");
                    }
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
                                    <td width="302" bgcolor="#99ccff" > &nbsp;&nbsp;<font color="#330066"><?php // echo $LDDocsUtilReport;                                                                                                                                                                                                                                ?></font></td>
                                    <td width="408" align=right bgcolor="#99ccff">
    <!--                                    <a href="javascript: history.back();"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)" ></a>
                                        <a href="javascript:gethelp('reporting_overview.php','Reporting :: Overview')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>
                                        <a href="<?php // echo $root_path;                                                                                                                                                                                                                                ?>modules/reporting_tz/reporting_main_menu.php" ><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>  -->
                                    </td>
                                </tr>
                            </table>	

                            <!-- END HEAD OF HTML CONTENT -->

                            <form name="form1" method="post" action=""></p>
                                <div align="center">
                                    <h2><?php echo $_GET['doctor']. '\'s'; ?>&nbsp;Patients Attendance List<?php echo ' From: ' . @formatDate2Local($startdate, "dd/mm/yyyy") . ' To: ' . @formatDate2Local($enddate, "dd/mm/yyyy"); ?></h1>
                                        <p><?php echo $LDCreationTime; ?><?php
                                            echo date("F j, Y, g:i a");
                                            ?></p>
                                </div>
                                <table border="1" cellspacing="0" cellpadding="2" align="center" bgcolor=#ffffdd>
                                    <tr> 
                                        <?php
                                        echo '<td><b>' . 'Serial No:' . '</td>';
                                        echo '<td><b>' . 'Visit Date' . '</td>';
                                        echo '<td><b>' . $LDPatientName . '</td>';
                                        echo '<td><b> Sex</b></td>';
                                        echo '<td><b> Health Fund</b></td>';
                                        echo '<td><b>' . 'FileNr' . '</td>';
                                        echo '<td><b>Findings</td>';
                                       
                                        ?>
                                    </tr>


                                    <?php



                                                                  

                                    
                                    
                                     // echo $doctor.'<br>';
                                     // echo $start.'<br>';
                                     // echo $end.'<br>';




                                     $sql_count_patients="SELECT  fr.encounter_nr,ce.encounter_date as visit_date,cp.selian_pid, cp.name_first,cp.name_last,cp.sex,cp.insurance_ID, fr.batch_nr,fr.findings,ds.item_description,fr.create_id,fr.findings_date as tarehe FROM care_test_findings_radio fr INNER JOIN care_encounter ce ON ce.encounter_nr=fr.encounter_nr INNER JOIN care_person cp ON cp.pid=ce.pid INNER JOIN care_test_request_radio rr ON rr.batch_nr=fr.batch_nr INNER JOIN care_tz_drugsandservices ds ON ds.item_id=rr.test_request WHERE fr.create_id='".$doctor."' AND fr.findings_date BETWEEN '".$startdate."' AND '".$enddate."' $currentDeptWard $insurance_ID  GROUP BY fr.encounter_nr ORDER BY fr.findings_date";



                                     $result_count_patient=$db->Execute($sql_count_patients);

                                     

                                     $sql_hf="";
                                     $serial=1;
                                     while ($rowp=$result_count_patient->FetchRow()) {

                                        //Health Fund                                        
                                        $sql_hf="SELECT name,id FROM care_tz_company WHERE id=".$rowp['insurance_ID'];


                                        $result_hf=$db->Execute($sql_hf);
                                        if ($hfname=$result_hf->FetchRow()) {
                                            $insurance=$hfname['name'];
                                            $CountHf[]=$insurance;
                                        }else{
                                            $insurance="CASH";
                                            $CountHf[]=$insurance;
                                        }



                                        echo '<tr><td>'.$serial.'</td><td>'.date('d/m/Y',strtotime($rowp['visit_date'])).'</td><td>'.$rowp['name_first'].' '.$rowp['name_last'].'</td><td>'.$rowp['sex'].'</td><td>'.$insurance.'</td><td>'.$rowp['selian_pid'].'</td><td>'.$rowp['item_description'].'('.$rowp['findings'].')'.'</td></tr>';

                                        $serial++;
                                         
                                     }


                                          $count_hf_values=array_count_values($CountHf);
                                        echo '<tr bgcolor="lightgrey"><td colspan="8">';

                                          foreach ($count_hf_values as $key => $value) {

                                            echo $key."=>"."<b>".$value."</b>"." ,";
                                            
                                              
                                          }

                                          echo '</td></tr>';


                     


                                    




                                            
                                   





                                    

                                    ?>

                                  
                                </table>
                                
                                <p>&nbsp; </p>

                            </form>			  
                            <a href="javascript:printOut()"><img border=0 src=<?php echo $root_path; ?>/gui/img/common/default/billing_print_out.gif></a><br>									  
                            <br><br><br>  <br><br><br>						  


                            
                            <!-- START BOTTIOM OF HTML CONTENT --->

                            </BODY>
