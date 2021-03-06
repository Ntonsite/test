<?php
require_once $root_path . 'include/care_api_classes/class_prescription.php';
if (!isset($pres_obj)) {
	$pres_obj = new Prescription;
}

require_once $root_path . 'include/care_api_classes/class_person.php';
$person_obj = new Person;

require_once $root_path . 'include/care_api_classes/class_encounter.php';
require_once $root_path . 'include/care_api_classes/class_tz_billing.php';
require_once $root_path . 'include/care_api_classes/class_tz_insurance.php';
$bill = new Bill();

$thisfile = basename($_SERVER['PHP_SELF']);

echo '<script type="text/javascript">';

echo 'function reCalculate(tl,s,t,d){';
echo '	tl.value= s.value*t.value*d.value;';
echo '}';
echo '</script>';

$thisURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (empty($encounter_nr) and !empty($pid)) {
	$encounter_nr = $person_obj->CurrentEncounter($pid);
}

$debug = false;
if ($debug) {
	if (!empty($back_path)) {
		$backpath = $back_path;
	}

	echo "file: show_prescription<br>";
	if (!isset($externalcall)) {
		echo "internal call<br>";
	} else {
		echo "external call<br>";
	}

	echo "mode=" . $mode . "<br>";

	echo "show=" . $show . "<br>";

	echo "nr=" . $nr . "<br>";

	echo "breakfile: " . $breakfile . "<br>";

	echo "backpath: " . $backpath . "<br>";

	echo "pid:" . $pid . "<br>";

	echo "encounter_nr:" . $encounter_nr . "<br>";

	echo "Session-ecnounter_nr: " . $_SESSION['sess_en'];
}
$pres_types = $pres_obj->getPrescriptionTypes();
?>

<script language="javascript">


    function chkform(d) {

        var dosage_item;
        var times_item;
        var days_item;
        var total_dosage_item;
        var pres_item = document.getElementById('prescr_count');
        var pres_count = pres_item.value;
       


	

        for (i = 0; i < pres_count; i++) {



        	
            isNHIFRestricted=$("#isNHIFRestricted_"+i).val();
            dosage_item = document.getElementById('dosage' + i);
            times_item = document.getElementById('timesperday' + i);
            days_item = document.getElementById('days' + i);
            total_dosage_item = document.getElementById('total_dosage' + i);

            if (isNHIFRestricted=='Yes') {
                var cardno = $("#cardNo_"+i).val();
                var inputid =$("#inputid_"+i).val();
                var nhifitemcode=$("#itemcode_"+i).val();
                var ReferenceNo=$("#refNo_"+i).val();

            
            var isValid=verify_nhif_approval(cardno, ReferenceNo, nhifitemcode);

            

                 if (isValid===false) {
            	alert("Reference Number "+ReferenceNo+" Is not valid");
            	return false;
                 }else
                 if (isValid===true) {
                 	alert("reference number is valid it will be sent to NHIF");
                 }           


            }

            



            





            


            

            if ((dosage_item.value == "") || (dosage_item.value < 0))
            {
                alert("Please enter dosage for prescription item " + (i + 1));
                return false;

            } else
            if ((times_item.value == "") || (times_item.value < 0))
            {
                alert("Please enter times per day for prescription item " + (i + 1));
                return false;

            } else
            if ((days_item.value == "") || (days_item.value < 0))
            {
                alert("Please enter days for prescription item " + (i + 1));
                return false;

            } else
            if ((total_dosage_item.value == "") || (total_dosage_item.value < 0) || (total_dosage_item.value == 0))
            {
                alert("Please enter total dosage for prescription item " + (i + 1));
                return false;

            } else
            if (isNaN(dosage_item.value))
            {
                alert("Wrong value,enter only numbers for single dose/items for prescription " + (i + 1));
                return false;

            } else
            if (isNaN(total_dosage_item.value))
            {
                alert("Wrong value,enter only numbers for total items for prescription " + (i + 1));
                return false;

            }


            

        }

        $("#save").attr("disabled", true);
        


          

    }

</script>






<form method="post" action="" name="prescform" onSubmit = "return chkform(this)">

    <input type="hidden" name="backpath" value="<?php echo $backpath; ?>">

