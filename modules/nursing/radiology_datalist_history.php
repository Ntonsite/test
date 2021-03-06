<?php

$rad_pending_Results = array();
$rad_findings_Results = array();

//$sql_lab="SELECT lab.test_date,lab.paramater_name,lab.parameter_value FROM care_test_findings_chemlabor_sub AS lab WHERE lab.encounter_nr IN (SELECT encounter_nr FROM care_encounter WHERE pid=".$_SESSION['sess_pid'].") ORDER BY lab.test_date ";
$sql_rad_findings = "SELECT DISTINCT rad.findings_date as test_date, ds.item_description AS paramater_name,rad.findings as parameter_value,rad.encounter_nr, rad.batch_nr as job_id,doctor_id FROM care_test_findings_radio rad, care_tz_drugsandservices ds,care_test_request_radio rrad"
	. " WHERE rrad.test_request=ds.item_id AND rad.encounter_nr=rrad.encounter_nr AND rad.encounter_nr IN (SELECT encounter_nr FROM care_encounter WHERE pid='" . $pid . "') ORDER BY rad.findings_date DESC";

	//echo $sql_rad_findings;die;
	

$rad_findings_result = $db->Execute($sql_rad_findings);


//echo "<pre>"; print_r($rad_findings_row); echo "</pre>";



// Pending lab requests $pn (encounter number)

$sql_rad_pending = "SELECT DISTINCT ds.item_description AS paramater_name, rrad.status,rrad.bill_number,  rrad.encounter_nr, rrad.batch_nr FROM care_test_request_radio rrad, care_tz_drugsandservices ds"
	. " WHERE rrad.test_request=ds.item_id AND rrad.bill_number = 0 AND rrad.is_deleted = 0 AND rrad.encounter_nr IN (SELECT encounter_nr FROM care_encounter WHERE pid='" . $pid . "') AND batch_nr NOT IN(SELECT batch_nr FROM care_test_findings_radio) ORDER BY  rrad.batch_nr DESC";

 


$rad_pending_Results = $db->Execute($sql_rad_pending);

// if (@$rad_pending_Results && $rad_pending_Results->RecordCount() > 0) {
// 	$rad_pending_row = $rad_pending_Results->GetArray();
// }

//echo "<pre>";print_r($rad_pending_row);echo "</pre>";die;




// foreach ($pendingResults as $key => $pendingResult) {
// 	foreach ($radResult as $radResult) {
// 		if ($radResult['job_id'] == $pendingResult['batch_nr'] && $radResult['paramater_name'] == $pendingResult['paramater_name']) {
// 			echo "<pre>";print_r($key); echo "</pre>";die;
// 			unset($pendingResults[$key]);
// 		}
// 	}
// }


?>
<?php $no = 1;if (count($rad_pending_Results) > 0): ?>

<table border=0 width="100%" bgcolor="#666666" cellpadding=3 cellspacing=1>
	<tr bgcolor="#CAD3EC" >
		<td class="va12_n" colspan="10"><h4>Pending Radiology Tests</h4></td>
	</tr>

	<tr bgcolor="#CAD3EC" >
		<td class="va12_n"><font color="#000"> &nbsp;<b>SN</b>
		<td class="va12_n"><font color="#000"> &nbsp;<b>Parameter name</b>
		</td>
		<td  class="j"><font color="#000">&nbsp;<b>Delete</b>&nbsp;</td>
	</tr>

     
	<?php foreach ($rad_pending_Results as $pendingResult): ?>


		<tr bgcolor="#CAD3EC" >
			<td class="va12_n"><font color="#000"> &nbsp; <?php echo $no++ ?></font>
			<td class="va12_n"><font color="#000"> &nbsp; <?php echo $pendingResult['paramater_name'] ?> </font></td>
			<td  class="j"><font color="#000">&nbsp; <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')? deleteRadTest(<?php echo $pendingResult['batch_nr'] ?>):'';" >Delete</button> </td>
		</tr>
	<?php endforeach?>

</table>
<?php endif?>


<table border=0 width="100%" bgcolor="#666666" cellpadding=3 cellspacing=1>
	
	
	<tr bgcolor="#D2DFD0" >
		<td class="va12_n" colspan="10"><h4>Lab Tests Findings</h4></td>
	</tr>

</table>

<?php
//$lab_result = $db->Execute($sql_lab);
echo '
<form action="labor-data-makegraph.php" method="post" name="labdata">
<table border=0 width="100%" bgcolor="#666666" cellpadding=3 cellspacing=1>';

echo '
		<tr bgcolor="#D2DFD0" >
		<td class="va12_n"><font color="#000"> &nbsp;<b>test date</b>
		<td class="va12_n"><font color="#000"> &nbsp;<b>Parameter name</b>
		</td>
		<td  class="j"><font color="#000">&nbsp;<b>Findings</b>&nbsp;</td>
		<td  class="j"><font color="#000">&nbsp;<b>Entered By</b>&nbsp;</td>
		</tr>
		';
if (@$rad_findings_result && $rad_findings_result->RecordCount()) {
	foreach ($rad_findings_result as $rad_findings) {
		//echo "<pre>"; print_r($rad_findings); echo "</pre>";
		echo '
		<tr bgcolor="#D2DFD0" >
		<td class="va12_n"><font color="#000"> &nbsp;' . $rad_findings['test_date'] . '
		<td class="va12_n"><font color="#000"> &nbsp;' . $rad_findings['paramater_name'] . '
		</td>
		<td  class="j"><font color="#000">&nbsp;' . $rad_findings['parameter_value'] . '&nbsp;</td>

		<td  class="j"><font color="#000">&nbsp;' . $rad_findings['doctor_id'] . '&nbsp;</td>
		
		';
	}
}

echo '</table>';
echo '</form>';
?>


