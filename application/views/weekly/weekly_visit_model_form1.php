
<link href="<?php echo base_url('assets/jquery_mobile/jquery.mobile-1.0.1.min.css'); ?>" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo base_url('assets/jquery_mobile/jquery-1.8.3.min.js'); ?>"></script>
 <script>
    $(document).bind('mobileinit',function(){

        $.extend(  $.mobile , {
                    ajaxFormsEnabled: false,
            ajaxLinksEnabled: false,
            ajaxEnabled: false

        });
    });

</script>

<script type="text/javascript" src="<?php echo base_url('assets/jquery_mobile/jquery.mobile-1.3.2.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/jquery_mobile/jqm-spinbox.js'); ?>"></script>


		<style type="text/css">
			.padsidestwenty { 
				padding-left: 20px; 
				padding-right: 20px; 
			}
			pre {
			    overflow-x: auto; /* Use horizontal scroller if needed; for Firefox 2, not needed in Firefox 3 */
			    white-space: pre-wrap; /* css-3 */
			    white-space: -moz-pre-wrap !important; /* Mozilla, since 1999 */
			    white-space: -pre-wrap; /* Opera 4-6 */
			    white-space: -o-pre-wrap; /* Opera 7 */
			    word-wrap: break-word; /* Internet Explorer 5.5+ */
			}
		</style>
	
		<script type='text/javascript'>
		
			function showLeft(leftdiv, rightdiv, dontslideright, dontslideleft) {
				$('#splitviewcontainer').simplesplitview('showLeft', leftdiv, rightdiv, dontslideright, dontslideleft);
			}
			
			function showRight(rightdiv, dontslideright) {
				$('#splitviewcontainer').simplesplitview('showRight', rightdiv, dontslideright);
			}
			
			function navHome() {
				$('#splitviewcontainer').simplesplitview('navHome');
			}
			
			function navBack() {
				$('#splitviewcontainer').simplesplitview('navBack');
			}
			
			$('#mainpage').live('pageshow', function(event) {
				document.getElementById('splitviewcontainer').padding = 44;
				$('#splitviewcontainer').simplesplitview();
			});
	
		</script>
		
		
		

<style >
 #models {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	width: 100%;
	border-collapse: collapse;
}
#models td, #customers th {
	font-size: 1em;
	border: 1px solid #0c4da2;
	padding: 3px 7px 2px 7px;
}
#models th {
	font-size: 1.1em;
	text-align: center;
	padding-top: 5px;
	padding-bottom: 4px;
	background-color: #0c4da2;
	color: #ffffff;
}
#models tr.alt td {
	color: #000000;
	background-color: #EAF2D3;
}
</style>
		



<?php echo form_open($this->config->item('admin_folder').'/weekly_visits/bulk_save/'.$id, array('id'=>'bulk_form'));?>
	
	
	
	
	<div data-role="page" id="mainpage" data-title=<?php echo $page_title;?> data-theme="b">
	
	<div data-role="header" data-position="fixed" data-fullscreen="true">
		<h1 id='appmaintitle'><?php echo $page_title;?></h1>
		
	</div><!-- /header -->
	
	
	<button class="btn" href="#"><i class="icon-ok"></i> <?php echo lang('bulk_save');?></button>
	
	<div data-role="content"   data-theme="d">

	
	
     
        	
        	
        	<table id="models">
		<thead>
			<tr>
		
			    <th width='20%'>Brand</th>
				<th>Model</th>
				<th>Shelf Share</th>
				<th>W/S</th>
				<th>Price</th>
				
				
			</tr>
		</thead>
		<tbody>
		<?php //echo (count($modern_models) < 1)?'<tr><td style="text-align:center;" colspan="7">'.lang('no_models').'</td></tr>':''?>
	<?php 
	$models = $this -> Weekly_model_model -> get_models_by_brand($visit_id,$brand_id);
	
	
	foreach ($models as $model):
	
	             $shelf=$model->shelf;
				 $ws=$model->ws;
				 $price=$model->price;
			
	 ?>
			<tr>
			
			<td><?php echo $brand_name;?></td>
			<td><?php echo $this->Model_model->get_model_name($model->model_id);?>
				
				<?php echo form_input(array(
				'type'=>'hidden',	
				'name'=>'model['.$model->id.'][model_id]',
				'value'=>form_decode($model->model_id)
				 ));?>
				
			</td>
			<td>
				<center>
				<div data-role="fieldcontain">
				<?php echo form_input(array(
				'id'=>'spin',
				'type'=>'text',	
				'data-role'=>"spinbox",	
				'name'=>'model['.$model->id.'][shelf]',
				'data-options'=>'{"type":"horizontal"}',
				'value'=>form_decode($shelf),
				'min'=>"0", 
				'max'=>"100" ));?>
				</div>
				</center>
            </td>
            
            
            
            <td>
            	<center>
				<div data-role="fieldcontain">
				<?php echo form_input(array(
				'id'=>'spin',
				'type'=>'text',		
				'data-role'=>"spinbox",
				'name'=>'model['.$model->id.'][ws]',
				'data-options'=>'{"type":"horizontal"}',
				'value'=>form_decode($ws),
				'min'=>"0", 
				'max'=>"100" ));?>
				</div>
				</center>
            </td>
            
            <td>
            	<center>
					<div data-role="fieldcontain">
				<?php echo form_input(array(
				'id'=>'spin',
				'type'=>'text',	
				'data-role'=>"spinbox",	
				'name'=>'model['.$model->id.'][price]',
				'data-options'=>'{"type":"horizontal"}',
				'value'=>form_decode($price),
				'min'=>"0", 
				'max'=>"10000" ));?>
				</div>
				</center>
            </td>
			
			
			</tr>
	<?php endforeach; ?>
		</tbody>
	</table>
	
        		
        	
        	
	
<button class="btn" href="#"><i class="icon-ok"></i> <?php echo lang('bulk_save');?></button>
	
	
	
		
	
	
	
	
</form>
<?php //include('footer.php'); ?>