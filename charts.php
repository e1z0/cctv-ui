<?php
require_once("config.php");
require_once("header.php");
?>
<center>
<h1>Charts</h1>
</center>
<?php require_once("navbar.php"); ?>

<div id="chart" style="min-height: 365px;">
</div>

<script>
var personstmp = <?php echo $redis->hget("cameras_activity_graph","persons"); ?>;
var carstmp = <?php echo $redis->hget("cameras_activity_graph","cars"); ?>;
var truckstmp = <?php echo $redis->hget("cameras_activity_graph","trucks"); ?>;

var labels = [];
var persons = [];
var cars = [];
var trucks = [];

for (var key in personstmp) {
labels.push(key);
}

for (var key in personstmp) {
persons.push(personstmp[key]);
}

for (var key in carstmp) {
cars.push(carstmp[key]);
}

for (var key in truckstmp) {
trucks.push(truckstmp[key]);
}



        var options = {
          series: [
          {
          name: 'Žmonės',
          type: 'area',
          data: persons
          },
          {
	  name: 'Mašinos',
          type: 'line',
          data: cars
          },
	  {
          name: 'Sunki technika',
          type: 'line',
          data: trucks
          }
        ],
          chart: {
          type: 'line',
          height: 350,
          },
stroke: {
          curve: 'smooth'
        },
        fill: {
          type:'solid',
          opacity: [0.35, 1],
        },
        labels: labels,
        markers: {
          size: 1
        },
        yaxis: [
          {
            title: {
              text: 'Šetono aktyvumas',
            },
          },
          {
            opposite: true,
            title: {
              text: 'Šetono aktyvumas',
            },
          },
        ],
        tooltip: {
          shared: true,
          intersect: false,
          y: {
            formatter: function (y) {
              if(typeof y !== "undefined") {
                return  y.toFixed(0) + " užfiksavimai";
              }
              return y;
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
</script>
<?php
require_once("footer.php");
?>
