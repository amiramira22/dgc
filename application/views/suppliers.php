<?php include('header.php'); ?>


 
<div class="row">
 <div class="col-md-12">									 
									 
	<div class="btn-group pull-right">
	    <a class="btn blue" href="<?php echo site_url('suppliers/form'); ?>">
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
<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table">

	<thead>
		<tr>
		<!--<th>Id</th>-->
			<th>Name</th>
			<th>Description</th>
			
			<?php if ($this -> auth -> check_access('Admin')) {  ?>
			<th>Actions</th>
			<?php } ?>
		</tr>
	</thead>
	
	<tbody>
		
<?php foreach ($suppliers as $supplier):?>
		<tr>
		<!--<td><?php //echo $names[$supplier -> id]['name']; ?></td> -->
		<!--<td><?php //echo $supplier -> id; ?></td>-->
			<td class="gc_cell_left"><?php echo $supplier -> name; ?></td>
			<td class="gc_cell_left"><?php echo $supplier -> description; ?></td>
			<?php if ($this -> auth -> check_access('Admin')) {  ?>
			<td><div class="btn-group" style="float:right;">
				<a class="btn btn-sm blue filter-submit margin-bottom" href="<?php echo site_url($this -> config -> item('admin_folder') . '/suppliers/form_edit/'.$supplier->id); ?>
 "> Edit</a>
				<a class="btn btn-sm red filter-submit margin-bottom" href="<?php echo site_url($this -> config -> item('admin_folder') . '/suppliers/delete/'.$supplier->id); ?>
" onclick="return areyousure();"><i class="fa fa-trash-o"></i> Delete</a>

				</div>
			</td>
			<?php } ?>
		</tr>
<?php endforeach;?>
		
	</tbody>
</table>
</div>
</div></div>
<?php
	include ('footer.php');
 ?>