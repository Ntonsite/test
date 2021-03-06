(function($) {

    var form = $("#tranferNHIFPatient-form");
    form.steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        labels: {
            previous : 'Prev',
            next : 'Next',
            finish : 'Close',
            current : ''
        },
        titleTemplate : '<h3 class="title">#title#</h3>',
        onFinished: function (event, currentIndex)
        {
        },
        onStepChanging: function (event, currentIndex, newIndex) {

        if (currentIndex == 0) {
            var authorizationText = $("#AuthorizationNoField").val();
            if (!authorizationText) {
                alert('Patient unauthorized. Please authorize the patient first to continue.');
                return false;
            }
        }

        if (currentIndex == 1) {
            var FormPhysicianName = $("#PhysicianNameField").val();
            var FormPhysicianQualificationID = $("#PhysicianQualificationID").val();
            var FormPhysicianMobileNo = $("#PhysicianMobileNoField").val();
            var FormServiceIssuingFacilityCode = $("#ServiceIssuingFacilityCodeField").val();
            var FormReferringDiagnosis = $("#ReferringDiagnosis").val();
            var FormReasonsForReferral = $("#ReasonsForReferral").val();
            if (!FormPhysicianName) {
                alert("Please Enter Physician Name")
                $("#FormPhysicianName").focus();
                return false;
            }else if (!FormPhysicianQualificationID) {
                alert("Please Enter Physician Qualification")
                $("#FormPhysicianQualificationID").focus();
                return false;
            }else if (!FormPhysicianMobileNo) {
                alert("Please Enter Physician Mobile Number")
                $("#FormPhysicianMobileNo").focus();
                return false;
            }else if (!FormServiceIssuingFacilityCode) {
                alert("Please Enter Service Issuing FacilityCode")
                $("#FormServiceIssuingFacilityCode").focus();
                return false;
            }else if (!FormReferringDiagnosis) {
                alert("Please Enter Referring Diagnosis")
                $("#FormReferringDiagnosis").focus();
                return false;
            }else if (!FormReasonsForReferral) {
                alert("Please Enter Reasons For Referral")
                $("#FormReasonsForReferral").focus();
                return false;
            }
            else {
                submitTransferNHIFPatient();
                return true;  
            }
           
        }else {
            return true;
        }
     },
    });

    $('a[href="#finish"]').click(function() {
        window.location.reload();
    });
  
    
})(jQuery);

$.fn.steps.setStep = function (step)
{
  var currentIndex = $(this).steps('getCurrentIndex');
  for(var i = 0; i < Math.abs(step - currentIndex); i++){
    if(step > currentIndex) {
      $(this).steps('next');
    }
    else{
      $(this).steps('previous');
    }
  } 
};
