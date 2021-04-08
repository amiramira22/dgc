<?php include('header.php'); ?>





<script type="text/javascript">


 $('#date').keyup(function () {
      var value = $(this).val();
      $('#date').val(value);
    }).keyup();
    
    
    
    function add_visit_image1(data)
{
	p	= data.split('.');
	
	var photo = '<?php  add_image("'+p[0]+'", "'+p[0]+'.'+p[1]+'", '', '', '', dirname($_SERVER['DOCUMENT_ROOT']).'/fuel_img');?>';
	
	
	$('#gc_photos').append(photo);
	$('#gc_photos').sortable('destroy');
	photos_sortable();
}

    function add_visit_image2(data)
{
	p	= data.split('.');
	
	var photo = '<?php  add_image1("'+p[0]+'", "'+p[0]+'.'+p[1]+'", '', '', '', dirname($_SERVER['DOCUMENT_ROOT']).'/fuel_img');?>';
	
	
	$('#gc_photos1').append(photo);
	$('#gc_photos1').sortable('destroy');
	photos_sortable1();
}

function add_visit_image3(data)
{
	p	= data.split('.');
	
	var photo = '<?php  add_image2("'+p[0]+'", "'+p[0]+'.'+p[1]+'", '', '', '', dirname($_SERVER['DOCUMENT_ROOT']).'/fuel_img');?>';
	
	
	$('#gc_photos2').append(photo);
	$('#gc_photos2').sortable('destroy');
	photos_sortable2();
}

function remove_image(img)
{

	if(confirm('<?php echo lang('confirm_remove_image');?>'))
	{
		var id	= img.attr('rel')
		$('#gc_photo_'+id).remove();
	}
}

function remove_image1(img)
{
	if(confirm('<?php echo lang('confirm_remove_image');?>'))
	{
		var id	= img.attr('rel')
		$('#gc_photo1_'+id).remove();
	}
}


function remove_image2(img)
{
	if(confirm('<?php echo lang('confirm_remove_image');?>'))
	{
		var id	= img.attr('rel')
		$('#gc_photo2_'+id).remove();
	}
}

function photos_sortable()
{
	$('#gc_photos').sortable({	
		handle : '.gc_thumbnail',
		items: '.gc_photo',
		axis: 'y',
		scroll: true
	});
}

function photos_sortable1()
{
	$('#gc_photos1').sortable({	
		handle : '.gc_thumbnail1',
		items: '.gc_photo1',
		axis: 'y',
		scroll: true
	});
}

function photos_sortable2()
{
	$('#gc_photos2').sortable({	
		handle : '.gc_thumbnail1',
		items: '.gc_photo2',
		axis: 'y',
		scroll: true
	});
}
    
     

     
     
</script>




