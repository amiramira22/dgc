<?php
include ('header.php');

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
<div class="profile">
                        <div class="tabbable-line tabbable-full-width">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1_1" data-toggle="tab"> List Routing</a>
                                </li>
                                <li>
                                    <a href="#tab_1_3" data-toggle="tab"> Form Routing </a>
                                </li>
                               
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1_1">
                                  
                                        							
<div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="fa fa-cogs"></i><?php echo $sub_title;?>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table">
	<thead>
		<tr>
		

			<th>Date</th>
			<th>Field Officer</th>
			<th>Outlet</th>
			<th>Start Time</th>
			<th>End Time</th>
			<th>Active</th>
			<th>Actions</th>
		</tr>
	</thead>
	
	<tbody>
		
<?php foreach ($routes as $route):?>
		<tr>

			<?php /*<td style="width:16px;"><?php echo  $customer->id; ?></td>*/ ?>
			<td><?php echo reverse_format($route -> date); ?></td>
			<td class="gc_cell_left"><?php echo $this->auth->get_admin_name($route -> admin_id); ?></td>
			<td class="gc_cell_left"><?php echo $this->Outlet_model->get_outlet_name($route -> outlet_id); ?></td>
			<td class="gc_cell_left"><?php echo $route -> start_time ?></td>
			<td class="gc_cell_left"><?php echo $route -> end_time ?></td>
			<td>
				<?php
				if ($route -> active == 1) {?>
<span class="badge badge-success">  <i class="fa fa-check"> </i> </span>            
			<?php	} else { ?>
<span class="badge badge-danger">  <i class="fa fa-remove"> </i> </span>            
				<?php } 
				?>
			</td>
			<td>
				<div class="btn-group">
					
				<a class="btn btn-sm red filter-submit margin-bottom" href="<?php echo site_url('admin/delete_route/' . $route -> id); ?>" onclick="return areyousure();"><i class="icon-trash icon-white"></i>Delete</a>
				
				</div>
			</td>
		</tr>
<?php endforeach;
			
		?>
		
	</tbody>
</table>
</div>
</div>

 <div class="row">
        <div class="col-md-12 text-center">
            <?php echo $pagination; ?>
        </div>
    </div>

                                </div>
								</div>
								</div>
                                <!--tab_1_2-->
                                <div class="tab-pane" id="tab_1_3">
                                   <div class="row">
                                        <div class="row">
					<div class="col-md-12">
						<!-- BEGIN VALIDATION STATES-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-gift"></i><?php echo $sub_title;?>
								</div>
								
							</div>
							<div class="portlet-body form">
								<!-- BEGIN FORM-->
								<?php $attributes = array('class' => 'form-horizontal');?>
								<?php echo form_open('admin/add_routing/'.$id, $attributes); ?>
										<div class="form-body">
		<div class="form-group">
											<label class="control-label col-md-3">Outlet <span class="required">
											* </span>
											</label>
											<div class="col-md-6">
										<?php
                 
				$outlet_ids = array();
				foreach ($outlets as $outlet) {
					$outlet_ids[$outlet -> id] = $outlet -> name;
				}
				?>
				<?php echo form_dropdown('outlet_id', $outlet_ids, '', 'class="span12 form-control"'); ?>
											</div>
										</div>
	
			
				<div class="form-group">
											<label class="control-label col-md-3">Date <span class="required">
											* </span>
											</label>
											<div class="col-md-6">
								<?php
				$data = array('id' => 'datepicker1', '', 'class' => 'span4 form-control', 'placeholder' => "Date");
				echo form_input($data);
				?>
								<input type="hidden" name="date" value="" id="datepicker1_alt" />

											</div>
										</div>
					
				
				<div class="form-group">
											<label class="control-label col-md-3">Start Time <span class="required">
											* </span>
											</label>
											<div class="col-md-6">
								<?php
				$data = array('name'=>'start_time','type'=>'time' ,'class' => 'span4 form-control', 'placeholder' => "12:00");
				echo form_input($data);
				?>

											</div>
										</div>
					
			   
				 
					<div class="form-group">
											<label class="control-label col-md-3">End Time <span class="required">
											* </span>
											</label>
											<div class="col-md-6">
								<?php
				$data = array('name'=>'end_time','type'=>'time' ,'class' => 'span4 form-control', 'placeholder' => "12:00");
				echo form_input($data);
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











                                </div>
                                </div>
                                <!--end tab-pane-->
                                <div class="tab-pane" id="tab_1_6">
                                    <div class="row">
                                        
                                     
                                </div>
                                <!--end tab-pane-->
                            </div>
                        </div>
                    </div>
                </div>


</br></br></br>




<?php

	include ('footer.php');
 ?>