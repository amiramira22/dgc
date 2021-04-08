<?php
include ('header.php');
 ?>
 
 
 
<div class="row">
 <div class="col-md-12">									 
									 
	<div class="btn-group pull-right">
	    <a class="btn red" href="<?php echo site_url('brands/form'); ?>">
		<i class="fa fa-plus"></i> Add New
	</a>
   </div>
  
 </div>
</div>
<br>
							
<div class="row">
                        <div class="col-md-12">

<table class="table table-striped table-bordered table-hover " >
	
	<thead>
		<tr>
			
			
			<th>Code</th>
			
			<th>Name</th>
			<th>Color</th>
			<th>Active</th>


			<th>Actions</th>
		</tr>
	</thead>
	
	<tbody>
		
<?php foreach ($brands as $brand):?>
		<tr>
			<td><?php echo $brand -> code; ?></td>
			<td><?php echo $brand -> name; ?></td>
			<td bgcolor="<?php echo $brand -> color; ?>"></td>
			
			<td>
				<?php
				if ($brand -> active == 1) {
					echo 'Yes';
				} else {
					echo 'No';
				}
				?>
			</td>
			<td>
				<div class="btn-group">
					<a class="btn btn-sm yellow filter-submit margin-bottom" href="<?php echo site_url('brands/form/' . $brand -> id); ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a>
					
					
					<a class="btn btn-sm red filter-submit margin-bottom" href="<?php echo site_url('brands/delete/' . $brand -> id); ?>" onclick="return confirm('Are you sure you want to delete this  range?');" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a>
				</div>
			</td>
		</tr>
<?php endforeach;?>
		
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








