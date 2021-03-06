  <link rel="stylesheet" href="../../js/date_picker/jquery-ui.css">
  <script src="../../js/date_picker/jquery-1.12.4.js"></script>
  <script src="../../js/date_picker/jquery-ui.js"></script>
 <script type="text/javascript" src="../../js/time_picker/dist/bootstrap-clockpicker.min.js"></script>


<script>
    
   $( function() {
    $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd 00:00:00' }).val();
  } );


    $( function() {
    $( "#datepicker2" ).datepicker({ dateFormat: 'yy-mm-dd 23:59:59' }).val();
  } );

     
  </script>

	<input name="date_from" id="datepicker" type="text" size=15 maxlength=15 value="<?php echo $_POST['date_from'] ?>"  placeholder="Start date"  readonly>

 	<input name="date_to" id="datepicker2" type="text" size=15 maxlength=15 value="<?php echo $_POST['date_to'] ?>"  placeholder="End date"  readonly>
 	
 



