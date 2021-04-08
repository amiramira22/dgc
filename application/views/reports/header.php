<!DOCTYPE html>
<html>
<?php $admin_url='admin/';

  $admin = $this -> admin_session -> userdata('admin');

		$first = $admin['firstname'];
		$last = $admin['lastname'];
		$access = $admin['access'];
		$full_name=$first.' '.$last;

 ?>
<!-- Mirrored from solutionportal.net/projects/materia/html/material.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 04 Dec 2015 17:55:23 GMT -->
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="description" content="Materia - Admin Template">
	<meta name="keywords" content="materia, webapp, admin, dashboard, template, ui">
	<meta name="author" content="solutionportal">
	<!-- <base href="/"> -->

	<title>Samsung mobile</title>
	
	<!-- Icons -->
	
	<link rel="stylesheet" href="<?php echo base_url('assets/fonts/ionicons/css/ionicons.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/fonts/font-awesome/css/font-awesome.min.css'); ?>">
<link href="<?php echo base_url('assets/css/jquery-ui.css'); ?>"  rel="stylesheet" type="text/css"/>
	<!-- Plugins -->
	<link rel="stylesheet" href="<?php echo base_url('assets/styles/plugins/c3.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/styles/plugins/waves.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/styles/plugins/perfect-scrollbar.css'); ?>">
	
	<!-- Css/Less Stylesheets -->
	<link rel="stylesheet" href="<?php echo base_url('assets/styles/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/styles/main.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/redactor.css'); ?>">

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/DataTables/media/css/jquery.dataTables.css'); ?>"/>
<link href="<?php echo base_url('assets/css/dataTables.tableTools.css'); ?>" rel="stylesheet" type="text/css"/>	
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/DataTables/extensions/Responsive/css/responsive.dataTables.css'); ?>"/>
<script src="<?php echo base_url('assets/plugins/jquery.min.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/redactor.min.js'); ?>"></script>
	 
 	<!-- <link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700,300' rel='stylesheet' type='text/css'> -->

	<!-- Match Media polyfill for IE9 -->
	<!--[if IE 9]> <script src="scripts/ie/matchMedia.js"></script>  <![endif]--> 
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
	
