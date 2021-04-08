<?php

//bcm
class Clusters extends CI_Controller {

    //this is used when editing or adding a customer
    var $cluster_id = false;

    function __construct() {
        parent::__construct();

        $this->load->model(array('Cluster_model', 'Category_model', 'Sub_category_model'));
        $this->load->helper('formatting_helper');
        $this->load->library('pagination');
    }

    function index() {
        $data['page_title'] = 'Clustering';
        $data['sub_title'] = 'List of clusters';

        //pagination settings
        $config['base_url'] = site_url('clusters/index');
        $config['total_rows'] = $this->Cluster_model->count_clusters();
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
        $data['clusters'] = $this->Cluster_model->get_clusters($config["per_page"], $data['page'], 'id', 'DESC');

        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('clusters', $data);
    }

    function form($id = false) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['page_title'] = 'Clustering';
        $data['sub_title'] = 'Cluster Form';
        //default values are empty if the cluster is new
        $data['id'] = '';
        $data['code'] = '';
        $data['name'] = '';
        $data['sub_category_id'] = '';
        $data['sub_categories'] = $this->Sub_category_model->get_sub_categories();


        $data['active'] = false;




        if ($id) {
            $this->cluster_id = $id;
            $cluster = $this->Cluster_model->get_cluster($id);
            //if the cluster does not exist, redirect them to the customer list with an error
            if (!$cluster) {
                $this->session->set_flashdata('error', 'error not found');
                redirect('clusters');
            }
            $data['sub_title'] = 'Cluster Form |' . $cluster->name;

            //set values to db values

            $data['id'] = $cluster->id;
            $data['code'] = $cluster->code;
            $data['name'] = $cluster->name;
            $data['sub_category_id'] = $cluster->sub_category_id;
            $data['active'] = $cluster->active;
        }

        $this->form_validation->set_rules('code', 'lang:firstname', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('name', 'lang:lastname', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('active', 'lang:active');




        if ($this->form_validation->run() == FALSE) {
            $this->load->view('cluster_form', $data);
        } else {
            $save['id'] = $id;
            $save['code'] = $this->input->post('code');
            $save['name'] = $this->input->post('name');
            $save['sub_category_id'] = $this->input->post('sub_category_id');
            $save['active'] = $this->input->post('active');



            $this->Cluster_model->save($save);

            $this->session->set_flashdata('message', 'Cluster has been saved');

            //go back to the cluster list
            redirect('clusters');
        }
    }

    function delete($id = false) {
        if ($id) {
            $cluster = $this->Cluster_model->get_cluster($id);
            //if the cluster does not exist, redirect them to the cluster list with an error
            if (!$cluster) {
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect('clusters');
            } else {
                //if the cluster is legit, delete them
                $delete = $this->Cluster_model->delete($id);

                $this->session->set_flashdata('error', 'cluster has been deleted');
                redirect('clusters');
            }
        } else {
            //if they do not provide an id send them to the cluster list page with an error
            $this->session->set_flashdata('error', 'error not found');
            redirect('clusters');
        }
    }

}
