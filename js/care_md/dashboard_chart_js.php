<?php 
require_once('./roots.php');
 ?>
<script>

  $('.topdiseases').Wload({text:' Loading'})
  // $('.patienttrends').Wload({text:' Loading'})
  // $('.servedpatients').Wload({text:' Loading'})
  // $('.frequentdrugs').Wload({text:' Loading'})

// $.getJSON("<?php echo $root_path.'modules/dashboard/patientTrends.php' ?>").done(function(data){

//     var config = {
//       type: 'line',
//       data: {
//         labels: data.labels,
//         datasets: data.series
//       },
//       options: {
//         responsive: true,
//         title: {
//           display: true,
//         },
//         legend: {
//             position: 'left',
//             labels: {
//                 boxWidth: 20,
//                 padding: 20
//             }
//         }
//       }
//     };
//      $('.patienttrends').Wload('hide',{time:0})

     
// }).fail(function(data){
//   console.log(data);
// })


function showPatientTrends(){
  $('.patienttrends').Wload({text:' Loading'});
  var period = $('#patienttrendselect').val();

  $.getJSON("<?php echo $root_path.'modules/dashboard/patientTrends.php?period=' ?>"+period).done(function(data){

    var config = {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: data.series
      },
      options: {
        responsive: true,
        title: {
          display: true,
        },
        legend: {
            position: 'left',
            labels: {
                boxWidth: 20,
                padding: 20
            }
        }
      }
    };
     $('.patienttrends').Wload('hide',{time:0})
     var ctx = document.getElementById('patienttrends').getContext('2d');
      patients = new Chart(ctx, config);

      patients.destroy();

      var ctx = document.getElementById('patienttrends').getContext('2d');
      patients = new Chart(ctx, config);

}).fail(function(data){
  console.log(data);
})

}


$.getJSON("<?php echo $root_path.'modules/dashboard/TopDiseases.php' ?>").done(function(response){
var diseaseConfig = {
      type: 'doughnut',
      data: {
        datasets: [{
          data: response.data,
          backgroundColor: response.background,
          label: 'Top Diseases'
        }],
        labels: response.labels
      },
      options: {
        responsive: true,
        legend: {
            position: 'right',
            labels: {
                boxWidth: 20,
                padding: 20
            }
        },
        title: {
          display: true,
          // text: 'Top Diseases'
        },
        animation: {
          animateScale: true,
          animateRotate: true
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              var dataset = data.datasets[tooltipItem.datasetIndex];
              var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                return previousValue + currentValue;
              });


              var currentValue = dataset.data[tooltipItem.index];
              var currentLabel = data.labels[tooltipItem.index];
              var percentage = Math.floor(((currentValue/total) * 100)+0.5);         
              return  currentLabel + " " + numberWithCommas(currentValue) + " equivalent to " + percentage + "%";
            }
          }
        }
      }
    };

    var dcht = document.getElementById('topdiseases').getContext('2d');
    topDiseasesChart = new Chart(dcht, diseaseConfig);
     $('.topdiseases').Wload('hide',{time:0})

 });


function showTopDiseases(){
   $('.topdiseases').Wload({text:' Loading'})
  var count = $('#topdiseasesselect').val();
  var period = $('#topdiseaseperiod').val();

  $.getJSON("<?php echo $root_path.'modules/dashboard/TopDiseases.php/?count=' ?>"+count +"&period=" + period).done(function(responseselect){
    var diseaseConfig = {
      type: 'doughnut',
      data: {
        datasets: [{
          data: responseselect.data,
          backgroundColor: responseselect.background,
          label: 'Top Diseases'
        }],
        labels: responseselect.labels
      },
      options: {
        responsive: true,
        legend: {
            position: 'right',
            labels: {
                boxWidth: 20,
                padding: 20
            }
        },
        title: {
          display: true,
          // text: 'Top Diseases'
        },
        animation: {
          animateScale: true,
          animateRotate: true
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              var dataset = data.datasets[tooltipItem.datasetIndex];
              var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                return previousValue + currentValue;
              });


              var currentValue = dataset.data[tooltipItem.index];
              var currentLabel = data.labels[tooltipItem.index];
              var percentage = Math.floor(((currentValue/total) * 100)+0.5);         
              return  currentLabel + " " + numberWithCommas(currentValue) + " equivalent to " + percentage + "%";
            }
          }
        }
      }
    };
      $('.topdiseases').Wload('hide',{time:0})
      topDiseasesChart.destroy();
      var dcht = document.getElementById('topdiseases').getContext('2d');
      topDiseasesChart = new Chart(dcht, diseaseConfig);

  })  
}



