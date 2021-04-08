<?php
//bcm
$dates = array();
$count_date = 0;
$components_date = array();
$components = array();
$components_chart = array();
$data_pie_chart = array();
$brand_colors = array();

foreach ($report_data as $row) {

    $date = ($row['date']);
    if (!in_array($date, $dates)) {
        $dates[] = $date;
        $count_date += 1;
    }
    $brand_colors[$row['brand_name']] = $row['brand_color'];
    $components[$row['brand_name']][$date] = array($row['shelf'], $row['metrage']);
    $components_chart[$row['brand_name']][$date] = $row['metrage'];
    $components_date [$row['date']] [$row['brand_name']] = $row['metrage'];
}
$sum_metrage_date = array();
foreach ($components_date as $date => $componentBrand) {
    $sum_metrage_date[$date] = array_sum(array_values($componentBrand));
}

//print_r($zone_id);
//echo '<br>';
//print_r($selected_channel_ids);
?> 


<div class="portlet light ">
    <div class="portlet-title tabbable-line">
        <div class="caption">
            <span class="caption-subject font-red bold uppercase"> </span>
        </div>
    </div>


    <table class="table table-striped table-bordered table-hover " width="100%">
        <thead>
            <tr>
                <th class ="text-center" colspan="2"></th>

                <?php foreach ($dates as $date) { ?>
                    <th class ="text-center" colspan="3"><?php
                        echo format_qmw_date($date_type, $date);
                        ?>
                    </th>
                <?php } ?>

                <th class ="text-center" colspan="3">Average</th>
            </tr>

            <tr>
                <th>Rank</th>
                <th>Brand</th>

                <?php foreach ($dates as $date) { ?>
                    <th>Shelf</th> 
                    <th>metrage</th> 
                    <th>%</th>
                <?php } ?>

                <th>Shelf</th>
                <th>metrage</th> 
                <th>%</th>
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
                        <td class="shelf_row<?php echo $zone_id; ?>">
                            <?php
                            if (isset($componentDates[$date])) {
                                echo $componentDates[$date][0];
                            } else
                                echo '-';
                            ?>
                        </td>


                        <td class="metrage_row<?php echo $zone_id; ?>">
                            <?php
                            if (isset($componentDates[$date])) {
                                echo number_format($componentDates[$date][1], 2, '.', '');
                            } else
                                echo '-';
                            ?>
                        </td>

                        <td class="perc_row<?php echo $zone_id; ?>">
                            <?php
                            if (isset($componentDates[$date])) {
                                echo number_format(($componentDates[$date][1] / $sum_metrage_date[$date]) * 100, 2, '.', '');
                            } else
                                echo '-';
                            ?> 
                        </td>
                    <?php } // end foreach dates      ?>

                    <td class="total-shelf_row<?php echo $zone_id; ?>"></td>
                    <td class="total-metrage_row<?php echo $zone_id; ?>"></td>
                    <td class="total-perc_row<?php echo $zone_id; ?>"></td>                
                <?php }// end foreach components                  ?>


            </tr>

        </tbody>



    </table>


        <!--<div style="height:500px; width:100%;" id="pie_chart<?php echo $zone_id; ?>"></div>-->
    <div style="height:700px; width:100%;" id="trend_zone<?php echo $zone_id; ?>"></div>
</div>




<!--     Clusters              -->
<?php
if (!empty($clusters)) {
    //print_r($channel_ids);
    ?>
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
                    <div id="content_cluster<?php echo $cluster_id; ?>_<?php echo $zone_id; ?>"> </div>

                    <script type="text/javascript">
                        var channel_ids = JSON.stringify(<?php echo $json_channel_ids; ?>);
                        $('#content_cluster<?php echo $cluster_id; ?>_<?php echo $zone_id; ?>').html('<div class="col-md-12"><img src="<?php echo base_url('assets/img/ajax-loading.gif'); ?>" class="img-responsive img-center" /></div>');
                        jQuery.ajax({
                        url: "<?php echo site_url("reports/load_shelf_cluster"); ?>",
                                data: {
                                start_date: "<?php echo $start_date; ?>",
                                        end_date: "<?php echo $end_date; ?>",
                                        date_type: "<?php echo $date_type; ?>",
                                        category_id: "<?php echo $category_id; ?>",
                                        cluster_id: "<?php echo $cluster_id; ?>",
                                        zone_id: "<?php echo $zone_id; ?>",
                                        json_channel_ids: channel_ids,
                                        zone_val: "<?php echo $zone_id; ?>",
                                        out_val: "0"
                                },
                                type: "POST",
                                success: function (data) {
                                $('#content_cluster<?php echo $cluster_id; ?>_<?php echo $zone_id; ?>').html(data);
                                }
                        });
                    </script>

                </div> <!-- end portlet light -->
            <?php } // end clusters foreach           ?>
        </div> <!-- end col-md-12 -->
    </div>  <!-- end row 2-->



