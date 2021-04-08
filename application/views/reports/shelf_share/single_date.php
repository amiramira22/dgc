<?php

//bcm
$dates = array();
$count_date = 0;
$components_date = array();
$components = array();
$components_chart = array();
$data_pie_chart = array();

foreach ($report_data as $row) {

    $date = ($row['date']);
    if (!in_array($date, $dates)) {
        $dates[] = $date;
        $count_date += 1;
    }
    $components[$row['brand_name']][$date] = array($row['shelf'], $row['metrage']);
    $components_chart[$row['brand_name']][$date] = $row['metrage'];
    $components_date [$row['date']] [$row['brand_name']] = $row['metrage'];
}
$sum_metrage_date = array();
foreach ($components_date as $date => $componentBrand) {
    $sum_metrage_date[$date] = array_sum(array_values($componentBrand));
}
?>     
<div class="portlet light ">
    <div class="portlet-title tabbable-line">
        <div class="caption">
            <i class="icon-settings font-red"></i>
            <span class="caption-subject font-red bold uppercase"> Shelf Share Report</span>
        </div>
    </div>
    <table class="table table-striped table-bordered table-hover dt-responsive" id="myTable1" width="100%" id="selection">
        <thead>
            <tr>
                <th colspan="2"></th>

                <?php foreach ($dates as $date) { ?>
                    <th colspan="3"><?php echo format_quarter($date); ?></th>
                <?php } ?>
            </tr>

            <tr>
                <th>Rank</th>
                <th>Brand</th>

                <?php foreach ($dates as $date) { ?>
                    <th>Shelf</th>                        
                    <th>metrage</th>
                    <th>%</th>
                <?php } ?>
            </tr>
        <thead>

        <tbody>

            <?php
            $i = 0;
            foreach ($components as $brand_name => $componentDates) {
                $i++;
                ?>
                <tr>
                    <td class="rank"><b><?php echo $i; ?></b></td>
                    <td><?php echo $brand_name; ?></td>

                    <?php foreach ($dates as $date) { ?>
                        <td class="shelf" >
                            <?php
                            if (isset($componentDates[$date])) {
                                echo $componentDates[$date][0];
                            } else
                                echo '-';
                            ?> 
                        </td>

                        <td class="metrage" >
                            <?php
                            if (isset($componentDates[$date])) {
                                //echo $componentDates[$date][1];
                                echo number_format(($componentDates[$date][1]), 2 ,'.', '');
                                //number_format($total_avg, 2, '.', '');
                            } else
                                echo '-';
                            ?> 
                        </td>

                        <td class="perc">
                            <?php
                            if (isset($componentDates[$date])) {
                                echo number_format(($componentDates[$date][1] / $sum_metrage_date[$date]) * 100, 2);
                            } else
                                echo '-';
                            ?> 
                        </td>

                    <?php } // end foreach dates       ?>

                <?php }// end foreach components       ?>

            <tr>
                <td colspan="2" align="center">Total</td>
                <td class="totalshelf"></td>
                <td class="totalmetrage"></td>
                <td class="totalperc"></td>
            </tr>
        </tbody>

    </table>
</div>

<div class="portlet light ">
    <div class="portlet-title tabbable-line">
        <div class="caption">
            <i class="icon-bar-chart font-red"></i>
            <span class="caption-subject font-blue bold uppercase">Shelf share Graphical Report</span>
        </div>
    </div>

    <div style="height:700px; width:100%;" id="shelf_pie_chart"></div>

</div> <!-- end portlet light -->

<?php
// pie chart
foreach ($components_chart as $brand_name => $componentDates) {

    $total_avg = (array_sum(array_values($componentDates))) / $count_date;

    $row_data['brand_name'] = $brand_name;
    $row_data['metrage'] = number_format($total_avg, 2, '.', '');
    $brand_color = $this->Brand_model->get_brand_by_name($brand_name)->color;
    $row_data['color'] = $brand_color;
    $data_pie_chart[] = $row_data;
}
$brand_data = json_encode($data_pie_chart);
?>