<?php echo form_open($this->config->item('admin_folder').'/fuels/form/'.$id); ?>

	<div class="row">
		<div class="span8">
			<label><b>Type</b></label>
			<?php
		$options = array(	'Weekly Fuel'		=> 'Weekly Fuel',
		                    'Accident'		=> 'Accident',
							'Others'	=> 'Others'
		                );
		echo form_dropdown('type', $options, set_value('type', $type), 'class="span8"');
		?>
		</div>
	</div>

	<div class="row">
		<div class="span8">
			<label> <b> Counter of the car</b></label>
			<?php
			$data	= array('name'=>'counter', 'value'=>set_value('counter', $counter), 'class'=>'span8');
			echo form_input($data); ?>
		</div>
		
	</div>
	
	<div class="row">
		<div class="span8">
			<label> <b> Fuel Filled during last week by card in TND </b></label>
			<?php
			$data	= array('name'=>'fuel_card', 'value'=>set_value('fuel_card', $fuel_card), 'class'=>'span8');
			echo form_input($data); ?>
		</div>
		
	</div>
	
	<div class="row">
		<div class="span8">
			<label> <b> Fuel Filled during last week in cash in TND</b></label>
			<?php
			$data	= array('name'=>'fuel_cash', 'value'=>set_value('fuel_cash', $fuel_cash), 'class'=>'span8');
			echo form_input($data); ?>
		</div>
		
	</div>
	
	<div class="row">
		<div class="span8">
			<label> <b> Remaining in the card in TND </b></label>
			<?php
			$data	= array('name'=>'remaining_card', 'value'=>set_value('remaining_card', $remaining_card), 'class'=>'span8');
			echo form_input($data); ?>
		</div>
		
	</div>

   
    
    </br>
    

	<legend>Car counter photos</legend>
    
    	<div id="visit_photos2">
				<div class="row">
					<iframe id="iframe_uploader3" src="<?php echo site_url($this->config->item('admin_folder').'/fuels/visit_image_form3');?>" class="span10" style="height:150px; border:0px;"></iframe>
				</div>
				
				
				<div class="row">
					<div class="span8">
						
						<div id="gc_photos2">
						
						
						
						<?php
						
						foreach($counter_photos as $photo_id=>$photo_obj)
						{
							
							if(!empty($photo_obj))
							{
								
								$photo = (array)$photo_obj;
								
								add_image2($photo_id, $photo['filename3'], '', $photo['caption'], isset($photo['primary_counter']),$counter_photos);
							}

						}
						?>
						</div>
					</div>
				</div>
				
				
		</div>
		
	
 </br>

	<legend>Accident photos</legend>
    
    
    <div id="visit_photos">
				<div class="row">
					<iframe id="iframe_uploader1" src="<?php echo site_url($this->config->item('admin_folder').'/fuels/visit_image_form1');?>" class="span10" style="height:150px; border:0px;"></iframe>
				</div>
				
				
				<div class="row">
					<div class="span8">
						
						<div id="gc_photos">
						
						<?php
						
						
						foreach($accident_photos as $photo_id=>$photo_obj)
						{
							//print_r($branding_before_images);
							if(!empty($photo_obj))
							{
								
								$photo = (array)$photo_obj;
								
								add_image($photo_id, $photo['filename1'], '', $photo['caption'], isset($photo['primary_accident']),$accident_photos);
							}

						}
						?>
						</div>
					</div>
				</div>
				
				
		</div>
    
 	
     </br>
 
	<legend>Others photos</legend>
    
    
    <div id="visit_photos1">
				<div class="row">
					<iframe id="iframe_uploader2" src="<?php echo site_url($this->config->item('admin_folder').'/fuels/visit_image_form2');?>" class="span10" style="height:150px; border:0px;"></iframe>
				</div>
				
				
				<div class="row">
					<div class="span8">
						
						<div id="gc_photos1">
						
						<?php
						foreach($other_photos as $photo_id=>$photo_obj)
						{
							
							if(!empty($photo_obj))
							{
								
								$photo = (array)$photo_obj;
								
								add_image1($photo_id, $photo['filename2'], '', $photo['caption'], isset($photo['primary_other']),$other_photos);
							}

						}
						?>
						</div>
					</div>
				</div>
				
				
		</div>
		
 	
 
     </br>
    
    
     <div class="row">
		<div class="span3">
       <label for="remark"><b>Remark</b></label>
	<?php
	$data	= array('name'=>'remark', 'value'=>set_value('remark', $remark), 'class'=>'span8');
	echo form_textarea($data);
	?>
    </div> 
    </div>
    
    
    
    
    
    
    
    
    
    

	<div class="form-actions">
		<input class="btn btn-primary" type="submit" value="Save"/>
	</div>
</form>










<?php
function add_image($photo_id, $filename, $alt, $caption, $primary=false,$tab)
{

	ob_start();
	?>
	<div class="row gc_photo" id="gc_photo_<?php echo $photo_id;?>" style="background-color:#fff; border-bottom:1px solid #ddd; padding-bottom:20px; margin-bottom:20px;">
		<div class="span6">
			<input type="hidden" name="accident_photos[<?php echo $photo_id;?>][filename1]" value="<?php echo $filename;?>"/>
			<img class="gc_thumbnail" src="http://www.capesolution.tn/fuel_img/<?php echo $filename;?>" style="padding:5px; border:1px solid #ddd"/>
			
			<div class="row">
				<div class="span5">
					<label>Comment</label>
					<textarea name="accident_photos[<?php echo $photo_id;?>][caption]" class="span6" rows="4"><?php echo $caption;?></textarea>
				</div>
			</div>
			
			
		</div>
		
		
		
		
			<div class="span2">
				
				
					<a onclick="return remove_image($(this));" rel="<?php echo $photo_id;?>" class="btn btn-danger" style="float:right; font-size:9px;"><i class="icon-trash icon-white"></i> <?php echo lang('remove');?></a>
				
				</div>
			
			</div>	
			
           
				
		

	<?php
	$stuff = ob_get_contents();

	ob_end_clean();
	
	echo replace_newline($stuff);
}


