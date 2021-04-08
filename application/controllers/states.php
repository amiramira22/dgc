<?php

class States extends CI_Controller {

	//this is used when editing or adding a customer
	var $state_id	= false;	

	function __construct()
	{		
		parent::__construct();

		$this->load->model(array('State_model','Zone_model'));
		$this->load->helper('formatting_helper');
	}
	
	function index($field='id', $by='DESC', $page=0)
	{
		
		
		$data['page_title']	= 'States';
		$data['sub_title']	= 'Manage States';

		$data['states']	= $this->State_model->get_states();
		$this->load->view('states', $data);
	}

	function form($id = false)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$data['page_title']		= 'State Form';
		$data['sub_title']		= 'State Form';

		$data['zones']		= $this->Zone_model->get_zones();
		
		//default values are empty if the state is new
		$data['id']					= '';
		$data['code']			= '';
		$data['name']			= '';
		$data['zone_id']			= '';
	
				
		
		
		
		if ($id)
		{	
			$this->state_id	= $id;
			$state		= $this->State_model->get_state_by_id($id);
			//if the state does not exist, redirect them to the customer list with an error
			if (!$state)
			{
				$this->session->set_flashdata('error', 'error not found');
				redirect('states');
			}
			$data['sub_title']		= 'State Form'.'|'.$state->name;

			//set values to db values
			$data['id']					= $state->id;
			$data['code']			= $state->code;
			$data['name']			= $state->name;
			$data['zone_id']			= $state->zone_id;
		
			
		}
		
		$this->form_validation->set_rules('code', '', 'trim|required|max_length[20]');

		
		
		
				
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('state_form', $data);
		}
		else
		{
			$save['id']		= $id;
			$save['code']	= $this->input->post('code');
			$save['name']	= $this->input->post('name');
			$save['zone_id']	= $this->input->post('zone_id');
		
	
			$this->State_model->save($save);
			
			$this->session->set_flashdata('message', 'State has been added');
			
			//go back to the state list
			redirect('states');
		}
	}
	
	
	function delete($id = false)
	{
		if ($id)
		{	
			$state	= $this->State_model->get_state_by_id($id);
			//if the state does not exist, redirect them to the state list with an error
			if (!$state)
			{
				$this->session->set_flashdata('error', 'error not found');
				redirect('states');
			}
			else
			{
				//if the state is legit, delete them
				$delete	= $this->State_model->delete($id);
				
				$this->session->set_flashdata('error', 'State has been deleted');
				redirect('states');
			}
		}
		else
		{
			//if they do not provide an id send them to the state list page with an error
			$this->session->set_flashdata('error','error not found');
			redirect('states');
		}
	}
	
	
	function get_states_by_zone($zone_id) {
		header('Content-Type: application/x-json; charset=utf-8');
		$states = array();
		foreach ($this -> State_model -> get_states_by_zone($zone_id)->result() as $state) {
			$states[$state -> id] = $state -> name;
		}

		echo(json_encode($states));
	}


}