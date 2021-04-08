<?php

class Product_groups extends CI_Controller {

    //this is used when editing or adding a customer
    var $product_group_id = false;

    function __construct() {
        parent::__construct();

        $this->load->model(array('Category_model', 'Cluster_model', 'Sub_category_model', 'Brand_model', 'Product_group_model'));

        $this->load->library('pagination');
    }

    function index() {
        //we're going to use flash data and redirect() after form submissions to stop people from refreshing and duplicating submissions
        //$this->session->set_flashdata('message', 'this is our message');

        $data['page_title'] = 'Product groups';
        $data['sub_title'] = 'List of product groups';

        $data['product_groups'] = $this->Product_group_model->get_product_groups();



        //load the department_view
        $this->load->view('product_groups', $data);
    }

    function form($id = false) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $data['page_title'] = 'Product groups';
        $data['sub_title'] = 'Product group Form';
        $data['clusters'] = $this->Cluster_model->get_clusters();

        //default values are empty if the category is new
        $data['brands'] = $this->Brand_model->get_brands();
        $data['categories'] = $this->Category_model->get_categories();
        $data['sub_categories'] = $this->Sub_category_model->get_sub_categories();
        $data['id'] = '';
        $data['code'] = '';
        $data['name'] = '';
        $data['metrage'] = '';
        $data['shelf_unit'] = '';
        $data['brand_id'] = '';
        $data['category_id'] = '';
        $data['cluster_id'] = '';
        $data['sub_category_id'] = '';
        $data['active'] = false;



        if ($id) {
            $this->category_id = $id;
            $category = $this->Product_group_model->get_product_group($id);
            //if the category does not exist, redirect them to the customer list with an error
            if (!$category) {
                $this->session->set_flashdata('error', 'The requested Product group could not be found.');
                redirect('product_groups');
            }


            //set values to db values
            $data['id'] = $category->id;
            $data['code'] = $category->code;
            $data['name'] = $category->name;
            $data['brand_id'] = $category->brand_id;
            $data['category_id'] = $category->category_id;
            $data['sub_category_id'] = $category->sub_category_id;
            $data['metrage'] = $category->metrage;
            $data['cluster_id'] = $category->cluster_id;
            $data['sub_category_id'] = $category->sub_category_id;
            $data['shelf_unit'] = $category->shelf_unit;
        }

        $this->form_validation->set_rules('code', 'Code', 'trim|required|max_length[20]');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[30]');


        if ($this->form_validation->run() == FALSE) {
            $this->load->view('product_group_form', $data);
        } else {
            $save['id'] = $id;
            $save['code'] = $this->input->post('code');
            $save['name'] = $this->input->post('name');
            $save['brand_id'] = $this->input->post('brand_id');
            $save['category_id'] = $this->input->post('category_id');
            $save['sub_category_id'] = $this->input->post('sub_category_id');
            $save['shelf_unit'] = $this->input->post('shelf_unit');
            $save['cluster_id'] = $this->input->post('cluster_id');
           // $save['metrage'] = $this->input->post('metrage');
            
            $this->Product_group_model->save($save);

            $this->session->set_flashdata('message', 'The product group has been saved!');

            //go back to the category list
            redirect('product_groups');
        }
    }

    function delete($id = false) {
        if ($id) {
            $category = $this->Product_group_model->get_product_group($id);
            //if the category does not exist, redirect them to the category list with an error
            if (!$category) {
                $this->session->set_flashdata('error', 'The requested Category could not be found.');
                redirect('product_groups');
            } else {
                //if the category is legit, delete them
                $delete = $this->Product_group_model->delete($id);

                $this->session->set_flashdata('error', 'Sub Category has been deleted');
                redirect('product_groups');
            }
        } else {
            //if they do not provide an id send them to the category list page with an error
            $this->session->set_flashdata('error', 'The requested Category could not be found.');
            redirect('product_groups');
        }
    }
    
    function desactivate($id) {
        $product = array('id' => $id, 'active' => 0);
        $this->Product_group_model->save($product);
        $this->session->set_flashdata('message', lang('message_product_saved'));
        redirect('product_groups');
    }

    function activate($id) {
        $product = array('id' => $id, 'active' => 1);
        $this->Product_group_model->save($product);
        $this->session->set_flashdata('message', lang('message_product_saved'));
        redirect('product_groups');
    }

}
