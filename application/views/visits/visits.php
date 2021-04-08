<?php
//bcm
$admin = $this->session->userdata('admin');

$admin_id = $admin['id'];
$full_name = $admin['name'];
$access = $admin['access'];
?>

<style>
    td{
        text-align: center;

    }

    th{

        text-align: center;

    }
    @media (max-width: 767px) {
        .table-responsive .dropdown-menu {
            position: relative; /* Sometimes needs !important */
        }
    }
</style>
<style>

    .btn {
        padding: 0.1rem 0.7rem !important;
    }

    .blink_me {
        -webkit-animation-name: blinker;
        -webkit-animation-duration: 1s;
        -webkit-animation-timing-function: linear;
        -webkit-animation-iteration-count: infinite;

        -moz-animation-name: blinker;
        -moz-animation-duration: 1s;
        -moz-animation-timing-function: linear;
        -moz-animation-iteration-count: infinite;

        animation-name: blinker;
        animation-duration: 1s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
    }

    @-moz-keyframes blinker {  
        0% { opacity: 1.0; }
        50% { opacity: 0.0; }
        100% { opacity: 1.0; }
    }

    @-webkit-keyframes blinker {  
        0% { opacity: 1.0; }
        50% { opacity: 0.0; }
        100% { opacity: 1.0; }
    }

    @keyframes blinker {  
        0% { opacity: 1.0; }
        50% { opacity: 0.0; }
        100% { opacity: 1.0; }
    }

</style>



