<?php
include ('header.php');

//bcm
?>

<style>
    .zoom img:hover {
        width:1500px;
        height:2000px;
    }
</style>

<script type="text/javascript">
    $(function () {
        $("#datepicker1").datepicker({dateFormat: 'yy-mm-dd'});
        $("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>

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
                <?php $attributes = array('class' => 'form-horizontal','autocomplete'=>'off'); ?>
                <?php echo form_open('reports/store_album/', $attributes) ?>

                <div class="form-body" >

                    <div class="row">

                        <div class="form-group">


                            <label class="control-label col-md-2">Field officer </label>
                            <div class="col-md-4">
                                <?php
                                $admin_ids = array();
                                $admin_ids[-1] = 'All Field Officer';
                                foreach ($admins as $ad) {
                                    $admin_ids[$ad->id] = $ad->name;
                                }
                                ?>
                                <?php echo form_dropdown('user_id', $admin_ids, $user_id, 'id= specific_admin class=form-control'); ?>
                            </div> <!-- end class="col-md-6"-->

                            <label class="control-label col-md-2">Zone </label>
                            <div class="col-md-4">
                                <?php
                                $zone_ids = array();
                                $zone_ids[-1] = 'All zones';
                                foreach ($zones as $z) {
                                    $zone_ids[$z->id] = $z->name;
                                }
                                echo form_dropdown('zone_id', $zone_ids, $zone_id, 'id=specific_zone class=form-control');
                                ?>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Outlet </label>
                            <div class="col-md-10">
                                <?php
                                $outlet_ids = array();
                                $outlet_ids[-1] = 'All outlets';
                                foreach ($outlets as $outlet) {
                                    $outlet_ids[$outlet->id] = $outlet->name;
                                }
                                echo form_dropdown('outlet_id', $outlet_ids, $outlet_id, 'id=specific_outlet class=form-control');
                                ?>
                            </div>
                        </div> <!--  end form-body-->


                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn btn-circle red-mint btn-outline sbold uppercase">Search</button>
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
    </div>
</div>
</br> 

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-picture-o"></i>Pictures Report 
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if (($outlet_id)) {
                    ?>              
                    <?php
                    foreach ($visits as $weekly_visit) {
                        if (($weekly_visit->one_pictures != '[]')) {
                            ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mt-element-list">
                                        <div class="mt-list-head list-simple ext-1 font-white bg-blue">
                                            <div class="list-head-title-container">
                                                <table >
                                                    <tr>  
                                                        <td><h3 class="list-title">Details</h3></td>
                                                        <td >
                                                            <div class="pull-right">
                                                                <a href="<?php echo site_url('reports/download_zip/' . $weekly_visit->id); ?>" class="btn red "><i class="fa fa-download"></i> </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="mt-list-container list-simple ">
                                            <ul>
                                                <li class="mt-list-item">
                                                    <i class="icon-check"></i>
                                                    <strong> Field Officer</strong>:&nbsp;<?php echo $weekly_visit->admin_name; ?></p>
                                                </li>
                                                <li class="mt-list-item">
                                                    <i class="icon-check"></i>
                                                    <strong> Outlet</strong>:&nbsp;<?php echo $weekly_visit->outlet_name; ?>
                                                </li>
                                                <li class="mt-list-item">
                                                    <i class="icon-check"></i>
                                                    <strong> Zone</strong>:&nbsp;<?php echo $weekly_visit->zone; ?>
                                                </li>
                                                <li class="mt-list-item">
                                                    <i class="icon-check"></i>
                                                    <strong>Date</strong>:&nbsp;<?php echo ($weekly_visit->date); ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9" style="border-color: #ff0000;">
                                    <?php if (($weekly_visit->one_pictures != '') && ($weekly_visit->one_pictures != '[]')) { ?>  
                                        <div id="myCarousel3<?php echo $weekly_visit->id ?>" class="carousel slide" data-ride="carousel">

                                            <?php
                                            $one_pictures = json_decode($weekly_visit->one_pictures, TRUE);
                                            ?>
                                            <ol class="carousel-indicators">
                                                <li data-target="#myCarousel3<?php echo $weekly_visit->id ?>" data-slide-to="0" class="active"></li>

                                                <?php for ($i = 0; $i < sizeof($one_pictures); $i++) { ?>
                                                    <li data-target="#myCarousel3<?php echo $weekly_visit->id ?>" data-slide-to="$i"></li>
                                                <?php } ?>
                                            </ol>

                                            <!-- Wrapper for slides -->
                                            <div class="carousel-inner" role="listbox">


                                                <div class="item active">
                                                    <center>
                                                        <img src="<?php echo base_url('uploads/outlet/' . $weekly_visit->outlet_picture); ?>"  alt="admin-pic" download  width="800px" height="400px">
                                                    </center>
                                                </div>

                                                <?php for ($i = 0; $i < sizeof($one_pictures); $i++) { ?>
                                                    <div class="item">
                                                        <center>
                                                            <img src="<?php echo base_url('uploads/branding/' . $one_pictures[$i]); ?> " alt="admin-pic" download  width="500px" height="400px">
                                                        </center>  
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <!-- Left and right controls -->
                                            <a class="left carousel-control" href="#myCarousel3<?php echo $weekly_visit->id ?>" role="button" data-slide="prev">
                                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="right carousel-control" href="#myCarousel3<?php echo $weekly_visit->id ?>" role="button" data-slide="next">
                                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <!-- end Left and right controls -->
                                </div>
                            </div>
                            <hr>
                            <?php
                        }
                    }
                } else {
                    ?>
                    <div class="alert alert-danger">
                        <center><strong>Sorry! No data available...</strong> </center>
                    </div>

                <?php } ?>
            </div>
        </div>
    </div>
</div>


<?php
include('footer.php');
?>


<script type="text/javascript" language="javascript">
    $(document).ready(function () {
        $('#specific_admin,#specific_zone').change(function () {
            $("#specific_outlet > option").remove();
            //first of all clear select items
            var admin_id = $('#specific_admin').val();
            var zone_id = $('#specific_zone').val();

            // here we are taking country id of the selected one.
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('reports/get_outlet_by_zone_fo'); ?>",
                data: {zone_id: zone_id, admin_id: admin_id},
                success: function (data)
                {
                    $.each(data, function (id, out)
                    {
                        var opt = $('<option />');
                        opt.val(id);
                        opt.text(out);
                        $('#specific_outlet').append(opt);
                    });
                }
            });

        });

    });
</script>