</head>
<body id="app" class="app off-canvas">
	
	<!-- header -->
	<header class="site-head" id="site-head">
		<ul class="list-unstyled left-elems">
			<!-- nav trigger/collapse -->
			<li>
				<a href="javascript:;" class="nav-trigger ion ion-drag"></a>
			</li>
			<!-- #end nav-trigger -->

			<!-- Search box -->
			<li>
				<div class="form-search hidden-xs">
					<form id="site-search" action="javascript:;">
						<input type="search" class="form-control" placeholder="Type here for search...">
						<button type="submit" class="ion ion-ios-search-strong"></button>
					</form>
				</div>
			</li>	<!-- #end search-box -->

			<!-- site-logo for mobile nav -->
			<li>
				<div class="site-logo visible-xs">
					<a href="javascript:;" class="text-uppercase h3">
						<span class="text">Samsung Mobile</span>
					</a>
				</div>
			</li> <!-- #end site-logo -->

			<!-- fullscreen -->
			<li class="fullscreen hidden-xs">
				<a href="javascript:;"><i class="ion ion-qr-scanner"></i></a>

			</li>	<!-- #end fullscreen -->

			<!-- notification drop -->
			<li class="notify-drop hidden-xs dropdown">
				<a href="javascript:;" data-toggle="dropdown">
					<i class="ion ion-speakerphone"></i>
					<span class="badge badge-danger badge-xs circle">3</span>
				</a>

				<div class="panel panel-default dropdown-menu">
					<div class="panel-heading">
						You have 3 new notifications 
						<a href="javascript:;" class="right btn btn-xs btn-pink mt-3">Show All</a>
					</div>
					<div class="panel-body">
						<ul class="list-unstyled">
							<li class="clearfix">
								<a href="javascript:;">
									<span class="ion ion-archive left bg-success"></span>
									<div class="desc">
										<strong>App downloaded</strong>
										<p class="small text-muted">1 min ago</p>
									</div>
								</a>
							</li>
							<li class="clearfix">
								<a href="javascript:;">
									<span class="ion ion-alert-circled left bg-danger"></span>
									<div class="desc">
										<strong>Application Error</strong>
										<p class="small text-muted">4 hours ago</p>
									</div>
								</a>
							</li>
							<li class="clearfix">
								<a href="javascript:;">
									<span class="ion ion-person left bg-info"></span>
									<div class="desc">
										<strong>New User Registered</strong>
										<p class="small text-muted">2 days ago</p>
									</div>
								</a>
							</li>
						</ul>
					</div>
				</div>

			</li>	<!-- #end notification drop -->

		</ul>
		<button type="button" class="btn btn-facebook ion ion-social-facebook icon" ></button>

		<ul class="list-unstyled right-elems">
			<!-- profile drop -->
			<li class="profile-drop hidden-xs dropdown">
				<a href="javascript:;" data-toggle="dropdown">
					<img src="<?php echo base_url('assets/images/user.jpg'); ?>" alt="admin-pic">
				</a>
				<ul class="dropdown-menu dropdown-menu-right">
					<li><a href="<?php echo site_url('admin/admin/form'); ?>"><span class="ion ion-person">&nbsp;&nbsp;</span>Profile</a></li>
					<li><a href="javascript:;"><span class="ion ion-settings">&nbsp;&nbsp;</span>Settings</a></li>
					<li class="divider"></li>
					<li><a href="javascript:;"><span class="ion ion-lock-combination">&nbsp;&nbsp;</span>Lock Screen</a></li>
					<li><a href="<?php echo site_url('admin/login/logout'); ?>"><span class="ion ion-power">&nbsp;&nbsp;</span>Logout</a></li>
				</ul>
			</li>
			<!-- #end profile-drop -->
