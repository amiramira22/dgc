<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title><?php echo APP_NAME; ?> System | Login </title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<link rel="icon" href="<?php echo base_url('assets/img/logo-cap.png'); ?>" width="50px">
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/plugins/simple-line-icons/simple-line-icons.min.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css'); ?>" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="<?php echo base_url('assets/css/login.css'); ?>" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="<?php echo base_url('assets/css/components.css'); ?>" id="style_components" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/css/plugins.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/css/layout.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/css/themes/grey.css'); ?>" rel="stylesheet" type="text/css" id="style_color"/>
<link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="<?php echo base_url('favicon.ico'); ?>"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
	
	
	
	<div class="row">
	<div class="col-md-4">
	</div>
	<div class="col-md-4">
	<?php
	//lets have the flashdata overright "$message" if it exists
	if ($this -> session -> flashdata('message')) {
		$message = $this -> session -> flashdata('message');
	}

	if ($this -> session -> flashdata('error')) {
		$error = $this -> session -> flashdata('error');
	}

	if (function_exists('validation_errors') && validation_errors() != '') {
		$error = validation_errors();
	}
	?>
	
	<div id="js_error_container" class="alert alert-error" style="display:none;"> 
		<p id="js_error"></p>
	</div>
	
	<div id="js_note_container" class="alert alert-note" style="display:none;">
		
	</div>
	
	<?php if (!empty($message)): ?>
		<div class="alert alert-success">
			<a class="close" data-dismiss="alert">×</a>
			<?php echo $message; ?>
		</div>
	<?php endif; ?>

	<?php if (!empty($error)): ?>
		<div class="alert alert-danger">
			<a class="close" data-dismiss="alert">×</a>
			<?php echo $error; ?>
		</div>
	<?php endif; ?>
</div>	
</div>
	
	
	
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGO -->

<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
	
	<div style="text-align:center" >
	<a href="index.html">
	<img src="<?php echo base_url('assets/img/'.LOGO ); ?>" style="height: 100px;" alt=""/>
	</a>
</div>
</br>
	
	
	<!-- BEGIN LOGIN FORM -->
	<?php $attributes = array('class' => 'login-form');?>

		<?php echo form_open('login', $attributes) ?>
		<div class="form-title">
			<span class="form-title"></span>
			<span class="form-subtitle">Please login.</span>
		</div>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>
			Enter any username and password. </span>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">E-mail / Username</label>
	        <?php echo form_input(array('name'=>'email', 'class'=>'form-control form-control-solid placeholder-no-fix' ,'placeholder'=>'E-mail / Username')); ?>

		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
		    <?php echo form_password(array('name'=>'password', 'class'=>'form-control form-control-solid placeholder-no-fix' ,'placeholder'=>'Password')); ?>
		</div>
		<div class="form-actions">
			<input class="btn btn-danger btn-block uppercase" type="submit" value="Login"/>
			<input type="hidden" value="<?php echo $redirect; ?>" name="redirect"/>
		    <input type="hidden" value="submitted" name="submitted"/>
		</div>
		<div class="form-actions">
			<div class="pull-left">
				<label class="rememberme check">
				<?php echo form_checkbox(array('name'=>'remember', 'value'=>'true'))?> Remember me </label>
				
				
			
			</div>
			
		</div>
		<div class="login-options">
	       Copyright ©  <?php echo date("Y"); ?> BeeSoft . Tous droits reserves.
		</div>
		
	<?php echo  form_close(); ?>
	<!-- END LOGIN FORM -->

	
</div>
<div class="copyright hide">
	  <?php echo date("Y"); ?> © Henkel HCS System</div>
<!-- END LOGIN -->
<script src="<?php echo base_url('assets/plugins/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.blockui.min.js'); ?>" type="text/javascript"></script>



<!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
<!-- END PAGE LEVEL PLUGINS -->
<script src="<?php echo base_url('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>" type="text/javascript"></script>

<!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="<?php echo base_url('assets/plugins/datatables/datatables.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js'); ?>" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        
  <script src="<?php echo base_url('assets/js/table-datatables-responsive.min.js'); ?>" type="text/javascript"></script>



 <!-- BEGIN RevolutionSlider -->
  
    <script src="<?php echo base_url('assets/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js'); ?>" type="text/javascript"></script> 
    <script src="<?php echo base_url('assets/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.tools.min.js'); ?>" type="text/javascript"></script> 
    <script src="<?php echo base_url('assets/js/revo-slider-init.js'); ?>" type="text/javascript"></script>
    <!-- END RevolutionSlider -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/app.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/layout.min.js'); ?>" type="text/javascript"></script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>