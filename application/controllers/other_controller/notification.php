<?php
class notification extends CI_Controller
{
	//these are used when editing, adding or deleting an admin
	var $admin_id		= false;
	var $connected_user_id = false;
	function __construct()
	{
		parent::__construct();
		$this -> load -> helper('form');
		$this -> load -> library('form_validation');
		$this->load->library('pagination');


		$admin = $this -> session -> userdata('admin');
		$this->connected_user_id = $admin['id'];		
		
		$this->load->helper('formatting_helper');
		$this -> load -> model(array('Notification_model','Report_model','New_model_model','Brand_model','Admin_model'));
	}
	

	

	function count_ads()
	{
	
  $notif_new_ads = $this->Notification_model->count_ads_non_actif();
      echo $notif_new_ads;

   }

	function count_models()
	{
	
  $notif_new_models = $this->Notification_model->count_model_non_actif();
      echo  $notif_new_models;

   }
function count_visits()
	{
	
  $notif_new_visit = $this->Notification_model->count_visit_non_actif();
      echo  $notif_new_visit;

   }
 function count_rows_pictures()
	{
	
   $number_row_pictures = $this->Notification_model->list_pictures_non_actif();
   echo  sizeof($number_row_pictures);

   }



   function count_total_notification()
	{
	
  $notif_new_ads = $this->Notification_model->count_ads_non_actif();
  $notif_new_visit = $this->Notification_model->count_visit_non_actif();
   $notif_new_ads = $this->Notification_model->count_ads_non_actif();
 $number_row_pictures = $this->Notification_model->list_pictures_non_actif();

$total_notification = $notif_new_ads + $notif_new_visit + $notif_new_ads + sizeof($number_row_pictures);
      echo  $total_notification;

   }


	function new_models_viewed()
	{
	

	$models=$this-> Notification_model->get_models_non_actif();

        foreach ($models as $model) {
	
        $save['id']=$model->id;
        $save['active']=1;
        $this-> Notification_model->save($save);
                    }
		redirect('new_models');


	}
	function ads_viewed()
	{
	

	$ads=$this-> Notification_model->get_ads_non_actif();

        foreach ($ads as $ad) {
	
        $save['id']=$ad->id;
        $save['active']=1;
        $this-> Notification_model->save($save);
                    }
		redirect('competitor_ads');


	}
	

	function visits_viewed()
	{
	

	$visits=$this-> Notification_model->get_visits_non_actif();

        foreach ($visits as $visit) {
	
        $save['id']=$visit->id;
        $save['active']=1;
        $this-> Notification_model->save($save);
                    }
		redirect('weekly_visits');


	}

	



	function pictures_viewed($id)
	{
		//get picture non actif  by visit_id
		$visit_picture_selected=$this->Notification_model->get_pictures_non_actif($id);
		

        foreach ($visit_picture_selected as $picture) {
	
        $save['id']= $picture->id;
        $save['active']=1;
        $this-> Notification_model->save($save);

       
                    }

        redirect('weekly_visits/report/'.$visit_picture_selected[0]->visit_id);


	}
	function listpic()
	{
	     
   

 $results = $this->Notification_model->list_pictures_non_actif();
        echo json_encode($results);
	   
}


}