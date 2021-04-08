<?php
include ('header.php');
 ?>
       
 <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
									<i class="fa fa-cogs"></i><?php echo $page_title;?>
								</div>
                                  
                                </div>
                                <div class="portlet-body">



	   <div class="profile">
                        <div class="tabbable-line tabbable-full-width">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1_pictures" data-toggle="tab"> Pictures </a>
                                </li>
                                <li>
                                    <a href="#tab_1_remark" data-toggle="tab"> Remark </a>
                                </li>
                               
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1_pictures">
                                    <div class="row">
                                       <div class="col-md-12">
            <!-- BEGIN VALIDATION STATES-->
              
                  <div class="profile">
                       
                         
                          <div class="row profile-account">
                                    <div class="col-md-3">
                                            <ul class="ver-inline-menu tabbable margin-bottom-10">
                                                <li class="active">
                                                    <a data-toggle="tab" href="#tab_1-1">
                                                        <i class="fa fa-cog"></i> Branding 
													 </a>
                                                </li>
                                                <li>
                                                    <a data-toggle="tab" href="#tab_2-2">
                                                        <i class="fa fa-picture-o"></i> One picture </a>
                                                </li>
                                            
												
												
                                            </ul>
                                    </div> <!--end col-md-3-->
                                       
                                    <div class="col-md-9">
                                            <div class="tab-content">
                                                <div id="tab_1-1" class="tab-pane active">
                                                 
												 
												 <div id="content">
		                                            <div id="my-dropzone" class="dropzone">
			                                          <div class="dz-message">
			                                         	<h3>Drop files here</h3> or <strong>click</strong> to upload
			                                          </div>
		                                            </div>
	                                             </div>
												 
												 
                                                </div>
												
                                                <div id="tab_2-2" class="tab-pane">
									             <div id="content">
		                                            <div id="my-dropzone-one_pictures" class="dropzone">
			                                          <div class="dz-message">
			                                         	<h3>Drop files here</h3> or <strong>click</strong> to upload
			                                          </div>
		                                            </div>
	                                             </div>
												 
		                                        </div>
                                         
												
                                        </div>
                                        
                                    </div> <!--end col-md-9-->
                
                 </div>
                               
                   
                    </div>
          
                 
                               
    </div> <!--end col-md-12-->
                                        
                                    </div>
                                </div>
                                <!--tab_1_2-->
                                <div class="tab-pane" id="tab_1_remark">
                                 <div class="row">
                                      	 <div id="content">
		                                           
               <?php echo form_open('visits/pictures/' . $id) ?>

       	<div class="form-group">
                      <label class="control-label col-md-3">Remark <span class="required"> * </span>  </label>
                      <div class="col-md-6">
                      <?php
                         $data = array('name'=>'remark_images', 'value'=>set_value('remark_images', $remark_images), 'class'=>'form-control' ,'placeholder'=>"Remark");
                         echo form_textarea($data);
                      ?>
                      </div>
                    </div>
				
					 <div class="form-actions">
					<br>
					<br>
					<br>
					<br><br>
					<br>
					<div class="row">
						
						<div class="col-md-offset-5 col-md-9" style=" margin-top: 10px;">
							<input class="btn blue" type="submit" value="Save"/>
							<button type="button" class="btn default">Cancel</button>
						</div>
					</div>
				</div>
										
										
									
				</form>      
	                                             </div>
                                        
                                    </div>
                                </div>
                                <!--end tab-pane-->
                       
                            </div>
                           </div>
                <!-- END CONTENT BODY -->
            </div>
    </div>
	 </div>


                <!-- old-->


                                              
        <?php
include ('footer.php');
 ?>
   
   
   
<script>
		Dropzone.autoDiscover = false;
		var myDropzone = new Dropzone("#my-dropzone", {
			url: "<?php echo site_url('visits/upload_branding/' . $id) ?>",
			acceptedFiles: "image/*",
			addRemoveLinks: true,
			
			
			removedfile: function(file) {
				var name = file.name;
				  


				$.ajax({
					type: "post",
					url: "<?php echo site_url('visits/remove_branding/' . $id) ?>",
					data: { file: name },
					dataType: 'html'
				});

				// remove the thumbnail
				var previewElement;
				return (previewElement = file.previewElement) != null ? (previewElement.parentNode.removeChild(file.previewElement)) : (void 0);
			},
			init: function() {
				var me = this;
				$.get("<?php echo site_url('visits/list_branding_files/' . $id) ?>", function(data) {
					// if any files already in server show all here
					if (data.length > 0) {
						$.each(data, function(key, value) {
							var mockFile = value;
							me.emit("addedfile", mockFile);
							me.createThumbnailFromUrl( mockFile, "<?php echo base_url(); ?>uploads/branding/" + value.name);
							me.emit("complete", mockFile);
						});
					}
				});
			}
		});
	</script>
	   
<script>
		Dropzone.autoDiscover = false;
		var myDropzone = new Dropzone("#my-dropzone-one_pictures", {
			url: "<?php echo site_url('visits/upload_one_pictures/' . $id) ?>",
			acceptedFiles: "image/*",
			addRemoveLinks: true,
			
			removedfile: function(file) {
				var name = file.name;

				$.ajax({
					type: "post",
					url: "<?php echo site_url('visits/remove_one_pictures/' . $id) ?>",
					data: { file: name },
					dataType: 'html'
				});

				// remove the thumbnail
				var previewElement;
				return (previewElement = file.previewElement) != null ? (previewElement.parentNode.removeChild(file.previewElement)) : (void 0);
			},
			init: function() {
				var me = this;
				$.get("<?php echo site_url('visits/list_one_pictures_files/' . $id) ?>", function(data) {
					// if any files already in server show all here
					if (data.length > 0) {
						$.each(data, function(key, value) {
							var mockFile = value;
							me.emit("addedfile", mockFile);
							me.createThumbnailFromUrl( mockFile, "<?php echo base_url(); ?>uploads/branding/" + value.name);
							me.emit("complete", mockFile);
						});
					}
				});
			}
		});
	</script>
	

	
