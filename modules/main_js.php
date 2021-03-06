<script>
$(function() {
    $( document ).idleTimer("destroy");

  var timeOut = 200000;

    window.loginUrl = "<?php echo $root_path ?>"+ "main/login.php";


    $.getJSON("<?php echo $root_path ?>modules/sessionSetting.php", function(data) {

        timeOut = data.timeout;

        $( document ).idleTimer( {
            timeout: timeOut,
            idle: true
        });


    }).fail( function(data, textStatus, error) {
         console.log(error);
    });


    $( document ).on( "idle.idleTimer", function(event, elem, obj){
        window.location.href = window.loginUrl;
    });


    $('#datepicker').dateTimePicker({
        mode: 'date',
        format: 'dd/MM/yyyy',
    });


    $('#datepicker1').dateTimePicker({
        mode: 'date',
        format: 'dd/MM/yyyy'
    });


    $('#datepicker2').dateTimePicker({
        mode: 'date',
        format: 'dd/MM/yyyy'
    });

$(document).ready(function () {
    $('.datatable').DataTable(
    {
        scrollX: true,
        scrollCollapse: true,
        fixedHeader: {
            header: false,
            footer: false
        },
        responsive: true,
        columnDefs: [
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1},
            {responsivePriority: 3, targets: -2}
        ]
    });

    $('.datatable2').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    $('.datatable3').DataTable( {
      "pageLength": 15
    } );

    $('.datatable4').DataTable({
      dom: 'Bfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ],
      "footerCallback": function(row, data, start, end, display) {
        var api = this.api();

        api.columns('.sum', {
          page: 'current'
        }).every(function() {
          var sum = this
            .data()
            .reduce(function(a, b) {
              console.log(b.replace(',', ''))
              var x = parseFloat(a) || 0;
              var y = parseFloat(b.replace(',', '')) || 0;
              return x + y;
            }, 0);
            $(this.footer()).html(formatNumber(sum));
        });
      }


    });

});


});





$('.referalInputs').hide();
$('.allergydetails').hide();


$('.visitType').change(function(){

    var isNHIF = $(".nhifRadio").is(':checked');

    if (isNHIF)
    {
        $('.referalInputs').show();

    }else
    {
        $('.referalInputs').hide();
    }
})

$('.uVisitType').change(function(){

    var uVisitType = $('input[name=uVisitType]:checked').val();

    if (uVisitType == 3 || uVisitType == 4)
    {
        $('.referalInputs').show();

    }else
    {
        $('.referalInputs').hide();
    }
})


$('.allergic').change(function(){

    var isAllergic = $('input[name=allergic]:checked').val();
    if (isAllergic ==1)
    {
      $('.allergydetails').show();

    }else{
      $('#allergicd').val('');
      $('.allergydetails').hide();
    }
})

var isAllergic = $('input[name=allergic]:checked').val();
if (isAllergic ==1)
{
  $('.allergydetails').show();

}else{
  $('.allergydetails').hide();
}

$(".acceptBtn").click(function(e){
    e.preventDefault();

    $(".acceptBtn").hide();
    $(".rejectBtn").hide();
    $(".sendBtn").show();

})

$(".rejectBtn").click(function(e){
    e.preventDefault();
    var url = $(".rejectBtn").attr("href");
    url += "&rejected=1";
    window.location.href = url;
})


// Diagnosis types
function chooseDiagnosisType(url) {
    window.diagnosisUrl = url;
    $('#diagnosisTypeModal').modal('show');
}

function setSelectedOption(diagnosisType) {
    createCookie('DiagnosisType', diagnosisType, '10');
    window.location.href = window.diagnosisUrl;
}

// un discharge patients

function showDischargedPatients() {
  $('#dischargePatientsModal').modal({
    keyboard: false,
    backdrop: false
  })
  $('#dischargePatientsModal').modal('show');
}

function closeDischargedPatientsModal() {
  window.location.reload()
}

function showDatepicker(){
    $('#datepicker10').dateTimePicker({
        mode: 'date',
        format: 'dd/MM/yyyy',
        constrainInput: false
    });
}

function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

