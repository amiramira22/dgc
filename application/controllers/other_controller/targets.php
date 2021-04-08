<?php

class Targets extends CI_Controller {

    var $connected_user_id = false;

    function __construct() {
        parent::__construct();

        if (!$this->auth->is_logged_in(false, false)) {

            redirect('login');
        }

        $admin = $this->session->userdata('admin');
        $this->connected_user_id = $admin['id'];

        $this->load->model(array('Outlet_model', 'Target_model', 'Admin_model'));
        $this->load->helper(array('form', 'date'));
        $this->load->library('pagination');
    }

    public function index() {
        $data['page_title'] = 'Targets';
        $data['sub_title'] = 'List of targets';

        //pagination settings
        $config['base_url'] = site_url('targets/index');

        //Total row


        $config['total_rows'] = $this->Target_model->count_targets();
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
        $data['targets'] = $this->Target_model->get_targets($config["per_page"], $data['page'], 'targets.id', 'DESC');

        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('targets', $data);
    }

    function form($id = false) {



        $this->load->library('form_validation');
        $this->load->helper(array('form', 'date'));


        $data['page_title'] = 'Visits';
        $data['sub_title'] = 'Vist Form';




        //default values are empty if the modern_visit is new
        $data['id'] = false;
        $data['date'] = '';





        $this->form_validation->set_rules('date', 'Date');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('target_form', $data);
        } else {
            $date = $this->input->post('date');

            $this->load->helper('date');


            $outlets = $this->Outlet_model->get_active_outlets();
            $array = daycount(strtotime($date));
            foreach ($outlets as $outlet) {
                $visit_days = json_decode($outlet->visit_day);

                $nb = 0;
                foreach ($visit_days as $day) {

                    $nb = $nb + $array[$day];
                }
                $save['id'] = false;
                $save['outlet_id'] = $outlet->id;
                $save['m_date'] = $date;
                $save['nb_visit'] = $nb;
                $this->Target_model->save($save);
            }



            redirect('targets');
        }
    }

