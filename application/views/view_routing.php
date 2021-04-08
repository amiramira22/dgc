	<?php include('header.php'); 
	
	
	?>	
				
				
				
				<!-- Datatable-->
				
				<div class="row">
					<div class="col-md-12">
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet box blue-madison">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-globe"></i>Preview routings
								</div>
								
							</div>
							<div class="portlet-body">
								<?php echo form_open('admin/admin/publish_routing') ?>
								<div class="table-toolbar">
									<div class="row">
										<div class="col-md-6">
											<div class="btn-group">
											
											
												<input class="btn green" type="submit" value="Publish"/>
											 
											

											</div>
										</div>
										
									</div>
								</div>
<table class="table table-striped table-bordered table-hover" ">
  <thead>
      <tr>
		<th>Product Code</th>
	    <th>Date</th>

	    
	 </tr>
   </thead>
   <tbody>							
									
							
								
	<?php
	$count=0;
	 foreach ($routings as $routing):
	 	$count++;
	 $outlet_id=$this->Outlet_model->get_outlet_id_by_name($routing['outlet']);
	 $date =date('d/m/Y', ( $routing['date'] - 25569)*24*60*60 );
	 ?>
		<tr class="odd gradeX">
			
			<td><?php echo $routing['outlet']; ?></td>
			<td><?php echo $date.'***'.$count.'---'.$admin_id2; ?></td>

			
			
			<?php 
			
            
			$data = array('name'  => 'routing['.$count.'][date]','type'  => 'hidden','value'=>$date);
            echo form_input($data);
			$data = array('name'  => 'routing['.$count.'][admin_id2]','type'  => 'hidden','value'=>$admin_id2);
            echo form_input($data);

            $data = array('name'  => 'routing['.$count.'][outlet_id]','type'  => 'hidden','value'=>$outlet_id);
            echo form_input($data);
            ?>


			
			
			
			
		</tr>
    <?php endforeach; ?>
  </tbody>
</table>


</form>
</div>
</div>
						<!-- END EXAMPLE TABLE PORTLET-->
</div>
</div>
				
<script type="text/javascript">
function areyousure()
{
	return confirm('Are you sure you want to delete this S/N?');
}
</script>			
				
				
		<? include('footer.php'); ?>