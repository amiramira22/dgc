<?php
include ('header.php');
//bcm
?>


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet light">

            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-magnifier font-red"></i>
                    <span class="caption-subject bold uppercase"> Search</span>
                    <span class="caption-helper">outlets</span>
                </div>
            </div> <!-- end portlet-title -->

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal'); ?>
                <?php echo form_open('outlets/search/', $attributes) ?>
                <div class="form-body">



                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Search </label>

                            <input type="text" name="search" class="form-control" placeholder="Search">
                        </div>

                        <div class="col-md-6">
                            <label>Field Officer </label>

                            <?php
                            $admin_ids = array();
                            foreach ($admins as $ad) {
                                $admin_ids[-1] = 'All Field Officer';
                                $admin_ids[$ad->id] = $ad->name;
                            }
                            ?>
                            <?php echo form_dropdown('user_id', $admin_ids, '', 'class="form-control"'); ?>
                        </div> <!-- end class="col-md-6"-->


                    </div>

                </div> <!--  end form-body-->
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-8">
                            <button type="submit" class="btn btn-circle red-mint btn-outline sbold uppercase">Search</button>
                            <button type="reset" class="btn btn-circle btn-default sbold uppercase">Cancel</button>
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


<div class="row">
    <div class="col-md-12">	

        <div class="row">
            <div class="col-md-12">									 
                <div class="btn-group pull-right">
                    <a class="btn btn-circle red-mint btn-outline sbold uppercase" href="<?php echo site_url('outlets/form'); ?>">
                        <i class="fa fa-plus"></i> Add New
                    </a>

                    <?php if (!$this->auth->check_access('Henkel')) { ?>
                        <a class="btn btn-circle blue btn-outline sbold uppercase" href="<?php echo site_url('outlets/export'); ?>">
                            <i class="glyphicon glyphicon-export"></i> Export
                        </a>
                    <?php } ?>

                </div>
            </div>
        </div>

    </div>
</div>
<br>

<div class="row">
    <div class="col-md-12">

        <table class="table table-striped table-bordered table-hover " width="100%" >
            <thead>
                <tr>


                    <th>Date</th>
                    <th>Code</th>
                    <th>Name</th>

                    <th>Zone</th>
                    <th>State</th>
                    <th>Field Officer</th>

                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($outlets as $outlet): ?>
                    <tr>
                        <td><?php echo substr($outlet->updated, 0, -8); ?></td>
                        <td><?php echo 'HCM' . str_pad($outlet->id, 3, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo $outlet->name; ?></td>

                        <td><?php echo $outlet->zone; ?></td>
                        <td><?php echo $outlet->state_name; ?></td>

                        <td><?php echo $this->auth->get_admin_name($outlet->admin_id); ?></td>



                        <td>
                            <?php if ($outlet->active == 1) { ?>
                                <span class="badge badge-success">  <i class="fa fa-check"> </i> </span>            
                            <?php } else { ?>
                                <span class="badge badge-danger">  <i class="fa fa-remove"> </i> </span>            
                            <?php }
                            ?>
                        </td>



                        <td>

                            <?php if ($this->auth->check_access('Admin')) { ?>
                                <div class="btn-group">
                                    <a class="btn btn-circle yellow btn-outline" target="_blank" href="<?php echo site_url('outlets/form/' . $outlet->id); ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a>
                                    <a class="btn btn-circle purple btn-outline"  target="_blank" href="<?php echo site_url('outlets/view/' . $outlet->id); ?>" data-toggle="tooltip" data-placement="top" title="View"><i class="icon-map"></i></a>

                                    <?php if ($outlet->active == 0) { ?>
                                        <a class="btn btn-circle blue btn-outline"   href="<?php echo site_url('outlets/activate/' . $outlet->id); ?>" data-toggle="tooltip" data-placement="top" title="Activate"><i class="glyphicon glyphicon-thumbs-up"></i></a>
                                    <?php } else {
                                        ?>
                                        <a class="btn btn-circle blue btn-outline"   href="<?php echo site_url('outlets/desactivate/' . $outlet->id); ?>" data-toggle="tooltip" data-placement="top" title="Desactivate"><i class="glyphicon glyphicon-thumbs-down"></i></a>
                                    <?php } ?>	
                                    <a class="btn btn-circle red btn-outline" href="<?php echo site_url('outlets/delete/' . $outlet->id); ?>" onclick="return confirm('Are you sure you want to delete this outlet?')"><i class="fa fa-trash-o" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>

                                </div>
                            <?php } ?>		 
                            <?php if ($this->auth->check_access('Henkel')) { ?>

                                <div class="btn-group btn-group-sm btn-group-solid">
                                    <a class="btn btn-sm blue filter-submit margin-bottom" href="<?php echo site_url('outlets/view/' . $outlet->id); ?>" data-toggle="tooltip" data-placement="top" title="View"><i class="icon-map"></i></a>
                                </div>
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