// end form

    function delete($id = false) {
        if ($id) {
            $target = $this->Target_model->get_target($id);

            if (!$target) {
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect('targets');
            } else {


                $delete = $this->Target_model->delete($id);

                $this->session->set_flashdata('error', lang('message_visit_deleted'));
                redirect($this->config->item('admin_folder') . '/targets');
            }
        } else {
            //if they do not provide an id send them to the modern_visit list page with an error
            $this->session->set_flashdata('error', lang('error_not_found'));
            redirect('targets');
        }
    }

    function models($visit_id = false) {
        //$this->output->cache(3600);

        $this->load->helper(array('form', 'date'));
        $this->load->library('form_validation');

        $data['page_title'] = 'Visits';

        $visit = $this->Visit_model->get_visit($visit_id);
        $data['monthly'] = $visit->monthly_visit;
        $data['models'] = $this->Visit_model->get_detail_models($visit_id);
        $data['id'] = $visit_id;
        $outlet_name = $this->Outlet_model->get_outlet_name($visit->outlet_id);
        $data['sub_title'] = "Visit | " . $outlet_name . " | " . format_week($visit->date);



        $this->load->view('visit_model_form', $data);
    }

    function bulk_save($visit_id) {

        $models = $this->input->post('model');

        if (!$models) {
            $this->session->set_flashdata('error', lang('error_bulk_no_models'));
            redirect('visits');
        }

        foreach ($models as $id => $model) {
            $model['id'] = $id;
            $shelf = $model['shelf'];
            $sku_display = $model['sku_display'];
            $nb_sku = $model['nb_sku'];
            $av = $model['av'];
            $price = $model['price'];


            if ($shelf == '') {
                $model['shelf'] = 0;
            }
            if ($nb_sku == '' || $nb_sku == 0) {
                $nb_sku = 1;
            }
            if ($price == '') {
                $model['price'] = 0;
            }


            //$model['nb_sku']=$nb_sku;
            if ($sku_display > 0) {
                $model['av_sku'] = $sku_display / $nb_sku;
            }



            $this->Visit_model->save_bulk($model);
        }




        $this->session->set_flashdata('message', 'Models have been saved successfully !');
        redirect('visits');
    }

    function report($visit_id = false) {
        //$this->output->cache(3600);

        $this->load->helper(array('form', 'date'));
        $this->load->library('form_validation');

        $data['page_title'] = 'Visits';
        $data['old'] = '';
        $data['id_rayon'] = '';
        $visit = $this->Visit_model->get_visit($visit_id);
        $data['models'] = $this->Visit_model->get_detail_models($visit_id);
        $data['pictures'] = $visit;
        $data['id'] = $visit_id;
        $outlet_name = $this->Outlet_model->get_outlet_name($visit->outlet_id);
        $data['sub_title'] = "Report  | " . $outlet_name . " | " . reverse_format($visit->date);
        $this->load->view('visit_report', $data);
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

        $search_user_id = ($this->input->post("user_id")) ? $this->input->post("user_id") : "-1";
        $search_user_id = ($this->uri->segment(6)) ? $this->uri->segment(6) : $search_user_id;
        if ($search_user_id != -1) {
            $user_name = $this->Admin_model->get_admin_name($search_user_id);
        } else {
            $user_name = 'All Fieled Officer';
        }
        $data['page_title'] = 'Weekly Visits' . '|' . $user_name . '|' . format_week($start_date) . '|' . format_week($end_date);





        //pagination settings
        $config['base_url'] = site_url($this->config->item('admin_folder') . '/visits/search/' . $search . '/' . $start_date . '/' . $end_date . '/' . $search_user_id);

        //Total row


        $config['total_rows'] = $this->Visit_model->count_visits_search($search, $search_user_id, $start_date, $end_date);
        $config['per_page'] = "10";
        $config["uri_segment"] = 7;
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
        $data['page'] = ($this->uri->segment(7)) ? $this->uri->segment(7) : 0;

        //call the model function to get the department data
        $data['visits'] = $this->Visit_model->get_visits_search($config["per_page"], $data['page'], 'visits.id', 'DESC', $search, $search_user_id, $start_date, $end_date);

        $data['pagination'] = $this->pagination->create_links();

        //load the department_view
        $this->load->view($this->config->item('admin_folder') . '/visits', $data);
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

    function copy($id = false) {

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
        $comp_id = $this->Competitors_activities_model->save($save_com);

        // voice of dealer

        $save_voice['admin_id'] = $this->input->post('admin_id');
        $save_voice['outlet_id'] = $visit->outlet_id;
        $save_voice['date'] = date("Y-m-d", strtotime($this->input->post('date')));
        $save_voice['date_taken'] = date("Y-m-d");
        $voice_id = $this->Voice_dealer_model->save($save_com);
        //brand_id

        $save_branding['admin_id'] = $this->input->post('admin_id');
        $save_branding['outlet_id'] = $visit->outlet_id;
        $save_branding['type'] = 'brand';
        $save_branding['date'] = date("Y-m-d");
        $save_branding['date_taken'] = date("Y-m-d");



        $branding_id = $this->Picture_model->save($save_branding);


        $save_display['admin_id'] = $visit->admin_id;
        $save_display['outlet_id'] = $visit->outlet_id;
        $save_display['type'] = 'display';
        $save_display['date'] = date("Y-m-d");
        $save_display['date_taken'] = date("Y-m-d");



        $branding_id = $this->Picture_model->save($save_display);


        $data['comp_id'] = $comp_id;
        $data['voice_id'] = $voice_id;
        $data['branding_id'] = $branding_id;


        $save_check['admin_id'] = $visit->admin_id;
        $save_check['type'] = 'Weekly visit';
        $save_check['date'] = date('Y-m-d');
        $save_check['visit_id'] = 200;
        $this->Check_position_model->save($save_check);


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

}
