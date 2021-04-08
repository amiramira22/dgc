<!-- Chart code hcm-->


<div class="portlet light portlet-fit bordered">

    <div class="portlet-title">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-settings font-red"></i>
                <span class="caption-subject font-red bold uppercase">OOS Trend</span>
            </div>

            <ul class="nav nav-tabs">

                <?php
                foreach ($categories as $category) {
                    if ($category->id == 1) {
                        $class = "active";
                    } else {
                        $class = "";
                    }
                    ?>

                    <li class="<?php echo $class; ?>" id="category_<?php echo $category->id; ?>">
                        <a href="#tab10_<?php echo $category->id; ?>" class="active" data-toggle="tab"> <?php echo $category->name; ?> </a>
                    </li>

                    <script type="text/javascript">

                        $("#<?php echo 'category_' . $category->id; ?>").click(function () {

                        $('#trend_div_<?php echo $category->id; ?>').html('<div class="col-md-12"><img style="margin-left: 30%;" align="center" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');
                        jQuery.ajax({
                        url: "<?php echo site_url("dashboard/load_chart_oos_trend"); ?>",
                                data: {category_id: "<?php echo $category->id; ?>"},
                                type: "POST",
                                success: function (data) {
                                $('#trend_div_<?php echo $category->id; ?>').html(data);
                                }
                        });
                        });
                    </script>
                <?php } ?>
            </ul>
        </div>

        <div class="portlet-body">
            <div class="tab-content">

                <?php
                foreach ($categories as $category) {
                    if ($category->id == 1) {
                        $class = "active";
                    } else {
                        $class = "";
                    }
                    ?>
                    <div class="tab-pane <?php echo $class; ?>" id="tab10_<?php echo $category->id; ?>">
                        <div style="height:305px; width:100%;" id="trend_div_<?php echo $category->id; ?>">
                        </div>
                    </div>  <!--   tab1 content-->
                <?php } ?>
            </div>
        </div>
    </div>
</div>



<script>


    var chart = AmCharts.makeChart("trend_div_1", {
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
                    "guides": [ <?php foreach ($brands as $brand) { ?>
                        {


                        "id": "<?php echo $brand['name']; ?>",
                                "title": "<?php echo $brand['name']; ?>",
                                "fillColors" : "<?php echo $brand['color']; ?>",
                                "valueField": "<?php echo $brand['name']; ?>"
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
<?php foreach ($brands as $brand) { ?>
                {
                "bullet": "square",
                        "balloonText": "<?php echo $brand['name']; ?>:[[value]]",
                        "id": "<?php echo $brand['name']; ?>",
                        "title": "<?php echo $brand['name']; ?>",
                        "lineColor" : "<?php echo $brand['color']; ?>",
                        "fillColors" : "<?php echo $brand['color']; ?>",
                        "valueField": "<?php echo $brand['name']; ?>"
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


