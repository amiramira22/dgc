<?php

class Messages extends CI_Controller {

    //this is used when editing or adding a channel
    var $message_id = false;

    function __construct() {
        parent::__construct();

        $this->load->model(array('Message_model', 'Admin_model', 'New_model_model', 'Competitor_ads_model'));
        $this->load->library('Auth');
        $this->load->helper('formatting_helper');
        $this->lang->load('message');
    }

    function test() {
        $admin = $this->session->userdata('admin');
        $admin_id = $admin['id'];
        $messages = $this->Message_model->get_messages_by_admin_no_view($admin_id);
        foreach ($messages as $message) {

            $save['id'] = $message->id;
            $save['active'] = 1;
            $this->Message_model->save($save);
        }
        redirect('messages');
    }

    function index($nb = null) {
        //we're going to use flash data and redirect() after form submissions to stop people from refreshing and duplicating submissions
        //$this->session->set_flashdata('message', 'this is our message');

        $data['page_title'] = 'Messages';
        $data['sub_title'] = 'Manage Messages';

        $admin = $this->session->userdata('admin');
        $admin_id = $admin['id'];
        $field = 'id';
        $by = 'DESC';
        $page = 0;
        if ($nb) {

            $ms = $this->Message_model->get_messages_by_admin(200, $page, $field, $by, $admin_id);
            foreach ($ms as $m) {
                $save_m['id'] = $m->id;
                $save_m['active'] = 1;

                $this->Message_model->save($save_m);
            }
        }

        $data['messagess'] = $this->Message_model->get_messages_by_admin(200, $page, $field, $by, $admin_id);



        $data['page'] = $page;
        $data['field'] = $field;
        $data['by'] = $by;

        $this->load->view('messages', $data);
    }

    function form($id = false) {
        force_ssl();
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['page_title'] = 'Message';
        $data['sub_title'] = 'Form Message';
//        if ($this->auth->check_access('Admin')) {
//
//            $data['admins'] = $this->auth->get_fo_list();
//        } else {
//            $data['admins'] = $this->auth->get_fo_list();
//        }
        $data['admins'] = $this->auth->get_fo_list();

        $admin = $this->session->userdata('admin');
        $sender_id = $admin['id'];

        //default values are empty if the chef is new
        $data['id'] = '';
        $data['sender_id'] = $sender_id;
        $data['receiver_id'] = '';

        $data['message'] = '';
        $data['created'] = '';


        if ($id) {
            $this->message_id = $id;
            $message = $this->Message_model->get_message($id);
            //if the channel does not exist, redirect them to the channel list with an error
            if (!$message) {
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect($this->config->item('admin_folder') . '/messages');
            }

            //set values to db values

            $data['id'] = $message->id;
            $data['sender_id'] = $sender_id;
            $data['receiver_id'] = $message->receiver_id;
            $data['message'] = $message->message;

            $data['created'] = $message->created;
        }

        $this->form_validation->set_rules('message', 'lang:message', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view($this->config->item('admin_folder') . '/message_form', $data);
        } else {

            $save['id'] = $id;
            $save['sender_id'] = $sender_id;
            $message = $this->input->post('message');
            $save['message'] = $message;



            $receiver_ids = $this->input->post('receiver_ids');
//            print_r($receiver_ids);
//            die();

            foreach ($receiver_ids as $rec_id) {
                if ($rec_id == 0) {
                    $admins = $this->auth->get_fo_list();
                    foreach ($admins as $admin) {
                        $save['receiver_id'] = $admin->id;
                        $this->Message_model->save($save);
                        $this->send_gcm($mesage, $admin->register_id);
                    }
                } else {
                    $save['receiver_id'] = $rec_id;
                    $this->Message_model->save($save);
                    $admin = $this->Admin_model->get_admin_by_id($receiver_id);
                    $this->send_gcm($message, $admin->register_id);
                }
            }



            $this->session->set_flashdata('message', 'Message has been send');

            //go back to the channel list
            redirect('messages');
        }
    }

    function delete($id = false) {
        if ($id) {
            $message = $this->Message_model->get_message($id);
            //if the channel does not exist, redirect them to the channel list with an error
            if (!$message) {
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect($this->config->item('admin_folder') . '/messages');
            } else {
                //if the channel is legit, delete them
                $delete = $this->Message_model->delete($id);

                $this->session->set_flashdata('error', 'Message has been deleted');
                redirect($this->config->item('admin_folder') . '/messages');
            }
        } else {
            //if they do not provide an id send them to the chef list page with an error
            $this->session->set_flashdata('error', lang('error_not_found'));
            redirect($this->config->item('admin_folder') . '/messages');
        }
    }

    function view($admin_id, $by = 'DESC', $page = 0) {
        //we're going to use flash data and redirect() after form submissions to stop people from refreshing and duplicating submissions
        //$this->session->set_flashdata('message', 'this is our message');
        $field = 'id';
        $data['messages'] = $this->Message_model->get_messages_by_admin(20, $page, 'id', $by, $admin_id);
        foreach ($data['messages'] as $message) {

            $this->Message_model->viewed($message->id);
        }

        $data['page_title'] = 'List Of Messages';

        $this->load->library('pagination');

        $config['base_url'] = base_url() . '/' . $this->config->item('admin_folder') . '/messages/index/' . $field . '/' . $by . '/';
        $config['total_rows'] = $this->Message_model->count_messages();
        $config['per_page'] = 20;
        $config['uri_segment'] = 6;
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['full_tag_open'] = '<div class="pagination"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $data['page'] = $page;
        $data['field'] = $field;
        $data['by'] = $by;

        $this->load->view($this->config->item('admin_folder') . '/view_messages', $data);
    }

    function activate_view($admin_id) {
        $messages = $this->Message_model->get_messages_by_admin(200, 0, 'id', 'ASC', $admin_id);
        foreach ($messages as $messages) {
            $this->Message_model->viewed($admin_id);
        }
    }

    public function send_gcm($message, $register_id) {
        $this->load->library('gcm');

        $this->gcm->setMessage($message);

        $this->gcm->addRecepient($register_id);

        $this->gcm->setTtl(false);

        $this->gcm->setGroup(false);

        $this->gcm->send();
    }

}
