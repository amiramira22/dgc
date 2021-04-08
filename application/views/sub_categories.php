<?php
include ('header.php');
?>



<!-- Datatable-->


<div class="row">
    <div class="col-md-12">									 

        <div class="btn-group pull-right">
            <a class="btn red" href="<?php echo site_url('sub_categories/form'); ?>">
                <i class="fa fa-plus"></i> Add New
            </a>
        </div>

    </div>
</div>
<br>

<div class="row">
    <div class="col-md-12">

        <table class="table table-striped table-bordered table-hover " width="100%" >
            </thead>

            <tr>	
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>




                <?php foreach ($sub_categories as $sub_category): ?>
                    <tr class="odd gradeX">

                        <td><?php echo $sub_category->code; ?></td>
                        <td><?php echo $sub_category->name; ?></td>
                        <td><?php echo $this->Category_model->get_category_name($sub_category->category_id); ?></td>

                        <td>
                            <a class="btn btn-sm yellow filter-submit margin-bottom" href="<?php echo site_url('sub_categories/form/' . $sub_category->id); ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-square-o"></i> </a>	
                            <?php if ($this->auth->check_access('Admin') && ($full_name == "Boulbaba Zouaoua" || $full_name == "Mohamed Ali Gassara")) { ?>
                                <a class="btn btn-sm red filter-submit margin-bottom" href="<?php echo site_url('sub_categories/delete/' . $sub_category->id); ?>" onclick="return confirm('Are you sure you want to delete this  categories?');" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-center">
        <?php echo $pagination; ?>
    </div>
</div>

<?php
include ('footer.php');
?>