<?php
include('header.php');
//bcm
?>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="fa fa-plus font-red"></i>
                    <span class="caption-subject bold uppercase font-red"> ADD FILE</span>
                    <span class="caption-helper"></span>
                </div>
            </div>

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal'); ?>
                <?php echo form_open_multipart('upload/form/' . $id, $attributes); ?>

                <div class="form-body">

                    <div class="form-group">
                        <label class="control-label col-md-3">Name <span class="required">* </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'name', 'value' => set_value('name', $name), 'required class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Version <span class="required"> * </span></label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'version', 'value' => set_value('version', $version), 'required class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">FILE<span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $f_file = array('name' => 'file', 'id' => 'file', 'required');
                            echo form_upload($f_file);
                            ?>

                            <?php if ($id && $file != ''): ?>
                                <div style="text-align:center; padding:5px; border:1px solid #ccc;"><img src="<?php echo base_url('uploads/product/' . $file); ?>" alt="current"/><br/>Current picture</div>
                            <?php endif; ?>

                        </div> 
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