// $.getJSON("<?php echo $root_path.'modules/dashboard/FrequentDrugs.php' ?>").done(function(response){
// var drugconfig = {
//       type: 'doughnut',
//       data: {
//         datasets: [{
//           data: response.data,
//           backgroundColor: response.background,
//           label: 'Frequent Drugs'
//         }],
//         labels: response.labels
//       },
//       options: {
//         responsive: true,
//         legend: {
//             position: 'left',
//             labels: {
//                 boxWidth: 20,
//                 padding: 20
//             }
//         },
//         title: {
//           display: true,
//           text: 'Frequent Drugs'
//         },
//         animation: {
//           animateScale: true,
//           animateRotate: true
//         },
//         tooltips: {
//           callbacks: {
//             label: function(tooltipItem, data) {
//               var dataset = data.datasets[tooltipItem.datasetIndex];
//               var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
//                 return previousValue + currentValue;
//               });


//               var currentValue = dataset.data[tooltipItem.index];
//               var currentLabel = data.labels[tooltipItem.index];
//               var percentage = Math.floor(((currentValue/total) * 100)+0.5);         
//               return  currentLabel + " " + numberWithCommas(currentValue) + " equivalent to " + percentage + "%";
//             }
//           }
//         }
//       }
//     };

//     $('.frequentdrugs').Wload('hide',{time:0})
//     var freqntdchart = document.getElementById('frequentdrugs').getContext('2d');
//    frequentdrugs = new Chart(freqntdchart, drugconfig);
// });


function showFrequentDrugs(){
  $('.frequentdrugs').Wload({text:' Loading'})
   var count = $('#frequentdrugsselect').val();
   var period = $('#frequentdrugsperiod').val();
  $.getJSON("<?php echo $root_path.'modules/dashboard/FrequentDrugs.php?count=' ?>"+count + "&period=" + period).done(function(response){
  var drugconfig = {
      type: 'doughnut',
      data: {
        datasets: [{
          data: response.data,
          backgroundColor: response.background,
          label: 'Frequent Drugs'
        }],
        labels: response.labels
      },
      options: {
        responsive: true,
        legend: {
            position: 'left',
            labels: {
                boxWidth: 20,
                padding: 20
            }
        },
        title: {
          display: true,
          text: 'Frequent Drugs'
        },
        animation: {
          animateScale: true,
          animateRotate: true
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              var dataset = data.datasets[tooltipItem.datasetIndex];
              var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                return previousValue + currentValue;
              });


              var currentValue = dataset.data[tooltipItem.index];
              var currentLabel = data.labels[tooltipItem.index];
              var percentage = Math.floor(((currentValue/total) * 100)+0.5);         
              return  currentLabel + " " + numberWithCommas(currentValue) + " equivalent to " + percentage + "%";
            }
          }
        }
      }
    };
   
    var freqntdchart = document.getElementById('frequentdrugs').getContext('2d');

    $('.frequentdrugs').Wload('hide',{time:0})
    frequentdrugs = new Chart(freqntdchart, drugconfig);
});

}


// $.getJSON("<?php echo $root_path.'modules/dashboard/ServedPatients.php' ?>").done(function(response){
// var barChartData = {
//       labels: response.labels,
//       datasets: response.datasets
//     };
    
//    var servedctx = document.getElementById('servedpatients').getContext('2d');
//     servedPatientBar = new Chart(servedctx, {
//       type: 'bar',
//       data: barChartData,
//       options: {
//         title: {
//           display: true,
//           text: 'Served & Unserved Customers'
//         },
//         tooltips: {
//           mode: 'index',
//           intersect: false
//         },
//         responsive: true,
//         scales: {
//           xAxes: [{
//             stacked: true,
//           }],
//           yAxes: [{
//             stacked: true
//           }]
//         }
//       }
//     });
//     $('.servedpatients').Wload('hide',{time:0})


// }).fail(function(){
//   console.log('error');
// })

function showServedPatients(){
   var period = $('#servedpatientselect').val();
   $('.servedpatients').Wload({text:' Loading'})
   $.getJSON("<?php echo $root_path.'modules/dashboard/ServedPatients.php?period=' ?>"+period).done(function(response){
    var barChartData = {
          labels: response.labels,
          datasets: response.datasets
        };
        if (typeof servedPatientBar !== 'undefined') {
          servedPatientBar.destroy(); 
        }
        var servedctx = document.getElementById('servedpatients').getContext('2d');
        servedPatientBar = new Chart(servedctx, {
          type: 'bar',
          data: barChartData,
          options: {
            title: {
              display: true,
              text: 'Served & Unserved Customers'
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
      $('.servedpatients').Wload('hide',{time:0})

    }).fail(function(){
      console.log('error');
    })

}

const numberWithCommas = (x) => {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}




</script>