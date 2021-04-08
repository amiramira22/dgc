<?php
class Competitors_activities extends CI_Controller
{
	//these are used when editing, adding or deleting an admin
	var $admin_id		= false;
	var $current_admin	= false;
	function __construct()
	{
		parent::__construct();
		//$this->auth->check_access('Admin', true);
		 if(!$this->auth->is_logged_in(false, false)){
			
			redirect($this -> config -> item('admin_folder') . '/login');
		}
		
		//load the admin language file in
		
		$this->current_admin	= $this->session->userdata('admin');
		$this->load->helper('formatting_helper');
		$this -> load -> model(array('Report_model','Competitors_activities_model','Outlet_model','New_model_model','Competitor_ads_model'));
	}

	function index()
	{
		$data['page_title']	= 'List of competitors_activities';
		$data['title1']	= 'competitors_activities';
		$data['title2']	= 'List of competitors_activities';

		$admin = $this -> admin_session -> userdata('admin');

		$admin_id = $admin['id'];
 if ($this -> auth -> check_access('Field Officer')) {
 	$data['competitors_activities']		= $this->Competitors_activities_model->get_competitors_activities_by_admin($admin_id);
 }
 else{

	$data['competitors_activities']		= $this->Competitors_activities_model->get_competitors_activities();
}




	$this->load->view($this -> config -> item('admin_folder') . '/competitors_activities', $data);
	}



	
	function delete($id)
	{
		//even though the link isn't displayed for an admin to delete themselves, if they try, this should stop them.
		
		print_r($id);
		$this->Competitors_activities_model->delete($id);
		$this->session->set_flashdata('message', lang('message_user_deleted'));
		redirect('admin/competitors_activities');
	}

function delete2($id)
	{
		//even though the link isn't displayed for an admin to delete themselves, if they try, this should stop them.
		
		print_r($id);
		//delete the user
		$this->Dashboard_model->deletelistbyid($id);
		$this->session->set_flashdata('message', lang('message_user_deleted'));
		redirect('dashboard');
	}




	function form($id = false)
	{
		//force_ssl();
		
	
$this -> load -> helper(array('form', 'date'));
		$this -> load -> library('form_validation');



		
		


		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$data['page_title']		= 'Competitor Activities';
		$data['title1']	= 'competitors_activities';
		$data['title2']	= 'Competitor Activities';
		$config['upload_path'] = 'uploads/competitor';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = '10000';
		$config['encrypt_name'] = true;
		$this -> load -> library('upload', $config);
		
		//default values are empty if the customer is new
		$data['id']		= '';
		$data['outlets']		= $this-> Outlet_model -> get_outlets();
		$data['outlet_id'] = '';
		$data['image'] = '';
		$data['title']	= '';
		$data['remark']	= '';
		$data['date'] = date("Y-m-d");
		$data['before_images'] = array();
		$data['after_images'] = array();
		$data['one_images'] = array();

		
		
		
		if ($id)
		{	
			$this->competitors_activities_id		= $id;

                
				


			$competitors_activitie			= $this->Competitors_activities_model->get_competitors_activities_by_id($id);
			//if the administrator does not exist, redirect them to the admin list with an error
			if (!$competitors_activitie)
			{
				$this->session->set_flashdata('message','competitor not found');
				redirect('competitors_activities');
			}
			//set values to db values
			$data['id']			= $competitors_activitie->id;
			
	     	$data['outlet_id']	= $competitors_activitie->outlet_id;
			$data['title']	= $competitors_activitie->title;
			$data['date']		= $competitors_activitie->date;
			$data['remark']		= $competitors_activitie->remark;
			$data['image']		= $competitors_activitie->image;
			
		
	if (!$this -> input -> post('submit')) {
				$data['before_images'] = (array)json_decode($competitors_activitie -> before_images);
				$data['after_images'] = (array)json_decode($competitors_activitie -> after_images);
				$data['one_images'] = (array)json_decode($competitors_activitie -> one_images);
			}

			
		}
		
	
		$this->form_validation->set_rules('outlet_id', 'lang:access', 'trim|required');
		
		//if this is a new account require a password, or if they have entered either a password or a password confirmation
	
			if ($this -> input -> post('submit')) {
			//reset the visit options that were submitted in the post
			$data['before_images'] = $this -> input -> post('before_images');
			$data['after_images'] = $this -> input -> post('after_images');
			$data['one_images'] = $this -> input -> post('one_images');
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view($this->config->item('admin_folder').'/picture_form3', $data);
		}
		else
		{
			$uploaded = $this -> upload -> do_upload('image');

			$save['id']		= $id;
			$save['title']	= $this->input->post('title');
			
			//print_r($current_admin['id']);
			$save['outlet_id']		= $this->input->post('outlet_id');
			if(!$id)
			{
			$save['date']		= $this->input->post('date');
			}
			$save['remark']		= $this->input->post('remark');
			$admin = $this -> admin_session -> userdata('admin');

		$admin_id = $admin['id'];
			$save['admin_id']=$admin_id;
			$save['active']=0;





              print_r('test 1');
			  print_r($this->input->post('date'));
				
				

$post_before_images = $this -> input -> post('before_images');
			$post_after_images = $this -> input -> post('after_images');
			$post_one_images = $this -> input -> post('one_images');

			if ($primary_before = $this -> input -> post('primary_before_image')) {
				if ($post_before_images) {
					foreach ($post_before_images as $key => &$pi) {
						if ($primary_before == $key) {
							$pi['primary_before'] = true;
							continue;
						}
					}
				}

			}
			
			if ($primary_after = $this -> input -> post('primary_after_image')) {
				if ($post_after_images) {
					foreach ($post_after_images as $key => &$pi) {
						if ($primary_after == $key) {
							$pi['primary_before'] = true;
							continue;
						}
					}
				}

			}
			
			if ($primary_one = $this -> input -> post('primary_one_image')) {
				if ($post_one_images) {
					foreach ($post_one_images as $key => &$pi) {
						if ($primary_one == $key) {
							$pi['primary_one'] = true;
							continue;
						}
					}
				}

			}
			
			$save['before_images'] = json_encode($post_before_images);
			$save['after_images'] = json_encode($post_after_images);
			$save['one_images'] = json_encode($post_one_images);


			
			$this->Competitors_activities_model->save($save);
			
			$this->session->set_flashdata('competitors_activities ajouté', 'competitors_activities ajouté');
			
			//go back to the customer list
		redirect($this -> config -> item('admin_folder') .'/competitors_activities');
		}
	}
	
