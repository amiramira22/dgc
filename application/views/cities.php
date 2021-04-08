<?php
include ('header.php');
 ?>
 
 
	
<div class="row">
 <div class="col-md-12">									 
									 
	<div class="btn-group pull-right">
	    <a class="btn blue" href="<?php echo site_url('cities/form'); ?>">
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
			
			 <th>State</th>

			<th>Actions</th>
		</tr>
	</thead>
	
	<tbody>
	
		
		
	
<?php foreach ($cities as $city): ?>
		<tr>
		
			<td><?php echo $city -> code; ?></td>
			<td class="gc_cell_left"><?php echo $city -> name; ?></td>
			<td class="gc_cell_left"><?php echo  $this->State_model->get_state_name($city->state_id); ?></td>
			
			<td>
				<div class="btn-group" >
					<a class="btn btn-sm yellow filter-submit margin-bottom" href="<?php echo site_url('cities/form/' . $city -> id); ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a>
					
					
					<a class="btn btn-sm red filter-submit margin-bottom" href="<?php echo site_url('cities/delete/' . $city -> id); ?>" onclick="return confirm('Are you sure you want to delete this city?')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a>
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
                  


<div class="row">
        <div class="col-md-12 text-center">
            <?php echo $pagination; ?>
        </div>
</div>
	
	

<?php
	include ('footer.php');
 ?>