
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>CAP FM-DMS<?php echo (isset($page_title))?' :: '.$page_title:''; ?></title>

<link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/css/bootstrap-responsive.min.css'); ?>" rel="stylesheet" type="text/css" />
<link type="text/css" href="<?php echo base_url('assets/css/jquery-ui.css'); ?>" rel="stylesheet" />
<link type="text/css" href="<?php echo base_url('assets/css/redactor.css'); ?>" rel="stylesheet" />
<link type="text/css" href="<?php echo base_url('assets/css/file-browser.css'); ?>" rel="stylesheet" />






<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/redactor.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/file-browser.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/custom.tables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/chart/highcharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/chart/exporting.js'); ?>"></script>




<?php if($this->auth->is_logged_in(false, false)):?>
	
<style type="text/css">
	body {
		margin-top: 50px;
	}

	@media (max-width: 979px) {
		body {
			margin-top: 0px;
		}
	}
	@media (min-width: 980px) {
		.nav-collapse.collapse {
			height: auto !important;
			overflow: visible !important;
		}
	}

	.nav-tabs li a {
		text-transform: uppercase;
		background-color: #f2f2f2;
		border-bottom: 1px solid #ddd;
		text-shadow: 0px 1px 0px #fff;
		filter: dropshadow(color=#fff, offx=0, offy=1);
		font-size: 12px;
		padding: 5px 8px;
	}

	.nav-tabs li a:hover {
		border: 1px solid #ddd;
		text-shadow: 0px 1px 0px #fff;
		filter: dropshadow(color=#fff, offx=0, offy=1);
	}

</style>
<script type="text/javascript">
	$(document).ready(function() {
		$('.datepicker').datepicker({
			dateFormat : 'yy-mm-dd'
		});

		$('.redactor').redactor({
			focus : true,
			plugins : ['fileBrowser']
		});
	}); 
</script>
<?php endif; ?>










</head>
<body>





<div class="container">
	

	
	
	<?php if(!empty($page_title)):?>
	<div class="page-header">
		<h1><?php echo $page_title; ?></h1>
	</div>
	<?php endif; ?>
	
	