function setSelectedBloodGroup() {
  var selectedBgroup = $('#blood_group_id').find(":selected").text();

    $.get('bloodGroupOrder.php', {group_id: selectedBgroup},
        function(result) {
          $("#order_test_group").empty();
          $("#order_test_group").append('<option value="0">-- Select --</option>');
          if(result.relatedGroups.length)
          {
              for(var i=0, len=result.relatedGroups.length; i<len; i++)
              {
                  $("#order_test_group").append('<option value="' + result.relatedGroups[i]['nr'] + '">' + result.relatedGroups[i]['name'] +'</option>');
              }
          }
        },
    "json");

}


var selectedBgroup = $('#blood_group_id').find(":selected").text();

if (selectedBgroup) {
$.get(
  'bloodGroupOrder.php',
  {group_id: selectedBgroup},
  function(result) {
    $("#order_test_group").empty();
    $("#order_test_group").append('<option value="0">-- Select --</option>');
    if(result.relatedGroups.length)
    {
        for(var i=0, len=result.relatedGroups.length; i<len; i++)
        {
            $("#order_test_group").append('<option value="' + result.relatedGroups[i]['nr'] + '">' + result.relatedGroups[i]['name'] +'</option>');
        }
    }
  },
  "json");

}

function deletePrescription(url) {
  $('#deletePrescriptionModal').modal('show');
  window.deletePrescriptionUrl = url;
}


$("document").ready(function(){
  $( ".deletePrescriptionForm" ).submit(function( event ) {

    var deleteReason = $("#deleteReasons").val();
    url = window.deletePrescriptionUrl + "&delete_reason="+deleteReason;
    window.location.href = url;

    event.preventDefault();
  });

})

function stopPrescription(url) {
  $('#stopPrescriptionModal').modal('show');
  window.stopPrescriptionUrl = url;
}

$("document").ready(function(){
  $( ".stopPrescriptionForm" ).submit(function( event ) {

    var stopReason = $("#stopReasons").val();
    url = window.stopPrescriptionUrl + "&stop_reason="+stopReason;
    window.location.href = url;

    event.preventDefault();
  });

})



$(function () {
  $('[data-toggle="popover"]').popover({'trigger': 'hover'})
})

<?php if ($page == "nursing"): ?>
function transferNHIFPatient(encounter_nr) {
  window.encounter_nr = encounter_nr;

  var tranferURL = '<?php echo $root_path ?>'+'modules/nursing/getNHIFTransferDetails.php';
  $.get(tranferURL, {encounter_nr: encounter_nr},
    function(data) {
      $("#AuthorizationNoField").val(data.AuthorizationNo);
      $("#CardNoField").val(data.CardNo);
      $("#GenderField").val(data.Gender);
      $("#PatientFullNameField").val(data.PatientFullName);
      $("#PhysicianMobileNoField").val(data.PhysicianMobileNo);
      $("#PhysicianNameField").val(data.PhysicianName);
      $("#PhysicianQualificationID").val(data.PhysicianQualificationID);
      $("#ReferringDiagnosisField").val(data.ReferringDiagnosis);

      $("#AuthorizationNoText").text("");
      $("#CardNoText").text("");
      $("#GenderText").text("");
      $("#PatientFullNameText").text("");
      $("#AuthorizationStatusText").text("");
      $("#RemarksText").text("");
      $("#DateOfBirthText").text("");
      $("#CardStatusText").text("");

      $("#AuthorizationNoText").text(data.AuthorizationNo);
      $("#CardNoText").text(data.CardNo);
      $("#GenderText").text(data.Gender);
      $("#PatientFullNameText").text(data.PatientFullName);
      $("#AuthorizationStatusText").text(data.AuthorizationStatus);
      $("#RemarksText").text(data.Remarks);
      $("#DateOfBirthText").text(data.DoB);
      $("#CardStatusText").text(data.CardStatus);

      var selectedValues = data.ReferringDiagnosis.split(",");

      $('.selectMultiple').selectpicker('val', selectedValues);
      $('.selectMultiple').selectpicker('refresh')

    },
  "json");

  $('#transferNHIFPatient').modal({
    keyboard: false,
    backdrop: false
  })
  $('#transferNHIFPatient').modal('show');
}
<?php endif;?>

