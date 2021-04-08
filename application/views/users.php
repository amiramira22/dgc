<?php
include ('header.php');
?>	



<!-- Datatable-->

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="btn-group pull-right">
            <a class="btn red" href="<?php echo site_url('users/form'); ?>">
                <i class="fa fa-plus"></i> Add New
            </a>
        </div>

        <table class="table table-bordered table-striped table-condensed flip-content">
            <thead class="flip-content">
                <tr>
                    <th>Username</th>
                    <th>fullname</th>
                    <th>E-mail</th>
                    <th>Role</th>
                    <th>Active</th>
                    <th colspan="3">Actions</th>
                </tr>
            </thead>
            <tbody>							



                <?php foreach ($users as $user): ?>
                    <tr class="odd gradeX">
                        <td><?php echo $user->username; ?></td>
                        <td><?php echo $user->name; ?></td>
                        <td><a href="mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a></td>
                        <td>
                            <?php
                            if ($user->access == 'Admin') {
                                echo '<span class="label label-sm label-danger">' . $user->access . '</span>';
                            } else if ($user->access == 'Field Officer') {
                                echo '<span class="label label-sm label-success">' . $user->access . '</span>';
                            } else if ($user->access == 'Henkel') {
                                echo '<span class="label label-sm label-warning">' . $user->access . '</span>';
                            } else {
                                echo '<span class="label label-sm label-primary">' . $user->access . '</span>';
                            }
                            ?></td>
                        <td>
                            <?php
                            if ($user->active == 1) {
                                echo '<span class="label label-sm label-success">Yes</span>';
                            } else {
                                echo '<span class="label label-sm label-default">No</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <a class="btn btn-sm yellow filter-submit margin-bottom" href="<?php echo site_url('users/form/' . $user->id); ?>"><i class="fa fa-pencil-square-o"></i> Edit</a>	

                        </td>		

                        <td>		
    <?php if ($user->active == 1) { ?>
                                <a class="btn btn-sm green filter-submit margin-bottom" href="<?php echo site_url('users/desactivate/' . $user->id); ?>"><i class="fa fa-thumbs-up"></i> </a>	
                            <?php } else { ?>
                                <a class="btn btn-sm default filter-submit margin-bottom" href="<?php echo site_url('users/activate/' . $user->id); ?>"><i class="fa fa-thumbs-down"></i> </a>	


        <?php
    }
    ?>
                        </td>
                        <td>	
                            <a class="btn btn-sm red filter-submit margin-bottom" href="<?php echo site_url('users/delete/' . $user->id); ?>" onclick="return areyousure();"><i class="fa fa-trash-o"></i> Delete</a>

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



<script type="text/javascript">
    function areyousure() {
        return confirm('Are you sure you want to delete this user?');
    }
</script>			


<?php
include ('footer.php');
?>