<?php

class Cities extends CI_Controller {

	//this is used when editing or adding a customer
	var $city_id	= false;	

	function __construct()
	{		
		parent::__construct();

		$this->load->model(array('City_model','State_model','New_model_model','Competitor_ads_model'));
		$this->load->helper('formatting_helper');
		$this->lang->load('city');
		$this->load->library('pagination');

	}
	
	function index()
	{
		
        $data['page_title']	= 'Cities';
		$data['sub_title']	= 'Manage Cities';
		
		//Total row
		
		$config['base_url'] = site_url('cities/index');

        $config['total_rows'] = $this->City_model->count_cities();
        $config['per_page'] = "20";
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = 4;
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
		$data['cities']	= $this->City_model->get_cities( $config['per_page'],$data['page'],'cities.id','DESC');
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('cities', $data);
	}

	function form($id = false)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$data['page_title']		= 'City Form';
		$data['sub_title']		= 'City Form';

		$data['states']		= $this->State_model->get_states();
		//default values are empty if the city is new
		$data['id']					= '';
		$data['code']			= '';
		$data['name']			= '';
		$data['state_id']			= '';
				
		
		
		
		if ($id)
		{	
			$this->city_id	= $id;
			$city		= $this->City_model->get_city_by_id($id);
			if (!$city)
			{
				$this->session->set_flashdata('error', 'error not found');
				redirect('cities');
			}
		$data['sub_title']		= 'City Form'.'|'.$city->name;

			$data['id']					= $city->id;
			$data['code']			= $city->code;
			$data['name']			= $city->name;
			$data['state_id']			= $city->state_id;
			
		}
		
		$this->form_validation->set_rules('code', 'lang:code', 'trim|required|max_length[20]');
		$this->form_validation->set_rules('name', 'lang:name', 'trim|required|max_length[30]');
		
		
		
				
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('city_form', $data);
		}
		else
		{
			$save['id']		= $id;
			$save['code']	= $this->input->post('code');
			$save['name']	= $this->input->post('name');
			$save['state_id']	= $this->input->post('state_id');
	
			$this->City_model->save($save);
			
			$this->session->set_flashdata('message','City has been Added');
			
			redirect('cities');
		}
	}
	
	
	function delete($id = false)
	{
		if ($id)
		{	
			$city	= $this->City_model->get_city_by_id($id);
			if (!$city)
			{
				$this->session->set_flashdata('error', 'Error Not Found');
				redirect('cities');
			}
			else
			{
				$delete	= $this->City_model->delete($id);
				
				$this->session->set_flashdata('error','City has been deleted');
				redirect('cities');
			}
		}
		else
		{
			$this->session->set_flashdata('error', lang('error_not_found'));
			redirect('cities');
		}
	}
	
	
	function get_cities_by_state($state_id) {
		header('Content-Type: application/x-json; charset=utf-8');
		$cities = array();
		foreach ($this -> City_model -> get_cities_by_state($state_id)->result() as $city) {
			$cities[$city -> id] = $city -> name;
		}

		echo(json_encode($cities));
	}


}