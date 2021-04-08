
<!DOCTYPE html>
<?php //bcm header ?>

<html lang="en">

    <?php
    //bcm
    $admin = $this->session->userdata('admin');

    $admin_id = $admin['id'];
    $full_name = $admin['name'];
    $access = $admin['access'];
    $email = $admin['email'];
    ?>
    <head>
        <meta charset="utf-8" />
        <title><?php echo APP_NAME; ?>   System  </title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <link rel="icon" href="<?php echo base_url('assets/img/logo-cap.png'); ?>" width="50px">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link rel="shortcut icon" href="/blob/628652/05f4704c6f9dbfae8daabc1e4872ca18/favicon/ar-gcc.ico">
        <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/plugins/simple-line-icons/simple-line-icons.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->

        <link href="<?php echo base_url('assets/plugins/icheck/skins/all.css'); ?>" rel="stylesheet" type="text/css" />


        <link href="<?php echo base_url('assets/plugins/datatables/datatables.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css'); ?>" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/bootstrap-select/bootstrap-select.min.css'); ?>"/>
        <link rel="s0tylesheet" type="text/css" href="<?php echo base_url('assets/plugins/select2/select2.css'); ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/jquery-multi-select/css/multi-select.css'); ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/select2/css/select2-bootstrap.min.css'); ?>"/>



        <link href="<?php echo base_url('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css'); ?>" rel="stylesheet" type="text/css" />

        <link href="<?php echo base_url('assets/plugins/dropzone/dropzone.min.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo base_url('assets/css/components-md.css'); ?>" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url('assets/css/plugins-md.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php echo base_url('assets/css/layout.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/css/themes/henkel.css'); ?>" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?php echo base_url('assets/css/custom.min.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->

        <link rel="stylesheet" href="<?php echo base_url('assets/plugins/jquery-ui/jquery-ui.css'); ?>">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/js/jquery.gomap-1.3.3.min.js'); ?>" type="text/javascript"></script>
        <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>


        <script>
            $(document).ready(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>




        <style>

@media (min-width: 992px){
.page-sidebar {
    width: 250px;
    }
}

@media (min-width: 992px){
.page-content-wrapper .page-content {
    margin-left: 250px;
}
}

            .scrollbar{ 
                overflow-y:scroll;
                /* max-height: 700px;
                  
                 min-width: 200px;
                 margin-right: 50px; */
            }
            @media screen and (max-width: 1200px) {

                .dt-buttons{
                    display: none;
                }
            }
        </style>

    </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-md">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="index.html">
                        <img src="<?php echo base_url('assets/img/logo-cap-hd.png'); ?>" style="margin-top:5%;width:120px;"/> </a>

                    <div class="menu-toggler sidebar-toggler">
                        <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                    </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN PAGE ACTIONS -->
                <!-- DOC: Remove "hide" class to enable the page header actions -->

                <!-- END PAGE ACTIONS -->
                <!-- BEGIN PAGE TOP -->
                <div class="page-top" >
                    <!-- BEGIN HEADER SEARCH BOX -->

                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <!-- BEGIN NOTIFICATION DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->


                            <!-- END NOTIFICATION DROPDOWN -->
                            <!-- BEGIN INBOX DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                            <!-- END INBOX DROPDOWN -->

                            <!-- END TODO DROPDOWN -->
                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-user">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"  data-hover="dropdown" data-close-others="true">
                                    <img alt="" class="img-circle" src="<?php echo base_url('assets/img/'.LOGO); ?>"  />
                                    <span class="username username-hide-on-mobile"><?php echo $full_name; ?></span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">

                                    <li>
                                        <a href="<?php echo site_url('login/logout'); ?>">
                                            <i class="icon-key"></i> Log Out </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                            <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END PAGE TOP -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <style>
                .nav2{
                    max-height: 500px;
                    display: block;
                    overflow-y:scroll; 
                }
            </style>
            <div class="page-sidebar-wrapper" >
                <!-- END SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse"  >
                    <!-- BEGIN SIDEBAR MENU -->
                    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <!-- <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-hover-submenu "  -->




                    <?php
                    if ($email == 'user-henkel@mail.com') {
                        ?>
                        <style>

                            .page-sidebar {
                                width: 210px !important;
                                float: left;
                                position: relative;
                                margin-right: -100%;
                            }
                        </style>
                        <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-compact "
                            data-keep-expanded="false" data-auto-scroll="false" data-slide-speed="200">
                            <li class="nav-item ">
                                <a href="<?php echo site_url('commande_report/maps'); ?>" class="nav-link nav-toggle">
                                    <i class="icon-home"></i>
                                    <span class="title">Maps</span>
                                    <span class=""></span>
                                </a>
                            </li>
                            <li class="nav-item  ">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-briefcase "></i>
                                    <span class="title">Historique Cde </span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('commande_report/historique_cmd_per_fo'); ?>" target="_blank" class="nav-link ">
                                            <span class="title">Fo</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('commande_report/historique_cmd_per_pos'); ?>" target="_blank" class="nav-link ">
                                            <span class="title">POS</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item ">
                                <a href="<?php echo site_url('commande_report/nbr_cde_per_fo'); ?>" target="_blank" class="nav-link nav-toggle">
                                    <i class="icon-home"></i>
                                    <span class="title">Nombre de Cde</span>
                                    <span class=""></span>
                                </a>
                            </li>


                        </ul>
                        <?php
                    } else if ($this->auth->check_access('Admin')) {
                        ?>
                        <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-compact "
                            data-keep-expanded="false" data-auto-scroll="false" data-slide-speed="200">
                            <li class="nav-item ">
                                <a href="<?php echo site_url('dashboard'); ?>" class="nav-link nav-toggle">
                                    <i class="icon-home"></i>

                                    <span class="title"> Dashboard</span>

                                    <span class=""></span>
                                </a>

                            </li>

                            <li class="nav-item  ">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-briefcase "></i>
                                    <span class="title">Visits</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('visits'); ?>" class="nav-link ">
                                            <span class="title">Daily</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/daily_visit_report'); ?>" target="_blank" class="nav-link ">
                                            <span class="title">Data</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/pos_data'); ?>" target="_blank" class="nav-link ">
                                            <span class="title">POS</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/tracking_oos'); ?>" class="nav-link ">
                                            <span class="title">Tracking oos</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item  ">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">Numeric Distribution</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/stock_issues_report'); ?>" class="nav-link ">
                                            <span class="title">Stats</span>
                                        </a>
                                    </li>
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/pos_stock_issues_report'); ?>" class="nav-link ">
                                            <span class="title">POS</span>
                                        </a>
                                    </li>
                                     <li class="nav-item  ">
                                        <a href="<?php echo site_url('outlets/numeric_distribution'); ?>" class="nav-link ">
                                            <span class="title">Map</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item  ">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="glyphicon glyphicon-align-left"></i>
                                    <span class="title">Shelf Share</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/shelf_share_report'); ?>" class="nav-link ">
                                            <span class="title">Stats</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/pos_shelf_share_report'); ?>" class="nav-link ">
                                            <span class="title">POS</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item ">
                                <a href="<?php echo site_url('reports/branding'); ?>" class="nav-link nav-toggle">
                                    <i class="glyphicon glyphicon-picture"></i>

                                    <span class="title"> Branding</span>

                                    <span class=""></span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a href="<?php echo site_url('reports/price_monitoring_report'); ?>" class="nav-link nav-toggle">
                                    <i class="glyphicon glyphicon-usd"></i>

                                    <span class="title"> Price monitoring</span>

                                    <span class=""></span>
                                </a>
                            </li>
                           
                            
                               <li class="nav-item  ">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="title">Outlets</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('outlets'); ?>" class="nav-link ">
                                            <span class="title">Outlets DB</span>
                                        </a>
                                    </li>
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('outlets/geo'); ?>" target="_blank" class="nav-link ">
                                            <span class="title"> Geolocalisation</span>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </li>

                         

                            <li class="nav-item ">
                                <a href="<?php echo site_url('reports/store_album'); ?>" class="nav-link nav-toggle">
                                    <i class="glyphicon glyphicon-camera"></i>

                                    <span class="title"> Store Album</span>

                                    <span class=""></span>
                                </a>

                            </li>

                            <li class="nav-item ">
                                <a href="<?php echo site_url('admin/field_officers'); ?>" class="nav-link nav-toggle">
                                    <i class="glyphicon glyphicon-user"></i>

                                    <span class="title"> FO Profils</span>

                                    <span class=""></span>
                                </a>
                            </li>

                            <li class="nav-item  ">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="glyphicon glyphicon-plane"></i>
                                    <span class="title">Fo Performance</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item ">
                                        <a href="<?php echo site_url('reports/fo_performance'); ?>" class="nav-link nav-toggle">
                                            <span class="title">Performance Report</span>
                                        </a>
                                    </li>
                                    <li class="nav-item ">
                                        <a href="<?php echo site_url('reports/Routing_trend'); ?>" class="nav-link nav-toggle">
                                            <span class="title">Routing Trend</span>
                                        </a>
                                    </li>
                                    <li class="nav-item ">
                                        <a href="<?php echo site_url('reports/routing_survey'); ?>" class="nav-link nav-toggle">
                                            <span class="title">Routing Survey</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>


                            <li class="nav-item  ">
                                <a href="?p=" class="nav-link nav-toggle">
                                    <i class="fa fa-info-circle"></i>
                                    <span class="title">FO Information</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/fo_information_input'); ?>" class="nav-link ">
                                            <i class="fa fa-arrow-right"></i>
                                            <span class="title">input</span>
                                        </a>
                                    </li>
                                    <li class="nav-item  ">
                                        <a href="<?php
                                        //echo site_url('reports/pos_oos_report');
                                        echo site_url('reports/fo_information_output');
                                        ?>" class="nav-link ">
                                            <i class="fa fa-arrow-left"></i>
                                            <span class="title">output</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            <li class="nav-item ">
                                <a href="<?php echo site_url('messages'); ?>" class="nav-link nav-toggle">
                                    <i class="icon-bubbles"></i>

                                    <span class="title"> Messages</span>

                                    <span class=""></span>
                                </a>
                            </li>

                            <li class="nav-item  ">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-settings"></i>
                                    <span class="title">Administration</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu ">
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('upload'); ?>" class="nav-link ">
                                            <span class="title">List Of APK</span>
                                        </a>
                                    </li>
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('products/ha_outlets'); ?>" class="nav-link ">
                                            <span class="title">HA Products</span>
                                        </a>
                                    </li> 
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/tracking_visits_report'); ?>" class="nav-link ">
                                            <span class="title">Tracking visits</span>
                                        </a>
                                    </li> 

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('users'); ?>" class="nav-link ">
                                            <span class="title">Users</span>
                                        </a>
                                    </li> 

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('outlets'); ?>" class="nav-link ">
                                            <span class="title">Outlets</span>
                                        </a>
                                    </li> 

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('brands'); ?>" class="nav-link ">
                                            <span class="title">Brands</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('targets'); ?>" class="nav-link ">
                                            <span class="title">Targets</span>
                                        </a>
                                    </li>
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('products'); ?>" class="nav-link ">
                                            <span class="title">Products</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('product_groups'); ?>" class="nav-link ">
                                            <span class="title">Product groups</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('clusters'); ?>" class="nav-link ">
                                            <span class="title">Clustering</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('sub_categories'); ?>" class="nav-link ">
                                            <span class="title">Sub Categories</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('categories'); ?>" class="nav-link ">
                                            <span class="title">Categories</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('admin/position'); ?>" class="nav-link ">
                                            <span class="title">Geolocalisation</span>
                                        </a>
                                    </li>
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/picture_outlet_report'); ?>" class="nav-link ">
                                            <span class="title">Store Album</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="?p=" class="nav-link nav-toggle">
                                            <span class="title">Locations</span>
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="sub-menu">
                                            <li class="nav-item  ">
                                                <a href="<?php echo site_url('zones'); ?>" class="nav-link ">
                                                    <span class="title">Zones</span>
                                                </a>
                                            </li>
                                            <li class="nav-item  ">
                                                <a href="<?php echo site_url('states'); ?>" class="nav-link ">
                                                    <span class="title">States</span>
                                                </a>
                                            </li>

                                            <li class="nav-item  ">
                                                <a href="<?php echo site_url('cities'); ?>" class="nav-link ">
                                                    <span class="title">Cities</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </li>
                                </ul>
                            </li>

                        </ul>

                        <?php
                    } else if ($this->auth->check_access('Field Officer')) {
                        ?>

                        <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-compact "
                            data-keep-expanded="false" data-auto-scroll="false" data-slide-speed="200">
                            <li class="nav-item ">
                                <a href="<?php echo site_url('dashboard'); ?>" class="nav-link nav-toggle">
                                    <i class="icon-home"></i>

                                    <span class="title"> Dashboard</span>

                                    <span class=""></span>
                                </a>

                            </li>

                            <li class="nav-item  ">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-briefcase "></i>
                                    <span class="title">Visits</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('visits'); ?>" class="nav-link ">
                                            <span class="title">Daily</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/daily_visit_report'); ?>" target="_blank" class="nav-link ">
                                            <span class="title">Data</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/pos_data'); ?>" target="_blank" class="nav-link ">
                                            <span class="title">POS</span>
                                        </a>
                                    </li>




                                </ul>
                            </li>
                            <li class="nav-item ">
                                <a href="<?php echo site_url('requests'); ?>" class="nav-link nav-toggle">
                                    <i class="icon-basket"></i>

                                    <span class="title"> Request Stock</span>

                                    <span class=""></span>
                                </a>

                            </li>

                            <li class="nav-item ">
                                <a href="<?php echo site_url('new_models'); ?>" class="nav-link nav-toggle">
                                    <i class="icon-screen-smartphone"></i>

                                    <span class="title"> New Models</span>

                                    <span class=""></span>
                                </a>

                            </li>

                            <li class="nav-item ">
                                <a href="<?php echo site_url('competitor_ads/'); ?>" class="nav-link nav-toggle">
                                    <i class="icon-layers"></i>

                                    <span class="title">Competitor Ads</span>

                                    <span class=""></span>
                                </a>

                            </li>


                            <li class="nav-item ">
                                <a href="<?php echo site_url('messages'); ?>" class="nav-link nav-toggle">
                                    <i class="icon-bubbles"></i>

                                    <span class="title"> Messages</span>

                                    <span class=""></span>
                                </a>

                            </li>	
                            <li class="nav-item ">
                                <a href="<?php echo site_url('upload'); ?>" class="nav-link nav-toggle">
                                    <i class="icon-bubbles"></i>

                                    <span class="title">List Of APK</span>

                                    <span class=""></span>
                                </a>

                            </li>
                        <?php } else if ($this->auth->check_access('Henkel')) { ?>

                            <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-compact "
                                data-keep-expanded="false" data-auto-scroll="false" data-slide-speed="200">
                                <li class="nav-item ">
                                    <a href="<?php echo site_url('dashboard'); ?>" class="nav-link nav-toggle">
                                        <i class="icon-home"></i>

                                        <span class="title"> Dashboard</span>

                                        <span class=""></span>
                                    </a>

                                </li>

                                <li class="nav-item  ">
                                    <a href="javascript:;" class="nav-link nav-toggle">
                                        <i class="icon-briefcase "></i>
                                        <span class="title">Visits</span>
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="nav-item  ">
                                            <a href="<?php echo site_url('visits'); ?>" class="nav-link ">
                                                <span class="title">Daily</span>
                                            </a>
                                        </li>

                                        <li class="nav-item  ">
                                            <a href="<?php echo site_url('reports/daily_visit_report'); ?>" target="_blank" class="nav-link ">
                                                <span class="title">Data</span>
                                            </a>
                                        </li>

                                        <li class="nav-item  ">
                                            <a href="<?php echo site_url('reports/pos_data'); ?>" target="_blank" class="nav-link ">
                                                <span class="title">POS</span>
                                            </a>
                                        </li>

                                        <li class="nav-item  ">
                                            <a href="<?php echo site_url('reports/tracking_oos'); ?>" class="nav-link ">
                                                <span class="title">Tracking oos</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                     <li class="nav-item  ">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">Numeric Distribution</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/stock_issues_report'); ?>" class="nav-link ">
                                            <span class="title">Stats</span>
                                        </a>
                                    </li>
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/pos_stock_issues_report'); ?>" class="nav-link ">
                                            <span class="title">POS</span>
                                        </a>
                                    </li>
                                     <li class="nav-item  ">
                                        <a href="<?php echo site_url('outlets/numeric_distribution'); ?>" class="nav-link ">
                                            <span class="title">Map</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item  ">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="glyphicon glyphicon-align-left"></i>
                                    <span class="title">Shelf Share</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/shelf_share_report'); ?>" class="nav-link ">
                                            <span class="title">Stats</span>
                                        </a>
                                    </li>

                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('reports/pos_shelf_share_report'); ?>" class="nav-link ">
                                            <span class="title">POS</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item ">
                                <a href="<?php echo site_url('reports/branding'); ?>" class="nav-link nav-toggle">
                                    <i class="glyphicon glyphicon-picture"></i>

                                    <span class="title"> Branding</span>

                                    <span class=""></span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a href="<?php echo site_url('reports/price_monitoring_report'); ?>" class="nav-link nav-toggle">
                                    <i class="glyphicon glyphicon-usd"></i>

                                    <span class="title"> Price monitoring</span>

                                    <span class=""></span>
                                </a>
                            </li>
                           
                            
                               <li class="nav-item  ">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="title">Outlets</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('outlets'); ?>" class="nav-link ">
                                            <span class="title">Outlets DB</span>
                                        </a>
                                    </li>
                                    <li class="nav-item  ">
                                        <a href="<?php echo site_url('outlets/geo'); ?>" target="_blank" class="nav-link ">
                                            <span class="title"> Geolocalisation</span>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </li>
                                <li class="nav-item ">
                                    <a href="<?php echo site_url('reports/store_album'); ?>" class="nav-link nav-toggle">
                                        <i class="glyphicon glyphicon-camera"></i>

                                        <span class="title"> Store Album</span>

                                        <span class=""></span>
                                    </a>

                                </li>

                                <li class="nav-item ">
                                    <a href="<?php echo site_url('admin/field_officers'); ?>" class="nav-link nav-toggle">
                                        <i class="glyphicon glyphicon-user"></i>

                                        <span class="title"> FO Profils</span>

                                        <span class=""></span>
                                    </a>
                                </li>

                                <li class="nav-item ">
                                    <a href="<?php echo site_url('messages'); ?>" class="nav-link nav-toggle">
                                        <i class="icon-bubbles"></i>

                                        <span class="title"> Messages</span>

                                        <span class=""></span>
                                    </a>
                                </li>

                            </ul>

                        <?php } ?>

                        <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <div class="hidden-sm hidden-md hidden-xs">
                    <h3 class="page-title" style="margin-left: 15px;"> <?php echo $page_title; ?> 
                        <small> <?php echo $sub_title; ?>  </small>
                    </h3>
                    </div>
                    
                    
                    <div class="page-bar">
                        <ul class="page-breadcrumb"> 
                            <li>
                                <i class="icon-home"></i>
                                <a href="#">Home</a>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            <li>
                                <span><?php echo $page_title; ?></span>
                            </li>
                        </ul>

                    </div>
                    
                    
                    
                    <div class="row">

                        <div class="col-md-12">
                            <?php
                            //lets have the flashdata overright "$message" if it exists
                            if ($this->session->flashdata('message')) {
                                $message = $this->session->flashdata('message');
                            }

                            if ($this->session->flashdata('error')) {
                                $error = $this->session->flashdata('error');
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