<?php
include ('header.php');
 ?>
 
 
 
 
	
<div class="row">
 <div class="col-md-12">									 
									 
	<div class="btn-group pull-right">
	    <a class="btn blue" href="<?php echo site_url('zones/form'); ?>">
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
									<i class="fa fa-cogs "></i><?php echo $sub_title;?>
								</div>
								
							</div>
							<div class="portlet-body">
							
<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table">

	<thead>
		<tr>
			<th>Code</th>
			<th>Name</th>
			<th>Actions</th>
		</tr>
	</thead>
	
	<tbody>
		
		
<?php foreach ($zones as $zone):?>
		<tr>
			<td><?php echo $zone -> code; ?></td>
			<td class="gc_cell_left"><?php echo $zone -> name; ?></td>
		
			<td>
				
	<a class="btn btn-sm yellow filter-submit margin-bottom" href="<?php echo site_url('zones/form/' . $zone -> id); ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a>
				
					<?php if ($zone -> id!=1)
					{
					 ?>
		   
<a class="btn btn-sm red filter-submit margin-bottom" href="<?php echo site_url( 'zones/delete/' . $zone -> id); ?> "onclick="return confirm('Are you sure you want to delete this zone?')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a>
				<?php } ?>
				
			</td>
		</tr>
<?php endforeach;
			
		?>
		
	</tbody>
</table>
</div>
</div>
</div>
</div>
<?php
	include ('footer.php');
 ?>