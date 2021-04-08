	<?php include('new_header.php'); ?>	
				
		
				
				<!-- Datatable-->
				
				<div class="box">
					<div class="col-md-12">
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet box red">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-globe"></i>Demandes de congé  
								</div>
								
							</div>
						
							 <div class="btn-group pull-right">
	<a class="btn btn-primary" href="<?php echo site_url($this -> config -> item('admin_folder') . '/demandes/form'); ?>"><i class="icon-plus-sign"></i> <?php echo 'Add demandes'; ?></a>
</div>
					
					
							
<table class="table table-bordered table-hover" id="sample_1">
  <thead>
      <tr>
		<th>Nom prenom</th>
	    
	    <th>Date debut</th>
	    <th>Date fin</th>
	    <th>Type de congé</th>
	    <th>Notification</th>
		<th>Actions</th>

	 </tr>
   </thead>
   <tbody>							
									
							
								
	<?php foreach ($demandes as $demande):?>
		<tr class="odd gradeX">
			<td><?php 
$a=$this->Demande_model->get_admin($demande->id_user);


			echo $a->firstname.' '.$a->lastname; ?></td>
			
			<td><?php echo $demande->date_deb; ?></td>
			<td><?php 

	echo $demande->date_fin;

			 ?></td>
			<td>
				<?php
			    
			     	echo $demande->type_conge;
			    
				
				
				?></td>
			<td><?php
if($demande->active==0)
{
echo 'demande en cours de traitement';
}
else{
echo 'demande validé';
}
			?></td>
			<td>
			<?php	if($demande->active==0)
{ ?>
	<?php if ($this -> auth -> check_access('Admin')){ ?>
				<div class="btn-group" style="float:right;">
					<a class="btn btn-flat btn-success" href="<?php  echo site_url($this -> config -> item('admin_folder') . '/demandes/confirm/'.$demande->id);?>"><i class="fa fa-pencil-square-o"></i> Confirmer congé</a>	
			<?php	}	?>
				
				<?php } ?>

				<a class="btn btn-flat btn-danger" href="<?php  echo site_url($this -> config -> item('admin_folder') . '/demandes/delete/'.$demande->id);?>"><i class="fa fa-pencil-square-o"></i> Supprimer demande</a>	
				<a class="btn btn-flat btn-primary" href="<?php  echo site_url($this -> config -> item('admin_folder') . '/demandes/form/'.$demande->id);?>"><i class="fa fa-pencil-square-o"></i> Consulter demande</a>	
					
				</div>
			</td>
		
		</tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
</div>

						<!-- END EXAMPLE TABLE PORTLET-->
</div>
</div>
				
<script type="text/javascript">
function areyousure()
{
	return confirm('Are you sure you want to delete this user?');
}
</script>			
				
				
		<?php include('new_footer.php'); ?>