<?php
class competitor_ads extends CI_Controller
{
	//these are used when editing, adding or deleting an admin
	var $admin_id		= false;
	var $current_admin	= false;
	function __construct()
	{
		parent::__construct();
		//$this->auth->check_access('Admin', true);
		$this -> load -> helper('form');
		$this -> load -> library('form_validation');
		//load the admin language file in
		$this->load->library('pagination');

		$this->current_admin	= $this->session->userdata('admin');
		
		$this->load->helper('formatting_helper');
		$this -> load -> model(array('Report_model','Brand_model','Admin_model','Competitor_ads_model'));
	}
	
	
	function viewed()
	{
	$ads=$this-> Competitor_ads_model->get_all();
     foreach ($ads as $ad) {
	
       $save['id']=$ad->id;
       $save['active']=1;
       $this-> Competitor_ads_model->save($save);
}
		redirect('competitor_ads');


	}
	function index()
	{

		$week_debut = $this -> input -> post('week_debut');
		$week_end = $this -> input -> post('week_end');

	    $data['week_debut'] = $week_debut;
		$data['week_end'] = $week_end;
        $config['base_url'] = site_url('competitor_ads/index');
		
		//Total row
        $config['total_rows'] = $this->Competitor_ads_model->count_competitor_ads($week_debut, $week_end);
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
		
		
		
	
	if ($this -> auth -> check_access('Field Officer')) {
	$admin = $this -> session -> userdata('admin');
		$admin_id = $admin['id'];
			$data['competitor_adss'] = $this -> Competitor_ads_model -> get_competitor_ads_by_admin($admin_id);
		} else {
			$data['competitor_adss'] = $this->Competitor_ads_model->get_competitor_ads($config["per_page"], $data['page'],$week_debut,$week_end);
}
		
		
	 if($this->auth->check_access('Admin') || $this->auth->check_access('Samsung') ){
    $data['page_title']		= 'Competitor Ads';
     if($week_debut != '' && $week_end!=''){
	$data['sub_title']		= 'Manage Competitor Ads'.' '. $week_debut.'|'.$week_end;
	} else {
		$data['sub_title']		= 'Manage Competitor Ads';

	}
    }else{
    $data['page_title']		= 'Competitor Ads';
		$data['sub_title']		= 'Manage Competitor Ads';

    }
	$data['pagination'] = $this->pagination->create_links();

	$this->load->view('competitor_ads', $data);


}


	function detail($id)
	{
		//we're going to use flash data and redirect() after form submissions to stop people from refreshing and duplicating submissions
		//$this->session->set_flashdata('message', 'this is our message');
		
		$data['page_title']	= 'New Competitor Ads';
		$data['sub_title']	=  'New Competitor Ads'.'|'.'Week '.date("W-Y", strtotime($this->Competitor_ads_model->get_competitor_ads_by_id($id)->date )).'|'.$this->Admin_model->get_admin_name($this->Competitor_ads_model->get_competitor_ads_by_id($id)->admin_id);		
		$data['competitor_ads_detail']	= $this->Competitor_ads_model->get_competitor_ads_by_id($id);		
		$this->load->library('pagination');
		
		
	$this->load->view($this -> config -> item('admin_folder') . '/competitor_ads_detail', $data);
	}
	function form($id = false)
	{
		
	    $data['page_title']	= 'Competitor Ads';

		$data['sub_title']		= 'Competitor Ads  Form';


		$this->load->helper('form');
		$this->load->library('form_validation');
		$this -> load -> helper(array('form', 'date'));

        $names ="";
		$name_array = array();
		if(isset($_FILES['file_ppt'])){
		$count = count($_FILES['file_ppt']['size']);
		}else{
		$count=0;
		}
		foreach($_FILES as $key=>$value)
		for($s=0; $s<=$count-1; $s++) {
		$_FILES['file_ppt']['name']=$value['name'][$s];
		$_FILES['file_ppt']['type']    = $value['type'][$s];
		$_FILES['file_ppt']['tmp_name'] = $value['tmp_name'][$s];
		$_FILES['file_ppt']['error']       = $value['error'][$s];
		$_FILES['file_ppt']['size']    = $value['size'][$s];
				$config['upload_path'] = 'uploads/ads';
				$config['allowed_types'] = '*';
				$config['max_size']    = '200000';
				
		$this->load->library('upload', $config);
		$uploaded = $this->upload->do_upload('file_ppt');

		$data = $this->upload->data();
		
		$names = $names.','.$value['name'][$s];

				}
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		//default values are empty if the customer is new
		$data['id']		= '';
		$data['date'] = '';
		$data['active'] = '';
		$data['title_ads'] = '';
		$data['remark'] = '';
		$data['admin_id']	= '';
     	$data['file_ppt']	= '';
		
		if ($id)
		{	
		
			$this->competitor_ads_id= $id;


			$new_ads			= $this->Competitor_ads_model->get_ads_by_id($id);
			//if the administrator does not exist, redirect them to the admin list with an error
			if (!$new_ads)
			{
				$this->session->set_flashdata('message','competitor not found');
				redirect('new_models');
			}
			//set values to db values/// update:recuperation des variable de la base par id
			
			$data['sub_title']		= 'Competitor Ads  Form'.'|'.$new_ads->title_ads;

			$data['id']			= $new_ads->id;
			$data['title_ads']	= $new_ads->title_ads;

		    $data['date']	= $new_ads->date;
			
			
			$data['remark']	= $new_ads->remark;
			$data['admin_id']		= $new_ads->admin_id;
			$data['active']		= $new_ads->active;


//fin element ajouté
			
		}
		
	
		$this->form_validation->set_rules('title_ads', 'lang:firstname', 'trim|required|max_length[20]');
		
		//if this is a new account require a password, or if they have entered either a password or a password confirmation
	
		
		
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('competitor_ads_form', $data);
		}
		else
		{

			$save['id']		= $id;
			$save['date']	= date('Y-m-d');
			$save['title_ads']		= $this->input->post('title_ads');
			
			
			$save['remark']		= $this->input->post('remark');
			$admin = $this -> session -> userdata('admin');
		    $admin_id = $admin['id'];
			$save['admin_id']		= $admin_id;
			$save['active']=0;

             if ($uploaded) {
				$save['file_ppt'] = $names;
			}else {
				echo $this->upload->display_errors();
			}
				

			

			$this->Competitor_ads_model->save($save);
			
			$this->session->set_flashdata('new_models', 'New_models has been added');
			
			//go back to the customer list
	redirect('competitor_ads');
		}
  }
  
  
	function delete($id)
	{
		//even though the link isn't displayed for an admin to delete themselves, if they try, this should stop them.
		
		$this-> Competitor_ads_model->delete($id);
		$this->session->set_flashdata('message', lang('message_user_deleted'));
		redirect('competitor_ads');
	}
 
	
}