<script type="text/javascript">
    $(function () {
        $("#datepicker1").datepicker({dateFormat: 'yy-mm-dd'});
        $("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>

<?php if ($this->auth->check_access('Admin') || ($this->auth->check_access('Henkel') )) { ?>



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
                    <?php echo form_open('visits/search/', $attributes) ?>
                    <div class="form-body">

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
                            <label class="control-label col-md-2">Search </label>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search">
                            </div>



                            <label class="control-label col-md-2">Field officer </label>
                            <div class="col-md-4">
                                <?php
                                $admin_ids = array();
                                foreach ($admins as $ad) {
                                    $admin_ids[-1] = 'All Field Officer';
                                    $admin_ids[$ad->id] = $ad->name;
                                }
                                ?>
                                <?php echo form_dropdown('user_id', $admin_ids, $user_id, 'class="form-control"'); ?>
                            </div> <!-- end class="col-md-6"-->



                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Visit type </label>
                            <div class="col-md-10">
                                <?php
                                $visits_ids = array();
                                $visits_ids[-1] = "All visits";
                                $visits_ids[0] = 'Stock issues';
                                $visits_ids[1] = 'Shelf share';
                                $visits_ids[2] = 'Price';
                                ?>
                                <?php echo form_dropdown('visit_type', $visits_ids, $search_type, 'class="form-control"'); ?>
                            </div> 
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

<?php } ?>


<?php if ($this->auth->check_access('Admin') || ($this->auth->check_access('Field Officer') )) { ?>

    <div class="row">
        <div class="col-md-12">									 
            <div class="btn-group pull-right">
                <a class="btn btn-circle red-mint btn-outline sbold uppercase" href="<?php echo site_url('visits/form'); ?>">
                    <i class="fa fa-plus"></i> Add New
                </a>
            </div>
        </div>
    </div>

    </br> 
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i><?php echo $sub_title; ?>
                </div>

            </div>
            <div class="portlet-body">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" width="100%" >
                        <thead>

                            <tr>

                                <th>Field Officer</th>
                                <th>Outlet</th>
                                <th>State</th>
                                <th>Date</th>
                                <th>Entry time</th>
                                <th>Exit time</th>
                                <th>OOS %</th>
                                <th>Branding</th>
                                <th>Order</th>
                                <th>Remark</th>
                                <th>Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($visits as $visit) { ?>

                                <tr>
                                    <td><?php echo $visit->name; ?></td>
                                    <td>
                                        <?php if (($visit->monthly_visit == 1) || ($visit->monthly_visit == 3)) { ?>
                                            <a  target="_blank" href="<?php echo site_url('visits/position/' . $visit->id); ?>">
                                                <font color="red"><?php echo $visit->outlet_name; ?></font> </a>
                                        <?php } else { ?> <a  target="_blank" href="<?php echo site_url('visits/position/' . $visit->id); ?>"><?php echo $visit->outlet_name; ?> </a>
                                        <?php } ?>
                                    </td>
                                 
                                    <td><?php echo $visit->outlet_state; ?></td>
                                    <?php if (($this->auth->check_access('Admin')) && ($visit->date != $visit->created_date)) { ?>
                                        <td><span class='blink_me' style='color: #F03434'><?php echo reverse_format($visit->date); ?></span>
                                            <span title="<?php echo 'created_date on ' . reverse_format($visit->created_date); ?>"<i class="fa fa-info-circle"></i></span></td>

                                    <?php } else { ?>
                                        <td><?php echo reverse_format($visit->date); ?></td>

                                    <?php }
                                    ?> 

                                    <?php if (($this->auth->check_access('Admin')) && ($visit->was_there == 0)) { ?>
                                        <td><span class='blink_me' style='color: #F03434'><?php echo substr($visit->entry_time, 0, -3); ?></span>
                                            <a style='color: #F03434' target="_blank" href="<?php echo site_url('visits/position/' . $visit->id); ?>" ><i class="fa fa-map-marker"></i></a></td>

                                    <?php } else { ?>
                                        <td><?php echo substr($visit->entry_time, 0, -3); ?></td>

                                    <?php }
                                    ?> 
                                    <?php
                                    $outlet = $this->Outlet_model->get_outlet($visit->outlet_id);
                                    $exit_was_there = was_there($outlet->latitude, $outlet->longitude, $visit->exit_latitude, $visit->exit_longitude);

                                    if (($this->auth->check_access('Admin')) && ($visit->exit_latitude != '') && ($exit_was_there == 0)) {
                                        ?>
                                        <td><span class='blink_me' style='color: #F03434'><?php echo substr($visit->exit_time, 0, -3); ?></span>
                                            <a style='color: #F03434' target="_blank" href="<?php echo site_url('visits/position/' . $visit->id); ?>" >
                                                <i class="fa fa-map-marker"></i>
                                            </a>
                                            <span title="<?php
                                            $Time = strtotime($visit->created_time);
                                            echo 'server_time  ' . date('H:i:s', $Time);
                                            echo "\n";
                                            ?>">
                                                <i class="fa fa-clock-o"></i>
                                            </span>
                                        </td>

                                    <?php } else { ?>

                                        <td>
                                            <?php echo substr($visit->exit_time, 0, -3); ?>

                                            <span title="<?php
                                            $Time = strtotime($visit->created_time);
                                            echo 'server_time  ' . date('H:i:s', $Time);
                                            echo "\n";
                                            ?>">
                                                <i class="fa fa-clock-o"></i>
                                            </span>
                                        </td>
                                    <?php } ?>

                                    <?php if ($visit->monthly_visit == 0) { ?>
                                        <td><?php echo number_format($visit->oos_perc, 2, ',', ' ') . '%'; ?></td>
                                    <?php } else { ?>

                                        <td>-</td>
                                    <?php } ?>
                                        <td><?php  echo substr_count($visit->branding_pictures, ".jpg")/2; ?></td>
                                        <td><?php  
                                        
                                        $count=substr_count($visit->order_picture, ".jpg"); 
                                        if($count==0){
                                            echo '';
                                        }else{
                                           ?> 
                                            <a href="<?php echo site_url('visits/order_report/' . $visit->id); ?>"><i class="fa fa-eye"> </i> </a>
                                            <?php 
                                        }
                                        ?></td>

                                    <?php if ($this->auth->check_access('Admin')) { ?>
                                        <td>
                                            <?php
                                            if ($visit->remark != null) {
                                                ?><img src="<?php echo base_url('assets/img/remark.png'); ?>" title="<?php echo $visit->remark; ?>" width="25px"> 

                                            <?php } ?>

                                        </td>
                                    <?php } ?>

                                    <td>
                                        <?php if ($this->auth->check_access('Admin')) { ?>
                                            <div class="dropdown">
                                                <button class="btn red btn-outline dropdown-toggle" type="button" data-toggle="dropdown">Actions
                                                    <span class="caret"></span>
                                                </button>

                                                <ul class="dropdown-menu" role="menu">

                                                    <li><a href="<?php echo site_url('visits/models/' . $visit->id); ?>"><i class="fa fa-list"> </i> Model Data</a></li>
                                                    <li><a href="<?php echo site_url('visits/report/' . $visit->id); ?>"><i class="fa fa-line-chart"> </i> Report</a></li>
                                                    <li><a href="<?php echo site_url('visits/form/' . $visit->id); ?>"><i class="fa fa-pencil"> </i>Edit </a> </li>
                                                    <li><a href="<?php echo site_url('visits/delete/' . $visit->id); ?>"onclick="return confirm('Are you sure you want to delete this visit?')"><i class="fa fa-trash-o"> </i> Delete</a></li>          

                                                    <?php if ($this->auth->check_access('Admin') && ($full_name == "Boulbaba Zouaoua" || $full_name == "Mohamed Ali Gassara")) { ?>
                                                        <li><a href="<?php echo site_url('visits/copy/' . $visit->id); ?>"><i class="fa fa-copy"> </i> Copy</a></li>
                                                    <?php } ?>
                                                </ul>


                                            </div>
                                        <?php } ?>
                                        <?php if ($this->auth->check_access('Henkel')) { ?>

                                            <a  class="btn btn-primary" href="<?php echo site_url('visits/report/' . $visit->id); ?>"><i class="fa fa-line-chart"> </i>Report </a>

                                        <?php } ?>

                                        <?php //if ($this->auth->check_access('')) {      ?>
                                        <!-- 
                                        <div class="btn-group btn-group-sm btn-group-solid">
                                            <a  class="btn btn-default" href="<?php echo site_url('visits/form/' . $visit->id); ?>"><i class="fa fa-pencil"> </i>Edit </a>
                                            <a class="btn btn-primary" href="<?php echo site_url('visits/models/' . $visit->id); ?>"><i class="fa fa-list"></i>Model data</a>
                                            <a class="btn btn-success"  href="<?php echo site_url('visits/pictures/' . $visit->id); ?>"><i class="fa fa-file-photo-o"> </i> Pictures</a></li>
                                            <a class="btn btn-danger" href="<?php echo site_url($this->config->item('admin_folder') . '/visits/delete/' . $visit->id); ?> " onclick="return confirm('Are you sure you want to delete this visit?')"><i class="fa fa-trash-o"> </i>Delete</a>
                                        </div> -->
                                        <?php //}      ?>

                                    </td>
                                </tr>



                            <?php } ?> 

                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-12 text-center">
        <?php echo $pagination; ?>
    </div>
</div>


<script type="text/javascript">

    initGeolocation();

    function initGeolocation()
    {
        if (navigator.geolocation)
        {
            // Call getCurrentPosition with success and failure callbacks
            navigator.geolocation.getCurrentPosition(success, fail);




        } else
        {
            alert("Sorry, your browser does not support geolocation services.");
        }

    }

    function success(position)
    {


        document.getElementById('lng').value = position.coords.longitude;
        document.getElementById('lat').value = position.coords.latitude;

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('geo/pos'); ?>",

            data: {lat: position.coords.latitude, lng: position.coords.longitude},
        });

    }

    function fail()
    {
    }

</script> 

<INPUT TYPE="text" NAME="lng" ID="lng" VALUE="" hidden>

<INPUT TYPE="text" NAME="lat" ID="lat" VALUE="" hidden>   


<script>

</script>
