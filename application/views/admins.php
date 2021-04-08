<?php include('header.php'); ?>

 
<div class="row">
 <div class="col-md-12">									 
									 
	<div class="btn-group pull-right">
	    <a class="btn blue" href="<?php echo site_url('admin/form'); ?>">
		<i class="fa fa-plus"></i> Add New
	</a>
   </div>
  
 </div>
</div>
<br>
							
<div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="fa fa-cogs"></i><?php echo $sub_title;?>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table">	<thead>
		<tr>

			<th>First Name</th>
			<th>Last Name</th>
			<th>Email</th>
			<th>Access</th>
			<th>Active</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($admins as $admin):?>
		<tr>

			<td><?php echo $admin->firstname; ?></td>
			<td><?php echo $admin->lastname; ?></td>
			<td><?php echo $admin->email; ?></td>
			<td><?php echo $admin->access; ?></td>
			
			<td>
				<?php
				if ($admin -> active == 1) {?>
<span class="badge badge-success">  <i class="fa fa-check"> </i> </span>            
			<?php	} else { ?>
<span class="badge badge-danger">  <i class="fa fa-remove"> </i> </span>            
				<?php } 
				?>
			</td>
			<td>
						 <div class="btn-group btn-group-sm btn-group-solid">
					<a class="btn btn-sm yellow filter-submit margin-bottom" href="<?php echo site_url('admin/form/'.$admin->id);?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a>	
					<?php if ($admin -> active == 1) { ?>
					<a class="btn btn-sm yellow-crusta filter-submit margin-bottom" href="<?php echo site_url('admin/desactivate/'.$admin->id);?>" data-toggle="tooltip" data-placement="top" title="Desactivate"><i class="fa fa-remove"></i></a>	
                    <?php } else {?>
                    <a class="btn btn-sm green filter-submit margin-bottom" href="<?php echo site_url('admin/activate/'.$admin->id);?>" data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-check"></i></a>	

                 
					<?php
					}
					$current_admin	= $this->session->userdata('admin');
					$margin			= 30;
					if ($current_admin['id'] != $admin->id): ?>
						<a class="btn btn-sm red filter-submit margin-bottom" href="<?php echo site_url($this->config->item('admin_folder').'/admin/delete/'.$admin->id); ?>" onclick="return confirm('Are you sure you want to delete this user?')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i> </a>
					<?php endif; ?>
				</div>
			</td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
</div>
</div>
</div>
</div>


<?php include('footer.php'); ?>