function submitTransferNHIFPatient() {

  $("#nhifTransferBtn").html('Submitting NHIF Tranfer. Please Wait');
  $("#nhifTransferBtn").prop('disabled', true);

  var ReasonsForReferral = $("#ReasonsForReferral").val();
  if (ReasonsForReferral.length < 10) {
    alert("Please fill Reasons for Referral ");
    return
  }
  var ReferringDiagnosis = $("#ReferringDiagnosis").val();
  var formdata = {
    'AuthorizationNo': $("#AuthorizationNoField").val(),
    'CardNo': $("#CardNoField").val(),
    'Gender': $("#GenderField").val(),
    'PatientFullName': $("#PatientFullNameField").val(),
    'PhysicianMobileNo': $("#PhysicianMobileNoField").val(),
    'PhysicianName': $("#PhysicianNameField").val(),
    'PhysicianQualificationID': $("#PhysicianQualificationID").val(),
    'ReferringDiagnosis' : ReferringDiagnosis.join(),
    'ServiceIssuingFacilityCode' : $("#ServiceIssuingFacilityCodeField").val(),
    'ReasonsForReferral': $("#ReasonsForReferral").val()
  };


  getNHIFToken(function(accessToken){
    accessToken = accessToken;
    $.ajax("<?php echo $nhif_base; ?>/breeze/verification/AddReferral",
    {
        headers: { "Authorization": "Bearer " + accessToken },
        xhrFields: {
            withCredentials: true
        },
        data: JSON.stringify(formdata),
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
    })
    .done(function (responseData) {

      $("#nhifTransferBtn").html('Transfer Patient');
      $("#nhifTransferBtn").prop('disabled', false);

      $("#transferredType").text(responseData.$type);
      $("#transferredAuthorizationNo").text(responseData.AuthorizationNo);
      $("#transferredCardNo").text(responseData.CardNo);
      $("#transferredCreatedBy").text(responseData.CreatedBy);
      $("#transferredCreatedBy").text(responseData.DateCreated);
      $("#transferredGender").text(responseData.Gender);
      $("#transferredLastModified").text(responseData.LastModified);
      $("#transferredPatientFullName").text(responseData.PatientFullName);
      $("#transferredPhysicianMobileNo").text(responseData.PhysicianMobileNo);
      $("#transferredPhysicianName").text(responseData.PhysicianName);
      $("#transferredPhysicianQualificationID").text(responseData.PhysicianQualificationID);
      $("#transferredReasonsForReferral").text(responseData.ReasonsForReferral);
      $("#transferredLastModifiedBy").text(responseData.LastModifiedBy);
      $("#transferredReferralNo").text(responseData.ReferralNo);
      console.log(responseData)
      alert("Successfully submitted NHIF Transfer request");
      responseData.encounter_nr = window.encounter_nr;
      $.ajax("<?php echo $root_path ?>modules/nursing/archiveNHIFResponseDetails.php",
      {
        data: responseData,
        type: 'POST',
      })

      $.ajax('<?php echo $nhif_base; ?>/api/Account/Logout', {
        type: "POST",
        headers: { "Authorization": "Bearer " + accessToken }
      });
    })
    .fail(function (data) {
      $("#nhifTransferBtn").html('Transfer Patient');
      $("#nhifTransferBtn").prop('disabled', false);
      alert(data);
    });
  })

}

function getNHIFToken(handleData) {

  var accessToken = null;

  var logindata = {
      "grant_type": "password",
      "username": "<?php echo $nhif_user; ?>",
      "password": "<?php echo $nhif_pwd; ?>"
  };
  var url = "<?php echo $nhif_base; ?>/Token";
  $.ajax(url, {
      type: "POST",
      data: logindata,
      timeout: 10000
  }).done(function (data) {

      accessToken = data.access_token;
      handleData(accessToken);

  }).fail(function (data) {

      if (data.status === 400) {
          alert("Error Login in to NHIF Server!\n" + JSON.stringify(data.responseJSON.error_description));
      } else {
          alert("Error Login in to NHIF Server!\n\nPlease check your network connection\nor contact your administrator!");
      }

  });
}

function closeModal(modalname) {
  $("#tranferNHIFPatient-form")[0].reset();
  setTimeout(function(){
    window.location.reload();
  });
}



function submitNHIFClaim(type, encounter_nr, submitURL) {


// alert(submitURL);
// return false;


  // $.get("<?php //echo $root_path ?>modules/nhif/printPatientFile.php?type="+type+"&encounter_nr="+encounter_nr+"&save=1", function(printed) {

    window.location.href = submitURL;

  // }).fail( function(data, textStatus, error) {
  //     alert('Unable to save patient file. Please try again')
  //      console.log(error);
  // });

}

</script>

