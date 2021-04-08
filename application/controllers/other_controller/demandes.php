<?php
class Demandes extends CI_Controller
{
	//these are used when editing, adding or deleting an admin
	var $admin_id		= false;
	var $current_admin	= false;
	function __construct()
	{
		parent::__construct();
		//$this->auth->check_access('Admin', true);
		
		//load the admin language file in
		
		$this->current_admin	= $this->session->userdata('admin');
		$this->load->helper('formatting_helper');
		$this -> load -> model(array('Report_model','Demande_model','New_model_model','Competitor_ads_model'));
	}

	function index()
	{
		$data['page_title']	= 'List of Demandes';
		$data['title1']	= 'Demandes';
		$data['title2']	= 'List of Demandes';

if ($this -> auth -> check_access('Admin')){

		$data['demandes']		= $this->Demande_model->get_demandes();
		
}
else {

$current_admin	= $this->admin_session->userdata('admin');
$id=$current_admin['id'];
	$data['demandes']		= $this->Demande_model->get_demande_by_users($id);

}


	$this->load->view($this -> config -> item('admin_folder') . '/demandes', $data);
	}




function send()
{

             $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.capesolution.tn';
            $config['smtp_port'] = 587;
            $config['smtp_user'] = 'henkelbcc@capesolution.tn';
            $config['smtp_pass'] = 'Henkelbcc';
			$config['mailtype'] = 'html';



 $this->load->library('email', $config);

           $this->email->set_newline("\r\n");
	   $this->email->from('henkelbcc@capesolution.tn', 'Henkel BCC');
        $this->email->to('raafet.dhaouadi@esprit.tn');
        $this->email->subject('test');
        $this->email->message('test');
        return $this->email->send();

        echo $this->email->print_debugger();
}


