<?php include('header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box red">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i><?php echo $sub_title; ?>
                </div>

            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal'); ?>
                <?php echo form_open('clusters/form/' . $id, $attributes); ?>

                <div class="form-body">


                    <div class="form-group">
                        <label class="control-label col-md-3">Code <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'code', 'value' => set_value('code', $code), 'class' => 'form-control');
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
                        <label class="control-label col-md-3">Sub Category <span class="required">* </span> </label>
                        <div class="col-md-6">
                            <?php
                            $sub_category_ids = array();
                            foreach ($sub_categories as $sub_cat) {
                                $sub_category_ids[$sub_cat->id] = $sub_cat->name;
                            }
                            ?>
                            <?php echo form_dropdown('sub_category_id', $sub_category_ids, set_value('sub_category_id', $sub_category_id), 'class="form-control"'); ?>
                        </div>
                    </div>







                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-5 col-md-9">
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