<?php
if (!$nr) {
	$item_array = $_SESSION['item_array'];
} else {
	$prescriptionitem = $pres_obj->GetPrescritptionItem($nr);
	$item_array = [];
	$item_array[0] = $prescriptionitem['article_item_number'];
	echo '<input type="hidden" value="' . $nr . '" name="nr">';
}

// echo "-->items in array: ".count($item_array)."<br>";#
for ($i = 0; $i < count($item_array); $i++) {
	$class = $pres_obj->GetClassOfItem($item_array[$i]);
	$sub_class = $pres_obj->GetSubClassOfItem($item_array[$i]);

	if ($nexttime) {
		$prescriptionitem['total_dosage'] = "";
		$nexttime = false;
	}

	if ($class == 'drug_list' || $class == 'special_others_list' || $class == 'special_ctc_list' || $class == 'drug_list_ctc' || $class == 'drug_list_nhif') {
		//label for type of drugs
		if ($sub_class == 'tabs') {
			$caption_total = 'tabs';
			$caption_dose = 'tabs';
		} else

		if ($sub_class == 'caps' || $sub_class == 'capsules') {
			$caption_total = 'capsules';
			$caption_dose = 'capsules';
		} else

		if ($sub_class == 'syrups' || $sub_class == 'suspensions') {
			$caption_total = 'bottles';
			$caption_dose = 'mls';
		} else

		if ($sub_class == 'injections') {
			$caption_total = 'injections';
		} else {
			$caption_total = 'items';
		}

		$caption_dosage = 'Single dose(per intake)';
		//end label for type of drugs
	} else {
		//below items are not drugs
		if ($class == 'dental' || $class == 'eye-service' || $class == 'minor_proc_op' || $class == 'obgyne_op' || $class == 'ortho_op' || $class == 'surgical_op') {

			$caption_dosage = 'Number of Procedures';
			if (!$prescriptionitem['total_dosage']) {
				$prescriptionitem['total_dosage'] = 1;
			}

		} else

		if ($class == 'service') {
			$caption_dosage = 'Amount/Items';
		} else {
			$caption_dosage = 'Total Amount/Items';
		}

		$nexttime = true;
	}
	?>

        <div id="prescription_item_<?php echo $i ?>">
        <font class="adm_div"><?php echo $pres_obj->GetNameOfItem($item_array[$i]); ?></font>

        <table border=0 cellpadding=2 width=100%>
            <?php

$isNHIFRestricted = $pres_obj->isNHIFRestristed($item_array[$i]);
	if ($isNHIFRestricted && $pres_obj->isNHIFMember()) {
		$bgcolor = "#B6EE56";
	} else {
		$bgcolor = '#f6f6f6';
	}
	?>
                 <tr bgcolor="<?php echo $bgcolor ?>">
                <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $caption_dosage; ?></td>

                <td>
                <?php
//select "dosage"

	if ($caption_dosage == 'Single dose(per intake)') {
		if ($sub_class == 'tabs' || $sub_class == 'caps' || $sub_class == 'capsules' || $sub_class == 'injections') {

			echo '<select class="presptionSelect" class="presptionSelect" id="dosage' . $i . '" name="arr_dosage[' . $i . ']" onChange=reCalculate(total_dosage' . $i . ',dosage' . $i . ',timesperday' . $i . ',days' . $i . ')>';
		} else

		if ($sub_class == 'syrups' || $sub_class == 'suspensions') {

			echo '<select class="presptionSelect" class="presptionSelect" id="dosage' . $i . '" name="arr_dosage[' . $i . ']"> ';
		} else

		if ($sub_class == 'injections1111') {

			echo '<input type="text" id="dosage' . $i . '" name="arr_dosage[' . $i . ']" size=5  value = "' . $prescriptionitem['dosage'] . '">';
		} else {
			echo '<input type="hidden" id="dosage' . $i . '" name="arr_dosage[' . $i . ']" value="1">';
		}

		if ($sub_class == 'tabs' || $sub_class == 'syrups' || $sub_class == 'suspensions' || $sub_class == 'caps' || $sub_class == 'capsules' || $sub_class == 'injections') {

			$dosageUnits = array(
				"" => "",
				"0.1" => "1 / 10",
				"0.25" => "1 / 4",
				"0.5" => "1 / 2",
				"0.75" => "3 / 4",
				"1" => "1",
				"1.25" => "1 + 1 / 4",
				"1.5" => "1 + 1 / 2",
				"1.75" => "1 + 3 / 4",
				"2" => "2",
				"2.25" => "2 + 1 / 4",
				"2.5" => "2 + 1 / 2",
				"3" => "3",
				"4" => "4",
				"5" => "5",
				"6" => "6",
				"7" => "7",
				"8" => "8",
				"9" => "9",
				"10" => "10",
				"15" => "15",
				"20" => "20",
				"25" => "25",
				"30" => "30",
			);

			foreach ($dosageUnits as $dec => $fract) {
				//preselect "1" in case of a new entry or the old value in case of an edit
				if (($prescriptionitem['dosage'] == $dec) || ((!$nr) && ($dec == "-1"))) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}

				echo '<option value="' . $dec . '" ' . $selected . '>' . $fract . '</option>';
			}

			echo '</select>';

			echo $caption_dose;
		}

		if (isset($nr) && ($prescrServ != 'serv')) {
			echo '(' . $prescriptionitem['dosage'] . ')&nbsp;&nbsp;&nbsp;';
		}

	} else {

		echo '<input type="hidden" id="dosage' . $i . '" name="arr_dosage[' . $i . ']" value="1">';
	}
	?>
                &nbsp;&nbsp;&nbsp;

                <?php
