<?php include('header.php'); ?>	

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box red">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i><?php echo $page_title; ?>
                </div>

            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal'); ?>
                <?php echo form_open_multipart('users/form/' . $id, $attributes) ?>
                <div class="form-body">


                    <div class="form-group">
                        <label class="control-label col-md-3">Username <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'username', 'value' => set_value('username', $username), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3">Fullname <span class="required">
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
                        <label class="control-label col-md-3">States <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'states', 'value' => set_value('states', $states), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="control-label col-md-3">E-mail <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'email', 'value' => set_value('email', $email_user), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>


                    <div class="form-group">


                        <label class="control-label col-md-3">Role <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">

                            <?php
                            $options = array('Admin' => 'Admin',
                                'Field Officer' => 'Field Officer',
                                'Henkel' => 'Slama',
                                'Responsible' => 'Responsible'
                            );
                            echo form_dropdown('access', $options, set_value('access', $access_user), "class='form-control'");
                            ?>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Password <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'password', 'class' => 'form-control');
                            echo form_password($data);
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Confirm password <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'confirm', 'class' => 'form-control');
                            echo form_password($data);
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
                                <div style="text-align:center; padding:5px; border:1px solid #ccc;"><img src="<?php echo base_url('uploads/users/' . $photos); ?>" alt="current" style="width:510px;"/><br/>Current picture</div>
                            <?php endif; ?>

                        </div> 






                    </div>

                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <input class="btn red" type="submit" value="Save"/>
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






<?php include('footer.php'); ?>