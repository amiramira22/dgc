<?php
include ('header.php');
?>




<?php if ($this -> auth -> check_access('Admin') || ($this -> auth -> check_access('Field Officer') )){?>
	
<div class="row">
 <div class="col-md-12">									 
									 
	<div class="btn-group pull-right">
	    <a class="btn red" href="<?php echo site_url('targets/form'); ?>">
		<i class="fa fa-plus"></i> Add New
	</a>
   </div>
  
 </div>
</div>
   
  </br> 
   <?php } ?>
<div class="row">
          <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            		<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-cogs"></i><?php echo $sub_title;?>
								</div>
								
							</div>
							<div class="portlet-body">
							
<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table">
			<thead>

		<tr>
		
		    
                <th>Outlet</th>
			    <th>date</th>

				<th>nb_visits</th>
	    		
	    		<th>Actions</th>

	 </tr>
			</thead>
			<tbody>
              <?php foreach ($targets as $target) { ?>

                    <tr>
					<td><?php echo $target -> outlet_name; ?></td>
					
					 <td><?php echo $target -> m_date; ?></td>
					  <td><?php echo $target -> nb_visit; ?></td>
					  <td><a class="btn btn-danger" href="<?php echo site_url($this -> config -> item('admin_folder') . '/targets/delete/' . $target -> id); ?> " onclick="return confirm('Are you sure you want to delete this visit?')"><i class="fa fa-trash-o"> </i>Delete</a></td>
						
                    </tr>
					


                    <?php } ?> 

			</tbody>
		</table>
		
		
</div>
 </div>
</div>
</div>
                  


<div class="row">
        <div class="col-md-12 text-center">
            <?php echo $pagination; ?>
        </div>
</div>
	
	
  <script type="text/javascript">
   
   initGeolocation();
   
     function initGeolocation()
     {
        if( navigator.geolocation )
        {
           // Call getCurrentPosition with success and failure callbacks
           navigator.geolocation.getCurrentPosition( success, fail );
		



        }
        else
        {
           alert("Sorry, your browser does not support geolocation services.");
        }
		
     }

     function success(position)
     {
	   

         document.getElementById('lng').value = position.coords.longitude;
         document.getElementById('lat').value = position.coords.latitude;
		
	 $.ajax({
    type: "POST",
    url:  "<?php echo site_url('geo/pos'); ?>", 

    data: {lat: position.coords.latitude,lng:position.coords.longitude},
});
		
     }

     function fail()
     {
	   }

   </script> 

      <INPUT TYPE="text" NAME="lng" ID="lng" VALUE="" hidden>

        <INPUT TYPE="text" NAME="lat" ID="lat" VALUE="" hidden>   
		
		
		<script>
		if (window.location.protocol != "https:")
    window.location.href = "https:" + window.location.href.substring(window.location.protocol.length);
</script>
<?php
	include ('footer.php');
?>