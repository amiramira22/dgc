<?php
$channels = array();
$components = array();
$channel_components = array();

foreach ($report_data as $row) {
    $channel = $row['sub_channel'];
    if (!in_array($channel, $channels)) {
        $channels[] = $channel;
    }
    //create an array for every brand and the count at a outlet
    $components[$row['brand_name']][$channel] = array($row['av'], $row['oos'], $row['ha'], $row['total']);
    $channel_components[$channel][$row['brand_name']] = array($row['av'], $row['oos'], $row['ha'], $row['total']);
}// end foreach report_data


?>


<div class="portlet light ">
    <div class="portlet-title tabbable-line">
        <div class="caption">
            <span class="caption-subject font-red bold uppercase"> Stock Issues Report </span>
        </div>
    </div>


    <table class="table table-striped table-bordered table-hover " width="100%">
        <thead>
            <tr>
                <th></th>

                <?php foreach ($channels as $channel) { ?>
                    <th class ="text-center" colspan="3">
                        <?php
                        echo $channel;
                        ?>
                    </th>
                <?php } ?>
                <th colspan="3">Average</th>
            </tr>

            <tr>

                <th>Brand</th>

                <?php foreach ($channels as $channel) { ?>
                    <th class ="text-center">AV</th>
                    <th class ="text-center">OOS</th>
                    <th class ="text-center">HA</th>
                <?php } ?>
                <th>AV%</th>
                <th>OOS%</th>
                <th>HA%</th>
            </tr>

        <thead>

        <tbody>

            <?php
            $i = 0;
            foreach ($components as $brand_name => $componentChannels) {
                $i++;
                ?>
                <tr>
                    <td><?php echo $brand_name; ?></td>
                    <?php foreach ($channels as $channel) { 
                        $total=$componentChannels[$channel][0]+$componentChannels[$channel][1];
                        ?>
                        <td class="av_row"><?php
                            if (isset($componentChannels[$channel][0]) && $total!=0) {
                                echo number_format(($componentChannels[$channel][0] /$total) * 100, 2, '.', ' ');
                            } else
                                echo '-';
                            ?> </td>


                        <td class="oos_row"><?php
                            if (isset($componentChannels[$channel][1])) {
                                echo number_format(($componentChannels[$channel][1] / $total) * 100, 2, '.', ' ');
                            } else
                                echo '-';
                            ?> </td>

                        <td class="ha_row"><?php
                            if (isset($componentChannels[$channel][2])) {
                                echo number_format(($componentChannels[$channel][2] / $componentChannels[$channel][3]) * 100, 2, '.', ' ');
                            } else
                                echo '-';
                            ?> </td>
                    <?php } // end foreach channels  ?>

                    <td class="total-av_row"></td>
                    <td class="total-oos_row"></td>
                    <td class="total-ha_row"></td>
                <?php }// end foreach components   ?>


            </tr>

        </tbody>



    </table>


    <div style="height:500px; width:100%;" id="chartHenkelalone"></div>
</div>




<!--     Clusters              -->
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
                    <div id="content_clusters<?php echo $cluster_id; ?>-1"> </div>
                    <script type="text/javascript">

                        var zone_ids = JSON.stringify(<?php echo $json_zone_ids; ?>);
                        var channel_ids = JSON.stringify(<?php echo $json_channel_ids; ?>);
                         var sub_channel_ids = JSON.stringify(<?php echo $json_sub_channel_ids; ?>);
                        $('#content_clusters<?php echo $cluster_id; ?>-1').html('<div class="col-md-12"><img src="<?php echo base_url('assets/img/ajax-loading.gif'); ?>" class="img-responsive img-center" /></div>');
                        jQuery.ajax({
                            url: "<?php echo site_url("reports/load_av_cluster_sub_channels"); ?>",
                            data: {
                                start_date: "<?php echo $start_date; ?>",
                                end_date: "<?php echo $end_date; ?>",
                                date_type: "<?php echo $date_type; ?>",
                                category_id: "<?php echo $category_id; ?>",
                                cluster_id: "<?php echo $cluster_id; ?>",
                                json_zone_ids: zone_ids,
                                json_channel_ids: channel_ids,
                                 json_sub_channel_ids: sub_channel_ids,
                                zone_val: "0",
                                out_val: "0"
                            },
                            type: "POST",
                            success: function (data) {
                                $('#content_clusters<?php echo $cluster_id; ?>-1').html(data);
                            }
                        });
                    </script>
                </div> <!-- end portlet light -->
            <?php } // end clusters foreach     ?>
        </div> <!-- end col-md-12 -->
    </div>  <!-- end row 2-->

<?php } // end if(!empty($clusters)){ ?>




<?php
// Channels
$ch_id = 0;


