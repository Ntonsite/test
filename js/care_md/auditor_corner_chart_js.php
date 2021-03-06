<?php 
require_once('./roots.php');
 ?>
<script>

// $('.pharmacypatients').Wload({text:' Loading'})
   
$(window).on('load', function(){
  var type = $('#pharmacypatienttype').val();
  var insurance = $('#pharmacypatientinsurance').val();
  var period = $('#pharmacypatientperiod').val();
  let today = new Date();
  let dd = today.getDate();
  let mm = today.getMonth()+1; 
  const yyyy = today.getFullYear();
  var start_date =  end_date = `${yyyy}-${mm}-${dd}`;
  // showPharmacyPatients(type, insurance, period, start_date, end_date);
});


function showPharmacyPatients(type, insurance, period, start_date, end_date){
  $(".pharmacy-btn").attr("disabled", true);
  if (!type) {
    var type = $('#pharmacypatienttype').val();
  }
  if (!insurance) {
    var insurance = $('#pharmacypatientinsurance').val();  
  }
  if (!period) {
    var period = $('#pharmacypatientperiod').val();  
  }
  if (!start_date) {
    var start_date = $('#pharmacy_start_date').val();  
  }
  if (!end_date) {
    var end_date = $('#pharmacy_end_date').val();  
  }
   $('.pharmacypatients').Wload({text:'Loading Patients'})
   $.getJSON("<?php echo $root_path.'modules/reporting_tz/PharmacyPatients.php?period=' ?>"+period + "&type="+type + "&insurance=" +insurance + "&start_date=" + start_date + "&end_date=" + end_date
  ).done(function(response){
    var barChartData = {
          labels: response.labels,
          datasets: response.datasets
        };
        if (typeof servedPatientBar !== 'undefined') {
          servedPatientBar.destroy(); 
        }
        var servedctx = document.getElementById('pharmacypatients').getContext('2d');
        servedPatientBar = new Chart(servedctx, {
          type: 'bar',
          data: barChartData,
          options: {
            title: {
              display: false,
              text: 'Pharmacy Trend'
            },
            legend: {
              display: true,
              labels: {
                boxWidth: 25
              }
            },
            tooltips: {
              mode: 'index',
              intersect: false
            },
            responsive: true,
            scales: {
              xAxes: [{
                stacked: true,
              }],
              yAxes: [{
                stacked: true
              }]
            }
          }
        });
      $('.pharmacypatients').Wload('hide',{time:0})
      $('#totalServedPaid').text(numberWithCommas(response.total.served_paid));
      $('#totalPendingPaid').text(numberWithCommas(response.total.pending_paid));
      $('#totalServedUnPaid').text(numberWithCommas(response.total.served_unpaid));
      $('#totalPendingUnpaid').text(numberWithCommas(response.total.pending_unpaid));
      $('#totalCollected').text(numberWithCommas(response.total.total_collected));
      $('#totalUnCollected').text(numberWithCommas(response.total.total_uncollected));
      $('#percentUncollected').text(numberWithCommas(response.total.percentage_uncollected) + "%") ;
      $(".pharmacy-btn").attr("disabled", false);
      if (period == 'custom' || period == '') {
        $(".pharmacypopover").popover('hide');
        $("#pharmacyrangedates").text("Date Range: " + response.start_date + " to " + response.end_date);
        $('#pharmacyrangedatesdiv').show();
        $('#pharmacypatientperiod').val('');
        window.PharmacyStartDate = response.StartDate;
        window.PharmacyEndDate = response.EndDate;

      }else{
        $('#pharmacyrangedatesdiv').hide();
      }
      $('.revenue').attr('url', "<?php echo $root_path;?>modules/reporting_tz/DetailedRevenue.php?start_date="+ response.start_date + "&end_date="+response.end_date);
    }).fail(function(){
      console.log('error');
    })

}

