<?php

//bcm 
class Dashboard extends CI_Controller {

    var $connected_user_id = false;

    function __construct() {
        parent::__construct();

        if (!$this->auth->is_logged_in(false, false)) {

            redirect('login');
        }
        $this->load->helper(array('form', 'date'));
        $this->load->model(array('Outlet_model', 'Dashboard_model', 'Zone_model', 'Category_model', 'Brand_model', 'Channel_model'));
    }

    function index() {
        $data_header['page_title'] = 'Dashboard';
        $data_header['sub_title'] = 'Dashboard & statistics';

        $data['brands'] = $this->Brand_model->get_selected_brands();

        // maps data
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

            $content = '<b>Outlet name:</b> ' . $outlet->name 
                    . '</br><b>Zone:</b> ' . $outlet->zone 
                    . '</br><b>State:</b> ' . $outlet->state 
                    . ' </br><b>Field officer:</b> ' . $visit->name 
                    . ' </br></br><b>More details:</b> <a class="btn btn-xs red filter-submit margin-bottom" href="' . site_url('outlets/view/' . $outlet->id) . ' " data-toggle="tooltip" data-placement="top" title="Outlet details" target="_blank"><i class="icon-map"></i></a><a class="btn btn-xs green filter-submit margin-bottom" href="' . site_url('visits/report/' . $visit->id) . ' " data-toggle="tooltip" data-placement="top" title="Visit details" target="_blank"><i class="icon-map"></i></a>';
            $marker['infowindow_content'] = $content;

            if ($outlet->channel == 'Gemo') {
                $marker['icon'] = base_url('assets/img/red1.png');
            } else if ($outlet->channel == 'UHD') {
                $marker['icon'] = base_url('assets/img/blue1.png');
            } else if ($outlet->channel == 'MG') {
                $marker['icon'] = base_url('assets/img/green1.png');
            }
            /*else if ($outlet->channel == 'Traditional Trade') {
                $marker['icon'] = base_url('assets/img/black1.png');
            } else if ($outlet->channel == 'Uni Market') {
                $marker['icon'] = base_url('assets/img/green1.png');
            } else {
                $marker['icon'] = base_url('assets/img/orange1.png');
            }*/

            $marker['position'] = $visit->latitude . ',' . $visit->longitude;
            $this->googlemaps->add_marker($marker);
        }

        $data['map'] = $this->googlemaps->create_map();


