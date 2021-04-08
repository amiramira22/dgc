<?php
include ('header.php');
//bcm
?>


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box red">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-search"></i>Search
                </div>

            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal'); ?>
                <?php echo form_open('products/search/', $attributes) ?>
                <div class="form-body">



                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Search </label>

                            <input type="text" name="search" class="form-control" placeholder="Search">
                        </div>




                    </div>

                </div> <!--  end form-body-->
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <input class="btn red" type="submit" value="Search"/>
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


<!-- Datatable-->


<div class="row">
    <div class="col-md-12">									 

        <div class="btn-group pull-right">
            <a class="btn red" href="<?php echo site_url('products/form'); ?>">
                <i class="fa fa-plus"></i> Add New
            </a>
        </div>

    </div>
</div>
<br>

<div class="row">
    <div class="col-md-12">

        <table class="table table-striped table-bordered table-hover " width="100%" >
            <thead>

                <tr>	
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Product.Group</th>
                    <th>Custering</th>
                    <th>Sub.category</th>
                    <th>Category</th>
                    <th>NB.SKU</th>
                    <th class="col-md-2">Actions</th>
                </tr>
            </thead>

            <tbody>




                <?php foreach ($products as $product): ?>
                    <tr class="odd gradeX">
                        <td>
                            <?php if ($product->image != ''): ?>
                                <img src="<?php echo base_url('uploads/product/' . $product->image); ?>" height="50" width="50" alt="current"/>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $product->code; ?></td>
                        <td><?php echo $product->name; ?></td>
                        <td><?php echo $product->brand_name; ?></td>
                        <td><?php echo $product->product_group_name; ?></td>
                        <td><?php echo $product->cluster_name; ?></td>
                        <td><?php echo $product->sub_category_name; ?></td>
                        <td><?php echo $product->category_name; ?></td>
                        <td><?php echo $product->nb_sku; ?></td>

                        <td class="col-md-2">
                            <a class="btn btn-sm yellow filter-submit margin-bottom" href="<?php echo site_url('products/form/' . $product->id); ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-square-o"></i> </a>	

                            <?php if ($this->auth->check_access('Admin') ) { ?>
                                <a class="btn btn-sm red filter-submit margin-bottom" href="<?php echo site_url('products/delete/' . $product->id); ?>" onclick="return confirm('Are you sure you want to delete this  product ?');" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a>
                            <?php } ?>
                            <?php if ($product->active == 1) { ?>
                                <a class="btn btn-sm green filter-submit margin-bottom"   href="<?php echo site_url('products/desactivate/' . $product->id); ?>" data-toggle="tooltip" data-placement="top" title="Activate"><i class="glyphicon glyphicon-thumbs-up"></i></a>
                            <?php } else {
                                ?>
                                <a class="btn btn-sm orange filter-submit margin-bottom"   href="<?php echo site_url('products/activate/' . $product->id); ?>" data-toggle="tooltip" data-placement="top" title="Desactivate"><i class="glyphicon glyphicon-thumbs-down"></i></a>
                                <?php } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>



<?php
include ('footer.php');
?>