<?php } // en if(!empty($clusters)) {        ?>


<script>
    $(document).ready(function () {
//iterate through each row in the table
    var bvm_shelf = 0;
    var bvm_perc = 0;
    $('tr').each(function () {


//the value of sum needs to be reset for each row, so it has to be set inside the row loop
    var sum_shelf = 0;
    var sum_metrage = 0;
    var sum_perc = 0;
    var n_shelf = 0;
    var n_metrage = 0;
    var n_perc = 0;
    brand = $(this).find('.brand').text();
//find the combat elements in the current row and sum it 

    $(this).find('.shelf_row<?php echo $zone_id; ?>').each(function () {
    var shelf = $(this).text();
    if (!isNaN(shelf) && shelf.length !== 0) {
    sum_shelf += parseFloat(shelf);
    n_shelf++;
    }
    });
    //****************************************************
    $(this).find('.metrage_row<?php echo $zone_id; ?>').each(function () {
    var metrage = $(this).text();
    if (!isNaN(metrage) && metrage.length !== 0) {
    sum_metrage += parseFloat(metrage);
    n_metrage++;
    }
    });
    //*************************************************
    $(this).find('.perc_row<?php echo $zone_id; ?>').each(function () {
    var perc = $(this).text();
    if (!isNaN(perc) && perc.length !== 0) {
    sum_perc += parseFloat(perc);
    n_perc++;
    }
    });
    if (brand == "HENKEL") {
    bvm_shelf = sum_shelf;
    bvm_shelf = sum_shelf;
    bvm_perc = sum_perc;
    }
//set the value of currents rows sum to the total-combat element in the current row
    $('.total-shelf_row<?php echo $zone_id; ?>', this).html(parseFloat(sum_shelf / n_shelf).toFixed(2));
    $('.total-metrage_row<?php echo $zone_id; ?>', this).html(parseFloat(sum_metrage / n_metrage).toFixed(2));
    $('.total-perc_row<?php echo $zone_id; ?>', this).html(parseFloat(sum_perc / n_perc).toFixed(2));
    }); // End foreach TR




    });
    /**
     * Make the chart
     */

    var trend_data = [];
    var graph_data = [];
// Graph Data
<?php foreach ($brand_colors as $brand => $color) { ?>
        graph_data.push(
        {
        "bullet": "square",
                "balloonText": "<?php echo $brand; ?>:[[value]]",
                "id": "<?php echo $brand; ?>",
                "title": "<?php echo $brand; ?>",
                "lineColor": "<?php echo $color; ?>",
                "fillColors": "<?php echo $color; ?>",
                "valueField": "<?php echo $brand; ?>"
        }
        ); // end graph_data
<?php } ?>



// Trend Data 
<?php foreach ($components_date as $date => $componentBrands) { ?>

        trend_data.push(
        {
        "date": "<?php echo format_qmw_date($date_type, $date); ?>",
    <?php foreach ($componentBrands as $brand => $value) { ?>


            "<?php echo $brand; ?>": <?php echo $value; ?>,
    <?php } ?>
        }
        ); // end push trend data

<?php } ?>


    var chart = AmCharts.makeChart("trend_zone<?php echo $zone_id; ?>", {
    "type": "serial",
            "theme": "light",
            "marginRight": 80,
            "autoMarginOffset": 20,
            "legend": {
            "align": "center",
                    "valueWidth": 100
            },
            "dataProvider": trend_data,
            "graphs": graph_data,
            "plotAreaBorderAlpha": 0,
            "marginLeft": 0,
            "marginBottom": 0,
            "categoryField": "date",
            "export": {
            "enabled": true
            }
    });
</script>



