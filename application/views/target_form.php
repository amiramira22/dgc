<?php
include ('header.php');
 ?>
 
 
 
<script type="text/javascript">
	$(function() {
		$("#datepicker1").datepicker({
			changeMonth: true,
            changeYear: true,
        
			dateFormat : 'MM yy',
			altField : '#datepicker1_alt',
			altFormat : 'yy-mm-dd',
			 onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
       },
        
        
			
		});
	}); 
</script>







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
               <?php echo form_open('targets/form/' . $id, $attributes) ?>
				 <div class="form-body">
				 
				  
					
					
				
					
					<div class="form-group">
                      <label class="control-label col-md-3">Month <span class="required">* </span> </label>
                      <div class="col-md-6">
                      <?php 
                       $data = array('id'=>'datepicker1', 'value'=>set_value('date', reverse_format($date)), 'class'=>'form-control');
                       echo form_input($data);
                      ?>
                    <input type="hidden" name="date" value="<?php echo set_value('date', $date) ?>" id="datepicker1_alt" />
                      </div>
                    </div>
					
				
					
					
					
					
					
					
					
				 
				 </div> <!--  end form-body-->
				
				 <div class="form-actions">
					<div class="row">
						<div class="col-md-offset-3 col-md-9">
							<input class="btn red" type="submit" value="Save"/>
							<button type="button" class="btn default">Cancel</button>
						</div>
					</div>
				</div>
										
										
									
				</form>
								<!-- END FORM-->
				</div>
			</div>
		</div>
	</div> <!-- END ROW FOR
<?php
include ('footer.php');
 ?>
   