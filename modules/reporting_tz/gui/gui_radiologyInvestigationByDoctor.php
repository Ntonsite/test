<!DOCTYPE html>
<html>
<head>
	<title>Radiology Investigation By doctor</title>
 
 <script language="javascript" src="<?php echo $root_path; ?>js/setdatetime.js"></script>
        <script language="javascript" src="<?php echo $root_path; ?>js/checkdate.js"></script>
        <script language="javascript" src="<?php echo $root_path; ?>js/dtpick_care2x.js"></script>
        <script src="<?php print $root_path; ?>/include/_jquery.js" language="javascript"></script> 

        <link rel="stylesheet" href="../../css/themes/default/default.css" type="text/css">
        <script language="javascript" src="../../js/hilitebu.js"></script>

        <style TYPE="text/css">
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
             function patientsList(doctor) {
                //alert(doctor);
                urlholder = "./docs_rad_report_patients.php?doctor=" + doctor + "&start=<?php echo $dateFromSql; ?>&end=<?php echo $dateToSql; ?>&currentDeptWard=<?php echo $currentDeptWard;?>&insurance_ID=<?php echo $insurance;?>";
                patientswin = window.open(urlholder, "patientslist", "width=1020,height=600,menubar=yes,resizable=yes,scrollbars=yes");
                window.patientswin.moveTo(0, 0);
            }
        </script>	

</head>
<body>

	<table width=100% border=0 cellspacing=0>
            <tbody class="main">
	<table cellspacing="0"  class="titlebar" border=0>
                            <tr valign=top  class="titlebar" >
                                <td width="202" bgcolor="#99ccff" >
                                    &nbsp;&nbsp;<font color="#330066">Radiology Investigation</font></td>
                                <td width="408" align=right bgcolor="#99ccff">
                                    <a href="javascript: history.back();"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)" ></a>
                                    <a href="javascript:gethelp('reporting_overview.php','Reporting :: Overview')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>
                                    <a href="<?php echo $root_path; ?>modules/reporting_tz/reporting_main_menu.php" ><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>  
                                </td>
                            </tr>
                        </table>

                    </tbody>
                </table>
	 
	 <form method="POST" action="">
	 	<?php require_once($root_path . $top_dir . 'include/inc_gui_timeframe_cash_credit_rad.php');?>

        <div align="center">
                                <h2>Radiologist Report<?php echo ' From: ' . $_POST['date_from'] . ' ' . '00:00:00 ' . 'To: ' . @formatDate2Local($_POST['date_from'], "dd/mm/yyyy") . ' ' . '23:59:59'; ?></h1>
                                    <p><?php echo $LDCreationTime; ?><?php
                                        echo date("F j, Y, g:i a");
                                        ?></p>
                            </div>

                             <table border="1" cellspacing="0" cellpadding="0" align="center" bgcolor=#ffffdd>
                                    <tr> 

                                         <?php
             echo '<td align="center"><b>' . 'Date' . '</b></td>';
             echo '<td align="center"><b>Radiologist</b></td>';
             echo '<td align="center"><b>' . 'Patients' . '</b></td>'; 
                                    
            ?>
                                       
                                    </tr>


                            <?php
                            // echo 'date from '. $dateFromSql.'<br>';
                            // echo 'date to '. $dateToSql.'<br>';
                            // echo 'department/ward '.$currentDeptWard;
                            // echo 'insurance id= '.$insurance;
                            $serial_start=1;

                             $sql_list_drs="";
                                $sql_list_drs="SELECT fr.create_id FROM care_test_findings_radio fr INNER JOIN care_encounter ce ON ce.encounter_nr=fr.encounter_nr INNER JOIN care_person cp ON cp.pid=ce.pid INNER JOIN care_test_request_radio rr ON rr.batch_nr=fr.batch_nr WHERE fr.findings_date  BETWEEN  '".$dateFromSql."' AND '".$dateToSql."' $currentDeptWard $insurance  GROUP BY fr.create_id ";

                               $result_list_drs=$db->Execute($sql_list_drs);


                               $total_all=0;
                                 $row_list_drs='';
                                 $sql_count_patients='';
                                while ($row_list_drs=$result_list_drs->FetchRow()) {
                                    $sql_count_patients='';
                                    $sql_count_patients="SELECT cp.name_first,cp.name_last,cp.insurance_ID, fr.batch_nr,fr.findings,ds.item_description,fr.create_id,fr.findings_date as tarehe, count(DISTINCT(fr.encounter_nr)) total_p FROM care_test_findings_radio fr INNER JOIN care_encounter ce ON ce.encounter_nr=fr.encounter_nr INNER JOIN care_person cp ON cp.pid=ce.pid INNER JOIN care_test_request_radio rr ON rr.batch_nr=fr.batch_nr INNER JOIN care_tz_drugsandservices ds ON ds.item_id=rr.test_request WHERE fr.create_id='".$row_list_drs['create_id']."' AND fr.findings_date BETWEEN '".$dateFromSql."' AND '".$dateToSql."' $currentDeptWard $insurance GROUP BY fr.findings_date ORDER BY fr.create_id,fr.findings_date";

                                    $sql_result_count_patients=$db->Execute($sql_count_patients);


                                    $total=0;


                                    while ($row_drs=$sql_result_count_patients->fetchRow()) {
                                        
                                        echo '<tr><td>'.date('d/m/Y',strtotime($row_drs['tarehe'])).'</td><td>'.$row_drs['create_id'].'</td><td>'.$row_drs['total_p'].'</td></tr>';

                                        $total+=$row_drs['total_p'];
                                        $total_all+=$row_drs['total_p'];
                                    }


                                     echo "<tr bgcolor='lightgrey'><td colspan='2' ><b>TOTAL:</b></td><td><b><a href=\"javascript:patientsList('".$row_list_drs['create_id']."');\"'>".$total."</a></b></td></tr>";


                                }


                           
                            ?>
                        </table>
	 </form>
                   


</body>
</html>