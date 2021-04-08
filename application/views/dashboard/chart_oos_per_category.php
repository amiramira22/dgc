<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/gauge.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>


<script>
    var chart = AmCharts.makeChart("brand_categorie_chart_div_<?php echo $brand_categorie_id; ?>", {
        "type": "gauge",
        "theme": "light",
        "axes": [{
                "axisAlpha": 0,
                "tickAlpha": 0,
                "labelsEnabled": false,
                "startValue": 0,
                "endValue": 100,
                "startAngle": 0,
                "endAngle": 270,
                "bands": <?php print_r($oos_data); ?>
            }],

        "allLabels": <?php print_r($brand_cat_data_label); ?>,

        "titles": [
            {
                "text": "OOS - <?php echo date('F'); ?>",
                "size": 15
            }
        ],
        "export": {
            "enabled": true
        }

    });
</script>


