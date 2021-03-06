<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<HTML>



<?php

require './roots.php';
require $root_path . 'include/inc_environment_global.php';
require $root_path . 'language/en/lang_en_aufnahme.php';
/**
 * CARE2X Integrated Hospital Information System version deployment 1.1 (mysql) 2004-01-11
 * GNU General Public License
 * Copyright 2002,2003,2004,2005 Elpidio Latorilla
 * , elpidio@care2x.org
 *
 * See the file "copy_notice.txt" for the licence notice
 */
//$db->debug=1;

define('SHOW_DOC_2', 1); # Define to 1 to  show the 2nd doctor-on-duty
if (!defined('DOC_CHANGE_TIME')) {
	define('DOC_CHANGE_TIME', '7.30');
}

$lang_tables[] = 'prompt.php';
define('LANG_FILE', 'nursing.php');
//define('NO_2LEVEL_CHK',1);
$local_user = 'ck_pflege_user';
require $root_path . 'include/inc_front_chain_lang.php';

if (empty($_COOKIE[$local_user . $sid])) {
	$edit = 0;
	include $root_path . "language/" . $lang . "/lang_" . $lang . "_" . LANG_FILE;
}

$ward_nr = $_GET['ward_nr'];
$station = $_GET['station'];

$breakfile = 'nursing-station-pass.php' . URL_APPEND . '&ward_nr=' . $ward_nr . '&mode=show&rt=pflege&edit=1&station=' . $station;
?>


    <HEAD>
        <TITLE> - </TITLE>
        <meta name="Description" content="Hospital and Healthcare Integrated Information System - CARE2x">
        <meta name="Author" content="Timo Hasselwander from merotech">
        <meta name="Generator" content="various: Quanta, AceHTML 4 Freeware, NuSphere, PHP Coder">
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

        <script language="javascript" >
        


<!--
            function gethelp(x, s, x1, x2, x3, x4)
            {
                if (!x)
                    x = "";
                urlholder = "../../main/help-router.php<?php echo URL_APPEND; ?>&helpidx=" + x + "&src=" + s + "&x1=" + x1 + "&x2=" + x2 + "&x3=" + x3 + "&x4=" + x4;
                helpwin = window.open(urlholder, "helpwin", "width=790,height=540,menubar=no,resizable=yes,scrollbars=yes");
                window.helpwin.moveTo(0, 0);
            }
// -->
        </script>

        <script type="text/javascript">
<?php
require $root_path . 'include/inc_checkdate_lang.php';

?>
        </script>
        <script language="javascript" src="<?php echo $root_path; ?>js/setdatetime.js"></script>
        <script language="javascript" src="<?php echo $root_path; ?>js/checkdate.js"></script>
        <script language="javascript" src="<?php echo $root_path; ?>js/dtpick_care2x.js"></script>

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
        function popPic(pid, nm) {

                if (pid != "")
                    regpicwindow = window.open("../../main/pop_reg_pic.php?sid=<?php echo $sid . "&lang=" . $lang ?>&pid=" + pid + "&nm=" + nm, "regpicwin", "toolbar=no,scrollbars,width=180,height=250");

            }


            // -->
        </script>

        <script language="javascript">


<!--
            function closewin()
            {
                location.href = 'startframe.php?sid=<?php echo $sid . "&lang=" . $lang ?>';
            }


            function open_drug_services() {
                urlholder = "<?php echo $root_path; ?>/modules/pharmacy_tz/pharmacy_tz_pending_prescriptions.php<?php echo URL_APPEND; ?>&target=search&task=newprescription&back_path=billing";
                patientwin = window.open(urlholder, "Ziel", "width=750,height=550,status=yes,menubar=no,resizable=yes,scrollbars=yes");
                patientwin.moveTo(0, 0);
                patientwin.resizeTo(screen.availWidth, screen.availHeight);
            }
            function open_lab_request() {
                urlholder = "<?php echo $root_path; ?>modules/laboratory/labor_test_request_pass.php?<?php echo URL_APPEND; ?>&target=chemlabor&user_origin=bill";
                patientwin = window.open(urlholder, "Ziel", "width=750,height=550,status=yes,menubar=no,resizable=yes,scrollbars=yes");
                patientwin.moveTo(0, 0);
                patientwin.resizeTo(screen.availWidth, screen.availHeight);
            }
