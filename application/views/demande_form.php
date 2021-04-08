<?php
include ('new_header.php');
 ?>

<script type="text/javascript">
	$(function() {
		$("#datepicker1").datepicker({
			
			dateFormat : 'dd/mm/yy',
			altField : '#datepicker1_alt',
			altFormat : 'yy-mm-dd'
		});
	}); 
</script>

<script type="text/javascript">
	$(function() {
		$("#datepicker2").datepicker({
			
			dateFormat : 'dd/mm/yy',
			altField : '#datepicker2_alt',
			altFormat : 'yy-mm-dd'
		});
	}); 
</script>
 


<?php echo form_open_multipart($this -> config -> item('admin_folder') . '/demandes/form/' . $id); ?>



<div class="well widget">
		
		
	
		
	

<div class="row">

	<div class="span3">
	                     </div>
		<div class="span3">
            <label style="font-weight:bold;">Date debut</label>
			<?php
			$data	= array('id'=>'datepicker1', 'value'=>set_value('date_deb', reverse_format($date_deb)), 'class'=>'span3 form-control');
			echo form_input($data);
			?>
			<input type="hidden" name="date_deb" value="<?php echo set_value('date_deb', $date_deb) ?>" id="datepicker1_alt" />			
</div>


<div class="span3"></div>
		<div class="span3"></div>
            <label style="font-weight:bold;">Date fin</label>
			<?php
			$data	= array('id'=>'datepicker2', 'value'=>set_value('date_fin', reverse_format($date_fin)), 'class'=>'span3 form-control');
			echo form_input($data);
			?>
			<input type="hidden" name="date_fin" value="<?php echo set_value('date_fin', $date_fin) ?>" id="datepicker2_alt" />			
</div>

<div class="row">
	<div class="span3"></div>
		<div class="span6">
<label style="font-weight:bold;">Type de congé</label>
	<?php
	 
		                                        $options = array(	 'Autorisation'		=> 'Autorisation',
		                                                             'Maladie'		=> 'Maladie',
		                                                             'Annuel'		=> 'Annuel',
							                                         
		                                         );
		                                         echo form_dropdown('type_conge', $options, set_value('type_conge', $type_conge),'class="form-control" id="specific_cat" class="form-control"');
		                                         
	?>
</br>

    </div> 

		<div class="span6">
<label style="font-weight:bold;">Autorisation</label>
	<?php
	 
		                                        $options = array(	 '1 heures'		=> '1 heures',
		                                                             '2 heures'		=> '2 heures',
		                                                             '3 heures'		=> '3 heures',
							                                         
		                                         );
		                                         echo form_dropdown('autorisation', $options, set_value('autorisation', $autorisation),'class="form-control" id="specific_cat" class="form-control"');
		                                         
	?>
</br>

    </div> 
	
	<button type="submit" class="btn btn-primary" >Save</button>
<a class="btn btn-flat btn-success" href="<?php  echo site_url($this -> config -> item('admin_folder') . '/demandes');?>"> Retour </a>	
		
	
	   
        <?php if($id && $image != ''):?>
        <div style="text-align:center; padding:5px; border:1px solid #ccc;"><img src="<?php echo base_url('uploads/certif/'.$image);?>" height="300" width="300 alt="current"/><br/><?php echo lang('current_file');?></div>
       <?php endif;?>

<div class="form-group">
 <label class="control-label col-md-3 for="image">Photo</label>
        <?php
  
        $f_image    = array('name'=>'image', 'id'=>'image');
        echo form_upload($f_image,'','multiple'); 
   
        ?>
			
	
		
	
	
 </div>
 
</form>

</div></div>







<?php
	include ('new_footer.php');
?>