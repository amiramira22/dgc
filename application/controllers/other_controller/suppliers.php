<?php

class Suppliers extends CI_Controller {

	//this is used when editing or adding a outlet
	// var $outlet_id = false;

	function __construct() {
		parent::__construct();

		$this->auth->check_access('Admin', true);
		$this -> load -> model(array('Report_model','Brand_model','Admin_model'));
		$this -> load -> library('Auth');
		$admin = $this -> session -> userdata('admin');
		$admin_access = $admin['access'];
		if (!$admin ) {
		  redirect('/login');
		}    
	}
	
	function index(){
	$data['page_title'] = 'Suppliers';
	$data['sub_title'] = 'Manage Suppliers';
		// $this->load->library('rest', array('server' => 'http://localhost/stock',
								// 'api_key' => '123456',
								// 'api_name' => 'X-API-KEY',
								// ));
	$suppliers = $this->rest->get('http://admin.capesolution.tn/index.php/ApiSuppliers/suppliers?X-API-KEY=123456', null,'');	
	// $this->rest->debug();	
	// $var_dump($suppliers);       	// print_r($suppliers);			    // die;
	$data['suppliers'] = $suppliers;     

	$jsonNames = $this->rest->get('http://admin.capesolution.tn/index.php/ApiSuppliers/suppliersnames?X-API-KEY=123456', null,'');	
	// $this->rest->debug(); die();	
	$suppliers_names= array();
	
	foreach ($jsonNames as $supplier) {
		$suppliers_names[$supplier->id]=$supplier->name;
		}
		// var_dump($suppliers_names);
		// die();
	$data['suppliers_names'] = $suppliers_names;     

	$this -> load -> view($this -> config -> item('admin_folder') . '/suppliers', $data);	
	}
	
	
	
	
	function form($id = false){
	
	$this -> load -> helper('form');
	$this -> load -> library('form_validation');
	$data['page_title'] = 'Suppliers';
	$data['sub_title'] = 'Supplier Form';
	
	$data['name'] = $this -> input -> post('name');
	$data['description'] = $this -> input -> post('description');
	
	
	$this->load->library('rest');
	$config = array('server' => 'http://admin.capesolution.tn/index.php/',
						'api_key' => '123456',
						'api_name' => 'X-API-KEY'
						);	
	$this->rest->initialize($config);  
	
	
		$itm = array('name' => $data['name'],
					'description' => $data['description']);
		

	$this -> form_validation -> set_rules('name', 'lang:name', 'trim|required');
	if ($this -> form_validation -> run() == FALSE) {
			$this -> load -> view($this -> config -> item('admin_folder') . '/supplier_form', $data);
	} else {
			
	$result = $this->rest->post('ApiSuppliers/suppliers', $itm ,'');	
	// $this->rest->debug();	

	// $this -> load -> view($this -> config -> item('admin_folder') . '/supplier_form', $itm);	
			redirect($this -> config -> item('admin_folder') . '/suppliers');

	}
	
	}
	
	
	
	function form_edit($id = false){
	$this -> load -> helper('form');
	$this -> load -> library('form_validation');
	$data['page_title'] = 'Suppliers';
	$data['sub_title'] = 'Edit Supplier';
	
	$this->load->library('rest');
	$config = array('server' => 'http://admin.capesolution.tn/index.php/',
						'api_key' => '123456',
						'api_name' => 'X-API-KEY'
						);	
	$this->rest->initialize($config); 
	// $this->rest->format('application/json');	
	
	
	if ($id) {
		$data['id']= $id;
		print_r("Passed ID :".$id);
		//we get the supplier by ID
		
		$supplier = $this->rest->get('ApiSuppliers/suppliers?id='.$id,'');	
		// var_dump($supplier);	
		// $this->rest->debug();	

					
		//we fill the form			
		$data['itm']['name'] = $supplier[0]->name;
		$data['itm']['description'] = $supplier[0]->description;	
		
		
		//we prepare the supplier for the API
		$supplier = array('id' => $supplier[0]->id,
					'name' => $this -> input -> post('name'),
					'description' => $this -> input -> post('description'));
		
		
		// $result = $this->rest->put('ApiSuppliers/suppliers?id='.$id, $supplier ,'');	
		// $this->rest->debug();
		// redirect($this -> config -> item('admin_folder') . 'suppliers/edit_supplier/'.$id);
		$this -> form_validation -> set_rules('name', 'lang:name', 'trim|required');
				if ($this -> form_validation -> run() == FALSE) {
						$this -> load -> view($this -> config -> item('admin_folder') . '/supplier_form_edit', $data);
				} 
				else 
				{
				$result = $this->rest->put('ApiSuppliers/suppliers?id='.$id, $supplier ,'');	
				redirect($this -> config -> item('admin_folder') . '/suppliers');
				}		
	}// end if $id	
	}// end function
	
