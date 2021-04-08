<?php include('header.php'); ?>

<div class="row">
					<div class="col-md-12">
						<!-- BEGIN VALIDATION STATES-->
						<div class="portlet box red">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-plus"></i><?php echo $sub_title;?>

								</div>
								
							</div>
							<div class="portlet-body form">
							<?php $attributes = array('class' => 'form-horizontal');?>
                           <?php echo form_open('brands/form/'.$id, $attributes); ?>


                         <div class="form-body">
                        
										
										<div class="form-group">
											<label class="control-label col-md-3">Code <span class="required">
											* </span>
											</label>
											<div class="col-md-6">
											<?php
			                                $data	= array('name'=>'code', 'value'=>set_value('code', $code), 'class'=>'form-control');
		                                  	echo form_input($data); ?>	
											</div>
										</div>
		
		                            <div class="form-group">
											<label class="control-label col-md-3">Name <span class="required">
											* </span>
											</label>
											<div class="col-md-6">
											<?php
		                           	$data	= array('name'=>'name', 'value'=>set_value('name', $name), 'class'=>'form-control');
		                        	echo form_input($data); ?>
											</div>
										</div>
								 <div class="form-group">
											<label class="control-label col-md-3">Color <span class="required">
											* </span>
											</label>
											<div class="col-md-6">
											<?php
		                           	$data	= array('name'=>'color','type'=>'color' ,'value'=>set_value('color', $color), 'class'=>'form-control');
		                        	echo form_input($data); ?>
											</div>
										</div>		
		                        
									
                                        
                                     <div class="form-group">
											
											<div class="col-md-offset-3 col-md-4">
	                                             <label class="checkbox">
				                                <?php
				                                $data = array('name' => 'active', 'value' => 1, 'checked' => $active,'class'=>'icheckbox_minimal-blue checked');
				                                echo form_checkbox($data).'  Active' ;
                                                ?>
		                                    	</label>
											</div>
											
											
											<div class="col-md-offset-3 col-md-4">
	                                             <label class="checkbox">
				                                <?php
				                                $data = array('name' => 'selected', 'value' => 1, 'checked' => $selected,'class'=>'icheckbox_minimal-blue checked');
				                                echo form_checkbox($data).'  selected' ;
                                                ?>
		                                    	</label>
											</div>
										</div>
                                        

										</div>
	
	
   

                                  <div class="form-actions">
										<div class="row">
											<div class="col-md-offset-5 col-md-9">
												<input class="btn red" type="submit" value="Save"/>
												<button type="button" class="btn default">Cancel</button>
											</div>
										</div>
									</div>
                                  </form>


</div></div></div></div>


<?php include('footer.php');