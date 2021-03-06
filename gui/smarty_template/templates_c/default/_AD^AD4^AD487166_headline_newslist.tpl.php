<?php /* Smarty version 2.6.22, created on 2018-09-29 11:19:16
         compiled from news/headline_newslist.tpl */ ?>
<table width=100%>
    <tr>
        <td>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "news/headline_titleblock.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </td>
    </tr>

    <tr valign=top>
        <td>
            <html>
  <head>
    <script type="text/javascript" src="../../js/graph_loader/line_graph.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Nhif', 'Cash','AAR','Jubilee'],
          ['Jan',  1000,      400, 300, 1000],
          ['Feb',  1170,      460, 400,500],
          ['mar',  660,       1120, 230,350],
          ['apr',  1030,      540,260,410],
        ]);

        var options = {
          title: 'Patient Trends',
          curveType: 'function',
          legend: {position: 'bottom'}
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
  </body>
</html>
            <hr>
        </td>
    </tr>

    <tr valign=top>
        <td>
            b
            <hr>
        </td>
    </tr>

    <tr valign=top>
        <td>
            c
            <hr>
        </td>
    </tr>

</table>