// -->
        </script>



    </HEAD>

    

    

    
     <?php
require_once $root_path . 'main_theme/head.inc.php';
require_once $root_path . 'main_theme/header.inc.php';
require_once $root_path . 'main_theme/topHeader.inc.php';

?>


    <BODY bgcolor=#ffffff link=#000066 alink=#cc0000 vlink=#000066  >


    

   
   




        <table width=100% border=0 cellspacing=0 height=100%>
            <tbody class="main">
                <tr>
                    <td  valign="top" align="middle" height="35">
                        <table cellspacing="0"  class="titlebar" border=0>
                            <tr valign=top  class="titlebar" >
                                <td bgcolor="#99ccff" >
                                    &nbsp;&nbsp;<font color="#330066"><?php echo 'Prescriptions for ' . $station; ?></font>

                                </td>
                                <td bgcolor="#99ccff" align=right><a
                                        href="javascript:window.history.back()"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)" ></a><a
                                        href="javascript:gethelp('billing_overview.php','Pharmacy')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a><a
                                        href="<?php echo $breakfile; ?>"><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>  </td>
                            </tr>
                        </table>		</td>
                </tr>

                <tr>
                    <td bgcolor=#ffffff valign=top>

                        <br>
                        <blockquote>
                            <TABLE cellSpacing=0  width=800 class="submenu_frame" cellpadding="0">
                                <TBODY>
                                    <?php
include_once $root_path . 'include/inc_date_format_functions.php';

if (isset($_GET['prescr_date'])) {
	$prescr_date = $_GET['prescr_date'];
	//echo 'Date set: '.$prescr_date;
} else {
	$prescr_date = formatDate2Local(date('Y-m-d'), $date_format);
}

echo '<tr class="titlebar" bgcolor="#99ccff"><td><font color="#330066">Prescriptions as at: ' . $prescr_date . '</font></td></tr>';
echo '<tr class="titlebar" bgcolor="#99ccff"><td>&nbsp;</td></tr>';


?>







 <TR>
     <TD>
        <TABLE cellSpacing=1 cellPadding=3 width=800>
        <TBODY class="submenu">

        <tr>
         <td class="submenu_item"><?php echo $LDAdm_Nr ?></td>
        <td class="submenu_item"><?php echo $LDLastName . ', ' . $LDName ?></td>
        <td class="submenu_item"><?php echo $LDSex ?></td>
     <td class="submenu_item"><?php echo $LDRoom ?></td>
    <td class="submenu_item"><?php echo $LDPrescriptions ?></td>
     <td class="submenu_item"><?php echo $LDDosage ?></td>
     <td class="submenu_item"><?php echo $LDTimesPerDay ?></td>
     <td class="submenu_item"><?php echo $LDDays ?></td>
    <td class="submenu_item"><?php echo $LDTotalDosage=$LDTotalDosage ?? ""; ?></td>
    <td class="submenu_item"><?php echo $LDExtraNotes ?></td>
     <td class="submenu_item"><?php echo $LDPrescriber ?></td>
    </tr>

                                            

    <form name="nursingform" id="nursingform"  method="POST" action="./nursing-station-save-drug-issues.php">

    <?php
 //echo "<pre>"; print_r($_POST); echo "</pre>";
                                                ?>

<?php
$coreObjOuter = new Core;

$sqlOuter = "select * from care_encounter where current_ward_nr=$ward_nr and is_discharged=0";
$sqlroomprefix = "SELECT roomprefix FROM care_ward WHERE nr=" . $ward_nr;
$room_prefix_result = $db->Execute($sqlroomprefix);
if ($room_prefix = $room_prefix_result->fetchRow()) {
	$roomprefix = $room_prefix['roomprefix'];
}

