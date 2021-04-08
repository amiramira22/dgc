<?php

class Zones extends CI_Controller {

	//this is used when editing or adding a customer
	var $zone_id	= false;	

	function __construct()
	{		
		parent::__construct();

		$this->load->model(array('Zone_model'));
		$this->load->helper('formatting_helper');
	}
	
	function index()
	{
		//we're going to use flash data and redirect() after form submissions to stop people from refreshing and duplicating submissions
		//$this->session->set_flashdata('message', 'this is our message');
		
		$data['page_title']	= 'Zones';
		$data['sub_title']	= 'Manage Zones';
		$data['zones']	= $this->Zone_model->get_zones();
		$this->load->view($this->config->item('admin_folder').'/zones', $data);
	}

	function form($id = false)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$data['page_title']		= 'Zone Form';
		$data['sub_title']		= 'Zone Form';

		$data['id']					= '';
		$data['code']			= '';
		$data['name']			= '';
		$data['zone_for_chart']			= false;
		
		if ($id)
		{	
			$this->zone_id	= $id;
			$zone		= $this->Zone_model->get_zone_by_id($id);
			if (!$zone)
			{
				$this->session->set_flashdata('error','Not found');
				redirect('zones');
			}
						$data['sub_title']		= 'Zone Form'.'|'.$zone->name;

			$data['id']				= $zone->id;
			$data['code']			= $zone->code;
			$data['name']			= $zone->name;
			$data['zone_for_chart']			= $zone->zone_for_chart;
			
			
		}
		
		$this->form_validation->set_rules('code', 'code', 'trim|required|max_length[32]');
				
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('zone_form', $data);
		}
		else
		{
			$save['id']		= $id;
			$save['code']	= $this->input->post('code');
			$save['name']	= $this->input->post('name');
			$save['zone_for_chart']	= $this->input->post('zone_for_chart');
			
	
			$this->Zone_model->save($save);
			
			$this->session->set_flashdata('message', 'Zone has been saved');
			
			//go back to the zone list
			redirect('zones');
		}
	}
	
	
	function delete($id = false)
	{
		if ($id)
		{	
			$zone	= $this->Zone_model->get_zone_by_id($id);
			//if the zone does not exist, redirect them to the zone list with an error
			if (!$zone)
			{
				$this->session->set_flashdata('error', '');
				redirect('zones');
			}
			else
			{
				//if the zone is legit, delete them
				$delete	= $this->Zone_model->delete($id);
				
				$this->session->set_flashdata('error', 'Zone has been deleted!');
				redirect('zones');
			}
		}
		else
		{
			//if they do not provide an id send them to the zone list page with an error
			$this->session->set_flashdata('error', 'Zone not deleted');
			redirect('zones');
		}
	}
	
	
	


}