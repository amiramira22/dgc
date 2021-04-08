<?php include('header.php'); 
//bcm
?>

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
                <?php echo form_open_multipart('products/form/' . $id, $attributes); ?>

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
                        <label class="control-label col-md-3">Code gemo <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'code_gemo', 'value' => set_value('code_gemo', $code_gemo), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3">Code MG <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'code_mg', 'value' => set_value('code_mg', $code_mg), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>




                    <div class="form-group">
                        <label class="control-label col-md-3">Code UHD <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'code_uhd', 'value' => set_value('code_uhd', $code_uhd), 'class' => 'form-control');
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
                        <label class="control-label col-md-3">Product group<span class="required">* </span> </label>
                        <div class="col-md-6">
                            <?php
                            $product_group_ids = array();
                            foreach ($product_groups as $product_group) {
                                $product_group_ids[$product_group->id] = $product_group->name;
                            }
                            ?>
                            <?php echo form_dropdown('product_group_id', $product_group_ids, set_value('product_group_id', $product_group_id), 'class="form-control"'); ?>
                        </div>
                    </div>

                    


                    <div class="form-group">
                        <label class="control-label col-md-3">Number of SKU<span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'nb_sku', 'value' => set_value('nb_sku', $nb_sku), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Picture<span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $f_image = array('name' => 'image', 'id' => 'image');
                            echo form_upload($f_image);
                            ?>

                            <?php if ($id && $image != ''): ?>
                                <div style="text-align:center; padding:5px; border:1px solid #ccc;"><img src="<?php echo base_url('uploads/product/' . $image); ?>" alt="current"/><br/>Current picture</div>
                            <?php endif; ?>

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