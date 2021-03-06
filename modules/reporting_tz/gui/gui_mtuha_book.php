<?php
// PRINTOUT - SECTION :: See below for common GUI
ini_set('max_execution_time', 0);

if ($PRINTOUT) {
    echo '<head>
    <script language="javascript"> this.window.print(); </script>
    <title>' . $LDMtuhaICD10Report . '</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    </head>';
    echo '<html><body>';
    ?>
    <div align="center">
        <h1><?php echo $LDPDDiagnosticReport; ?><?php echo date('F Y', $start); ?></h1>
        <p><?php echo $LDCreationTime; ?><?php echo date("F j, Y, g:i a"); ?></p>
    </div>
    <br><br>
    <form name="form1" method="post" action="">
        <br>
        <table width="750" style="margin-left: -200px;" border="1" cellspacing="0" cellpadding="0" align="left" bgcolor=#ffffdd>
            <tr>
                <td width="100" colspan="2" bgcolor="#ffffaa"></td>
                <td colspan="3" align="center" bgcolor="#ffffaa">&lt; 1 Month </td>
                <td colspan="3" align="center">1 month to &lt; 1yr </td>
                <td colspan="3" align="center" bgcolor="#ffffaa">1 yr to &lt; 5yrs </td>
                <td colspan="3" align="center" bgcolor="#ffffaa">5 yrs to &lt; 60yrs </td>
                <td colspan="3" align="center">60 yrs and above</td>
                <td rowspan="2" bgcolor="#ffffaa">Total Male</td>
                <td rowspan="2" bgcolor="#ffffaa">Total Female</td>
                <td rowspan="2" bgcolor="#ffffaa">Grand Total</td>
            </tr>
            <tr>
                <td width="50" bgcolor="#ffffaa">MTUHA Series</td>
                <td width="50" bgcolor="#ffffaa"><?php echo $LDDiagnosticFullName; ?></td>
                <td width="50" bgcolor="#ffffaa"><?php echo 'Male'; ?></td>
                <td width="50" bgcolor="#ffffaa"><?php echo 'Female'; ?></td>
                <td width="50" bgcolor="#ffffaa"><?php echo 'Total'; ?></td>
                <td width="50"><?php echo 'Male'; ?></td>
                <td width="50"><?php echo 'Female'; ?></td>
                <td width="50"><?php echo 'Total'; ?></td>
                <td width="50" bgcolor="#ffffaa"><?php echo 'Male'; ?></td>
                <td width="50" bgcolor="#ffffaa"><?php echo 'Female'; ?></td>
                <td width="50" bgcolor="#ffffaa"><?php echo 'Total'; ?></td>
                <td width="50"><?php echo 'Male'; ?></td>
                <td width="50"><?php echo 'Female'; ?></td>
                <td width="50"><?php echo 'Total' ?></td>
                <td width="50"><?php echo 'Male';?></td>
                <td width="50"><?php echo 'Female'; ?></td>
                <td width="50"><?php echo 'Total Male'; ?></td>
                <td width="50"><?php echo 'Total Female'; ?></td>
                <td width="50"><?php echo 'Grand Total'; ?></td>
            </tr>

            <?php

            $rep_obj->DisplayOPDMtuhaRows($start, $end, $_GET['admission_nr'], $_GET['hf'], $_GET['current_ward_nr'], $_GET['dept_nr']);
            ?>
        </table>

    </form>
    <?php
    exit();
}
?>

<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<HTML>