$coreObjOuter->result = $db->Execute($sqlOuter);

foreach ($coreObjOuter->result as $rowEncounter) {

	echo '<TR  height=1>
                        <TD colSpan=10 class="vspace"><IMG height=1 src="../../gui/img/common/default/pixel.gif" width=5></TD>
                      </TR>';

	echo '<tr>';

	//data person
	$pid = $rowEncounter['pid'];
	$enc_nr = $rowEncounter['encounter_nr'];
	echo '<td>' . $enc_nr . '</td>';

	$sqlPerson = "select * from care_person where pid=$pid";
	$coreObjInner = new \stdClass();
	$coreObjOuter = new \stdClass();
	$coreObjInner->result = $db->Execute($sqlPerson);
	$rowPerson = $coreObjInner->result->FetchRow();
	$name_last = $rowPerson['name_last'];
	$name_first = $rowPerson['name_first'];
	$sex = $rowPerson['sex'];

	echo '<td>' . $name_last . ', ' . $name_first . '</td><td>' . $sex . '</td><td>' . $roomprefix . '' . $rowEncounter['current_room_nr'] . '</td>';

	
	$encounterNr = $rowEncounter['encounter_nr'];
	

	$sqlInner = "select * from care_encounter_prescription INNER JOIN care_tz_drugsandservices ON care_tz_drugsandservices.item_id=care_encounter_prescription.article_item_number" .
        " where encounter_nr = $encounterNr and purchasing_class IN('drug_list','supplies')";
        

	$coreObjInner->result = $db->Execute($sqlInner);

	$prescr = '';

	foreach ($coreObjInner->result as $rowPrescr) {

		if ($prescr == '') {
			$prescr .= '<td>';
		} else {
			$prescr .= '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>';
		}

		$article = $rowPrescr['article'];
		$dosage = $rowPrescr['dosage'];
		$times_per_day = $rowPrescr['times_per_day'];
		$days = $rowPrescr['days'];
		$totaldosage = $rowPrescr['total_dosage'];
		$notes = $rowPrescr['notes'];
		$prescriber = $rowPrescr['prescriber'];
		$nr = $rowPrescr['nr'];

		$prescr .= $article . '</td><td>' . $dosage . '</td><td>' . $times_per_day . '</td><td>' . $days . '</td><td>' . $totaldosage . '</td><td>' . $notes . '</td><td>' . $prescriber . '</td>';
		$prescr .= '</td></tr>';
	}
	if ($prescr == '') {
		$prescr .= '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
	}

	echo $prescr;
}
?>
 <input type="hidden" name="station" value="<?php echo $station ?>">
<input type="hidden" name="ward_nr" value="<?php echo $ward_nr ?>">






                 </TBODY>
            </TABLE>
                    </TD>
                </TR>






                                                    </TBODY>
                                                    </TABLE>
     <table cellSpacing=1  width=600  cellpadding=3>
    <TBODY class="submenu">
 <tr class="titlebar" bgcolor=#ffffff colspan="2"><td colspan="2" align="center"><font color=#000000><?php echo $station.' '.'Issues'?></font></td></tr>
<tr>
   <td class="submenu_item"><?php echo $LDPrescrWithoutServices ?></td>
    <td class="submenu_item"><?php echo 'Total ' . $LDDosage ?></td>
 <td class="submenu_item"><?php echo 'Already Issued '?></td>
 <td class="submenu_item"><?php echo 'Unissued'?></td>
<td class="submenu_item"><?php echo 'Balance'?></td>
 <td class="submenu_item"><?php echo 'Today ' . $LDDosage ?></td> 
 <td class="submenu_item"><?php echo 'Is Issued'  ?></td>
 <td class="submenu_item"><?php echo 'Issued By '  ?></td>
  <td class="submenu_item" ><?php echo 'Tick To issue '  ?></td>
  <td class="submenu_item" colspan="4"></td>


 </tr>
<?php
echo '<TR  height=1>
                        <TD colspan=13 class="vspace"><IMG height=1 src="../../gui/img/common/default/pixel.gif" width=5></TD>
    </TR>';
