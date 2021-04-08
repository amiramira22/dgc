<?php
//bcm

$dates = array();
$components = array();
$date_components = array();
$count_date = 0;
$brand_colors = array();

foreach ($report_data as $row) {
    $date = $row['date'];
    if (!in_array($date, $dates)) {
        $dates[] = $date;
        $count_date += 1;
    }

    $brand_colors[$row['brand_name']] = $row['brand_color'];
    //create an array for every brand and the count at a outlet
    $components[$row['brand_name']][$date] = array($row['av'], $row['oos'], $row['ha'], $row['total']);

    $date_components[$date] [$row['brand_name']] = number_format(($row['oos'] / $row['total']) * 100, 2, '.', ' ');
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

                <?php foreach ($dates as $date) { ?>
                    <th class ="text-center" colspan="3">
                        <?php
                        echo format_qmw_date($date_type, $date);
                        ?>
                    </th>
                <?php } ?>
                <th colspan="3">Average</th>
            </tr>

            <tr>

                <th>Brand</th>

                <?php foreach ($dates as $date) { ?>
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
            foreach ($components as $brand_name => $componentDates) {
                $i++;
                ?>
                <tr>
                    <td><?php echo $brand_name; ?></td>
                    <?php foreach ($dates as $date) { ?>
                        <td class="av_row"><?php
                            if (isset($componentDates[$date][0])) {
                                echo number_format(($componentDates[$date][0] / $componentDates[$date][3]) * 100, 2, '.', ' ');
                            } else
                                echo '-';
                            ?> </td>


                        <td class="oos_row"><?php
                            if (isset($componentDates[$date][1])) {
                                echo number_format(($componentDates[$date][1] / $componentDates[$date][3]) * 100, 2, '.', ' ');
                            } else
                                echo '-';
                            ?> </td>

                        <td class="ha_row"><?php
                            if (isset($componentDates[$date][2])) {
                                echo number_format(($componentDates[$date][2] / $componentDates[$date][3]) * 100, 2, '.', ' ');
                            } else
                                echo '-';
                            ?> </td>
                    <?php } // end foreach dates  ?>

                    <td class="total-av_row"></td>
                    <td class="total-oos_row"></td>
                    <td class="total-ha_row"></td>
                <?php }// end foreach components   ?>


            </tr>

        </tbody>



    </table>

    <div style="height:500px; width:100%;" id="chartHenkelalone"></div>


<!--    <style>

#chartdiv {
    width: 100%;
    height: 350px;
    background: #5d3bb4;
    background: linear-gradient(to bottom, #626dd4, #5d3bb4);
    color: #fff;
    font-weight: 100;
    overflow: hidden;
}

.amcharts-graph-g4 {
    filter: url(#blur);
}
</style>
<div  id="chartdiv"></div>-->
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
                    <div id="content_all_zones<?php echo $cluster_id; ?>-1"> </div>

                    <script type="text/javascript">

                        $('#content_all_zones<?php echo $cluster_id; ?>-1').html('<div class="col-md-12"><img src="<?php echo base_url('assets/img/ajax-loading.gif'); ?>" class="img-responsive img-center" /></div>');
                        jQuery.ajax({
                        url: "<?php echo site_url("reports/load_av_cluster"); ?>",
                                data: {
                                start_date: "<?php echo $start_date; ?>",
                                        end_date: "<?php echo $end_date; ?>",
                                        date_type: "<?php echo $date_type; ?>",
                                        category_id: "<?php echo $category_id; ?>",
                                        cluster_id: "<?php echo $cluster_id; ?>",
                                        zone_id: "-1",
                                        channel_id: "-1",
                                        zone_val: "0",
                                        out_val: "0"
                                },
                                type: "POST",
                                success: function (data) {
                                $('#content_all_zones<?php echo $cluster_id; ?>-1').html(data);
                                }
                        });
                    </script>

                </div> <!-- end portlet light -->
            <?php } // end clusters foreach      ?>
        </div> <!-- end col-md-12 -->
    </div>  <!-- end row 2-->


<?php } // end if(!empty($clusters)) {   ?>








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
<?php foreach ($date_components as $date => $componentBrands) { ?>

        trend_data.push(
        {
        "date": "<?php echo format_qmw_date($date_type, $date); ?>",
    <?php foreach ($componentBrands as $brand => $value) { ?>


            "<?php echo $brand; ?>": <?php echo $value; ?>,
    <?php } ?>
        }
        ); // end push trend data

<?php } ?>


    var chart = AmCharts.makeChart("chartHenkelalone", {
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
//        pie_data.push(
//                {
//                    name: "oos",
//                    value: s_oos,
//                    color: "#DF0101"
//                });
//        pie_data.push(
//                {
//                    name: "av",
//                    value: s_av,
//                    "color": "#298A08"
//                });
//
//        pie_data.push(
//                {
//                    name: "ha",
//                    value: s_ha,
//                    "color": "#000000"
//                }
//
//        );
//
//
//        showchart(pie_data);
//
//
//        function showchart(data)
//        {
//
//
//            var chart = AmCharts.makeChart("chartHenkelalone", {
//                "type": "pie",
//                "theme": "light",
//                "dataProvider": data,
//                "valueField": "value",
//                "titleField": "name",
//                "colorField": "color",
//                "fontSize": 20,
//                "depth3D": 10,
//                "angle": 30,
//                "titles": [
//                    {
//                        "text": "Henkel stock issues",
//                        "size": 15
//                    }
//                ],
//
//                "export": {
//                    "enabled": true
//                }
//            });
//        }

    });
</script>


<!--<script>

    var chart = AmCharts.makeChart("chartdiv", {
        "type": "serial",
        "theme": "light",
        "addClassNames": true,
        "marginLeft": 25,
        "marginRight": 25,
        "marginTop": 45,
        "marginBottom": 0,
        "autoMarginOffset": 15,
        "startDuration": 2,
        "sequencedAnimation": false,
        "dataProvider": [{
                "month": "JAN",
                "value1": 40,
                "value2": 5,
                "value3": 0,
                "value4": 10,
                "color": "#807fd3"
            }, {
                "month": "FEB",
                "value1": 10,
                "value2": 5,
                "value3": 0,
                "value4": 10,
                "color": "#6e6abc"
            }, {
                "month": "MAR",
                "value1": 40,
                "value2": 5,
                "value3": 0,
                "value4": 10,
                "color": "#807fd3"
            }, {
                "month": "APR",
                "value1": 22,
                "value2": 5,
                "value3": 0,
                "value4": 10,
                "color": "#6e6abc"
            }],
        "graphs": [{
                "id": "g1",
                "lineAlpha": 0,
                "lineThickness": 3,
                "valueField": "value1",
                "showBalloon": false
            }, {
                "id": "g2",
                "lineAlpha": 0,
                "lineColor": "#fff",
                "lineThickness": 0,
                "fillColors": "#807fd3",
                "fillColorsField": "color",
                "fillAlphas": 1,
                "valueField": "value2",
                "showBalloon": false
            }, {
                "id": "g3",
                "lineAlpha": 1,
                "lineColor": "#fff",
                "lineThickness": 5,
                "valueField": "value3",
                "balloonColor": "#5fb3f3",
                "balloonText": "[[value1]]",
                "balloon": {
                    "drop": true,
                    "adjustBorderColor": false,
                    "color": "#ffffff"
                }
            }, {
                "id": "g4",
                "lineAlpha": 1,
                "lineColor": "#000",
                "lineThickness": 10,
                "valueField": "value4",
                "showBalloon": false,
                "stackable": false,
                "lineAlpha": 0.6
            }
        ],
        "categoryField": "month",
        "categoryAxis": {
            "color": "#8a86c7",
            "axisColor": "#5957b1",
            "gridAlpha": 0,
            "startOnAxis": false,
            "balloon": {
                "fillAlpha": 1,
                "fontSize": 15,
                "horizontalPadding": 10
            }
        },
        "valueAxes": [{
                "stackType": "regular",
                "gridAlpha": 0,
                "gridColor": "#5957b1",
                "axisAlpha": 0,
                "labelsEnabled": false,
                "minimum": 0,
                "maximum": 100,
                "ignoreAxisWidth": true
            }],
        "balloon": {
            "borderThickness": 0,
            "shadowAlpha": 0,
            "fontSize": 18
        },
        "chartCursor": {
            "cursorAlpha": 0.7,
            "cursorColor": "#5fb3f3",
            "limitToGraph": "g3",
            "categoryBalloonColor": "#5e59b9",
            "categoryBalloonAlpha": 1,
            "zoomable": false
        },
        "defs": {
            "filter": [{
                    "x": "-50%",
                    "y": "-50%",
                    "width": "200%",
                    "height": "200%",
                    "id": "blur",
                    "feGaussianBlur": {
                        "in": "SourceGraphic",
                        "stdDeviation": "15"
                    }
                }]
        }
    });

</script>-->