<div class="modal" id="diagnosisTypeModal" style="display: none" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Choose Diagnosis Type</h5>
        <button type="button" class="close" onclick="closeModal('#diagnosisTypeModal')" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="custom-control custom-radio" onclick="setSelectedOption('preliminary')">
          <input type="radio" class="custom-control-input" id="preliminarydiag"  name="diagnosis">
          <label class="custom-control-label preliminarydiag" for="preliminarydiag">Preliminary Diagnosis</label>
        </div> <br>
         <div class="custom-control custom-radio" onclick="setSelectedOption('final')">
          <input type="radio" class="custom-control-input" id="finaldiag" name="diagnosis">
          <label class="custom-control-label finaldiag" for="finaldiag">Final Diagnosis</label>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal" id="deletePrescriptionModal" style="display: none" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form name="deletePrescriptionForm" class="deletePrescriptionForm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Prescription</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <label for="deleteReasons">Delete Reasons</label><br><br>
        <textarea name="deleteReasons" minlength="10" required="" class="" autofocus="" id="deleteReasons" cols="60" rows="8"></textarea>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-sm btn-primary" >Delete</button>
      </div>

    </div>
      </form>

  </div>
</div>

<!-- STOP Prescription -->
<div class="modal" id="stopPrescriptionModal" style="display: none" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form name="stopPrescriptionForm" class="stopPrescriptionForm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Stop Medication</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <label for="stopReasons">stop Reasons</label><br><br>
        <textarea name="stopReasons" minlength="10" required="" class="" autofocus="" id="stopReasons" cols="60" rows="8"></textarea>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-sm btn-primary" >Stop</button>
      </div>

    </div>
      </form>

  </div>
</div>





<!-- Modal -->
<div class="modal fade" id="mtuhaICDUpdate" style="display: none"  tabindex="-1" role="dialog" aria-labelledby="mtuhaICDUpdateLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="uploadICD10Excel.php" method="post" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mtuhaICDUpdateLabel">MTUHA ICD10 UPDATE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div class="row">
           <div class="col-md-12">
            <a href="mtuhaUpdateSample.php" class="btn btn-sm btn-outline-success">Download Sample File</a>
           </div>
         </div>
         <div class="row">
           <div class="col-md-12">
            <br><br><br>
            <label>Select Excel file to upload:</label>
             <input type="file" name="fileToUpload" id="fileToUpload">
           </div>
         </div>
      </div>
      <div class="modal-footer" style="border: none;">
        <button type="submit" class="btn btn-primary">Update ICD10</button>
      </div>
    </div>
  </form>
  </div>
</div>

<!-- Modal -->

<style type="text/css">
  .title {
    font-size: 14px !important;
    font-family: "Roboto", "Helvetica", "Arial", sans-serif;
    font-weight: 300;
  }
</style>

<div class="modal fade" id="transferNHIFPatient" style="display: none"  tabindex="-1" role="dialog" aria-labelledby="transferNHIFPatientLabel" aria-hidden="true">
  <?php if ($page == 'nursing'): ?>
    <link href="<?php echo $root_path ?>css/themes/care_md/modal-step.css" rel="stylesheet"/>
    <link href="<?php echo $root_path ?>css/themes/care_md/bootstrap-select.css" rel="stylesheet"/>
    <style>
      .filter-option {
        color: #000;
        background: #fff;
       }
    </style>
    <?php
$nhifRoles = array();
$roleSQL = "SELECT name, nhif_qualification_id FROM `care_role_person` WHERE nhif_qualification_id > 0  ORDER BY `nr` ASC";
$roleResult = $db->Execute($roleSQL);
if ($roleResult->RecordCount()) {
	$nhifRoles = $roleResult->GetArray();
}

$hospitals = array();
$hospitalCode = 0;
$hospitalName = 0;

$sql = "SELECT value FROM  care_config_global WHERE type = 'main_info_facility_code' ";
$hospQuery = $db->Execute($sql);

while ($hospital_datail = $hospQuery->FetchRow()) {
	$hospitalCode = $hospital_datail['value'];
}

$hsql = "SELECT value FROM  care_config_global WHERE type = 'main_info_name' ";
$hospitalQuery = $db->Execute($hsql);

while ($hospital_datail = $hospitalQuery->FetchRow()) {
	$hospitalName = $hospital_datail['value'];
}
if (@$hospitalName) {
	$hospitals[] = array('name' => $hospitalName, 'code' => $hospitalCode);
}

$diagnosises = [];

