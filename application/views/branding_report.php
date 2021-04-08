<?php
include ('header.php');

//bcm
?>
<style>
   .zoom:hover {
        transform: scale(1.8); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
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
                <?php $attributes = array('class' => 'form-horizontal', 'autocomplete' => 'off'); ?>
                <?php echo form_open('reports/branding/', $attributes) ?>
                <div class="form-body">
                    <div class="row">

                        <div class="form-group">
                            <label class="control-label col-md-2">Start date </label>
                            <div class="col-md-4">
                                <input type="text" name="start_date" 
                                       value="<?php echo $start_date; ?>" 
                                       id="datepicker1" 
                                       class="form-control" />		
                            </div>

                            <label class="control-label col-md-2">End date </label>
                            <div class="col-md-4">
                                <input type="text" name="end_date" 
                                       value="<?php echo $end_date; ?>" 
                                       id="datepicker2" 
                                       class="form-control" />		
                            </div>
                        </div>

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
                                <?php echo form_dropdown('user_id', $admin_ids, set_value('user_id', $user_id), 'id= specific_admin class=form-control'); ?>
                            </div> <!-- end class="col-md-6"-->

                            <label class="control-label col-md-2">Zone </label>
                            <div class="col-md-4">
                                <?php
                                $zone_ids = array();
                                $zone_ids[-1] = 'All zones';
                                foreach ($zones as $z) {
                                    $zone_ids[$z->id] = $z->name;
                                }
                                echo form_dropdown('zone_id', $zone_ids, set_value('zone_id', $zone_id), 'id=specific_zone class=form-control');
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
                                echo form_dropdown('outlet_id', $outlet_ids, set_value('outlet_id', $outlet_id), 'id=specific_outlet class=form-control');
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
    </br> 
</div>


<?php
/*
  $outlets = array();
  $components = array();
  $count_outlet = 0;

  foreach ($visits as $row) {
  $outlet = $row->outlet_id;
  if (!in_array($outlet, $outlets)) {
  $outlets[] = $outlet;
  $count_outlet += 1;
  }

  // de 0 a 6
  $components[$outlet][$row->date] = array($row->id, $row->outlet_name, $row->zone, $row->outlet_picture, $row->admin_name, $row->branding_pictures, $row->one_pictures);
  }//

  foreach ($components as $outlet => $componentDates) {

  echo $outlet;
  echo '<br>';
  foreach ($componentDates as $key => $value) {
  echo $key;
  print_r($value);
  echo '<br>';
  echo '<br>';
  }
  }

 */
?>
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
                if (($start_date) && ($end_date)) {
                    ?>              
                    <?php
                    foreach ($visits as $weekly_visit) {

//                        print_r($weekly_visit);
//                        echo '<br>';
//                        echo '<br>';
//                        echo '<br>';

                        $outlet_id = $weekly_visit->outlet_id;
                        if ($weekly_visit->branding_pictures != '[]') {
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
                                    <div id="myCarousel<?php echo $weekly_visit->id ?>" class="carousel slide" data-ride="carousel" >
                                        <!-- Indicators -->
                                        <!-- begin Competitor picture -->
                                        <?php
                                        $branding_pictures = json_decode($weekly_visit->branding_pictures);
                                        $one_picture = json_decode($weekly_visit->one_pictures);
                                        ?>
                                        <ol class="carousel-indicators">
                                            <li data-target="#myCarousel<?php echo $weekly_visit->id ?>" data-slide-to="0" class="active"></li>
                                            <li data-target="#myCarousel_outlet" data-slide-to="0"></li>
                                            <?php
                                            $size_pictures = sizeof($branding_pictures);
                                            for ($i = 1; $i < $size_pictures; $i++) {
                                                ?>
                                                <li data-target="#myCarousel<?php echo $weekly_visit->id ?>" data-slide-to="$i"></li>
                                            <?php } ?>
                                        </ol>
                                        <!-- Wrapper for slides -->
                                        <div class="carousel-inner" role="listbox" >
                                            <div class="item active">
                                                <table>
                                                    <tr>
                                                        <td width="10%"></td>
                                                        <td width="100%"><span>
                                                                <img class="zoom" width="800px" height="400px" src="<?php echo base_url('uploads/outlet/' . $weekly_visit->outlet_picture); ?>" >
                                                            </span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="item ">
                                                <table>
                                                    <tr>
                                                        <td width="10%"></td>
                                                        <td width="50%"><span><img class="zoom" src="<?php echo base_url('uploads/branding/' . $branding_pictures[0][0]); ?> " width="400px" height="400px" alt="admin-pic" download></span></td>
                                                        <td width="50%"> <span><img class="zoom" src="<?php echo base_url('uploads/branding/' . $branding_pictures[0][1]); ?> " width="400px" height="400px" alt="admin-pic" download></span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <?php
                                            for ($i = 1; $i < $size_pictures; $i++) {
                                                ?>
                                                <div class="item">
                                                    <table>
                                                        <tr>
                                                            <td width="10%"></td>
                                                            <td width="50%"><span><img class="zoom" src="<?php echo base_url('uploads/branding/' . $branding_pictures[$i][0]); ?> " width="400px" height="400px" alt="admin-pic" download></span></td>
                                                            <td width="50%"> <span><img class="zoom" src="<?php echo base_url('uploads/branding/' . $branding_pictures[$i][1]); ?> " width="400px" height="400px" alt="admin-pic" download></span></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <!-- Left and right controls -->
                                        <a class="left carousel-control" href="#myCarousel<?php echo $weekly_visit->id ?>" role="button" data-slide="prev">
                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="right carousel-control" href="#myCarousel<?php echo $weekly_visit->id ?>" role="button" data-slide="next">
                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
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

