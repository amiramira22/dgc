<?php

//bcmf
class Outlets extends CI_Controller
{

    //this is used when editing or adding a outlet
    var $outlet_id = false;

    function __construct()
    {
        parent::__construct();
        if (!$this->auth->is_logged_in(false, false)) {

            redirect($this->config->item('admin_folder') . '/login');
        }

        // $this->auth->check_access('Admin', true);
        $this->load->model(array('Outlet_model', 'Zone_model', 'State_model', 'Channel_model', 'Sub_channel_model',
            'Category_model', 'Sub_category_model', 'Cluster_model', 'Product_group_model', 'Product_model'));
        $this->load->library('Auth');
        $this->load->helper('formatting_helper');
        $this->lang->load('outlet');
        $this->load->library('pagination');
        $this->load->helper(array('form', 'date'));
    }

    function index()
    {
        //we're going to use flash data and redirect() after form submissions to stop people from refreshing and duplicating submissions
        //$this->session->set_flashdata('message', 'this is our message');

        $data['page_title'] = 'Outlets';
        $data['sub_title'] = 'List Outlets';

        //pagination settings
        $config['base_url'] = site_url('outlets/index');
        $config['total_rows'] = $this->Outlet_model->count_outlets();
        $config['per_page'] = "20";
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = 8;
        //floor($choice);
        $data['admins'] = $this->auth->get_fo_list();

        //config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        //call the model function to get the department data
        if ($this->auth->check_access('Henkel')) {
            $data['outlets'] = $this->Outlet_model->get_active_outlets($config["per_page"], $data['page'], 'id', 'DESC');
        } else {
            $data['outlets'] = $this->Outlet_model->get_outlets($config["per_page"], $data['page'], 'id', 'DESC');
        }

        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('outlets', $data);
    }

    function form($id = false)
    {

        $this->load->helper('form');
        $this->load->library('form_validation');


        $data['page_title'] = 'Outlets';
        $data['sub_title'] = 'Outlet Form';

        $config['upload_path'] = 'uploads/outlet';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);

        $data['zones'] = $this->Zone_model->get_zones();
        $data['channels'] = $this->Channel_model->get_channels();
        $data['sub_channels'] = $this->Sub_channel_model->get_sub_channels();
        $data['states'] = $this->State_model->get_states();
        $data['admins'] = $this->auth->get_fo_list();
        $data['responsibles'] = $this->auth->get_responsible_list();

        //default values are empty if the outlet is new
        $data['id'] = false;
        $data['code'] = '';
        $data['name'] = '';
        $data['sfo_id'] = '';
        $data['zone'] = '';
        $data['channel'] = '';
        $data['channel_id'] = '';
        $data['state'] = '';
        $data['source'] = '';
        $data['adress'] = '';
        $data['contact_pdv'] = '';
        $data['contact'] = '';
        $data['activity'] = '';
        $data['caisse_number'] = '';
        $data['classe'] = '';
        $data['visit_day'] = '';
        $data['visit_days'] = '';
        $data['delivery_days'] = '';
        $data['photos'] = '';
        $data['sub_channel'] = '';
        $data['sub_channel_id'] = '';
        $data['longitude'] = '';
        $data['latitude'] = '';
        $data['week_of_work'] = '';
        $data['responsible_id'] = '';
        $data['super_market_project'] = '';
        $data['active'] = false;


