<?php include('header.php');?>


<?php echo form_open($this->config->item('admin_folder').'/weekly_visits/shortage_bulk_save/'.$id, array('id'=>'bulk_form'));?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Model Name</th>
				<th>Brand</th>
				<th>Shortage</th>
				
			
			</tr>
		</thead>
		<tbody>
		<?php //echo (count($modern_models) < 1)?'<tr><td style="text-align:center;" colspan="7">'.lang('no_models').'</td></tr>':''?>
	<?php foreach ($models as $model): ?>
			<tr>
				<td>
				<?php echo $this->Model_model->get_model_name($model->model_id); ?>
				</td>
				
				<td>
				<?php echo $this->Brand_model->get_brand_name($model->brand_id); ?>
				</td>
				<td><?php echo form_input(array('id'=>'model_'. $model->model_id,'name'=>'model['.$model->id.'][shortage]','value'=>form_decode($model->shortage), 'type'=>'hidden', 'class'=>'span1'));?>
					
           <FONT color="green"><b>Yes &nbsp;</b></FONT>	<input type="radio" name=<?php echo 'model['.$model->id.'][shortage]'; ?> value="1"  <?php echo ($model->shortage== 1)? 'checked':'' ; ?>     onClick="document.getElementById('model_<?php echo $model->model_id; ?>').value=this.value"  /> 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           <FONT color="red"><b>No &nbsp;</b></FONT>     <input type="radio" name=<?php echo 'model['.$model->id.'][shortage]'; ?> value="0"  <?php echo ($model->shortage== 0)? 'checked':'' ; ?>     onClick="document.getElementById('model_<?php echo $model->model_id; ?>').value=this.value"  /> 
			
				</td>
				
		
				
			
				
			</tr>
	<?php endforeach; ?>
		</tbody>
	</table>
	
	
	
	<div class="form-actions">
		<span class="btn-group pull-right">
						<button class="btn" href="#"><i class="icon-ok"></i> <?php echo lang('bulk_save');?></button>
		</span>
	</div>
	
	
</form>
<?php include('footer.php'); ?>