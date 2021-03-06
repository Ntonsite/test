<table border=0 cellpadding=4 cellspacing=10 width=100% class="frame">
	<?php
	$dbtable = 'care_op_med_doc';

    $sql = "SELECT * FROM care_op_med_doc op 
    INNER JOIN care_encounter ce ON ce.encounter_nr=op.encounter_nr
    INNER JOIN care_person cp ON cp.pid=ce.pid WHERE cp.pid='".$pid."'
    ";

    $result_op=$db->Execute($sql);

     

    while ($row_op=$result_op->FetchRow()) {
    	if ($toggle)
            $bgc = '#f3f3f3';
        else
            $bgc = '#FFFFCC';
        $toggle = !$toggle;

        ?>
        

        <tr bgcolor="<?php echo $bgc; ?>" valign="top">
           <td>OP Nr.:<br></td><td><font color="#800000"><?php echo $row_op['nr'];  ?></td>


        </tr>
        <tr bgcolor="<?php echo $bgc; ?>" valign="top">
           <td>Operation date:</td><td><font color="#800000"><?php echo date('d/m/Y',strtotime($row_op['op_date'])).' '.'Surgeon:'.' '.$row_op['operator'];  ?></td>


        </tr>

        <tr bgcolor="<?php echo $bgc; ?>" valign="top">
           <td>admission Nr:</td><td><FONT color="#000099"><?php echo $row_op['encounter_nr']; ?></td>
           

        </tr>
        <tr bgcolor="<?php echo $bgc; ?>" valign="top">
           <td>Diagnosis/ICD-10:</td><td><font color="#800000"><?php echo $row_op['diagnosis']; ?></td>
           

        </tr>

        <tr bgcolor="<?php echo $bgc; ?>" valign="top">
           <td>Localization:</td><td><font color="#800000"><?php echo $row_op['localize']; ?></td>
           

        </tr>

        <tr bgcolor="<?php echo $bgc; ?>" valign="top">
           <td>Therapy:</td><td><font color="#800000"><?php echo $row_op['therapy']; ?></td>
           

        </tr>

         <tr bgcolor="<?php echo $bgc; ?>" valign="top">
           <td>Special notes:</td><td><font color="#800000"><?php echo $row_op['special']; ?></td>
           

        </tr>

        <tr bgcolor="<?php echo $bgc; ?>" valign="top">
           <td>Post OP Orders:</td><td><font color="#800000"><?php echo $row_op['postorder']; ?></td>
           

        </tr>

        <tr bgcolor="<?php echo $bgc; ?>" valign="top">
           <td>Classification:</td><font color="#800000"><td>operation</td>
           

        </tr>

        <tr bgcolor="<?php echo $bgc; ?>" valign="top">
           <td colspan="2">OP Start:OP Start:<font color="#0"><font color="#800000"><?php echo $row_op['op_start'].'&nbsp;&nbsp;<font color="#0">'.'OP End:<font color="#800000">'.$row_op['op_end'].'&nbsp;<font color="#0">'.'Anaesthetist:<font color="#800000">'.$row_op['anasthetist'].'&nbsp;<font color="#0">'.' Scrub nurse:<font color="#800000">'.$row_op['scrub_nurse'].'&nbsp;<font color="#0">'.' Assistant:<font color="#800000">'.$row_op['assistant'].'<font color="#0">'.' OP Room:&nbsp;<font color="#0"><font color="#800000">' .$row_op['op_room'];?></td>
           

        </tr>



    	
    <?php	
    }

    


	?>
</table>