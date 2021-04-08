<?php if ($excel!=1){ 
 include ('header.php');

//print_r($this->Report_model->get_weekly_ss_outlet_data('2014-03-03'));
?>
 



<script type="text/javascript">

Date.prototype.getWeek = function() {
  var date = new Date(this.getTime());
   date.setHours(0, 0, 0, 0);
  // Thursday in current week decides the year.
  date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
  // January 4 is always in week 1.
  var week1 = new Date(date.getFullYear(), 0, 4);
  // Adjust to Thursday in week 1 and count number of weeks from date to week1.
  return 1 + Math.round(((date.getTime() - week1.getTime()) / 86400000
                        - 3 + (week1.getDay() + 6) % 7) / 7);
}

// Returns the four-digit year corresponding to the ISO week of the date.
Date.prototype.getWeekYear = function() {
  var date = new Date(this.getTime());
  date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
  return date.getFullYear();
}



$(function() {
    $( "#week-picker1" ).datepicker({
        showWeek: true,
        firstDay: 1,
        onSelect: function(date){
         var d = new Date(date);
            var index = d.getDay();
            if(index == 0) {
             d.setDate(d.getDate() - 6);
            }
            else if(index == 1) {
             d.setDate(d.getDate());
            }
            else if(index != 1 && index > 0) {
              d.setDate(d.getDate() - (index - 1));
            }
           
           
            

           
            $(this).val(d.getWeekYear()+'-W'+d.getWeek());
            
              var curr_date = d.getDate();
              var curr_month = d.getMonth() + 1; //Months are zero based
               var curr_year = d.getFullYear();
     $("#datepicker1_alt").val(curr_year + "-" + curr_month + "-" + curr_date);
            
        }

    });
	
	
	  $( "#week-picker2" ).datepicker({
        showWeek: true,
        firstDay: 1,
        onSelect: function(date){
         var d = new Date(date);
            var index = d.getDay();
            if(index == 0) {
             d.setDate(d.getDate() - 6);
            }
            else if(index == 1) {
             d.setDate(d.getDate());
            }
            else if(index != 1 && index > 0) {
              d.setDate(d.getDate() - (index - 1));
            }
           
           
            

           
            $(this).val(d.getWeekYear()+'-W'+d.getWeek());
            
              var curr_date = d.getDate();
              var curr_month = d.getMonth() + 1; //Months are zero based
               var curr_year = d.getFullYear();
     $("#datepicker2_alt").val(curr_year + "-" + curr_month + "-" + curr_date);
            
        }

    });
	
});
</script>




				<div class="row">
					<div class="col-md-12">
						<!-- BEGIN VALIDATION STATES-->
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption">
Weekly model Report							</div>
								
							</div>
							

							<div class="portlet-body form">
								<!-- BEGIN FORM-->
			
	<?php echo form_open($this->config->item('admin_folder').'/reports/weekly_model_report', 'class="form-horizontal" ');?>

												<div class="col-md-3">

							        <label >Start Date</label><br>
										<?php
				                          $data = array('id' => 'week-picker1', '', 'class' => 'form-control', 'placeholder' => "From");
				                          echo form_input($data);
				                        ?>
				                        <input type="hidden" name="from" value="" id="datepicker1_alt" />
									</div>
														<div class="col-md-3">

							        <label >End Date</label><br>
										<?php
				                          $data = array('id' => 'week-picker2', '', 'class' => 'form-control', 'placeholder' => "To");
				                          echo form_input($data);
				                        ?>
				                        <input type="hidden" name="to" value="" id="datepicker2_alt" />
									</div>
				
				            									<div class="col-md-3">

							        <label >Model</label><br>
										<?php

	                                      $model_ids = array();
                                          $model_ids[-1] = 'Select models';
	                                      foreach ($active_models as $model) {
		                                  $model_ids[$model -> id] = $model -> name ;
	                                      }
	                                     ?>
	                                   <?php echo form_dropdown('model_id', $model_ids, set_value('model_id', $model_id), 'class="form-control"'); ?>
									</div>
															<div class="col-md-3">

									<label >Per Week / Sum</label><br>
										<?php
			                             echo form_dropdown('type', array(
			                                 'SUM' => 'Per Sum',
			                                 'WEEK' => 'Per Week'
			                                  ), '', 'class="form-control"');
             ?>
									</div>
					         
			
<div class="col-md-12">

             						<label >Export to Excel</label><br>
			 <?php
			 echo form_dropdown('excel', array(
			 '0' => 'No',
			 '1' => 'Yes'
			  ), '', 'class="form-control"');
             ?>
             </div>

	
	<div class="form-actions">
										<div class="row">
											<div class="col-md-offset-5 col-md-12"><br>
												<button class="btn btn-primary" type="submit" value="Search">Search</button>
												<button type="button" class="btn default">Cancel</button>
											</div>
										</div>
									   </div>
										
								</div>
								</form>
								<!-- END FORM-->
							</div>
						</div>
						<!-- END VALIDATION STATES-->
					</div>

<?php } else { 
	 include ('header_report.php');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$page_title." - ".format_week($from)."_".format_week($to).".xls");
