<script type="text/javascript">
    $(function () {
        $("#datepicker1").datepicker({dateFormat: 'yy-mm-dd'});
        $("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});
    });</script>


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet light">

            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-magnifier font-red"></i>
                    <span class="caption-subject bold uppercase"> Search</span>
                    <span class="caption-helper"></span>
                </div>
            </div> <!-- end portlet-title -->

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal', 'autocomplete' => 'off'); ?>
                <?php echo form_open('outlets/numeric_distribution/', $attributes) ?>
                <div class="form-body">

                    <div class="form-group">
                        <label class="control-label col-md-2">Start date </label>
                        <div class="col-md-4">
                            <input type="text" name="start_date"
                                   value="<?php echo $start_date; ?>"
                                   id="datepicker1"
                                   class="form-control"/>
                        </div>

                        <label class="control-label col-md-2">End date </label>
                        <div class="col-md-4">
                            <input type="text" name="end_date"
                                   value="<?php echo $end_date; ?>"
                                   id="datepicker2"
                                   class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Channel</label>
                        <div class="col-md-10">
                            <?php
                            $channel_ids = array();
                            $channel_ids[-1] = 'All Channel';
                            foreach ($channels as $chl) {
                                $channel_ids[$chl->id] = $chl->name;
                            }
                            ?>
                            <?php echo form_dropdown('channel_id', $channel_ids, set_value('channel_id', $channel_id), 'class="form-control"');
                            ?>
                        </div>


                    </div>
                    <div class="form-group">
                        <!--                        <div class="col-md-2"><input type="checkbox" name="chek_category_id" value="category"></div>-->
                        <div class="control-label col-md-2"></div>

                        <div class="col-md-3">
                            <?php
                            $category_ids = array();
                            $category_ids['0'] = 'All Categories';
                            foreach ($categories as $category) {
                                $category_ids[$category->id] = $category->name;
                            }
                            ?>
                            <?php echo form_dropdown('category_id', $category_ids, set_value('category_id', $category_id), 'id = specific_category class = "span3 form-control"'); ?>
                        </div>


                        <div class="col-md-2">
                            <?php
                            $sub_category_ids = array();
                            $sub_category_ids['0'] = 'All Sub categories';
                            foreach ($sub_categories as $sub_category) {
                                $sub_category_ids[$sub_category->id] = $sub_category->name;
                            }
                            ?>
                            <?php echo form_dropdown('sub_category_id', $sub_category_ids, set_value('sub_category_id', $sub_category_id), 'id = specific_sub_category class = "span3 form-control"'); ?>
                        </div>


                        <div class="col-md-2">
                            <?php
                            $product_group_ids = array();
                            $product_group_ids['0'] = 'All Product groups';
                            foreach ($product_groups as $product_group) {
                                $product_group_ids[$product_group->id] = $product_group->name;
                            }
                            ?>
                            <?php echo form_dropdown('product_group_id', $product_group_ids, set_value('product_group_id', $product_group_id), 'id = specific_product_group class = "span3 form-control"'); ?>
                        </div>

                        <div class="col-md-3">
                            <?php
                            $product_ids = array();
                            $product_ids['0'] = 'All Products';
                            foreach ($products as $product) {
                                $product_ids[$product->id] = $product->name;
                            }
                            ?>
                            <?php echo form_dropdown('product_id', $product_ids, set_value('product_id', $product_id), 'id = specific_product class = "span3 form-control"'); ?>
                        </div>
                    </div>
                </div> <!--  end form-body-->

                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle red-mint btn-outline sbold uppercase">Search
                            </button>
                            <button type="reset" class="btn btn-circle btn-default sbold uppercase">Cancel</button>
                        </div>
                    </div>
                </div>

                </form>
                <!-- END FORM-->
            </div>
        </div>
        <!-- END VALIDATION STATES-->
    </div>


</div> <!-- END ROW FORM-->

<?php
//echo '$start_date'.$start_date;
//echo '<br>';
//echo '$end_date'.$end_date;
//echo '<br>';
//echo '$channel_id'.$channel_id;
//echo '<br>';
//echo '$av_type'.$av_type;
//echo '<br>';
//echo '$category_id'.$category_id;
//echo '<br>';
//echo '$sub_category_id'.$sub_category_id;
//echo '<br>';
//echo '$cluster_id'.$cluster_id;
//echo '<br>';
//echo '$product_group_id'.$cluster_id;
//echo '<br>';
//echo '$product_id'.$product_id;
//echo '<br>';
//echo '<br>';
//foreach ($outlets as $outlet) {
//    print_r($outlet);
//    echo '<br>';
//    echo '<br>';
//}
if (isset($outlets_for_map_two)) {
//    print_r($outlets_for_map_two);
//    echo'<br>';
//    echo'<br>';
    $area_st = array();
    $area = '';
    foreach ($outlets_for_map_two as $outlet) {


        $area_st += ["id" => $outlet->code_map];
        $area_st += ["title" => $outlet->state];

        if (isset($outlet->av))
            $area_st += ["value" => number_format($outlet->av, 2, ".", ",")];
        else if (isset($outlet->oos))
            $area_st += ["value" => number_format($outlet->oos, 2, ".", ",")];
        else if (isset($outlet->ha))
            $area_st += ["value" => number_format($outlet->ha, 2, ".", ",")];

//        print_r($outlet);
//        echo'<br>';
//        echo'<br>';
//        print_r($area_st);
//        echo'<br>';
//        echo'<br>';

        $area .= json_encode($area_st, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ',';

        $area_st = array();
    }
    //print_r($area);
}
//$state = $this->State_model->get_states();
//print_r($state);
//echo'<br>';
//echo'<br>';
//echo'<br>';
//foreach ($state as $st) {
//    $st += ["value" => 2];
//    print_r($st);
//    echo'<br>';
//    $st = json_encode($st);
//    print_r($st);
//    echo'<br>';
//    echo'<br>';
//}
//json_encode($state);
//print_r($state);
?>
<style>
    span {
        margin-left: 12px;
    }
</style>

<style>
    .tab-pane {
        height: 700px !important;
    }

    .my_body {
        width: 100%;
        height: 100%;
        margin: 0px;
    }

    .chartclass {
        width: 100%;
        height: 100%;
    }

    /*
        .chartwrapper {
            width: 100%;
            height: 100%;
    
            position: relative;
    
        }
    
        .chartdiv {
            position: absolute;
            width: 100%;
            height: 100%;
    
        }*/
</style>


<?php if (!empty($outlets)) { ?>
<div class='row'>
    <div class="col-md-12">
        <!-- BEGIN MARKERS PORT LET-->
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="portlet-title tabbable-line">
                    <div class="caption">
                        <div class="block">
                            <span class="caption-subject font-red bold uppercase">Outlet Numeric Distribution</span>

                            <?php if ($channel_id == 1) { ?>
                                <span class="label label-info" style="background-color: #003272"> UHD </span>
                            <?php } ?>

                            <?php if ($channel_id == 2) { ?>
                                <span class="label label-danger" style="background-color: #ff0000"> GEMO </span>
                            <?php } ?>

                            <?php if ($channel_id == 3) { ?>
                                <span class="label label-success" style="background-color: #008000"> MG </span>
                            <?php } ?>




                            <?php if ($channel_id == -1) { ?>
                                <span class="label label-info" style="background-color: #003272"> UHD </span>
                                <span class="label label-danger" style="background-color: #ff0000"> GEMO </span>
                                <span class="label label-success" style="background-color: #008000"> MG </span>
                            <?php } ?>

                        </div>
                        <br>
                        <ul class="nav nav-tabs">
                            <li class="active" id="maps_per_outlet">
                                <a href="#tab_maps_per_outlet" class="active" data-toggle="tab"> maps one </a>
                            </li>

                            <li class="" id="maps_per_state">
                                <a href="#tab_maps_per_state" data-toggle="tab"> maps two </a>
                            </li>
                        </ul>
                    </div>


                    <a class="btn btn-circle green btn-outline sbold uppercase"
                       href="<?php echo site_url('outlets/export_map/' . $start_date . '/' . $end_date . '/' . $channel_id . '/' . $category_id . '/' . $sub_category_id . '/' . $product_group_id . '/' . $product_id); ?>">
                        <i class="glyphicon glyphicon-export"></i> Export OOS
                    </a>


                </div>
            </div>
            <div class="portlet-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_maps_per_outlet">
                        <?php echo $map_one['js']; ?>
                        <?php echo $map_one['html']; ?>
                    </div>  <!--   tab1 content-->

                    <div class="tab-pane" id="tab_maps_per_state">
                        <div id="my_chartdiv" class="my_body">
                        </div>
                    </div>  <!--   tab1 content-->

                </div>
            </div>
            <!-- END MARKERS PORTLET-->
        </div>
    </div>

    <?php } ?>


    <script>

        var map = AmCharts.makeChart("my_chartdiv", {
            "type": "map",
            "theme": "light",

            "dataProvider": {

                "map": "tunisiaLow",
                "areas": [
                    <?php print_r($area) ?>
                ]
            },
            "areasSettings": {
                "autoZoom": true,
                "selectedColor": "#CC0000"
            },
            "imagesSettings": {
                "labelColor": "#000000",
                "labelRollOverColor": "#000000",
                "labelFontSize": 10,
                "labelPosition": "middle",

            },
            "allLabels": [
                {
                    "text": "availability : <?php echo 'AV'; ?> ",
                    "size": 15,
                    "bold": true,
                    "x": 20,
                    "y": 150
                }
            ]

        });
        map.addListener("init", function () {

            // small areas
            var small = [];
            // set up a longitude exceptions for certain areas
            var longitude = {};
            var latitude = {};
            // Positions of callouts
            var callouts = [];
            var offset = 0;
            setTimeout(function () {
                // iterate through areas and put a label over center of each
                //map.dataProvider.images = [];
                for (x in map.dataProvider.areas) {
                    var area = map.dataProvider.areas[x];
                    area.groupId = area.id;
                    var image = new AmCharts.MapImage();
                    image.title = area.title;
                    image.linkToObject = area;
                    image.groupId = area.id;
                    // callout or regular label
                    if (area.callout) {
                        image.latitude = callouts.shift();
                        image.longitude = 165;
                        image.label = area.value;
                        image.type = "rectangle";
                        image.color = area.color;
                        image.shiftX = offset;
                        image.width = 22;
                        image.height = 22;
                        // create additional image
                        var image2 = new AmCharts.MapImage();
                        image2.latitude = image.latitude;
                        image2.longitude = image.longitude;
                        image2.label = area.id.split('-').pop();
                        image2.labelColor = "#E83C1A";
                        image2.labelShiftX = 24;
                        image2.groupId = area.id;
                        map.dataProvider.images.push(image2);
                    } else {
                        image.latitude = latitude[area.id] || map.getAreaCenterLatitude(area);
                        image.longitude = longitude[area.id] || map.getAreaCenterLongitude(area);
                        image.label = area.value;

//                        image.label = area.title.split('-').pop() + "\n" + area.value;
                    }

                    map.dataProvider.images.push(image);
                }
                map.validateData();
            }, 100)
        });
    </script>


    <script type="text/javascript" language="javascript">
        //********************************************************
        $('#specific_category').change(function () {
            //first of all clear select items
            var data = $(this).find(':selected').val();
            var id = $(this).attr("id");
            //alert($(this).attr('id'));
            console.log(id);
            console.log(data);
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('outlets/get_all_data'); ?>",
                //                data: {category_id: category_id, sub_category: sub_category, cluster: cluster, product_group: product_group, product: product},
                data: {data: data, type: id},
                success: function (data) {
                    //                    var tab = json_decode(data);
                    //                    console.log('tab');
                    //                    console.log('test');
                    var res = jQuery.parseJSON(data);
                    console.log(res.categories);
                    console.log(res.sub_categories);
                    console.log(res.clusters);
                    console.log(res.product_groups);
                    console.log(res.products);
                    $("#specific_sub_category > option").remove();
                    $.each(res.sub_categories, function (id, out) {
                        var opt = $('<option/>');
                        opt.val(id);
                        opt.text(out);
                        $('#specific_sub_category').append(opt);
                    });
                    //***********************************************************************************


                    //***********************************************************************************

                    $("#specific_product_group > option").remove();
                    $.each(res.product_groups, function (id, out) {
                        var opt = $('<option/>');
                        opt.val(id);
                        opt.text(out);
                        $('#specific_product_group').append(opt);
                    });
                    //***********************************************************************************

                    $("#specific_product > option").remove();
                    $.each(res.products, function (id, out) {
                        var opt = $('<option/>');
                        opt.val(id);
                        opt.text(out);
                        $('#specific_product').append(opt);
                    });
                    //***********************************************************************************


                }
            });
        });
        $('#specific_sub_category').change(function () {
            //$("#specific_category > option").remove();
            //$("#specific_sub_category > option").remove();
            //            $("#specific_cluster > option").remove();
            //            $("#specific_product_group > option").remove();
            //            $("#specific_product > option").remove();
            //first of all clear select items
            var data = $(this).find(':selected').val();
            var id = $(this).attr("id");
            //alert($(this).attr('id'));
            console.log(id);
            console.log(data);
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('outlets/get_all_data'); ?>",
                //                data: {category_id: category_id, sub_category: sub_category, cluster: cluster, product_group: product_group, product: product},
                data: {data: data, type: id},
                success: function (data) {
                    //                    var tab = json_decode(data);
                    //                    console.log('tab');
                    //                    console.log('test');
                    var res = jQuery.parseJSON(data);
                    console.log(res.categories);
                    console.log(res.sub_categories);
                    console.log(res.clusters);
                    console.log(res.product_groups);
                    console.log(res.products);
                    $("#specific_product_group > option").remove();
                    $.each(res.product_groups, function (id, out) {
                        var opt = $('<option/>');
                        opt.val(id);
                        opt.text(out);
                        $('#specific_product_group').append(opt);
                    });
                    //***********************************************************************************


                    //***********************************************************************************

                    $("#specific_product > option").remove();
                    $.each(res.products, function (id, out) {
                        var opt = $('<option/>');
                        opt.val(id);
                        opt.text(out);
                        $('#specific_product').append(opt);
                    });
                    //***********************************************************************************


                }
            });
        });
        $('#specific_cluster').change(function () {


            //first of all clear select items
            var data = $(this).find(':selected').val();
            var id = $(this).attr("id");
            //alert($(this).attr('id'));
            console.log(id);
            console.log(data);
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('outlets/get_all_data'); ?>",
                //                data: {category_id: category_id, sub_category: sub_category, cluster: cluster, product_group: product_group, product: product},
                data: {data: data, type: id},
                success: function (data) {
                    //                    var tab = json_decode(data);
                    //                    console.log('tab');
                    //                    console.log('test');
                    var res = jQuery.parseJSON(data);
                    console.log(res.categories);
                    console.log(res.sub_categories);
                    console.log(res.clusters);
                    console.log(res.product_groups);
                    console.log(res.products);
                    $("#specific_product_group > option").remove();
                    $.each(res.product_groups, function (id, out) {
                        var opt = $('<option/>');
                        opt.val(id);
                        opt.text(out);
                        $('#specific_product_group').append(opt);
                    });
                    //***********************************************************************************

                    $("#specific_product > option").remove();
                    $.each(res.products, function (id, out) {
                        var opt = $('<option/>');
                        opt.val(id);
                        opt.text(out);
                        $('#specific_product').append(opt);
                    });
                    //***********************************************************************************


                }
            });
        });
        $('#specific_product_group').change(function () {

            //first of all clear select items
            var data = $(this).find(':selected').val();
            var id = $(this).attr("id");
            //alert($(this).attr('id'));
            console.log(id);
            console.log(data);
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('outlets/get_all_data'); ?>",
                //                data: {category_id: category_id, sub_category: sub_category, cluster: cluster, product_group: product_group, product: product},
                data: {data: data, type: id},
                success: function (data) {
                    //                    var tab = json_decode(data);
                    //                    console.log('tab');
                    //                    console.log('test');
                    var res = jQuery.parseJSON(data);
                    console.log(res.categories);
                    console.log(res.sub_categories);
                    console.log(res.clusters);
                    console.log(res.product_groups);
                    console.log(res.products);
                    //***********************************************************************************

                    $("#specific_product > option").remove();
                    $.each(res.products, function (id, out) {
                        var opt = $('<option/>');
                        opt.val(id);
                        opt.text(out);
                        $('#specific_product').append(opt);
                    });
                    //***********************************************************************************


                }
            });
        });</script>


