<?php
$admin = $this->session->userdata('admin');
$full_name = $admin['name'];
?>
<style>
    td{
        text-align: center !important;
        width: 125px !important;
    }

    th{

        text-align: center !important;
        width: 125px !important;
    }
</style>
<div class="row">
    <div class="col-md-12">	
        <?php if ($full_name == "Boulbaba Zouaoua") { ?>
            <div class="row">
                <div class="col-md-12">									 
                    <div class="btn-group pull-right">
                        <a class="btn btn-circle red-mint btn-outline sbold uppercase" href="<?php echo site_url('upload/form'); ?>">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<br>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    LIST OF ANDROID APP
                </div>

            </div>
            <div class="portlet-body">

                <div class="table-responsive">

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Version</th>
                                <th>active</th>

                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($files as $file): ?>
                                <tr class="odd gradeX">


                                    <td><?php echo $file->name; ?></td>
                                    <td><?php echo $file->version; ?></td>
                            <style>.font-blue {
                                    color: #5558c5 !important;
                                }</style>
                                <?php if ($file->active == 1) { ?>
                                <td>  <i class="fa fa-check font-blue"></i></td>
                            <?php } else { ?>
                                <td>   <i class="fa fa-times font-red"></i> </td>
                            <?php }
                            ?>

                            <td class="col-md-2">
                                <a class="btn btn-circle blue btn-outline" href="<?php echo base_url('uploads/apk/' . $file->file); ?> " download="<?php echo $file->name . '_' . $file->version; ?> "><i class="fa fa-download"></i>download</a>
                                <?php if ($full_name == "Boulbaba Zouaoua") { ?>
                                    <a class="btn btn-circle red btn-outline" href="<?php echo site_url('upload/delete/' . $file->id); ?>" onclick="return confirm('Are you sure you want to delete this  file ?');" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a>
                                    <?php } ?>

                            </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
