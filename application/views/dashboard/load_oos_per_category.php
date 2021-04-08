<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/gauge.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>


<div class="portlet light portlet-fit bordered">
    <div class="portlet-title">
        <div class="portlet-title tabbable-line">

            <div class="caption">
                <i class="icon-settings font-red"></i>
                <span class="caption-subject font-red bold uppercase"> OOS PER CATegory </span>
            </div>


            <ul class="nav nav-tabs">

                <?php
                foreach ($brands as $brand_categorie) {
                    if ($brand_categorie->id == 1) {
                        $class = "active";
                    } else {
                        $class = "";
                    }
                    ?>

                    <li class="<?php echo $class; ?>" id="brand_categorie_<?php echo $brand_categorie->id; ?>">
                        <a href="#tab2_<?php echo $brand_categorie->id; ?>" class="active" data-toggle="tab"> <?php echo $brand_categorie->name; ?> </a>
                    </li>



                    <script type="text/javascript">

                        $("#<?php echo 'brand_categorie_' . $brand_categorie->id; ?>").click(function () {


                            $('#brand_categorie_chart_div_<?php echo $brand_categorie->id; ?>').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');

                            jQuery.ajax({
                                url: "<?php echo site_url("dashboard/load_chart_oos_per_category"); ?>",

                                data: {brand_id: "<?php echo $brand_categorie->id; ?>"},
                                type: "POST",
                                success: function (data) {
                                    $('#brand_categorie_chart_div_<?php echo $brand_categorie->id; ?>').html(data);
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
            foreach ($brands as $brand_categorie) {
                if ($brand_categorie->id == 1) {
                    $class = "active";
                } else {
                    $class = "";
                }
                ?>

                <div class="tab-pane <?php echo $class; ?>" id="tab2_<?php echo $brand_categorie->id; ?>">
                    <div style="height:380px; width:100%; margin: auto; " id="brand_categorie_chart_div_<?php echo $brand_categorie->id; ?>">
                    </div>
                </div>  <!--   tab1 content-->


            <?php } ?>


        </div>

    </div>

</div>

<script>
    var chart = AmCharts.makeChart("brand_categorie_chart_div_1", {
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


