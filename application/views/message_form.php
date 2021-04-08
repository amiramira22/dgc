<?php include('header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i><?php echo $sub_title; ?>
                </div>

            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal'); ?>
                <?php echo form_open('messages/form/' . $id, $attributes); ?>

                <div class="form-body">



                    <div class="form-group">
                        <label class="control-label col-md-3">From <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php echo $this->Admin_model->get_admin_name($sender_id); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">To</label>
                        <div class="col-md-6">
                            <select multiple="multiple" class="multi-select" id="my_multi_select1" name="receiver_ids[]">
                                <option  value="0" >All Fo</option>
                                <?php
                                foreach ($admins as $admin) {
                                    ?>
                                    <option  value="<?php echo $admin->id ?>" ><?php echo $admin->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>




                    <div class="form-group">
                        <label class="control-label col-md-3">Message <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'message', 'value' => set_value('message', $message), 'class' => 'span3 form-control');
                            echo form_textarea($data);
                            ?>
                        </div>
                    </div>










                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-5 col-md-9">
                            <input class="btn blue" type="submit" value="Save"/>
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
</div>
<?php
include ('footer.php');
?>