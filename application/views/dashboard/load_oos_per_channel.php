<?php //print_r($oos_per_channel_data); ?>



<div class="portlet light portlet-fit bordered">

    <div class="portlet-title">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-settings font-red"></i>
                <span class="caption-subject font-red bold uppercase">OOS per Channel</span>
            </div>

            <ul class="nav nav-tabs">
                <?php
                foreach ($brands as $brand) {
                    $class = "";
                    if ($brand->id == 1) {
                        $class = "active";
                    } else {
                        $class = "";
                    }
                    ?>
                    <li class="<?php echo $class; ?>" id="brand_<?php echo $brand->id; ?>">
                        <a href="#tab_<?php echo $brand->id; ?>" class="active" data-toggle="tab"> <?php echo $brand->name; ?> </a>
                    </li>

                    <script type="text/javascript">

                        $("#<?php echo 'brand_' . $brand->id; ?>").click(function () {

                            $('#brand_chart_div_<?php echo $brand->id; ?>').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');

                            jQuery.ajax({
                                url: "<?php echo site_url("dashboard/load_chart_oos_per_channel"); ?>",

                                data: {brand_id: "<?php echo $brand->id; ?>"},
                                type: "POST",
                                success: function (data) {
                                    $('#brand_chart_div_<?php echo $brand->id; ?>').html(data);
                                }
                            });
                        });

                    </script>
                <?php } ?>
            </ul>
        </div>
    </div>


    <div class="portlet-body">
        <div class="tab-content">

            <?php
           // print_r($brands);die();
            foreach ($brands as $brand) {
                if ($brand->id == 1) {
                    $class = "active";
                } else {
                    $class = "";
                }
                ?>

                <div class="tab-pane <?php echo $class; ?>" id="tab_<?php echo $brand->id; ?>">
                    <div style="height:380px; width:100%;" id="brand_chart_div_<?php echo $brand->id; ?>">
                    </div>
                </div>  <!--   tab1 content-->

            <?php } ?>
        </div>
    </div>
</div>


<script>


    var chart = AmCharts.makeChart("brand_chart_div_1", {
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
        "graphs": [{
                "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
                "fillAlphas": 0.8,
                "labelText": "[[value]]",
                "lineAlpha": 0.3,

                "fixedColumnWidth": 45,
                "fixedColumnHeight": 5,
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
        "depth3D": 40,
        "angle": 30,
        "chartCursor": {
            "categoryBalloonEnabled": false,
            "cursorAlpha": 0,
            "zoomable": false
        },
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

