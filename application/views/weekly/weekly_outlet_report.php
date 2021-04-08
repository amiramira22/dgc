
<?php 
include ('header.php');

if ($excel!=1){ 
 //include ('header.php');

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
	<div class="col-sm-12 col-md-12" >
		
			
			
	<?php echo form_open($this->config->item('admin_folder').'/reports/weekly_outlet_report', 'class="form-horizontal" ');?>

						
			<div class="row">
			
			        <div class="form-group">
							        <label class="col-md-2 control-label">Start Date</label>
									<div class="col-md-4">
										<?php
				                          $data = array('id' => 'week-picker1', '', 'class' => 'form-control', 'placeholder' => "From");
				                          echo form_input($data);
				                        ?>
				                        <input type="hidden" name="from" value="" id="datepicker1_alt" />
									</div>
					
							        <label class="col-md-2 control-label">End Date</label>
									<div class="col-md-4">
										<?php
				                          $data = array('id' => 'week-picker2', '', 'class' => 'form-control', 'placeholder' => "To");
				                          echo form_input($data);
				                        ?>
				                        <input type="hidden" name="to" value="" id="datepicker2_alt" />
									</div>
					</div>
				
				</div> <!-- end row --->
					
				
			<div class="row">	
				              <div class="form-group">
							       <label class="col-md-2 control-label">Report Type</label>
							       <div class="col-md-4">
			 <?php
			 echo form_dropdown('type', array(
			'ss' => 'Shelf Share',
			'ms' => 'Market Share'
			  ), '', 'class="form-control"');
             ?>
</div>
             						<label class="col-md-2 control-label">Export to Excel</label>
             						<div class="col-md-4">
			 <?php
			 echo form_dropdown('excel', array(
			 '0' => 'No',
			 '1' => 'Yes'
			  ), '', 'class="form-control"');
             ?>
             </div>
									</div>
						
			
									</div>
					          </div>
			
            </div> <!-- end row --->			


	

			<div class="row">
			
			
			     <div class="clearfix right">
					<button class="btn btn-primary mr5" type="submit" >Submit</button>
					<a class="btn btn-default" href="<?php echo site_url($this->config->item('admin_folder').'/reports/weekly_model_report');?>">Reset</a>
				 </div>
			
			</div> <!-- end row --->
				
		
			
			
				
				
				
				</form>
           

	</div>
</div>







</br></br>



<?php } else { 
	 include ('header.php');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$page_title." - ".format_week($date).".xls");
header("Pragma: no-cache");
header("Expires: 0");
	
	}
?>
	
	