header("Pragma: no-cache");
header("Expires: 0");
	
	}
?>

<?php 



if(($type=='SUM')&&($from!='')) {?>
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
                               <div class="portlet-body flip-scroll">
							
	   <table class="table table-bordered table-striped table-condensed flip-content">
	
	<thead>
		<tr>
			<th> Brand</th>
			<th>Model</th>
			<th>Range </th>
			<th>Price Range</th>
			<th>SIM</th>
			<th>Standard Price</th>
			
			<th>Weekly Sales</th>
			<th>Shelf Share</th>
			<th>Price</th>
			<th>Dispo</th>
		
			
		</tr>
	</thead>
	
	<tbody>
		
   
		<?php


		foreach ($report_data as $data):
			
			
			?>
	
	
	
		 <tr>
		 
		

     		<td><?php echo $data['brand']; ?></td>
     		<td><?php echo $data['model']; ?></td>
     		<td><?php echo $this->Range_model->get_range_name($this->Model_model->get_model_range1($data['model_id'])); ?></td>
     		<td><?php echo $this->Price_range_model->get_price_range_name($this->Model_model->get_model_price_range($data['model_id'])); ?></td>
     		<td><?php echo $this->Model_model->get_model_sim($data['model_id']); ?></td>
			<td><?php echo $data['std_price']; ?></td>
     		<td><?php echo $data['ws']; ?></td>
     		<td><?php echo $data['shelf']; ?></td>
     		<td><?php echo number_format($data['price'], 3, ".", "")  ; ?></td>
			<td><?php
			//echo $data['model_id']  ; 
			$count=$this->Report_model->get_count_dispo_model($from, $to, $data['model_id'])->nb;
			echo $count;
			?></td>
			
			
			 
			
			
			
		
		</tr>
<?php endforeach;?>
		
	</tbody>
</table>

</div>
</div>
</div>
</div>
<?php }
else if(($type=='WEEK')&&($from!='')){
	
	$dates = array();
	$components = array();
	
	$count_date=0;
	   
	    foreach ($report_data as $row) {
	    	 
		//create an array with all the models
		if(!in_array($row['date'], $dates)){
        $dates[] = $row['date'];
			$count_date+=1;
		}
		//create an array for every brand and the count at a outlet
        $components[$row['model_id']][$row['date']] = $row['ws'];
		
		}
	
?>
<div class="row">
          <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            		<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-cogs"></i><?php echo $sub_title ?>
								</div>
								
							</div>
							<div class="portlet-body flip-scroll">
							
	   <table class="table table-bordered table-striped table-condensed flip-content">
	
	<thead>
		<tr>


			<th> Brand</th>
			<th>Model</th>
			<th>Range </th>
			<th>Price Range</th>
			<th>SIM</th>
			<th>Standard Price</th>

			
			<th colspan="<?php echo $count_date; ?>">Weekly Sales</th>
			<th>Dispo</th>
		
			
		</tr>
		
		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<?php foreach($dates as $date){
			?>
			<th ><?php echo format_week($date); ?></th>
            <?php } ?>
			
			<th></th>
		
			
		</tr>
	</thead>
	
	<tbody>
		
   
		<?php 	
		 foreach($components as $model_id => $componentDates){
		 	
			$model = $this -> Model_model -> get_model($model_id);
			$model_name = $model -> name;
			$brand_id = $model -> brand_id;
			$brand_name=$this->Brand_model->get_brand_name($brand_id);
			$range_id = $model -> range_id;
			$range_name=$this->Range_model->get_range_name($range_id);
			$price_range_id = $model -> price_range_id;
			$price_range_name=$this->Price_range_model->get_price_range_name($price_range_id);
			$sim = $model -> sim;
			$std_price = $model -> price;
			
			
			
		?>
	
	
	
		 <tr>
		 
		

     		<td><?php echo $brand_name; ?></td>
     		<td><?php echo $model_name; ?></td>
     		<td><?php echo $range_name; ?></td>
     		<td><?php echo $price_range_name; ?></td>
     		<td><?php echo $sim; ?></td>
			<td><?php echo $std_price; ?></td>
			
			
			  <?php 
                foreach($dates as $dt){
              ?>
                 <td><?php
			        
			          if(isset($componentDates[$dt]) ){
			        	echo  $componentDates[$dt];
			        }else echo '-'; 
			         ?> 
			     </td>
			    <?php } ?>
			
			
     		
     		
     		
     		
			<td><?php
			//echo $data['model_id']  ; 
			$count=$this->Report_model->get_count_dispo_model($from, $to, $model_id)->nb;
			echo $count;
			?></td>
			
			
			 
			
			
			
		
		</tr>
<?php } //endforeach ?>
		
	</tbody>
</table>
</div>

</div>
</div>
</div>




<?php } else { ?>
  <div class="note note-success">
                        <span class="label label-danger">NOTE!</span>
                        <span class="bold">Start date & End date are required.</span> </div>
<?php } ?>
<?php
	include ('footer.php');
 ?>