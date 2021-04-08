<?php include('header.php'); ?>

<div class="box">
<div class="box-content nopadding">

<table id="dt-example" cellpadding="0" cellspacing="0" border="0" class="table-dt table-striped-dt table-bordered-dt dataTable" >
	
	  <thead>
		<tr>
				<th></th>
		</tr>
	  </thead>
	
	<tbody>
<?php foreach ($messages as $message):?>
		<tr>
			<?php /*<td style="width:16px;"><?php echo  $customer->id; ?></td>*/?>
			<td>
				<h4>
				<a href="#"> <b>From : </b></a><?php echo $this->Admin_model -> get_admin_name($message -> from); ?></br>
				<a href="#"><b>Date : </b></a><?php echo  $message->modified; ?></br>
				<a href="#"><b>Object : </b></a><?php echo  $message->object; ?></br>
				<a href="#"><b>Message : </b></a><?php echo  $message->msg; ?></br>
				</h4>
			</td>
		
			
		</tr>
		
		
		
<?php endforeach;?>
</tbody>
</table>


</div>
</div>




<?php include('footer.php'); ?>