<li class="floating-sidebar">
				<a href="javascript:;">
					<i class="ion ion-grid"></i>
				</a>
				<div class="sidebar-wrap" data-perfect-scrollbar>
					<ul class="nav nav-tabs nav-justified">
						<li class="active">
							<a href="#sidebar-chat-tab" data-toggle="tab">Chat</a>
						</li>
						<li>
							<a href="#sidebar-info-tab" data-toggle="tab">Info</a>
						</li>
					</ul> <!-- #end nav-tabs -->
					<div class="tab-content">
						<div class="tab-pane active" id="sidebar-chat-tab">
							<div class="chat-tab tab clearfix">
								<h5 class="title mt0 mb20">Online</h5>

								<?php $admins= $this -> auth ->get_admin_list();
								foreach ($admins as $admin) {
								 	# code...
								  ?>
								<div class="user-container mb15">
									<img src="<?php echo base_url('assets/images/user.jpg'); ?>" alt="">
									<div class="desc">
										<p class="mb0"><?php echo $admin ->lastname .' ';?></p>
										<p class="xsmall"><?php echo $admin->firstname;?> </p>

									</div>
									<span class="ion ion-record avail right on"></span>
								</div>

								<?php  }?>

								<h5 class="title mt0 mb20">Offline</h5>

								<div class="user-container mb15">
									<img src="images/sample/7.jpg" alt="">
									<div class="desc">
										<p class="mb0">Martin Xx.</p>
										<p class="xsmall"><span class="ion ion-location"></span>&nbsp;xxx, yyy</p>
									</div>
									<span class="ion ion-record avail right off"></span>
								</div>

								<div class="user-container mb15">
									<img src="images/sample/2.jpg" alt="">
									<div class="desc">
										<p class="mb0">Lorem Ipsum</p>
										<p class="xsmall"><span class="ion ion-location"></span>&nbsp;Virginia, USA</p>
									</div>
									<span class="ion ion-record avail right off"></span>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="sidebar-info-tab">
							<div class="info-tab tab clearfix">
								<h5 class="title mt0 mb20">Work in Progress</h5>
								<ul class="list-unstyled mb15 clearfix">
									<li>
										<div class="clearfix mb10">
											<small class="left">App Upload</small>
											<small class="right">80%</small>
										</div>
										<div class="progress-xs progress">
											<div class="progress-bar progress-bar-primary" style="width: 80%;"></div>
										</div>
									</li>
									<li>
										<div class="clearfix mb10">
											<small class="left">Creating Assets</small>
											<small class="right">50%</small>
										</div>
										<div class="progress-xs progress">
											<div class="progress-bar progress-bar-danger" style="width: 50%;"></div>
										</div>
									</li>
									<li>
										<div class="clearfix mb10">
											<small class="left">New UI 2.0</small>
											<small class="right">90%</small>
										</div>
										<div class="progress-xs progress">
											<div class="progress-bar progress-bar-success" style="width: 90%;"></div>
										</div>
									</li>
								</ul>

								<h5 class="title mt0 mb20">Settings</h5>
								<div class="clearfix mb15">
									<div class="left">
										<p>Show me online</p>
									</div>

									<div class="right">
										<div class="ui-toggle ui-toggle-success ui-toggle-xs">
											<label>
												<input type="checkbox" checked> 
												<span></span>
											</label>
										</div>
									</div>
								</div>

								<div class="clearfix mb15">
									<div class="left">
										<p>Notifications</p>
									</div>

									<div class="right">
										<div class="ui-toggle ui-toggle-success ui-toggle-xs">
											<label>
												<input type="checkbox"> 
												<span></span>
											</label>
										</div>
									</div>
								</div>

								<div class="clearfix mb15">
									<div class="left">
										<p>Enable History</p>
									</div>

									<div class="right">
										<div class="ui-toggle ui-toggle-success ui-toggle-xs">
											<label>
												<input type="checkbox" checked> 
												<span></span>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div> <!-- #end tab content -->
				</div> <!-- #end sidebar-wrap -->
			
			</li>

		</ul>

	</header>
	<!-- #end header -->


	<!-- main-container -->
	<div class="main-container clearfix">
		<!-- main-navigation -->
		<aside class="nav-wrap" id="site-nav" data-perfect-scrollbar>
			<div class="nav-head">
				<!-- site logo -->
				<a href="index-2.html" class="site-logo text-uppercase">
					<i class="ion ion-disc"></i>
					<span class="text">Samsung</span>
				</a>
			</div>

			<!-- Site nav (vertical) -->

			<nav class="site-nav clearfix" role="navigation">
				<div class="profile clearfix mb15">
					<img src="<?php echo base_url('assets/images/user.jpg'); ?>" alt="admin">
					<div class="group">
						<h5 class="name"><?php echo $full_name;?></h5>
						<small class="desig text-uppercase"><?php echo ' Access :'.$access;?></small>
					</div>
				</div>

				<!-- navigation -->
				<ul class="list-unstyled clearfix nav-list mb15">
					<li>
						<a href="<?php echo site_url('admin/dashboard'); ?>">
							<i class="ion ion-monitor"></i>
							<span class="text">Dashboard</span>
						</a>
					</li>
					<li>
						<a href="<?php echo site_url("admin/shortage_visits"); ?>">
							<i class="ion ion-arrow-graph-down-right"></i>
							<span class="text">Shortage visits</span>
						</a>
					</li>

					<li>
						<a href="<?php echo site_url('admin/new_models'); ?>">
							<i class="ion ion-iphone"></i>
							<span class="text">New models</span>
							<span class="badge badge-xs badge-primary">New</span>
						</a>
					</li>
					<li class="javascript:;">
						<a href="<?php echo site_url('admin/competitors_activities'); ?>">
							<i class="ion ion-pinpoint"></i>
							<span class="text">Competitors </span>
							<span class="badge badge-xs badge-primary">New</span>
						</a>
					</li>

					<li class="javascript:;">
						<a href="<?php echo site_url('admin/voice_dealers'); ?>">
							<i class="ion ion-volume-high"></i>
							<span class="text">Voice of dealers </span>
							
						</a>
					</li>
					
					<li>
						<a href="javascript:;">
							<i class="ion ion-clipboard"></i>
							<span class="text">Visits</span>
							<i class="arrow ion-chevron-left"></i>
						
						</a>
						<ul class="inner-drop list-unstyled">
							<li><a href="<?php echo site_url('admin/weekly_visits'); ?>">Weekly visits</a></li>
							<li><a href="<?php echo site_url('admin/monthly_visits'); ?>">Monthly visits</a></li>
							
							
						</ul>
					</li>

					<li>
						<a href="javascript:;">
							<i class="ion ion-ionic"></i>
							<span class="text">Reports</span>
							<i class="arrow ion-chevron-left"></i>
						
						</a>
						<ul class="inner-drop nested list-unstyled">
							<li><a href="icons.html">Font Awesome</a></li>
						</ul>
					</li>

                     <li>
						<a href="javascript:;">
							<i class="ion ion-camera"></i>
							<span class="text">Branding</span>
							<span class="badge badge-xs badge-primary">New</span>
							<i class="arrow ion-chevron-left"></i>
						</a>
						<ul class="inner-drop list-unstyled">
							<li><a href="<?php echo site_url('admin/pictures'); ?>">Branding</a></li>
							<li><a href="<?php echo site_url('admin/pictures/display'); ?>">Display</a></li>
						
						</ul>
					</li>
					<li>
						<a href="javascript:;">
							<i class="ion ion-pin"></i>
							<span class="text">Locations</span>
							<i class="arrow ion-chevron-left"></i>
						</a>
						<ul class="inner-drop list-unstyled">
							<li><a href="<?php echo site_url('admin/zones'); ?>">Zones</a></li>
							<li><a href="<?php echo site_url('admin/states'); ?>">States</a></li>
							<li><a href="<?php echo site_url('admin/cities'); ?>">Cities</a></li>
						</ul>
					</li>


					<li>
						<a href="javascript:;">
							<i class="ion ion-images "></i>
							<span class="text">POP</span>
							<span class="badge badge-xs badge-primary">New</span>
							<i class="arrow ion-chevron-left"></i>
						</a>
						<ul class="inner-drop list-unstyled">
							<li><a href="<?php echo site_url('admin/ilvs'); ?>">POP</a></li>
						</ul>
						<ul class="inner-drop list-unstyled">
							<li><a href="<?php echo site_url('admin/ilv_fos'); ?>">POP to FO</a></li>
						</ul>
						<ul class="inner-drop list-unstyled">
							<li><a href="<?php echo site_url('admin/ilv_dist'); ?>">POP distribuation</a></li>
						</ul>
					</li>
					
					<li>
						<a href="javascript:;">
							<i class="ion ion-gear-a"></i>
							<span class="text">Administration</span>
							<i class="arrow ion-chevron-left"></i>
						</a>
						<ul class="inner-drop list-unstyled">
							<li><a href="<?php echo site_url('admin/outlets'); ?>">Outlets</a></li>
							<li><a href="<?php echo site_url('admin/admin'); ?>">Users</a></li>
							<li><a href="<?php echo site_url('admin/brands'); ?>">Brands</a></li>
							<li><a href="<?php echo site_url('admin/models'); ?>">Models</a></li>
							<li><a href="<?php echo site_url('admin/categories'); ?>">Categories</a></li>
							<li><a href="<?php echo site_url('admin/ranges'); ?>">Ranges</a></li>
							<li><a href="<?php echo site_url('admin/price_ranges'); ?>">Price range</a></li>
							
						</ul>
					</li>
				</ul> <!-- #end navigation -->
			</nav>

			<!-- nav-foot -->
			<footer class="nav-foot">
				<p>2015 &copy; <span>MATERIA</span></p>
			</footer>

		</aside>
		<!-- #end main-navigation -->

		<!-- content-here -->
		<div class="content-container" id="content">
			<!-- material page -->
			<div class="page page-material">
				<ol class="breadcrumb breadcrumb-small">
					<li><?php echo $page_title;?></li>
					
				</ol>
				
				<div class="page-wrap">