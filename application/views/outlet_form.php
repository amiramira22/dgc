<?php include('header.php'); ?>
<link href="<?php echo base_url('assets/css/multiselect_bootstrap.css'); ?>" rel="stylesheet" type="text/css" />



<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet light">

            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-magnifier font-red"></i>
                    <span class="caption-subject bold uppercase"><?php echo $sub_title; ?></span>
                    <span class="caption-helper">outlets</span>
                </div>
            </div> <!-- end portlet-title -->

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal'); ?>
                <?php echo form_open_multipart('outlets/form/' . $id, $attributes); ?>

                <div class="form-body">


                    <div class="form-group">
                        <label class="control-label col-md-3">Code <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'code', 'value' => set_value('code', $code), 'class' => 'span3 form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3">Name <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'name', 'value' => set_value('name', $name), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3">Admin <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $admin_ids = array();

                            foreach ($admins as $ad) {
                                $admin_ids[$ad->id] = $ad->name;
                            }
                            ?>
                            <?php echo form_dropdown('sfo_id', $admin_ids, set_value('sfo_id', $sfo_id), 'class="form-control"'); ?>
                        </div>
                    </div>	

                    <div class="form-group">
                        <label class="control-label col-md-3">Responsible <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $responsible_ids = array();
                            $responsible_ids[-1] = 'No one';
                            foreach ($responsibles as $res) {
                                $responsible_ids[$res->id] = $res->name;
                            }
                            ?>
                            <?php echo form_dropdown('responsible_id', $responsible_ids, set_value('responsible_id', $responsible_id), 'class="form-control"'); ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3">Zone <span class="required">* </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $zone_ids = array();
                            foreach ($zones as $zn) {
                                $zone_ids[$zn->id] = $zn->name;
                            }
                            ?>
                            <?php echo form_dropdown('zone', $zone_ids, set_value('zone', $zone), 'class="form-control"');
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Channel <span class="required">* </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $channel_ids = array();
                            foreach ($channels as $chl) {
                                $channel_ids[$chl->id] = $chl->name;
                            }
                            ?>
                            <?php echo form_dropdown('channel_id', $channel_ids, set_value('channel_id', $channel_id), 'class="form-control"');
                            ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3">State <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                           <?php
                            $state_ids = array();
                            foreach ($states as $st) {
                                $state_ids[$st->id] = $st->name;
                            }
                            ?>
                            <?php echo form_dropdown('state', $state_ids, set_value('state', $state), 'class="form-control"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Sub Channel <span class="required">* </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $sub_channel_ids = array();
                            foreach ($sub_channels as $sb) {
                                $sub_channel_ids[$sb->id] = $sb->name;
                            }
                            ?>
                            <?php echo form_dropdown('sub_channel_id', $sub_channel_ids, set_value('sub_channel_id', $sub_channel_id), 'class="form-control"');
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Visit Days <span class="required">
                                * </span>
                        </label>

                        <div class="col-md-6">

                            <select name="visit_days[]"  size="6" multiple="multiple" id="specific_visit_day" class="form-control col-md-6" style="width: 100%">


                                <?php
                                $days = array(
                                    'Monday' => 'Monday',
                                    'Tuesday' => 'Tuesday',
                                    'Wednesday' => 'Wednesday',
                                    'Thursday' => 'Thursday',
                                    'Friday' => 'Friday',
                                    'Saturday' => 'Saturday',
                                    'Sunday' => 'Sunday');
                                foreach ($days as $day) {
                                    ?>

                                    <?php
                                    if (is_array($visit_days) && in_array($day, $visit_days)) {
                                        $var = 'selected';
                                    } else {
                                        $var = "";
                                    }
                                    ?>

                                    <option style="width: 390px;"  <?php echo $var; ?> value="<?php echo $day ?>" ><?php echo $day ?></option>
                                <?php } ?>



                            </select>
                        </div>

                        <script>
                            $(document).ready(function () {

                                $('#specific_visit_day').multiselect({
                                    includeSelectAllOption: true,
                                    enableFiltering: true,

                                });



                            });

                        </script>
                    </div>



                    <div class="form-group">
                        <label class="control-label col-md-3">Delivery Day  <span class="required">
                                * </span>
                        </label>

                        <div class="col-md-6">

                            <select name="delivery_days[]"  size="6" multiple="multiple" id="specific_delivery_day" class="form-control col-md-6" style="width: 100%">


                                <?php
                                $days2 = array(
                                    'Monday' => 'Monday',
                                    'Tuesday' => 'Tuesday',
                                    'Wednesday ' => 'Wednesday',
                                    'Thursday' => 'Thursday',
                                    'Friday' => 'Friday',
                                    'Saturday' => 'Saturday');
                                foreach ($days2 as $day2) {
                                    if (is_array($delivery_days) && in_array($day2, $delivery_days)) {

                                        $var2 = 'selected';
                                    } else {
                                        $var2 = "";
                                    }
                                    ?>

                                    <option style="width: 390px;"  <?php echo $var2; ?> value="<?php echo $day2 ?>" ><?php echo $day2; ?></option>
                                <?php } ?>



                            </select>
                        </div>

                        <script>
                            $(document).ready(function () {

                                $('#specific_delivery_day').multiselect({
                                    includeSelectAllOption: true,
                                    enableFiltering: true,

                                });



                            });

                        </script>
                    </div>




                    <div class="form-group">
                        <label class="control-label col-md-3">Caisse Number<span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'caisse_number', 'value' => set_value('caisse_number', $caisse_number), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3">Contact PDV<span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'contact_pdv', 'value' => set_value('contact_pdv', $contact_pdv), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Contact<span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'contact', 'value' => set_value('contact', $contact), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3">Adress<span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'adress', 'value' => set_value('adress', $adress), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="control-label col-md-3">Longitude <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'longitude', 'value' => set_value('longitude', $longitude), 'class' => ' form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Latitude <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'latitude', 'value' => set_value('latitude', $latitude), 'class' => ' form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Week of work <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            echo form_dropdown('week_of_work', array(
                                '0' => 'All weeks',
                                '2' => 'Pair weeks',
                                '1' => 'Impaire weeks'
                                    ), set_value('week_of_work', $week_of_work), 'class="form-control"');
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Picture<span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $f_image = array('name' => 'photos', 'id' => 'image');
                            echo form_upload($f_image);
                            ?>

                            <?php if ($id && $photos != ''): ?>
                                <div style="text-align:center; padding:5px; border:1px solid #ccc;"><img src="<?php echo base_url('uploads/outlet/' . $photos); ?>" alt="current" style="width:510px;"/><br/>Current picture</div>
                            <?php endif; ?>

                        </div> 






                    </div>





                    <div class="form-group">
                        <label class="control-label col-md-3">Active <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'active', 'value' => 1, 'checked' => $active);
                            echo '&nbsp &nbsp' . form_checkbox($data) . ' ' . lang('active');
                            ?>

                        </div>
                    </div>


                    >

                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-5 col-md-9">
                            <input class="btn btn-circle red-mint btn-outline sbold uppercase" type="submit" value="Save"/>
                            <button type="button" class="btn btn-circle btn-default sbold uppercase">Cancel</button>
                        </div>
                    </div>
                </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
        <!-- END VALIDATION STATES-->
    </div>
</div>


<?php include('footer.php'); ?>





<script src="<?php echo base_url('assets/js/multiselect_bootstrap.js'); ?>" type="text/javascript"></script> 
