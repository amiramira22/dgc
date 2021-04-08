<?php

//bcm
class Products extends CI_Controller {

    //this is used when editing or adding a customer
    var $product_id = false;

    function __construct() {
        parent::__construct();

        $this->load->model(array('Product_model', 'Product_group_model', 'Brand_model', 'Category_model', 
            'Sub_category_model', 'Cluster_model', 'Outlet_model'));
        $this->load->helper('formatting_helper');
        $this->load->library('pagination');
        $this->load->helper(array('form', 'date'));
    }

    function index() {
        $data['page_title'] = 'Products';
        $data['sub_title'] = 'List of products';


        //call the model function to get the department data
        $data['products'] = $this->Product_model->get_products();
        $this->load->view('products', $data);
    }

    function form($id = false) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['page_title'] = 'Product';
        $data['sub_title'] = 'Product Form';

        $config['upload_path'] = 'uploads/product';
        $config['allowed_types'] = '*';
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);

        $data['product_groups'] = $this->Product_group_model->get_product_groups();
        $data['clusters'] = $this->Cluster_model->get_clusters();
        //default values are empty if the product is new
        $data['id'] = '';
        $data['code'] = '';
        $data['code_gemo'] = '';
        $data['code_mg'] = '';
        $data['code_uhd'] = '';
        $data['name'] = '';
        $data['product_group_id'] = '';
        $data['cluster_id'] = '';
        $data['nb_sku'] = 0;
        $data['image'] = '';




        if ($id) {
            $this->product_id = $id;
            $product = $this->Product_model->get_product($id);
            //if the product does not exist, redirect them to the product list with an error
            if (!$product) {
                $this->session->set_flashdata('error', 'error not found');
                redirect('products');
            }
            $data['sub_title'] = 'Product Form |' . $product->name;

            //set values to db values

            $data['id'] = $product->id;
            $data['code'] = $product->code;
            $data['code_gemo'] = $product->code_gemo;
            $data['code_uhd'] = $product->code_uhd;
            $data['code_mg'] = $product->code_mg;
            $data['name'] = $product->name;
            $data['active'] = $product->active;
            $data['product_group_id'] = $product->product_group_id;
            $data['cluster_id'] = $product->cluster_id;
            $data['nb_sku'] = $product->nb_sku;
            $data['image'] = $product->image;
        }

        $this->form_validation->set_rules('code', 'Code', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[50]');




        if ($this->form_validation->run() == FALSE) {
            $this->load->view('product_form', $data);
        } else {
            //$save['id']		= $id;
            $save['code'] = $this->input->post('code');
            $save['code_gemo'] = $this->input->post('code_gemo');
            $save['code_uhd'] = $this->input->post('code_uhd');
            $save['code_mg'] = $this->input->post('code_mg');
            $save['name'] = $this->input->post('name');

            $save['product_group_id'] = $this->input->post('product_group_id');
            $save['cluster_id'] = $this->input->post('cluster_id');

            $product_group = $this->Product_group_model->get_product_group($save['product_group_id']);
            $save['brand_id'] = $product_group->brand_id;
            $save['category_id'] = $product_group->category_id;
            $save['sub_category_id'] = $product_group->sub_category_id;

            $save['nb_sku'] = $this->input->post('nb_sku');

            $uploaded = $this->upload->do_upload('image');
            
             if (!$uploaded) {
            
                $save['image'] ='';
                
            $this->Product_model->save($save);

            $this->session->set_flashdata('message', 'Product has been saved');

            //go back to the product list
            redirect('products');

            }

            //print_r($save);

            if ($id) {
                $save['id'] = $id;

                //delete the original file if another is uploaded
                if ($uploaded) {
                    if ($data['image'] != '') {
                        $file = 'uploads/product/' . $data['image'];

                        //delete the existing file if needed
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                }
            } else {
                if (!$uploaded) {
                    $data['error'] = $this->upload->display_errors();
                    $this->load->view('/product_form', $data);
                    return;
                    //end script here if there is an error
                }
            }

            if ($uploaded) {
                $image = $this->upload->data();
                $save['image'] = $image['file_name'];
            }

            //print_r($save);

            $this->Product_model->save($save);

            $this->session->set_flashdata('message', 'Product has been saved');

            //go back to the product list
            redirect('products');
        }
    }

    public function ha_outlets() {
        $data['page_title'] = 'Products';
        $data['sub_title'] = 'HA Products';
        //call the model function to get the department data
        $data['outlets'] = $this->Outlet_model->get_active_outlets();
        $this->load->view('product_ha_outlets', $data);
    }
    
    public function ha_products($outlet_id=false) {
        $data['page_title'] = 'Products';
        $data['sub_title'] = 'HA Products | '. $this->Outlet_model->get_outlet_name($outlet_id);
        $data['products'] = $this->Product_model->get_active_products();
        $data['ha_product_ids'] = $this->Product_model->get_ha_products($outlet_id);
        $data['outlet_id'] =$outlet_id;
        $this->load->view('product_ha_models', $data); 
    }
    
     // Action ha product
    function disable() {
        $product_id = $this->input->post("product_id");
        $outlet_id = $this->input->post("outlet_id");
       
        $this->Product_model->delete_ha_product($product_id,$outlet_id);
        echo '<a onclick=" enable(' . $product_id .','.$outlet_id. ')" class="btn btn-circle red btn-outline"  data-toggle="tooltip" data-placement="top" title="Disable"><i class="fa fa-thumbs-down"></i></a>';
    }
   
    function enable() {
        
        $product_id = $this->input->post("product_id");
        $outlet_id = $this->input->post("outlet_id");
        //$ha['id'] = false;
        $ha['product_id'] = $product_id;
        $ha['outlet_id'] = $outlet_id;
         $this->Product_model->add_ha_product($ha);
        echo '<a  onclick="disable(' . $product_id .','.$outlet_id. ')" class="btn btn-circle green btn-outline" data-toggle="tooltip" data-placement="top" title="Enable"><i class="fa fa-thumbs-up"></i> </a>';
    }

    public function search() {
        $data['page_title'] = 'Products';
        $data['sub_title'] = 'Manage products';



        $search = ($this->input->post("search")) ? $this->input->post("search") : "-1";
        $search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;




        $config['base_url'] = site_url('outlets/search/' . $search . '/' . $search_user_id);

        //Total row
        $config['total_rows'] = $this->Product_model->count_products_search($search);
        //	echo $config['total_rows'];
        //die();
        $config['per_page'] = "10";
        $config["uri_segment"] = 4;
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
        $data['page'] = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;


        //call the model function to get the department data
        $data['products'] = $this->Product_model->get_products_search($config["per_page"], $data['page'], 'product.name', 'DESC', $search);

        $data['pagination'] = $this->pagination->create_links();

        //load the department_view
        $this->load->view('products', $data);
    }

    function delete($id = false) {
        if ($id) {
            $product = $this->Product_model->get_product($id);
            //if the product does not exist, redirect them to the product list with an error
            if (!$product) {
                $this->session->set_flashdata('error', 'Error not found');
                redirect('products');
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

                $data = json_encode($product);
                $save['data'] = $data;

                $this->Log_model->save_log($save);

                //if the product is legit, delete them
                $delete = $this->Product_model->delete($id);

                $this->session->set_flashdata('error', 'product has been deleted');
                redirect('products');
            }
        } else {
            //if they do not provide an id send them to the product list page with an error
            $this->session->set_flashdata('error', 'error not found');
            redirect('products');
        }
    }

    function desactivate($id) {
        $product = array('id' => $id, 'active' => 0);
        $this->Product_model->save($product);
        $this->session->set_flashdata('message', lang('message_product_saved'));
        redirect('products');
    }

    function activate($id) {
        $product = array('id' => $id, 'active' => 1);
        $this->Product_model->save($product);
        $this->session->set_flashdata('message', lang('message_product_saved'));
        redirect('products');
    }

}