$icdSQL = "SELECT diagnosis_code, description FROM  care_icd10_en ";
$icdResult = $db->Execute($icdSQL);
if ($icdResult->RecordCount()) {
	$diagnosises = $icdResult->GetArray();
}

?>
  <?php endif?>

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
         <div class="row">
           <div class="col-md-12" style="padding: 0;">
            <button type="button" class="modal-close"  data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true" onClick="closeModal('#diagnosisTypeModal')">&times;</span>
            </button>

              <form method="POST" id="tranferNHIFPatient-form" style="min-height: 85vh; height: 85vh; overflow-y: auto;" class="tranferNHIFPatient-form" enctype="multipart/form-data">
                <h3>
                    Member's Details
                </h3>
                <fieldset>
                    <h4 class="font-md">Authorization No: <strong class="p-md" id="AuthorizationNoText"></strong></h4>
                    <!-- <h4 class="font-md">Membership No: <strong id="MembershipNoText"></strong></h4> -->
                    <h4 class="font-md">Card No: <strong class="p-md" id="CardNoText"></strong></h4>
                    <h4 class="font-md">Full Name: <strong class="p-md" id="PatientFullNameText"></strong></h4>
                    <h4 class="font-md">Gender: <strong class="p-md" id="GenderText"></strong></h4>
                    <h4 class="font-md">Date Of Birth: <strong class="p-md" id="DateOfBirthText"></strong></h4>
                    <h4 class="font-md">Card Status: <strong class="p-md" id="CardStatusText"></strong></h4>
                    <h4 class="font-md">Authorization Status: <strong class="p-md" id="AuthorizationStatusText"></strong></h4>
                    <h4 class="font-md">Remarks: <strong class="p-md" id="RemarksText"></strong></h4>

                    <input type="hidden" id="AuthorizationNoField" name="AuthorizationNo">
                    <input type="hidden" id="CardNoField" name="CardNo">
                    <input type="hidden" id="GenderField" name="Gender">
                    <input type="hidden" id="PatientFullNameField" name="PatientFullName">


                </fieldset>

                <h3>
                    Transfer Details
                </h3>
                <fieldset>

                  <div class="row">
                    <div class="col">
                      <label for="PhysicianNameField">Referring Physician Name (*) </label>
                      <input type="text"  required class="form-control" id="PhysicianNameField" name="PhysicianName">
                    </div>
                    <div class="col">
                      <label for="PhysicianQualificationID">Physician Qualification</label>
                      <select id="PhysicianQualificationID" required="" name="PhysicianQualificationID" class="form-control">
                        <option value="">--Select--</option>
                        <?php foreach ($nhifRoles as $role): ?>
                          <option value="<?php echo $role['nhif_qualification_id'] ?>"><?php echo $role['name'] ?></option>
                        <?php endforeach?>
                      </select>
                    </div>
                    <div class="col">
                      <label for="PhysicianMobileNoField">Physician Mobile No</label>
                      <input type="text" id="PhysicianMobileNoField" name="PhysicianMobileNo" class="form-control">
                    </div>
                  </div>

                  <div class="row" style="margin-top: 50px;">
                   <!--  <div class="col">
                      <label for="referringdate">Referring Date </label>
                      <input type="text" class="form-control" id="referringdate">
                    </div> -->
                    <div class="col-md-4">
                      <label for="ServiceIssuingFacilityCodeField">Service Issuing Facility</label>
                      <select id="ServiceIssuingFacilityCodeField" required="" name="ServiceIssuingFacilityCode" class="form-control">
                        <?php foreach ($hospitals as $hospital): ?>
                          <option value="<?php echo $hospital['code'] ?>"><?php echo $hospital['name'] ?></option>
                        <?php endforeach?>
                      </select>
                    </div>
                    <div class="col-md-8">
                      <label for="referringdiagnosis">Referring Diagnosis</label>
                      <select class="selectMultiple form-control" id="ReferringDiagnosis" name="ReferringDiagnosis[]" multiple data-live-search="true">
                        <?php foreach ($diagnosises as $diagn): $diagname = substr($diagn['diagnosis_code'] . ' - (' . $diagn['description'] . ' )', 0, 50);?>
		                          <option value="<?php echo $diagn['diagnosis_code'] ?>"><?php echo $diagname; ?></option>
		                        <?php endforeach?>
                      </select>
                    </div>
                  </div>

                  <div class="form-textarea" style="margin-top: 50px;">
                      <label for="ReasonsForReferral" class="radio-label">Reasons For Referral</label>
                      <textarea name="ReasonsForReferral" required="" id="ReasonsForReferral" placeholder="Eg :Fractured hand needs attention from Orthopaedician at MOI"></textarea>
                  </div>

                </fieldset>

                <h3>
                  Confirmation
                </h3>
                <fieldset>
                  <h4 class="font-sm">Transferred Type: <strong class="p-sm" id="transferredType"></strong></h4>
                  <h4 class="font-sm">Transferred Authorization No: <strong class="p-sm" id="transferredAuthorizationNo"></strong></h4>
                  <h4 class="font-sm">Transferred Card No: <strong class="p-sm" id="transferredCardNo"></strong></h4>
                  <h4 class="font-sm">Transferred Created By: <strong class="p-sm" id="transferredCreatedBy"></strong></h4>
                  <h4 class="font-sm">Transferred Patient Gender: <strong class="p-sm" id="transferredGender"></strong></h4>
                  <h4 class="font-sm">Transferred Last Modified: <strong class="p-sm" id="transferredLastModified"></strong></h4>
                  <h4 class="font-sm">Transferred Patient Full Name: <strong class="p-sm" id="transferredPatientFullName"></strong></h4>
                  <h4 class="font-sm">Transferred Physician Mobile No: <strong class="p-sm" id="transferredPhysicianMobileNo"></strong></h4>
                  <h4 class="font-sm">Transferred Physician Name: <strong class="p-sm" id="transferredPhysicianName"></strong></h4>
                  <h4 class="font-sm">Transferred Physician Qualification: <strong class="p-sm" id="transferredPhysicianQualificationID"></strong></h4>
                  <h4 class="font-sm">Transferred Reasons For Referral: <strong class="p-sm" id="transferredReasonsForReferral"></strong></h4>
                  <h4 class="font-sm">Transferred Reasons For Referral: <strong class="p-sm" id="transferredLastModifiedBy"></strong></h4>

                  <h4 class="font-sm">Transferred Referral No: <strong class="p-sm" id="transferredReferralNo"></strong></h4>


                </fieldset>
            </form>

           </div>
         </div>
      </div>
    </div>
  </div>
  <?php if ($page == "nursing"): ?>
    <script src="<?php echo $root_path ?>js/care_md/plugins/jquery-steps/jquery.steps.min.js" type="text/javascript"></script>
    <script src="<?php echo $root_path ?>js/care_md/plugins/jquery-steps/main.js" type="text/javascript"></script>
    <script src="<?php echo $root_path ?>/js/care_md/bootstrap-select.min.js"></script>
  <?php endif?>
