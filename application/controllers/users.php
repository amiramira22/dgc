<?php

class Users extends CI_Controller {

    //these are used when editing, adding or deleting an admin
    var $admin_id = false;
    var $current_admin = false;

    function __construct() {
        parent::__construct();
        //$this->auth->check_access('Admin', true);
        //load the admin language file in
        $this->auth->is_logged_in();
        $this->auth->check_access('Admin', true);
        $this->load->library('pagination');
        $this->load->model(array('User_model'));
    }

    function update_fo() {
        $id = $_POST['id'];
        $age = $_POST['age'];
        $zone = $_POST['zone'];
        $name = $_POST['name'];
        $tel = $_POST['tel'];
        $date = $_POST['date'];
        $niveau = $_POST['niveau'];
        $save['id'] = $id;
        $save['age'] = $age;
        $save['zone'] = $zone;
        $save['name'] = $name;
        $save['tel'] = $tel;
        $save['integration_date'] = $date;
        $save['niveau'] = $niveau;


        $this->auth->save($save);
    }

    function index() {

        $data['page_title'] = 'Users';
        $data['sub_title'] = 'List of Users';



        //pagination settings
        $config['base_url'] = site_url('users/index');
        $config['total_rows'] = $this->User_model->count_users();
        $config['per_page'] = "20";
        $config["uri_segment"] = 3;
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
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        //call the model function to get the department data
        $data['users'] = $this->User_model->get_users($config["per_page"], $data['page'], 'id', 'DESC');

        $data['pagination'] = $this->pagination->create_links();



        $this->load->view('users', $data);
    }

    function profile($id = false) {
        //force_ssl();

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $data['page_title'] = 'User Form';
        $data['title1'] = 'Users';
        $data['title2'] = 'Profil';

        //default values are empty if the customer is new
        $data['id'] = '';
        $data['firstname'] = '';
        $data['lastname'] = '';
        $data['email'] = '';
$data['states'] = '';
        $data['username'] = '';

        if ($id) {
            $this->admin_id = $id;
            $admin = $this->auth->get_admin($id);
            //if the administrator does not exist, redirect them to the admin list with an error
            if (!$admin) {
                $this->session->set_flashdata('message', lang('admin_not_found'));
                redirect('users');
            }
            //set values to db values
            $data['id'] = $admin->id;
            $data['firstname'] = $admin->firstname;
            $data['lastname'] = $admin->lastname;
            $data['email'] = $admin->email;
            $data['states'] = $admin->states;
            $data['username'] = $admin->username;
        }

        $this->form_validation->set_rules('firstname', 'lang:firstname', 'trim|max_length[32]');
        $this->form_validation->set_rules('lastname', 'lang:lastname', 'trim|max_length[32]');
        $this->form_validation->set_rules('email', 'lang:email', 'trim|required|valid_email|max_length[128]|callback_check_email');


        //if this is a new account require a password, or if they have entered either a password or a password confirmation
        if ($this->input->post('password') != '' || $this->input->post('confirm') != '' || !$id) {
            $this->form_validation->set_rules('password', 'lang:password', 'required|min_length[6]|sha1');
            $this->form_validation->set_rules('confirm', 'lang:confirm_password', 'required|matches[password]');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->load->view($this->config->item('admin_folder') . '/profile', $data);
        } else {
            $admin = $this->auth->get_admin($id);
            $save['id'] = $id;
            $save['firstname'] = $this->input->post('firstname');
            $save['lastname'] = $this->input->post('lastname');
            $save['email'] = $this->input->post('email');
            $save['states'] = $admin->states;
            $save['username'] = $admin->username;

            if ($this->input->post('password') != '' || !$id) {
                $save['password'] = $this->input->post('password');
            }

            $this->auth->save($save);

            $this->session->set_flashdata('message', 'Your profile has been changed!');

            //go back to the customer list
            redirect('welcome');
        }
    }