<?php if (!empty($clusters)) { ?>

    <div class="row">
        <div class="col-md-12">

            <?php
            foreach ($clusters as $cluster) {

                $cluster_id = $cluster->id;
                $cluster_name = $cluster->name;
                ?>
                <div class="portlet light ">
                    <div class="portlet-title tabbable-line">
                        <div class="caption">
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject font-red bold uppercase"><?php echo $cluster_name; ?></span>
                        </div>
                    </div>

                    <div id="content_cluster<?php echo $cluster_id; ?>-1"> </div>

                    <script type="text/javascript">

                        $('#content_cluster<?php echo $cluster_id; ?>-1').html('<div class="col-md-12"><img src="<?php echo base_url('assets/img/ajax-loading.gif'); ?>" class="img-responsive img-center" /></div>');
                        jQuery.ajax({
                            url: "<?php echo site_url("reports/load_shelf_cluster"); ?>",

                            data: {
                                start_date: "<?php echo $start_date; ?>",
                                end_date: "<?php echo $end_date; ?>",
                                category_id: "<?php echo $category_id; ?>",
                                zone_id: "-1",
                                channel: "-1",
                                date_type: "<?php echo $date_type; ?>",
                                cluster_id: "<?php echo $cluster_id; ?>"
                            },
                            type: "POST",
                            success: function (data) {
                                $('#content_cluster<?php echo $cluster_id; ?>-1').html(data);
                            }
                        });


                    </script>
                </div> <!-- end portlet light -->

            <?php } // end clusters foreach       ?>

        </div> <!-- end col-md-12 -->
    </div>  <!-- end row 2-->

<?php }// end if(!empty($clusters)){ ?>     


<script>

    var chart = AmCharts.makeChart("shelf_pie_chart", {
        "type": "pie",
        "theme": "light",
        "dataProvider": <?php echo $brand_data; ?>,
        "valueField": "metrage",
        "titleField": "brand_name",
        "titles": [
            {
                "text": "Shelf share",
                "size": 15
            }
        ],
        "outlineAlpha": 0.4,
        "colorField": "color",
        //"fontSize": 20,
        "depth3D": 15,
        "balloonText": "[[title]]<br><span style='font-size:15px'><b>[[value]]</b> ([[percents]]%)</span>",
        "angle": 30,
        "legend": {
            "position": "right",
            "marginRight": 0,
            "autoMargins": true
        },
        "export": {
            "enabled": true
        }
    });
</script>



<script>
    $(function () {
        function tally(selector) {
            $(selector).each(function () {
                var total = 0,
                        column = $(this).siblings(selector).andSelf().index(this);
                $(this).parents().prevUntil(':has(' + selector + ')').each(function () {
                    total += parseFloat($('td.shelf:eq(' + column + ')', this).html()) || 0;
                })
                $(this).html(total);
            });
        }
        tally('td.totalshelf');
    });

</script>
<script>
    $(function () {
        function tally(selector) {
            $(selector).each(function () {
                var totalmetrage = 0,
                        column = $(this).siblings(selector).andSelf().index(this);
                $(this).parents().prevUntil(':has(' + selector + ')').each(function () {
                    totalmetrage += parseFloat($('td.metrage:eq(' + column + ')', this).html()) || 0;
                })
                $(this).html(totalmetrage.toFixed(2));
            });
        }
        tally('td.totalmetrage');
    });

</script>

<script>
    $(function () {
        function tally(selector) {
            $(selector).each(function () {
                var totalperc = 0,
                        column = $(this).siblings(selector).andSelf().index(this);
                $(this).parents().prevUntil(':has(' + selector + ')').each(function () {
                    totalperc += parseFloat($('td.perc:eq(' + column + ')', this).html()) || 0;
                })
                $(this).html(totalperc.toFixed(2));
            });
        }
        tally('td.totalperc');
    });

</script>
