<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('formatting_helper');
        $this->load->library('pagination');
        $this->load->helper(array('form', 'date'));
        $this->load->model(array('Upload_model'));
    }

    public function index() {
        $data_header['page_title'] = "LIST OF ANDROID APP";
        $data_header['sub_title'] = "";
        $data['files'] = $this->Upload_model->get_file();

        $this->load->view('header', $data_header);
        $this->load->view('upload', $data);
        $this->load->view('footer');
    }

    function form($id = false) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['page_title'] = 'ANDROID APP';
        $data['sub_title'] = 'ADD FILE';

        $config['upload_path'] = 'uploads/apk';
        $config['allowed_types'] = '*';

        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);
        //default values are empty if the FILE is new
        $data['id'] = '';
        $data['name'] = '';
        $data['version'] = '';
        $data['file'] = '';

        if ($id) {
            //$this->file_id = $id;
            $file = $this->Upload_model->get_file($id);
            //if the FILE does not exist, redirect them to the FIlE list with an error
            if (!$file) {
                $this->session->set_flashdata('error', 'error not found');
                //le controller 
                redirect('upload');
            }
            $data['sub_title'] = 'FILe Form |' . $file->name;

            //set values to db values

            $data['id'] = $file->id;
            $data['name'] = $file->name;
            $data['version'] = $file->version;
            $data['file'] = $file->file;
        }

        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('version', 'Version', 'trim|required|max_length[50]');


        if ($this->form_validation->run() == FALSE) {
            //le formulaire
            $this->load->view('upload_form', $data);
        } else {
            //$save['id']		= $id;
            $save['name'] = $this->input->post('name');
            $save['version'] = $this->input->post('version');
            $save['file'] = $this->input->post('file');

            $uploaded = $this->upload->do_upload('file');

            if ($id) {
                $save['id'] = $id;
                //delete the original file if another is uploaded
                if ($uploaded) {
                    if ($data['file'] != '') {
                        $file = 'uploads/apk/' . $data['file'];

                        //delete the existing file if needed
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                }
            } else {
                if (!$uploaded) {
                    $data['error'] = $this->upload->display_errors();
                    //le formulaire
                    $this->load->view('/upload_form', $data);
                    return;
                    //end script here if there is an error
                }
            }
            if ($uploaded) {
                $file = $this->upload->data();
                $save['file'] = $file['file_name'];
            }

            //print_r($save);

            $this->Upload_model->save($save);

            $this->session->set_flashdata('message', 'FILE has been saved');

            //go back to the APK list
            redirect('upload');
        }
    }

    function delete($id = false) {
        if ($id) {
            $file = $this->Upload_model->get_file($id);
            //if the file does not exist, redirect them to the file list with an error
            if (!$file) {
                $this->session->set_flashdata('error', 'Error not found');
                redirect('upload');
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

                $data = json_encode($file);
                $save['data'] = $data;

                $this->Log_model->save_log($save);

                //if the file is legit, delete them
                $delete = $this->Upload_model->delete($id);

                $this->session->set_flashdata('error', 'file has been deleted');
                redirect('upload');
            }
        } else {
            //if they do not provide an id send them to the file list page with an error
            $this->session->set_flashdata('error', 'error not found');
            redirect('upload');
        }
    }

}
