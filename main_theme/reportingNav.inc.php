<?php require_once $root_path . 'main_theme/inc_reporting_permission.php';?>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon">Main Report navigation</span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo $root_path ?>modules/reporting_tz/reporting_main_menu.php"><i class="fa fa-home"></i> <span class="sr-only">(Home)</span></a>
      </li>
      <?php if ($showClinicalReport): ?>
       <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Clinical Reports
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/OPD_diagnostic.php<?php echo URL_APPEND ?>">OPD - Diagnostic <5 >5</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/OPD_diagnostic_1_month.php<?php echo URL_APPEND ?>">OPD - Diagnostic_1_month</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/OPD_summary.php<?php echo URL_APPEND ?>">OPD - Summary<small>&nbsp;  - All visits (with diagnostic) to this clinic</small></a>
          <a class="dropdown-item"  href="<?php echo $root_path ?>modules/reporting_tz/OPD_summary_withoutdiagnosis.php<?php echo URL_APPEND ?>">OPD - Summary <small>&nbsp;  - All visits (without diagnostic) to this clinic </small></a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/OPD_infections.php<?php echo URL_APPEND ?>">OPD - Infections-Summary</a>
          <div class="dropdown-divider"></div>

          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/mtuha_icd10.php<?php echo URL_APPEND ?>">MTUHA-ICD10-Summary</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/mtuha_icd10_report.php<?php echo URL_APPEND ?>">MTUHA BOOK 5 AND 14</a>
           <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/mtuha_opd_summary.php<?php echo URL_APPEND ?>">MTUHA BOOK 5 AND 14 VISITS</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/mtuha_detailed.php<?php echo URL_APPEND ?>">Detailed MTUHA Report</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/mtuha_book.php<?php echo URL_APPEND ?>">MTUHA BOOK</a>

          <div class="dropdown-divider"></div>

          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/mtuha_visits.php<?php echo URL_APPEND ?>">New and Return Patients</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/OPD_Admissions.php<?php echo URL_APPEND ?>">OPD and IPD Admission Summary</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/OPD_new_return.php<?php echo URL_APPEND ?>">New and Return with Gender</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/OPD_Department_Admissions.php<?php echo URL_APPEND ?>">OPD Departments Admission Summary</a>
          <div class="dropdown-divider"></div>
           <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/IPD_admissions.php<?php echo URL_APPEND ?>">IPD Admission Summary</a>
           <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/IPD_discharge.php<?php echo URL_APPEND ?>">IPD Discharge Summary</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/IPD_discharge_types.php<?php echo URL_APPEND ?>">IPD Discharge Types Summary</a>
          <div class="dropdown-divider"></div>

        </div>
      </li>
      <?php endif?>

      <?php if ($showLabReport): ?>
       <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Investigation Reports
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/laboratory_summary.php<?php echo URL_APPEND ?>">NUMBER OF LAB TEST PER EACH DAY IN A MONTH</a>

           <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/detailed_lab.php<?php echo URL_APPEND ?>"> POSITIVE AND NEGATIVE RESULTS BY GENDER</a>

           <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/lab_mtuha.php<?php echo URL_APPEND ?>">NUMBER OF ALL LAB RESULT BY GENDER</a>

            <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/radiologyTests.php<?php echo URL_APPEND ?>">RADIOLOGY TESTS</a>

          <div class="dropdown-divider"></div>
        </div>
      </li>
      <?php endif?>

      <?php if ($showctcReport): ?>
       <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          CTC Reports
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/arv_2/arv_reporting_quarterly.php<?php echo URL_APPEND ?>">NACP Cross Sectional Report</a>
          <div class="dropdown-divider"></div>

          <a class="dropdown-item" href="<?php echo $root_path ?>modules/arv_2/arv_reporting_cohort_quarterly.php?<?php echo URL_APPEND ?>">NACP Quarterly Cohort Report <small>&nbsp; &nbsp;Quarterly Cohort Reporting</small></a>


          <a class="dropdown-item" href="<?php echo $root_path ?>modules/arv_2/arv_reporting_one_cohort.php<?php echo URL_APPEND ?>">One Cohort Report <small>&nbsp; &nbsp;Six Years Cohort Reporting</small></a>


          <a class="dropdown-item" href="<?php echo $root_path ?>modules/arv_2/arv_reporting_six_cohorts.php<?php echo URL_APPEND ?>">Six Cohorts Report <small>&nbsp; &nbsp;Two Years Cohort Reporting</small></a>

          <div class="dropdown-divider"></div>

          <a class="dropdown-item" href="<?php echo $root_path ?>modules/arv_2/arv_reporting_cohort_ind.php<?php echo URL_APPEND ?>">NACP Cohort Indicators Report</a>
          <div class="dropdown-divider"></div>
        </div>
      </li>
      <?php endif;?>

      <?php if ($showFinancialReport): ?>
       <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Revenue Reports
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/DetailedRevenue.php<?php echo URL_APPEND ?>">Detailed_Revenue</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/DailySummary.php<?php echo URL_APPEND ?>">Daily Summary</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/services.php<?php echo URL_APPEND ?>">Consultations/Services Report</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/laboratory_income.php<?php echo URL_APPEND ?>">Laboratory Revenue Report</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/reporting_radiology.php<?php echo URL_APPEND ?>">Radiology Report</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/general_surgeries_report.php<?php echo URL_APPEND ?>">General Surgeries Report</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/obgyne_surgeries_report.php<?php echo URL_APPEND ?>">OB Gyne Report</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/ortho_surgeries_report.php<?php echo URL_APPEND ?>">Orthopedics Surgeries Report</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/minor_procedures_report.php<?php echo URL_APPEND ?>">Dressings and Minor Procedures</a>

          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/zero_consultations.php">Consultations with Zero Amount</a>
         <!--  <a class="dropdown-item" href="<?php //echo $root_path ?>modules/reporting_tz/blockpayment_financial_summary.php<?php //echo URL_APPEND ?>">Block payment financial summary </a> -->
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/pending_quotations_report.php<?php echo URL_APPEND ?>">Pending Quotations Report</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/deleted_quotations_report.php<?php echo URL_APPEND ?>">Deleted Quotations Report</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/tracking_summary.php<?php echo URL_APPEND ?>">Audit Report</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/reporting_weberp.php<?php echo URL_APPEND ?>">WebERP Report</a>
          <div class="dropdown-divider"></div>

          <?php if ($showMealsReport): ?>
           <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/meals_report.php<?php echo URL_APPEND ?>">Meals Report</a>
          <div class="dropdown-divider"></div>
          <?php endif?>


        </div>
      </li>
      <?php endif?>

      <?php if ($showPharmacyReport): ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Pharmacy Reports
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/reporting_pharmacy_stock.php<?php echo URL_APPEND ?>">Pharmacy with stock information</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/reporting_consumables.php<?php echo URL_APPEND ?>">Consumables/Supplies Report</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/deleted_prescription.php<?php echo URL_APPEND ?>">Deleted Prescription Report</a>
          <div class="dropdown-divider"></div>


        </div>
      </li>
      <?php endif;?>

      <?php if ($showSystemReport): ?>
       <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          System Reports
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/docs_util_report.php<?php echo URL_APPEND ?>">Doctors' Patients Consultation Report Summary</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/docs_presc_report.php<?php echo URL_APPEND ?>">Number of Patients seen by doctors'(counted by First to take history)</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/radiologyInvestigationByDoctor.php<?php echo URL_APPEND ?>">Radiology Investigation Done</a>
          <a class="dropdown-item" href="<?php echo $root_path ?>modules/reporting_tz/docs_util_general_report.php<?php echo URL_APPEND ?>">Doctors' Activities</a>
          <div class="dropdown-divider"></div>
        </div>
      </li>
      <?php endif;?>

      <?php if ($showAuditorCorner): ?>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $root_path ?>modules/reporting_tz/auditor_report.php<?php echo URL_APPEND ?>" id="" role="button">
          Auditor's Corner
        </a>
      </li>
      <?php endif?>

  </div>
</nav>
