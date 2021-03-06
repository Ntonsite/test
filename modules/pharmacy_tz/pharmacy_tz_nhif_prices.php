<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');

require($root_path . 'include/inc_environment_global.php');
require($root_path . 'include/care_api_classes/class_tz_pharmacy.php');

define('NO_2LEVEL_CHK', 1);
require($root_path . 'include/inc_front_chain_lang.php');



$debug = FALSE;
// Endable db-debugging if variable debug is true
($debug) ? $db->debug = TRUE : $db->debug = FALSE;

$product_obj = new Product();
$product_obj->usePriceDescriptionTable();

$_COOKIE['PageName'] = 'NHIFPrice';

require_once($root_path . 'main_theme/head.inc.php');
require_once($root_path . 'main_theme/header.inc.php');
require_once($root_path . 'main_theme/topHeader.inc.php');

?>

<html>
  <head>
    <title></title>
    <meta
      name="Description" content="Hospital and Healthcare Integrated Information System - CAREMD"
    />
    <meta name="Author" content="Rayton Kiwelu" />
    <meta
      name="Generator"
      content="various: Quanta, AceHTML 4 Freeware, NuSphere, PHP Coder"
    />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

    <script language="javascript">
      <!--
      function gethelp(x,s,x1,x2,x3,x4)
      {
          if (!x) x="";
          urlholder="../../main/help-router.php?sid=<?php echo $sid."&lang=".$lang;?>&helpidx="+x+"&src="+s+"&x1="+x1+"&x2="+x2+"&x3="+x3+"&x4="+x4;
          helpwin=window.open(urlholder,"helpwin","width=790,height=540,menubar=no,resizable=yes,scrollbars=yes");
          window.helpwin.moveTo(0,0);
      }
      // -->
    </script>
    <link
      rel="stylesheet"
      href="../../css/themes/default/default.css"
      type="text/css"
    />
    <script language="javascript" src="../../js/hilitebu.js"></script>

    <style type="text/css">
     .text-right {
        text-align: right;
     }
    </style>
    <script language="JavaScript">
      <!--
      function popPic(pid,nm){

       if(pid!="") regpicwindow = window.open("../../main/pop_reg_pic.php?sid=<?php $sid ?>&lang=<?php $lang ?>&pid="+pid+"&nm="+nm,"regpicwin","toolbar=no,scrollbars,width=180,height=250");

      }
      // -->
    </script>
  </head>
</html>


<body bgcolor="#ffffff" link="#000066" alink="#cc0000" vlink="#000066">
  <table width="100%" border="0" cellspacing="0">
    <tbody class="main">
      <tr>
        <td valign="top" align="middle" height="35">
          <table cellspacing="0" class="titlebar" border="0">
            <tr valign="top" class="titlebar">
              <td bgcolor="#99ccff">
                &nbsp;&nbsp;<font color="#330066"
                  >NHIF Prices</font
                >
              </td>
              <td bgcolor="#99ccff" align="right">
                <a href="javascript:window.history.back()"
                  ><img
                    src="../../gui/img/control/default/en/en_back2.gif"
                    border="0"
                    width="110"
                    height="24"
                    alt=""
                    style="filter:alpha(opacity=70)"
                    onMouseover="hilite(this,1)"
                    onMouseOut="hilite(this,0)"/></a
                ><a
                  href="javascript:gethelp('pharmacy_product_menu.php','Pharmacy :: My Product Catalog')"
                  ><img
                    src="../../gui/img/control/default/en/en_hilfe-r.gif"
                    border="0"
                    width="75"
                    height="24"
                    alt=""
                    style="filter:alpha(opacity=70)"
                    onMouseover="hilite(this,1)"
                    onMouseOut="hilite(this,0)"/></a
                ><a href="pharmacy_tz.php"
                  ><img
                    src="../../gui/img/control/default/en/en_close2.gif"
                    border="0"
                    width="103"
                    height="24"
                    alt=""
                    style="filter:alpha(opacity=70)"
                    onMouseover="hilite(this,1)"
                    onMouseOut="hilite(this,0)"
                /></a>
              </td>
            </tr>
          </table>
        </td>
      </tr>

    </tbody>

  </table>

<?php 


$drugSQL = "SELECT *  FROM  care_tz_drugsandservices_nhifschemes";
$drugQuery = $db->Execute($drugSQL);


