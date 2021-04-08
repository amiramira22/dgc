<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Grid Listview - jQuery Mobile Demos</title>
<link href="<?php echo base_url('assets/jquery_mobile/jquery.mobile-1.3.2.min.css'); ?>" type="text/css" rel="stylesheet" />
<link href="<?php echo base_url('assets/jquery_mobile/jqm-demos.css'); ?>" type="text/css" rel="stylesheet" />

	<link rel="shortcut icon" href="../../favicon.ico">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700"
	<link href="<?php echo base_url('assets/jquery_mobile/grid-listview.css'); ?>" type="text/css" rel="stylesheet" />
	
	<script type="text/javascript" src="<?php echo base_url('assets/jquery_mobile/jquery.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/jquery_mobile/index.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/jquery_mobile/jquery.mobile-1.3.2.min.js'); ?>"></script>
</head>
<body>
<div data-role="page" data-theme="a" id="demo-page" class="my-page">

	<div data-role="header">
		<h1>News</h1>
		<a href="grid-listview.html" data-shadow="false" data-iconshadow="false" data-icon="arrow-l" data-iconpos="notext" data-rel="back" data-ajax="false">Back</a>
	</div><!-- /header -->
	
	<div data-role="content">
	
        <ul data-role="listview" data-inset="true">
        
        		<?php foreach ($brands as $brand):
		        $brand_id=$brand->id;
			    $brand_name=$brand->name;
		        ?>
        	<li><a href="<?php echo site_url($this -> config -> item('admin_folder') . '/weekly_visits/models/' . $visit_id.'/'.$brand_id); ?>">
            	<img src="../../_assets/img/apple.png">
            	<h2><?php echo $brand_name; ?></h2>
                <p><?php echo $brand_name; ?> Models</p>
                <p class="ui-li-aside">iOS</p>
            </a></li>
            <?php endforeach; ?>
        
        </ul>
                
    </div><!-- /content -->
    
    <div data-role="footer" data-theme="none">
        <h3>Responsive Grid Listview</h3>
    </div><!-- /footer -->

</div><!-- /page -->
</body>
</html>
