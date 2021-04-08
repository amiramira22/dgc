
<script>
    var chart = AmCharts.makeChart("brand2_chart_div_<?php echo $brand_id; ?>", {
        "type": "pie",
        "theme": "light",
        "dataProvider": <?php print_r($stock_issue_data); ?>,
        "titleField": "title",
        "valueField": "value",

        "titles": [
            {
                "text": "stock issues - <?php echo date('F'); ?>",
                "size": 15
            }
        ],

        "labelRadius": 5,
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[title]]: <b>[[value]]</b></span>",
        "colorField": "color",
        "radius": "42%",
        "innerRadius": "60%",
        "labelText": "[[title]] [[value]]",
        "export": {
            "enabled": true
        }
    });
</script>











