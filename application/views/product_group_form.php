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
                <?php echo form_open('product_groups/form/' . $id, $attributes); ?>

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
                        <label class="control-label col-md-3">Brand <span class="required">* </span> </label>
                        <div class="col-md-6">
                            <?php
                            $brand_ids = array();
                            foreach ($brands as $brand) {
                                $brand_ids[$brand->id] = $brand->name;
                            }
                            ?>
                            <?php echo form_dropdown('brand_id', $brand_ids, set_value('brand_id', $brand_id), 'class="form-control"'); ?>
                        </div>
                    </div>




                    <div class="form-group">
                        <label class="control-label col-md-3">Clustering <span class="required">* </span> </label>
                        <div class="col-md-6">
                            <?php
                            $cluster_ids = array();
                            foreach ($clusters as $cluster) {
                                $cluster_ids[$cluster->id] = $cluster->name;
                            }
                            ?>
                            <?php echo form_dropdown('cluster_id', $cluster_ids, set_value('cluster_id', $cluster_id), 'class="form-control"'); ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3">Metrage <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'metrage', 'value' => set_value('metrage', $metrage), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3">Shelf unit<span class="required">* </span> </label>
                        <div class="col-md-6">
                            <?php
                            $shelf_units = array();
                            $shelf_units['shelf'] = "shelf";
                            $shelf_units['metrage'] = "metrage";
                            ?>
                            <?php echo form_dropdown('shelf_unit', $shelf_units, set_value('shelf_unit', $shelf_unit), 'class="form-control"'); ?>
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