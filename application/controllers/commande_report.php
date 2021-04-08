<?php

//controller

class Commande_report extends CI_Controller {

    var $connected_user_id = false;

    function __construct() {
        parent::__construct();

        if (!$this->auth->is_logged_in(false, false)) {

            redirect('login');
        }
        $admin = $this->session->userdata('admin');
        $this->connected_user_id = $admin['id'];
        $this->load->model(array('Admin_model', 'Channel_model', 'Product_group_model', 'Product_model', 'Cluster_model',
            'Sub_category_model', 'Visit_model', 'Outlet_model', 'Brand_model', 'Report_model',
            'State_model', 'Zone_model', 'Category_model', 'Dashboard_model', 'Commande_model'));

        $this->load->helper(array('formatting', 'date'));

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->lang->load('reports');
    }

    function maps() {

        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "Maps";

        $date = ($this->input->post("date")) ? $this->input->post("date") : date('Y-m-d');
        $data['date'] = $date;
        // maps data
        $this->load->library('googlemaps');
        $config['center'] = '35.0534864,9.2408933';
        $config['zoom'] = '7';
        $config['styles'] = array(array("name" => "Red Parks", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-30"))), array("featureType" => "poi.park", "stylers" => array(array("saturation" => "10"), array("hue" => "#990000"))))), array("name" => "Black Roads", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-70"))), array("featureType" => "road.arterial", "elementType" => "geometry", "stylers" => array(array("hue" => "#000000"))))), array("name" => "No Businesses", "definition" => array(array("featureType" => "poi.business", "elementType" => "labels", "stylers" => array(array("visibility" => "off"))))));
        $config['stylesAsMapTypes'] = true;
        $config['stylesAsMapTypesDefault'] = "Black Roads";
        $config['https'] = true;
        $this->googlemaps->initialize($config);

        $today_visits = $this->Commande_model->get_today_visits($date);

        foreach ($today_visits as $visit) {

            $outlet = $this->Outlet_model->get_outlet($visit->outlet_id);

            $content = '<b>Outlet name:</b> ' . $outlet->name
                    . '</br><b>Zone:</b> ' . $outlet->zone
                    . '</br><b>State:</b> ' . $outlet->state
                    . '</br><b>Field officer:</b> ' . $visit->name
                    . '</br></br><b>oos details:</b> <a class="btn btn-xs red filter-submit margin-bottom" href="' . site_url('commande_report/oos_details/' . $visit->id) . ' " data-toggle="tooltip" data-placement="top" title="Outlet details" target="_blank"><i class="icon-map"></i></a>';

            $marker['infowindow_content'] = $content;

            if ($outlet->channel == 'Gemo') {
                $marker['icon'] = base_url('assets/img/red1.png');
            } else if ($outlet->channel == 'UHD') {
                $marker['icon'] = base_url('assets/img/blue1.png');
            } else if ($outlet->channel == 'MG') {
                $marker['icon'] = base_url('assets/img/green1.png');
            } /*else if ($outlet->channel == 'Traditional Trade') {
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
        $this->load->view('commande/maps', $data);
        $this->load->view('footer');
    }

    function oos_details($visit_id = false) {
        //$this->output->cache(3600);

        $this->load->helper(array('form', 'date', 'number'));
        $this->load->library('form_validation');

        $data['page_title'] = 'OOS Details';
        $data['old'] = '';
        $data['id_rayon'] = '';

        $data['models'] = $this->Commande_model->get_oos_detail($visit_id);
        $data['pictures'] = $this->Commande_model->get_pictures($visit_id);
        $data['id'] = $visit_id;
        $outlet_name = $this->Outlet_model->get_outlet_name($data['pictures']->outlet_id);
        $data['sub_title'] = "Outlet : " . $outlet_name . " | " . reverse_format($data['pictures']->date);


        $this->load->view('header', $data);
        $this->load->view('commande/oos_details', $data);
        $this->load->view('footer');
    }

    function historique_cmd_per_fo() {
        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "historique commande per FO";


        $data['admins'] = $this->auth->get_fo_list();


        $start_date = ($this->input->post("start_date")) ? $this->input->post("start_date") : date('Y-m-d');
        $end_date = ($this->input->post("end_date")) ? $this->input->post("end_date") : date('Y-m-d');
        $fo_id = ($this->input->post("fo_id")) ? $this->input->post("fo_id") : -1;



        $data['fo_id'] = $fo_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        if ($fo_id != -1)
            $data['data'] = $this->Commande_model->get_historique_cmd_per_fo($fo_id, $start_date, $end_date);

        $this->load->view('header', $data_header);
        $this->load->view('commande/historique_cmd_per_fo', $data);
        $this->load->view('footer');
    }

    function historique_cmd_per_pos() {
        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "historique commande per POS";

        $data['channels'] = $this->Channel_model->get_channels();
        $data['zones'] = $this->Zone_model->get_zones();
        $data['outlets'] = $this->Outlet_model->get_outlets();

        $start_date = ($this->input->post("start_date")) ? $this->input->post("start_date") : date('Y-m-d');
        $end_date = ($this->input->post("end_date")) ? $this->input->post("end_date") : date('Y-m-d');
        $channel_id = ($this->input->post("channel_id")) ? $this->input->post("channel_id") : -1;
        $zone_id = ($this->input->post("zone_id")) ? $this->input->post("zone_id") : -1;
        $outlet_id = ($this->input->post("outlet_id")) ? $this->input->post("outlet_id") : -1;

        $data['channel_id'] = $channel_id;
        $data['zone_id'] = $zone_id;
        $data['outlet_id'] = $outlet_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        if ($outlet_id != -1)
            $data['data'] = $this->Commande_model->get_historique_cmd_per_pos($outlet_id, $start_date, $end_date);

        $this->load->view('header', $data_header);
        $this->load->view('commande/historique_cmd_per_pos', $data);
        $this->load->view('footer');
    }

    //bcm filtre branding 
    function get_outlet_by_zone_channel() {
        $zone_id = $this->input->post("zone_id");
        $channel_id = $this->input->post("channel_id");

        header('Content-Type: application/x-json; charset=utf-8');
        $outlets = array();
        $outlets[-1] = 'Please Select';
        foreach ($this->Commande_model->get_outlet_by_zone_channel($zone_id, $channel_id) as $outlet) {
            $outlets[$outlet->id] = $outlet->name;
        }
        echo(json_encode($outlets));
    }

    function nbr_cde_per_fo() {
        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "Nombre de commande per FO";

        $start_date = ($this->input->post("start_date")) ? $this->input->post("start_date") : date('Y-m-d');
        $end_date = ($this->input->post("end_date")) ? $this->input->post("end_date") : date('Y-m-d');


        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $data['data'] = $this->Commande_model->get_nbr_cde_per_fo($start_date, $end_date);

        $this->load->view('header', $data_header);
        $this->load->view('commande/nbr_cde_per_fo', $data);
        $this->load->view('footer');
    }

}
