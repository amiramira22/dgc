<?php
include ('header.php');

//bcm
?>



<!-- Datatable-->


<div class="row">
    <div class="col-md-12">									 

        <div class="btn-group pull-right">
            <a class="btn red" href="<?php echo site_url('product_groups/form'); ?>">
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
                    <th>Code</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Cluster</th>
                    <th>Sub category</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>




                <?php foreach ($product_groups as $product_group): ?>
                    <tr class="odd gradeX">

                        <td><?php echo $product_group->code; ?></td>
                        <td><?php echo $product_group->name; ?></td>
                        <td><?php echo $product_group->brand_name; ?></td>
                        <td><?php echo $product_group->cluster_name; ?></td>
                        <td><?php echo $product_group->sub_category_name; ?></td>
                        <td><?php echo $product_group->category_name; ?></td>


                        <td>
                            <a class="btn btn-sm yellow filter-submit margin-bottom" href="<?php echo site_url('product_groups/form/' . $product_group->id); ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-square-o"></i> </a>	
                            <?php if ($this->auth->check_access('Admin')) { ?>
                                <a class="btn btn-sm red filter-submit margin-bottom" href="<?php echo site_url('product_groups/delete/' . $product_group->id); ?>" onclick="return confirm('Are you sure you want to delete this  categories?');" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a>
                                <?php } ?>
                                  <?php if ($product_group->active == 1) { ?>
                                <a class="btn btn-sm green filter-submit margin-bottom"   href="<?php echo site_url('product_groups/desactivate/' . $product_group->id); ?>" data-toggle="tooltip" data-placement="top" title="Activate"><i class="glyphicon glyphicon-thumbs-up"></i></a>
                            <?php } else {
                                ?>
                                
                                <a class="btn btn-sm orange filter-submit margin-bottom"   href="<?php echo site_url('product_groups/activate/' . $product_group->id); ?>" data-toggle="tooltip" data-placement="top" title="Desactivate"><i class="glyphicon glyphicon-thumbs-down"></i></a>
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