        if ($id) {
            $this->outlet_id = $id;
            $outlet = $this->Outlet_model->get_outlet($id);
            //if the outlet does not exist, redirect them to the outlet list with an error
            if (!$outlet) {
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect($this->config->item('admin_folder') . '/outlets');
            }
            $data['sub_title'] = 'Outlet Form |' . $outlet->name;

            //set values to db values
            $data['id'] = $outlet->id;
            $data['code'] = 'HCM' . str_pad($outlet->id, 3, '0', STR_PAD_LEFT);
            $data['name'] = $outlet->name;
            $data['sfo_id'] = $outlet->admin_id;
            $data['zone'] = $outlet->zone_id;
            $data['channel_id'] = $outlet->channel_id;
            $data['state'] = $outlet->state_id;

            $data['sub_channel_id'] = $outlet->sub_channel_id;
            $data['adress'] = $outlet->adress;
            $data['contact_pdv'] = $outlet->contact_pdv;
            $data['contact'] = $outlet->contact;
            //	$data['activity'] = $outlet -> activity;
            $data['caisse_number'] = $outlet->caisse_number;
            //$data['classe'] = $outlet -> classe;
            // $visit_day=array_values(json_decode($outlet -> visit_day));
            // $visit_day=implode(",",$visit_day);
            $data['visit_days'] = json_decode($outlet->visit_day);
            $data['delivery_days'] = explode(',', $outlet->delivery_day);

            $data['photos'] = $outlet->photos;
            $data['longitude'] = $outlet->longitude;
            $data['latitude'] = $outlet->latitude;
            $data['week_of_work'] = $outlet->week_of_work;
            $data['super_market_project'] = $outlet->super_market_project;
            $data['responsible_id'] = $outlet->responsible_id;

            $data['active'] = $outlet->active;
        }

        $this->form_validation->set_rules('code', 'Code', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[50]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('outlet_form', $data);
        } else {


            $save['id'] = $id;
            $save['code'] = $this->input->post('code');
            $save['name'] = $this->input->post('name');
            $save['admin_id'] = $this->input->post('sfo_id');
            $zone_id = $this->input->post('zone');
            $save['zone_id'] = $zone_id;

            $zone = $this->Zone_model->get_zone_by_id($zone_id);
            $save['zone'] = $zone->name;


            $channel_id = $this->input->post('channel_id');
            $channel = $this->Channel_model->get_channel_by_id($channel_id);
            $save['channel_id'] = $channel_id;
            $save['channel'] = $channel->name;


            $sub_channel_id = $this->input->post('sub_channel_id');
            $sub_channel = $this->Sub_channel_model->get_sub_channel_by_id($sub_channel_id);
            $save['sub_channel_id'] = $sub_channel_id;
            $save['sub_channel'] = $sub_channel->name;

            $save['state_id'] = $this->input->post('state');
            $state_by_id = $this->State_model->get_state_by_id($save['state_id']);
            $save['state'] = $state_by_id->name;


            $save['adress'] = $this->input->post('adress');
            $save['contact_pdv'] = $this->input->post('contact_pdv');
            $save['contact'] = $this->input->post('contact');
            //$save['activity'] = $this -> input -> post('activity');
            $save['caisse_number'] = $this->input->post('caisse_number');
            //$save['classe'] = $this -> input -> post('classe');

            $save['visit_day'] = json_encode($this->input->post('visit_days'));
            //$delivery_days = array();
            $delivery_days = $this->input->post('delivery_days');

            if (is_array($delivery_days))
                $save['delivery_day'] = implode(",", $delivery_days);

            $save['longitude'] = $this->input->post('longitude');
            $save['latitude'] = $this->input->post('latitude');
            $save['week_of_work'] = $this->input->post('week_of_work');
            $save['active'] = $this->input->post('active');
            $save['super_market_project'] = $this->input->post('super_market_project');
            $save['responsible_id'] = $this->input->post('responsible_id');

            $uploaded = $this->upload->do_upload('photos');

            if ($id) {
                $save['id'] = $id;

                //delete the original file if another is uploaded
                if ($uploaded) {
                    if ($data['photos'] != '') {
                        $file = 'uploads/outlet/' . $data['photos'];

                        //delete the existing file if needed
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                }
            } else {
                if (!$uploaded) {
                    $data['error'] = $this->upload->display_errors();
                    $this->load->view('/outlet_form', $data);
                    return;
                    //end script here if there is an error
                }
            }

            if ($uploaded) {
                $image = $this->upload->data();
                $save['photos'] = $image['file_name'];
            }


            $this->Outlet_model->save($save);

            $this->session->set_flashdata('message', 'Outlet has been saved');

            //go back to the outlet list
            redirect('outlets');
        }
    }

    function geo()
    {
        ini_set('memory_limit', '-1');
        $data['page_title'] = 'Outlets';
        $data['sub_title'] = 'Outlet Geolocalisation';
        $this->load->view('outlet_geo', $data);
    }

