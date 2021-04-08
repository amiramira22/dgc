<?php
class Admin extends CI_Controller
{
	//these are used when editing, adding or deleting an admin
	var $admin_id		= false;
	var $current_admin	= false;
	function __construct()
	{
		parent::__construct();
		//$this->auth->check_access('Admin', true);
		
		//load the admin language file in
		$this->lang->load('admin');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('form_validation');
		$this->load->library('pagination');

		
		
		 if(!$this->auth->is_logged_in(false, false)){
			
			redirect('login');
		}
		
		$this->current_admin	= $this->session->userdata('admin');

	}

	function index()
	{
		$data['page_title']	= 'Users';
		$data['sub_title']	='List of users';
		$data['admins']		= $this->auth->get_admin_list();
		
      
      
       //$this->export->to_excel($admins, 'nameForFile'); 

		$this->load->view($this->config->item('admin_folder').'/admins', $data);
	}









	function field_officers(){
		
	$data['page_title']	= 'Routing';
	$data['sub_title']	= 'Manage Routing';
		
		//pagination settings
        $config['base_url'] = site_url('field_officers/index');
        $config['total_rows'] = $this->auth->count_field_officers();
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
        $data['admins'] = $this->auth->get_field_officers($config["per_page"], $data['page'],'id','DESC');           

        $data['pagination'] = $this->pagination->create_links();
		
		$this->load->view('field_officers', $data);
	}

	function routing($admin_id){
		$admin_name=$this->auth->get_admin_name($admin_id);
		$data['id']	=$admin_id;
		$data['page_title']	= $admin_name.' - Daily Routing';
		$data['sub_title']	= 'Manage Daily Routing';

		$data['outlets']	= $this->Outlet_model->get_outlets_by_id($admin_id);
		
		$config['base_url'] = site_url('admin/routing/'.$admin_id.'/index');
        $config['total_rows'] = $this->Daily_route_model->count_routes_by_id($admin_id);
        $config['per_page'] = "20";
        $config["uri_segment"] = 5;
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
		$data['routes']	= $this->Daily_route_model->get_routes_by_admin_id($config["per_page"], $data['page'],'id','DESC',$admin_id);

        $data['pagination'] = $this->pagination->create_links();
		$this->load->view('admin_routing', $data);
	}

	function add_routing($id){
	$save['id']=false;
	$save['outlet_id']	= $this->input->post('outlet_id');
	$save['admin_id']	= $id;
	$save['date']	= $this->input->post('date');
	$save['start_time']	= $this->input->post('start_time');
	$save['end_time']	= $this->input->post('end_time');
	$this->Daily_route_model->save($save);
	redirect($this->config->item('admin_folder').'/admin/routing/'.$id);
		
	}

	function delete_route($id)
	{
		//delete the user
		$this->Daily_route_model->delete($id);
		$this->session->set_flashdata('error', 'Routing has been deleted');
		redirect($this->config->item('admin_folder').'/admin/field_officers');
	}


	function position(){
		
		$data['page_title'] = 'Geolocalisation';
	    $data['sub_title'] = 'Field Officers Real Time Position';

		$admins= $this -> auth -> get_fo_list();
		$long='10.0661367';
		$lat='35.6742641';
		
		// Load the library
		$this -> load -> library('googlemaps');
		
		$config['center'] = $lat . ',' . $long;
		$config['zoom'] = '7';
		
		$config['cluster'] = TRUE;
		
		$this -> googlemaps -> initialize($config);
		
        foreach ($admins as $admin) {
		$marker = array();
	    $marker['infowindow_content'] = $admin->firstname.' '.$admin->lastname;
		$marker['position'] = $admin->latitude . ',' . $admin->longitude;
		
		$this -> googlemaps -> add_marker($marker);
		}
		$data['map'] = $this -> googlemaps -> create_map();
		// Load our view, passing the map data that has just been created
		//$this -> load -> view('daily_map', $data);
		$this -> load -> view($this -> config -> item('admin_folder') . '/admin_map', $data);
	}
	
// import routing for field officers

public function import_routing() {
		$this -> load -> helper('form');
		$this -> load -> library('form_validation');

		$data['page_title'] = 'Upload Excel File to Add Routing';
		$data['sub_title'] = '';


		$admin = $this -> session -> userdata('admin');
		$admin_id = $admin['id'];

		

			$admins = $this -> auth -> get_fo_list();
			$data['admins'] = $admins;


		$this -> load -> view('import_routing', $data);

	}

