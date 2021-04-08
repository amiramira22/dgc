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
    $components[$row['brand_name']][$date] = array($row['shelf'], $row['metrage']);
    $components_chart[$row['brand_name']][$date] = $row['metrage'];
    $components_date [$row['date']] [$row['brand_name']] = $row['metrage'];

    $brand_colors[$row['brand_name']] = $row['brand_color'];
}
$sum_metrage_date = array();
foreach ($components_date as $date => $componentBrand) {
    $sum_metrage_date[$date] = array_sum(array_values($componentBrand));
}
?>   

<div class="portlet light ">
    <div class="portlet-title tabbable-line">
        <div class="caption">
            <span class="caption-subject font-red bold uppercase"> Shelf Share Report </span>
        </div>
    </div>


    <table class="table table-striped table-bordered table-hover " width="100%">
        <thead>
            <tr>
                <th></th>

                <?php foreach ($dates as $date) { ?>
                    <th class ="text-center" colspan="3">
                        <?php
                        //echo $date_type;
                        echo format_qmw_date($date_type, $date);
                        ?>
                    </th>
                <?php } ?>
                <th class ="text-center" colspan="3">Average</th>
            </tr>

            <tr>

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
                                echo number_format(($componentDates[$date][1]), 2, '.', ' ');
                            } else
                                echo '-';
                            ?> 
                        </td>

                        <td class="perc">
                            <?php
                            if (isset($componentDates[$date])) {
                                echo number_format(($componentDates[$date][1] / $sum_metrage_date[$date]) * 100, 2, '.', ' ');
                            } else
                                echo '-';
                            ?> 
                        </td>

                    <?php } // end foreach dates         ?>

                    <td class="total-shelf_row"></td>
                    <td class="total-metrage_row"></td>
                    <td class="total-perc_row"></td>
                <?php }// end foreach components    ?>
            </tr>

            <tr>
                <td align="center">Total</td>
                <?php foreach ($dates as $date) { ?>
                    <td class="sumshelfdate"></td>
                    <td class="summetragedate"></td>
                    <td class="sumpercdate"></td>
                <?php } // end foreach zones    ?>
                <td class="sumshelftotal"></td>
                <td class="summetragetotal"></td>
                <td class="sumperctotal"></td>
            </tr>

        </tbody>
    </table>

    <div style="height:600px; width:100%;" id="chartHenkelalone"></div>

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
                    <div id="content_cluster<?php echo $cluster_id; ?>-1"> </div>
                    <script type="text/javascript">
                        $('#content_cluster<?php echo $cluster_id; ?>-1').html('<div class="col-md-12"><img src="<?php echo base_url('assets/img/ajax-loading.gif'); ?>" class="img-responsive img-center" /></div>');
                        jQuery.ajax({
                        url: "<?php echo site_url("reports/load_shelf_cluster"); ?>",
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
                                $('#content_cluster<?php echo $cluster_id; ?>-1').html(data);
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

    //*************************************
    $(this).find('.shelf').each(function () {
    var shelf = $(this).text();
    if (!isNaN(shelf) && shelf.length !== 0) {
    sum_shelf += parseFloat(shelf);
    n_shelf++;
    }
    });
    //***************************************
    $(this).find('.metrage').each(function () {
    var metrage = $(this).text();
    if (!isNaN(metrage) && metrage.length !== 0) {
    sum_metrage += parseFloat(metrage);
    n_metrage++;
    }
    });
    //***************************************
    $(this).find('.perc').each(function () {
    var perc = $(this).text();
    if (!isNaN(perc) && perc.length !== 0) {
    sum_perc += parseFloat(perc);
    n_perc++;
    }
    });
    //***************************************
    if (brand == "HENKEL") {
    bvm_shelf = sum_shelf;
    bvm_perc = sum_perc;
    }
    //set the value of currents rows sum to the total-combat element in the current row
    $('.total-shelf_row', this).html(parseFloat(sum_shelf / n_shelf).toFixed(2));
    $('.total-metrage_row', this).html(parseFloat(sum_metrage / n_metrage).toFixed(2));
    $('.total-perc_row', this).html(parseFloat(sum_perc / n_perc).toFixed(2));
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
    });</script>

<script>
    $(function () {
    function tally(selector) {
    $(selector).each(function () {
    var sumshelfzone = 0,
            column = $(this).siblings(selector).andSelf().index(this);
    $(this).parents().prevUntil(':has(' + selector + ')').each(function () {
    sumshelfzone += parseFloat($('td.shelf:eq(' + column + ')', this).html()) || 0;
    })
            $(this).html(sumshelfzone.toFixed(2));
    });
    }
    tally('td.sumshelfdate');
    });</script>