?>
  <div style="margin: 10px;">
      <button class="btn btn-success btn-md updateNHIFBtn" onclick="updateNHIFPrices();">Update NHIF Price List</button>
  </div>


  <div style="margin: 10px;">
      
    <table class="table datatable2 table-condensed table-striped table-bordered table-hover">
        <thead>
            <tr>
                <td>SN</td>
                <td>Item Code</td>
                <td>Price Code</td>
                <td>Level Price Code</td>
                <td>Old Item Code</td>
                <td >Item Name</td>
                <td>Strength</td>
                <td>Package ID</td>
                <td>Scheme ID</td>
                <td>Facility Level Code</td>
                <td>Unit Price</td>
                <td>Is Restricted</td>
                <td>Dosage</td>
                <td>Item Type ID</td>
                <td>Maximum Quantity</td>
                <td>Available In Levels</td>
                <td>Practitioner Qualifications</td>
                <td>Is Active</td>

            </tr>
        </thead>

        <tbody>
            <?php 
                $number = 0;
                while ($drug= $drugQuery->FetchRow()) {
                    $isActive = ($drug['IsActive'])?'Yes':'No';
                    $isRestricted = ($drug['IsRestricted'])?'Yes':'No';
                    $number ++;
                    echo "<tr>
                        <td>". $number."</td>
                        <td>". $drug['ItemCode']."</td>
                        <td>". $drug['PriceCode']."</td>
                        <td>". $drug['LevelPriceCode']."</td>
                        <td>". $drug['OldItemCode']."</td>
                        <td>". $drug['ItemName']."</td>
                        <td>". $drug['Strength']."</td>
                        <td>". $drug['PackageID']."</td>
                        <td>". $drug['SchemeID']."</td>
                        <td>". $drug['FacilityLevelCode']."</td>
                        <td class='text-right'>". number_format($drug['UnitPrice'], 2)."</td>
                        <td>". $isRestricted."</td>
                        <td>". $drug['Dosage']."</td>
                        <td>". $drug['ItemTypeID']."</td>
                        <td>". $drug['MaximumQuantity']."</td>
                        <td>". $drug['AvailableInLevels']."</td>
                        <td>". $drug['PractitionerQualifications']."</td>
                        <td>". $isActive."</td>
                    </tr>";

                }
             ?>

        </tbody>
    </table>
  </div>
</body>

<?php

 $_SESSION['hospital_code'] = 0;
  $hsql="SELECT value FROM  care_config_global WHERE type = 'main_info_facility_code' ";
  $hospQuery = $db->Execute($hsql);

  while ($hospital_datail=$hospQuery->FetchRow()) {
    $_SESSION['hospital_code'] = $hospital_datail['value'];
  }


require_once($root_path . 'main_theme/footer.inc.php');



?>


<script>
  <?php if($_SESSION['hospital_code']): ?>
function updateNHIFPrices() {
    
    $(".updateNHIFBtn").prop("disabled",true);
    $(".updateNHIFBtn").html('Updating NHIF Prices Please Wait')
    var accessToken = null;



    var logindata = {
      grant_type: "password",
      username: "<?php echo $nhif_user; ?>",
      password: "<?php echo $nhif_pwd; ?>"
    };
    
    var url = "<?php echo $nhif_claim_server; ?>/Token";
    $.ajax(url, {
      type: "POST",
      data: logindata,
      timeout: 10000
    })
    .done(function(data) {
        accessToken = data.access_token;
        GetAndUpdatedNHIFPrices(accessToken);
      })
    .fail(function(data) {
        ProgressDestroy();
        if (data.status === 400) {
          alert(
            "Error Login in to NHIF Server!\n" +
              JSON.stringify(data.responseJSON.error_description)
          );
        } else {
          alert(
            "Error Login in to NHIF Server!\n\nPlease check your network connection\nor contact your administrator!"
          );
        }
    });

}

function GetAndUpdatedNHIFPrices(accessToken) {

  $.ajax(
        "getHospitalCode.php",
        {
          headers: { Authorization: "Bearer " + accessToken },
          xhrFields: {
            withCredentials: true
          }
        }
    )
    .done(function(data) {

      $.ajax(
        "<?php echo $nhif_claim_url; ?>?FacilityCode="+data.hospital_code,
        {
          headers: { Authorization: "Bearer " + accessToken },
          xhrFields: {
            withCredentials: true
          }
        }
    )
    .done(function(nhifData) {
      // console.log(nhifData)
        $.post("<?php echo $root_path ?>modules/nhifPriceList.php",
        {formdata: JSON.stringify(nhifData)},
        function(data, status){
            if (data.success == 1) {
              $(".updateNHIFBtn").prop("disabled",false);
              $(".updateNHIFBtn").html('Updated NHIF Prices')
              window.location.href = '<?php echo $root_path ?>modules/downloadNHIFExcelFile.php';
            }
           
        });

    })
    .fail(function(data) {
      ProgressDestroy();
      if (data.status === 0) {
        alert(
          "Error Connecting to NHIF Server!\n\nPlease check your network connection!"
        );
      } else {
        if (data.status === 404) {
        } else {
          alert(JSON.stringify(data.responseText));
        }
      }
    });


    })
}

<?php endif ?>
</script>