function add_image1($photo_id, $filename, $alt, $caption, $primary=false,$tab)
{

	ob_start();
	?>
	<div class="row gc_photo1" id="gc_photo1_<?php echo $photo_id;?>" style="background-color:#fff; border-bottom:1px solid #ddd; padding-bottom:20px; margin-bottom:20px;">
		<div class="span6">
			<input type="hidden" name="other_photos[<?php echo $photo_id;?>][filename2]" value="<?php echo $filename;?>"/>
			<img class="gc_thumbnail1" src="http://www.capesolution.tn/fuel_img/<?php echo $filename;?>" style="padding:5px; border:1px solid #ddd"/>
			
			<div class="row">
				<div class="span5">
					<label>Comment</label>
					<textarea name="other_photos[<?php echo $photo_id;?>][caption]" class="span6" rows="4"><?php echo $caption;?></textarea>
				</div>
			</div>
			
			
		</div>
		
		
			<div class="span2">
					<a onclick="return remove_image1($(this));" rel="<?php echo $photo_id;?>" class="btn btn-danger" style="float:right; font-size:9px;"><i class="icon-trash icon-white"></i> <?php echo lang('remove');?></a>
				
			</div>
			
			</div>	
				
		

	<?php
	$stuff = ob_get_contents();

	ob_end_clean();
	
	echo replace_newline($stuff);
}



function add_image2($photo_id, $filename, $alt, $caption, $primary=false,$tab)
{

	ob_start();
	?>
	<div class="row gc_photo2" id="gc_photo2_<?php echo $photo_id;?>" style="background-color:#fff; border-bottom:1px solid #ddd; padding-bottom:20px; margin-bottom:20px;">
		<div class="span6">
			<input type="hidden" name="counter_photos[<?php echo $photo_id;?>][filename3]" value="<?php echo $filename;?>"/>
			<img class="gc_thumbnail1" src="http://www.capesolution.tn/fuel_img/<?php echo $filename;?>" style="padding:5px; border:1px solid #ddd"/>
			
			<div class="row">
				<div class="span5">
					<label>Comment</label>
					<textarea name="counter_photos[<?php echo $photo_id;?>][caption]" class="span6" rows="4"><?php echo $caption;?></textarea>
				</div>
			</div>
			
			
		</div>
		
		
			<div class="span2">
					<a onclick="return remove_image2($(this));" rel="<?php echo $photo_id;?>" class="btn btn-danger" style="float:right; font-size:9px;"><i class="icon-trash icon-white"></i> <?php echo lang('remove');?></a>
				
				</div>
			
			</div>	
				
		

	<?php
	$stuff = ob_get_contents();

	ob_end_clean();
	
	echo replace_newline($stuff);
}

function hide_show($tab,$photo_id){
	$i=0;
	if (is_array($tab)){
		foreach($tab as $id=>$photo_obj){
		if ($id==$photo_id){
			$i=$i+1;
		}
	}
	}
	
	
	if($i!=0){
		return true;
	}
	
}

//this makes it easy to use the same code for initial generation of the form as well as javascript additions
function replace_newline($string) {
  return trim((string)str_replace(array("\r", "\r\n", "\n", "\t"), ' ', $string));
}

?>



<script type="text/javascript">
//<![CDATA[


function photos_sortable()
{
	$('#gc_photos').sortable({	
		handle : '.gc_thumbnail',
		items: '.gc_photo',
		axis: 'y',
		scroll: true
	});
}
//]]>
</script>









<?php include('footer.php');