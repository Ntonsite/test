<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Datepicker - Default functionality</title>
                
<link rel="stylesheet" type="text/css" href="../../js/time_picker/assets/css/bootstrap.min.css">
 
<link rel="stylesheet" type="text/css" href="../../js/time_picker/dist/bootstrap-clockpicker.min.css">

<script type="text/javascript" src="../../js/time_picker/assets/js/bootstrap.min.js"></script>




  <link rel="stylesheet" href="../../js/date_picker/jquery-ui.css">
  <script src="../../js/date_picker/jquery-1.12.4.js"></script>
  <script src="../../js/date_picker/jquery-ui.js"></script>
 <script type="text/javascript" src="../../js/time_picker/dist/bootstrap-clockpicker.min.js"></script>

  <script>
    
   $( function() {
    $( "#date" ).datepicker({ dateFormat: 'dd/mm/yy' }).val();
  } );

     
  </script>


</head>


<body>

<script type="text/javascript">
    function chkForm(d) {
        var r = false;

        if (d.date.value == '') {
            alert("<?php echo $LDPlsEnterDate; ?>");
            d.date.focus();
            return false;
        } else if (d.time.value == '') {
            alert("<?php echo $LDPlsEnterTime; ?>");
            d.time.focus();
            return false;
        } else if (d.to_dept_nr.value == '') {
            alert("<?php echo $LDPlsSelectDept; ?>");
            d.to_dept_nr.focus();
            return false;
        } else if (d.to_personell_name.value == '') {
            alert("<?php echo $LDPlsEnterDoctor; ?>");
            d.to_personell_name.focus();
            return false;
        } else if (d.purpose.value == '') {
            alert("<?php echo $LDPlsEnterPurpose; ?>");
            d.purpose.focus();
            return false;
        } else if (d.hd.value == '1') {
            alert("There is another appointment on this date.");
            d.time.focus();
            return false;
        } else if (d.max_app.value == '1') {
            alert("Maximum number of appointments reached!");
            return false;
        } else {
            r = validateTime('submit');
            if (r)
                return true;
            else
                return false;
        }
    }

    </script>


    <script type="text/javascript">
        // function validateAppt(){
        //     alert("It works! please proceed");
        //     return false;
            
        // }
        
    </script>

 
<form method="post" name="appt_form" onSubmit="return chkForm(this)">
    <table border=0 cellpadding=2 width=100%>
        <tr bgcolor="#f6f6f6">
            <td><font color="red"><b>*</b><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDDate; ?></td>
            <td><input type="text" name="date" id="datepicker"   readonly="readonly">                     
            
            </td>
        </tr>
        <tr bgcolor="#f6f6f6">
            <td><font color="red"><b>*</b><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDTime; ?></td>
            <td>


            





<input id="time" name="time" readonly placeholder="Time"  />
<div id="display"></div>


<script type="text/javascript">

$('#time').clockpicker({
    autoclose: true 
});



</script>
<script type="text/javascript">
   

        function validateTime(){

            $(document).ready(function (){ 
            
            var tarehe= $("#date").val();
            var idara=$("#to_dept_nr").val();
            var muda =$("#time").val();            

                $.ajax({
                url: "input_show_appointment_validator.php",
                data:{tm: muda,dt:tarehe,dpt:idara},
                type: "GET"
            }).done(function(data){
                $("#display").append(data);

            });  

        

            

                      

              

            });

            
        }//end of validatetime
            




                

        


    