	function check_email($str)
	{
		$email = $this->auth->check_email($str, $this->admin_id);
		if ($email)
		{
			$this->form_validation->set_message('check_email', 'The requested email is already in use.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function desactivate($id) {
		$admin = array('id' => $id, 'active' => 0);
		$this ->Competitors_activities_model-> save($admin);
		$this->session->set_flashdata('message', lang('message_user_saved'));
		redirect($this -> config -> item('admin_folder') .'/competitors_activities');
	}
	function activate($id) {
		$admin = array('id' => $id, 'active' => 1);
		$this ->Competitors_activities_model-> save($admin);
		$this->session->set_flashdata('message', lang('message_user_saved'));
		redirect($this -> config -> item('admin_folder') .'/competitors_activities');
	}
	
	




function visit_image_form() {
		$data['file_name'] = false;
		$data['error'] = false;
		$this -> load -> view($this -> config -> item('admin_folder') . '/iframe3/visit_image_uploader', $data);
	}
	
	function visit_image_form1() {
		$data['file_name'] = false;
		$data['error'] = false;
		$this -> load -> view($this -> config -> item('admin_folder') . '/iframe3/visit_image_uploader1', $data);
	}

    function visit_image_form2() {
		$data['file_name'] = false;
		$data['error'] = false;
		$this -> load -> view($this -> config -> item('admin_folder') . '/iframe3/visit_image_uploader2', $data);
	}
	

	function visit_image_upload() {
		$data['file_name'] = false;
		$data['error'] = false;

		$config['allowed_types'] = 'gif|jpg|png';
		//$config['max_size']	= $this->config->item('size_limit');
		$config['upload_path'] = 'uploads/images/full';

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
			$config['source_image'] = 'uploads/images/full/'.$upload_data['file_name'];
			$config['new_image']	= 'uploads/images/full/'.$upload_data['file_name'];
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 640;
			$config['height'] = 480;
			$this->image_lib->initialize($config);
			$this->image_lib->resize();
			$this->image_lib->clear();

			$data['file_name'] = $upload_data['file_name'];
		}

		if ($this -> upload -> display_errors() != '') {
			$data['error'] = $this -> upload -> display_errors();
		}
		$this -> load -> view($this -> config -> item('admin_folder') . '/iframe3/visit_image_uploader', $data);
	}


	function visit_image_upload1() {
		$data['file_name1'] = false;
		$data['error1'] = false;

		$config['allowed_types'] = 'gif|jpg|png';
		//$config['max_size']	= $this->config->item('size_limit');
		$config['upload_path'] = 'uploads/images/full';

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
			$config['source_image'] = 'uploads/images/full/'.$upload_data['file_name'];
			$config['new_image']	= 'uploads/images/full/'.$upload_data['file_name'];
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 640;
			$config['height'] = 480;
			$this->image_lib->initialize($config);
			$this->image_lib->resize();
			$this->image_lib->clear();

			$data['file_name1'] = $upload_data['file_name'];
		}

		if ($this -> upload -> display_errors() != '') {
			$data['error1'] = $this -> upload -> display_errors();
		}
		$this -> load -> view($this -> config -> item('admin_folder') . '/iframe3/visit_image_uploader1', $data);
	}
	

	function visit_image_upload2() {
		$data['file_name2'] = false;
		$data['error2'] = false;

		$config['allowed_types'] = 'gif|jpg|png';
		//$config['max_size']	= $this->config->item('size_limit');
		$config['upload_path'] = 'uploads/images/full';

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

			$data['file_name2'] = $upload_data['file_name'];
		}

		if ($this -> upload -> display_errors() != '') {
			$data['error2'] = $this -> upload -> display_errors();
		}
		$this -> load -> view($this -> config -> item('admin_folder') . '/iframe3/visit_image_uploader2', $data);
	}



}