        $this->load->view('header', $data_header);
        $this->load->view('dashboard/chart_header');
        $this->load->view('dashboard/dashboard', $data);
        $this->load->view('footer');
    }

    function load_indice() {
        // Count Active outlets
        $nb_active_outlets = $this->Outlet_model->count_active_outlets();
        $data['active_outlets'] = $nb_active_outlets;
        $data['all_outlets'] = $this->Outlet_model->count_outlets();

        // Count Daily visits
        $count_daily_visits = $this->Dashboard_model->count_daily_visits();
        $data['number_today_visits'] = $count_daily_visits;

        // Count Daily target
        $date_lib = date('l', strtotime('today'));
        $count_daily_target = $this->Dashboard_model->count_daily_target($date_lib);
        $data['count_daily_target'] = $count_daily_target;

        // Daily visits vs daily target
        if ($count_daily_target > 0) {
            $data['perc_visit_day'] = ($count_daily_visits / $count_daily_target) * 100;
        } else {
            $data['perc_visit_day'] = 0;
        }
        //monthly visits
        $nb_month = $this->Dashboard_model->count_month_visits();

        //traitement target monthly bcm
        $tab_nom_jour = array('Monday' => 2, 'Tuesday' => 3, 'Wednesday' => 4, 'Thursday' => 5, 'Friday' => 6, 'Saturday' => 7, 'Sunday' => 1);

        $total = 0;
        $fos = $this->auth->get_fo_list();
        $days[] = array();

        foreach ($fos as $fo) {
            //tt les outlets d'un fo 
            $outlets = $this->Outlet_model->get_outlets_by_id($fo->id);
            $target_meurch = 0;
            foreach ($outlets as $outlet) {

                $days = (json_decode($outlet->visit_day));

                if (!empty($days)) {
                    $s = 0;
                    foreach ($days as $day) {
                        $numeroJour = $tab_nom_jour[$day];

                        $J1 = 1;
                        $M1 = date('n');
                        $A1 = date('Y');

                        $J2 = date('t', mktime(0, 0, 0, $M1, 1, $A1));
                        $M2 = date('n');
                        $A2 = date('Y');

                        $nbJour = 0;
                        $Date1 = mktime(0, 0, 0, $M1, $J1, $A1);
                        $Date2 = mktime(0, 0, 0, $M2, $J2, $A2);
                        $nbJourDiff = ($Date2 - $Date1) / (60 * 60 * 24);
                        for ($i = 0; $i < $nbJourDiff + 1; $i++) {
                            $Date1 = mktime(0, 0, 0, $M1, $J1 + $i, $A1);
                            if (date("w", $Date1) == $numeroJour - 1)
                                $nbJour++;
                        }
                        //echo $nbJour;
                        //chaque meurch chaque outlet
                        $s = $s + $nbJour;

                        //chaque meurch ces outlet
                        $target_meurch = $target_meurch + $nbJour;

                        //tt les meurch 
                        $total = $total + $nbJour;
                    }
                }
            }
        }

        $nb_target_month = $total;
        $data['number_outlet_target_month'] = $nb_target_month;
        $data['number_visit_month'] = $nb_month;
        $perc_visit_month = number_format(($nb_month / $nb_target_month) * 100, 2, '.', '');
        $data['perc_visit_month'] = $perc_visit_month;

        $this->load->view('dashboard/load_indice', $data);
    }

    function load_oos_peer_channel() {
        $data['brands'] = $this->Brand_model->get_selected_brands();
        $date = date('Y-m-01');
        $data['oos_per_channel_data'] = $this->Dashboard_model->get_oos_per_channel(1, $date);
        $this->load->view('dashboard/load_oos_per_channel', $data);
    }

    function load_chart_oos_per_channel() {

        $brand_id = $_POST['brand_id'];
        $date = date('Y-m-01');
        $data['brand_id'] = $brand_id;
        $data['oos_per_channel_data'] = $this->Dashboard_model->get_oos_per_channel($brand_id, $date);
        $this->load->view('dashboard/chart_oos_per_channel', $data);
    }

    function load_oos_per_category() {
        $data['brands'] = $this->Brand_model->get_selected_brands();
        $date = date('Y-m-01');

        //val oos per categorie
        $oos_data = $this->Dashboard_model->get_oos_per_category(1, $date);

        $data_chart = array();
        $back = array();
        $front = array();
        $cpradius = 110;
        $cpinner = 105;

        //for label
        $cpt = 9.5;
        $lab = array();
        $labels = array();

        foreach ($oos_data as $row) {
$lab=array();
            $row_data = array();

            $row_data['brand'] = $row['category_name'];
            
            $total=$row['oos']+$row['av'];

            if($total!=0){
            $row_data['oos'] = number_format(($row['oos']/($row['oos']+$row['av']))*100, 2, '.', ' ');
            }
            
            
            if($total!=0){
                
                $front['color'] = "red";
           
            
            $front['startValue'] = 0;

            if ($row_data['oos'] < 0) {
                $front['endValue'] = 0;
            } else {
                $front['endValue'] = $row_data['oos'];
            }

            $front['radius'] = $cpradius . '%';
            $front['innerRadius'] = $cpinner . "%";
            $front['balloonText'] = $row_data['oos'] . '%';

            $back['color'] = "green";
            $back['startValue'] = 0;
            $back['endValue'] = 100;
            $back['radius'] = $cpradius . '%';
            $back['innerRadius'] = $cpinner . "%";
            $data_chart[] = $front;
            $data_chart[] = $back;
            $cpradius = $cpradius - 7;
            $cpinner = $cpinner - 7;


            $lab['text'] = $row['category_name'];
            $lab['x'] = '49%';
            $lab['y'] = $cpt . "%";
            $lab['size'] = 8;
            $lab['bold'] = true;
            if(($row['oos']+$row['av'])==0){
            $lab['color'] = "#000000";
                
            }
            $lab['align'] = "right";
            $cpt = $cpt + 2.8;
            
             }
            $labels[] = $lab;
        }
        $data['brand_cat_data_label'] = (json_encode($labels));
        $data['oos_data'] = json_encode(array_reverse($data_chart));
        $this->load->view('dashboard/load_oos_per_category', $data);
    }

    function load_chart_oos_per_category() {

        $brand_id = $_POST['brand_id'];
        $date = date('Y-m-01');
        $data['brand_categorie_id'] = $brand_id;

        $oos_data = $this->Dashboard_model->get_oos_per_category($brand_id, $date);

        $data_chart = array();
        $back = array();
        $front = array();
        $cpradius = 110;
        $cpinner = 105;

        //for label
        $cpt = 9.5;
        $lab = array();
        $labels = array();

        foreach ($oos_data as $row) {

            $row_data = array();

            $row_data['brand'] = $row['category_name'];

            $row_data['oos'] = number_format(($row['oos']), 2, '.', ' ');

            $front['color'] = "red";
            $front['startValue'] = 0;

            if ($row_data['oos'] < 0) {
                $front['endValue'] = 0;
            } else {
                $front['endValue'] = $row_data['oos'];
            }

            $front['radius'] = $cpradius . '%';
            $front['innerRadius'] = $cpinner . "%";
            $front['balloonText'] = $row_data['oos'] . '%';

            $back['color'] = "green";
            $back['startValue'] = 0;
            $back['endValue'] = 100;
            $back['radius'] = $cpradius . '%';
            $back['innerRadius'] = $cpinner . "%";
            $data_chart[] = $front;
            $data_chart[] = $back;
            $cpradius = $cpradius - 7;
            $cpinner = $cpinner - 7;


            $lab['text'] = $row['category_name'];
            $lab['x'] = '49%';
            $lab['y'] = $cpt . "%";
            $lab['size'] = 8;
            $lab['bold'] = true;
            //$lab['color'] = $cat->color;
            $lab['align'] = "right";
            $cpt = $cpt + 2.8;
            $labels[] = $lab;
        }
        $data['brand_cat_data_label'] = (json_encode($labels));
        $data['oos_data'] = json_encode(array_reverse($data_chart));

        $this->load->view('dashboard/chart_oos_per_category', $data);
    }

    function load_top_5_oos() {
        date_default_timezone_set('Europe/Amsterdam');
        $date = new DateTime();

        //*************************** les 4 weeks du mois courant **********************************************************
        $date->modify('this week');
        $date_this_week = $date->format('Y-m-d');

        $date->modify('this week -7 days');
        $date_last_week = $date->format('Y-m-d');

        $date->modify('this week -7 days');
        $date_last2_week = $date->format('Y-m-d');

        $date->modify('this week -7 days');
        $date_last3_week = $date->format('Y-m-d');
        //*******************************************************************************************************************
        $prod_this_week = $this->Dashboard_model->get_top_products_by_date($date_this_week);

        $data['date_this_week'] = $date_this_week;
        $data['date_last_week'] = $date_last_week;
        $data['date_last2_week'] = $date_last2_week;
        $data['date_last3_week'] = $date_last3_week;
        $data['prod_this_week'] = $prod_this_week;

        $this->load->view('dashboard/top_5_oos', $data);
    }

    //appel js
    function load_top_oos_products() {
        $date = $_POST['date'];
        $data['date'] = $date;
        $data['products'] = $this->Dashboard_model->get_top_products_by_date($date);
        $this->load->view('dashboard/dashboard_top_oos_ptoducts', $data);
    }

    //view more 
    function top_oos_all_product($date) {
        $data['page_title'] = "Top Oos Products";
        $data['sub_title'] = $date;
        $data['date'] = $date;
        $data['products'] = $this->Dashboard_model->get_top_products_by_date_all($date);

        $this->load->view('header', $data);
        $this->load->view('dashboard/chart_header');
        $this->load->view('dashboard/dashboard_top_oos_ptoducts_all', $data);
        $this->load->view('footer');
    }

    function load_stock_issue() {

        $data['brands'] = $this->Brand_model->get_selected_brands();
        $month = date('Y-m-01');
        $data['stock_issue_data'] = $this->Dashboard_model->get_stock_issue_data(1, $month);
        $this->load->view('dashboard/stock_issue', $data);
    }

    function load_chart_stock_issue() {

        $brand_id = $_POST['brand_id'];
        $data['brand_id'] = $brand_id;
        $month = date('Y-m-01');
        $data['stock_issue_data'] = $this->Dashboard_model->get_stock_issue_data($brand_id, $month);
        $this->load->view('dashboard/chart_stock_issue', $data);
    }

    function load_oos_trend() {
        $data['categories'] = array_reverse($this->Category_model->get_categories());
        $result = $this->Dashboard_model->get_data_oos_of_trend(1);
        $data['brands'] = $result['brands'];
        $data['result'] = json_encode($result['data']);
        $this->load->view('dashboard/load_oos_trend', $data);
    }

    function load_chart_oos_trend() {
        $category_id = $this->input->post('category_id');
        $result = $this->Dashboard_model->get_data_oos_of_trend($category_id);
        $data['category_id'] = $category_id;
        $data['result'] = json_encode($result['data']);
        $data['brands'] = $result['brands'];
        $this->load->view('dashboard/chart_oos_trend', $data);
    }

    function feeds() {

        $data['feeds'] = $this->Dashboard_model->get_monthly_remarks_visits();
        $this->load->view('dashboard/feeds', $data);
    }

    function daily_details() {

        $data['page_title'] = 'Dashboard';
        $data['sub_title'] = 'dashboard & daily details';

        if (isset($_POST['start_date'])) {
            $start_date = $this->input->post('start_date');
        } else {
            $start_date = date("Y-m-d");
        }
        $data['start_date'] = $start_date;

        if (isset($_POST['end_date'])) {
            $end_date = $this->input->post('end_date');
        } else {
            $end_date = date("Y-m-d");
        }
        $data['end_date'] = $end_date;

        $data['fos'] = $this->auth->get_fo_list();
        $data['channels'] = $this->Channel_model->get_active_channels();

        $this->load->view('daily_details', $data);
    }

    function monthly_details() {

        $data['page_title'] = 'Dashboard';
        $data['sub_title'] = 'dashboard & monthly details';
        if (isset($_POST['date'])) {
            $date = $this->input->post('date');
        } else {
            $date = date("Y-m-01");
        }
        $data['date'] = $date;
        $data['fos'] = $this->auth->get_fo_list();

        $this->load->view('header', $data);
        $this->load->view('dashboard/monthly_details', $data);
        $this->load->view('footer');
    }

    function outlets_details() {

        $data['page_title'] = 'Dashboard';
        $data['sub_title'] = 'dashboard & active outlets details';

        $data['channels'] = $this->Channel_model->get_active_channels();

        $data['outlets_by_channel'] = ($this->Dashboard_model->get_outlets_by_channels());
        $data['outlets_by_state'] = ($this->Dashboard_model->get_outlets_by_states());
        $data['states'] = $this->Outlet_model->get_states();
        $data['active_outlets'] = $this->Outlet_model->get_outlet_by_state_classe_details();
        $data['super_outlets'] = $this->Outlet_model->get_outlet_by_state_super_details();
        $this->load->view('header', $data);
        $this->load->view('dashboard/chart_header');
        $this->load->view('dashboard/active_outlets_details', $data);
        $this->load->view('footer');
    }

