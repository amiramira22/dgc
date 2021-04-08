<?php
include ('header.php');
 ?>
 

<div class="row">
 <div class="col-md-12">									 
									 
	<div class="btn-group pull-right">
	    <a class="btn blue" href="<?php echo site_url('states/form'); ?>">
		<i class="fa fa-plus"></i> Add New
	</a>
   </div>
  
 </div>
</div>
<br>
<div class="row">
          <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            		<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-cogs"></i><?php echo $sub_title;?>
								</div>
								
							</div>
							<div class="portlet-body">
							
<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table">
	
	<thead>
		<tr>
			
			
			<th>Code</th>
			
			<th>Name</th>

            <th>Zone</th>

			<th>Actions</th>
		</tr>
	</thead>
	
	<tbody>
		
<?php foreach ($states as $state):?>
		<tr>
			<td><?php echo $state -> code; ?></td>
			<td class="gc_cell_left"><?php echo $state -> name; ?></td>
			<td class="gc_cell_left"><?php echo  $this->Zone_model->get_zone_name($state->zone_id); ?></td>
			
			<td>
				<div class="btn-group" >
					<a class="btn btn-sm yellow filter-submit margin-bottom" href="<?php echo site_url($this -> config -> item('admin_folder') . '/states/form/' . $state -> id); ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="icon-pencil"></i></a>
					
					
					<a class="btn btn-sm red filter-submit margin-bottom" href="<?php echo site_url($this -> config -> item('admin_folder') . '/states/delete/' . $state -> id); ?>" onclick="return confirm('Are you sure you want to delete this state?')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="icon-trash icon-white"></i></a>
				</div>
			</td>
		</tr>
<?php endforeach;?>
		
	</tbody>
</table>
</div>
</div>
</div>
</div>

<?php
	include ('footer.php');
 ?>