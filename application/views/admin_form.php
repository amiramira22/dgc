<?php include('header.php'); ?>
				<div class="row">
					<div class="col-md-12">
						<!-- BEGIN VALIDATION STATES-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-plus"></i><?php echo $sub_title;?>
								</div>
								
							</div>
							<div class="portlet-body form">
								<!-- BEGIN FORM-->
								<?php $attributes = array('class' => 'form-horizontal');?>
								<?php echo form_open('admin/form/'.$id, $attributes); ?>

									<div class="form-body">
										
										
										<div class="form-group">
											<label class="control-label col-md-3">First Name <span class="required">
											* </span>
											</label>
											<div class="col-md-6">
											<?php
		$data	= array('name'=>'firstname', 'value'=>set_value('firstname', $firstname),'class'=>'form-control','placeholder'=>"first name");
		echo form_input($data);
		?>
											</div>
										</div>
										
											
										<div class="form-group">
											<label class="control-label col-md-3">Last Name <span class="required">
											* </span>
											</label>
											<div class="col-md-6">
										<?php
		$data	= array('name'=>'lastname', 'value'=>set_value('lastname', $lastname),'class'=>'form-control','placeholder'=>"Last name");
		echo form_input($data);
		?>

											</div>
										</div>
										
										
										<div class="form-group">
											<label class="control-label col-md-3">Email <span class="required">
											* </span>
											</label>
											<div class="col-md-6">
											<?php
		$data	= array('name'=>'email', 'value'=>set_value('email', $email),'class'=>'form-control','placeholder'=>"Email");
		echo form_input($data);
		?>

											</div>
										</div>	
										
										
										<div class="form-group">
											<label class="control-label col-md-3">Access <span class="required">
											* </span>
											</label>
											<div class="col-md-6">
										<?php
		$options = array(	'Admin'		=> 'Admin',
		                    'Field Officer '		=> 'Field Officer',
							'Samsung'	=> 'Samsung'
		                );
		echo form_dropdown('access', $options, set_value('access', $access),'class="span3 form-control"');
		?>

											</div>
										</div>	
										
										<div class="form-group">
											<label class="control-label col-md-3">Password <span class="required">
											* </span>
											</label>
											<div class="col-md-6">
											<?php
		$data	= array('name'=>'password','class'=>'form-control','placeholder'=>"Password");
		echo form_password($data);
		?>
		

											</div>
										</div>	
										<div class="form-group">
											<label class="control-label col-md-3">Confirm Password <span class="required">
											* </span>
											</label>
											<div class="col-md-6">
										
		<?php
		$data	= array('name'=>'confirm','class'=>'form-control','placeholder'=>"Confirm Password");
		echo form_password($data);
		?>
		
		

											</div>
										</div>	
										
										
										
										
									
										
									</div>
									<div class="form-actions">
										<div class="row">
											<div class="col-md-offset-5 col-md-9">
												<input class="btn blue" type="submit" value="Save"/>
												<button type="button" class="btn default">Cancel</button>
											</div>
										</div>
									</div>
								</form>
								<!-- END FORM-->
							</div>
						</div>
						<!-- END VALIDATION STATES-->
					</div>
				</div>
				
				
<?php include('footer.php'); ?>





