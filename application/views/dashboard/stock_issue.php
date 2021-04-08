<!-- Begin: life time stats -->
<!-- BEGIN PORTLET-->
<div class="portlet light ">
    <div class="portlet-title tabbable-line">
        <div class="caption">
            <i class="icon-settings font-red"></i>
            <span class="caption-subject font-red bold uppercase">stock issues</span>
        </div>

        <ul class="nav nav-tabs">
            <?php
            foreach ($brands as $brand) {
                if ($brand->id == 1) {
                    $class = "active";
                } else {
                    $class = "";
                }
                ?>
                <li class="<?php echo $class; ?>" id="brand2_<?php echo $brand->id; ?>">
                    <a href="#tab6_<?php echo $brand->id; ?>" class="active" data-toggle="tab"> <?php echo $brand->name; ?> </a>
                </li>

                <script type="text/javascript">

                    $("#<?php echo 'brand2_' . $brand->id; ?>").click(function () {


                        $('#brand2_chart_div_<?php echo $brand->id; ?>').html('<div class="col-md-12"><img style="margin-left: 30%;" align="center" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');

                        jQuery.ajax({
                            url: "<?php echo site_url("dashboard/load_chart_stock_issue"); ?>",

                            data: {brand_id: "<?php echo $brand->id; ?>"},
                            type: "POST",
                            success: function (data) {
                                $('#brand2_chart_div_<?php echo $brand->id; ?>').html(data);
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
            foreach ($brands as $brand) {
                if ($brand->id == 1) {
                    $class = "active";
                } else {
                    $class = "";
                }
                ?>
                <div class="tab-pane <?php echo $class; ?>" id="tab6_<?php echo $brand->id; ?>">
                    <div style="height:340px; width:100%;" id="brand2_chart_div_<?php echo $brand->id; ?>">
                    </div>
                </div>  <!--   tab1 content-->
            <?php } ?>
        </div>
    </div>
</div>


<script>

    var chart = AmCharts.makeChart("brand2_chart_div_1", {
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
