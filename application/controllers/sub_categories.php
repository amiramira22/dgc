<?php
//bcm
class Sub_categories extends CI_Controller {

    //this is used when editing or adding a customer
    var $sub_category_id = false;

    function __construct() {
        parent::__construct();

        $this->load->model(array('Category_model', 'Sub_category_model'));

        $this->load->library('pagination');
    }

    function index() {
        //we're going to use flash data and redirect() after form submissions to stop people from refreshing and duplicating submissions
        //$this->session->set_flashdata('message', 'this is our message');

        $data['page_title'] = 'Sub Categories';
        $data['sub_title'] = 'List of sub categories';
        //pagination settings
        $config['base_url'] = site_url('sub_categories/index');
        $config['total_rows'] = $this->Sub_category_model->count_sub_categories();
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
        $data['sub_categories'] = $this->Sub_category_model->get_sub_categories($config["per_page"], $data['page'], 'id', 'DESC');

        $data['pagination'] = $this->pagination->create_links();

        //load the department_view
        $this->load->view('sub_categories', $data);
    }

    function form($id = false) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $data['page_title'] = 'Sub Categories';
        $data['sub_title'] = 'Sub Category Form';


        //default values are empty if the category is new
        $data['categories'] = $this->Category_model->get_categories();
        $data['id'] = '';
        $data['code'] = '';
        $data['name'] = '';
        $data['category_id'] = '';
        $data['active'] = false;




        if ($id) {
            $this->category_id = $id;
            $category = $this->Sub_category_model->get_sub_category($id);
            //if the category does not exist, redirect them to the customer list with an error
            if (!$category) {
                $this->session->set_flashdata('error', 'The requested Category could not be found.');
                redirect('sub_categories');
            }


            //set values to db values
            $data['id'] = $category->id;
            $data['code'] = $category->code;
            $data['name'] = $category->name;
            $data['category_id'] = $category->category_id;
        }

        $this->form_validation->set_rules('code', 'Code', 'trim|required|max_length[20]');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[30]');




        if ($this->form_validation->run() == FALSE) {
            $this->load->view('sub_category_form', $data);
        } else {
            $save['id'] = $id;
            $save['code'] = $this->input->post('code');
            $save['name'] = $this->input->post('name');
            $save['category_id'] = $this->input->post('category_id');

            $this->Sub_category_model->save($save);

            $this->session->set_flashdata('message', 'The sub category has been saved!');

            //go back to the category list
            redirect('sub_categories');
        }
    }

    function delete($id = false) {
        if ($id) {
            $category = $this->Sub_category_model->get_sub_category($id);
            //if the category does not exist, redirect them to the category list with an error
            if (!$category) {
                $this->session->set_flashdata('error', 'The requested Category could not be found.');
                redirect('sub_categories');
            } else {
                //if the category is legit, delete them
                $delete = $this->Sub_category_model->delete($id);

                $this->session->set_flashdata('error', 'Sub Category has been deleted');
                redirect('sub_categories');
            }
        } else {
            //if they do not provide an id send them to the category list page with an error
            $this->session->set_flashdata('error', 'The requested Category could not be found.');
            redirect('sub_categories');
        }
    }

}
