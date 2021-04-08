
<?php
include ('new_header.php');
 ?>
<style>
.input_modify{
   
	border-radius:8px;
	width:80px;
	hover { color:#fff }
}
</style>

<style>
.input_modify2{
   
	border-radius:8px;
	width:50px;
	hover { color:#fff }
}
</style>

<?php echo form_open($this->config->item('admin_folder').'/weekly_visits/bulk_save/'.$visit_id, array('id'=>'bulk_form'));?>
	
	
	
		<div class="form-actions">
		<span class="btn-group pull-right">
						<button class="btn btn-primary" href="#"><i class="icon-ok"></i> <?php echo lang('bulk_save');?></button>
		</span>
	</div>




	
	
     <h3><?php echo $brand_name;?></h3>
        	
        	<div class=" table table-responsive">
        	<table id="models">
		<thead>
			<tr>
		
			   
				<th>Model</th>
				<th>Shelf Share</th>
				<th>W/S</th>
				<th>Price</th>
								<th>total</th>

				 <?php if($brand_id==1){?>

				<th>Shortage</th>
				 <?php } ?>
				
			</tr>
		</thead>
		<tbody>
		<?php //echo (count($modern_models) < 1)?'<tr><td style="text-align:center;" colspan="7">'.lang('no_models').'</td></tr>':''?>
	<?php 
	$models = $this -> Weekly_model_model -> get_models_by_brand($visit_id,$brand_id);
	//print_r($models);
	
	
	foreach ($models as $model){
	
	             $shelf=$model->shelf;
				 $ws=$model->ws;
				 $price=$model->price;
				 
				 if($shelf==0){
				 	$shelf='';
				 }
				 if($ws==0){
				 	$ws='';
				 }
				 if($price==0){
				 	$price='';
				 }
			
	 ?>
			<tr>
			<?php $modell=$this->Model_model->get_model($model->model_id);?>
			
			<td><?php echo $modell->name;?>
				
				<?php echo form_input(array(
				'class'=>'input_modify2',
				'type'=>'hidden',	
				'name'=>'model['.$model->id.'][model_id]',
				'value'=>form_decode($model->model_id)
				 ));?>
				
			</td>
			<td>
				<center>
				<div data-role="">
				<?php echo form_input(array(
				'class'=>'input_modify2',
				'type'=>'text',	
				
				'name'=>'model['.$model->id.'][shelf]',
				'data-options'=>'{"type":"horizontal"}',
				'value'=>form_decode($shelf),
				'min'=>"0", 
				'max'=>"100" ));?>
				</div>
				</center>
            </td>
            
            
            
            <td>
            	<center>
				<div data-role="fieldcontain">
				<?php echo form_input(array(
				'id'=>'spin',
				'class'=>'input_modify2',
				'type'=>'text',		
				'data-role'=>"spinbox",
				'name'=>'model['.$model->id.'][ws]',
				'data-options'=>'{"type":"horizontal"}',
				'value'=>form_decode($ws),
				'min'=>"0", 
				'max'=>"100" ));?>
				</div>
				</center>
            </td>
            <td><input class="input_modify2" value="<?php echo $modell->price;?>" readonly></input>
				
			</td>

			<td><input class="input_modify2" value="<?php echo $modell->price * $ws;?>" readonly></input>
				
			</td>
            <td hidden>
            	<center>
					<div data-role="fieldcontain">
				<?php echo form_input(array(
				'id'=>'spin',
				'visibility'=>'hidden',
				'class'=>'input_modify',
				'type'=>'text',	
				'data-role'=>"spinbox",	
				'name'=>'model['.$model->id.'][price]',
				'data-options'=>'{"type":"horizontal"}',
				'value'=>form_decode($price),
				'min'=>"0", 
				'max'=>"10000" ));?>
				</div>
				</center>
            </td>
			
																 <?php if(($brand_id==1)&&($modell->shortage==1)){?>
													<td>
													
													
												<?php

//echo $model->shortage;
													echo form_input(array('id'=>'model_'. $model->model_id,'name'=>'model['.$model->id.'][shortage]','value'=>form_decode($model->shortage), 'type'=>'hidden', 'class'=>'span1'));?>
					
           <FONT color="green"><b>No </b></FONT>	<input type="radio" name=<?php echo 'model['.$model->id.'][shortage]'; ?> value="1"  <?php echo ($model->shortage== 1)? 'checked':'' ; ?>     onClick="document.getElementById('model_<?php echo $model->model_id; ?>').value=this.value"  /> 
           </td>
		   
		   <td>
			<FONT color="red"><b>Yes </b></FONT>     <input type="radio" name=<?php echo 'model['.$model->id.'][shortage]'; ?> value="0"  <?php echo ($model->shortage== 0)? 'checked':'' ; ?>     onClick="document.getElementById('model_<?php echo $model->model_id; ?>').value=this.value"  /> 
           </td>
													 <?php } ?>
			
			</tr>
	<?php } ?>
		</tbody>
	</table>
	</div>
	
        		
        	
        	
	<div class="form-actions">
		<span class="btn-group pull-right">
						<button class="btn btn-primary" href="#"><i class="icon-ok"></i> <?php echo lang('bulk_save');?></button>
		</span>
	</div>
	
	
		
	
	
	
	
</form>
<?php include('new_footer.php'); ?>