</div>

<script type="text/javascript">
<?php if ($page == "nursing"): ?>

$(function () {
    $('.selectMultiple').selectpicker({
        includeSelectAllOption: true,
        noneSelectedText: 'Select Diagnosises'
    });

});
<?php endif;?>
</script>

<script type="text/javascript">
function verify_nhif_approval(card_no,approval,item_code) {

  var approval_no=approval;

  alert("approval number is: "+approval_no);
  return false;

  




  console.log('This function was executed');

  var accessToken = null;
  var logindata = {
      "grant_type": "password",
      "username": "<?php echo $nhif_user ; ?>",
      "password": "<?php echo $nhif_pwd; ?>"
  };


  //alert(JSON.stringify(logindata));



  var validNHIFApprovalNo = false;

  var url = "<?php echo $nhif_base; ?>/Token";
  $.ajax(url, {
      type: "POST",
      data: logindata,
      timeout: 10000,
      async: false
  }).done(function(data) {
      accessToken = data.access_token;
      


      $.ajax("<?php echo $nhif_base; ?>/breeze/Verification/GetReferenceNoStatus?CardNo=" + card_no + "&ReferenceNo=" + approval_no + "&ItemCode=" + item_code, {
          headers: {
              "Authorization": "Bearer " + accessToken
          },
          xhrFields: {
              withCredentials: true
          },
          async: false
      }).done(function(data) {
          console.log(data);

          if (data.Status === 'INVALID') {
              validNHIFApprovalNo = false;
              

          } else if (data.Status === 'VALID') {
              validNHIFApprovalNo = true;
          }
      }).fail(function(data) {
          if (data.status === 0) {
              alert("Error Connecting to NHIF Server!\n\nPlease check your network connection!");
          } else {
              if (data.status === 404) {
                  alert(JSON.stringify(data.responseText));
              } else {
                  alert(JSON.stringify(data.responseText));
              }
          }

          return false;
      });
  }).fail(function(data) {
      if (data.status === 400) {
          alert("Error Login in to NHIF Server!\n" + JSON.stringify(data.responseJSON.error_description));
      } else {
          alert("Error Login in to NHIF Server!\n\nPlease check your network connection\nor contact your administrator!");
      }

      return false;
  });

  return validNHIFApprovalNo;
}
 


  

  
</script>
<script >
  var authbutton=$('#authorize_btn').val();