// $dateSQL = substr($prescr_date, 6, 4) . '-' . substr($prescr_date, 3, 2) . '-' . substr($prescr_date, 0, 2);

echo '<tr height=1>';
$SQL_TOTALDOSE = "SELECT *, SUM(total_dosage) AS totaldosage,SUM((CASE WHEN sub_class='syrup' THEN '1' WHEN sub_class='suspension' THEN '1' WHEN sub_class='bottle' THEN '1' ELSE (dosage*times_per_day) END )) AS today_dose  FROM care_encounter_prescription INNER JOIN care_tz_drugsandservices ON care_tz_drugsandservices.item_id=care_encounter_prescription.article_item_number INNER JOIN care_encounter ON care_encounter.encounter_nr=care_encounter_prescription.encounter_nr WHERE current_ward_nr='" . $ward_nr . "' and purchasing_class IN ('drug_list','supplies') and care_encounter.is_discharged=0 and is_disabled<>1 group by article_item_number order by article";


//echo $SQL_TOTALDOSE; die;



$RESULT_TOTALDOSE = $db->Execute($SQL_TOTALDOSE);
while ($rows = $RESULT_TOTALDOSE->FetchRow()) {
    $issued=0;
    $given=0;
   // echo "<pre>"; print_r($row); echo "</pre>";

    $sqlIssued="SELECT *, SUM(qtyIssued) AS totalGiven FROM care_tz_ward_dispensed as dis INNER JOIN care_encounter_prescription cep ON  dis.prescriptionNr=cep.nr
    INNER JOIN care_tz_drugsandservices as ds ON ds.item_id=cep.article_item_number INNER JOIN care_encounter ce ON ce.encounter_nr=cep.encounter_nr WHERE cep.article_item_number='".$rows['article_item_number']."' AND wardNr='".$ward_nr."' AND cep.is_disabled<>1 AND ce.is_discharged=0  GROUP BY cep.article_item_number";
    $resultIssued=$db->Execute($sqlIssued);

    if ($rowsIssued=$resultIssued->FetchRow()) {
         $issued=$rowsIssued['totalGiven'];
         
     } 

     $bal=$rows['totaldosage']-$issued;
     $given=$issued-$rows['today_dose'];

     $today = date('Y-m-d');
     


     $sqlIsIssued="SELECT *,wd.issuer as issuerID FROM care_tz_ward_dispensed wd INNER JOIN care_encounter_prescription cep ON cep.nr=wd.prescriptionNr  WHERE wd.is_issued='1' AND dateIssued='".$today."' AND cep.article_item_number='".$rows['article_item_number']."' AND wd.wardNr='".$ward_nr."'";




     //echo  $sqlIsIssued;


     $resultIsIssued=$db->Execute($sqlIsIssued);

     if ($resultIsIssued->RecordCount()>0) {
         $isIssued='<font color="green">issued</font>';
         $showUser=TRUE;         
     }else{
        $isIssued='<font color="red">Not Issued</font>';
        $showUser=FALSE;
     }

     if ($rowsIsIssued=$resultIsIssued->FetchRow()) {
        $issuer=$rowsIsIssued['issuerID'];       

     }

     $added=0;


     //Same prescription was added later, we need to get difference and show it to pharmacist as added later prescription
     $sqlAdded="SELECT cep.nr,(CASE WHEN sub_class='syrup' THEN '1' WHEN sub_class='suspension' THEN '1' WHEN sub_class='bottle' THEN '1' WHEN sub_class='' THEN total_dosage  ELSE SUM(dosage*times_per_day) END ) AS unIssuedTotal FROM care_encounter_prescription cep INNER JOIN care_tz_drugsandservices ds ON ds.item_id=cep.article_item_number INNER JOIN care_encounter ce ON ce.encounter_nr=cep.encounter_nr WHERE
     cep.prescribe_date='".$today."' AND ce.current_ward_nr='".$ward_nr."' AND cep.article_item_number='".$rows['article_item_number']."' AND cep.nr NOT IN(SELECT prescriptionNr FROM care_tz_ward_dispensed)";
      $resultAdded=$db->Execute($sqlAdded);

      if ($rowAdded=$resultAdded->FetchRow()) {          
          $added=$rowAdded['unIssuedTotal'];
      }



     $rows['is_issued']=isset($rows['is_issued']) ? $rows['is_issued'] : null;
     echo '<td>' . $rows['article'] . '</td>';
     echo '<td>' . $rows['totaldosage']. '</td>';
     echo '<td>' . $issued. '</td>';
    
     

     if ($added>0) {        
        echo '<td bgcolor="yellow">' . $added. '</td>';         
     }else{
        echo '<td>' .'0'. '</td>'; 

     }
     
     
     echo '<td>' . $bal. '</td>';
     if ($rows['purchasing_class']=='supplies') {
        echo '<td>'.'<input type="text" size="5" placeholder="qty" name="supply_'.$rows['nr'].'">'.'</td>';

     }else{
    //$rows['today_dose']=($bal<$rows['today_dose']) ? $bal : $rows['today_dose'];

        if ($bal<$rows['today_dose']) {//will result into negative
            $rows['today_dose']=$bal;
            echo '<input type="hidden" name="lastDose_'.$rows['nr'].'" value="'.$rows['today_dose'].'">';
            
        }else{
            $rows['today_dose']=$rows['today_dose'];

        }

        
        
        echo '<td>' . $rows['today_dose']. '</td>';
     }
     
     echo '<td>' .$isIssued . '</td>';

     
     if ($showUser) {
         echo '<td>'.$issuer.'</td>';
     }else{
        echo '<td></td>';
     }
     


     if ($rows['totaldosage']>$issued) {      
     
     echo '<td><input type="checkbox"  name="checked_'.$rows["nr"].'"></td>';
     }else{
        echo '<td></td>';
     }

     echo '<td><input type="hidden" name="todayDose_'.$rows["nr"].'" value="'.$rows['today_dose'].'"><td>';

     echo '<td><input type="hidden" name="article_'.$rows["nr"].'" value="'.$rows['article_item_number'].'"><td>';
     echo '<tr><input type="hidden" name="user" value="'.$_SESSION['sess_user_name'].'">';




     


     //

     





	echo '</tr>';


	echo '<TR  height=1>
                        <TD colSpan=13 class="vspace"><IMG height=1 src="../../gui/img/common/default/pixel.gif" width=5></TD>
                      </TR>';
                      
}

   
  
