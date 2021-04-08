
<?php
$channels = array();
$components = array();
$components_channel = array();
$sum_metrage_channel = array();
//print_r($this->Report_model->get_total_metrage('quarter', '2017-10-01', '2017-10-01', 15));



foreach ($report_data as $row) {
    $channel = $row['channel'];
    if (!in_array($channel, $channels)) {
        $channels[] = $channel;
    }
    //create an array for every brand and the count at a outlet
    $components[$row['brand_name']][$channel] = array($row['shelf'], $row['metrage'], $row['color']);
    $components_channel [$row['channel']] [$row['brand_name']] = $row['metrage'];
}// end foreach report_data
?>



<?php
foreach ($components_channel as $channel => $componentBrand) {
    $sum_metrage_channel[$channel] = array_sum(array_values($componentBrand));
}
?>


<div class="portlet light ">
    <div class="portlet-title tabbable-line">
        <div class="caption">
            <span class="caption-subject font-red bold uppercase"> Shelf Share Report </span>
        </div>
    </div>


    <table id="myTable" class="table table-striped table-bordered table-hover " width="100%">
        <thead>
            <tr>
                <th colspan="2"></th>

                <?php foreach ($channels as $channel) { ?>
                    <th class ="text-center" colspan="3">
                        <?php
                        echo $channel;
                        ?>
                    </th>
                <?php } ?>
                <th colspan="3">Total</th>
            </tr>

            <tr>
                <th>#</th>
                <th>Brand</th>

                <?php foreach ($channels as $channel) { ?>
                    <th>shelf</th>
                    <th>Metrage</th>
                    <th>%</th>
                <?php } ?>
                <th>shelf</th>
                <th>Metrage</th>
                <th>%</th>
            </tr>

        <thead>

        <tbody>

            <?php
            $i = 0;
            foreach ($components as $brand_name => $componentChannels) {
                $i++;
                ?>
                <tr>
                    <td class="rank"><b><?php echo $i; ?></b></td>
                    <td class="brand_brand"><?php echo $brand_name; ?></td> 
                    <?php foreach ($channels as $channel) { ?>
                        <td class="shelf_brand">
                            <?php
                            if (isset($componentChannels[$channel])) {
                                echo $componentChannels[$channel][0];
                            } else
                                echo '-';
                            ?> </td>
                        <td class="metrage_brand">
                            <?php
                            if (isset($componentChannels[$channel])) {
                                echo number_format(($componentChannels[$channel][1]), 2 ,'.', '');
                            } else
                                echo '-';
                            ?> 
                        </td>

                        <td class="color_color" style="display: none;">
                            <?php
                            if (isset($componentChannels[$channel])) {
                                echo $componentChannels[$channel][2];
                            } else
                                echo '-';
                            ?> 
                        </td>

                        <td class="perc_brand">
                            <?php
                            if (isset($componentChannels[$channel])) {
                                //echo  ($componentDates[$channel][0]/ $sum_shelf_channel[$channel])*100 .'%';
                                if ($sum_metrage_channel[$channel] != 0) {
                                    echo number_format(($componentChannels[$channel][1] / $sum_metrage_channel[$channel]) * 100, 2);
                                } else {
                                    echo '0';
                                }
                                // echo $componentDates[$channel][0];
                            } else
                                echo '-';
                            ?> </td>

                    <?php } // end foreach channels    ?>

                    <td class="total-shelf_brand"></td>
                    <td class="total-metrage_brand"></td>
                    <td class="total-perc_brand"></td>
                <?php }// end foreach components    ?>
            </tr>
        </tbody>
    </table>
    <div style="height:700px; width:100%;" id="pie_chart"></div>
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
                    <div id="content_clusters<?php echo $cluster_id; ?>"> </div>
                    <script type="text/javascript">
                        var zone_ids = JSON.stringify(<?php echo $json_zone_ids; ?>);
                        var channel_ids = JSON.stringify(<?php echo $json_channel_ids; ?>);

                        $('#content_clusters<?php echo $cluster_id; ?>').html('<div class="col-md-12"><img src="<?php echo base_url('assets/img/ajax-loading.gif'); ?>" class="img-responsive img-center" /></div>');
                        jQuery.ajax({
                            url: "<?php echo site_url("reports/load_shelf_cluster_channels"); ?>",
                            data: {
                                start_date: "<?php echo $start_date; ?>",
                                end_date: "<?php echo $end_date; ?>",
                                category_id: "<?php echo $category_id; ?>",
                                json_zone_ids: zone_ids,
                                json_channel_ids: channel_ids,
                                zone_val: "0",
                                out_val: "0",
                                date_type: "<?php echo $date_type; ?>",
                                cluster_id: "<?php echo $cluster_id; ?>"
                            },
                            type: "POST",
                            success: function (data) {
                                $('#content_clusters<?php echo $cluster_id; ?>').html(data);
                            }
                        });
                    </script>
                </div> <!-- end portlet light -->
            <?php } // end clusters foreach      ?>
        </div> <!-- end col-md-12 -->
    </div>  <!-- end row 2-->

<?php } // end if(!empty($clusters)){  ?>