//select "times_per_day"

	if ($caption_dosage == 'Single dose(per intake)') {

		echo '<FONT SIZE=-1  FACE="Arial" color="#000066"> Times per day :  </FONT>';

		if ($sub_class == 'tabs' || $sub_class == 'caps' || $sub_class == 'capsules' || $sub_class == 'injections') {

			echo '<select class="presptionSelect" class="presptionSelect" id="timesperday' . $i . '" name="arr_timesperday[' . $i . ']" onChange=reCalculate(total_dosage' . $i . ',dosage' . $i . ',timesperday' . $i . ',days' . $i . ')>';
		} else {

			echo '<select class="presptionSelect" class="presptionSelect" id="timesperday' . $i . '" name="arr_timesperday[' . $i . ']">';
		}

		$timesperdayUnits = array('', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10');

		foreach ($timesperdayUnits as $unit) {
			//preselect "1" in case of a new entry or the old value in case of an edit
			if (($prescriptionitem['times_per_day'] == $unit) || ((!$nr) && ($unit == "-1"))) {
				$selected = 'selected="selected"';
			} else {
				$selected = '';
			}

			echo '<option value="' . $unit . '" ' . $selected . '>' . $unit . '</option>';
		}

		echo '</select>';
	} else {
		echo '<input type="hidden" id="timesperday' . $i . '" name="arr_timesperday[' . $i . ']" value="1">';
	}

	if (isset($nr) && ($prescrServ != 'serv') && ($prescrServ != 'proc')) {
		echo '(' . $prescriptionitem['times_per_day'] . ')&nbsp;&nbsp;&nbsp;'
		;
	}
	?>

                &nbsp;&nbsp;&nbsp;
                <?php
//select "days"

	if ($caption_dosage == 'Single dose(per intake)') {

		echo '<FONT SIZE=-1  FACE="Arial" color="#000066">  Days : </FONT>';

		if ($sub_class == 'tabs' || $sub_class == 'caps' || $sub_class == 'capsules' || $sub_class == 'injections') {

			echo '<select class="presptionSelect" id="days' . $i . '" name="arr_days[' . $i . ']" onChange=reCalculate(total_dosage' . $i . ',dosage' . $i . ',timesperday' . $i . ',days' . $i . ')>';
		} else {

			echo '<select class="presptionSelect" id="days' . $i . '" name="arr_days[' . $i . ']">';
		}

		$dayUnits[0] = '';

		for ($daycounter = 1; $daycounter < 121; $daycounter++) {
			$dayUnits[$daycounter] = $daycounter;
		}

		foreach ($dayUnits as $unit) {
			//preselect "1" in case of a new entry or the old value in case of an edit
			if (($prescriptionitem['days'] == $unit) || ((!$nr) && ($unit == "-1"))) {
				$selected = 'selected="selected"';
			} else {
				$selected = '';
			}

			echo '<option value="' . $unit . '" ' . $selected . '>' . $unit . '</option>';
		}

		echo '</select>';
	} else {
		echo '<input type="hidden" id="days' . $i . '" name="arr_days[' . $i . ']" value="1">';
	}

	if (isset($nr) && ($prescrServ != 'serv') && ($prescrServ != 'proc')) {
		echo '(' . $prescriptionitem['days'] . ')&nbsp;&nbsp;&nbsp;'
		;
	}
	?>
                &nbsp;&nbsp;&nbsp;
                <?php
//select "total dose"

	if ($caption_dosage == 'Single dose(per intake)') {

		echo '<FONT SIZE=-1  FACE="Arial" color="#000066">  Total Dose/Items : </FONT>';

		if ($sub_class == 'tabs' || $sub_class == 'caps' || $sub_class == 'capsules' || $sub_class == 'injections') {

			echo '<input type="text" id="total_dosage' . $i . '" name="arr_total_dosage[' . $i . ']" size=5 readonly=true>';
		} else {

			echo '<select class="presptionSelect" id="total_dosage' . $i . '" name="arr_total_dosage[' . $i . ']">';

			$totalDoseUnits[0] = '';
			for ($doseCounter = 1; $doseCounter < 11; $doseCounter++) {
				$totalDoseUnits[$doseCounter] = $doseCounter;
			}

			foreach ($totalDoseUnits as $td_unit) {
				//preselect "1" in case of a new entry or the old value in case of an edit
				if (($prescriptionitem['total_dosage'] == $td_unit) || ((!$nr) && ($td_unit == "-1"))) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}

				echo '<option value="' . $td_unit . '" ' . $selected . '>' . $td_unit . '</option>';
			}

			echo '</select>';
		}

		echo $caption_total;
	} else {

		if ($caption_dosage == 'Total Amount/Items') {

			echo '<select class="presptionSelect" id="total_dosage' . $i . '" name="arr_total_dosage[' . $i . ']">';

			$totalDoseUnits[0] = '';
			for ($doseCounter = 1; $doseCounter < 121; $doseCounter++) {
				$totalDoseUnits[$doseCounter] = $doseCounter;
			}

			foreach ($totalDoseUnits as $td_unit) {
				//preselect "1" in case of a new entry or the old value in case of an edit
				if (($prescriptionitem['total_dosage'] == $td_unit) || ((!$nr) && ($td_unit == "-1"))) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}

				echo '<option value="' . $td_unit . '" ' . $selected . '>' . $td_unit . '</option>';
			}

			echo '</select>';
		} else {
			echo '<input type="text" id="total_dosage' . $i . '" name="arr_total_dosage[' . $i . ']" size=50 maxlength=60 value = "' . $prescriptionitem['total_dosage'] . '">';
		}

	}

	if (isset($nr) && ($prescrServ != 'serv') && ($prescrServ != 'proc')) {
		echo '(' . $prescriptionitem['total_dosage'] . ')&nbsp;&nbsp;&nbsp;';
	}

	//work on stock balance
	/*
	                            Below code was written by:-
	                            Israel Pascal
	                            Email: israel@ostech.co.tz; israel.pascal10@gmail.com
	                            Tel: +255 767809660

	                            Date: 14-JUlY-2018

*/

	//I can't figure out why  $GLOBAL_CONFIG['out_of_stock'] is not returned. Im writing query for it
	$sql_notify_doctor = "SELECT value FROM care_config_global WHERE type='auto_out_of_stock'";
	$result_notify = $db->Execute($sql_notify_doctor);
	if ($row_notify = $result_notify->FetchRow()) {
		$notify_doctor_status = $row_notify['value'];
	}
	$transmit_to_weberp_enabled = $glob_obj->getConfigValue('transmit_to_weberp_enabled');
	if ($transmit_to_weberp_enabled == '1' && $notify_doctor_status == '1' && ($class == 'drug_list' || $class == 'special_others_list' || $class == 'special_ctc_list' || $class == 'drug_list_ctc' || $class == 'drug_list_nhif')) {
		//start of purchasing class
		# code...

		if (!isset($weberp_obj)) {
			$weberp_obj = new weberp();
		}
		if (!isset($enc_obj)) {
			$enc_obj = new Encounter();
		}

		// $pharmacy=$enc_obj->GetPharmacy($encounter_nr);
		// $partcode=$pres_obj->GetPartcodeOfItem($item_array[$i]);
		// $Bal=$weberp_obj->get_stock_balance_webERP($partcode);

		// for ($j=0; $j<sizeof($Bal) ; $j++) {
		//   if($Bal[$j]['loccode']==$pharmacy){
		//     //echo $Bal[$j]['quantity'];
		//     echo '<strong> Stock Bal.:<input type="text" readonly name="balance'.$j.'" id="balance'.$j.'"  value="'.$Bal[$j]['quantity'].'" size="5" ></strong>';

		//   }
		// }

		//stuff must start here

		//Check stock location in weberp and care2x
		$care2x_pharmacy = $enc_obj->GetPharmacy($encounter_nr); //loc. to pick drug for this patient.
		$pharmacy_name = $enc_obj->GetPharmacyName($encounter_nr);
		$partcode_c2x = $partcode = $pres_obj->GetPartcodeOfItem($item_array[$i]);
		//echo 'c2x_partcode'.$partcode_c2x;

		$item = $weberp_obj->get_stock_item_from_webERP($partcode_c2x);
		// print_r($item);
		if (!empty($care2x_pharmacy)) {
			//Pharmacy tagged to this patient

			// echo 'c2x partcode'.$partcode_c2x.'<br>';
			// echo 'weberp stockid'.$item['stockid'];

			if ($partcode_c2x == $item['stockid']) {
				//item match
				$Bal = $weberp_obj->get_stock_balance_webERP($partcode);

				for ($j = 0; $j < sizeof($Bal); $j++) {
					if ($Bal[$j]['loccode'] == $care2x_pharmacy) {
						$Balance = $Bal[$j]['quantity'];

						//now let get minimum level
						$sql_min = "SELECT MAX(min_level) AS min_level FROM care_tz_drugsandservices WHERE partcode='" . $partcode_c2x . "'";

						$result_min = $db->Execute($sql_min);

						if ($min_row = $result_min->FetchRow()) {
							$min_level = $min_row['min_level'];

							if ($Balance > $min_level) {

								echo '<br><div><font style="font-size: 15px;  font-style: bold;">Stock Balance.=' . $Balance . ' Min_level=' . $min_level . ' This Patient is tagged to pick drugs at: ' . $pharmacy_name . '</font></div>';
							} else {
								echo '<br><div>Stock Balance.=' . $Balance . ' Min_level=' . $min_level . '</div><div><font style="font-size: 15px; color: red; font-style: italic; font-style: bold;">INSURFFICIENT STOCK. THIS DRUG WILL NOT BE SHOWN AT PHARMACY AND BILLING. STOCK IS AT MINIMUM LEVEL.</font></div>';

								echo '<input type="hidden" id="mark_os' . $i . '" name="mark_os[' . $i . ']" value="1">';
							}
						}
					}
				}
			} else {
				//item don't match
				//$partcode_c2x

				echo '<br><div><font style="font-size: 15px;color: orange;  font-style: bold;">THIS DRUG WITH PARTCODE ' . $partcode_c2x . ' NOT IN webERP, PLEASE NOTIFY PHARMACIST.</font></div>';

				?>

                <?php
}
		} else {
			//pharmacy not tagged
			echo 'Patient Not tagged with pharmacy';
		}
	} //end of purchasing class
	if ($pres_obj->isNHIFMember() && $isNHIFRestricted) {
		$cardNo=$pres_obj->nhifCardNumber($pid);
		$nhifItemCode=$pres_obj->nhifItemCode($item_array[$i]);
	?>
    <input type="hidden" name="cardNo_<?php echo $i?>" id="cardNo_<?php echo $i?>" value="<?php echo $cardNo;?>">
    <input type="hidden" name="itemcode_<?php echo $i?>" id="itemcode_<?php echo $i?>" value="<?php echo $nhifItemCode;?>">
    <input type="hidden" name="inputid_<?php echo $i?>" id="inputid_<?php echo $i?>" value="<?php echo $i;?>">
    <input type="hidden" name="isNHIFRestricted_<?php echo $i?>" id="isNHIFRestricted_<?php echo $i?>" value="Yes">
	<?php
					

	}else{
		?>
		<input type="hidden" name="isNHIFRestricted_<?php echo $i?>" id="isNHIFRestricted_<?php echo $i?>" value="No">
		<?php

	}



	?>



            <?php if ($isNHIFRestricted && $pres_obj->isNHIFMember()): ?>
                 <span style="margin-left: 10%; font-size: 15px;">NHIF Approval No:</span>
                <input type="text"   required minlength="4" name="arr_nhifApproval[<?php echo $i; ?>]" id="refNo_<?php echo $i;?>"  placeholder="NHIF Approval Number" onblur="//return verify_nhif_approval(<?php //echo $cardNo.','.$i.','.$nhifItemCode; ?>)">
            <?php endif?>
            
            <input type="hidden" name="approvalStatus_<?php echo $i;?>" id="approvalStatus_<?php echo $i;?>" value="">
            <?php if ($sub_class == 'meal'): ?>
                &nbsp;&nbsp;&nbsp;
                <select name="arr_meal_type[<?php echo $i ?>]" style="width: 10%" id="meal_type" required="">
                    <option value="">--Select Meal Type--</option>
                    <option value="breakfast">Breakfast</option>
                    <option value="lunch">Lunch</option>
                    <option value="supper">Supper </option>
                    <option value="dinner">Dinner </option>
                    <option value="tea">Tea </option>
                    <option value="brunch">Brunch </option>
                    <option value="Beverage">beverage </option>
                    <option value="elevenses">Elevenses </option>
                </select>
            <?php endif?>

            </td>

            </tr>
            <tr bgcolor="#f6f6f6">
                <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDApplication . ' ' . $LDNotes; ?></td>
                <!--<td><textarea name="arr_notes[<?PHP echo $i; ?>]" cols=40 rows=3 wrap="physical"><?php echo $prescriptionitem['notes']; ?></textarea>
                    </td>-->
                <td>
                    <input type="text" style="float: left;" name="arr_notes[<?PHP echo $i; ?>]" size="120"><?php echo $prescriptionitem['notes']; ?>
                    <button type="button" onclick="removePrescriptionItem(<?php echo $i ?>, <?php echo $item_array[$i] ?>)" class="btn btn-info btn-sm" style="float: right; "> Delete</button>

                </td>
            </tr>


            <tr bgcolor="#f6f6f6">
                <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDPrescribedBy; ?></td>
                <td><input type="text" name="prescriber" size=50 maxlength=60 value="<?php echo $_SESSION['sess_user_name']; ?>" readonly></td>
            </tr>
        </table>
        </div>

        <input type="hidden" id= "prescr_count" name="prescr_count" value= "4" >

        <input type="hidden" name="arr_item_number[<?PHP echo $i; ?>]" value="<?PHP echo $i; ?>">

        <input type="hidden" name="arr_article_item_number[<?PHP echo $i; ?>]" value="<?php echo $item_array[$i]; ?>">

        <input type="hidden" name="arr_price[<?PHP echo $i; ?>]" value="<?php echo $pres_obj->GetPriceOfItem($item_array[$i]); ?>">

        <input type="hidden" name="arr_article[<?PHP echo $i; ?>]" value="<?php echo $pres_obj->GetNameOfItem($item_array[$i]); ?>">

        <input type="hidden" name="arr_is_labtest[<?PHP echo $i; ?>]" value="<?php if ($pres_obj->GetClassOfItem($item_no[$i]) == 'lab_test') {
		echo 1;
	} else {
		echo 0;
	}

	?>">

        <input type="hidden" name="arr_is_medicine[<?PHP echo $i; ?>]" value="<?php if ($pres_obj->GetClassOfItem($item_array[$i]) == 'drug_list' || $pres_obj->GetClassOfItem($item_array[$i]) == 'supplies') {
		echo 1;
	} else {
		echo 0;
	}

	?>">

        <input type="hidden" name="arr_is_radio_test[<?PHP echo $i; ?>]" value="<?php if ($pres_obj->GetClassOfItem($item_no[$i]) == 'xray') {
		echo 1;
	} else {
		echo 0;
	}

	?>">

        <input type="hidden" name="arr_is_service[<?PHP echo $i; ?>]" value="<?php
