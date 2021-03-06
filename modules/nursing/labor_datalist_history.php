<?php

$pendingResults = array();
$labResults = array();

//$sql_lab="SELECT lab.test_date,lab.paramater_name,lab.parameter_value FROM care_test_findings_chemlabor_sub AS lab WHERE lab.encounter_nr IN (SELECT encounter_nr FROM care_encounter WHERE pid=".$_SESSION['sess_pid'].") ORDER BY lab.test_date ";
$sql_lab = "SELECT DISTINCT lab.test_date, labpar.name AS paramater_name,lab.parameter_value,labpar.lo_bound,labpar.hi_bound,lab.encounter_nr, lab.job_id FROM care_test_findings_chemlabor_sub lab, care_tz_laboratory_param labpar"
	. " WHERE lab.paramater_name=labpar.id AND lab.encounter_nr IN (SELECT encounter_nr FROM care_encounter WHERE pid='" . $pid . "') ORDER BY lab.test_date DESC, lab.sort_order";

	

$lab_result = $db->Execute($sql_lab);
if (@$lab_result && $lab_result->RecordCount() > 0) {
	$labResults = $lab_result->GetArray();
}

// Pending lab requests $pn (encounter number)

$pendingLabSQL = "SELECT DISTINCT labpar.name AS paramater_name, lab.status, lab.sub_id, lab.bill_number, lab.parameter_value, lab.encounter_nr, lab.batch_nr FROM care_test_request_chemlabor_sub lab, care_tz_laboratory_param labpar"
	. " WHERE lab.paramater_name=labpar.id AND lab.bill_number = 0 AND lab.deleted = 0 AND lab.encounter_nr IN (SELECT encounter_nr FROM care_encounter WHERE pid='" . $pid . "') ORDER BY  lab.batch_nr DESC, lab.sort_order";
$pendingLabResult = $db->Execute($pendingLabSQL);

if (@$pendingLabResult && $pendingLabResult->RecordCount() > 0) {
	$pendingResults = $pendingLabResult->GetArray();
}

foreach ($pendingResults as $key => $pendingResult) {
	foreach ($labResults as $labResult) {
		if ($labResult['job_id'] == $pendingResult['batch_nr'] && $labResult['paramater_name'] == $pendingResult['paramater_name']) {
			unset($pendingResults[$key]);
		}
	}
}

?>
<?php $no = 1;if (count($pendingResults) > 0): ?>

<table border=0 width="100%" bgcolor="#666666" cellpadding=3 cellspacing=1>
	<tr bgcolor="#CAD3EC" >
		<td class="va12_n" colspan="10"><h4>Pending Lab Tests</h4></td>
	</tr>

	<tr bgcolor="#CAD3EC" >
		<td class="va12_n"><font color="#000"> &nbsp;<b>SN</b>
		<td class="va12_n"><font color="#000"> &nbsp;<b>Parameter name</b>
		</td>
		<td  class="j"><font color="#000">&nbsp;<b>Delete</b>&nbsp;</td>
	</tr>

	<?php foreach ($pendingResults as $pendingResult): ?>
		<tr bgcolor="#CAD3EC" >
			<td class="va12_n"><font color="#000"> &nbsp; <?php echo $no++ ?></font>
			<td class="va12_n"><font color="#000"> &nbsp; <?php echo $pendingResult['paramater_name'] ?> </font></td>
			<td  class="j"><font color="#000">&nbsp; <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')? deleteLabTest(<?php echo $pendingResult['sub_id'] ?>):'';" >Delete</button> </td>
		</tr>
	<?php endforeach?>

</table>
<?php endif?>


<?php
//echo $_REQUEST['pn'];
//RecordCount()

//LABORATORY COMMENT
 $SQL_labcomment="SELECT lab_comment,batch_nr FROM care_test_findings_chemlab
  WHERE encounter_nr='".$_REQUEST['pn']."' AND lab_comment<>''";
$commentResult=$db->Execute($SQL_labcomment);
 
 if ($commentResult->RecordCount()>0) {
 	$header='<tr bgcolor="white">
		<td colspan="10"><h4>Lab Comment</h4></td>
	</tr>';


	// '<div style="width:100%;">' .
	// 		'' . nl2br($this->row[1]) . '' .
	// 			'</div>'


      $header.='<tr bgcolor="white"><td>';
	while ($commentRow=$commentResult->FetchRow()) {
           $header.=nl2br($commentRow['lab_comment']);
		
	}

	$header.='</td></tr>';

 }






?>


<table border=0 width="100%" bgcolor="#666666" cellpadding=3 cellspacing=1>
	<tr bgcolor="#D2DFD0" >
	</tr>

</table>


<table border=0 width="100%" bgcolor="#666666" cellpadding=3 cellspacing=1>
	<?php
	echo $header;
	?>
	
	<tr bgcolor="#D2DFD0" >
		<td class="va12_n" colspan="10"><h4>Lab Tests Findings</h4></td>
	</tr>

</table>

<?php
$lab_result = $db->Execute($sql_lab);
echo '
<form action="labor-data-makegraph.php" method="post" name="labdata">
<table border=0 width="100%" bgcolor="#666666" cellpadding=3 cellspacing=1>';

echo '
		<tr bgcolor="#D2DFD0" >
		<td class="va12_n"><font color="#000"> &nbsp;<b>test date</b>
		<td class="va12_n"><font color="#000"> &nbsp;<b>Parameter name</b>
		</td>
		<td  class="j"><font color="#000">&nbsp;<b>value</b>&nbsp;</td>
		<td  class="j"><font color="#000">&nbsp;<b>Normal Range</b>&nbsp;</td>
		</tr>
		';
if (@$lab_result && $lab_result->RecordCount()) {
	while ($parameter = $lab_result->FetchRow()) {
		echo '
		<tr bgcolor="#D2DFD0" >
		<td class="va12_n"><font color="#000"> &nbsp;' . $parameter['test_date'] . '
		<td class="va12_n"><font color="#000"> &nbsp;' . $parameter['paramater_name'] . '
		</td>
		<td  class="j"><font color="#000">&nbsp;' . $parameter['parameter_value'] . '&nbsp;</td>
		<td  class="j"><font color="#000">&nbsp;' . $parameter['lo_bound'].'-' .$parameter['hi_bound']. '&nbsp;</td>
		</tr>
		';
	}
}

echo '</table>';
echo '</form>';
?>

<script>

	function deleteLabTest(sub_id) {
		$.getJSON("./deleteLabTest.php?sub_id="+sub_id).done(function(data){

        if (data.deleted) {
            window.location.reload();
        }

        }).fail(function(data){
          alert('Unable to delete Lab Test. Please try again');
        })
	}
</script>
<script>

	function deleteRadTest(batch) {
		$.getJSON("./deleteRadTest.php?batch_nr="+batch).done(function(data){

        if (data.deleted) {
            window.location.reload();
        }

        }).fail(function(data){
          alert('Unable to delete Lab Test. Please try again');
        })
	}
</script>