function showLaboratoryPatients(){
  $(".lab-btn").attr("disabled", true);
   $('.laboratorypatients').Wload({text:'Loading Patients'});
   var ward = $("#laboratorypatientward").val();
   var insurance = $("#laboratorypatientinsurance").val();
   var period = $("#laboratorypatientperiod").val();
   var type = $("#laboratorypatienttype").val();
   var start_date = $('#laboratory_start_date').val();
   var end_date = $('#laboratory_end_date').val();

   $.getJSON("<?php echo $root_path.'modules/reporting_tz/LaboratoryPatients.php?period=' ?>"+period + "&type="+type + "&insurance=" +insurance+ "&start_date=" +start_date+ "&end_date=" +end_date).done(function(response){
        var labBarChartData = {
          labels: response.labels,
          datasets: response.datasets
        };
        if (typeof labPatientBar !== 'undefined') {
          labPatientBar.destroy(); 
        }
        var labpatientctx = document.getElementById('laboratorypatients').getContext('2d');
        labPatientBar = new Chart(labpatientctx, {
          type: 'bar',
          data: labBarChartData,
          options: {
            title: {
              display: false,
              text: 'Lab Patients'
            },
            tooltips: {
              mode: 'index',
              intersect: false
            },
            legend: {
              display: true,
              labels: {
                boxWidth: 25
              }
            },
            responsive: true,
            scales: {
              xAxes: [{
                stacked: true,
              }],
              yAxes: [{
                stacked: true
              }]
            }
          }
        });
      $('.laboratorypatients').Wload('hide',{time:0})
      $('#labtotalServedUnPaid').text(numberWithCommas(response.total.served_unpaid));
      $('#labtotalPendingUnPaid').text(numberWithCommas(response.total.pending_unpaid));
      $('#labtotalCollected').text(numberWithCommas(response.total.total_collected));
      $('#labtotalUnCollected').text(numberWithCommas(response.total.total_uncollected));
      $('#labpercentUncollected').text(numberWithCommas(response.total.percentage_uncollected) + "%") ;
      $(".lab-btn").attr("disabled", false);

      if (period == 'custom' || period == '') {
        $(".laboratorypopover").popover('hide');
        $("#laboratoryrangedates").text("Date Range: " + response.start_date + " to " + response.end_date);
        $('#laboratoryrangedatesdiv').show();
        $('#laboratorypatientperiod').val('');
        window.LabStartDate = response.StartDate;
        window.LabEndDate = response.EndDate;
      }else{
        $('#laboratoryrangedatesdiv').hide()
      }

    }).fail(function(){
      console.log('error');
    })

}


function showRadiologyPatients(){
  $(".radiology-btn").attr("disabled", true);
   $('.radiologypatients').Wload({text:'Loading Patients'});
   var ward = $("#radiologypatientward").val();
   var type = $("#radiologypatienttype").val();
   var insurance = $("#radiologypatientinsurance").val();
   var period = $("#radiologypatientperiod").val();
   var start_date = $('#radiology_start_date').val();
   var end_date = $('#radiology_end_date').val();

   $.getJSON("<?php echo $root_path.'modules/reporting_tz/RadiologyPatients.php?period=' ?>"+period + "&type="+type + "&insurance="+insurance+ "&start_date="+start_date+ "&end_date="+end_date).done(function(response){
        var radiologyBarChartData = {
          labels: response.labels,
          datasets: response.datasets
        };
        if (typeof radiologyPatientBar !== 'undefined') {
          radiologyPatientBar.destroy(); 
        }
        var radiologypatientctx = document.getElementById('radiologypatients').getContext('2d');
        radiologyPatientBar = new Chart(radiologypatientctx, {
          type: 'bar',
          data: radiologyBarChartData,
          options: {
            title: {
              display: false,
              text: 'Lab Patients'
            },
            tooltips: {
              mode: 'index',
              intersect: false
            },
            legend: {
              display: true,
              labels: {
                boxWidth: 25
              }
            },
            responsive: true,
            scales: {
              xAxes: [{
                stacked: true,
              }],
              yAxes: [{
                stacked: true
              }]
            }
          }
        });
      $('.radiologypatients').Wload('hide',{time:0})
      $('#radiologytotalServedPaid').text(numberWithCommas(response.total.served_paid));
      $('#radiologytotalPendingPaid').text(numberWithCommas(response.total.pending_paid));
      $('#radiologytotalServedUnpaid').text(numberWithCommas(response.total.served_unpaid));
      $('#radiologytotalPendingUnpaid').text(numberWithCommas(response.total.pending_unpaid));
      $('#radiologytotalCollected').text(numberWithCommas(response.total.total_collected));
      $('#radiologytotalUnCollected').text(numberWithCommas(response.total.total_uncollected));
      $('#radiologypercentUncollected').text(numberWithCommas(response.total.percentage_uncollected) + "%") ;
      $(".radiology-btn").attr("disabled", false);
      if (period == 'custom' || period == '') {
        $(".radiologypopover").popover('hide');
        $("#radiologyrangedates").text("Date Range: " + response.start_date + " to " + response.end_date);
        $('#radiologyrangedatesdiv').show();
        $('#radiologypatientperiod').val('');
         window.RadioStartDate = response.StartDate;
        window.RadioEndDate = response.EndDate;
      }else{
        $('#radiologyrangedatesdiv').hide()
      }

    }).fail(function(){
      console.log('error');
    })

}