if ($pres_obj->GetClassOfItem($item_array[$i]) == 'service' || $pres_obj->GetClassOfItem($item_array[$i]) == 'dental' ||
		$pres_obj->GetClassOfItem($item_array[$i]) == 'eye-services' || $pres_obj->GetClassOfItem($item_array[$i]) == 'minor_proc_op' || $pres_obj->GetClassOfItem($item_array[$i]) == 'obgyne_op' || $pres_obj->GetClassOfItem($item_array[$i]) == 'ortho_op' || $pres_obj->GetClassOfItem($item_array[$i]) == 'surgical_op') {
		echo 1;
	} else {
		echo 0;
	}

	?>">

        <?php
} // end of loop
?>
    <input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
    <input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
    <?php
if (!$nr) {
	echo '<input type="hidden" name="mode" value="create">';
} else {
	echo '<input type="hidden" name="mode" value="update">';
}

?>
    <input type="hidden" name="history" value="Created: <?php echo date('Y-m-d H:i:s'); ?> : <?php echo $_SESSION['sess_user_name'] . "\n"; ?>">
    <input type="hidden" name="target" value="<?php echo $target; ?>">


<?php
if (isset($externalcall)) {
	?>
        <input type="hidden" name="externalcall" value="<?php echo $externalcall; ?>">
    <?php }?>

        <input type="hidden" name="is_outpatient_prescription" value="1">
        <input type="image" <?php echo createLDImgSrc($root_path, 'savedisc.gif', '0'); ?> id="save">

    </form>


    <?php
