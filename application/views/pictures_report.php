<?php
include ('header.php');
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
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-search"></i>Search
                </div>

            </div>
            <div class="portlet-body form">

                <?php $attributes = array('class' => 'form-horizontal'); ?>
                <?php echo form_open('reports/picture_report/', $attributes) ?>
                <div class="form-body">
                    <div class="row">


                        <div class="col-md-6">
                            <label >Start date 
                            </label>

                            <input type="text" name="start_date" value="<?php echo $start_date; ?>" id="datepicker1" class="form-control" />		
                        </div>


                        <div class="col-md-6">

                            <label >End date 
                            </label>

                            <input type="text" name="end_date" value="<?php echo $end_date; ?>" id="datepicker2" class="form-control" />		
                        </div>





                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <label >Channel </label>
                            <?php
                            $channel_ids = array();
                            $channel_ids[-1] = 'All channels';
                            foreach ($channels as $channel) {
                                $channel_ids[$channel->name] = $channel->name;
                            }
                            echo form_dropdown('channel_id', $channel_ids, $channel_id, 'class="form-control"');
                            ?>
                        </div>



                        <div class="col-md-6">
                            <label >Zone </label>
                            <?php
                            $zone_ids = array();
                            $zone_ids[-1] = 'All zones';
                            foreach ($zones as $z) {
                                $zone_ids[$z->name] = $z->name;
                            }
                            echo form_dropdown('zone', $zone_ids, '', 'class="form-control"');
                            ?>
                        </div>
                    </div>
                    <div class="row">




                        <div class="col-md-12">
                            <label >Field officer </label>

                            <?php
                            $admin_ids = array();
                            $admin_ids[-1] = 'All Field Officer';

                            foreach ($admins as $ad) {
                                $admin_ids[$ad->id] = $ad->name;
                            }
                            ?>

                            <?php echo form_dropdown('user_id', $admin_ids, '', 'class="form-control"'); ?>
                        </div> <!-- end class="col-md-6"-->








                    </div>

                    <div class="row">
                        <label class="control-label col-md-2">Best of<span class="required">
                                * </span>
                        </label>
                        <div class="col-md-4">
                            <?php
                            $data = array('name' => 'best_of', 'value' => 1, 'checked' => $best_of);
                            echo '&nbsp &nbsp' . form_checkbox($data);
                            ?>

                        </div>



                    </div> <!--  end form-body-->



                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <input class="btn blue" type="submit" value="Search"/>
                                <button type="button" class="btn default">Cancel</button>
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


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>Pictures Report 
                </div>

            </div>
            <div class="portlet-body">


                <?php
                if (($start_date) && ($end_date) && ($best_of == 0)) {
                    ?>              



                    <?php
                    foreach ($visits as $weekly_visit) {






                        if (($best_of == 0) && ($weekly_visit->branding_pictures != '[]')) {
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
                                        $files = json_decode($weekly_visit->branding_pictures);
                                        $files2 = json_decode($weekly_visit->one_pictures);
                                        ?>


                                        <ol class="carousel-indicators">
                                            <li data-target="#myCarousel<?php echo $weekly_visit->id ?>" data-slide-to="0" class="active"></li>
                                            <li data-target="#myCarousel_outlet" data-slide-to="0"></li>
                                            <?php
                                            $size_pictures = sizeof($files) + sizeof($files2);
                                            for ($i = 1; $i < sizeof($size_pictures); $i++) {
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

                                                                <img width="800px" height="400px" src="<?php echo base_url('uploads/outlet/' . $weekly_visit->outlet_picture); ?>" >
                                                            </span></td>
                                                    </tr>
                                                </table>

                                            </div>
                                            <div class="item ">
                                                <table>


                                                    <tr>
                                                        <td width="10%"></td>
                                                        <td width="50%"><span><img src="<?php echo base_url('uploads/branding/' . $files[0][0]); ?> " width="400px" height="400px" alt="admin-pic" download></span></td>
                                                        <td width="50%"> <span><img src="<?php echo base_url('uploads/branding/' . $files[0][1]); ?> " width="400px" height="400px" alt="admin-pic" download></span></td>
                                                    </tr>
                                                </table>


                                            </div>




                                            <?php for ($i = 1; $i < sizeof($files); $i++) { ?>
                                                <div class="item">



                                                    <table>
                                                        <tr>
                                                            <td width="10%"></td>
                                                            <td width="50%"><span><img src="<?php echo base_url('uploads/branding/' . $files[$i][0]); ?> " width="400px" height="400px" alt="admin-pic" download></span></td>
                                                            <td width="50%"> <span><img src="<?php echo base_url('uploads/branding/' . $files[$i][1]); ?> " width="400px" height="400px" alt="admin-pic" download></span></td>
                                                        </tr>
                                                    </table>

                                                </div>
                                            <?php } ?>









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
                } else if (($start_date) && ($end_date) && ($best_of == 1)) {


                    if (($start_date) && ($end_date) && ($best_of == 1)) {
                        ?>              



                        <?php
                        foreach ($visits as $weekly_visit) {






                            if (($best_of == 1)) {
                                ?>





                                <div class="row">
                                    <div class="col-md-3">


                                        <div class="mt-element-list">
                                            <div class="mt-list-head list-simple ext-1 font-white bg-blue">
                                                <div class="list-head-title-container">
                                                    <h3 class="list-title">Details </h3>
                                                    <br>

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


                                                    <li class="mt-list-item">
                                                        <div class="">
                                                            <img width="250px" height="200px" src="<?php echo base_url('uploads/outlet/' . $weekly_visit->outlet_picture); ?>">
                                                        </div>
                                                    </li>


                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div id="myCarousel<?php echo $weekly_visit->id ?>" class="carousel slide" data-ride="carousel">
                                            <!-- Indicators -->
                                            <!-- begin Competitor picture -->

                                            <?php
                                            $branding_pictures = json_decode($weekly_visit->branding_pictures);
                                            ?>

                                            <div class="carousel-inner" role="listbox">

                                                <div class="item active">
                                                    <table>


                                                        <tr>
                                                            <td width="50%"><span><img src="<?php echo base_url('uploads/branding/' . $branding_pictures[0][0]); ?> " width="400px" height="400px" alt="admin-pic" download></span></td>
                                                            <td width="50%"> <span><img src="<?php echo base_url('uploads/branding/' . $branding_pictures[0][1]); ?> " width="400px" height="400px" alt="admin-pic" download></span></td>
                                                        </tr>
                                                    </table>


                                                </div>

                                                <?php for ($i = 1; $i < sizeof($branding_pictures); $i++) { ?>
                                                    <div class="item">

                                                        <table>
                                                            <tr>
                                                                <td width="50%"><span><img src="<?php echo base_url('uploads/branding/' . $branding_pictures[$i][0]); ?> " width="400px" height="400px" alt="admin-pic" download></span></td>
                                                                <td width="50%"> <span><img src="<?php echo base_url('uploads/branding/' . $branding_pictures[$i][1]); ?> " width="400px" height="400px" alt="admin-pic" download></span></td>
                                                            </tr>
                                                        </table>

                                                    </div>
                                                <?php } ?>

                                            </div>

                                            <ol class="carousel-indicators">
                                                <li data-target="#myCarousel<?php echo $weekly_visit->id ?>" data-slide-to="0" class="active"></li>


                                            </ol>

                                            <!-- Wrapper for slides -->


                                            <div class="carousel-inner" role="listbox">


                                                <div class="item active">



                                                </div>











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
                    }
                    ?>













                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>



<?php } else { ?>
    <div class="note note-danger">
        <span class="label label-danger">NOTE!</span>
        <span class="bold">No data .</span> </div>
    <?php } ?>

<?php
include('footer.php');
?>
