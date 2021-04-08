<?php

class Dashboard extends CI_Controller {

    var $connected_user_id = false;

    function __construct() {
        parent::__construct();

        if (!$this->auth->is_logged_in(false, false)) {

            redirect('login');
        }
        $this->load->helper(array('form', 'date'));
        $this->load->model(array('Outlet_model', 'Dashboard_model', 'Zone_model', 'Category_model', 'Brand_model'));
    }

    function load_trend() {

        //print_r(json_encode($result['data']));
        $category_id = $_POST['category_id'];
        $result = $this->Dashboard_model->get_brand_multiple_date_data_for_shelf($category_id);
        $data['result'] = json_encode($result['data']);
        $data['brands'] = $result['brands'];

        $this->load->view('test', $data);
    }

    function load_last_div() {

        $data['feeds'] = $this->Dashboard_model->get_monthly_remarks_visits();
        $data['messages'] = $this->Dashboard_model->get_admins_messages();
        $this->load->library('googlemaps');

        $config['center'] = '35.0534864,9.2408933';
        $config['zoom'] = '7';
        $config['styles'] = array(array("name" => "Red Parks", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-30"))), array("featureType" => "poi.park", "stylers" => array(array("saturation" => "10"), array("hue" => "#990000"))))), array("name" => "Black Roads", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-70"))), array("featureType" => "road.arterial", "elementType" => "geometry", "stylers" => array(array("hue" => "#000000"))))), array("name" => "No Businesses", "definition" => array(array("featureType" => "poi.business", "elementType" => "labels", "stylers" => array(array("visibility" => "off"))))));
        $config['stylesAsMapTypes'] = true;
        $config['stylesAsMapTypesDefault'] = "Black Roads";
        $config['https'] = true;
        $this->googlemaps->initialize($config);


        $today_visits = $this->Dashboard_model->get_today_visits();



        foreach ($today_visits as $visit) {

            $outlet = $this->Outlet_model->get_outlet($visit->outlet_id);

            $content = '<b>Outlet name:</b> ' . $outlet->name . '</br><b>Zone:</b> ' . $outlet->zone . '</br><b>State:</b> ' . $outlet->state . ' </br><b>Field officer:</b> ' . $visit->name . ' </br></br><b>More details:</b> <a class="btn btn-xs red filter-submit margin-bottom" href="' . site_url('outlets/view/' . $outlet->id) . ' " data-toggle="tooltip" data-placement="top" title="Outlet details" target="_blank"><i class="icon-map"></i></a><a class="btn btn-xs green filter-submit margin-bottom" href="' . site_url('visits/report/' . $visit->id) . ' " data-toggle="tooltip" data-placement="top" title="Visit details" target="_blank"><i class="icon-map"></i></a>';
            $marker['infowindow_content'] = $content;

            if ($outlet->channel == 'Gemo') {
                $marker['icon'] = base_url('assets/img/red1.png');
            } else if ($outlet->channel == 'UHD') {
                $marker['icon'] = base_url('assets/img/blue1.png');
            } else if ($outlet->channel == 'MG') {
                $marker['icon'] = base_url('assets/img/yellow1.png');
            } else if ($outlet->channel == 'Traditional Trade') {
                $marker['icon'] = base_url('assets/img/black1.png');
            } else if ($outlet->channel == 'Uni Market') {
                $marker['icon'] = base_url('assets/img/green1.png');
            } else {
                $marker['icon'] = base_url('assets/img/orange1.png');
            }

            $marker['position'] = $visit->latitude . ',' . $visit->longitude;
            $this->googlemaps->add_marker($marker);
        }

        $data['map'] = $this->googlemaps->create_map();



        $this->load->view('last_div', $data);
    }

    function test() {
        $this->load->helper('date');

        $outlets = $this->Outlet_model->get_active_outlets();
        $array = daycount(strtotime("2016-11-01"));
        foreach ($outlets as $outlet) {
            $visit_days = json_decode($outlet->visit_day);

            $nb = 0;
            foreach ($visit_days as $day) {

                $nb = $nb + $array[$day];
            }
            print_r($nb);
            print_r('<br>');
        }
    }

    function raafet() {

        $std_first_day_of_month = date('Y-m-01');
        $res = $this->Dashboard_model->rr(4, $std_first_day_of_month);


        $categories = $this->Category_model->get_categories();
        $cpt = 10;
        $lab = array();
        $labels = array();
        foreach ($categories as $cat) {

            $lab['text'] = $cat->name;
            $lab['x'] = '49%';
            $lab['y'] = $cpt . "%";
            $lab['size'] = 15;
            $lab['bold'] = true;
            $lab['color'] = $cat->color;
            $lab['align'] = "right";

            $cpt = $cpt + 5;
            $labels[] = $lab;
        }

        print_r(json_encode($labels));
    }

    function load_top_oos_products() {


        $date = $_POST['date'];
        $data['date'] = $date;
        $data['products'] = $this->Dashboard_model->get_top_products_by_date($date);
        $this->load->view('dashboard_top_oos_ptoducts', $data);
    }

    function top_oos_bvm($date) {
        $data['page_title'] = "BCM top oos";
        $data['sub_title'] = $date;
        $data['date'] = $date;
        $data['products'] = $this->Dashboard_model->get_top_products_by_date_all($date);
        $this->load->view('dashboard_top_oos_ptoducts_all', $data);
    }

    function load_brand_data() {
        $brand_id = $_POST['brand_id'];
        $date = date('Y-m-01');
        $data['brand_id'] = $brand_id;
        $data['brand_data'] = $this->Dashboard_model->get_brand_json_data($brand_id, $date);



        $this->load->view('dashboard_brand_data', $data);
    }

    function load_brand_data_super() {
        $brand_id = $_POST['brand_id'];
        $date = date('Y-m-01');
        $data['brand_id'] = $brand_id;
        $data['brand_data'] = $this->Dashboard_model->get_brand_json_data_super($brand_id, $date);



        $this->load->view('dashboard_brand_data_super', $data);
    }

    function load_zone_data() {
        $brand_id = $_POST['brand_id'];
        $date = date('Y-m-01');
        $data['zone_id'] = $brand_id;
        $data['zone_data'] = $this->Dashboard_model->rr($brand_id, $date);
        
        $categories5 = $this->Dashboard_model->get_distinct_categories($brand_id, $date);



        // $categories5=$this->Category_model->get_categories();
        $cpt = 13.5;
        $lab = array();
        $labels = array();
        foreach ($categories5 as $cat) {

            $lab['text'] = $cat->category_name;
            $lab['x'] = '49%';
            $lab['y'] = $cpt . "%";
            $lab['size'] = 8;
            $lab['bold'] = true;
            $lab['color'] = $cat->color;
            $lab['align'] = "right";

            $cpt = $cpt + 2.8;
            $labels[] = $lab;
        }

        $data['zone_data_label'] = (json_encode($labels));

        $this->load->view('dashboard_zone_data', $data);
    }

    function load_brand_date() {

        $brand_id = $_POST['brand_id'];
        $data['brand_id'] = $brand_id;
        $month = date('Y-m-01');
        $data['brand_data2'] = $this->Dashboard_model->get_by_brand_json_data($brand_id, $month);
        $this->load->view('dashboard_brand_data_super', $data);
    }

    function t() {

        print_r($this->Dashboard_model->get_monthly_remarks_visits());
    }

    function index() {
        $data_header['page_title'] = 'Dashboard';
        $data_header['sub_title'] = 'Dashboard & statistics';
        $data = array();
        //retrieve input dates
        $std_first_day_of_month = date('Y-m-01');
        $std_today = date('Y-m-d');
        $data['feeds'] = $this->Dashboard_model->get_monthly_remarks_visits();
        $data['std_first_day_of_month'] = $std_first_day_of_month;
        $data['std_today'] = $std_today;
        $data['brands'] = $this->Brand_model->get_selected_brands();
        $data['zones'] = $this->Zone_model->get_selected_zones();
        $data['categories'] = array_reverse($this->Category_model->get_categories());
        $data['products'] = $this->Dashboard_model->get_top_products_by_category(7, $std_first_day_of_month);
        $month = date('Y-m-01');
        $data['brand_data2'] = $this->Dashboard_model->get_by_brand_json_data(18, $month);
        $data['messages'] = $this->Dashboard_model->get_admins_messages();
        // print_r($this->Dashboard_model->get_by_brand_json_data(1,'2017-06-01'));
        // die();

        date_default_timezone_set('Europe/Amsterdam');
        $date = new DateTime();



        $date->modify('this week');
        $date_this_week = $date->format('Y-m-d');

        $date->modify('this week -7 days');
        $date_last_week = $date->format('Y-m-d');

        $date->modify('this week -7 days');
        $date_last2_week = $date->format('Y-m-d');

        $date->modify('this week -7 days');
        $date_last3_week = $date->format('Y-m-d');

        $prod_this_week = $this->Dashboard_model->get_top_products_by_date($date_this_week);
        $prod_last_week = $this->Dashboard_model->get_top_products_by_date($date_last_week);
        $prod_last2_week = $this->Dashboard_model->get_top_products_by_date($date_last2_week);
        $prod_last3_week = $this->Dashboard_model->get_top_products_by_date($date_last3_week);

        $data['date_this_week'] = $date_this_week;
        $data['date_last_week'] = $date_last_week;
        $data['date_last2_week'] = $date_last2_week;
        $data['date_last3_week'] = $date_last3_week;

        $data['prod_this_week'] = $prod_this_week;
        // $data['prod_last_week'] =$prod_last_week;
        // $data['prod_last2_week'] =$prod_last2_week;
        // $data['prod_last3_week'] =$prod_last3_week;

        $date_lib = date('l', strtotime('today'));



        //indices
        // Count Active outlets
        $nb_active_outlets = $this->Outlet_model->count_active_outlets();
        $data['active_outlets'] = $nb_active_outlets;
        // Count Daily visits
        $count_daily_visits = $this->Dashboard_model->count_daily_visits();
        $data['number_today_visits'] = $count_daily_visits;
        // Count Daily target
        $count_daily_target = $this->Dashboard_model->count_daily_target($date_lib);
        $data['count_daily_target'] = $count_daily_target;
        // Daily visits vs daily target
        if ($count_daily_target > 0) {
            $data['perc_visit_day'] = ($count_daily_visits / $count_daily_target) * 100;
        } else {
            $data['perc_visit_day'] = 0;
        }





        $nb_month = $this->Dashboard_model->count_month_visits();
        
                 $target=0;
                 $fos=$this->auth->get_fo_list();
                foreach ($fos as $fo)
                {
                       $outlets=$this->Outlet_model->get_outlets_by_id($fo->id);
			$nb=0;
			foreach($outlets as $outlet)
			{
			$nb=$nb+sizeof(json_decode($outlet->visit_day));
			
			}
                        
                        $target=$target+($nb*4);
                    
                }
                
               
            
                
		$nb_target_month=$target;
        $data['number_outlet_target_month'] = $nb_target_month;
        $data['number_visit_month'] = $nb_month;
        $perc_visit_month = number_format(($nb_month / $nb_target_month) * 100, 2, '.', '');
        $data['perc_visit_month'] = $perc_visit_month;
        //brands stats
        $data['brand_data'] = $this->Dashboard_model->get_brand_json_data(18, $std_first_day_of_month);


        //$data['brand_data_super'] =$this->Dashboard_model->get_brand_json_data_super(1,$std_first_day_of_month);
        //zones stats
        $data['zone_data'] = $this->Dashboard_model->rr(18, $std_first_day_of_month);
        $categories5 = $this->Dashboard_model->get_distinct_categories(18, $std_first_day_of_month);
        $cpt = 13.5;
        $lab = array();
        $labels = array();
        foreach ($categories5 as $cat) {

            $lab['text'] = $cat->category_name;
            $lab['x'] = '49%';
            $lab['y'] = $cpt . "%";
            $lab['size'] = 8;
            $lab['bold'] = true;
            $lab['color'] = $cat->color;
            $lab['align'] = "right";

            $cpt = $cpt + 2.8;
            $labels[] = $lab;
        }

        $data['zone_data_label'] = (json_encode($labels));
        // //maps data
        $this->load->library('googlemaps');

        $config['center'] = '35.0534864,9.2408933';
        $config['zoom'] = '7';
        $config['styles'] = array(array("name" => "Red Parks", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-30"))), array("featureType" => "poi.park", "stylers" => array(array("saturation" => "10"), array("hue" => "#990000"))))), array("name" => "Black Roads", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-70"))), array("featureType" => "road.arterial", "elementType" => "geometry", "stylers" => array(array("hue" => "#000000"))))), array("name" => "No Businesses", "definition" => array(array("featureType" => "poi.business", "elementType" => "labels", "stylers" => array(array("visibility" => "off"))))));
        $config['stylesAsMapTypes'] = true;
        $config['stylesAsMapTypesDefault'] = "Black Roads";
        $config['https'] = true;
        $this->googlemaps->initialize($config);


        $today_visits = $this->Dashboard_model->get_today_visits();



        foreach ($today_visits as $visit) {

            $outlet = $this->Outlet_model->get_outlet($visit->outlet_id);

            $content = '<b>Outlet name:</b> ' . $outlet->name . '</br><b>Zone:</b> ' . $outlet->zone . '</br><b>State:</b> ' . $outlet->state . ' </br><b>Field officer:</b> ' . $visit->name . ' </br></br><b>More details:</b> <a class="btn btn-xs red filter-submit margin-bottom" href="' . site_url('outlets/view/' . $outlet->id) . ' " data-toggle="tooltip" data-placement="top" title="Outlet details" target="_blank"><i class="icon-map"></i></a><a class="btn btn-xs green filter-submit margin-bottom" href="' . site_url('visits/report/' . $visit->id) . ' " data-toggle="tooltip" data-placement="top" title="Visit details" target="_blank"><i class="icon-map"></i></a>';
            $marker['infowindow_content'] = $content;

            if ($outlet->channel == 'Gemo') {
                $marker['icon'] = base_url('assets/img/red1.png');
            } else if ($outlet->channel == 'UHD') {
                $marker['icon'] = base_url('assets/img/blue1.png');
            } else if ($outlet->channel == 'MG') {
                $marker['icon'] = base_url('assets/img/yellow1.png');
            } else if ($outlet->channel == 'Traditional Trade') {
                $marker['icon'] = base_url('assets/img/black1.png');
            } else if ($outlet->channel == 'Uni Market') {
                $marker['icon'] = base_url('assets/img/green1.png');
            } else {
                $marker['icon'] = base_url('assets/img/orange1.png');
            }

            $marker['position'] = $visit->latitude . ',' . $visit->longitude;
            $this->googlemaps->add_marker($marker);
        }

        $data['map'] = $this->googlemaps->create_map();


        // $result =$this->Dashboard_model->get_brand_multiple_date_data_for_shelf();
        // $data['result2'] = json_encode($result['data']);
        // $data['brands2'] = $result['brands'];


        $this->load->view('header', $data_header);
        $this->load->view('chart_header');
        $this->load->view('dashboard', $data);
        $this->load->view('footer');
    }

    function oussema() {
        $visits = $this->Dashboard_model->get_target_visit_by_admin(3, 'Friday');
        print_r($visits);
        print_r($visits);
    }

    function index3() {

        $data['page_title'] = 'Dashboard';


        $data['sub_title'] = 'Dashboard & statistics';

        $data['categories'] = array_reverse($this->Category_model->get_categories());

        $name_date = date('l', strtotime('today'));
        $number_outlet_target = $this->Outlet_model->count_outlet_by_date($name_date);
        $data['number_outlet_target'] = $number_outlet_target;

        $number_today_visits = $this->Dashboard_model->count_today_visits();
        $data['number_today_visits'] = $number_today_visits;



        $data['perc_visit_day'] = ($number_today_visits / $number_outlet_target) * 100;
        $data['top_oos'] = json_decode($this->Dashboard_model->get_top5_oss_henkel());
        $avs = json_decode($this->Dashboard_model->get_top5_av_henkel());
        $data['top_av'] = $avs;

        $data['top_oos_competitor'] = json_decode($this->Dashboard_model->get_top5_oss_competitor());
        $avs = json_decode($this->Dashboard_model->get_top5_av_competitor());
        $data['top_av_competitor'] = $avs;


        $data['active_outlets'] = $this->Outlet_model->count_active_outlets();

        $data['zones'] = $this->Zone_model->get_zones_for_charts();
        $data['brands'] = $this->Dashboard_model->get_brands_for_charts();
        $data['zones_av'] = $this->Zone_model->get_zones_av();
        $data['zones_oos'] = $this->Zone_model->get_zones_oos();
        $data['perc_oos'] = $this->Dashboard_model->get_visit_oos();
        $data['perc_av'] = $this->Dashboard_model->get_visit_av();

        $data['perc_av_per_zone'] = $this->Dashboard_model->get_visit_av_per_zone('Grand Tunis');
        $data['perc_oos_per_zone'] = $this->Dashboard_model->get_visit_oos_per_zone('Grand Tunis');

        $data['products'] = $this->Dashboard_model->get_top_products_by_category(7);

        $this->load->library('googlemaps');

        $config['center'] = '35.0534864,9.2408933';
        $config['zoom'] = '6';
        $config['styles'] = array(array("name" => "Red Parks", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-30"))), array("featureType" => "poi.park", "stylers" => array(array("saturation" => "10"), array("hue" => "#990000"))))), array("name" => "Black Roads", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-70"))), array("featureType" => "road.arterial", "elementType" => "geometry", "stylers" => array(array("hue" => "#000000"))))), array("name" => "No Businesses", "definition" => array(array("featureType" => "poi.business", "elementType" => "labels", "stylers" => array(array("visibility" => "off"))))));
        $config['stylesAsMapTypes'] = true;
        $config['stylesAsMapTypesDefault'] = "Black Roads";
        $config['https'] = true;
        $this->googlemaps->initialize($config);


        $today_visits = $this->Dashboard_model->get_today_visits();



        foreach ($today_visits as $visit) {

            $outlet = $this->Outlet_model->get_outlet($visit->outlet_id);

            $content = '<b>Outlet name:</b> ' . $outlet->name . '</br><b>Zone:</b> ' . $outlet->zone . '</br><b>State:</b> ' . $outlet->state . ' </br><b>Field officer:</b> ' . $visit->name . ' </br></br><b>More details:</b> <a class="btn btn-xs red filter-submit margin-bottom" href="' . site_url('outlets/view/' . $outlet->id) . ' " data-toggle="tooltip" data-placement="top" title="More details" target="_blank"><i class="icon-map"></i></a>';
            $marker['infowindow_content'] = $content;

            if ($outlet->classe == 'A+') {
                $marker['icon'] = base_url('assets/img/red1.png');
            } else if ($outlet->classe == 'A') {
                $marker['icon'] = base_url('assets/img/blue1.png');
            } else {
                $marker['icon'] = base_url('assets/img/yellow1.png');
            }








            $marker['position'] = $visit->latitude . ',' . $visit->longitude;
            $this->googlemaps->add_marker($marker);
        }



        $data['map'] = $this->googlemaps->create_map();






        $this->load->view('dashboard2', $data);
    }

    function load_capbon_av() {

        $data['zones'] = $this->Zone_model->get_zones_for_charts();
        $data['brands'] = $this->Dashboard_model->get_brands_for_charts();


        $data['perc_av_per_zone'] = $this->Dashboard_model->get_visit_av_per_zone('Cap Bon');
        $data['perc_oos_per_zone'] = $this->Dashboard_model->get_visit_oos_per_zone('Cap Bon');


        $this->load->view('load_capbon_av', $data);
    }

    function load_unilever() {

        $data['zones'] = $this->Zone_model->get_zones_for_charts();


        $data['perc_oos'] = $this->Dashboard_model->get_visit_oos_by_brand(5);
        $data['perc_av'] = $this->Dashboard_model->get_visit_av_by_brand(5);





        $this->load->view('load_unilever', $data);
    }

    function load_sodet() {

        $data['zones'] = $this->Zone_model->get_zones_for_charts();


        $data['perc_oos'] = $this->Dashboard_model->get_visit_oos_by_brand(6);
        $data['perc_av'] = $this->Dashboard_model->get_visit_av_by_brand(6);





        $this->load->view('load_sodet', $data);
    }

    function load_judy() {

        $data['zones'] = $this->Zone_model->get_zones_for_charts();


        $data['perc_oos'] = $this->Dashboard_model->get_visit_oos_by_brand(9);
        $data['perc_av'] = $this->Dashboard_model->get_visit_av_by_brand(9);





        $this->load->view('load_judy', $data);
    }

    function load_pg() {

        $data['zones'] = $this->Zone_model->get_zones_for_charts();


        $data['perc_oos'] = $this->Dashboard_model->get_visit_oos_by_brand(4);
        $data['perc_av'] = $this->Dashboard_model->get_visit_av_by_brand(4);





        $this->load->view('load_pg', $data);
    }

    function load_north_av() {

        $data['zones'] = $this->Zone_model->get_zones_for_charts();
        $data['brands'] = $this->Dashboard_model->get_brands_for_charts();


        $data['perc_av_per_zone'] = $this->Dashboard_model->get_visit_av_per_zone('North');
        $data['perc_oos_per_zone'] = $this->Dashboard_model->get_visit_oos_per_zone('North');


        $this->load->view('load_north_av', $data);
    }

    function outlets_details() {

        $data['page_title'] = 'Dashboard';
        $data['sub_title'] = 'dashboard & active outlets details';

        $data['outlets_by_state'] = ($this->Dashboard_model->get_outlets_by_states());
        $data['outlets_by_channel'] = ($this->Dashboard_model->get_outlets_by_channels());

        $data['states'] = $this->Outlet_model->get_states();
        $data['active_outlets'] = $this->Outlet_model->get_outlet_by_state_classe_details();
        $data['super_outlets'] = $this->Outlet_model->get_outlet_by_state_super_details();

        $this->load->view('active_outlets_details', $data);
    }

    function daily_details() {
 $start_date = $this->input->post('start_date');
        $data['start_date'] = $start_date;
        
        $end_date = $this->input->post('end_date');
        $data['end_date'] = $end_date;

     
   
        $data['page_title'] = 'Dashboard';
        $data['sub_title'] = 'dashboard & daily details';
        
       

        $data['fos'] = $this->auth->get_fo_list();


        $this->load->view('daily_details', $data);
    }

    function monthly_details() {

        $data['page_title'] = 'Dashboard';
        $data['sub_title'] = 'dashboard & monthly details';
        $date = $this->input->post('date');
        $data['date'] = $date;
        $data['fos'] = $this->auth->get_fo_list();


        $this->load->view('monthly_details', $data);
    }

    function index1() {

        $data['page_title'] = 'Dashboard';
        $data['sub_title'] = 'dashboard & statistics';


        /*         * **         Date Manipulation         ** */

        // Current day datetime
        $current_date_time = new DateTime();
        //Current day Standard
        $current_day_std = $current_date_time->format('Y-m-d');

        //Current day Format FR
        $current_day_fr = reverse_format($current_day_std);

        /*         * **         First day of week         ** */
        $first_day_week_std = firstDayOf('week', $current_date_time);
        //$first_day_week_std='2016-08-08';
        $first_day_last_week_std = date('Y-m-d', strtotime("-7 day", strtotime("$first_day_week_std")));



        if ($this->auth->check_access('Samsung') || $this->auth->check_access('Admin')) {
            /*             * *   Indices   ** */
            $data['weekly_indices'] = $this->Dashboard_model->get_weekly_indices($first_day_week_std);

            /*             * *   Brand Stats   ** */
            $data['brand_weekly_sales'] = $this->Dashboard_model->get_brand_weekly_sales($first_day_last_week_std);



            /*             * *   Top Models Stats   ** */
            //$data['count_all_weekly_models'] =$this->Dashboard_model->get_count_all_weekly_models($first_day_week_std);
            $data['shortage_top_models'] = $this->Dashboard_model->get_shortage_weekly_top_models($first_day_last_week_std, 5);

            /*             * *   Trend Stats   ** */
            $data['trend_weekly_amount'] = $this->Dashboard_model->get_trend_weekly_amount(10);

            /*             * *   spark Stats   ** */
            $data['ws_spark'] = json_decode($this->Dashboard_model->get_trend_weekly_sales(10));
            $data['amt_spark'] = json_decode($this->Dashboard_model->get_trend_weekly_amount(10));
        } else {
            /*             * *  Total Number shortage by sfo ** */

            //	$data['total_visits_sfo'] =$this->Dashboard_model->get_total_visits($this->connected_user_id);
            $data['shortage_sfo'] = $this->Dashboard_model->get_weekly_shortage_by_sfo($first_day_week_std, $this->connected_user_id);

            /*             * *	Weekly image by sfo  ** */

            $data['wekly_images'] = $this->Dashboard_model->get_weekly_images_by_sfo($first_day_week_std, $this->connected_user_id);


            $data['total_weekly_visits_sfo'] = $this->Dashboard_model->get_total_weekly_visits($this->connected_user_id, $first_day_week_std);
            /*             * *    Active Outlets     ** */

            $data['total_outlet_actif_sfo'] = $this->Dashboard_model->get_total_outlet_actif_by_user($this->connected_user_id);
        }






        $data['current_day_std'] = $current_day_std;
        $data['first_day_week_std'] = $first_day_week_std;
        $data['first_day_last_week_std'] = $first_day_last_week_std;


        $this->load->view('dashboard', $data);
    }

}