    function geo_old()
    {
        $data['page_title'] = 'Outlets';
        $data['sub_title'] = 'Outlet Geolocalisation';

        $outlets = $this->Outlet_model->get_active_outlets();


        $this->load->library('googlemaps');

        $config['center'] = '35.0534864,9.2408933';
        $config['zoom'] = '7';
        $config['styles'] = array(array("name" => "Red Parks", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-30"))), array("featureType" => "poi.park", "stylers" => array(array("saturation" => "10"), array("hue" => "#990000"))))), array("name" => "Black Roads", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-70"))), array("featureType" => "road.arterial", "elementType" => "geometry", "stylers" => array(array("hue" => "#000000"))))), array("name" => "No Businesses", "definition" => array(array("featureType" => "poi.business", "elementType" => "labels", "stylers" => array(array("visibility" => "off"))))));
        $config['stylesAsMapTypes'] = true;
        $config['stylesAsMapTypesDefault'] = "Black Roads";
        $config['https'] = true;
        $this->googlemaps->initialize($config);


        foreach ($outlets as $outlet) {
            $marker = array();
            $content = '<b>Outlet name:</b> ' . $outlet->name . '</br><b>Zone:</b> ' . $outlet->zone . '</br><b>State:</b> ' . $outlet->state . ' </br><b>Adress:</b> ' . $outlet->adress . ' </br></br><b>More details:</b> <a class="btn btn-xs red filter-submit margin-bottom" href="' . site_url('outlets/view/' . $outlet->id) . ' " data-toggle="tooltip" data-placement="top" title="More details" target="_blank"><i class="icon-map"></i></a>';
            $marker['infowindow_content'] = $content;
            if ($outlet->channel == 'Gemo') {
                $marker['icon'] = base_url('assets/img/red1.png');
            } else if ($outlet->channel == 'UHD') {
                $marker['icon'] = base_url('assets/img/blue1.png');
            } else {
                $marker['icon'] = base_url('assets/img/green1.png');
            }


            $marker['position'] = $outlet->latitude . ',' . $outlet->longitude;
            $this->googlemaps->add_marker($marker);
        }// end for


        $data['map'] = $this->googlemaps->create_map();

        $this->load->view('outlet_geo', $data);
    }

    function view($id = false)
    {
        $data['page_title'] = 'Outlets';
        $data['sub_title'] = 'Outlet Details';
        $outlet = $this->Outlet_model->get_outlet($id);
        $this->load->library('googlemaps');

        $config['center'] = $outlet->latitude . ',' . $outlet->longitude;
        $config['zoom'] = '13';
        $config['styles'] = array(array("name" => "Red Parks", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-30"))), array("featureType" => "poi.park", "stylers" => array(array("saturation" => "10"), array("hue" => "#990000"))))), array("name" => "Black Roads", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-70"))), array("featureType" => "road.arterial", "elementType" => "geometry", "stylers" => array(array("hue" => "#000000"))))), array("name" => "No Businesses", "definition" => array(array("featureType" => "poi.business", "elementType" => "labels", "stylers" => array(array("visibility" => "off"))))));
        $config['stylesAsMapTypes'] = true;
        $config['stylesAsMapTypesDefault'] = "Black Roads";
        $config['https'] = true;

        $this->googlemaps->initialize($config);

        $marker = array();
        $content = $outlet->name;
        $marker['infowindow_content'] = $content;
        if ($outlet->channel_id == 1)
            $marker['icon'] = base_url('assets/img/blue1.png');
        else if ($outlet->channel_id == 2)
            $marker['icon'] = base_url('assets/img/red1.png');
        else
            $marker['icon'] = base_url('assets/img/green1.png');

        $marker['position'] = $outlet->latitude . ',' . $outlet->longitude;
        $this->googlemaps->add_marker($marker);
        $data['map'] = $this->googlemaps->create_map();
        $data['outlet'] = $outlet;

        $this->load->view('outlet_view', $data);
    }

    function delete($id = false)
    {
        if ($id) {
            $outlet = $this->Outlet_model->get_outlet($id);
            //if the outlet does not exist, redirect them to the outlet list with an error
            if (!$outlet) {
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect('outlets');
            } else {
                $admin = $this->session->userdata('admin');
                $save['user_id'] = $admin['id'];
                $save['user_name'] = $admin['name'];

                $segs = $this->uri->segment_array();
                $link = '';
                foreach ($segs as $segment) {
                    $link = $link . '/' . $segment;
                }

                $save['type'] = "delete";
                $save['link'] = $link;

                $data = json_encode($outlet);
                $save['data'] = $data;

                $this->Log_model->save_log($save);

                //if the outlet is legit, delete them
                $delete = $this->Outlet_model->delete($id);

                $this->session->set_flashdata('error', 'Outlet has been deleted');
                redirect('outlets');
            }
        } else {
            //if they do not provide an id send them to the chef list page with an error
            $this->session->set_flashdata('error', lang('error_not_found'));
            redirect('outlets');
        }
    }

    function desactivate($id)
    {
        $outlet = array('id' => $id, 'active' => 0);
        $this->Outlet_model->save($outlet);
        $this->session->set_flashdata('message', lang('message_outlet_saved'));
        redirect('outlets');
    }

    function activate($id)
    {
        $outlet = array('id' => $id, 'active' => 1);
        $this->Outlet_model->save($outlet);
        $this->session->set_flashdata('message', lang('message_outlet_saved'));
        redirect('outlets');
    }

    public function search()
    {
        $data['page_title'] = 'Outlets';
        $data['sub_title'] = 'Manage Outlets';

        $data['admins'] = $this->auth->get_fo_list();
        $search = ($this->input->post("search")) ? $this->input->post("search") : "-1";
        $search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;
        $search_user_id = ($this->input->post("user_id")) ? $this->input->post("user_id") : "-1";
        $search_user_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : $search_user_id;
        $config['base_url'] = site_url('outlets/search/' . $search . '/' . $search_user_id);

        //Total row
        $config['total_rows'] = $this->Outlet_model->count_outlets_search($search, $search_user_id);
        //	echo $config['total_rows'];
        //die();
        $config['per_page'] = "10";
        $config["uri_segment"] = 5;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = 8;
        //floor($choice);
        //config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $data['page'] = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;

        if ($this->auth->check_access('Henkel')) {
            $henkel = 1;
        } else {

            $henkel = 0;
        }

        //call the model function to get the department data
        $data['outlets'] = $this->Outlet_model->get_outlets_search($config["per_page"], $data['page'], 'outlets.name', 'DESC', $search, $search_user_id, $henkel);

        $data['pagination'] = $this->pagination->create_links();

        //load the department_view
        $this->load->view('outlets', $data);
    }

    function update_out()
    {


        $outlets = $this->Outlet_model->get_outlets();
        foreach ($outlets as $out) {
            $save['id'] = $out->id;
            $save['sub_channel_id'] = $this->Sub_channel_model->get_sub_channel_by_name($out->sub_channel)->id;
            $this->db->where('id', $save['id']);
            $this->db->update('outlets', $save);

            print_r($save);
        }
    }

    function export()
    {
        $this->load->library('export');
        $outlets = $this->Outlet_model->get_outlets();
        $this->export->to_excel($outlets, 'outlets');
    }

    function traite()
    {
        $outlets = $this->Outlet_model->get_outlets();
        foreach ($outlets as $outlet) {
            $visit_days = array($outlet->visit_day);

            $save['id'] = $outlet->id;
            $save['visit_day'] = json_encode($visit_days);

            $this->Outlet_model->save($save);
        }
    }

    function export_map($start_date, $end_date, $channel_id, $category_id, $sub_category_id, $product_group_id, $product_id)
    {
        $this->load->library('export');

        $result = array();
        $outlets = $this->Outlet_model->get_outlet_numeric_distribution($start_date, $end_date, $channel_id, $category_id, $sub_category_id, $product_group_id, $product_id);

        foreach ($outlets as $outlet) {

            //   print_r($outlet); echo '<br>';
            $total = $outlet->av + $outlet->oos;
            if ($total != 0) {
                $oos_data = number_format(($outlet->oos / ($total)) * 100, 2, '.', ' ');

                if ($oos_data != 0) {
                    $row = new stdClass();
                    $row->name = $outlet->name;
                    $row->channel = $outlet->channel;
                    $row->sub_channel = $outlet->sub_channel;
                    $row->state = $outlet->state;
                    $result[] = $row;
                }
            }
        }
        // die();
        $this->export->to_excel($result, 'outlets');
    }

    function numeric_distribution()
    {
        //ini_set('memory_limit', '-1');
        $data['page_title'] = 'Outlets';
        $data['sub_title'] = 'Numeric Distribution';

        $start_date = ($this->input->post('start_date')) ? $this->input->post("start_date") : "";
        $end_date = ($this->input->post('end_date')) ? $this->input->post("end_date") : "";
        $channel_id = ($this->input->post('channel_id')) ? $this->input->post("channel_id") : "-1";
        //******************
        $category_id = ($this->input->post('category_id')) ? $this->input->post("category_id") : "0";
        $sub_category_id = ($this->input->post('sub_category_id')) ? $this->input->post("sub_category_id") : "0";
        //$cluster_id = ($this->input->post('cluster_id')) ? $this->input->post("cluster_id") : "0";
        $product_group_id = ($this->input->post('product_group_id')) ? $this->input->post("product_group_id") : "0";
        $product_id = ($this->input->post('product_id')) ? $this->input->post("product_id") : "0";
        //**********************
        //$av_type = ($this->input->post('av_type')) ? $this->input->post('av_type') : "2";
        //****************************
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['channel_id'] = $channel_id;
        //$data['av_type'] = $av_type;

        $data['category_id'] = $category_id;
        $data['sub_category_id'] = $sub_category_id;
        //$data['cluster_id'] = $cluster_id;
        $data['product_group_id'] = $product_group_id;
        $data['product_id'] = $product_id;

//        print_r($this->Sub_category_model->get_active_sub_categories(13));
//        die();
        $data['channels'] = $this->Channel_model->get_active_channels();
        $data['categories'] = $this->Category_model->get_active_henkel_categories();
        $data['sub_categories'] = $this->Sub_category_model->get_active_henkel_sub_categories($category_id);
        //$data['clusters'] = $this->Cluster_model->get_active_henkel_clusters($sub_category_id);
        $data['product_groups'] = $this->Product_group_model->get_active_henkel_product_groups($sub_category_id);
        $data['products'] = $this->Product_model->get_active_henkel_products($product_group_id);

        //*********************report************************************
        if ($start_date != "" and $start_date != "") {
            $outlets = $this->Outlet_model->get_outlet_numeric_distribution($start_date, $end_date, $channel_id, $category_id, $sub_category_id, $product_group_id, $product_id);
            $data['outlets'] = $outlets;

            $this->load->library('googlemaps');

            //map one
            $config['center'] = '35.0534864,9.2408933';
            $config['zoom'] = '6.95';
            $config['map_name'] = 'map_one';
            $config['map_div_id'] = 'map_canvas_one';
            $config['styles'] = array(
                array("name" => "Red Parks", "definition" =>
                    array(array("featureType" => "all", "stylers" =>
                        array(array("saturation" => "-30"))),
                        array("featureType" => "poi.park", "stylers" =>
                            array(array("saturation" => "10"),
                                array("hue" => "#990000"))))),
                array("name" => "Black Roads", "definition" =>
                    array(array("featureType" => "all", "stylers" =>
                        array(array("saturation" => "-70"))),
                        array("featureType" => "road.arterial", "elementType" => "geometry", "stylers" =>
                            array(array("hue" => "#000000"))))),
                array("name" => "No Businesses", "definition" =>
                    array(array("featureType" => "poi.business", "elementType" => "labels", "stylers" =>
                        array(array("visibility" => "off"))))));


            $config['stylesAsMapTypes'] = true;
            $config['stylesAsMapTypesDefault'] = "Black Roads";
            $config['https'] = true;
            $this->googlemaps->initialize($config);
            foreach ($outlets as $outlet) {
                if (($outlet->av + $outlet->oos) == 0) {
                    $av_type_name = '-';
                    $oos_data = '-';
                } else {
                    $av_type_name = 'OOS';
                    $oos_data = number_format(($outlet->oos / ($outlet->av + $outlet->oos )) * 100, 2, '.', ' ');

                    $marker = array();
                    $content = '<b>Outlet name:</b> ' . $outlet->name
                        . '</br><b>Zone:</b> ' . $outlet->zone
                        . '</br><b>Channel:</b> ' . $outlet->channel
                        . '</br><b>State:</b> ' . $outlet->state
                        . '</br><b>OOS % : </b>' . $oos_data . '%'
                        . '</br></br><b>More details:</b> '
                        . '<a class="btn btn-xs red filter-submit margin-bottom" href="' . site_url('outlets/view/' . $outlet->id) . ' " data-toggle="tooltip" data-placement="top" title="More details" target="_blank"><i class="icon-map"></i></a>';

                    $marker['infowindow_content'] = $content;
                    if ($oos_data != 0) {  // av < 100%
                        $marker['icon'] = base_url('assets/img/black1.png');
                    } else
                        if ($outlet->channel == 'Gemo' && $outlet->active == 1) {
                            $marker['icon'] = base_url('assets/img/red1.png');
                            //$marker['id'] = 'GEMO';
                        } else if ($outlet->channel == 'UHD' && $outlet->active == 1) {
                            $marker['icon'] = base_url('assets/img/blue1.png');
                            //$marker['id'] = 'UHD';
                        } else if ($outlet->channel == 'MG' && $outlet->active == 1) {
                            $marker['icon'] = base_url('assets/img/green1.png');
                            //$marker['id'] = 'MG';
                        }
                    //else if ($outlet->active == 0) {
                    //   $marker['icon'] = base_url('assets/img/black1.png');
//                    //$marker['id'] = 'inative';
                    // }

                    $marker['position'] = $outlet->latitude . ',' . $outlet->longitude;
                    //if ($av_type == 1 && ($outlet->oos > 0)) {
                    $this->googlemaps->add_marker($marker);
                    //} else if ($av_type == 2 && ($outlet->av > 0)) {
                    //   $this->googlemaps->add_marker($marker);
                    // } else if ($av_type == 3 && ($outlet->ha_pro > 0)) {
                    //    $this->googlemaps->add_marker($marker);
                    //}
                }
            }

            $data['map_one'] = $this->googlemaps->create_map();


            //map two
            $outlets_for_map_two = $this->Outlet_model->get_outlet_per_satet_numeric_distribution($start_date, $end_date, $channel_id, $category_id, $sub_category_id, $product_group_id, $product_id);
            $data['outlets_for_map_two'] = $outlets_for_map_two;
        }
        $this->load->view('header', $data);
        $this->load->view('map_chart');
        $this->load->view('outlets_numeric_distrubution', $data);
        $this->load->view('footer');
    }

    function get_all_data()
    {
//        $category_id = $this->input->post("category_id");
//        $sub_category_id = $this->input->post("sub_category_id");
//        $cluster_id = $this->input->post("cluster_id");
//        $product_group_id = $this->input->post("product_group_id");
//        $product_id = $this->input->post("product_id");
        $data = $this->input->post("data");
        $type = $this->input->post("type");

        //header('Content-Type: application/x-json; charset=utf-8');

        $sub_categories[0] = 'All_sub_categories';
        $categories[0] = 'All_categories';
        $clusters[0] = 'All_clusters';
        $product_groups[0] = 'All_product_groups';
        $products[0] = 'All_products';


//        print_r($this->Outlet_model->get_all_data($category_id, $sub_category_id, $cluster_id, $product_group_id, $product_id));
//        die();
        foreach ($this->Outlet_model->get_all_data($data, $type) as $data) {
//      foreach ($this->Outlet_model->get_all_data($category_id, $sub_category_id, $cluster_id, $product_group_id, $product_id) as $data) {
//            echo $data->sub_category_id;
//            echo $data->sub_category_name;
//            echo $data->category_id;
//            echo $data->category_name;
            $sub_categories[$data->sub_category_id] = $data->sub_category_name;
            $categories[$data->category_id] = $data->category_name;
            // $clusters[$data->cluster_id] = $data->cluster_name;
            $product_groups[$data->product_group_id] = $data->product_group_name;
            $products[$data->product_id] = $data->product_name;

//            print_r($sub_categories);
//            print_r($categories);
            //die();
        }
        //print_r($response[1]);
        //die();
        $response = array(
            'sub_categories' => $sub_categories,
            'categories' => $categories,
            //'clusters' => $clusters,
            'product_groups' => $product_groups,
            'products' => $products,
        );
        echo json_encode($response);
    }

}
