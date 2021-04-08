<?php include('header.php'); ?>

<?php echo form_open($this->config->item('admin_folder').'/weekly_visits/add_model/'.$visit_id); ?>


	
	<div class="row">
		<div class="span3">
        <label for="zone_id"><?php echo lang('model');?></label>
	  
	     <?php echo form_dropdown('model_id', $model_ids, '', 'class="span8"'); ?>
        </div>
	</div>

		<div class="row">
		<div class="span3">
			<label><?php echo lang('shelf');?></label>
			<?php
			$data	= array('name'=>'shelf', '', 'class'=>'span8');
			echo form_input($data); ?>
		</div>
	</div>

	<div class="row">
		<div class="span3">
			<label><?php echo lang('ws');?></label>
			<?php
			$data	= array('name'=>'ws', '', 'class'=>'span8');
			echo form_input($data); ?>
		</div>
		
	</div>
	
	<div class="row">
		<div class="span3">
			<label><?php echo lang('price');?></label>
			<?php
			$data	= array('name'=>'price', '', 'class'=>'span8');
			echo form_input($data); ?>
		</div>
		
	</div>

	

	<div class="form-actions">
		<input class="btn btn-primary" type="submit" value="<?php echo lang('save');?>"/>
	</div>
</form>

<?php include('footer.php');



