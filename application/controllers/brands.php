<?php

class Brands extends CI_Controller {

    //this is used when editing or adding a customer
    var $brand_id = false;

    function __construct() {
        parent::__construct();

        $this->load->model(array('Brand_model'));
        $this->load->helper('formatting_helper');
        $this->load->library('pagination');
    }

    function index() {
        $data['page_title'] = 'Brands';
        $data['sub_title'] = 'list of Brands';

        //pagination settings
        $config['base_url'] = site_url('brands/index');
        $config['total_rows'] = $this->Brand_model->count_brands();
        $config['per_page'] = "30";
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
        $data['brands'] = $this->Brand_model->get_brands($config["per_page"], $data['page'], 'id', 'DESC');

        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('brands', $data);
    }

    function form($id = false) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['page_title'] = 'Brand';
        $data['sub_title'] = 'Brand Form';
        //default values are empty if the brand is new
        $data['id'] = '';
        $data['code'] = '';
        $data['name'] = '';
        $data['color'] = '';
        $data['selected'] = false;

        $data['active'] = false;




        if ($id) {
            $this->brand_id = $id;
            $brand = $this->Brand_model->get_brand($id);
            //if the brand does not exist, redirect them to the customer list with an error
            if (!$brand) {
                $this->session->set_flashdata('error', 'error not found');
                redirect('brands');
            }
            $data['sub_title'] = 'Brand Form |' . $brand->name;

            //set values to db values

            $data['id'] = $brand->id;
            $data['code'] = $brand->code;
            $data['name'] = $brand->name;
            $data['active'] = $brand->active;
            $data['color'] = $brand->color;
            $data['selected'] = $brand->selected;
        }

        $this->form_validation->set_rules('code', 'lang:firstname', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('name', 'lang:lastname', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('active', 'lang:active');




        if ($this->form_validation->run() == FALSE) {
            $this->load->view('brand_form', $data);
        } else {
            $save['id'] = $id;
            $save['code'] = $this->input->post('code');
            $save['name'] = $this->input->post('name');
            $save['active'] = $this->input->post('active');
            $save['selected'] = $this->input->post('selected');

            $save['color'] = $this->input->post('color');

            $this->Brand_model->save($save);

            $this->session->set_flashdata('message', 'Brand has been saved');

            //go back to the brand list
            redirect('brands');
        }
    }

    function delete($id = false) {
        if ($id) {
            $brand = $this->Brand_model->get_brand($id);
            //if the brand does not exist, redirect them to the brand list with an error
            if (!$brand) {
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect('brands');
            } else {
                //if the brand is legit, delete them
                $delete = $this->Brand_model->delete($id);

                $this->session->set_flashdata('error', 'brand has been deleted');
                redirect('brands');
            }
        } else {
            //if they do not provide an id send them to the brand list page with an error
            $this->session->set_flashdata('error', 'error not found');
            redirect('brands');
        }
    }

}
