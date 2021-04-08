<?php

//bcm visits
class Visits extends CI_Controller {

    var $connected_user_id = false;

    function __construct() {
        parent::__construct();

        if (!$this->auth->is_logged_in(false, false)) {

            redirect('login');
        }

        $admin = $this->session->userdata('admin');
        $this->connected_user_id = $admin['id'];
        $this->lang->load('visit');
        $this->load->model(array('Outlet_model', 'Zone_model', 'Visit_model', 'Brand_model', 'Product_model', 'Admin_model'));
        $this->load->helper(array('form', 'date'));
        $this->load->library('pagination');
    }

    function position($visit_id) {

        $data_header['page_title'] = 'Dashboard';
        $data_header['sub_title'] = 'Dashboard & statistics';
        $visit = $this->Visit_model->get_visit($visit_id);
        $outlet = $this->Outlet_model->get_outlet($visit->outlet_id);
        $this->load->library('googlemaps');

        $config['center'] = $outlet->latitude . ',' . $outlet->longitude;
        $config['zoom'] = '15';
        $config['styles'] = array(array("name" => "Red Parks", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-30"))), array("featureType" => "poi.park", "stylers" => array(array("saturation" => "10"), array("hue" => "#990000"))))), array("name" => "Black Roads", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-70"))), array("featureType" => "road.arterial", "elementType" => "geometry", "stylers" => array(array("hue" => "#000000"))))), array("name" => "No Businesses", "definition" => array(array("featureType" => "poi.business", "elementType" => "labels", "stylers" => array(array("visibility" => "off"))))));
        $config['stylesAsMapTypes'] = true;
        $config['stylesAsMapTypesDefault'] = "Black Roads";
        $config['https'] = true;
        $this->googlemaps->initialize($config);

        //position outlet
        $content = '<b>Outlet position:</b> ' . $outlet->name . '</br><b>Zone:</b> ' . $outlet->zone . '</br><b>State:</b> ' . $outlet->state . ' </br> ';
        $marker['infowindow_content'] = $content;
        $marker['icon'] = base_url('assets/img/blue1.png');
        $marker['position'] = $outlet->latitude . ',' . $outlet->longitude;
        $this->googlemaps->add_marker($marker);

        // entry position
        $content = '<b>visit entry position:</b> ' . $outlet->name . '</br><b>Zone:</b> ' . $outlet->zone . '</br><b>State:</b> ' . $outlet->state . ' </br> ';
        $marker1['infowindow_content'] = $content;
        $marker1['icon'] = base_url('assets/img/yellow1.png');
        $marker1['position'] = $visit->latitude . ',' . $visit->longitude;
        $this->googlemaps->add_marker($marker1);

        // exit position
        $content = '<b>visit exit position:</b> ' . $outlet->name . '</br><b>Zone:</b> ' . $outlet->zone . '</br><b>State:</b> ' . $outlet->state . ' </br> ';
        $marker2['infowindow_content'] = $content;
        $marker2['icon'] = base_url('assets/img/red1.png');
        $marker2['position'] = $visit->exit_latitude . ',' . $visit->exit_longitude;
        $this->googlemaps->add_marker($marker2);



        $data['map'] = $this->googlemaps->create_map();

        $this->load->view('header', $data_header);
        $this->load->view('visits/visitoutletposition', $data);
        $this->load->view('footer');
    }

    function visitoutletposition($visit_id) {

        $data_header['page_title'] = 'Dashboard';


        $data_header['sub_title'] = 'Dashboard & statistics';
        $visit = $this->Visit_model->get_visit($visit_id);
        $outlet = $this->Outlet_model->get_outlet($visit->outlet_id);
        $this->load->library('googlemaps');

        $config['center'] = $outlet->latitude . ',' . $outlet->longitude;
        $config['zoom'] = '15';
        $config['styles'] = array(array("name" => "Red Parks", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-30"))), array("featureType" => "poi.park", "stylers" => array(array("saturation" => "10"), array("hue" => "#990000"))))), array("name" => "Black Roads", "definition" => array(array("featureType" => "all", "stylers" => array(array("saturation" => "-70"))), array("featureType" => "road.arterial", "elementType" => "geometry", "stylers" => array(array("hue" => "#000000"))))), array("name" => "No Businesses", "definition" => array(array("featureType" => "poi.business", "elementType" => "labels", "stylers" => array(array("visibility" => "off"))))));
        $config['stylesAsMapTypes'] = true;
        $config['stylesAsMapTypesDefault'] = "Black Roads";
        $config['https'] = true;
        $this->googlemaps->initialize($config);

        $content = '<b>visit position:</b> ' . $outlet->name . '</br><b>Zone:</b> ' . $outlet->zone . '</br><b>State:</b> ' . $outlet->state . ' </br> ';
        $marker['infowindow_content'] = $content;
        $marker['icon'] = base_url('assets/img/red1.png');
        $marker['position'] = $visit->latitude . ',' . $visit->longitude;
        $this->googlemaps->add_marker($marker);

        $content = '<b>Outlet position:</b> ' . $outlet->name . '</br><b>Zone:</b> ' . $outlet->zone . '</br><b>State:</b> ' . $outlet->state . ' </br> ';
        $marker['infowindow_content'] = $content;
        $marker['icon'] = base_url('assets/img/blue1.png');
        $marker['position'] = $outlet->latitude . ',' . $outlet->longitude;
        $this->googlemaps->add_marker($marker);

        $data['map'] = $this->googlemaps->create_map();
        $this->load->view('header', $data_header);
        $this->load->view('visits/visitoutletposition', $data);
        $this->load->view('footer');
    }

    public function index() {
        $data['page_title'] = 'Visits';
        $data['sub_title'] = 'List of Visits';


        if ($this->auth->check_access('Henkel') || $this->auth->check_access('Admin')) {
            $current_admin_id = -1;
        } else if ($this->auth->check_access('Field Officer')) {

            $current_admin_id = $this->connected_user_id;
        }
        $data['admins'] = $this->auth->get_fo_list();

        $search_user_id = $this->input->post('user_id');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $visit_type = $this->input->post('visit_type');

        $data['visit_type'] = $visit_type;
        $data['user_id'] = $search_user_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['search_type'] = -1;


        //pagination settings
        $config['base_url'] = site_url('visits/index');

        //Total row
        $config['total_rows'] = $this->Visit_model->count_visits($current_admin_id);
        $config['per_page'] = "20";
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = 4;
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
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        //call the model function to get the department data
        $data['visits'] = $this->Visit_model->get_visits($config["per_page"], $data['page'], 'visits.id', 'DESC', $current_admin_id);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('header', $data);
        $this->load->view('visits/visits', $data);
        $this->load->view('footer');
    }

    public function search() {
        $data['page_title'] = 'Weekly Visits';
        $data['sub_title'] = 'Manage Weekly Visits';
        $data['admins'] = $this->auth->get_fo_list();

        $search = ($this->input->post("search")) ? $this->input->post("search") : "-1";
        $search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;


        $start_date = ($this->input->post("start_date")) ? $this->input->post("start_date") : "-1";
        $start_date = ($this->uri->segment(4)) ? $this->uri->segment(4) : $start_date;

        $end_date = ($this->input->post("end_date")) ? $this->input->post("end_date") : "-1";
        $end_date = ($this->uri->segment(5)) ? $this->uri->segment(5) : $end_date;

        $data['start_date'] = $this->input->post("start_date");
        $data['end_date'] = $this->input->post("end_date");


        $search_user_id = ($this->input->post("user_id")) ? $this->input->post("user_id") : "-1";
        $search_user_id = ($this->uri->segment(6)) ? $this->uri->segment(6) : $search_user_id;
        $data['user_id'] = $this->input->post("user_id");

        $search_type = ($this->input->post("visit_type")) ? $this->input->post("visit_type") : "0";
        $search_type = ($this->uri->segment(7)) ? $this->uri->segment(7) : $search_type;
        $data['search_type'] = $search_type;

        if ($search_user_id != -1) {
            $user_name = $this->Admin_model->get_admin_name($search_user_id);
        } else {
            $user_name = 'All Fieled Officer';
        }

        $data['page_title'] = 'Weekly Visits' . '|' . $user_name . '|' . ($start_date) . '|' . ($end_date);

        //pagination settings
        $config['base_url'] = site_url($this->config->item('admin_folder') . '/visits/search/' . $search . '/' . $start_date . '/' . $end_date . '/' . $search_user_id . '/' . $search_type);

        //Total row
        $config['total_rows'] = $this->Visit_model->count_visits_search($search, $search_user_id, $start_date, $end_date, $search_type);
        $config['per_page'] = "10";
        $config["uri_segment"] = 8;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = 4;
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
        $data['page'] = ($this->uri->segment(8)) ? $this->uri->segment(8) : 0;

        //call the model function to get the department data
        $data['visits'] = $this->Visit_model->get_visits_search($config["per_page"], $data['page'], 'visits.id', 'DESC', $search, $search_user_id, $start_date, $end_date, $search_type);

        $data['pagination'] = $this->pagination->create_links();

        //load the department_view       
        $this->load->view('header', $data);
        $this->load->view($this->config->item('admin_folder') . '/visits/visits', $data);
        $this->load->view('footer');
    }

    function form($id = false) {

        $this->load->library('form_validation');
        $this->load->helper(array('form', 'date'));

        $data['page_title'] = 'Visits';
        $data['sub_title'] = 'Vist Form';

        if ($this->auth->check_access('Field Officer')) {
            $data['outlets'] = $this->Outlet_model->get_outlets_by_id($this->connected_user_id);
            $data['admins'] = $this->auth->get_admin_by_id($this->connected_user_id);
        } else {
            $data['outlets'] = $this->Outlet_model->get_outlets();
            $data['admins'] = $this->auth->get_admin_list();
        }

        //default values are empty if the modern_visit is new
        $data['id'] = false;
        $data['date'] = date('Y-m-d');
        $data['modified'] = '';
        $data['user_id'] = '';
        $data['outlet_id'] = '';
        $data['remark'] = '';
        $data['active'] = false;

        if ($id) {

            $visit = $this->Visit_model->get_visit($id);
            if (!$visit) {
                $this->session->set_flashdata('error', 'The requested Visit could not be found.');
                redirect('visits');
            }
            $data['sub_title'] = 'Vist Form' . '|' . $this->Outlet_model->get_outlet_name($visit->outlet_id) . '|' . format_week($visit->date) . '';

            //set values to db values
            $data['id'] = $visit->id;
            $data['date'] = $visit->date;

            $data['user_id'] = $visit->admin_id;
            $data['outlet_id'] = $visit->outlet_id;
            $data['remark'] = $visit->remark;
            //$data['active'] = $visit->active;
            //$data['long'] = $visit -> long;
            //$data['lat'] = $visit -> lat;
        }
        $this->form_validation->set_rules('date', 'Date');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header', $data);
            $this->load->view('visits/visit_form', $data);
            $this->load->view('footer');
        } else {
            $date = $this->input->post('date');
            $w_date = firstDayOf('week', new DateTime($date));
            $m_date = firstDayOf('month', new DateTime($date));
            $q_date = firstDayOf('quarter', new DateTime($date));

            $save['id'] = $id;
            $save['admin_id'] = $this->input->post('user_id');
            $save['outlet_id'] = $this->input->post('outlet_id');
            $save['uniqueId'] = $save['outlet_id'] . $save['admin_id'] . str_replace("-", "", $date) . $visit->monthly_visit . $visit->entry_time;

            $save['date'] = $date;
            $save['w_date'] = $w_date;
            $save['m_date'] = $m_date;
            $data['q_date'] = $q_date;

            $save['remark'] = $this->input->post('remark');

            $result = $this->Visit_model->save($save);
            if ($result) {
                $this->session->set_flashdata('message', 'The Visit has been saved.');
            } else {
                $this->session->set_flashdata('error', 'The Visit has not been saved.');
            }
            redirect('visits');
        }
    }

    function copy($id = false) {

        $visit = $this->Visit_model->get_visit($id);

        $data['id'] = false;
        $data['admin_id'] = $visit->admin_id;
        $data['outlet_id'] = $visit->outlet_id;
        $date = date("Y-m-d");
        $data['date'] = $date;
        $data['w_date'] = firstDayOf('week', new DateTime($date));
        $data['m_date'] = firstDayOf('month', new DateTime($date));
        $data['q_date'] = firstDayOf('quarter', new DateTime($date));
        $data['monthly_visit'] = $visit->monthly_visit;
        $data['entry_time'] = $visit->entry_time;
        $data['mobile_entry_time'] = $visit->mobile_entry_time;
        $data['exit_time'] = $visit->exit_time;
        $data['mobile_exit_time'] = $visit->mobile_exit_time;
        $data['worked_time'] = $visit->worked_time;
        $data['remark'] = '';
        $data['oos_perc'] = $visit->oos_perc;
        $data['shelf_perc'] = $visit->shelf_perc;
        $data['longitude'] = $visit->longitude;
        $data['exit_longitude'] = $visit->exit_longitude;
        $data['latitude'] = $visit->latitude;
        $data['exit_latitude'] = $visit->exit_latitude;
        $data['active'] = $visit->active;
//        $data['uniqueId'] = $visit->uniqueId;       
        $data['uniqueId'] = $visit->outlet_id . $visit->admin_id . str_replace("-", "", $date) . $visit->monthly_visit . $visit->entry_time;


        $data['was_there'] = $visit->was_there;
        $data['branding_pictures'] = $visit->branding_pictures;
        $data['one_pictures'] = $visit->one_pictures;
//        $data['bonCommande'] = $visit->bonCommande;

        $copy_visit = $this->Visit_model->copy($data, $id);
        $this->session->set_flashdata('message', lang('message_saved_visit'));
        //go back to the modern_visit list
        redirect($this->config->item('admin_folder') . '/visits/models/' . $copy_visit);
    }

    function delete($id = false) {
        $visit = $this->Visit_model->get_visit($id);

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

        $data = json_encode($visit);
        $save['data'] = $data;

        $this->Log_model->save_log($save);

        $delete = $this->Visit_model->delete($id);
        $delete_models = $this->Visit_model->delete_models($id);

        $this->session->set_flashdata('error', 'visit deleted');
        redirect($this->config->item('admin_folder') . '/visits');
    }

    function models($visit_id = false) {
        //$this->output->cache(3600);

        $this->load->helper(array('form', 'date'));
        $this->load->library('form_validation');

        $data['page_title'] = 'Visits';

        $visit = $this->Visit_model->get_visit($visit_id);
        $data['monthly'] = $visit->monthly_visit;
        if ($visit->monthly_visit == 0) {
            $data['models'] = $this->Visit_model->get_detail_daily_models($visit_id);
        } else {
            $data['models'] = $this->Visit_model->get_detail_models($visit_id);
        }

        $data['id'] = $visit_id;
        $outlet_name = $this->Outlet_model->get_outlet_name($visit->outlet_id);
        $data['sub_title'] = "Visit | " . $outlet_name . " | " . format_week($visit->date);

        $this->load->view('header', $data);
        $this->load->view('visits/visit_model_form', $data);
        $this->load->view('footer');
    }

    function bulk_save($visit_id) {

        $models = $this->input->post('model');

        if (!$models) {
            $this->session->set_flashdata('error', lang('error_bulk_no_models'));
            redirect('visits');
        }

        $nb_oos = 0;
        $nb_henkel = 0;
        foreach ($models as $id => $model) {
            if (isset($model['av'])) {
                $av = $model['av'];
                if (($model['brand_id'] == 1) && ($av != 2)) {
                    $nb_henkel++;
                }
                if ($av == 0) {
                    $nb_oos++;
                }
            }
            print_r('<br>');

            $model['id'] = $id;

            $this->Visit_model->save_bulk($model);
        }

        $save_visit['id'] = $visit_id;
        $save_visit['oos_perc'] = $nb_oos / $nb_henkel;

        $this->Visit_model->save($save_visit);

        $this->session->set_flashdata('message', 'Models have been saved successfully !');
        redirect('visits');
    }

    function report($visit_id = false) {
        //$this->output->cache(3600);

        $this->load->helper(array('form', 'date', 'number'));
        $this->load->library('form_validation');

        $data['page_title'] = 'Visits';
        $data['old'] = '';
        $data['id_rayon'] = '';
        $visit = $this->Visit_model->get_pictures($visit_id);


//        //daily0
//        if ($visit->monthly_visit == 0) {
//            $data['models'] = $this->Visit_model->get_detail_models($visit_id);
//        }
//        //monthly (1:shelf 2:price 3:shelf+price)
//        else {
//            $data['models'] = $this->Visit_model->get_detail_monthly_models($visit_id);
//        }
        if ($visit->monthly_visit == 0) {
            $data['models'] = $this->Visit_model->get_detail_daily_models($visit_id);
        } else {
            $data['models'] = $this->Visit_model->get_detail_models($visit_id);
        }
        $data['monthly'] = $visit->monthly_visit;
        $data['pictures'] = $visit;
        $data['id'] = $visit_id;
        $outlet_name = $this->Outlet_model->get_outlet_name($visit->outlet_id);
        $data['sub_title'] = "Report  | " . $outlet_name . " | " . reverse_format($visit->date);


        $this->load->view('header', $data);
        $this->load->view('visits/visit_report', $data);
        $this->load->view('footer');
    }
    
    
    
     function order_report($visit_id = false) {
        //$this->output->cache(3600);

     

        $data['page_title'] = 'Visits';
       
        $visit = $this->Visit_model->get_pictures($visit_id);


        $data['visit'] = $visit;
        $data['id'] = $visit_id;
        $outlet_name = $this->Outlet_model->get_outlet_name($visit->outlet_id);
        $data['sub_title'] = "Order Report  | " . $outlet_name . " | " . reverse_format($visit->date);


        $this->load->view('header', $data);
        $this->load->view('visits/visit_order_report', $data);
        $this->load->view('footer');
    }

    function update_picutres($old_name) {
        print_r($old_name);
    }

    function update_pic($visit_id = false) {
        //$this->output->cache(3600);

        $this->load->helper(array('form', 'date'));
        $this->load->library('form_validation');

        $data['page_title'] = 'Visits';
        $data['old'] = '';
        $data['id_rayon'] = '';
        $data['item'] = 0;
        $visit = $this->Visit_model->get_visit($visit_id);
        $data['models'] = $this->Visit_model->get_detail_models($visit_id);
        $data['pictures'] = $visit;
        $data['id'] = $visit_id;
        $outlet_name = $this->Outlet_model->get_outlet_name($visit->outlet_id);
        $data['sub_title'] = "Report  | " . $outlet_name . " | " . reverse_format($visit->date);
        $this->load->view('visit_report2', $data);
    }

    function pictures($id = false) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'date'));
        $data['page_title'] = 'Visits';
        $data['sub_title'] = 'Visit Pictures';

        if ($id) {

            $visit = $this->Visit_model->get_visit($id);
            if (!$visit) {
                $this->session->set_flashdata('error', 'The requested Visit could not be found.');
                redirect('visits');
            }
            $data['id'] = $visit->id;
            $data['remark_images'] = $visit->remark;
            $data['page_title'] = 'Visit Pictures' . '|' . $this->Outlet_model->get_outlet_name($visit->outlet_id) . '|' . format_week($visit->date) . '';
        }
        $this->form_validation->set_rules('remark_images', 'lang:remark_images', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('visit_picture', $data);
        }
        ///le noms dans la base
        else {
            $save['id'] = $id;
            $save['remark_images'] = $this->input->post('remark_images');

            $this->Visit_model->save($save);

            //$this->session->set_flashdata('Produit', 'kl');

            redirect('visits');
        }
    }