<HEAD>
    <TITLE>MTUHA Book</TITLE>
    <meta name="Description" content="Hospital and Healthcare Integrated Information System - CARE2x">
    <meta name="Author" content="Robert Meggle">
    <meta name="Generator" content="various: Quanta, AceHTML 4 Freeware, NuSphere, PHP Coder">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

    <?php 
    $start = strtotime(str_replace("/", "-", $_POST['date_from']));
    $end = strtotime(str_replace("/", "-", $_POST['date_to']));

     ?>

    <script language="javascript">
        <!-- 
        function gethelp(x, s, x1, x2, x3, x4) {
            if (!x)
                x = "";
            urlholder = "../../main/help-router.php?sid=<?php echo sid; ?>&lang=$lang&helpidx=" + x + "&src=" + s + "&x1=" + x1 + "&x2=" + x2 + "&x3=" + x3 + "&x4=" + x4;
            helpwin = window.open(urlholder, "helpwin", "width=790,height=540,menubar=no,resizable=yes,scrollbars=yes");
            window.helpwin.moveTo(0, 0);
        }

        function printOut() {
            urlholder = "./mtuha_book.php?printout=TRUE&start=<?php echo $start; ?>&end=<?php echo $end; ?>&admission_nr=<?php echo $_POST['admission_id']; ?>&hf=<?php echo $_POST['insurance']; ?>&current_ward_nr=<?php echo $_POST['current_ward_nr'] ?>&dept_nr=<?php echo $_POST['dept_nr']; ?>";
            testprintout = window.open(urlholder, "printout", "width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
            window.testprintout.moveTo(0, 0);
        }
        // 
        -->

    </script>
    <link rel="stylesheet" href="../../css/themes/default/default.css" type="text/css">
    <script language="javascript" src="../../js/hilitebu.js"></script>

    <STYLE TYPE="text/css">
        A:link {
            color: #000066;
        }

        A:hover {
            color: #cc0033;
        }

        A:active {
            color: #cc0000;
        }

        A:visited {
            color: #000066;
        }

        A:visited:active {
            color: #cc0000;
        }

        A:visited:hover {
            color: #cc0033;
        }
        .mr-sm {
            padding-right: 5px;
        }
        .table-row:hover {
            background: #c7d4dd !important;
        }
    </STYLE>
    <script language="JavaScript">
        <!--
        function popPic(pid, nm) {

            if (pid != "")
                regpicwindow = window.open("../../main/pop_reg_pic.php?sid=<?php echo sid; ?>&lang=$lang&pid=" + pid + "&nm=" + nm, "regpicwin", "toolbar=no,scrollbars,width=180,height=250");

        }
        // 
        -->
    </script>


    <script language="JavaScript">
        function popdepts() {
            var x = document.getElementById("admission_id").value;
            if (x == 1) {
                document.getElementById("dept").innerHTML = <?php echo json_encode($TP_SELECT_BLOCK_IN); ?>

            } else if (x == 2) {
                document.getElementById("dept").innerHTML = <?php echo json_encode($TP_SELECT_BLOCK); ?>
            } else if (x == "all_opd_ipd") {

                document.getElementById("dept").innerHTML = "all_opd_ipd";
            }
        }
    </script>

</HEAD>

<BODY bgcolor=#ffffff link=#000066 alink=#cc0000 vlink=#000066>

    <table width=100% border=0 cellspacing=0 height=100%>
        <tbody class="main">

            <tr>

                <td valign="top" align="middle" height="35">
                    <table cellspacing="0" class="titlebar" border=0>
                        <tr valign=top class="titlebar">
                            <td width="202" bgcolor="#99ccff">
                                &nbsp;&nbsp;<font color="#330066">MTUHA Book</font>
                            </td>
                            <td width="408" align=right bgcolor="#99ccff">
                                <a href="javascript: history.back();"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>
                                <a href="javascript:gethelp('reporting_overview.php','Reporting :: Overview')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>
                                <a href="<?php echo $root_path; ?>modules/reporting_tz/reporting_main_menu.php"><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>
                            </td>
                        </tr>
                    </table>

                    <?php
                    require_once($root_path . 'main_theme/reportingNav.inc.php');
                    require_once($root_path . 'main_theme/footer.inc.php');

                    ?>

                    <?php if (@($_SESSION['icd_updated']) && $_SESSION['icd_updated'] == 1): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                          <strong>Successfully Updated</strong>
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                    <?php endif; ?>
                    <?php if(@$_SESSION['icd_updated'] && $_SESSION['icd_updated'] == 0): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                          <strong>Update Failed. Please try again.</strong>
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                    <?php endif; @$_SESSION['icd_updated'] = ""; ?>


                    <br><br>
                    <form name="form1" method="POST" action="">

                        <input name="date_from" id="datepicker" type="text" size=15 maxlength=15 value="<?php echo $_POST['date_from'] ?>"  placeholder="Start date"  readonly>

                        <input name="date_to" id="datepicker2" type="text" size=15 maxlength=15 value="<?php echo $_POST['date_to'] ?>"  placeholder="End date"  readonly>
    

                        <?php


                        $companies = $insurance_obj->ShowAllInsurancesForQuotatuion();

                        ?>
                        <label>

                            <div>Ward/Dept:<select name="admission_id" id="admission_id" onChange="popdepts()">
                                    <option value="all_opd_ipd">ALL</option>
                                    <option value="1">INPATIENT</option>
                                    <option value="2">OUT PATIENT</option>

                                </select></div>

                            <div id="dept"></div>


                        </label>
                        <label><?php echo $companies; ?></label>
                        
                        <button type="button" data-toggle="modal" style="margin-left: 30px;" data-target="#mtuhaICDUpdate"> Update  </button>

                        </p>
                        <br>
                        <input type="submit" name="show" value="show">
                    </form>

                    <?php

                    if (isset($_POST['show'])) {
                        switch ($admission_nr) {
                            case 'all_opd_ipd':
                                $LDidara = 'ALL IN AND OUT PATIENT';
                                break;
                            case '1':
                                if ($current_ward_nr == 'all_ipd') {
                                    $LDidara = 'INPATIENT';
                                } else {
                                    $sql_idara = 'SELECT ward_id FROM care_ward WHERE nr=' . $current_ward_nr;
                                    $result = $db->Execute($sql_idara);
                                    $LDidara = $result->FetchRow();
                                    $LDidara = $LDidara['ward_id'];
                                }
                                break;

                            case '2':

                                if ($dept_nr == 'all_opd') {
                                    $LDidara = 'OUTPATIENT';
                                } else {

                                    $sql_dept = 'SELECT name_formal FROM care_department WHERE nr=' . $dept_nr;
                                    $result = $db->Execute($sql_dept);
                                    $LDidara = $result->FetchRow();
                                    $LDidara = $LDidara['name_formal'];
                                }
                                break;

                            default:
                                $LDidara = '';
                                break;
                        }


                        switch ($insurance) {
                            case '-2':
                                $LDhf = 'ALL COMPANIES';
                                break;

                            case '0':
                                $LDhf = 'CASH';
                                break;

                            default:

                                $sql_insurance = "SELECT name FROM care_tz_company WHERE id=" . $insurance;
                                $result = $db->Execute($sql_insurance);
                                $LDhf = $result->FetchRow();
                                $LDhf = $LDhf['name'];

                                break;
                        }
                    }

                    //echo $LDidara .' '.$LDhf;
                    ?>

                    <table width="90%"  border="1" cellspacing="0" cellpadding="0" align="center" bgcolor=#ffffdd>

                        <tr>
                            <td colspan="20"><b>Date:</b><?php echo ' ' . $monthName . ' ' . $_POST['year'] . ' '; ?><b>Health Fund:</b><?php echo $LDhf . '<b> Dept/ward:</b> ' . $LDidara;   ?> </td>
                        </tr>

                        <tr>
                            <td width="30%" colspan="2" bgcolor="#ffffaa"></td>
                            <td colspan="3" align="center" bgcolor="#ffffaa">&lt; 1 Month </td>
                            <td colspan="3" align="center">1 month to &lt; 1yr </td>
                            <td colspan="3" align="center" bgcolor="#ffffaa">1 yr to &lt; 5yrs </td>
                            <!--<td colspan="3" align="center">5 yrs and above</td>-->
                            <td colspan="3" align="center" bgcolor="#ffffaa">5 yrs to &lt; 60yrs </td>
                            <td colspan="3" align="center">60 yrs and above</td>
                            <td rowspan="2" bgcolor="#ffffaa">Total Male</td>
                            <td rowspan="2" bgcolor="#ffffaa">Total Female</td>
                            <td rowspan="2" bgcolor="#ffffaa">Grand Total</td>
                        </tr>
                        <tr>
                            <td width="10" bgcolor="#ffffaa">Mtuha Series</td>
                            <td width="50" bgcolor="#ffffaa"><?php echo $LDDiagnosticFullName; ?></td>
                            <td width="50" bgcolor="#ffffaa"><?php echo 'Male'; //echo $LDMale;      
                                                                ?></td>
                            <td width="50" bgcolor="#ffffaa"><?php echo 'Female'; //echo $LDFemale;      
                                                                ?></td>
                            <td width="50" bgcolor="#ffffaa"><?php echo 'Total'; //echo $LDTotal;      
                                                                ?></td>
                            <td width="50"><?php echo 'Male'; //echo $LDMale;      
                                            ?></td>
                            <td width="50"><?php echo 'Female'; //echo $LDFemale;      
                                            ?></td>
                            <td width="50"><?php echo 'Total'; //echo $LDTotal;      
                                            ?></td>
                            <td width="50" bgcolor="#ffffaa"><?php echo 'Male'; //echo $LDMale;      
                                                                ?></td>
                            <td width="50" bgcolor="#ffffaa"><?php echo 'Female'; //echo $LDFemale;      
                                                                ?></td>
                            <td width="50" bgcolor="#ffffaa"><?php echo 'Total'; //echo $LDTotal;      
                                                                ?></td>
                            <!--<td width="50"> //echo 'Male';//echo $LDMale; </td>
                                    <td width="50"><?php //echo 'Female';//echo $LDFemale;      
                                                    ?></td>
                                    <td width="50"><?php //echo 'Total'//echo $LDTotal;      
                                                    ?></td>-->
                            <td width="50"><?php echo 'Male'; //echo $LDMale;      
                                            ?></td>
                            <td width="50"><?php echo 'Female'; ?></td>
                            <td width="50"><?php echo 'Total' ?></td>
                            <td width="50" bgcolor="#ffffaa"><?php echo 'Male'; ?></td>
                            <td width="50" bgcolor="#ffffaa"><?php echo 'Female';  ?></td>
                            <td width="50" bgcolor="#ffffaa"><?php echo 'Total' ?></td>

                        </tr>

                        <?php
                        // $start = str_replace("/", "-", $_POST['date_from']);
                        // $end = str_replace("/", "-", $_POST['date_to']);

                        $dateFrom=explode("/", $_POST['date_from']);
                        $dateTo=explode("/", $_POST['date_to']);
                        $dayF=$dateFrom[0];
                        $monthF=$dateFrom[1];
                        $yearF=$dateFrom[2];

                        $dayT=$dateTo[0];
                        $monthT=$dateTo[1];
                        $yearT=$dateTo[2]; 

                        $MysqlDateFrom=$yearF.'-'.$monthF.'-'.$dayF.' 00:00:00';
                        $MysqlDateTo=$yearT.'-'.$monthT.'-'.$dayT.' 23:59:59';          
                          


                        
                        

                        
                        




                         $start=strtotime($MysqlDateFrom);
                         $end=strtotime($MysqlDateTo);

                         

                        $rep_obj->DisplayOPDMtuhaRows($start, $end, $_POST['admission_id'], $_POST['insurance'], $_POST['current_ward_nr'], $_POST['dept_nr']);
                        ?>
                    </table>


                    <a href="javascript:printOut()"><img border=0 src=<?php echo $root_path; ?>/gui/img/common/default/billing_print_out.gif> </a> <br>
                        <br><br><br>
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
                                                        //  Script End 
                                                        -->
                                                    </script>


                                                    <a href="http://www.care2x.org" target=_new>CARE2X 3rd Generation pre-deployment 3.3</a> :: <a href="../../legal_gnu_gpl.htm" target=_new> License</a> ::
                                                    <a href=mailto:care2x@care2x.org>Contact</a> :: <a href="../../language/en/en_privacy.htm" target="pp"> Our Privacy Policy </a> ::
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
                        