//********************************************************************************************************************************

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

    function CalculNbreJourDsMois($mois = 3) {

        //$tab_num_jour = array(0 => 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        //$tab_nom_jour = array('Monday' => 0, 'Tuesday' => 1, 'Wednesday' => 2, 'Thursday' => 3, 'Friday' => 4, 'Saturday' => 5, 'Sunday' => 6);
        $tab_nom_jour = array('Monday' => 2, 'Tuesday' => 3, 'Wednesday' => 4, 'Thursday' => 5, 'Friday' => 6, 'Saturday' => 1, 'Sunday' => 0);

        $total = 0;
        $fos = $this->auth->get_fo_list();
        $days[] = array();

        foreach ($fos as $fo) {
            //tt les outlets d'un fo 
            $outlets = $this->Outlet_model->get_outlets_by_id($fo->id);
            $target_meurch = 0;
            foreach ($outlets as $outlet) {

                $days = (json_decode($outlet->visit_day));

                //if (!empty($days)) {
                $s = 0;
                foreach ($days as $day) {
                    $numjour = $tab_nom_jour[$day];
                    //$mois = date('n');
                    //$mois = 1;
                    $annee = date('Y');
                    echo 'meurch' . $fo->id . '<br>';
                    echo 'outlet id***' . $outlet->id . '<br>';
                    echo 'day***' . $day . '<br>';
                    echo 'son num***' . $numjour . '<br>';
                    echo 'mois***' . $mois . '<br>';
                    echo 'anne****' . $annee . '<br>';

                    $jour = 1;
                    $SeqJour = '';
                    while (checkdate($mois, $jour ++, $annee) === true) {
                        $SeqJour = $SeqJour . strftime("%u", strtotime($annee . '-' . $mois . '-' . $jour));
                    }
                    $Nbjour = substr_count($SeqJour, $numjour);
                    $s = $s + $Nbjour;
                    $target_meurch = $target_meurch + $Nbjour;
                    $total = $total + $Nbjour;
                    echo 'nb de ce jour***' . $Nbjour . '<br>';
                    //echo 'admin_name :' . $fo->name . 'outlet_name :' . $outlet->name . '*****' . $day . 'num'.$numjour.'*******' . $Nbjour . '<br>';
                }
                echo '*************************total par outlet' . $outlet->id . '____' . ($s) . '********************************<br>';
                echo '*************************total target    ' . $outlet->id . '____' . ($total) . '********************************<br>';
                echo '*************************total par meurch' . $fo->id . 'outlet' . $outlet->id . '____' . ($target_meurch) . '********************************<br>';
                //}
            }
        }
    }

    function CalculNbreJourDsMoisNew($numjour, $mois = 4, $annee = 2018) {
        $jour = 1;
        $SeqJour = '';
        while (checkdate($mois, $jour ++, $annee) === true) {
            //%w De 0 (pour Dimanche) � 6 (pour Samedi)
            //%u De 1 (pour Lundi) � 7 (pour Dimanche)
            $SeqJour = $SeqJour . strftime("%u", strtotime($annee . '-' . $mois . '-' . $jour));
        }
        $Nbjour = substr_count($SeqJour, $numjour);
        echo $Nbjour;
    }

}
