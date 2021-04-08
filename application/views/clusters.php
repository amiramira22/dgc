<?php
include ('header.php');
?>



<!-- Datatable-->


<div class="row">
    <div class="col-md-12">									 

        <div class="btn-group pull-right">
            <a class="btn red" href="<?php echo site_url('clusters/form'); ?>">
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
                <th>Sub category</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>

                <?php foreach ($clusters as $cluster): ?>
                    <tr class="odd gradeX">

                        <td><?php echo $cluster->code; ?></td>
                        <td class="gc_cell_left"><?php echo $cluster->name; ?></td>
                        <td><?php echo $this->Sub_category_model->get_sub_category_name($cluster->sub_category_id); ?></td>

                        <td>
                            <a class="btn btn-sm yellow filter-submit margin-bottom" href="<?php echo site_url('clusters/form/' . $cluster->id); ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-square-o"></i> </a>	
                            <?php if ($this->auth->check_access('Admin') && ($full_name == "Boulbaba Zouaoua" || $full_name == "Mohamed Ali Gassara")) { ?>
                                <a class="btn btn-sm red filter-submit margin-bottom" href="<?php echo site_url('clusters/delete/' . $cluster->id); ?>" onclick="return confirm('Are you sure you want to delete this  categories?');" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a>
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