	public function add_file_routing() {
		$file_data = array();
		$file = $this -> input -> post('file');
		$admin_id = $this -> input -> post('admin_id');

		$config['upload_path'] = 'uploads/routing/';
		$config['allowed_types'] = '*';
		$config['max_size'] = '10048';
		$this -> load -> library('upload', $config);

		$uploaded = $this -> upload -> do_upload('file');

		if ($uploaded) {
			$file_data = $this -> upload -> data();

		}

		$file = 'uploads/routing/' . $file_data['file_name'];
		print_r($file.' '.$admin_id);
		$this -> import_excel_routing($file, $admin_id);

	}

	public function import_excel_routing($file, $admin_id) {

		$data = array();
		//load the excel library
		$this -> load -> library('excel');

		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($file);

		//get only the Cell Collection
		$cell_collection = $objPHPExcel -> getActiveSheet() -> getCellCollection();

		//extract to a PHP readable array format
		foreach ($cell_collection as $cell) {
			$column = $objPHPExcel -> getActiveSheet() -> getCell($cell) -> getColumn();
			$row = $objPHPExcel -> getActiveSheet() -> getCell($cell) -> getRow();
			$data_value = $objPHPExcel -> getActiveSheet() -> getCell($cell) -> getValue();

			//header will/should be in row 1 only. of course this can be modified to suit your need.

			//echo gettype($column);
			$col = '';
			if ($column == 'A') {
				$col = 'outlet';
			} else if ($column == 'B') {
				$col = 'date';
			}

			if ($row == 1) {
				$header[$row][$col] = $data_value;
			} else {
				if (trim($data_value) != '') {
					$arr_data[$row][$col] = $data_value;
				}

			}
		}

	
		$admin_name = $this -> auth -> get_admin_name($admin_id);
		$data['page_title'] = 'Preview routing | ' . $admin_name;
		$data['title1'] = 'Routings';
		$data['title2'] = 'Preview routing';
		$data['routings'] = $arr_data;
		$data['admin_id2'] = $admin_id;
			//print_r($arr_data);
		$this -> load -> view('view_routing', $data);
	}

	function publish_routing() {
		$models = $this -> input -> post('routing');
		//print_r($models);

		

		if (!$models) {
			$this -> session -> set_flashdata('error', lang('error_bulk_no_sales'));
			redirect('admin/admin/field_officers');
		}

	

		foreach ($models as $a => $model) {
			print_r(std_format($model['date']));
			print_r($model['admin_id2']);
			
			    $save=array();

				$save['id'] = false;
				$save['admin_id'] = $model['admin_id2'];
				$save['outlet_id'] = $model['outlet_id'];
				$save['date'] = std_format($model['date']);
				//aa
				$this -> Daily_route_model -> save2($save);

			

		}

		redirect('admin/field_officers');

	}





