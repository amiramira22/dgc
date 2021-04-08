<!-- Chart code -->


<div style="height:305px; width:100%;" id="chartdivstat">
<script>


var chart = AmCharts.makeChart("chartdivstat", {
	 "type": "serial",
    "theme": "light",
    "marginRight":80,
    "autoMarginOffset":20,
    "dataDateFormat": "YYYY-MM-DD HH:NN",
    "dataProvider": <?php print_r($result); ?>,
	"titles": [
		{
			"text": "OOS ",
			"size": 15
		}
	],
    "valueAxes": [{
        "axisAlpha": 0,
        "guides": [ <?php foreach ($brands as $brand){?>
		{
			
			
			"id": "<?php echo $brand['name'];?>",
			
			"title": "<?php echo $brand['name'];?>",
		
			"fillColors" : "<?php echo $brand['color'];?>",
			"valueField": "<?php echo $brand['name'];?>"
		},
		<?php } ?>],
        "position": "left",
        "tickLength": 0
    }],
	
	 "legend": {
        "horizontalGap": 10,
        "maxColumns": 1,
        "position": "right",
		"useGraphSettings": true,
		"markerSize": 10
    },
    "graphs": [
        <?php foreach ($brands as $brand){?>
		{
			 "bullet": "square",
		
	
			"balloonText": "<?php echo $brand['name'];?>:[[value]]",
			
			"id": "<?php echo $brand['name'];?>",
			
			"title": "<?php echo $brand['name'];?>",
		    "lineColor" : "<?php echo $brand['color'];?>",
			"fillColors" : "<?php echo $brand['color'];?>",
			"valueField": "<?php echo $brand['name'];?>"
		},
		<?php } ?>
	
	
	],
   
     "colorField":"color",
    "chartCursor": {
        "fullWidth":true,
        "valueLineEabled":true,
        "valueLineBalloonEnabled":true,
        "valueLineAlpha":0.5,
        "cursorAlpha":0
    },
    "categoryField": "date",
    "categoryAxis": {
        "parseDates": false,
        "axisAlpha": 0,
        "gridAlpha": 0,
        "minorGridAlpha": 0,
        "minorGridEnabled": false
    },
    "export": {
        "enabled": true
     }

});









</script>




<!-- HTML -->
