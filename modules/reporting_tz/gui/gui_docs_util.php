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
        <h2><?php echo $LDDocsUtilReport; ?><?php echo ' as From: ' . $startdate . ' To: ' . @formatDate2Local($enddate, "dd/mm/yyyy"); ?></h1>
            <p><?php echo $LDCreationTime; ?><?php
                echo date("F j, Y, g:i a");
                ?></p>
    </DIV>
    <table border="1" cellspacing="0" cellpadding="0" align="center" bgcolor=#ffffdd>
        <tr> 
            <?php
            echo '<td align="center"><b>' . 'Serial No:' . '</td>';
            echo '<td align="center"><b>' . 'Date:' . '</td>';
            echo '<td align="center"><b>' . $LDDoctor . '</td>';
            echo '<td align="center"><b>' . $LDTotalPatients . '</td>';
            echo '<td align="center"><b>' . 'Department' . '</td>';
            echo '<td align="center"><b>' . $LDAmountperPatient . '</td>';
            echo '<td align="center"><b>' . $LDTotalAmount . '</td>';
            ?>
        </tr>
        <?php
        echo $tabler;
        ?>
        <tr> 
            <td bgcolor="#ffffaa" colspan="3"><b><?php echo'Grand ' . $LDtotal; ?></td>
            <?php
//            echo '<td></td>';
//            echo '<td></td>';
            echo '<td align="center"><b>' . $gtotal . '</b></td>';

            echo '<td></td>';

            echo '<td align="center"><b>' . number_format($gtotal * $amount_per_person, 2) . '</b></td>';


            echo '<td></td>';
            ?>


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
        <meta name="Author" content="Moye Masenga">
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
                urlholder = "./docs_util_report.php?printout=TRUE&start=<?php echo $startdate; ?>&end=<?php echo $enddate; ?>&amount_per_person=<?php echo $amount_per_person; ?>";
                testprintout = window.open(urlholder, "printout", "width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
                window.testprintout.moveTo(0, 0);
            }
            function patientsList(doctor) {
//                alert(doctor);
                urlholder = "./docs_util_report_patients.php?doctor=" + doctor + "&start=<?php echo $startdate; ?>&end=<?php echo $enddate; ?>";
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
                                <td width="302" bgcolor="#99ccff" > &nbsp;&nbsp;<font color="#330066"><?php echo $LDDocsUtilReport; ?></font></td>
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
                            <DIV align="center">
                                <h2><?php echo $LDDocsUtilReport; ?><?php echo 'From: ' . $_POST['date_from'] . ' ' . '00:00:00 ' . 'To: ' . @formatDate2Local($enddate, "dd/mm/yyyy") . ' ' . '23:59:59'; ?></h1>
                                    <p><?php echo $LDCreationTime; ?><?php
                                        echo date("F j, Y, g:i a");
                                        ?></p>
                            </DIV>
                            <table border="1" cellspacing="0" cellpadding="2" align="center" bgcolor=#ffffdd>
                                <tr> 
                                    <?php
                                    echo '<td align="center"><b>' . 'Serial No:' . '</td>';
                                    echo '<td align="center"><b>' . 'Date:' . '</td>';
                                    echo '<td align="center"><b>' . $LDDoctor . '</td>';
                                    echo '<td align="center"><b>' . $LDTotalPatients . '</td>';
                                    echo '<td align="center"><b>' . 'Department' . '</td>';
                                    echo '<td align="center"><b>' . $LDAmountperPatient . '</td>';
                                    echo '<td align="center"><b>' . $LDTotalAmount . '</td>';
                                    echo '<td align="center"><b>' . $LDOptionsTittle . '</td>';
                                    ?>
                                </tr>
                                <?php
                                echo $tabler;
                                ?>
                                <tr> 
                                    <td bgcolor="#ffffaa" colspan="3"><b><?php echo'Grand ' . $LDtotal; ?></td>
                                    <?php
//                                    echo '<td></td>';
//                                    echo '<td></td>';
                                    echo '<td align="center"><b>' . $gtotal . '</b></td>';

                                    echo '<td></td>';

                                    echo '<td align="center"><b>' . number_format($gtotal * $amount_per_person, 2) . '</b></td>';


                                    echo '<td></td>';
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