var allowVerification=$('#allowVerification').val();
if (authbutton) {
  nhifPatient=true;
}

//alert(allowVerification);





if (nhifPatient&&allowVerification=="") {
  hide_links();
  $('.ipd').hide();
  $('.opd').hide();
  $('.verify').hide();
  $('#authorize_btn').hide();
}else if (nhifPatient&&authbutton) {
  hide_links();
  $('.ipd').hide();
  $('.opd').hide();
}
</script>

<script>
  function showConsultation(){
    var pid=document.getElementById("pid").value;
    var qualificationID=document.getElementById("qualification").value;
    var url = "<?php echo $root_path ?>modules/registration_admission/return_consultation_jquery.php";
    $.ajax(url,{
      type: "POST",
      data: {
        id: qualificationID,
        patientid:pid 
      },
      timeout: 10000,
      async: false

    }).done(function (data) {
      // alert(data);
      // return false;
      console.log(data);
       $("#consultation_fee").empty();
       $("#consultation_fee").append(data);


    

      

  }).fail(function(data,textStatus, error){

    console.log(error);

  });

  }
</script>
<script type="text/javascript">
  function CreateNhifForm(type, encounter_nr, submitURL) {   
    
     var fileStatus="";

     //create file
     $.get("<?php echo $root_path ?>modules/nhif/printPatientFile.php?type="+type+"&encounter_nr="+encounter_nr+"&save=1", function(printed) {

        if (printed == "file_created") {
          fileStatus="Created";

        }else{

          fileStatus="file_not_created";

        }


    //window.location.href = submitURL;

  }).fail( function(data, textStatus, error) {
      alert('Unable to save patient file. Please try again')
       console.log(error);
       return false;
  });
     //end create file

    

  $.get("<?php echo $root_path ?>modules/nhif/createNhifForm.php?type="+type+"&encounter_nr="+encounter_nr+"&save=1", function(created) { 

   if (created == "no file" || fileStatus == "file_not_created") {
     alert("NHIF FORM IS NOT CREATED OR FILE CORRUPTED, PLEASE TRY AGAIN");
     return false;

   }



    
    window.location.href = submitURL;

  }).fail( function(data, textStatus, error) {
      alert('Unable to save patient file. Please try again')
       console.log(error);
  });

}
</script>

<script>
  function finalApproval(type, encounter_nr, submitURL) {     
    
    window.location.href = submitURL;

 

}
  
</script>

<script>
  function undoApproval(type, encounter_nr, submitURL) {     
    
    window.location.href = submitURL;

 

}
  
</script>
<script type="text/javascript">
   function getinfo(pn) {
<?php
/* if($edit) */ {
    echo '
        urlholder="' . $root_path . 'modules/nursing/nursing-station-patientdaten.php' . URL_REDIRECT_APPEND;
    echo '&pn=" + pn + "';
    echo "&pday=$pday&pmonth=$pmonth&pyear=$pyear&edit=$edit&station=$station";
    echo '";';
    echo 'patientwin=window.open(urlholder, "_self");';
}
/* else echo '
  window.location.href=\'nursing-station-pass.php'.URL_APPEND.'&rt=pflege&edit=1&station='.$station.'\''; */
?>
            }
  
</script>
<script>

  function deleteDx(caseNumber) {
           var result = confirm("Are you sure You want to delete this diagnosis?");
                 if (result == true) {                                 


                  $.getJSON("./deleteDx.php?caseId="+caseNumber, function(data){          



                    if (data.updated == 1) {
                      alert(caseNumber+ " Is is deleted Successfully");
                    }
                    
                   



                  }).fail( function(data, textStatus, error) {
                    console.log(error);
                     });

                  
                  
                  

      }else{
        return false;
      }
    }
                
                    
                 
        



    
  
</script>



</body>