foreach ($channel_components as $channel_name => $componentBrands) {

    $ch_id++;
    ?>

    <div class="portlet light ">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <span class="caption-subject font-red bold uppercase"> <?php echo $channel_name; ?> </span>
            </div>
        </div>



        <div style="height:500px; width:100%;" id="chart_channel<?php echo $ch_id; ?>"></div>
        <script>
            pie_data = [];
            pie_data.push(
                    {
                        name: "av",
                        value: <?php echo $componentBrands[MAIN_BRAND_NAME][0]; ?>,
                        color: "#298A08"
                    });

            pie_data.push(
                    {
                        name: "oos",
                        value: <?php echo $componentBrands[MAIN_BRAND_NAME][1]; ?>,
                        color: "#DF0101"
                    });

            pie_data.push(
                    {
                        name: "ha",
                        value: <?php echo $componentBrands[MAIN_BRAND_NAME][2]; ?>,
                        color: "#000000"
                    });



            var chart = AmCharts.makeChart("chart_channel<?php echo $ch_id; ?>", {
                "type": "pie",
                "theme": "light",
                "dataProvider": pie_data,
                "valueField": "value",
                "titleField": "name",
                "colorField": "color",
                "fontSize": 20,
                "depth3D": 10,
                "angle": 30,
                "titles": [
                    {
                        "text": "<?php echo MAIN_BRAND_NAME; ?> stock issues",
                        "size": 15
                    }
                ],

                "export": {
                    "enabled": true
                }
            });

        </script>

    </div>
    <?php
}
?>






<script>
    $(document).ready(function () {
        /*****************************************************/
// Pie Chart
        /*****************************************************/

//iterate through each row in the table
        $('tr').each(function () {
//the value of sum needs to be reset for each row, so it has to be set inside the row loop
            var sum = 0
            var n = 0
//find the combat elements in the current row and sum it 
            $(this).find('.av_row').each(function () {
                var shelf = $(this).text();
                if (!isNaN(shelf) && shelf.length !== 0) {
                    sum += parseFloat(shelf);
                    n++;
                }
            });
//set the value of currents rows sum to the total-combat element in the current row
            $('.total-av_row', this).html(parseFloat(sum / n).toFixed(2));
        });


        $('tr').each(function () {
//the value of sum needs to be reset for each row, so it has to be set inside the row loop
            var sum = 0
            var n = 0
//find the combat elements in the current row and sum it 
            $(this).find('.oos_row').each(function () {
                var perc = $(this).text();

                if (!isNaN(perc) && perc.length !== 0) {
                    sum += parseFloat(perc);
                    n++;
                }
            });


//set the value of currents rows sum to the total-combat element in the current row
            $('.total-oos_row', this).html(parseFloat(sum / n).toFixed(2));
        });


        $('tr').each(function () {
//the value of sum needs to be reset for each row, so it has to be set inside the row loop
            var sum = 0
            var n = 0
//find the combat elements in the current row and sum it 
            $(this).find('.ha_row').each(function () {
                var perc = $(this).text();

                if (!isNaN(perc) && perc.length !== 0) {
                    sum += parseFloat(perc);
                    n++;
                }
            });


//set the value of currents rows sum to the total-combat element in the current row
            $('.total-ha_row', this).html(parseFloat(sum / n).toFixed(2));
        });


        /*****************************************************/
// Pie Chart
        /*****************************************************/



        var pie_data = [];


        var sum = 0;
        var n = 0;
        var i = 0;
        var s_ha = 0;
        var s_oos = 0;
        var s_av = 0;
        $(this).find('.av_row').each(function () {

            var perc = $(this).text();

            if (!isNaN(perc) && perc.length !== 0) {
                s_av += parseFloat(perc);
                n++;
                i++;
            }
        });

        $(this).find('.oos_row').each(function () {

            var shelf = $(this).text();

            if (!isNaN(shelf) && shelf.length !== 0) {
                s_oos += parseFloat(shelf);
                n++;
                i++;
            }
        });

        $(this).find('.ha_row').each(function () {

            var ha = $(this).text();

            if (!isNaN(ha) && ha.length !== 0) {
                s_ha += parseFloat(ha);
                n++;
                i++;
            }
        });


        pie_data.push(
                {
                    name: "oos",
                    value: s_oos,
                    color: "#DF0101"
                });
        pie_data.push(
                {
                    name: "av",
                    value: s_av,
                    "color": "#298A08"
                });

        pie_data.push(
                {
                    name: "ha",
                    value: s_ha,
                    "color": "#000000"
                }

        );


        showchart(pie_data);


        function showchart(data)
        {


            var chart = AmCharts.makeChart("chartHenkelalone", {
                "type": "pie",
                "theme": "light",
                "dataProvider": data,
                "valueField": "value",
                "titleField": "name",
                "colorField": "color",
                "fontSize": 20,
                "depth3D": 10,
                "angle": 30,
                "titles": [
                    {
                        "text": "<?php echo MAIN_BRAND_NAME; ?> stock issues",
                        "size": 15
                    }
                ],

                "export": {
                    "enabled": true
                }
            });
        }

    });
</script>