/**
 * Second part: Show all prescriptions for this encounter no. since now.
 */
?>

    <table border=0 cellpadding=4 cellspacing=1 width=100% class="frame">
        <?php
$toggle = TRUE;
while ($row = $result->FetchRow()) {
	if ($toggle) {
		$bgc = '#f3f3f3';
	} else {
		$bgc = '#fefefe';
	}

	if ($toggle) {
		$toggle = FALSE;
	} else {
		$toggle = TRUE;
	}

	if ($row['encounter_class_nr'] == 1) {
		$full_en = $row['encounter_nr'] + $GLOBAL_CONFIG['patient_inpatient_nr_adder'];
	}
	// inpatient admission
	else {
			$full_en = $row['encounter_nr'] + $GLOBAL_CONFIG['patient_outpatient_nr_adder'];
		}
		// outpatient admission
		?>

																																            <tr bgcolor="<?php echo $bgc; ?>" valign="top">
																																                <td><FONT SIZE=-1  FACE="Arial"><?php echo @formatDate2Local($row['prescribe_date'], $date_format); ?></td>
																																                <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['article']; ?></td>
																																                <td><FONT SIZE=-1  FACE="Arial" color="#006600"><?php echo $row['total_dosage']; ?></td>
																																                <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['drug_class']; ?></td>
																																            </tr>
																																            <tr bgcolor="<?php echo $bgc; ?>" valign="top">
																																                <td><FONT SIZE=-1  FACE="Arial"><?php echo $full_en; ?></td>
																																                <td rowspan=2><FONT SIZE=-1  FACE="Arial"><?php echo $row['notes']; ?></td>
																																                <td><FONT SIZE=-1  FACE="Arial">Notes</td>
																																                <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['notes']; ?></td>
																																            </tr>
																																            <tr bgcolor="<?php echo $bgc; ?>" valign="top">
																																                <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['prescription_type_nr']; ?></td>

																																                <td><FONT SIZE=-1  FACE="Arial">Requested by:</td>
																																                <td><FONT SIZE=-1  FACE="Arial"><?php echo $row['prescriber']; ?></td>
																																            </tr>
																																        <?php
	}
	?>
																																</table>

																																<script type="text/javascript">
																																    function removePrescriptionItem(indexToRemove, itemValue) {
																																        $("#prescription_item_"+ indexToRemove).remove();
																																        var formdata = {
																																            currData: <?php echo json_encode($item_array) ?>,
																																            itemValue: itemValue
																																        }

																																        $.ajax("removePrescriptionItem.php",
																																        {
																																            data: formdata,
																																            type: 'POST',
																																        })
																																    }

																																</script>
