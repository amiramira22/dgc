
<script>
    var chart = AmCharts.makeChart("brand_chart_div_<?php echo $brand_id; ?>", {
        "type": "serial",
        "theme": "light",

        "angle": 30,

        "dataProvider": <?php echo $oos_per_channel_data; ?>,
        "valueAxes": [{
                "stackType": "regular",
                "axisAlpha": 0.3,
                "gridAlpha": 0
            }],

        "titles": [
            {
                "text": "OOS - <?php echo date('F'); ?>",
                "size": 15
            }
        ],

        "depth3D": 40,
        "angle": 30,
        "chartCursor": {
            "categoryBalloonEnabled": false,
            "cursorAlpha": 0,
            "zoomable": false
        },
        "graphs": [{
                "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
                "fillAlphas": 0.8,
                "labelText": "[[value]]",
                "lineAlpha": 0.3,
                "columnWidth": 0.6,
                "title": "OOS",
                "type": "column",
                "color": "#FFFFFF",
                "lineColor": "#FF0000",
                "topRadius": 1,
                "valueField": "oos"
            }

            ,
            {
                "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",

                "fixedColumnWidth": 45,
                "fixedColumnHeight": 5,
                "title": "",
                "type": "column",

                "lineColor": "rgba(255, 255, 255, .4)",

                "valueField": "inv"
            }
        ],
        "categoryField": "channel",
        "categoryAxis": {
            "gridPosition": "start",
            "axisAlpha": 0,
            "gridAlpha": 0,
            "position": "left",
            "labelRotation": 20
        },
        "export": {
            "enabled": true
        }

    });
</script>











