<?php include('header.php'); ?>
<link href="<?php echo base_url('assets/jquery_mobile/jquery.mobile-1.0.1.min.css'); ?>" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo base_url('assets/jquery_mobile/jquery-1.8.3.min.js'); ?>"></script>
<script type="text/javascript">
            $(document).bind("mobileinit", function() {
            $.mobile.page.prototype.options.addBackBtn = true;
            });    
        </script>

<script type="text/javascript" src="<?php echo base_url('assets/jquery_mobile/jquery.mobile-1.3.2.min.js'); ?>"></script>

	
	
		
		
		


<ul data-role="listview" data-inset="true" >
    <li data-role="list-divider" data-theme="b"><h3><?php echo $page_title;?></h3></li>
    <div class="btn-group">
		<a class="btn btn-line-primary btn-icon-inline waves-effect" href="<?php echo site_url('admin/weekly_visits'); ?>">
			 <i class="fa fa-reply-all"></i> Back
		</a>

	</div>
    <?php 
   
    //print_r($this->Weekly_model_model->get_brands_by_category(9,9));
    
    foreach ($brands as $brand): 	
	   $brand_id=$brand->id;
	   $brand_name=$brand->name;
     ?>
    
            <div class="col-md-12">
                            <h3 class="text-light">
     <li><a href="<?php echo site_url($this->config->item('admin_folder').'/weekly_visits/specific_models/'.$visit_id.'/'. $brand_id);?>"><?php echo $brand_name; ?></a></li>

   </h3>
                            <div class="panel panel-lined mb20 panel-hovered">
                            </div>
                        </div>
    <?php endforeach; ?>
    
    
    
   
</ul>
<?php include('footer.php'); ?>