    function delete($id) {
        //even though the link isn't displayed for an admin to delete themselves, if they try, this should stop them.
        if ($this->current_admin['id'] == $id) {
            $this->session->set_flashdata('message', lang('error_self_delete'));
            redirect('users');
        }

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

        $user = $this->auth->get_admin($id);
        $data = json_encode($user);
        $save['data'] = $data;

        $this->Log_model->save_log($save);

        //delete the user
        $this->auth->delete($id);
        $this->session->set_flashdata('message', lang('message_user_deleted'));
        redirect('users');
    }

    function form($id = false) {
        //force_ssl();

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $data['page_title'] = 'Users';
        $data['sub_title'] = 'User Form';

        $config['upload_path'] = 'uploads/users';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);

        //default values are empty if the customer is new
        $data['id'] = '';
        $data['name'] = '';
        $data['email_user'] = '';
        $data['access_user'] = '';
        $data['states'] = '';
        $data['username'] = '';
        $data['photos'] = '';

        if ($id) {
            $this->admin_id = $id;
            $user = $this->auth->get_admin($id);
            //if the administrator does not exist, redirect them to the admin list with an error
            if (!$user) {
                $this->session->set_flashdata('message', lang('admin_not_found'));
                redirect('users');
            }
            //set values to db values
            $data['id'] = $user->id;
            $data['name'] = $user->name;
            $data['email_user'] = $user->email;
            $data['access_user'] = $user->access;
            $data['states'] = $user->states;
            $data['username'] = $user->username;
            $data['photos'] = $user->photos;
        }

        $this->form_validation->set_rules('firstname', 'lang:firstname', 'trim|max_length[32]');
        $this->form_validation->set_rules('lastname', 'lang:lastname', 'trim|max_length[32]');
        $this->form_validation->set_rules('email', 'lang:email', 'trim|required|valid_email|max_length[128]|callback_check_email');
        $this->form_validation->set_rules('access', 'lang:access', 'trim|required');

        //if this is a new account require a password, or if they have entered either a password or a password confirmation
        if ($this->input->post('password') != '' || $this->input->post('confirm') != '' || !$id) {
            $this->form_validation->set_rules('password', 'lang:password', 'required|min_length[6]|sha1');
            $this->form_validation->set_rules('confirm', 'lang:confirm_password', 'required|matches[password]');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->load->view( 'user_form', $data);
        } else {
            $save['id'] = $id;


            if ($id) {
                $save['id'] = $id;
                $uploaded = $this->upload->do_upload('photos');
                //delete the original file if another is uploaded
                if ($uploaded) {
                    if ($data['photos'] != '') {
                        $file = 'uploads/users/' . $data['photos'];

                        //delete the existing file if needed
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                }
            } else {
                
            }

            if ($uploaded) {
                $image = $this->upload->data();
                $save['photos'] = $image['file_name'];
            }
            $save['name'] = $this->input->post('name');
            $save['email'] = $this->input->post('email');
            $save['access'] = $this->input->post('access');
            $save['username'] = $this->input->post('username');
             $save['states'] = $this->input->post('states');
            if ($this->input->post('password') != '' || !$id) {
                $save['password'] = $this->input->post('password');
            }



            $this->auth->save($save);

            $this->session->set_flashdata('message', 'The user has been saved!');

            //go back to the customer list
            redirect('users');
        }
    }

    function check_email($str) {
        $email = $this->auth->check_email($str, $this->admin_id);
        if ($email) {
            $this->form_validation->set_message('check_email', 'The requested email is already in use.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function desactivate($id) {
        $admin = array('id' => $id, 'active' => 0);
        $this->auth->save($admin);
        $this->session->set_flashdata('message', lang('message_user_saved'));
        redirect('users');
    }

    function activate($id) {
        $admin = array('id' => $id, 'active' => 1);
        $this->auth->save($admin);
        $this->session->set_flashdata('message', lang('message_user_saved'));
        redirect('users');
    }

}