	function Test()
	{



            // Load email library and passing configured values to email library 
           
            
         $this->send('raafet.dhaouadi@esprit.tn','test','test msg');

		$data['page_title']	= 'dashboard';
		$data['title1']	= 'dashboard';
		$data['title2']	= 'dashboard';

    // $this-> Dashboard_model->deletelist();
     $data['users']= $this->Dashboard_model -> get_winners();

//$this->load->view('dashboard', $data);

	}

	



	
	function delete($id)
	{
		//even though the link isn't displayed for an admin to delete themselves, if they try, this should stop them.
		
		print_r($id);
		$this->Demande_model->delete($id);
		$this->session->set_flashdata('message', lang('message_user_deleted'));
		redirect('admin/demandes');
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


function confirm($id)
{
	 $data['page_title']	= 'Tirage au sort';
		$data['title1']	= 'Tirage au sort';
		$data['title2']	= 'CFE';


$demande=$this->Demande_model->get_demande($id);

$demande = array('id' => $id, 'active' => 1);

$this->Demande_model->save($demande);
$demande=$this->Demande_model->get_demande($id);
//print_r($demande);



            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.capesolution.tn';
            $config['smtp_port'] = 587;
            $config['smtp_user'] = 'vacation@capesolution.tn';
            $config['smtp_pass'] = 'vacation2016';
			$config['mailtype'] = 'html';


 $this->load->library('email', $config);

        $this->email->set_newline("\r\n");
	   $this->email->from('vacation@capesolution.tn', 'Samsung');
       // $this->email->to('ibtissem@capesolution.tn','akrem.b@capesolution.tn','raafet.dhaouadi@esprit.tn');
	 
	   $this->email->to('ibtissem@capesolution.tn');
        $current_admin	= $this->admin_session->userdata('admin');
			$id_user=$current_admin['id'];
			$a=$this->Demande_model->get_admin($id_user);

			$image='uploads/certif/'.$demande->image;
			print_r('chemin de l image'.$image);
			
        $this->email->subject('demande de congé confirmé par : '.$a->firstname.' '.$a->lastname);
        $a=$this->Demande_model->get_admin($demande->id_user);
        if($demande->image != '')
        {
        $this->email->message('Mr '.$a->firstname.' '.$a->lastname.' a pris un congé a partir de '.$demande->date_deb. ' à '.$demande->date_deb.' cause de congé '.$demande->type_conge.' Vous trouverez çi joint la certif');
   $this->email->attach($image);
   print_r('test 1');
   }
   else{
   	 $this->email->message('Mr '.$a->firstname.' '.$a->lastname.' a pris un congé a partir de '.$demande->date_deb. ' à '.$demande->date_deb.' cause de congé '.$demande->type_conge.' '.$demande->autorisation);
print_r('test 2');  
  }
     $this->email->send();

     


redirect('admin/demandes');

}

	function form($id = false)
	{
		//force_ssl();
		
	
$this -> load -> helper(array('form', 'date'));
		$this -> load -> library('form_validation');



		
		


		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$data['page_title']		= 'Demande Form';
		$data['title1']	= 'Demandes';
		$data['title2']	= 'Demande Form';
		$config['upload_path'] = 'uploads/certif';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = '10000';
		$config['encrypt_name'] = true;
		$this -> load -> library('upload', $config);
		
		//default values are empty if the customer is new
		$data['id']		= '';
		$data['image'] = '';
		$data['type_conge']	= '';
		$data['autorisation']	= '';
		$data['id_user']	= '';
		$data['date_deb'] = date("Y-m-d");
		$data['date_fin'] = date("Y-m-d");
		
		
		if ($id)
		{	
			$this->demande_id		= $id;

                
				


			$demande			= $this->Demande_model->get_demande($id);
			//if the administrator does not exist, redirect them to the admin list with an error
			if (!$demande)
			{
				$this->session->set_flashdata('message', lang('admin_not_found'));
				redirect('demandes');
			}
			//set values to db values
			$data['id']			= $demande->id;
			$current_admin	= $this->session->userdata('admin');
			$data['id_user']	= $demande->id_user;
			$data['type_conge']	= $demande->type_conge;
			$data['autorisation']	= $demande->autorisation;
			$data['date_deb']		= $demande->date_deb;
			$data['date_fin']		= $demande->date_fin;
			$data['image']		= $demande->image;
		


			
		}
		
	
		$this->form_validation->set_rules('type_conge', 'lang:access', 'trim|required');
		
		//if this is a new account require a password, or if they have entered either a password or a password confirmation
	
		
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view($this->config->item('admin_folder').'/demande_form', $data);
		}
		else
		{
			$uploaded = $this -> upload -> do_upload('image');

			$save['id']		= $id;
			$save['type_conge']	= $this->input->post('type_conge');
			$save['autorisation']	= $this->input->post('autorisation');
			$current_admin	= $this->admin_session->userdata('admin');
			$id_user=$current_admin['id'];
			$save['id_user']	= $id_user;
			print_r($current_admin.'idddddddddddd');
			$save['date_deb']		= $this->input->post('date_deb');
			$save['date_fin']		= $this->input->post('date_fin');
			$save['active']=0;





              if ($uploaded) {
					print_r('uploader  ');
					print_r($data['image']);
					if ($data['image'] != '') {

						print_r('data image  ');
						$file = 'uploads/certif/' . $data['image'];

						//delete the existing file if needed
						if (file_exists($file)) {
							unlink($file);
						}
				
				}

			} else {
				if (!$uploaded) {
					print_r('non uploader  ');
					$data['error'] = $this -> upload -> display_errors();
					//$this -> load -> view($this -> config -> item('admin_folder') . '/car_form', $data);
				//	return;
					//end script here if there is an error
				}
			}

			if ($uploaded) {
				print_r('uploader 2 ');
				$image = $this -> upload -> data();
				print_r($image['file_name']);
				$save['image'] = $image['file_name'];
			}	




			
			$this->Demande_model->save($save);
			
			$this->session->set_flashdata('demande ajouté', 'demande ajouté');
			
			//go back to the customer list
		redirect($this -> config -> item('admin_folder') .'/demandes');
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
		$this ->auth-> save($admin);
		$this->session->set_flashdata('message', lang('message_user_saved'));
		redirect('users');
	}
	function activate($id) {
		$admin = array('id' => $id, 'active' => 1);
		$this ->auth-> save($admin);
		$this->session->set_flashdata('message', lang('message_user_saved'));
		redirect('users');
	}
	
	
}