<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHuB62hGNykVD6L2axjCwu0KUPSBF1VBA&sensor=false"></script>

<?php
//bcm
include('header.php');
$outlets = $this->Outlet_model->get_outlets();
?>

<style>
    .page-sidebar .page-sidebar-menu, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu {
        list-style: none;
        margin: 1;
        padding: 0;
    }

    .tab-pane {
        height: 700px !important;
    }

    .my_body {
        width: 100%;
        height: 100%;
    }

    .chartclass {
        width: 100%;
        height: 100%;
    }
</style>


<style>
    .blue {
        background-color: #003272 !important;
    }

    .green {
        background-color: #008000 !important;
    }

    .yellow {
        background-color: #F1C40F !important;
    }

    .red {
        background-color: #ff0000 !important;
    }
</style>


<div class='row'>
    <div class="col-md-12">
        <!-- BEGIN MARKERS PORTLET-->
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">

                    <span class="caption-subject font-red bold uppercase">

                        <button type="button" class="btn default btn-sm" id="all">Show All Outlet Type</button>
                        <button type="button" class="btn blue btn-sm" id="g1">UHD</button>
                        <button type="button" class="btn red btn-sm" id="g2">Gemo</button>
                        <button type="button" class="btn green btn-sm" id="g3">MG</button>
                        <button type="button" class="btn dark btn-sm" id="g7">Inactive</button>
                        <!--<input type="button" name="count1" value="count markers" id="count1" class="btn default"/>-->


                    </span>
                </div>

            </div>
        </div>
        <!-- END MARKERS PORTLET-->
    </div>
</div>


<div class="tab-pane" id="tab_maps_per_state">
    <div id="my_chartdiv" class="my_body">
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {

        $("#my_chartdiv").goMap({
            latitude: 35.0534864,
            longitude: 9.2408933,
            zoom: 7.2,
            scaleControl: false,
            maptype: 'ROADMAP',
        });
        $.goMap.ready(function () {

            <?php foreach ($outlets as $outlet) { ?>
            var channel_id = <?php echo $outlet->channel_id; ?>;
            var outlet_active = <?php echo $outlet->active; ?>;
            var lt = <?php echo $outlet->latitude; ?>;
            var lg = <?php echo $outlet->longitude; ?>;
            var outlet_name = "<?php echo $outlet->name; ?>";
            var outlet_zone = "<?php echo $outlet->zone; ?>";
            var outlet_state = "<?php echo $outlet->state; ?>";
            var outlet_adress = "<?php echo $outlet->adress; ?>";
            var outlet_id = "<?php echo $outlet->id; ?>";
            var outlet_id = outlet_id.trim();

            //UHD
            if (channel_id == 1 && outlet_active == 1) {
                group = 'g1';
                icon = '<?php echo base_url("assets/img/blue1.png"); ?>';
            }
            //GEMO
            else if (channel_id == 2 && outlet_active == 1) {
                var group = 'g2';
                var icon = '<?php echo base_url("assets/img/red1.png"); ?>';
            }
            //MG
            else if (channel_id == 3 && outlet_active == 1) {
                group = 'g3';
                icon = '<?php echo base_url("assets/img/green1.png"); ?>';
            } else if (outlet_active == 0) {
                group = 'g7';
                icon = '<?php echo base_url("assets/img/black1.png"); ?>';
            }


            //*******************************


            $.goMap.createMarker({
                latitude: lt,
                longitude: lg,
                group: group,
                icon: icon,
                html: {
                    content: "<b>Outlet name:</b> " + outlet_name +
                        "</br><b>Zone:</b> " + outlet_zone +
                        "</br><b>State:</b> " + outlet_state +
                        "</br><b>Adress:</b> " + outlet_adress +
                        "</br></br><b>More details:</b> <a class='btn btn-xs red filter-submit margin-bottom' href='view/" + outlet_id + "' data-toggle='tooltip' data-placement='top' title='More details' target='_blank'><i class='icon-map'></i></a>",
                    //popup: true
                }


            });
            hideByClick: true
            <?php } ?>

        });
        $("button").click(function () {
            //alert($(this).attr('id'));
            group = $(this).attr('id');
            if (group == 'all')
                for (var i in $.goMap.markers) {
                    $.goMap.showHideMarker($.goMap.markers[i], true);
                }
            else {
                for (var i in $.goMap.markers) {
                    $.goMap.showHideMarker($.goMap.markers[i], false);
                }

                $.goMap.showHideMarkerByGroup(group, true);
            }
        });
        $("#count1").click(function () {
            $("#my_chartdiv").goMap();
            alert($.goMap.getMarkerCount());
        });
    });

</script>

<?php include('footer.php'); ?>