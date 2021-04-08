<?php

class Fuels extends CI_Controller {

	//this is used when editing or adding a customer
	var $device_id	= false;	

	function __construct()
	{		
		parent::__construct();

		$this->load->model(array('Fuel_model','Admin_model','New_models_model','Competitor_ads_model'));
		$this->load->helper('formatting_helper');
		$this -> load -> helper(array('form', 'date'));
		//$this->lang->load('fuel');
	}
	
	function index($field='id', $by='DESC', $page=0)
	{
		//we're going to use flash data and redirect() after form submissions to stop people from refreshing and duplicating submissions
		//$this->session->set_flashdata('message', 'this is our message');
		$admin = $this -> admin_session -> userdata('admin');
        $admin_id = $admin['id'];
		
		$data['page_title']	= 'Fuel Monitoring';
		$data['fuels']	= $this->Fuel_model->get_fuels_by_admin_id($admin_id,'IM');
		
		$this->load->library('pagination');

		$config['base_url']		= base_url().'/'.$this->config->item('admin_folder').'/fuels/index/'.$field.'/'.$by.'/';
		$config['total_rows']	= $this->Fuel_model->count_fuels();
		$config['per_page']		= 50;
		$config['uri_segment']	= 6;
		$config['first_link']		= 'First';
		$config['first_tag_open']	= '<li>';
		$config['first_tag_close']	= '</li>';
		$config['last_link']		= 'Last';
		$config['last_tag_open']	= '<li>';
		$config['last_tag_close']	= '</li>';

		$config['full_tag_open']	= '<div class="pagination"><ul>';
		$config['full_tag_close']	= '</ul></div>';
		$config['cur_tag_open']		= '<li class="active"><a href="#">';
		$config['cur_tag_close']	= '</a></li>';
		
		$config['num_tag_open']		= '<li>';
		$config['num_tag_close']	= '</li>';
		
		$config['prev_link']		= '&laquo;';
		$config['prev_tag_open']	= '<li>';
		$config['prev_tag_close']	= '</li>';

		$config['next_link']		= '&raquo;';
		$config['next_tag_open']	= '<li>';
		$config['next_tag_close']	= '</li>';
		
		$this->pagination->initialize($config);
		
		
		$data['page']	= $page;
		$data['field']	= $field;
		$data['by']		= $by;
		
		$this->load->view($this->config->item('admin_folder').'/fuels', $data);
	}

