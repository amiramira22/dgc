
 <div id="content_capbon"  >aaa</div>
  


	
			






<script>




	// LINE CHART 2

	$('#content_capbon').highcharts({
        chart: {
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 0,
                beta: 0,
                viewDistance: 0,
                depth: 0
            }
        },
        title: {
            text: 'Monthly Availibility Status'
        },
        xAxis: {
            categories: <?php echo $brands;?>
        },
        yAxis: {
            min: 0,
            max: 100,
            title: {
                text: 'Percent (%)'
            }
        },
        legend: {
            reversed: true
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    style: {
                        textShadow: '0 0 3px black'
                    }
                }
            }
        },
        series: [{
            name: 'AV',
            color:'green',
            data: <?php echo $perc_av_per_zone;?>
        }, {
            name: 'OOS',
            color:'red',
            data: <?php echo $perc_oos_per_zone;?>
        }]
    });
      
        	



	</script>