?>
<input type='hidden' name='destination' value="<?php echo $_SERVER['REQUEST_URI'];?>" />
 </table>

     <p>
     <a href="<?php echo $breakfile ?>"><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this, 1)" onMouseOut="hilite(this, 0)"></a>

                        <!--<input type="image" src="../../gui/img/control/default/en/en_savedisc.gif" value="button"  name="save">
                        <br><br><br>-->
              

 
 




</script>
<button type="submit" name="show"  class="btn btn-success">Refresh</button>  <button type="submit" name="issue" class="btn btn-info">Issue</button> </font><br>

                                                        
                                                        

    </form>

         <p>
        </blockquote>
        </td>
         </tr>

        <tr valign=top >
        <td bgcolor=#cccccc>
        <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#cfcfcf">
         <tr>
        <td align="center">

     <table width="100%" bgcolor="#ffffff" cellspacing=0 cellpadding=5>

<tr><td>
 <div class="copyright">
<script language="JavaScript">
 <!-- Script Begin
function openCreditsWindow() {
  urlholder = "../../language/$lang/$lang_credits.php?lang=$lang";
creditswin = window.open(urlholder, "creditswin", "width=500,height=600,menubar=no,resizable=yes,scrollbars=yes");

  }  //  Script End -->
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
     </td>

     </tr>

     </tbody>
    </table>
                            </BODY>
                            </HTML>
<?php require_once $root_path . 'main_theme/footer.inc.php';?>
