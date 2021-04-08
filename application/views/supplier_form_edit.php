<?php include('header.php'); ?>

<?php //echo form_open($this->config->item('admin_folder').'/suppliers/form_edit/'.$id); ?> 

<?php echo form_open($this->config->item('admin_folder').'/suppliers/form_edit/'.$id); ?>

	<div class="row">

		
		<div class="span3">
			<label>Name</label><br>
			<?php
			$data	= array('name'=>'name', 'value'=>$itm['name'],'class'=>'qua');
			echo form_input($data); ?>
		</div>
		
		<div class="span3">
			<label>Description</label><br>
			<?php
			$data	= array('name'=>'description', 'value'=>$itm['description'], 'class'=>'qua');
			echo form_input($data); ?>
		</div>
		
		
		
	</div> <!-- end row-->

	

	<div class="form-actions">
		<input class="btn-primary" type="submit" value="<?php echo lang('save');?>"/>
	</div>
</form>
<style>
	.qua , select{
    width: 50%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
		</style>

<?php
	include ('footer.php');
 ?>