	function delete($id)
	{
		//even though the link isn't displayed for an admin to delete themselves, if they try, this should stop them.
		if ($this->current_admin['id'] == $id)
		{
			$this->session->set_flashdata('error', lang('error_self_delete'));
			redirect('admin/index');	
		}
		
		//delete the user
		$this->auth->delete($id);
		$this->session->set_flashdata('error', 'User Has been Deleted');
		redirect('admin/index');
	}
	function form($id = false)
	{
		force_ssl();
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$data['page_title']		= 'Admins';
		$data['sub_title']	='Admin Form';
		
		//default values are empty if the customer is new
		$data['id']		= '';
		$data['firstname']	= '';
		$data['lastname']	= '';
		$data['email']		= '';
		$data['access']		= '';
		
		if ($id)
		{	
			$this->admin_id		= $id;
			$admin			= $this->auth->get_admin($id);
			//if the administrator does not exist, redirect them to the admin list with an error
			if (!$admin)
			{
				$this->session->set_flashdata('message', lang('admin_not_found'));
				redirect($this->config->item('admin_folder').'/admin/index');
			}
			//set values to db values
			$data['id']			= $admin->id;
			$data['firstname']	= $admin->firstname;
			$data['lastname']	= $admin->lastname;
			$data['email']		= $admin->email;
			$data['access']		= $admin->access;
		}
		
		$this->form_validation->set_rules('firstname', 'lang:firstname', 'trim|max_length[32]');
		$this->form_validation->set_rules('lastname', 'lang:lastname', 'trim|max_length[32]');
		$this->form_validation->set_rules('email', 'lang:email', 'trim|required|valid_email|max_length[128]|callback_check_email');
		$this->form_validation->set_rules('access', 'lang:access', 'trim|required');
		
		//if this is a new account require a password, or if they have entered either a password or a password confirmation
		if ($this->input->post('password') != '' || $this->input->post('confirm') != '' || !$id)
		{
			$this->form_validation->set_rules('password', 'lang:password', 'required|min_length[6]|sha1');
			$this->form_validation->set_rules('confirm', 'lang:confirm_password', 'required|matches[password]');
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view($this->config->item('admin_folder').'/admin_form', $data);
		}
		else
		{
			$save['id']		= $id;
			$save['firstname']	= $this->input->post('firstname');
			$save['lastname']	= $this->input->post('lastname');
			$save['email']		= $this->input->post('email');
			$save['access']		= $this->input->post('access');
			
			if ($this->input->post('password') != '' || !$id)
			{
				$save['password']	= $this->input->post('password');
			}
			
			$this->auth->save($save);
			
			$this->session->set_flashdata('message', 'User Has been Saved');
			
			//go back to the customer list
			redirect($this->config->item('admin_folder').'/admin/index');
		}
	}
	
	function check_email($str)
	{
		$email = $this->auth->check_email($str, $this->admin_id);
		if ($email)
		{
			$this->form_validation->set_message('check_email', lang('error_email_taken'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	 function check_position($flag=0){
    	
		$date = new DateTime('now', new DateTimeZone('Africa/Tunis'));
    	$admin = $this -> admin_session -> userdata('admin');
         $admin_id = $admin['id'];
		 $current_user=$this->auth->get_admin($admin_id);
    	if($flag==1){
    		$save['admin_id']=$admin_id;
			$save['type']='Start Day';
			$save['date']=$date->format('Y-m-d');
			$save['latitude']=$current_user->latitude;
			$save['longitude']=$current_user->longitude;
			$this->Check_position_model->save($save);
    		
    	}else if($flag==2){
    		
			$save['admin_id']=$admin_id;
			$save['type']='Check';
			$save['date']=$date->format('Y-m-d');
			$save['latitude']=$current_user->latitude;
			$save['longitude']=$current_user->longitude;
			$this->Check_position_model->save($save);
			
    	}else{
    		$save['admin_id']=$admin_id;
			$save['type']='End Day';
			$save['date']=$date->format('Y-m-d');
			$save['latitude']=$current_user->latitude;
			$save['longitude']=$current_user->longitude;
			$this->Check_position_model->save($save);
    	}
		redirect($this->config->item('admin_folder').'/dashboard');
    	
    }
	function desactivate($id) {
		$admin = array('id' => $id, 'active' => 0);
		$this ->auth-> save($admin);
		$this->session->set_flashdata('message', 'User has been Desctivated');
		redirect('admin/index');
	}
	function activate($id) {
		$admin = array('id' => $id, 'active' => 1);
		$this ->auth-> save($admin);
		$this->session->set_flashdata('message', 'User has been Activated');
		redirect('admin/index');
	}
	
	
}