<script>
    $(function () {
    function tally(selector) {
    $(selector).each(function () {
    var summetragezone = 0,
            column = $(this).siblings(selector).andSelf().index(this);
    $(this).parents().prevUntil(':has(' + selector + ')').each(function () {
    summetragezone += parseFloat($('td.metrage:eq(' + column + ')', this).html()) || 0;
    })
            $(this).html(summetragezone.toFixed(2));
    });
    }
    tally('td.summetragedate');
    });</script>

<script>
    $(function () {
    function tally(selector) {
    $(selector).each(function () {
    var sumperczone = 0,
            column = $(this).siblings(selector).andSelf().index(this);
    $(this).parents().prevUntil(':has(' + selector + ')').each(function () {
    sumperczone += parseFloat($('td.perc:eq(' + column + ')', this).html()) || 0;
    })
            $(this).html(sumperczone.toFixed(2));
    });
    }
    tally('td.sumpercdate');
    });</script>


<script>
    $(function () {
    function tally(selector) {
    $(selector).each(function () {
    var sumshelftotal = 0,
            column = $(this).siblings(selector).andSelf().index(this);
    $(this).parents().prevUntil(':has(' + selector + ')').each(function () {
    sumshelftotal += parseFloat($('td.total-shelf_row:eq(' + column + ')', this).html()) || 0;
    })
            $(this).html(sumshelftotal.toFixed(2));
    });
    }
    tally('td.sumshelftotal');
    });</script>



<script>
    $(function () {
    function tally(selector) {
    $(selector).each(function () {
    var summetragetotal = 0,
            column = $(this).siblings(selector).andSelf().index(this);
    $(this).parents().prevUntil(':has(' + selector + ')').each(function () {
    summetragetotal += parseFloat($('td.total-metrage_row:eq(' + column + ')', this).html()) || 0;
    })
            $(this).html(summetragetotal.toFixed(2));
    });
    }
    tally('td.summetragetotal');
    });</script>

<script>
    $(function () {
    function tally(selector) {
    $(selector).each(function () {
    var sumperctotal = 0,
            column = $(this).siblings(selector).andSelf().index(this);
    $(this).parents().prevUntil(':has(' + selector + ')').each(function () {
    sumperctotal += parseFloat($('td.total-perc_row:eq(' + column + ')', this).html()) || 0;
    })
            $(this).html(sumperctotal.toFixed(2));
    });
    }
    tally('td.sumperctotal');
    });

</script>

<style>

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


<!--
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
            $(this).find('.shelf').each(function () {
                var shelf = $(this).text();
                if (!isNaN(shelf) && shelf.length !== 0) {
                    sum += parseFloat(shelf);
                    n++;
                }
            });
            //set the value of currents rows sum to the total-combat element in the current row
            $('.total-shelf_row', this).html(parseFloat(sum / n).toFixed(2));
        });


        $('tr').each(function () {
            //the value of sum needs to be reset for each row, so it has to be set inside the row loop
            var sum = 0
            var n = 0
            //find the combat elements in the current row and sum it 
            $(this).find('.perc').each(function () {
                var perc = $(this).text();

                if (!isNaN(perc) && perc.length !== 0) {
                    sum += parseFloat(perc);
                    n++;
                }
            });

            //set the value of currents rows sum to the total-combat element in the current row
            $('.total-perc_row', this).html(parseFloat(sum / n).toFixed(2));
        });


        /*****************************************************/
// Pie Chart
        /*****************************************************/



        var pie_data = [];


        var sum = 0;
        var n = 0;
        var i = 0;
        var s_ha = 0;
        var s_perc = 0;
        var s_shelf = 0;
        $(this).find('.shelf_row').each(function () {

            var perc = $(this).text();

            if (!isNaN(perc) && perc.length !== 0) {
                s_shelf += parseFloat(perc);
                n++;
                i++;
            }
        });

        $(this).find('.perc_row').each(function () {

            var shelf = $(this).text();

            if (!isNaN(shelf) && shelf.length !== 0) {
                s_perc += parseFloat(shelf);
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
                    name: "perc",
                    value: s_perc,
                    color: "#DF0101"
                });
        pie_data.push(
                {
                    name: "shelf",
                    value: s_shelf,
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
                //"fontSize": 16,
                "depth3D": 10,
                "angle": 30,
                "titles": [
                    {
                        "text": "Henkel stock issues",
                        "size": 15
                    }
                ],

                "legend": {
                    "position": "bottom",
                    "align": "center",
                    "autoMargins": false
                },

                "export": {
                    "enabled": true
                }
            });
        }

    });
</script>



<script>

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

</script>
-->
