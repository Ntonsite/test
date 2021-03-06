<link rel="stylesheet" type="text/css" href="../../js/time_picker/assets/css/bootstrap.min.css"> 
<link rel="stylesheet" type="text/css" href="../../js/time_picker/dist/bootstrap-clockpicker.min.css">
<script type="text/javascript" src="../../js/time_picker/assets/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="../../js/date_picker/jquery-ui.css">
  <script src="../../js/date_picker/jquery-1.12.4.js"></script>
  <script src="../../js/date_picker/jquery-ui.js"></script>
 <script type="text/javascript" src="../../js/time_picker/dist/bootstrap-clockpicker.min.js"></script>


 <script>
    
   $( function() {
    $( "#date_from" ).datepicker({ dateFormat: 'dd/mm/yy' }).val();
  } );

     
  </script>


  <script>
    
   $( function() {
    $( "#date_to" ).datepicker({ dateFormat: 'dd/mm/yy' }).val();
  } );

     
  </script>



<table width="59%" border="0" align="center">
    <tr>
        <td><?php 
        $LDDateFrom=(isset($LDDateFrom) ? $LDDateFrom : null);
        $_POST['date_from']=(isset($_POST['date_from']) ? $_POST['date_from'] : null);
        echo $LDDateFrom; 
        ?><input name="date_from" id="datepicker" type="text" size=15 maxlength=15 value="<?php echo $_POST['date_from'] ?>"  placeholder="Start date"  readonly >
            <?php 
            echo $LDDateTo;
            $_POST['date_to']=(isset($_POST['date_to']) ? $_POST['date_to'] : null); 
            ?>
            <input name="date_to" id="datepicker2" type="text" size=15 maxlength=15 value="<?php echo $_POST['date_to'] ?>" placeholder="End date" readonly >
            
            <?php echo $insurance_obj->ShowAllInsurancesForQuotatuion(); ?>
            <select name="admission_id" id="admission_id" onChange="javascript:popdepts()">
                <OPTION selected value="all_opd_ipd" >--All--</OPTION>
                <OPTION value="2">Outpatient</OPTION>
                <OPTION value="1">Inpatient</OPTION>
            </select>
            <div id="dept"></div>
            <?php ?><br>
            
            <input type="submit" name="show"  value="<?php echo $LDShow; ?>">
        </td>

    </tr>
</table>	














