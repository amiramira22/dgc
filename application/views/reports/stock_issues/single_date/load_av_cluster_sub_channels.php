<?php
$channels = array();
$components = array();

foreach ($report_data as $row) {
    $channel = $row['sub_channel'];
    if (!in_array($channel, $channels)) {
        $channels[] = $channel;
    }
    //create an array for every brand and the count at a outlet
    $components[$row['product_id']][$channel] = array($row['av'], $row['oos'], $row['ha'], $row['total']);
}// end foreach report_data
?>

<div class="portlet light ">
    <div class="portlet-title tabbable-line">
        <div class="caption">
            <span class="caption-subject font-red bold uppercase">  </span>
        </div>
    </div>

    <table class="table table-striped table-bordered table-hover " width="100%">
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
                <th colspan="3">Average</th>
            </tr>

            <tr>

                <th>Brand</th>
                <th>Product</th>

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
            foreach ($components as $product_id => $componentChannels) {
                $i++;
                $product = $this->Product_model->get_product($product_id);
                $product_name = $product->name;
                $brand_name = $this->Brand_model->get_brand_name($product->brand_id);
                ?>
                <tr>
                    <td><?php echo $brand_name; ?></td>
                    <td><?php echo $product_name; ?></td>
                    <?php foreach ($channels as $channel) {
                        $total=$componentChannels[$channel][0]+$componentChannels[$channel][1];
                        ?>

                        <td class="av_row<?php echo $cluster_id; ?>_<?php echo $zone_val . '_' . $out_val; ?>"><?php
                            if (isset($componentChannels[$channel][0]) && $total!=0) {
                                echo number_format(($componentChannels[$channel][0] / $total) * 100, 2, '.', '');
                            } else
                                echo '-';
                            ?> 
                        </td>

                        <td class="oos_row<?php echo $cluster_id; ?>_<?php echo $zone_val . '_' . $out_val; ?>"><?php
                            if (isset($componentChannels[$channel][1]) && $total!=0) {
                                echo number_format(($componentChannels[$channel][1] / $total) * 100, 2, '.', '');
                            } else
                                echo '-';
                            ?> 
                        </td>

                        <td class="ha_row<?php echo $cluster_id; ?>_<?php echo $zone_val . '_' . $out_val; ?>"><?php
                            if (isset($componentChannels[$channel][2])) {
                                echo number_format(($componentChannels[$channel][2] / $componentChannels[$channel][3]) * 100, 2, '.', '');
                            } else
                                echo '-';
                            ?> 
                        </td>

                    <?php } // end foreach dates  ?>

                    <td class="total-av_row<?php echo $cluster_id; ?>_<?php echo $zone_val . '_' . $out_val; ?>"></td>
                    <td class="total-oos_row<?php echo $cluster_id; ?>_<?php echo $zone_val . '_' . $out_val; ?>"></td>
                    <td class="total-ha_row<?php echo $cluster_id; ?>_<?php echo $zone_val . '_' . $out_val; ?>"></td>
                <?php }// end foreach components  ?>
            </tr>
        </tbody>
    </table>

    <div style="height:500px; width:100%;" id="chartHenkelalone<?php echo $cluster_id; ?>_<?php echo $zone_val . '_' . $out_val; ?>"></div>
</div>


<script>
    $(document).ready(function () {
        /*****************************************************/
// Pie Chart
        /*****************************************************/

//iterate through each row in the table
        var total_av = 0;
        var total_oos = 0;
        var total_ha = 0;
        var nb_rows = 0;
        $('tr').each(function () {
            nb_rows++;
//the value of sum needs to be reset for each row, so it has to be set inside the row loop
            var sum_av = 0;
            var sum_oos = 0;
            var sum_ha = 0;
            var n_av = 0
            var n_oos = 0
            var n_ha = 0
//find the combat elements in the current row and sum it 
            $(this).find('.av_row<?php echo $cluster_id; ?>_<?php echo $zone_val . '_' . $out_val; ?>').each(function () {
                var shelf = $(this).text();
                if (!isNaN(shelf) && shelf.length !== 0) {
                    sum_av += parseFloat(shelf);
                    total_av += sum_av;
                    n_av++;
                }
            });

            $(this).find('.oos_row<?php echo $cluster_id; ?>_<?php echo $zone_val . '_' . $out_val; ?>').each(function () {
                var perc = $(this).text();

                if (!isNaN(perc) && perc.length !== 0) {
                    sum_oos += parseFloat(perc);
                    total_oos += sum_oos;
                    n_oos++;
                }
            });

            $(this).find('.ha_row<?php echo $cluster_id; ?>_<?php echo $zone_val . '_' . $out_val; ?>').each(function () {
                var perc = $(this).text();

                if (!isNaN(perc) && perc.length !== 0) {
                    sum_ha += parseFloat(perc);
                    total_ha += sum_ha;
                    n_ha++;
                }
            });


//set the value of currents rows sum to the total-combat element in the current row

//set the value of currents rows sum to the total-combat element in the current row
            $('.total-av_row<?php echo $cluster_id; ?>_<?php echo $zone_val . '_' . $out_val; ?>', this).html(parseFloat(sum_av / n_av).toFixed(2));
            $('.total-oos_row<?php echo $cluster_id; ?>_<?php echo $zone_val . '_' . $out_val; ?>', this).html(parseFloat(sum_oos / n_oos).toFixed(2));
            $('.total-ha_row<?php echo $cluster_id; ?>_<?php echo $zone_val . '_' . $out_val; ?>', this).html(parseFloat(sum_ha / n_ha).toFixed(2));
        });

        /*****************************************************/
// Pie Chart
        /*****************************************************/

        var pie_data = [];

        pie_data.push(
                {
                    name: "oos",
                    value: (total_oos / nb_rows) * 100,
                    color: "#DF0101"
                });
        pie_data.push(
                {
                    name: "av",
                    value: (total_av / nb_rows) * 100,
                    "color": "#298A08"
                });

        pie_data.push(
                {
                    name: "ha",
                    value: (total_ha / nb_rows) * 100,
                    "color": "#000000"
                }

        );


        showchart(pie_data);

        function showchart(data)
        {


            var chart = AmCharts.makeChart("chartHenkelalone<?php echo $cluster_id; ?>_<?php echo $zone_val . '_' . $out_val; ?>", {
                "type": "pie",
                "theme": "light",
                "dataProvider": data,
                "valueField": "value",
                "titleField": "name",
                "colorField": "color",
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