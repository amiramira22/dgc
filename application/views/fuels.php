<?php
include ('header.php');
 ?>
<script type="text/javascript">
	function areyousure()
{
	return confirm('<?php echo lang('confirm_delete_brand'); ?>
	');
	}
</script>
<div class="btn-group pull-right">
	<a class="btn" href="<?php echo site_url($this -> config -> item('admin_folder') . '/fuels/form'); ?>"><i class="icon-plus-sign"></i> Add New</a>
</div>

</br></br>
<div class="box">
<div class="box-content nopadding">
<table id="dt-example" cellpadding="0" cellspacing="0" border="0" class="table-dt table-striped-dt table-bordered-dt dataTable" >
	<thead>
		<tr>
			
			
			
			<th>Type</th>
			<th>Date</th>
			
		    <th>Full Name</th>
			<th>Project</th>
			<th>Active</th>
			<th>Actions</th>
		</tr>
	</thead>
	
	<tbody>
		
<?php foreach ($fuels as $fuel):?>
		<tr>
		
			<td><?php echo $fuel -> type; ?></td>
			<td><?php echo reverse_format($fuel -> date); ?></td>
			<td class="gc_cell_left"><?php echo $this->Admin_model->get_admin_name($fuel ->admin_id); ?></td>
			<td><?php echo $fuel -> project_id; ?></td>
			<td>
				<?php
				if ($fuel -> active == 1) {
					echo 'Yes';
				} else {
					echo 'No';
				}
				?>
			</td>
			<td>
				<div class="btn-group" style="float:right">
					<a class="btn" href="<?php echo site_url($this -> config -> item('admin_folder') . '/fuels/form/' . $fuel -> id); ?>"><i class="icon-pencil"></i> Edit</a>
					<a class="btn btn-danger" href="<?php echo site_url($this -> config -> item('admin_folder') . '/fuels/delete/' . $fuel -> id); ?>" onclick="return areyousure();"><i class="icon-trash icon-white"></i> Delete</a>
				
				</div>
			</td>
		</tr>
<?php endforeach;
			
		?>
		
	</tbody>
</table>
</div>
</div>

<?php
	include ('footer.php');
 ?>