	function form($id = false)
	{
		force_ssl();
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$data['page_title']		= 'Fuel Tracking Form';
		
		
		//$data['admins']		= $this->Admin_model->get_admins();
		//default values are empty if the device is new
		$admin = $this -> admin_session -> userdata('admin');
        $admin_id = $admin['id'];
		
		
		$data['id']				= '';
        $data['type']		= '';
		$data['date']		= '';
		$data['admin_id']		= '';
		$data['project_id']		= '';
		$data['counter_photos']		= array();
		$data['accident_photos']		= array();
		$data['other_photos']		= array();	
		$data['counter']		= '';	
		$data['fuel_card']		= '';	
		$data['fuel_cash']		= '';	
		$data['remaining_card']		= '';	
		$data['remark']		= '';	
		$data['active']		= '';		
		
		
		if ($id)
		{	
			$this->fuel_id	= $id;
			$fuel		= $this->Fuel_model->get_fuel($id);
			//print_r($fuel);
			//if the device does not exist, redirect them to the customer list with an error
			if (!$fuel)
			{
				$this->session->set_flashdata('error', lang('error_not_found'));
				redirect($this->config->item('admin_folder').'/fuels');
			}
			
			//set values to db values
			$data['id']				= $fuel->id;
			$data['admin_id']		= $admin_id;
			$data['project_id']			= 'IM';
			$data['type']			= $fuel->type;
			
			$data['counter']	= $fuel->counter;
			$data['fuel_card']			= $fuel->fuel_card;
			$data['fuel_cash']		= $fuel->fuel_cash;
			$data['remaining_card']	= $fuel->remaining_card;	
			$data['remark']	= $fuel->remark;
			$data['active']	= $fuel->active;	
			
			if (!$this -> input -> post('submit')) {
				$data['counter_photos'] = (array)json_decode($fuel -> counter_photos);
				$data['accident_photos']		= (array)json_decode($fuel -> accident_photos);
		        $data['other_photos']		= (array)json_decode($fuel -> other_photos);	
				
				
						
				
				
			}
						
			
		}
		
		//$this->form_validation->set_rules('code', 'lang:code', 'trim|required|max_length[20]');
		$this->form_validation->set_rules('name', 'type', 'trim');
		
		
		
				
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view($this->config->item('admin_folder').'/fuel_form', $data);
		}
		else
		{
			$save['id']		= $id;

			$save['admin_id']	= $admin_id;
			$save['project_id']		= 'IM';
			$save['type']		= $this->input->post('type');
			$date = new DateTime('now', new DateTimeZone('Africa/Tunis'));
			$date = $date -> format('Y-m-d');	
			$save['date']		= $date;		
			$save['counter']	= $this->input->post('counter');
			$save['fuel_card']	= $this->input->post('fuel_card');
			$save['fuel_cash']		= $this->input->post('fuel_cash');
			$save['remaining_card']	= $this->input->post('remaining_card');	
			$save['remark']	= $this->input->post('remark');	
			$save['active']	= $this->input->post('active');	
			
			
			
			$post_counter_photos = $this -> input -> post('counter_photos');
			$post_accident_photos = $this -> input -> post('accident_photos');
			$post_other_photos = $this -> input -> post('other_photos');

			if ($primary_counter = $this -> input -> post('primary_counter')) {
				if ($post_counter_photos) {
					foreach ($post_counter_photos as $key => &$pi) {
						if ($primary_counter == $key) {
							$pi['primary_counter'] = true;
							continue;
						}
					}
				}

			}

			if ($primary_accident = $this -> input -> post('primary_accident')) {
				if ($post_accident_photos) {
					foreach ($post_accident_photos as $key => &$pi) {
						if ($primary_accident == $key) {
							$pi['primary_accident'] = true;
							continue;
						}
					}
				}

			}

			if ($primary_other = $this -> input -> post('primary_other')) {
				if ($post_other_photos) {
					foreach ($post_other_photos as $key => &$pi) {
						if ($primary_other == $key) {
							$pi['primary_other'] = true;
							continue;
						}
					}
				}

			}


			$save['counter_photos'] = json_encode($post_counter_photos);
			$save['accident_photos'] = json_encode($post_accident_photos);
			$save['other_photos'] = json_encode($post_other_photos);
			

			
			
			$this->Fuel_model->save($save);
			//print_r($save);
			
			$this->session->set_flashdata('message', lang('message_saved_fuel'));
			
			//go back to the fuel list
			redirect($this->config->item('admin_folder').'/fuels');
		}
	}
	
	
	function delete($id = false)
	{
		if ($id)
		{	
			$fuel	= $this->Fuel_model->get_fuel($id);
			//if the device does not exist, redirect them to the device list with an error
			if (!$fuel)
			{
				$this->session->set_flashdata('error', lang('error_not_found'));
				redirect($this->config->item('admin_folder').'/devices');
			}
			else
			{
				//if the device is legit, delete them
				$delete	= $this->Fuel_model->delete($id);
				
				$this->session->set_flashdata('message', lang('message_fuel_deleted'));
				redirect($this->config->item('admin_folder').'/fuels');
			}
		}
		else
		{
			//if they do not provide an id send them to the device list page with an error
			$this->session->set_flashdata('error', lang('error_not_found'));
			redirect($this->config->item('admin_folder').'/fuels');
		}
	}
	

	function desactivate($id) {
		$fuel = array('id' => $id, 'active' => 0);
		$this -> Fuel_model -> save($fuel);
		$this -> session -> set_flashdata('message', lang('message_saved_fuel'));
		redirect($this -> config -> item('admin_folder') . '/fuels');
	}

	function activate($id) {
		$fuel = array('id' => $id, 'active' => 1);
		$this -> Fuel_model -> save($fuel);
		$this -> session -> set_flashdata('message', lang('message_saved_fuel'));
		redirect($this -> config -> item('admin_folder') . '/fuels');
	}
	
	
	
	function visit_image_form1() {
		$data['file_name1'] = false;
		$data['error1'] = false;
		$this -> load -> view($this -> config -> item('admin_folder') . '/iframe/fuel/visit_image_uploader1', $data);
	}

	function visit_image_upload1() {
		$data['file_name1'] = false;
		$data['error1'] = false;
		$p=dirname($_SERVER['DOCUMENT_ROOT']);

		$config['allowed_types'] = 'gif|jpg|png';
		//$config['max_size']	= $this->config->item('size_limit');
		//$config['upload_path'] = 'uploads/images/fuel';
		$config['upload_path'] = $p.'/fuel_img';

		$config['encrypt_name'] = true;
		$config['remove_spaces'] = true;

		$this -> load -> library('upload', $config);

		if ($this -> upload -> do_upload()) {
			$upload_data = $this -> upload -> data();

			$this -> load -> library('image_lib');
			/*

			 I find that ImageMagick is more efficient that GD2 but not everyone has it
			 if your server has ImageMagick then you can change out the line

			 $config['image_library'] = 'gd2';

			 with

			 $config['library_path']		= '/usr/bin/convert'; //make sure you use the correct path to ImageMagic
			 $config['image_library']	= 'ImageMagick';
			 */

			$config['image_library'] = 'gd2';
			$config['source_image'] = $p.'/fuel_img' . $upload_data['file_name'];
			$config['new_image'] = $p.'/fuel_img' . $upload_data['file_name'];
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 640;
			$config['height'] = 480;
			
			if($this->input->post("mode")=='270'){   	
             $config['rotation_angle'] = '270';
             }else if ($this->input->post("mode")=='90'){
				 $config['rotation_angle'] = '90';
			}
			
			$this -> image_lib -> initialize($config);
			$this -> image_lib -> resize();
			$this->image_lib->rotate();
			$this -> image_lib -> clear();

			$data['file_name1'] = $upload_data['file_name'];
		}

		if ($this -> upload -> display_errors() != '') {
			$data['error1'] = $this -> upload -> display_errors();
		}
		$this -> load -> view($this -> config -> item('admin_folder') . '/iframe/fuel/visit_image_uploader1', $data);
	}

	function visit_image_form2() {
		$data['file_name2'] = false;
		$data['error2'] = false;
		$this -> load -> view($this -> config -> item('admin_folder') . '/iframe/fuel/visit_image_uploader2', $data);
	}

	function visit_image_upload2() {
		$data['file_name2'] = false;
		$data['error2'] = false;
		$p=dirname($_SERVER['DOCUMENT_ROOT']);

		$config['allowed_types'] = 'gif|jpg|png';
		//$config['max_size']	= $this->config->item('size_limit');
		$config['upload_path'] = $p.'/fuel_img';

		$config['encrypt_name'] = true;
		$config['remove_spaces'] = true;

		$this -> load -> library('upload', $config);

		if ($this -> upload -> do_upload()) {
			$upload_data = $this -> upload -> data();

			$this -> load -> library('image_lib');
			/*

			 I find that ImageMagick is more efficient that GD2 but not everyone has it
			 if your server has ImageMagick then you can change out the line

			 $config['image_library'] = 'gd2';

			 with

			 $config['library_path']		= '/usr/bin/convert'; //make sure you use the correct path to ImageMagic
			 $config['image_library']	= 'ImageMagick';
			 */

			$config['image_library'] = 'gd2';
			$config['source_image'] = $p.'/fuel_img' . $upload_data['file_name'];
			$config['new_image'] = $p.'/fuel_img' . $upload_data['file_name'];
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 640;
			$config['height'] = 480;
			
			if($this->input->post("mode")=='270'){   	
             $config['rotation_angle'] = '270';
             }else if ($this->input->post("mode")=='90'){
				 $config['rotation_angle'] = '90';
			}
			
			$this -> image_lib -> initialize($config);
			$this -> image_lib -> resize();
			$this->image_lib->rotate();
			$this -> image_lib -> clear();

			$data['file_name2'] = $upload_data['file_name'];
		}

		if ($this -> upload -> display_errors() != '') {
			$data['error2'] = $this -> upload -> display_errors();
		}
		$this -> load -> view($this -> config -> item('admin_folder') . '/iframe/fuel/visit_image_uploader2', $data);
	}

	function visit_image_form3() {
		$data['file_name3'] = false;
		$data['error3'] = false;
		$this -> load -> view($this -> config -> item('admin_folder') . '/iframe/fuel/visit_image_uploader3', $data);
	}

	function visit_image_upload3() {
		$data['file_name3'] = false;
		$data['error3'] = false;
		$p=dirname($_SERVER['DOCUMENT_ROOT']);

		$config['allowed_types'] = 'gif|jpg|png';
		//$config['max_size']	= $this->config->item('size_limit');
		$config['upload_path'] = $p.'/fuel_img';

		$config['encrypt_name'] = true;
		$config['remove_spaces'] = true;

		$this -> load -> library('upload', $config);

		if ($this -> upload -> do_upload()) {
			$upload_data = $this -> upload -> data();

			$this -> load -> library('image_lib');
			/*

			 I find that ImageMagick is more efficient that GD2 but not everyone has it
			 if your server has ImageMagick then you can change out the line

			 $config['image_library'] = 'gd2';

			 with

			 $config['library_path']		= '/usr/bin/convert'; //make sure you use the correct path to ImageMagic
			 $config['image_library']	= 'ImageMagick';
			 */

			$config['image_library'] = 'gd2';
			$config['source_image'] = $p.'/fuel_img' . $upload_data['file_name'];
			$config['new_image'] = $p.'/fuel_img' . $upload_data['file_name'];
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 640;
			$config['height'] = 480;
			
			if($this->input->post("mode")=='270'){   	
             $config['rotation_angle'] = '270';
             }else if ($this->input->post("mode")=='90'){
				 $config['rotation_angle'] = '90';
			}
			
			$this -> image_lib -> initialize($config);
			$this -> image_lib -> resize();
			$this->image_lib->rotate();
			$this -> image_lib -> clear();

			$data['file_name3'] = $upload_data['file_name'];
		}

		if ($this -> upload -> display_errors() != '') {
			$data['error3'] = $this -> upload -> display_errors();
		}
		$this -> load -> view($this -> config -> item('admin_folder') . '/iframe/fuel/visit_image_uploader3', $data);
	}


}