const numberWithCommas = (x) => {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


function viewRadiologyRecords(category) {
  var insurance = $("#radiologypatientinsurance").val();
  var period = $("#radiologypatientperiod").val();
  var start_date = window.RadioStartDate;
  var end_date = window.RadioEndDate;
   var type = $("#radiologypatienttype").val();

  var radiologyurl = "<?php echo $root_path.'modules/reporting_tz/RadiologyPatients.php?period=' ?>"+period + "&type="+type+ "&insurance="+insurance+ "&category="+category+ "&start_date="+start_date + "&end_date="+end_date;

  if (window.radiologyTable) {
    window.radiologyTable.destroy();  
  }


  $("#radiologyModal").modal('show');
    window.radiologyTable = $("#radiologydatatable").DataTable({
      "aoColumnDefs": [
      // {'bSortable': false, 'aTargets': [0, 6]}
      ],
      "ordering": false,
      "paging": false,
      "processing": true,
      "serverSide": true,
      "aLengthMenu": [[-1, 100, 200, -1], ["All", 100, 200, "All"]],
      "iDisplayLength": 50,
      "order": [1, "asc"],
      "ajax": {
          "url": radiologyurl,
          data: {data_id:'<?=isset($_GET['data_id'])?$_GET['data_id']:false;?>'},
          type: 'get'
      },
      "aoColumns": [ 
      {"sClass": "left"},
      {"sClass": "left"},
      {"sClass": "left"},
      {"sClass": "left"},
      {"sClass": "left"},
      {"sClass": "right"},
      {"sClass": "right"},
      {"sClass": "right"},
      {"sClass": "right"},
      {"sClass": "right"},
      {"sClass": "right"},
      ],
      dom: 'Brtip',
      buttons: [
          'copy',
          {
            extend: 'excel',
            messageTop: 'Radiology Records',
          },
          {
            extend: 'csv',
            messageTop: 'Radiology Records',
          },
          {
            extend: 'print',
            messageTop: 'Radiology Records',
            orientation: 'landscape',
          },
          {
            extend: 'pdf',
            messageTop: 'Radiology Records',
            orientation: 'landscape',
          }
         
      ]
  });
}

function viewLaboratoryRecords(category) {
  var ward = $("#laboratorypatientward").val();
  var insurance = $("#laboratorypatientinsurance").val();
  var period = $("#laboratorypatientperiod").val();
  var start_date = window.LabStartDate;
  var end_date = window.LabEndDate;
  var type = $("#laboratorypatienttype").val();
  var laboratoryurl = "<?php echo $root_path.'modules/reporting_tz/LaboratoryPatients.php?period=' ?>"+period + "&type="+type + "&insurance=" +insurance+ "&category=" + category+ "&start_date=" + start_date+ "&end_date=" + end_date;

  if (window.laboratoryTable) {
    window.laboratoryTable.destroy();  
  }


  $("#laboratoryModal").modal('show');
    window.laboratoryTable = $("#laboratorydatatable").DataTable({
      "aoColumnDefs": [
      // {'bSortable': false, 'aTargets': [0, 6]}
      ],
      "ordering": false,
      "paging": false,
      "bInfo": false,
      "processing": true,
      "serverSide": true,
      "aLengthMenu": [[50, 100, 200, -1], [50, 100, 200, "All"]],
      "iDisplayLength": 50,
      "order": [1, "asc"],
      "ajax": {
          "url": laboratoryurl,
          data: {data_id:'<?=isset($_GET['data_id'])?$_GET['data_id']:false;?>'},
          type: 'get'
      },
      "aoColumns": [ 
      {"sClass": "left"},
      {"sClass": "left"},
      {"sClass": "left"},
      {"sClass": "left"},
      {"sClass": "left"},
      {"sClass": "left"},
      {"sClass": "left"},
      {"sClass": "right"},
      {"sClass": "right"},
      {"sClass": "right"},
      ],
      dom: 'Brtip',
      buttons: [
          'copy',
          {
            extend: 'excel',
            messageTop: 'Laboratory Records',
          },
          {
            extend: 'csv',
            messageTop: 'Laboratory Records',
          },
          {
            extend: 'print',
            messageTop: 'Laboratory Records',
            orientation: 'landscape',
          },
          {
            extend: 'pdf',
            messageTop: 'Laboratory Records',
            orientation: 'landscape',
          }
         
      ]
  });
}

function viewPharmacyRecords(category) {
  var insurance = $('#pharmacypatientinsurance').val();
  var type = $('#pharmacypatienttype').val();
  var period = $('#pharmacypatientperiod').val();
  var start_date = window.PharmacyStartDate;
  var end_date = window.PharmacyEndDate;
  var pharmacyurl = "<?php echo $root_path.'modules/reporting_tz/PharmacyPatients.php?period=' ?>"+period + "&type="+type + "&insurance=" +insurance+ "&category=" + category + "&start_date=" + start_date+ "&end_date=" + end_date;

  if (window.pharmacyTable) {
    window.pharmacyTable.destroy();  
  }

  $("#pharmacyModal").modal('show');
  $('.labtableoverlay').Wload({text:' Processing...'})
    window.pharmacyTable = $("#pharmacydatatable").DataTable({
      "aoColumnDefs": [
      // {'bSortable': false, 'aTargets': [0, 6]}
      ],
      
      "ordering": false,
      "paging": false,
      "bInfo": false,
      "processing": true,
      "serverSide": true,
      "aLengthMenu": [[-1, 100, 200, -1], ["All", 100, 200, "All"]],
      "iDisplayLength": 50,
      "order": [1, "asc"],
      "ajax": {
          "url": pharmacyurl,
          data: {data_id:'<?=isset($_GET['data_id'])?$_GET['data_id']:false;?>'},
          type: 'get'
      },
      "aoColumns": [ 
      // {"sClass": "left"},
      {"sClass": "left"},
      {"sClass": "left"},
      {"sClass": "left"},
      {"sClass": "left"},
      {"sClass": "right"},
      {"sClass": "right"},
      {"sClass": "right"},
      {"sClass": "right"},
      {"sClass": "right"},
      {"sClass": "right"},
      {"sClass": "right"},
      {"sClass": "right"},
      {"sClass": "right"},
      ],
      dom: 'Brtip',
      buttons: [
          'copy',
          {
            extend: 'excel',
            messageTop: 'Pharmacy Records',
          },
          {
            extend: 'csv',
            messageTop: 'Pharmacy Records',
          },
          {
            extend: 'print',
            messageTop: 'Pharmacy Records',
            orientation: 'landscape',
          },
          {
            extend: 'pdf',
            messageTop: 'Pharmacy Records',
            orientation: 'landscape',
          }
         
      ],
      fnInitComplete : function() {
        $('.labtableoverlay').Wload('hide',{time:0})
     }
  });
}

function openPharmacyPopover() {
   var period = $('#pharmacypatientperiod').val();

   $(".pharmacypopover").popover({placement : 'bottom', 'html': true});
   if (period == 'custom') {
      $(".pharmacypopover").popover('show');
   }else{
      $(".pharmacypopover").popover('hide');
      $("#pharmacy_start_date").val('')
      $("#pharmacy_end_date").val('')
   }
}

function openLaboratoryPopover() {
   var period = $('#laboratorypatientperiod').val();

   $(".laboratorypopover").popover({placement : 'bottom', 'html': true});
   if (period == 'custom') {
      $(".laboratorypopover").popover('show');
   }else{
      $(".laboratorypopover").popover('hide');
      $("#laboratory_start_date").val('')
      $("#laboratory_end_date").val('')
   }
}

function openRadiologyPopover() {
   var period = $('#radiologypatientperiod').val();

   $(".radiologypopover").popover({placement : 'bottom', 'html': true});
   if (period == 'custom') {
      $(".radiologypopover").popover('show');
   }else{
      $(".radiologypopover").popover('hide');
      $("#radiology_start_date").val('')
      $("#radiology_end_date").val('')
   }
}

function goToRevenueURL() {
  var revenueURL = $(".revenue").attr('url');
  if (revenueURL) {
    window.location.href = revenueURL;
  }
}

$(document).ready(function() {
$(".datatable").dataTable();
});

$(function () {
    $('.selectMulti').selectpicker({
        includeSelectAllOption: true,
        noneSelectedText: 'All Insurances'
    });

});


</script>