</script>


            <!--TIME PICKER END HERE-->
                 


                

                <div id="special" style="width:87%; float:right; font:bold 14px Tahoma; color:#FF0000;">
                    <input type="hidden" id="hd" name="hd" value="0">
                </div>

            </td>
        </tr>
        <tr bgcolor="#f6f6f6">
            <td><font color="red"><b>*</b><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDDepartment; ?></td>
            <td>
                <select name="to_dept_nr" id="to_dept_nr" onchange="validateTime();">
                    <option value="">===Select a Department===</option>
                    <?php
                    while (list($x, $v) = each($deptarray)) {
                        echo '
                <option value="' . $v['nr'] . '" ';
                            $to_dept_nr=(isset($to_dept_nr) ? $to_dept_nr : null);
                        if ($v['nr'] == $to_dept_nr)
                            echo 'selected';
                        echo ' >';
                        if (isset($$v['LD_var']) && !empty($$v['LD_var']))
                            echo $$v['LD_var'];
                        else
                            echo $v['name_formal'];
                        echo '</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>

        <tr bgcolor="#f6f6f6">
            <td><font color="red"><b></b><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo "$LDPhysician/$LDClinician"; ?></td>
            <td><select name="to_personell_name"><option>===Select a Doctor===</option>
                    <?php
                    $sql = "select UPPER(name_last) as name_last, CONCAT(name_first,'  ', name_2) AS name_first from care_person left join care_personell on care_person.pid=care_personell.pid where care_personell.job_function_title=17";
                    $doctors = $db->Execute($sql);
                    while ($doctor_list = $doctors->FetchRow()) {
                        if (($doctor_list[0] . ' ' . $doctor_list[1]) == $to_personell_name) {
                            echo '<option selected value="' . $doctor_list[0] . ' ' . $doctor_list[1] . '">' . $doctor_list[0] . ' ' . $doctor_list[1] . '</option>';
                        } else {
                            echo '<option value="' . $doctor_list[0] . ' ' . $doctor_list[1] . '">' . $doctor_list[0] . ' ' . $doctor_list[1] . '</option>';
                        }
                    }
                    ?>
                </select></td>
        </tr>

        <tr bgcolor="#f6f6f6">
            <td><font color="red"><b>*</b><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDPurpose; ?></td>
            <td><textarea name="purpose"  style="font-size: 15pt" cols=40 rows=6 wrap="physical"><?php if (isset($purpose)) echo $purpose; ?></textarea>
            </td>
        </tr>
        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDUrgency; ?></td>
            <td><FONT SIZE=-1  FACE="Arial" color="#000066">
            <?php $urgency=(isset($urgency) ? $urgency : null); ?>
                <input type="radio" name="urgency" value="0" <?php if ($urgency == 0) echo 'checked'; ?>><?php echo $LDNormal; ?>
                <input type="radio" name="urgency" value="3" <?php if ($urgency == 3) echo 'checked'; ?>><?php echo $LDPriority; ?>
                <input type="radio" name="urgency" value="5" <?php if ($urgency == 5) echo 'checked'; ?>><?php echo $LDUrgent; ?>
                <input type="radio" name="urgency" value="7" <?php if ($urgency == 7) echo 'checked'; ?>><?php echo $LDEmergency; ?>
            </td>
        </tr>
        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDRemindPatient; ?> ?</td>
            <td><FONT SIZE=-1  FACE="Arial" color="#000066">
                <input type="radio" name="remind" value="1" <?php if (isset($remind)) echo 'checked'; ?>> <?php echo $LDYes; ?> <input type="radio" name="remind" value="0"   <?php if (!isset($remind)) echo 'checked'; ?>> <?php echo $LDNo; ?>
            </td>
        </tr>
        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDRemindBy; ?></td>
            <td><FONT SIZE=-1  FACE="Arial" color="#000066">
                <input type="checkbox" name="remind_email" value="1"   <?php if (isset($remind_email)) echo 'checked'; ?>><?php echo $LDEmail; ?>
                <input type="checkbox" name="remind_phone" value="1"  <?php if (isset($remind_phone)) echo 'checked'; ?>><?php echo $LDPhone; ?>
                <input type="checkbox" name="remind_mail" value="1"  <?php if (isset($remind_mail)) echo 'checked'; ?>><?php echo $LDMail; ?>
            </td>
        </tr>
        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDPlannedEncType; ?></td>
            <td><FONT SIZE=-1  FACE="Arial" color="#000066">
                <?php
                if (is_object($encounter_classes)) {
                    while ($result = $encounter_classes->FetchRow()) {
                        $encounter_class_nr=(isset($encounter_class_nr) ? $encounter_class_nr : null);
                        ?>
                        <input name="encounter_class_nr" type="radio"  value="<?php echo $result['class_nr']; ?>" <?php if ($encounter_class_nr == $result['class_nr']) echo 'checked'; ?>>
                        <?php
                        $LD = $result['LD_var'];
                        if (isset($$LD) && !empty($$LD))
                            echo $$LD;
                        else
                            echo $result['name'];
                        echo '&nbsp;';
                    }
                }
                ?>
            </td>
        </tr>

    </table>
    <input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
    <input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
    <?php
    if ($mode == 'select') {
        ?>
        <input type="hidden" name="nr" value="<?php echo $nr; ?>">
        <?php
    }
    ?>

    <input type="hidden" name="mode" value="<?php
    if ($mode == 'select')
        echo 'update';
    else
        echo 'create';
    ?>">
    <input type="hidden" name="target" value="<?php echo $target; ?>">
    <input id="save" type="image" <?php echo createLDImgSrc($root_path, 'savedisc.gif', '0'); ?>>

</form>


 
 
</body>
</html>


