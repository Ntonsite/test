 <!-- <footer class="footer">
        <div class="container-fluid">
          <nav class="float-left">
            <ul>
              <li>
                <a href="#">
                 OSTECH
                </a>
              </li>
              <li>
                <a href="#">
                  About Us
                </a>
              </li>
              <li>
                <a href="#">
                  Blog
                </a>
              </li>
              <li>
                <a href="#">
                  Licenses
                </a>
              </li>
            </ul>
          </nav>
          <div class="copyright float-right">
            &copy;
            <script>
              document.write(new Date().getFullYear())
            </script>, made with <i class="material-icons">favorite</i> by
            <a href="#" target="_blank">Entice</a> for a better Hospital.
          </div>
        </div>
      </footer> -->
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="<?php echo $root_path ?>/js/care_md/jquery.min.js" type="text/javascript"></script>
  <script src="<?php echo $root_path ?>/js/care_md/popper.min.js" type="text/javascript"></script>
  <script src="<?php echo $root_path ?>/js/care_md/bootstrap-material-design.min.js" type="text/javascript"></script>
  <!-- <script src="<?php echo $root_path ?>/js/care_md/plugins/perfect-scrollbar.jquery.min.js"></script> -->
  <!-- Chartist JS -->
  <!-- <script src="<?php echo $root_path ?>/js/care_md/plugins/chartist.js"></script> -->
  <!--  Notifications Plugin    -->
  <script src="<?php echo $root_path ?>/js/care_md/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="<?php echo $root_path ?>/js/care_md/material-dashboard.js?v=2.1.0" type="text/javascript"></script>
  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <!-- <script src="<?php echo $root_path ?>/js/care_md/chartist-plugin-tooltip.js"></script> -->
  <!-- <script src="<?php echo $root_path ?>/js/care_md/chartist-plugin-legend.js"></script> -->
  <script src="<?php echo $root_path ?>/js/care_md/plugins/Chart.bundle.min.js"></script>
  <script src="<?php echo $root_path ?>/js/care_md/plugins/moment.min.js"></script>
  <script src="<?php echo $root_path ?>/js/care_md/plugins/date-time-picker.min.js"></script>
  <script src="<?php echo $root_path ?>/js/care_md/plugins/jquery.Wload.js"></script>

  <script src="<?php echo $root_path ?>/js/care_md/plugins/idle-timer.js"></script>

      <!--datatables-->
    <script src="<?php echo $root_path; ?>assets/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?php echo $root_path; ?>assets/datatables/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
 
    <!--Bootstrap-->
    <!-- <script src="<?php echo $root_path; ?>assets/bootstrap/js/bootstrap.min.js"></script> -->
    <script src="<?php echo $root_path; ?>assets/datatables/plugins/fixedHeader/js/dataTables.fixedHeader.min.js" type="text/javascript"></script>

    <script src="<?php echo $root_path; ?>assets/datatables/plugins/responsive/js/dataTables.responsive.min.js" type="text/javascript"></script>
    <!-- <script src="<?php echo $root_path; ?>assets/datatables/plugins/responsive/js/responsive.bootstrap4.min.js" type="text/javascript"></script> -->
    <!-- <script src="<?php echo $root_path; ?>assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script> -->

    <script src="<?php echo $root_path ?>/js/care_md/plugins/dataTables.buttons.min.js"></script>
    <script src="<?php echo $root_path ?>/js/care_md/plugins/buttons.flash.min.js"></script>
    <script src="<?php echo $root_path ?>/js/care_md/plugins/jszip.min.js"></script>
    <script src="<?php echo $root_path ?>/js/care_md/plugins/pdfmake.min.js"></script>
    <script src="<?php echo $root_path ?>/js/care_md/plugins/vfs_fonts.js"></script>
    <script src="<?php echo $root_path ?>/js/care_md/plugins/buttons.html5.min.js"></script>
    <script src="<?php echo $root_path ?>/js/care_md/plugins/buttons.print.min.js"></script>
    
    <script src="<?php echo $root_path; ?>js/care_md/plugins/popper.js" ></script>

<?php 

if (empty($_SESSION['sess_login_userid'])) {
    header("location: " . $root_path . "main/login.php");
    exit;
}

(@include_once ( $root_path.'modules/main_js.php'));

?>