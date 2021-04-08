<?php // print_r($report_data); ?>

<?php
include ('header.php');
 ?>
 
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
 
 
 
<script type="text/javascript">
	function areyousure()
{
	return confirm('<?php echo lang('confirm_delete_visit'); ?>
	');
	}
</script>


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
    $( "#week-picker" ).datepicker({
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
});
</script>





<div class="row">
	<div class="span12" style="border-bottom:1px solid #f5f5f5;">
		<div class="row">	
			
			
			<div class="span9">
	<?php echo form_open($this->config->item('admin_folder').'/reports/weekly_report', 'class="form-inline" style="float:right"');?>
	<fieldset>
						
			<div class="row">
				
					
				<div class="span3">
                
				<?php
				$data = array('id' => 'week-picker', '', 'class' => 'span3', 'placeholder' => "Week");
				echo form_input($data);
				?>
				<input type="hidden" name="date" value="" id="datepicker1_alt" />
			    </div>
			    
			    <div class="btn-group pull-left">
			  	<div class="span3">
			    <button class="btn btn-primary" name="submit" value="search" >Get Visits</button>
				
	             <a class="btn" href="<?php echo site_url($this->config->item('admin_folder').'/reports/weekly_report');?>">Reset</a>
                 </div>
               
               </div>
			    
			   
			
			   
			  </div>
				
			</br>
			
			  <div class="row">
			  
                </div>
				
				
					
					</fieldset>
				</form>
             </div>

		</div>
	</div>
</div>









</br></br>

<div class="box">
<div class="box-content nopadding">
<table id="example" cellpadding="0" cellspacing="0" border="0" class="display" >
	
	<thead>
		<tr>
			<th><a href="#">Brand</a></th>
			<th><a href="#">Model</a></th>
			<th><a href="#">Weekly Sales</a></th>
			<th><a href="#">Shelf Share</a></th>
			<th><a href="#">Price</a></th>
		
			
		</tr>
	</thead>
	
	<tbody>
		
   
		<?php foreach ($report_data as $data):
			
			
			?>
	
	
	
		 <tr>
		 
		

     		<td><?php echo $data['brand']; ?></td>
     		<td><?php echo $data['model']; ?></td>
     		<td><?php echo $data['ws']; ?></td>
     		<td><?php echo $data['shelf']; ?></td>
     		<td><?php echo $data['price']; ?></td>
			
			
			
		
		</tr>
<?php endforeach;?>
		
	</tbody>
</table>

</div>
</div>




<?php
	include ('footer.php');
 ?>