    // Branding images
    public function upload_branding($visit_id = false) {
        $upload_path = "./uploads/branding";
        if (!empty($_FILES)) {
            $config["upload_path"] = $upload_path;
            $config["allowed_types"] = "gif|jpg|png";
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload("file")) {
                echo "failed to upload file(s)";
            } else {


                $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
                $file_name = $upload_data['file_name'];

                $visit = $this->Visit_model->get_visit($visit_id);
                $files = json_decode($visit->branding_pictures);

                $files[] = $file_name;

                $save['id'] = $visit_id;
                $save['branding_pictures'] = json_encode($files);
                $this->Visit_model->save($save);
            }
        }
    }

    public function upload_change($visit_id = false, $old = false, $id_rayon = false) {

        $config['allowed_types'] = '*';
        $config['max_size'] = 10000;
        $config['upload_path'] = 'uploads/branding';

        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);
        $id_rayon = $this->input->post('id_rayon');
        print_r($id_rayon);
        $upload_data = $this->upload->data();

        if ($this->upload->do_upload('file' . $id_rayon)) {
            $upload_data = $this->upload->data('file' . $id_rayon);


            $new_file_name = $upload_data['file_name'];
        }

        if ($this->upload->do_upload('file2' . $id_rayon)) {
            $upload_data = $this->upload->data('file2' . $id_rayon);


            $new_file_name = $upload_data['file_name'];
        }
        $save['id'] = $visit_id;
        $old_name = $this->input->post('old');
        print_r('old name ' . $old_name);
        print_r('new name ' . $new_file_name);
        $old_branding_pictures = $this->Visit_model->get_visit($visit_id)->branding_pictures;
        $branding_pictures = str_replace($old_name, $new_file_name, $old_branding_pictures);
        $save['branding_pictures'] = $branding_pictures;
        $this->Visit_model->save($save);
        redirect('visits/report/' . $visit_id . '#tab_1_1');
    }

    public function remove_branding($visit_id = false) {

        $upload_path = "./uploads/branding";
        $file = $this->input->post("file");
        if ($file && file_exists($upload_path . "/" . $file)) {
            //unlink($upload_path . "/" . $file);
        }

        $visit = $this->Visit_model->get_visit($visit_id);
        $files = json_decode($visit->branding_pictures);
        $key = array_search($file, $files); // $key = 2;
        unset($files[$key]);
        $save['id'] = $visit_id;
        if (sizeof($files) > 0) {
            $save['branding_pictures'] = json_encode(array_values($files));
        } else {
            $save['branding_pictures'] = Null;
        }
        $this->Visit_model->save($save);
    }

    public function list_branding_files($visit_id = false) {
        $this->load->helper("file");
        $upload_path = "./uploads/branding";
        $visit = $this->Visit_model->get_visit($visit_id);
        $files = json_decode($visit->branding_pictures);
        foreach ($files as $file) {
            $new_files[] = $file[0];
            $new_files[] = $file[1];
            $file_en[] = array(
                'name' => $file[0],
                'size' => filesize($upload_path . "/" . $file[0])
            );
            $file_en[] = array(
                'name' => $file[1],
                'size' => filesize($upload_path . "/" . $file[1])
            );
        }


        header("Content-type: text/json");
        header("Content-type: application/json");
        echo json_encode($file_en);
        // we need name and size for dropzone mockfile
        // for ( $i=1;$i<sizeof($files); $i++) {
        // $file = array(
        // 'name' => $file[$i][0],
        // 'size' => filesize($upload_path . "/" . $files[$i][0])
        // );
        // $file = array(
        // 'name' => $file[$i][1],
        // 'size' => filesize($upload_path . "/" . $files[$i][1])
        // );
        // }
    }

    // one pictures images

    public function upload_one_pictures($visit_id = false) {
        $upload_path = "./uploads/branding";
        if (!empty($_FILES)) {
            $config["upload_path"] = $upload_path;
            $config["allowed_types"] = "gif|jpg|png";
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload("file")) {
                echo "failed to upload file(s)";
            } else {


                $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
                $file_name = $upload_data['file_name'];

                $visit = $this->Visit_model->get_visit($visit_id);
                $files = json_decode($visit->one_pictures);

                $files[] = $file_name;

                $save['id'] = $visit_id;
                $save['one_pictures'] = json_encode($files);
                $this->Visit_model->save($save);
            }
        }
    }

    public function remove_one_pictures($visit_id = false) {

        $upload_path = "./uploads/branding";
        $file = $this->input->post("file");
        if ($file && file_exists($upload_path . "/" . $file)) {
            unlink($upload_path . "/" . $file);
        }



        $visit = $this->Visit_model->get_visit($visit_id);
        $files = json_decode($visit->one_pictures);
        $key = array_search($file, $files); // $key = 2;
        unset($files[$key]);

        $save['id'] = $visit_id;
        if (sizeof($files) > 0) {
            $save['one_pictures'] = json_encode(array_values($files));
        } else {
            $save['one_pictures'] = Null;
        }
        $this->Visit_model->save($save);
    }

    public function list_one_pictures_files($visit_id = false) {
        $this->load->helper("file");
        $upload_path = "./uploads/branding";
        $visit = $this->Visit_model->get_visit($visit_id);
        $files = json_decode($visit->one_pictures);
        // we need name and size for dropzone mockfile
        foreach ($files as &$file) {
            $file = array(
                'name' => $file,
                'size' => filesize($upload_path . "/" . $file)
            );
        }

        header("Content-type: text/json");
        header("Content-type: application/json");
        echo json_encode($files);
    }

    function up_models() {
        $data['page_title'] = 'Update Models';
        $data['sub_title'] = '';
        $data['models'] = $this->Model_model->get_active_up_models();
        $this->load->view($this->config->item('admin_folder') . '/up_models', $data);
    }

    function save_new_model() {
        $model_id = $this->input->post('model_id');
        $recent_visits = $this->Visit_model->get_visits_update_weekly_models();
        foreach ($recent_visits as $visit) {
            $model = $this->Model_model->get_model($model_id);
            $this->Weekly_model_model->add_new_model($model, $visit->id);
        }
        $this->session->set_flashdata('message', ' model has been updated successfully.');
        redirect($this->config->item('admin_folder') . '/visits/up_models');
    }

    function copy_old($id = false) {
        //date_default_timezone_set('Europe/Amsterdam');
        //$date = new DateTime();
        //$date->modify('this week');
        //$date_this_week = $date->format('Y-m-d');
        //$date_this_month = $date->format('Y-m-01');
        $data['id'] = '';
        $data['date'] = '';
        $data['entry_time'] = '';
        $data['exit_time'] = '';
        $data['modified'] = '';
        $data['admin_id'] = '';
        $data['outlet_id'] = '';
        $data['remark'] = '';
        $data['active'] = false;

        $visit = $this->Visit_model->get_visit($id);

        $data['id'] = false;
        $data['date'] = $visit->date;
        $data['entry_time'] = $visit->entry_time;
        $data['exit_time'] = $visit->exit_time;
        $data['modified'] = $visit->modified;
        $data['admin_id'] = $visit->admin_id;
        $data['outlet_id'] = $visit->outlet_id;
        $data['remark'] = $visit->remark;
        $data['active'] = $visit->active;


        //save competitor
        $save_com['admin_id'] = $visit->admin_id;
        $save_com['outlet_id'] = $visit->outlet_id;
        $save_com['date'] = date("Y-m-d", strtotime($this->input->post('date')));
        $save_com['date_taken'] = date("Y-m-d");
        //$comp_id = $this->Competitors_activities_model->save($save_com);
        // voice of dealer
        $save_voice['admin_id'] = $this->input->post('admin_id');
        $save_voice['outlet_id'] = $visit->outlet_id;
        $save_voice['date'] = date("Y-m-d", strtotime($this->input->post('date')));
        $save_voice['date_taken'] = date("Y-m-d");
        //$voice_id = $this->Voice_dealer_model->save($save_com);
        //brand_id
        $save_branding['admin_id'] = $this->input->post('admin_id');
        $save_branding['outlet_id'] = $visit->outlet_id;
        $save_branding['type'] = 'brand';
        $save_branding['date'] = date("Y-m-d");
        $save_branding['date_taken'] = date("Y-m-d");
        //$branding_id = $this->Picture_model->save($save_branding);

        $save_display['admin_id'] = $visit->admin_id;
        $save_display['outlet_id'] = $visit->outlet_id;
        $save_display['type'] = 'display';
        $save_display['date'] = date("Y-m-d");
        $save_display['date_taken'] = date("Y-m-d");
        //$branding_id = $this->Picture_model->save($save_display);

        $data['comp_id'] = $comp_id;
        $data['voice_id'] = $voice_id;
        $data['branding_id'] = $branding_id;
        $save_check['admin_id'] = $visit->admin_id;

        $save_check['type'] = 'Weekly visit';
        $save_check['date'] = date('Y-m-d');
        $save_check['visit_id'] = 200;
        //$this->Check_position_model->save($save_check);

        $this->Visit_model->copy($data, $id);

        //$this -> session -> set_flashdata('message', lang('message_saved_visit'));
        //go back to the modern_visit list
        redirect($this->config->item('admin_folder') . '/visits');
    }

    function shortage($visit_id = false) {
        //$this->output->cache(3600);
        force_ssl();
        $this->load->helper(array('form', 'date'));
        $this->load->library('form_validation');
        //$data['model_product'] = $this -> Product_model;

        $data['brands'] = $this->Brand_model->get_brands_by_code();

        $data['visit_id'] = $visit_id;
        $data['outlet_id'] = $this->Visit_model->get_outlet_id($visit_id);
        $data['date'] = format_week($this->Visit_model->get_visit_date($visit_id));
        $data['outlet_name'] = $this->Outlet_model->get_outlet_name($data['outlet_id']);
        $data['sub_title'] = "Shortage | " . $data['outlet_name'] . " | " . $data['date'];
        $data['models'] = $this->Weekly_model_model->get_shortage_models($visit_id);

        //print_r($data['modern_vis_models']);

        $data['id'] = $visit_id;

        $this->load->view($this->config->item('admin_folder') . '/visit_shortage_form', $data);
    }

    function brands($visit_id = false) {
        //$this->output->cache(3600);
        force_ssl();
        $this->load->helper(array('form', 'date'));
        $this->load->library('form_validation');
        //$data['model_product'] = $this -> Product_model;

        $data['brands'] = $this->Brand_model->get_brands_by_code();

        $data['visit_id'] = $visit_id;
        $data['outlet_id'] = $this->Visit_model->get_outlet_id($visit_id);
        $data['date'] = format_week($this->Visit_model->get_visit_date($visit_id));
        $data['outlet_name'] = $this->Outlet_model->get_outlet_name($data['outlet_id']);
        $data['page_title'] = "Weekly visit | " . $data['outlet_name'] . " | " . $data['date'];
        //$data['modern_vis_models'] = $this -> Modern_vis_model_model -> get_modern_vis_models($visit_id);
        //print_r($data['modern_vis_models']);

        $data['id'] = $visit_id;

        $this->load->view($this->config->item('admin_folder') . '/visit_brand_form', $data);
    }

    function specific_models($visit_id = false, $brand_id = false) {
        force_ssl();
        $this->load->helper(array('form', 'date'));
        $this->load->library('form_validation');

        $data['visit_id'] = $visit_id;
        $data['brand_id'] = $brand_id;
        $data['outlet_id'] = $this->Visit_model->get_outlet_id($visit_id);

        $brand_name = $this->Brand_model->get_brand_name($brand_id);

        $data['brand_name'] = $brand_name;

        $data['sub_title'] = "Weekly visit | " . $brand_name;

        $this->load->view($this->config->item('admin_folder') . '/visit_model_form2', $data);
    }

    function shortage_bulk_save($visit_id) {
        $models = $this->input->post('model');

        if (!$models) {
            $this->session->set_flashdata('error', lang('error_bulk_no_models'));
            redirect($this->config->item('admin_folder') . '/visits');
        }

        foreach ($models as $id => $model) {
            $model['id'] = $id;
            $this->Weekly_model_model->shortage_save_bulk($model);
        }

        $this->session->set_flashdata('message', lang('message_bulk_update'));
        redirect($this->config->item('admin_folder') . '/visits');
        //echo "<script>window.close();</script>";
    }

    function bulk_activate() {
        $visits = $this->input->post('visit');

        if ($visits) {
            foreach ($visits as $visit) {
                $this->Visit_model->activate($visit);
            }
            $this->session->set_flashdata('message', lang('message_visits_activated'));
        } else {
            $this->session->set_flashdata('error', lang('error_no_visits_selected'));
        }
        //redirect as to change the url
        redirect($this->config->item('admin_folder') . '/visits');
    }

    function add_model($visit_id = false) {
        force_ssl();

        $this->load->helper(array('form', 'date'));
        $this->load->library('form_validation');

        $data['sub_title'] = 'Add New Model';

        $data['model_ids'] = $this->Model_model->get_new_models($visit_id);
        $data['visit_id'] = $visit_id;

        $this->form_validation->set_rules('shelf', 'lang:shelf');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view($this->config->item('admin_folder') . '/weekly_add_model_form', $data);
        } else {

            $model_id = $this->input->post('model_id');
            $shelf = $this->input->post('shelf');
            $ws = $this->input->post('ws');
            $price = $this->input->post('price');
            $brand_id = $this->Model_model->get_model_brand($model_id);
            $category_id = $this->Model_model->get_model_category($model_id);
            $range_id = $this->Model_model->get_model_range1($model_id);
            $price_range_id = $this->Model_model->get_model_price_range($model_id);

            $save['visit_id'] = $visit_id;
            $save['model_id'] = $model_id;
            $save['brand_id'] = $brand_id;
            $save['category_id'] = $category_id;
            $save['range_id'] = $range_id;
            $save['price_range_id'] = $price_range_id;
            $save['shelf'] = $shelf;
            $save['ws'] = $ws;
            $save['price'] = $price;
            $save['amount'] = $shelf * $ws;

            $this->Weekly_model_model->save_single($save);

            $this->session->set_flashdata('message', lang('message_saved_visit'));

            //go back to the visit list
            redirect($this->config->item('admin_folder') . '/visits');
        }
    }

    function update_models() {
        //$this->output->cache(3600);

        $models = $this->Weekly_model_model->get_all_models();

        foreach ($models as $model) {
            print_r($model);
            echo '*******************************************************************';
            $this->Weekly_model_model->update($model);
        }
    }

    function up_price() {

        $this->Weekly_model_model->update_mod();
    }

    function shortage_report($visit_id) {
        $this->load->helper(array('form', 'date'));
        $data = array();

        $data['outlet_id'] = $this->Visit_model->get_outlet_id($visit_id);
        $data['date'] = format_week($this->Visit_model->get_visit_date($visit_id));
        $data['outlet_name'] = $this->Outlet_model->get_outlet_name($data['outlet_id']);
        $data['sub_title'] = "Shortage Report | " . $data['outlet_name'] . " | " . format_week($data['date']);
        $data['visit_id'] = $visit_id;
        $this->load->view($this->config->item('admin_folder') . '/weekly_shortage_report', $data);
    }

    function update_weekly_models2() {
        $this->load->helper(array('form', 'date'));
        $this->load->library('form_validation');
        $data['sub_title'] = 'Update Models - Weekly visits';
        $i = 0;
        $visits = $this->Visit_model->get_visits(1, '', 'id', 'DESC');
        if ($visits) {

            foreach ($visits as $visit_id) {
                $i++;

                //	print_r("expression");
                //	print_r($visit_id);

                $diff_models = $this->Visit_model->compare_models($visit_id->id);
                $diff_models = $this->Model_model->get_models2();
                print_r($diff_models);
                foreach ($diff_models as $model_id) {
                    //	 print_r($model_id);
                    $model = $this->Model_model->get_model($model_id->id);
                    //   print_r($model);
                    $new_model['id'] = false;
                    $new_model['model_id'] = $model_id->id;
                    $new_model['visit_id'] = $visit_id->id;
                    $new_model['category_id'] = $model->category_id;
                    $new_model['brand_id'] = $model->brand_id;
                    $new_model['range_id'] = $model->range_id;
                    $new_model['price_range_id'] = $model->price_range_id;
                    $new_model['price'] = $model->price;
                    $new_model['shelf'] = 0;
                    $new_model['ws'] = 0;
                    $new_model['amount'] = 0;
                    // print_r($new_model);
                    $this->Weekly_model_model->save_model($new_model);
                }
            }
            redirect($this->config->item('admin_folder') . '/visits');
        }

        if ($i > 0) {
            //	$this -> session -> set_flashdata('message', $i . ' ' . ' visits has been updated successfully.');
            //	redirect($this -> config -> item('admin_folder') . '/visits/update_weekly_models');
        } else {
            //	$this -> load -> view($this -> config -> item('admin_folder') . '/update_weekly_models', $data);
        }
        // $this -> load -> view($this -> config -> item('admin_folder') . '/update_weekly_models', $data);
    }

    function update_weekly_model_name($id1, $id2) {

        $weekly_models = $this->db->where('visit_id >= ', $id1)->where('visit_id <= ', $id2)->get('weekly_models')->result();
        foreach ($weekly_models as $weekly_model) {
            $model = $this->Model_model->get_model($weekly_model->model_id);
            if ($model) {
                $save['id'] = $weekly_model->id;
                $save['model_name'] = $model->name;
                print_r($save);
                $this->Visit_model->save_model($save);
            }
        }
    }

    function update_weekly_shortage($id1, $id2) {

        $weekly_models = $this->db->where('visit_id >= ', $id1)->where('visit_id <= ', $id2)->get('weekly_models')->result();
        foreach ($weekly_models as $weekly_model) {
            $model = $this->Model_model->get_model($weekly_model->model_id);
            if ($model) {
                $save['id'] = $weekly_model->id;
                $save['selected_shortage'] = $model->shortage;
                print_r($save);
                $this->Visit_model->save_model($save);
            }
        }
    }

    function update_visit_picture_name() {

        $visits = $this->db->where('comp_id = ', 31)->get('visits')->result();


        foreach ($visits as $visit) {
            $comp = $this->Competitors_activities_model->get_competitors_activities_by_id($visit->comp_id);

            if ($comp) {
                $save['id'] = $visit->id;
                $save['competitor_images'] = $comp->before_images;
                //print_r($save);
                $this->Visit_model->save_visit_picture($save);
            }
        }
    }

    function update_weekly_models() {
        $this->load->helper(array('form', 'date'));
        $this->load->library('form_validation');
        $data['sub_title'] = 'Update Models - Weekly visits';

        $i = 0;

        $data['visits'] = $this->Visit_model->get_visits(200, '', 'id', 'DESC');
        $visits = $this->input->post('visit');

        if ($visits) {
            foreach ($visits as $visit_id) {
                $i++;



                $diff_models = $this->Visit_model->compare_models($visit_id);

                foreach ($diff_models as $model_id) {
                    $model = $this->Model_model->get_model($model_id);
                    $new_model['id'] = false;
                    $new_model['model_id'] = $model_id;
                    $new_model['visit_id'] = $visit_id;
                    $new_model['category_id'] = $model->category_id;
                    $new_model['brand_id'] = $model->brand_id;
                    $new_model['range_id'] = $model->range_id;
                    $new_model['price_range_id'] = $model->price_range_id;
                    $new_model['price'] = $model->price;
                    $new_model['shelf'] = 0;
                    $new_model['ws'] = 0;
                    $new_model['amount'] = 0;
                    // print_r($new_model);
                    $this->Weekly_model_model->save_model($new_model);
                }
            }
        }

        if ($i > 0) {
            $this->session->set_flashdata('message', $i . ' ' . ' visits has been updated successfully.');
            redirect($this->config->item('admin_folder') . '/visits/update_weekly_models');
        } else {
            $this->load->view($this->config->item('admin_folder') . '/update_weekly_models', $data);
        }
        // $this -> load -> view($this -> config -> item('admin_folder') . '/update_weekly_models', $data);
    }

    function get_models($brand_id) {
        header('Content-Type: application/x-json; charset=utf-8');
        $models = array();

        foreach ($this->Model_model->get_models_by_brand($brand_id) as $model) {
            $models[$model->id] = $model->name;
        }
        echo(json_encode($models));
    }

    function get_outlets_not_visited() {

        $data['page_title'] = 'Visits';
        $data['sub_title'] = 'List of outlets not visited';
        $data['pagination'] = $this->pagination->create_links();

        $data['outlets'] = $this->Visit_model->get_outlets_not_visited();




        $this->load->view('outlets_not_visited', $data);
    }

    /*
      function up_monthly_visits() {
      $this->db->select('visits.*, SUM(hcs.models.shelf) as sum_shelf,SUM(hcs.models.price) as sum_price');
      $this->db->from('visits');
      $this->db->join('models', 'models.visit_id=visits.id');
      $this->db->where('sum_shelf > ', 0);
      $this->db->where('sum_price ', 0);
      $result = $this->db->get();
      $visits = $result->result();

      foreach ($visits as $visit) {
      $save['id'] = $visit->id;
      $save['monthly_visit'] = 1;

      print_r($save);
      echo '</br></br>';
      }
      }
     */

    //bcm
    function up_monthly_visits() {
        ini_set('memory_limit', -1);

        $this->db->select('sum(bcc_models.shelf) as sum_shelf,'
                . 'sum(bcc_models.price) as sum_price,'
                . 'visits.*', false);

        $this->db->from('visits');
        $this->db->join('models', 'models.visit_id=visits.id');

        $this->db->group_by('models.visit_id');
        $result = $this->db->get();
        $visits = $result->result();

        foreach ($visits as $visit) {
            if ($visit->sum_shelf > 0 && $visit->sum_price == 0) {
                $save_shelf['id'] = $visit->id;
                $save_shelf['monthly_visit'] = 1;
                $this->Visit_model->save($save_shelf);
                //print_r($save_shelf);
            } else if ($visit->sum_shelf > 0 && $visit->sum_price > 0) {
                $save_shelf_price['id'] = $visit->id;
                $save_shelf_price['monthly_visit'] = 3;
                $this->Visit_model->save($save_shelf_price);
            } else if ($visit->sum_shelf == 0 && $visit->sum_price > 0) {
                $save_price['id'] = $visit->id;
                $save_price['monthly_visit'] = 2;
                $this->Visit_model->save($save_price);
            } else {
                $save_av['id'] = $visit->id;
                $save_av['monthly_visit'] = 0;
                $this->Visit_model->save($save_av);
            }
            echo $visit->id . '---' . $visit->sum_shelf . '***' . $visit->sum_price . '</br></br>';
        }
    }

    function up_quarter() {

        $this->db->select('visits.*', false);
        $this->db->from('visits');
        $result = $this->db->get();
        $visits = $result->result();

        foreach ($visits as $visit) {
            $save['id'] = $visit->id;
            $save['m_date'] = $visit->m_date;
            $save['q_date'] = firstDayOf('quarter', new DateTime($visit->m_date));
            $this->Visit_model->save($save);
            print_r($save);
            echo '<br/>';
        }
    }

    function up_shelf_perc() {
        ini_set('memory_limit', -1);

        $this->db->select('((SUM(CASE WHEN (brand_id =18)THEN shelf ELSE 0 END )) / (SUM( shelf )))*100 AS perc,visits.id', false);
        $this->db->from('models');
        $this->db->join('visits', 'visits.id=models.visit_id');
        $this->db->where('models.shelf >', 0);
        $this->db->group_by('models.visit_id');
        $result = $this->db->get();
        $visits = $result->result();

        foreach ($visits as $visit) {
            $save_shelf_perc['id'] = $visit->id;
            $save_shelf_perc['shelf_perc'] = $visit->perc;
            $this->Visit_model->save($save_shelf_perc);
        }
    }

    function up_desactive_product($date1, $date2) {
        ini_set('memory_limit', -1);
        $this->db->select('models.*', false);
        $this->db->from('models');
        $this->db->join('visits', 'visits.id=models.visit_id');
        $this->db->join('products', 'products.id=models.product_id');
        $this->db->where('visits.date >=', $date1);
        $this->db->where('visits.date <=', $date2);
        $this->db->where('products.active ', 0);
        $result = $this->db->get();
        $models = $result->result();
        foreach ($models as $model) {
            $this->db->where('id', $model->id);
            $this->db->delete('models');
            print_r($model);
            echo '</br>';
        }
    }

    function up_ha($date1, $date2) {
        ini_set('memory_limit', -1);
        $this->db->select('visits.*', false);
        $this->db->from('visits');
        $this->db->where('date >=', $date1);
        $this->db->where('date <=', $date2);
        $result = $this->db->get();
        $visits = $result->result();
        foreach ($visits as $visit) {
            $ha_product_ids = array();
            $outlet_id = $visit->outlet_id;
            $ha_product_ids = $this->Product_model->get_ha_products($outlet_id);
            print_r($ha_product_ids);
            echo '<br/>**********' . $outlet_id . '*******************</br>';
            $visit_id = $visit->id;
            if (!empty($ha_product_ids)) {
                $this->db->select('models.*', false);
                $this->db->from('models');
                //$this->db->where('product_id', $ha_product_ids);
                $this->db->where('visit_id', $visit_id);
                $result = $this->db->get();
                $models = $result->result();
                foreach ($models as $model) {

                    if (in_array($model->product_id, $ha_product_ids) && $model->av != 2) {
                        $save['id'] = $model->id;
                        $save['product_id'] = $model->product_id;

                        $save['av'] = 2;
                        $this->Visit_model->save_model($save);
                        print_r($save);
                        echo '<br/>';
                    } else if (!in_array($model->product_id, $ha_product_ids) && $model->av == 2) {
                        $save['id'] = $model->id;
                        $save['product_id'] = $model->product_id;

                        $save['av'] = 0;
                        $this->Visit_model->save_model($save);
                        print_r($save);
                        echo '<br/>';
                    }
                }
            }



            //print_r($ha_products);
            echo '<br/>';
        }
    }

    //hcm
    function up_visit_picture() {
        $this->db->select('visits.*', false);
        $this->db->from('visits');
//        $array1 = array('one_pictures' => '[]', 'branding_pictures' => '[]');
//        $this->db->not_like($array1);
//        $array2 = array('one_pictures !=' => '', 'branding_pictures !=' => '');
//        $this->db->where($array2);


        $this->db->not_like('one_pictures', '[]');
        $this->db->or_not_like('branding_pictures', '[]');



        $result = $this->db->get();
        $visits = $result->result();

        $i = 0;
        foreach ($visits as $visit) {
            $i++;
            $save['visit_id'] = $visit->id;
            $save['branding_pictures'] = $visit->branding_pictures;
            $save['one_pictures'] = $visit->one_pictures;
            print_r($save);
            echo '<br/>';
            echo '<br/>';

            echo '<br/>';
            $picture_id = $this->Visit_model->save_picture($save);
            echo $picture_id;
            echo '<br/>';
        }
        echo $i;
    }

    function copy_picture() {
        $this->db->select('visits.*', false);
        $this->db->from('visits');
        $this->db->where('date >=', '2019-02-01');
        $this->db->where('date <=', '2019-02-31');
        $this->db->not_like('branding_pictures', '[]');



        $result = $this->db->get();
        $visits = $result->result();

        foreach ($visits as $visit) {
            $branding_pictures = json_decode($visit->branding_pictures);
            foreach ($branding_pictures as $p) {
                print_r($p[0]);
                echo '<br>';
                $path = '/home/capesolu/www/capxyz_henkel/bcm/'; // Full Path to site NOT URL 



                copy($path . 'uploads/branding/' . $p[0], $path . 'uploads/branding1/' . $p[0]);
                copy($path . 'uploads/branding/' . $p[1], $path . 'uploads/branding1/' . $p[1]);
            }
        }
    }

    function up_date() {
        $this->db->select('visits.*', false);
        $this->db->from('visits');
        $this->db->where('date >=', '2019-06-01');
        $this->db->where('date <=', '2019-06-30');
        $result = $this->db->get();
        $visits = $result->result();

        foreach ($visits as $visit) {
            $date = $visit->date;
            $save['id'] = $visit->id;
            $save['w_date'] = firstDayOf('week', new DateTime($date));
            $save['m_date'] = firstDayOf('month', new DateTime($date));
            $save['q_date'] = firstDayOf('quarter', new DateTime($date));

            $this->Visit_model->save($save);
        }
    }

}