<?php if($report_data!=array() && $type=='ss'){?>

<script state="text/javascript" charset="utf-8">
			$(document).ready( function () {
				$('#example').dataTable( {
					"bJQueryUI": true,
					"iDisplayLength": 2000,
					
					"bSort": false,
					"oColVis": {
            
                    "bRestore": true,
                    "sAlign": "right"
                    },
					
					"sDom": '<"H"Tfr>t<"F"ip>',
					"oTableTools": {
		         	"sSwfPath": "../../../assets/media/swf/copy_csv_xls_pdf.swf"
		            }
				} );
				
				
					$("tfoot input").keyup( function () {
        /* Filter on the column (the index) of this element */
        oTable.fnFilter( this.value, $("tfoot input").index(this) );
    } );
     
     
     
    /*
     * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
     * the footer
     */
    $("tfoot input").each( function (i) {
        asInitVals[i] = this.value;
    } );
     
    $("tfoot input").focus( function () {
        if ( this.className == "search_init" )
        {
            this.className = "";
            this.value = "";
        }
    } );
     
    $("tfoot input").blur( function (i) {
        if ( this.value == "" )
        {
            this.className = "search_init";
            this.value = asInitVals[$("tfoot input").index(this)];
        }
    } );
	
				
				
				
			} );
  </script>



<div class="row">
	<div class="span12">
<table class="table table-striped dt-responsive" cellpadding="0" cellspacing="0" border="1">
		<thead>
			<tr>
				
				<th>POS</th> 
				<th>City</th> 
				<th>State</th> 
				<th>Class</th> 
				<th>Type</th> 
			<?php
			$brands = array();
			$components = array();
		//$report_data=$this->Report_model->get_weekly_ss_outlet_data('2014-03-03');
	    foreach ($report_data as $row) { 
		//create an array with all the models
		if(!in_array($row['brand'], $brands)){
        $brands[] = $row['brand'];
		}
		//create an array for every brand and the count at a outlet
        $components[$row['outlet_id']][$row['brand']] = $row['shelf'];
		
		}
		

			
			foreach($brands as $brand){
			?>
			<th><?php echo $brand; ?></th>
            <?php } ?>
            </tr>
			</thead>
		<tbody>
	
			<?php foreach($components as $outlet_id => $componentBrands){ 
				
				$city=$this->City_model->get_city_name($this->Outlet_model->get_outlet_city_id($outlet_id)) ;
				$state=$this->State_model->get_state_name($this->Outlet_model->get_outlet_state_id($outlet_id)) ;
				$class=$this->Outlet_model->get_outlet_class($outlet_id);
				$type=$this->Outlet_model->get_outlet_type($outlet_id);
				?>
                <tr>
                	<td><?php echo $this->Outlet_model->get_outlet_name($outlet_id) ; ?></td>
                	<td><?php echo $city ; ?></td>
                	<td><?php echo $state ; ?></td>
                	<td><?php echo $class ; ?></td>
                	<td><?php echo $type ; ?></td>
                   <?php  foreach($brands as $bd){?>
			        <td><?php
			          if(isset($componentBrands[$bd])){
			        	echo $componentBrands[$bd];
			        }else echo '-'; 
			         ?> </td>
				   <?php } ?>
                </tr>
            <?php } ?>
            
            
         
			
			
		</tbody>
	</table>
</div>

</div>

<?php } else if($report_data!=array() && $type=='ms'){?>
<script state="text/javascript" charset="utf-8">
			$(document).ready( function () {
				$('#example').dataTable( {
					"bJQueryUI": true,
					"iDisplayLength": 2000,
					
					"bSort": false,
					"oColVis": {
            
                    "bRestore": true,
                    "sAlign": "right"
                    },
					
					"sDom": '<"H"Tfr>t<"F"ip>',
					"oTableTools": {
		         	"sSwfPath": "../../../assets/media/swf/copy_csv_xls_pdf.swf"
		            }
				} );
				
				
					$("tfoot input").keyup( function () {
        /* Filter on the column (the index) of this element */
        oTable.fnFilter( this.value, $("tfoot input").index(this) );
    } );
     
     
     
    /*
     * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
     * the footer
     */
    $("tfoot input").each( function (i) {
        asInitVals[i] = this.value;
    } );
     
    $("tfoot input").focus( function () {
        if ( this.className == "search_init" )
        {
            this.className = "";
            this.value = "";
        }
    } );
     
    $("tfoot input").blur( function (i) {
        if ( this.value == "" )
        {
            this.className = "search_init";
            this.value = asInitVals[$("tfoot input").index(this)];
        }
    } );
	
				
				
				
			} );
  </script>

	<script>
$('#myTable').DataTable( {
    responsive: true
} );
	</script>
	<div class="row">
	<div class="span12">
<table id='myTable' class="table table-striped dt-responsive" cellpadding="0" cellspacing="0" border="1">
		<thead>
			<tr>
				
				<th>POS</th> 
				<th>City</th> 
				<th>State</th> 
				<th>Class</th> 
				<th>Type</th> 
			<?php
			$brands = array();
			$components = array();
		//$report_data=$this->Report_model->get_weekly_ss_outlet_data('2014-03-03');
	    foreach ($report_data as $row) { 
		//create an array with all the models
		if(!in_array($row['brand'], $brands)){
        $brands[] = $row['brand'];
		}
		//create an array for every brand and the count at a outlet
        $components[$row['outlet_id']][$row['brand']] = $row['ws'];
		
		}
		

			
			foreach($brands as $brand){
			?>
			<th><?php echo $brand; ?></th>
            <?php } ?>
            </tr>
			</thead>
		<tbody>
	
			<?php foreach($components as $outlet_id => $componentBrands){ 
				
				$city=$this->City_model->get_city_name($this->Outlet_model->get_outlet_city_id($outlet_id)) ;
				$state=$this->State_model->get_state_name($this->Outlet_model->get_outlet_state_id($outlet_id)) ;
				$class=$this->Outlet_model->get_outlet_class($outlet_id);
				$type=$this->Outlet_model->get_outlet_type($outlet_id);
				?>
                <tr>
                	<td><?php echo $this->Outlet_model->get_outlet_name($outlet_id) ; ?></td>
                	<td><?php echo $city ; ?></td>
                	<td><?php echo $state ; ?></td>
                	<td><?php echo $class ; ?></td>
                	<td><?php echo $type ; ?></td>
                   <?php  foreach($brands as $bd){?>
			        <td><?php
			          if(isset($componentBrands[$bd])){
			        	echo $componentBrands[$bd];
			        }else echo '-'; 
			         ?> </td>
				   <?php } ?>
                </tr>
            <?php } ?>
            
            
         
			
			
		</tbody>
	</table>
</div>

</div>

<?php }else{?>
	
        <div class="alert alert-standard">
	        <center>
            <strong>No data available . </strong> 
            </center> 
        </div>

<?php }?>	
	
<?php
	include ('footer.php');
 ?>