	function delete($id = false)
	{
	// $id=$this->uri->segment(3);   can help 
	$this->load->library('rest');
	$config = array('server' => 'http://admin.capesolution.tn/index.php/',
						'api_key' => '123456',
						'api_name' => 'X-API-KEY'
						);	
	$this->rest->initialize($config);  
	$result = $this->rest->delete('ApiSuppliers/suppliers/'.$id,'');	
	// $this->rest->debug();
	redirect($this -> config -> item('admin_folder') . '/suppliers');
	
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------
	
	
	
	
	
	
	
	
	
	
	
	
	

	// function index() {
		// //we're going to use flash data and redirect() after form submissions to stop people from refreshing and duplicating submissions
		// //$this->session->set_flashdata('message', 'this is our message');

		// $data['page_title'] = 'Suppliers';
		// $data['sub_title'] = 'List of Suppliers';
		

		// $this -> load -> library('pagination');

       // // //pagination settings
        // // $config['base_url'] = site_url('outlets/index');
        // // $config['total_rows'] = $this->Outlet_model->count_outlets();
        // // $config['per_page'] = "20";
        // // $config["uri_segment"] = 3;
        // // $choice = $config["total_rows"] / $config["per_page"];
        // // $config["num_links"] = 8;
		// // //floor($choice);

        // // //config for bootstrap pagination class integration
        // // $config['full_tag_open'] = '<ul class="pagination">';
        // // $config['full_tag_close'] = '</ul>';
        // // $config['first_link'] = false;
        // // $config['last_link'] = false;
        // // $config['first_tag_open'] = '<li>';
        // // $config['first_tag_close'] = '</li>';
        // // $config['prev_link'] = '&laquo';
        // // $config['prev_tag_open'] = '<li class="prev">';
        // // $config['prev_tag_close'] = '</li>';
        // // $config['next_link'] = '&raquo';
        // // $config['next_tag_open'] = '<li>';
        // // $config['next_tag_close'] = '</li>';
        // // $config['last_tag_open'] = '<li>';
        // // $config['last_tag_close'] = '</li>';
        // // $config['cur_tag_open'] = '<li class="active"><a href="#">';
        // // $config['cur_tag_close'] = '</a></li>';
        // // $config['num_tag_open'] = '<li>';
        // // $config['num_tag_close'] = '</li>';

        // // $this->pagination->initialize($config);
        // // $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // // //call the model function to get the department data
        // // $data['outlets'] = $this->Outlet_model->get_outlets($config["per_page"], $data['page'],'id','DESC');           

        // // $data['pagination'] = $this->pagination->create_links();

		// $this -> load -> view($this -> config -> item('admin_folder') . '/Suppliers	', $data);
	// }

	// function test(){
		// $data['page_title'] = 'test';
		// $data['sub_title'] = 'test of test';
		
		// $data['tests'] = $this->Outlet_model->get_tests();           
		// $data['outlets'] = $this->Outlet_model->get_outlets();    
		// $this -> load -> view($this -> config -> item('admin_folder') . '/tests_view', $data);
		
	// }
	// function form($id = false) {
		
		// $this -> load -> helper('form');
		// $this -> load -> library('form_validation');

		// $config['upload_path'] = 'uploads/logo';
		// $config['allowed_types'] = 'gif|jpg|png';
		// $config['max_size'] = $this -> config -> item('size_limit');
		// $config['encrypt_name'] = true;
		// $this -> load -> library('upload', $config);

		// $data['page_title'] = lang('outlet_form');
		// $data['zones'] = $this -> Zone_model -> get_zones();
		// $data['states'] = $this -> State_model -> get_states();
		// $data['cities'] = $this -> City_model -> get_cities();
		// $data['admins'] = $this -> Admin_model -> get_active_sfo();

		// //default values are empty if the outlet is new
		// $data['id'] = '';
		// $data['code'] = '';
		// $data['name'] = '';
		// $data['fax'] = '';
		// $data['adress'] = '';
		// $data['contact_name'] = '';
		// $data['contact_mobile'] = '';
		// $data['phone'] = '';
		// $data['state_id'] = '';
		// $data['city_id'] = '';
		// $data['zone_id'] = '';
		// $data['activity'] = '';
		// $data['class'] = '';
		// $data['type'] = '';
		// $data['active'] = false;
		// $data['promoter'] = false;
		// $data['antenna'] = false;
		// $data['m_project'] = false;
		// $data['long'] = '';
		// $data['lat'] = '';
		// $data['image'] = '';

		// if ($id) {
			// $this -> outlet_id = $id;
			// $outlet = $this -> Outlet_model -> get_outlet($id);
			// //if the outlet does not exist, redirect them to the outlet list with an error
			// if (!$outlet) {
				// $this -> session -> set_flashdata('error', lang('error_not_found'));
				// redirect($this -> config -> item('admin_folder') . '/outlets');
			// }

			// //set values to db values
			// $data['id'] = $outlet -> id;
			// $data['code'] = $outlet -> code;
			// $data['name'] = $outlet -> name;

			// $data['zone_id'] = $outlet -> zone_id;
			// $data['type'] = $outlet -> type;
			// $data['state_id'] = $outlet -> state_id;
			// $data['city_id'] = $outlet -> city_id;
			// $data['class'] = $outlet -> class;
			// $data['adress'] = $outlet -> adress;
			// $data['phone'] = $outlet -> phone;
			// $data['fax'] = $outlet -> fax;
			// $data['contact_name'] = $outlet -> contact_name;
			// $data['contact_mobile'] = $outlet -> contact_mobile;
			// $data['activity'] = $outlet -> activity;
			// $data['antenna'] = $outlet -> antenna;
			// $data['m_project'] = $outlet -> m_project;
			// $data['active'] = $outlet -> active;
			// $data['promoter'] = $outlet -> promoter;
			// $data['long'] = $outlet -> long;
			// $data['lat'] = $outlet -> lat;
			// $data['image'] = $outlet -> image;

		// }

		// $this -> form_validation -> set_rules('code', 'lang:code', 'trim|required|max_length[32]');
		// $this -> form_validation -> set_rules('name', 'lang:name', 'trim|required|max_length[50]');
		// $this -> form_validation -> set_rules('type', 'lang:type', 'trim|required|max_length[32]');
		// $this -> form_validation -> set_rules('active', 'lang:active');

		// if ($this -> form_validation -> run() == FALSE) {
			// $this -> load -> view($this -> config -> item('admin_folder') . '/outlet_form', $data);
		// } else {

			// $uploaded = $this -> upload -> do_upload('image');

			// //	$save['id']		   = $id;
			// $save['code'] = $this -> input -> post('code');
			// $save['name'] = $this -> input -> post('name');
			
			// $save['zone_id'] = $this -> input -> post('zone_id');
			// $save['type'] = $this -> input -> post('type');
			// $save['state_id'] = $this -> input -> post('state_id');
			// $save['city_id'] = $this -> input -> post('city_id');
			// $save['class'] = $this -> input -> post('class');
			// $save['adress'] = $this -> input -> post('adress');
			// $save['phone'] = $this -> input -> post('phone');
			// $save['fax'] = $this -> input -> post('fax');
			// $save['contact_name'] = $this -> input -> post('contact_name');
			// $save['contact_mobile'] = $this -> input -> post('contact_mobile');
			// $save['activity'] = $this -> input -> post('activity');
			// $save['active'] = $this -> input -> post('active');
			// $save['promoter'] = $this -> input -> post('promoter');
			// $save['long'] = $this -> input -> post('long');
			// $save['lat'] = $this -> input -> post('lat');
			// $save['antenna'] = $this -> input -> post('antenna');
			// $save['m_project'] = $this -> input -> post('m_project');

			// if ($id) {
				// $save['id'] = $id;

				// //delete the original file if another is uploaded
				// if ($uploaded) {
					// if ($data['image'] != '') {
						// $file = 'uploads/logo/' . $data['image'];

						// //delete the existing file if needed
						// if (file_exists($file)) {
							// unlink($file);
						// }
					// }
				// }

			// } else {
				// if (!$uploaded) {
					// $data['error'] = $this -> upload -> display_errors();
					// $this -> load -> view($this -> config -> item('admin_folder') . '/outlet_form', $data);
					// return;
					// //end script here if there is an error
				// }
			// }

			// if ($uploaded) {
				// $image = $this -> upload -> data();
				// $save['image'] = $image['file_name'];
			// }

			// $this -> Outlet_model -> save($save);

			// $this -> session -> set_flashdata('message', lang('message_saved_outlet'));

			// //go back to the outlet list
			// redirect($this -> config -> item('admin_folder') . '/outlets');
		// }
	// }

	// function delete($id = false) {
		// if ($id) {
			// $outlet = $this -> Outlet_model -> get_outlet($id);
			// //if the outlet does not exist, redirect them to the outlet list with an error
			// if (!$outlet) {
				// $this -> session -> set_flashdata('error', lang('error_not_found'));
				// redirect($this -> config -> item('admin_folder') . '/outlets');
			// } else {
				// //if the outlet is legit, delete them
				// $delete = $this -> Outlet_model -> delete($id);

				// $this -> session -> set_flashdata('message', lang('message_outlet_deleted'));
				// redirect($this -> config -> item('admin_folder') . '/outlets');
			// }
		// } else {
			// //if they do not provide an id send them to the chef list page with an error
			// $this -> session -> set_flashdata('error', lang('error_not_found'));
			// redirect($this -> config -> item('admin_folder') . '/outlets');
		// }
	// }

	// function no_promoter($id) {
		// $outlet = array('id' => $id, 'promoter' => 0);
		// $this -> Outlet_model -> save($outlet);
		// $this -> session -> set_flashdata('message', lang('message_outlet_saved'));
		// redirect($this -> config -> item('admin_folder') . '/outlets');
	// }

	// function promoter($id) {
		// $outlet = array('id' => $id, 'promoter' => 1);
		// $this -> Outlet_model -> save($outlet);
		// $this -> session -> set_flashdata('message', lang('message_outlet_saved'));
		// redirect($this -> config -> item('admin_folder') . '/outlets');
	// }

}