<!--     Channels              -->
<?php if (!empty($channels)) { ?>
    <div class="row">
        <div class="col-md-12">
            <?php
            foreach ($selected_channel_ids as $channel_id) {
                //$zone_id = $zone->id;
                //$zone_name = $zone->name;
                ?>
                <div class="portlet light ">
                    <div class="portlet-title tabbable-line">
                        <div class="caption">
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject font-red bold uppercase"><?php echo $this->Channel_model->get_channel_name($channel_id); ?></span>
                        </div>
                    </div>


                    <div  style ="height:700px" id="content_channel<?php echo $channel_id; ?>"> </div>


                    <script type="text/javascript">
                        $('#content_channel<?php echo $channel_id; ?>').html('<div class="col-md-12"><img src="<?php echo base_url('assets/img/ajax-loading.gif'); ?>" class="img-responsive img-center" /></div>');
                        jQuery.ajax({
                            url: "<?php echo site_url("reports/load_shelf_channel_pie_chart"); ?>",
                            data: {
                                start_date: "<?php echo $start_date; ?>",
                                end_date: "<?php echo $end_date; ?>",
                                category_id: "<?php echo $category_id; ?>",
                                channel_id: "<?php echo $channel_id; ?>",
                                date_type: "<?php echo $date_type; ?>"

                            },
                            type: "POST",
                            success: function (data) {
                                $('#content_channel<?php echo $channel_id; ?>').html(data);
                            }
                        });
                    </script>
                </div> <!-- end portlet light -->
            <?php } // end clusters foreach      ?>
        </div> <!-- end col-md-12 -->
    </div>  <!-- end row 2-->

<?php } // end if(!empty($zones)){  ?>


<script>
    function showchart(data)
    {
        var chart = AmCharts.makeChart("pie_chart", {

            "type": "pie",
            "theme": "light",
            "dataProvider": data,
            "valueField": "metrage",
            "titleField": "brand_name",
            "titles": [
                {
                    "text": "Shelf share",
                    "size": 15
                }
            ],
            "outlineAlpha": 0.4,
            "colorField": "color_name",
            "fontSize": 16,
            "depth3D": 15,
            "balloonText": "[[title]]<br><span style='font-size:16px'><b>[[value]]</b> ([[percents]]%)</span>",
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
    }

</script>

<script>
    $(document).ready(function () {

        var chartdata = [];
        var tab_perc = [];
        var tot_metrage = 0;
        tot_metrage = <?php echo $sum_metrage; ?>;
        //iterate through each row in the table
        // Total Shelf
        $('tr').each(function () {
            //the value of sum needs to be reset for each row, so it has to be set inside the row loop
            var sum_shelf = 0;
            var sum_metrage = 0;


            //find the combat elements in the current row and sum it 
            // Calculate Total Shelf for each row
            $(this).find('.shelf_brand').each(function () {
                var shelf = $(this).text();
                var shelf = shelf.replace(",", "");
                if (!isNaN(shelf) && shelf.length !== 0 && shelf > 0) {
                    sum_shelf += parseFloat(shelf);
                }
            });
            //set the value of currents rows sum to the total-combat element in the current row
            // Calculate Total Metrage for each row
            $(this).find('.metrage_brand').each(function () {
                var metrage = $(this).text();
                var metrage = metrage.replace(",", "");
                if (!isNaN(metrage) && metrage.length !== 0 && metrage > 0) {
                    sum_metrage += parseFloat(metrage);
                }
            });
            $('.total-shelf_brand', this).html(parseFloat(sum_shelf).toFixed(0));
            $('.total-metrage_brand', this).html(parseFloat(sum_metrage).toFixed(2));
            $('.total-perc_brand', this).html(parseFloat((sum_metrage / tot_metrage) * 100).toFixed(2));

            // Retrieve Brand name
            $(this).find('.brand_brand').each(function () {
                brand = $(this).text();
            });

            // Retrieve color for each brand
            $(this).find('.color_color').each(function () {
                color = $.trim($(this).text());
            });

            //metrage = (parseFloat(sum_metrage / count).toFixed(2));
            if (sum_metrage > 0)
            {
                chartdata.push(
                        {
                            brand_name: brand,
                            color_name: color,
                            metrage: parseFloat(sum_metrage).toFixed(2)
                        }
                )
            }

        });// end foreach TR
        showchart(chartdata);
    });
</script>

<script>
    $(document).ready(function () {

        var table, rows, switching, i, x, y, shouldSwitch;
        table = document.getElementById("myTable");
        switching = true;
        /*Make a loop that will continue until
         no switching has been done:*/
        while (switching) {
            //start by saying: no switching is done:
            switching = false;
            rows = table.getElementsByTagName("TR");
            /*Loop through all table rows (except the
             first, which contains table headers):*/
            for (i = 2; i < (rows.length - 1); i++) {
                //start by saying there should be no switching:
                shouldSwitch = false;
                /*Get the two elements you want to compare,
                 one from current row and one from the next:*/
                x = rows[i].getElementsByClassName("total-metrage_brand");
                y = rows[i + 1].getElementsByClassName("total-metrage_brand");

                //check if the two rows should switch place:
                if (parseInt(x[0].innerHTML.toLowerCase()) < parseInt(y[0].innerHTML.toLowerCase())) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
                rows[2].getElementsByClassName("rank")[0].innerHTML = 1;
                rows[i + 1].getElementsByClassName("rank")[0].innerHTML = i;

            }
            if (shouldSwitch) {
                /*If a switch has been marked, make the switch
                 and mark that a switch has been done:*/
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
            }
        }
    });
</script>