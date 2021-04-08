
 <div id="content_judy"  >aaa</div>
  


	
			






<script>



$('#content_judy').highcharts({
        chart: {
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 0,
                beta: 0,
                viewDistance: 0,
                depth: 70
            }
        },
        title: {
            text: 'Daily Availibility Status'
        },
        xAxis: {
            categories: <?php print_r($zones);?>
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
        series: [
		
		{
            name: 'AV',
            color:'green',
            data: <?php print_r($perc_av);?>
        }, 
		{
            name: 'OOS',
            color:'red',
            data: <?php print_r($perc_oos);?>
        }
		
		]
    });
        	



	</script>