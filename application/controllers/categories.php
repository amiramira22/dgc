<?php

//bcm
class Categories extends CI_Controller {

    //this is used when editing or adding a product_type
    var $product_type_id = false;

    function __construct() {
        parent::__construct();

        $this->load->model(array('Category_model'));
        $this->load->library('Auth');
        $this->load->library('pagination');
    }

    function index() {
        //we're going to use flash data and redirect() after form submissions to stop people from refreshing and duplicating submissions
        //$this->session->set_flashdata('message', 'this is our message');

        $data['page_title'] = 'Categories';
        $data['sub_title'] = 'List of categories';
        //pagination settings
        $config['base_url'] = site_url('categories/index');
        $config['total_rows'] = $this->Category_model->count_categories();
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
        $data['categories'] = $this->Category_model->get_categories($config["per_page"], $data['page'], 'id', 'DESC');

        $data['pagination'] = $this->pagination->create_links();

        //load the department_view
        $this->load->view('categories', $data);
    }

    function form($id = false) {
        force_ssl();
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['page_title'] = 'Categories';
        $data['sub_title'] = 'Category Form';



        //default values are empty if the chef is new
        $data['id'] = '';
        $data['code'] = '';
        $data['name'] = '';
        $data['abrev_name'] = '';
        $data['active'] = false;

        if ($id) {
            $category = $this->Category_model->get_category($id);
            //if the product_type does not exist, redirect them to the product_type list with an error
            if (!$category) {
                $this->session->set_flashdata('error', 'The requested Category could not be found.');
                redirect('categories');
            }

            //set values to db values
            $data['id'] = $category->id;
            $data['code'] = $category->code;
            $data['name'] = $category->name;
            $data['abrev_name'] = $category->abrev_name;
            $data['active'] = $category->active;
        }

        $this->form_validation->set_rules('code', 'lang:code', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('name', 'lang:name', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('active', 'lang:active');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('category_form', $data);
        } else {
            $save['id'] = $id;
            $save['code'] = $this->input->post('code');
            $save['name'] = $this->input->post('name');
            $save['abrev_name'] = $this->input->post('abrev_name');
            $save['active'] = $this->input->post('active');
            $this->Category_model->save($save);
            $this->session->set_flashdata('message', 'The Category has been saved.');

            //go back to the product_type list
            redirect('categories');
        }
    }

    function delete($id = false) {
        if ($id) {
            $category = $this->Category_model->get_category($id);
            //if the product_type does not exist, redirect them to the product_type list with an error
            if (!$category) {
                $this->session->set_flashdata('error', 'The requested Category could not be found.');
                redirect('categories');
            } else {
                //if the product_type is legit, delete them
                $delete = $this->Category_model->delete($id);

                $this->session->set_flashdata('message', 'The Category has been deleted.');
                redirect('categories');
            }
        } else {
            //if they do not provide an id send them to the chef list page with an error
            $this->session->set_flashdata('error', 'The requested Category could not be found.');
            redirect('categories');
        }
    }

}
