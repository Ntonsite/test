<?php 
require('./roots.php');

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

<STYLE TYPE="text/css">
A:link  {color: #000066;}
A:hover {color: #cc0033;}
A:active {color: #cc0000;}
A:visited {color: #000066;}
A:visited:active {color: #cc0000;}
A:visited:hover {color: #cc0033;}
</style>
<script language="JavaScript">
<!--

function popPic(pid,nm){

 if(pid!="") regpicwindow = window.open("../../main/pop_reg_pic.php?sid=<?php echo sid;?>&lang=$lang&pid="+pid+"&nm="+nm,"regpicwin","toolbar=no,scrollbars,width=180,height=250");

}
function getARV(path) {
	urlholder="<?php echo $root_path ?>"+path+"<?php echo URL_REDIRECT_APPEND; ?>";
	patientwin=window.open(urlholder,"arv","menubar=no,resizable=yes,scrollbars=yes");
	patientwin.resizeTo(screen.availWidth,screen.availHeight);
	patientwin.focus();
}

// -->
</script>


</HEAD>
<BODY bgcolor=#ffffff link=#000066 alink=#cc0000 vlink=#000066  >

<!-- START HEAD OF HTML CONTENT --->

<table width=100% border=0 cellspacing=0 height=100%>
	<tr>
		<td  valign="top" align="middle" height="35">
			 <table cellspacing="0"  class="titlebar" border=0>
 				<tr valign=top  class="titlebar" >
  					<td width="202" bgcolor="#99ccff" >
    					&nbsp;&nbsp;<font color="#330066"><?php echo $LDRemortingMenu; ?></font></td>
						  <td width="408" align=right bgcolor="#99ccff">
						   <a href="javascript: history.back();"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)" ></a>
						   <a href="javascript:gethelp('reporting_overview.php','Reporting :: Overview')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>
						   <a href="javascript:history.back()" ><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>
						  </td>
  					 </tr>
 </table>

 <?php require_once($root_path . 'main_theme/reportingNav.inc.php'); ?>

<!-- END HEAD OF HTML CONTENT -->

<div class="row">

    <div class="col-md-6">
    <div class="card">
       <div class="card-header bg-light">
          <h4 class="card-title">Clinical Reports</h4>
        </div>

      <div class="card-body">

         <div class="table-responsive">
          <table class="table table-condensed table-striped table-hover">
            <tbody>

              <tr>
                <td with="70%"><a href="<?php echo $root_path ?>modules/reporting_tz/OPD_diagnostic.php">OPD - Diagnostic <5 >5</a></td>
                <td><small class="font-sm">All diagnostics group by ICD-10 code</small></td>
              </tr>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/OPD_diagnostic_1_month.php">OPD - Diagnostic_1_month</a></td>
                <td><small class="font-sm">All diagnostics group by ICD-10 code</small></td>
              </tr>

               <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/OPD_summary.php">OPD - Summary</a></td>
                <td><small class="font-sm">All visits (with diagnostic) to this clinic</small></td>
              </tr>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/OPD_summary_withoutdiagnosis.php">OPD - Summary</a></td>
                <td><small class="font-sm">All visits (without diagnostic) to this clinic</small></td>
              </tr>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/OPD_infections.php">OPD - Infections-Summary</a></td>
                <td><small class="font-sm"></small></td>
              </tr>

               <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/mtuha_icd10.php">Mtuha-ICD10-Summary</a></td>
                <td><small class="font-sm"></small></td>
              </tr>

               <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/mtuha_icd10_report.php">MTUHA BOOK 5 AND 14</a></td>
                <td><small class="font-sm">BOOK 5 IS OPD AND 14 IS IPD. MAKE SELECTION ACCORDINGLY</small></td>
              </tr>
               <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/mtuha_opd_summary.php">MTUHA BOOK 5 AND 14 VISITS</a></td>
                <td><small class="font-sm">THIS REPORT SHOWS ATTENDANCE</small></td>
              </tr>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/mtuha_detailed.php">Detailed MTUHA Report</a></td>
                <td><small class="font-sm"></small></td>
              </tr>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/mtuha_book.php">MTUHA BOOK</a></td>
                <td><small class="font-sm"></small></td>
              </tr>

               <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/mtuha_visits.php">New and Return Patients</a></td>
                <td><small class="font-sm"></small></td>
              </tr>
              
               

                <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/OPD_Admissions.php">OPD and IPD Admission Summary</a></td>
                <td><small class="font-sm">OPD and IPD with Health Fund</small></td>
              </tr>

                <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/OPD_new_return.php">New and Return with Gender</a></td>
                <td><small class="font-sm">OPD Admissions Report</small></td>
              </tr>
                <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/OPD_Department_Admissions.php">OPD Departments Admission Summary</a></td>
                <td><small class="font-sm">OPD Admissions Department Summary Report</small></td>
              </tr>
                 <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/IPD_admissions.php">IPD Admission Summary</a></td>
                <td><small class="font-sm">IPD Admission Summary Report</small></td>
              </tr> 

                <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/IPD_discharge.php">IPD Discharge Summary</a></td>
                <td><small class="font-sm">IPD Discharge Summary Report</small></td>
              </tr>
                <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/IPD_discharge_types.php">IPD Discharge Types Summary  </a></td>
                <td><small class="font-sm">IPD Discharge Report by types</small></td>
              </tr>

            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

 <div class="col-md-6">
   
   <div class="row">
      <div class="col-md-12">
    <div class="card">
       <div class="card-header bg-light">
          <h4 class="card-title">Investigation Reports</h4>
        </div>

      <div class="card-body">
        
        <div class="table-responsive">
          <table class="table table-condensed table-striped table-hover">
            <tbody>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/laboratory_summary.php">NUMBER OF LAB TEST PER EACH DAY IN A MONTH</a></td>
                <td><small class="font-sm">TESTS THAT RESULT HAS BEEN ENTERED</small></td>
              </tr>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/detailed_lab.php">POSITIVE AND NEGATIVE RESULTS BY GENDER</a></td>
                <td><small class="font-sm">ONLY TEST WITH POSITIVE AND NEGATIVE RESULT</small></td>
              </tr>

               <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/lab_mtuha.php">NUMBER OF ALL LAB RESULT BY GENDER</a></td>
                <td><small class="font-sm">ALL TESTS DONE</small></td>
              </tr>
              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/radiologyTests.php">RADIOLOGY TESTS</a></td>
                <td><small class="font-sm">RADIOLOGY TESTS</small></td>
              </tr>

            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="card">
       <div class="card-header bg-light">
          <h4 class="card-title">CTC Reports</h4>
        </div>

      <div class="card-body">
        
        <div class="table-responsive">
          <table class="table table-condensed table-striped table-hover">
            <tbody>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/arv_2/arv_reporting_cohort_quarterly.php"> NACP Cross Sectional Report</a></td>
                <td><small class="font-sm"></small>Quarterly Facility Based HIV Care/ART Reporting</td>
              </tr>

               <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/arv_2/arv_reporting_cohort_quarterly.php">NACP Quarterly Cohort Report</a></td>
                <td><small class="font-sm">Quarterly Cohort Reporting</small></td>
              </tr>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/arv_2/arv_reporting_one_cohort.php">One Cohort Report</a></td>
                <td><small class="font-sm">Six Years Cohort Reporting</small></td>
              </tr>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/arv_2/arv_reporting_six_cohorts.php">Six Cohorts Report</a></td>
                <td><small class="font-sm">Two Years Cohort Reporting</small></td>
              </tr>
              
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="card">

        <div class="card-header bg-light">
          <h4 class="card-title">Pharmacy Reports</h4>
        </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-condensed table-striped table-hover">
            <tbody>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/reporting_pharmacy_stock.php">Pharmacy with stock information</a></td>
                <td><small class="font-sm">Pharmacy Report (with stock information and cost)</small></td>
              </tr>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/reporting_consumables.php">Consumables/Supplies Report</a></td>
                <td><small class="font-sm">Monthly Utilization of consumables and supplies</small></td>
              </tr>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/deleted_prescription.php">Deleted Prescriptions Report</a></td>
                <td><small class="font-sm">Deleted Prescriptions</small></td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

   </div>

 </div>

  
<?php if ($showFinancialReport): ?>  

  <div class="col-md-6">
     
    <div class="card">
      <div class="card-header bg-light">
          <h4 class="card-title">Revenue Reports</h4>
        </div>
      <div class="card-body">

        <div class="table-responsive">
          <table class="table table-condensed table-striped table-hover">
            <tbody>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/DetailedRevenue.php">Detailed_Revenue</a></td>
                <td><small class="font-sm">Detailed Revenue Report</small></td>
              </tr>

               <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/DailySummary.php">Daily Summary </a></td>
                <td><small class="font-sm"></small>Daily Summary Report</td>
              </tr>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/services.php">Consultations/Services Report  </a></td>
                <td><small class="font-sm">Consultation and Services</small></td>
              </tr>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/laboratory_income.php">Laboratory Revenue Report  </a></td>
                <td><small class="font-sm">Laboratory Revenue Report</small></td>
              </tr>


              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/reporting_radiology.php">Radiology Report </a></td>
                <td><small class="font-sm">Monthly Radiology Report</small></td>
              </tr>


              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/general_surgeries_report.php">General Surgeries Report </a></td>
                <td><small class="font-sm"></small>Monthly general Surgeries Report</td>
              </tr>


              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/obgyne_surgeries_report.php">OB Gyne Report </a></td>
                <td><small class="font-sm"></small>Monthly OB Gyne Surgeries Report</td>
              </tr>


              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/ortho_surgeries_report.php">Orthopedics Surgeries Report</a></td>
                <td><small class="font-sm">Orthopedics Surgeries Report</small></td>
              </tr>


              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/minor_procedures_report.php">Dressings and Minor Procedures </a></td>
                <td><small class="font-sm">Reporting::Minor Procedures/Dressings Report</small></td>
              </tr>

               <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/zero_consultations.php">Consultations with Zero Amount</a></td>
                <td><small class="font-sm">Consultations with Zero Amount</small></td>
              </tr>


              <!-- <tr>
                <td><a class="report-link" href="<?php //echo $root_path ?>modules/reporting_tz/blockpayment_financial_summary.php">Block payment financial summary</a></td>
                <td><small class="font-sm"></small>Block Payment Summary</td>
              </tr> -->


              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/pending_quotations_report.php">Pending Quotations Report  </a></td>
                <td><small class="font-sm"></small>Montly Report of value of daily pending quotations</td>
              </tr>


              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/deleted_quotations_report.php">Deleted Quotations Report  </a></td>
                <td><small class="font-sm">Monthly Report of deleted bill quotations</small></td>
              </tr>


              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/tracking_summary.php">Audit Report </a></td>
                <td><small class="font-sm">Monthly Audit Trail Report</small></td>
              </tr>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/reporting_weberp.php">WebERP Report </a></td>
                <td><small class="font-sm">Failed WebERP Transactions</small></td>
              </tr>

                <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/meals_report.php">Meals Report </a></td>
                <td><small class="font-sm">Meals Report</small></td>
              </tr>
              
              
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

<?php endif ?>


   <div class="col-md-6">
    <div class="card">
       <div class="card-header bg-light">
          <h4 class="card-title">System Reports</h4>
        </div>

      <div class="card-body">

         <div class="table-responsive">
          <table class="table table-condensed table-striped table-hover">
            <tbody>

              <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/docs_util_report.php">Doctors' Patients Consultation Report Summary</a></td>
                <td><small class="font-sm">Doctors' Patients Consultation Report Summary</small></td>
              </tr>

               <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/docs_presc_report.php">Number of Patients seen by doctors'(counted by First to take history)  </a></td>
                <td><small class="font-sm">Number of Patients seen by doctors'(counted by First to take history)</small></td>
              </tr>   
               <tr>
                <td><a class="report-link" href="<?php echo $root_path ?>modules/reporting_tz/radiologyInvestigationByDoctor.php">Radiology Investigation Done </a></td>
                <td><small class="font-sm">Number of Radiology Investigation done by doctor</small></td>
              </tr>             
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

</div>

<!-- 
<TABLE cellSpacing=0 cellPadding=0 border=0 class="submenu_frame">
	<TBODY>
	<TR>
		<TD><table cellpadding=3 cellspacing=1>
              <tbody class="submenu">
                <TR>
                        <td align=center><img src="../../gui/img/common/default/b-write_addr.gif" border=0></td>
                        <TD class="submenu_item"><nobr><a href="clinical_reports_pass.php"><?php echo $LDClinicReports; ?></a></nobr></TD>
                        <TD><?php echo $LDClinicalReports; ?></TD>
                      </tr>
                      <TR  height=1>
                        <TD colSpan=3 class="vspace"><IMG height=1 src="../../gui/img/common/default/pixel.gif" width=5></TD>
                      </TR>
                
                 <td align=center><img src="../../gui/img/common/default/b-write_addr.gif" border=0></td>
                        <TD class="submenu_item"><nobr><a href="laboratory_summary.php"><?php echo $LDLaboratorySummary; ?></a></nobr></TD>
                        <TD><?php echo $LDMonthlyLabReport; ?></TD>
                      </tr>                                    
                      
                    
                      <TR  height=1>
                        <TD colSpan=3 class="vspace"><IMG height=1 src="../../gui/img/common/default/pixel.gif" width=5></TD>
                      </TR>
                
                 <td align=center><img src="../../gui/img/common/default/b-write_addr.gif" border=0></td>
                        <TD class="submenu_item"><nobr><a href="detailed_lab.php"><?php echo $LDLabDetailed; ?></a></nobr></TD>
                        <TD><?php echo $LDLabDetailed; ?></TD>
                      </tr>                                    
                      
                      <TR  height=1>
                        <TD colSpan=3 class="vspace"><IMG height=1 src="../../gui/img/common/default/pixel.gif" width=5></TD>
                      </TR>
                
                 <td align=center><img src="../../gui/img/common/default/b-write_addr.gif" border=0></td>
                        <TD class="submenu_item"><nobr><a href="lab_mtuha.php"><?php echo $LDMtuhaLab; ?></a></nobr></TD>
                        <TD><?php echo $LDMtuhaLab; ?></TD>
                      </tr>   
                      
                      
					  
                   <TR  height=1>
                   <TD colSpan=3 class="vspace"><IMG height=1 src="../../gui/img/common/default/pixel.gif" width=5></TD>
                   </TR>
                    <tr>
                <td align=center><img src="../../gui/img/common/default/b-write_addr.gif" border=0></td>
                <TD class="submenu_item"><nobr><a href="ctc_reports_pass.php"><?php echo 'CTC Reports'; ?></a></nobr></TD>
                <TD><?php echo 'CTC Clinic Reports'; ?></TD>
                    </tr>
                      
					  
					  <TR  height=1>
                        <TD colSpan=3 class="vspace"><IMG height=1 src="../../gui/img/common/default/pixel.gif" width=5></TD>
                      </TR>
					  
					  <td align=center><img src="../../gui/img/common/default/b-write_addr.gif" border=0></td>
                        <TD class="submenu_item"><nobr><a href="financial_reports_pass.php"><?php echo $LDRevReports; ?></a></nobr></TD>
                        <TD><?php echo $LDRevenueReports; ?></TD>
                      </tr>
                       
					  
					  <TR  height=1>
                        <TD colSpan=3 class="vspace"><IMG height=1 src="../../gui/img/common/default/pixel.gif" width=5></TD>
                      </TR>
					  
					  <td align=center><img src="../../gui/img/common/default/b-write_addr.gif" border=0></td>
                        <TD class="submenu_item"><nobr><a href="system_reports_pass.php"><?php echo $LDSystemReports; ?></a></nobr></TD>
                        <TD><?php echo $LDSystemUtilReports; ?></TD>
                      </tr>
                      <TR  height=1>
                        <TD colSpan=3 class="vspace"><IMG height=1 src="../../gui/img/common/default/pixel.gif" width=5></TD>
                      </TR>	 
                
              </tbody>
            </table></TD>
	</TR>
	</TBODY>
</TABLE> -->
<br><br><br>


<!-- START BOTTIOM OF HTML CONTENT --->

<table width="100%" border="1" cellspacing="0" cellpadding="1" bgcolor="#cfcfcf">
<tr>
	<td align="center">
  		<table width="100%" bgcolor="#ffffff" cellspacing=0 cellpadding=5>
   		<tr>
   			<td>
	    		<div class="copyright">
					<script language="JavaScript">
					<!-- Script Begin
					function openCreditsWindow() {

						urlholder="../../language/$lang/$lang_credits.php?lang=$lang";
						creditswin=window.open(urlholder,"creditswin","width=500,height=600,menubar=no,resizable=yes,scrollbars=yes");

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
</HTML>
